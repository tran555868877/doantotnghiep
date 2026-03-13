<?php

namespace App\Services;

use App\Mail\SuggestionProductsMail;
use App\Models\BehaviorEvent;
use App\Models\CustomerScore;
use App\Models\EmailLog;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\SearchKeyword;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BehaviorAnalyticsService
{
    public function record(string $type, array $data = []): void
    {
        BehaviorEvent::create([
            'user_id' => $data['user_id'] ?? null,
            'product_id' => $data['product_id'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'session_id' => $data['session_id'] ?? null,
            'event_type' => $type,
            'source' => $data['source'] ?? null,
            'search_keyword' => $data['search_keyword'] ?? null,
            'event_value' => $data['event_value'] ?? null,
            'meta' => isset($data['meta']) ? json_encode($data['meta'], JSON_UNESCAPED_UNICODE) : null,
            'ip_address' => $data['ip_address'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'occurred_at' => now(),
        ]);
    }

    public function recordProductView(Product $product, array $data): void
    {
        ProductView::create([
            'user_id' => $data['user_id'] ?? null,
            'product_id' => $product->id,
            'session_id' => $data['session_id'] ?? null,
            'viewed_at' => now(),
        ]);

        $product->increment('view_count');

        $this->record('product_view', array_merge($data, [
            'product_id' => $product->id,
            'category_id' => $product->category_id,
            'event_value' => $product->final_price,
        ]));
    }

    public function recordSearch(string $keyword, int $results, array $data): void
    {
        SearchKeyword::create([
            'user_id' => $data['user_id'] ?? null,
            'session_id' => $data['session_id'] ?? null,
            'keyword' => $keyword,
            'result_count' => $results,
            'searched_at' => now(),
        ]);

        $this->record('search', array_merge($data, [
            'search_keyword' => $keyword,
            'event_value' => $results,
        ]));
    }

    public function calculateAll(): void
    {
        User::query()->where('role', 'customer')->get()->each(fn ($user) => $this->calculateForUser($user));
    }

    public function calculateForUser(User $user): CustomerScore
    {
        $events = $user->behaviorEvents()->get();
        $visits = $events->where('event_type', 'visit')->count();
        $views = $events->where('event_type', 'product_view')->count();
        $searches = $events->where('event_type', 'search')->count();
        $addToCart = $events->where('event_type', 'add_to_cart')->count();
        $purchases = $events->where('event_type', 'purchase')->count();
        $purchaseAmount = (float) $events->where('event_type', 'purchase')->sum('event_value');
        $lastPurchase = $user->orders()->latest('ordered_at')->first();
        $daysSinceLastPurchase = $lastPurchase ? now()->diffInDays($lastPurchase->ordered_at) : 60;

        $engagement = ($visits * 1.2) + ($views * 2.0) + ($searches * 2.5) + ($addToCart * 4.5);
        $purchaseScore = ($purchases * 10) + ($purchaseAmount / 200000);

        $z = -2.4
            + (0.18 * $visits)
            + (0.12 * $views)
            + (0.3 * $searches)
            + (0.55 * $addToCart)
            + (0.65 * $purchases)
            + (0.000001 * $purchaseAmount)
            - (0.05 * min($daysSinceLastPurchase, 60));

        $probability = round((1 / (1 + exp(-$z))) * 100, 2);

        $segment = match (true) {
            $probability >= 75 => 'vip',
            $probability >= 50 => 'warm',
            $probability >= 30 => 'potential',
            default => 'cold',
        };

        $favoriteCategoryId = $events
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->sortByDesc(fn ($group) => $group->count())
            ->keys()
            ->first();

        $recommendedIds = Product::query()
            ->when($favoriteCategoryId, fn ($query) => $query->where('category_id', $favoriteCategoryId))
            ->where('is_active', true)
            ->orderByDesc('sold_count')
            ->orderByDesc('view_count')
            ->limit(6)
            ->pluck('id')
            ->implode(',');

        return CustomerScore::updateOrCreate(
            ['user_id' => $user->id],
            [
                'favorite_category_id' => $favoriteCategoryId,
                'engagement_score' => round($engagement, 2),
                'purchase_score' => round($purchaseScore, 2),
                'retention_probability' => $probability,
                'segment' => $segment,
                'recommended_product_ids' => $recommendedIds,
                'calculated_at' => now(),
            ]
        );
    }

    public function recommendedProducts(?User $user, int $limit = 6)
    {
        if (! $user || ! $user->customerScore) {
            return Product::query()
                ->where('is_active', true)
                ->orderByDesc('sold_count')
                ->orderByDesc('is_featured')
                ->limit($limit)
                ->get();
        }

        $ids = $user->customerScore->recommendedProductIds();

        if (! count($ids)) {
            return Product::query()->where('is_active', true)->limit($limit)->get();
        }

        return Product::query()
            ->whereIn('id', $ids)
            ->orderByDesc('is_featured')
            ->limit($limit)
            ->get();
    }

    public function queueSuggestionEmail(User $user, string $reason = 'system'): EmailLog
    {
        $score = $this->calculateForUser($user);
        $products = $this->recommendedProducts($user, 4)->pluck('name')->implode(', ');

        return EmailLog::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'subject' => 'Gợi ý sản phẩm phù hợp cho bạn',
            'status' => 'queued',
            'payload' => json_encode([
                'reason' => $reason,
                'segment' => $score->segment,
                'retention_probability' => $score->retention_probability,
                'products' => $products,
            ], JSON_UNESCAPED_UNICODE),
            'sent_at' => now(),
        ]);
    }

    public function sendSuggestionEmail(User $user, string $reason = 'manual'): bool
    {
        $score = $this->calculateForUser($user);
        $products = $this->recommendedProducts($user, 4);

        if ($products->isEmpty()) {
            return false;
        }

        try {
            Mail::to($user->email)->send(new SuggestionProductsMail($user, $score, $products, $reason));

            EmailLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'subject' => 'Gợi ý sản phẩm dành riêng cho bạn',
                'status' => 'sent',
                'payload' => json_encode([
                    'reason' => $reason,
                    'segment' => $score->segment,
                    'retention_probability' => $score->retention_probability,
                    'products' => $products->pluck('name')->all(),
                ], JSON_UNESCAPED_UNICODE),
                'sent_at' => now(),
            ]);

            return true;
        } catch (Throwable $exception) {
            report($exception);

            EmailLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'subject' => 'Gợi ý sản phẩm dành riêng cho bạn',
                'status' => 'failed',
                'payload' => json_encode([
                    'reason' => $reason,
                    'error' => $exception->getMessage(),
                ], JSON_UNESCAPED_UNICODE),
                'sent_at' => now(),
            ]);

            return false;
        }
    }

    public function autoSendSuggestionEmails(int $limit = 50, bool $force = false): int
    {
        $sent = 0;

        $users = User::query()
            ->where('role', 'customer')
            ->whereNotNull('email')
            ->with('customerScore')
            ->limit($limit)
            ->get();

        foreach ($users as $user) {
            if (! $force) {
                $alreadySentRecently = EmailLog::query()
                    ->where('user_id', $user->id)
                    ->where('subject', 'Gợi ý sản phẩm dành riêng cho bạn')
                    ->where('status', 'sent')
                    ->where('created_at', '>=', now()->subDay())
                    ->exists();

                if ($alreadySentRecently) {
                    continue;
                }
            }

            if ($this->sendSuggestionEmail($user, 'auto_campaign')) {
                $sent++;
            }
        }

        return $sent;
    }
}
