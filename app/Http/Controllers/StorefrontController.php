<?php

namespace App\Http\Controllers;

use App\Mail\ContactSupportMail;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Services\BehaviorAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class StorefrontController extends Controller
{
    public function index(Request $request, BehaviorAnalyticsService $analytics)
    {
        if ($request->user()) {
            $analytics->calculateForUser($request->user());
        }

        return view('storefront.home', [
            'banners' => Banner::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'featuredCategories' => Category::query()
                ->where('is_featured', true)
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->with('children')
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
            'featuredProducts' => Product::query()->where('is_featured', true)->where('is_active', true)->latest()->limit(8)->get(),
            'bestSellers' => Product::query()->where('is_active', true)->orderByDesc('sold_count')->limit(8)->get(),
            'recommendedProducts' => $analytics->recommendedProducts($request->user(), 8),
            'posts' => \App\Models\Post::query()->where('status', 'published')->latest('published_at')->limit(3)->get(),
        ]);
    }

    public function shop(Request $request, BehaviorAnalyticsService $analytics)
    {
        $query = Product::query()->with('category')->where('is_active', true);
        $selectedCategories = collect((array) $request->input('categories', []))
            ->when($request->filled('category'), fn ($items) => $items->push((string) $request->string('category')))
            ->filter()
            ->unique()
            ->values();

        if ($selectedCategories->isNotEmpty()) {
            $categories = Category::query()
                ->whereIn('slug', $selectedCategories)
                ->with('children')
                ->get();

            if ($categories->isNotEmpty()) {
                $ids = $categories
                    ->flatMap(fn ($category) => $category->children->pluck('id')->prepend($category->id))
                    ->unique()
                    ->values();

                $query->whereIn('category_id', $ids);
            }
        }

        $selectedBrands = collect((array) $request->input('brands', []))->filter()->values();
        if ($selectedBrands->isNotEmpty()) {
            $query->whereIn('brand', $selectedBrands);
        }

        $selectedAgeGroups = collect((array) $request->input('ages', []))->filter()->values();
        if ($selectedAgeGroups->isNotEmpty()) {
            $query->whereIn('age_group', $selectedAgeGroups);
        }

        $selectedPrices = collect((array) $request->input('prices', []))->filter()->values();
        if ($selectedPrices->isNotEmpty()) {
            $query->where(function ($builder) use ($selectedPrices) {
                foreach ($selectedPrices as $range) {
                    match ($range) {
                        'under-200' => $builder->orWhereRaw('COALESCE(sale_price, price) < 200000'),
                        '200-500' => $builder->orWhereRaw('COALESCE(sale_price, price) BETWEEN 200000 AND 500000'),
                        '500-1000' => $builder->orWhereRaw('COALESCE(sale_price, price) BETWEEN 500001 AND 1000000'),
                        'over-1000' => $builder->orWhereRaw('COALESCE(sale_price, price) > 1000000'),
                        default => null,
                    };
                }
            });
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->string('q'));
            $query->where(fn ($builder) => $builder
                ->where('name', 'like', "%{$keyword}%")
                ->orWhere('brand', 'like', "%{$keyword}%")
                ->orWhere('short_description', 'like', "%{$keyword}%"));

            $analytics->recordSearch($keyword, (clone $query)->count(), [
                'user_id' => optional($request->user())->id,
                'session_id' => $request->session()->get('tracking_session_id'),
            ]);
        }

        return view('storefront.shop', [
            'products' => $query->latest()->paginate(12)->withQueryString(),
            'categories' => Category::query()->whereNull('parent_id')->where('is_active', true)->with('children')->get(),
            'brands' => Product::query()->where('is_active', true)->distinct()->orderBy('brand')->pluck('brand'),
            'ageGroups' => Product::query()->where('is_active', true)->distinct()->orderBy('age_group')->pluck('age_group'),
            'priceRanges' => [
                'under-200' => 'Dưới 200.000đ',
                '200-500' => '200.000đ - 500.000đ',
                '500-1000' => '500.000đ - 1.000.000đ',
                'over-1000' => 'Trên 1.000.000đ',
            ],
            'filters' => [
                'q' => $request->input('q', ''),
                'categories' => $selectedCategories->all(),
                'brands' => $selectedBrands->all(),
                'ages' => $selectedAgeGroups->all(),
                'prices' => $selectedPrices->all(),
            ],
        ]);
    }

    public function category(Category $category)
    {
        $ids = $category->children()->pluck('id')->prepend($category->id);

        return view('storefront.shop', [
            'products' => Product::query()->whereIn('category_id', $ids)->where('is_active', true)->paginate(12),
            'categories' => Category::query()->whereNull('parent_id')->where('is_active', true)->with('children')->get(),
            'brands' => Product::query()->where('is_active', true)->distinct()->orderBy('brand')->pluck('brand'),
            'ageGroups' => Product::query()->where('is_active', true)->distinct()->orderBy('age_group')->pluck('age_group'),
            'priceRanges' => [
                'under-200' => 'Dưới 200.000đ',
                '200-500' => '200.000đ - 500.000đ',
                '500-1000' => '500.000đ - 1.000.000đ',
                'over-1000' => 'Trên 1.000.000đ',
            ],
            'filters' => [
                'q' => '',
                'categories' => [$category->slug],
                'brands' => [],
                'ages' => [],
                'prices' => [],
            ],
            'currentCategory' => $category,
        ]);
    }

    public function product(Request $request, Product $product, BehaviorAnalyticsService $analytics)
    {
        abort_unless($product->is_active, 404);

        $analytics->recordProductView($product, [
            'user_id' => optional($request->user())->id,
            'session_id' => $request->session()->get('tracking_session_id'),
            'source' => 'product_detail',
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        if ($request->user()) {
            $analytics->calculateForUser($request->user());
        }

        return view('storefront.product', [
            'product' => $product->load('category'),
            'relatedProducts' => Product::query()
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->limit(4)
                ->get(),
        ]);
    }

    public function about()
    {
        return view('storefront.about', [
            'stats' => [
                'products' => Product::query()->where('is_active', true)->count(),
                'categories' => Category::query()->where('is_active', true)->count(),
                'customers' => \App\Models\User::query()->where('role', 'customer')->count(),
                'orders' => \App\Models\Order::query()->count(),
            ],
            'featuredCategories' => Category::query()
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(6)
                ->get(),
            'latestPosts' => Post::query()->where('status', 'published')->latest('published_at')->limit(3)->get(),
        ]);
    }

    public function contact()
    {
        return view('storefront.contact', [
            'branches' => [
                [
                    'name' => 'BabyMart Plus Quận 7',
                    'address' => '123 Nguyễn Văn Linh, Quận 7, TP.HCM',
                    'hours' => '08:00 - 22:00',
                    'phone' => '0909 123 456',
                ],
                [
                    'name' => 'BabyMart Plus Thủ Đức',
                    'address' => '25 Võ Văn Ngân, Thủ Đức, TP.HCM',
                    'hours' => '08:00 - 21:30',
                    'phone' => '0909 223 556',
                ],
                [
                    'name' => 'BabyMart Plus Bình Dương',
                    'address' => '88 Đại lộ Bình Dương, Thuận An, Bình Dương',
                    'hours' => '08:00 - 21:30',
                    'phone' => '0909 323 656',
                ],
            ],
            'topCategories' => Category::query()
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
        ]);
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'regex:/^(0|\\+84)[0-9]{9,10}$/'],
                'email' => ['required', 'email', 'max:255'],
                'topic' => ['required', 'string', 'max:255'],
                'message' => ['required', 'string', 'max:3000'],
            ]
        );

        $adminEmail = env('ADMIN_NOTIFICATION_EMAIL', 'admin@gmail.com');
        Mail::to($adminEmail)->send(new ContactSupportMail($data));

        return redirect()->route('contact')->with('success', 'Đã gửi liên hệ thành công. Bộ phận chăm sóc khách hàng sẽ phản hồi sớm.');
    }

    public function faq()
    {
        return view('storefront.faq', [
            'faqs' => [
                [
                    'question' => 'Làm sao chọn đúng sản phẩm theo độ tuổi của bé?',
                    'answer' => 'Bạn có thể dùng bộ lọc độ tuổi ngay ở trang Cửa hàng. Mỗi sản phẩm đều có thông tin độ tuổi và hướng dẫn sử dụng rõ ràng.',
                ],
                [
                    'question' => 'Đặt hàng bao lâu thì nhận được?',
                    'answer' => 'Khu vực nội thành nhận hàng trong ngày, các tỉnh thành khác từ 1 đến 3 ngày làm việc tùy khu vực giao nhận.',
                ],
                [
                    'question' => 'Sản phẩm có đảm bảo chính hãng không?',
                    'answer' => 'Toàn bộ sản phẩm tại hệ thống đều được nhập chính hãng từ nhà phân phối uy tín, có hóa đơn và thông tin xuất xứ rõ ràng.',
                ],
                [
                    'question' => 'Chính sách đổi trả áp dụng như thế nào?',
                    'answer' => 'Bạn được đổi trả trong 7 ngày với sản phẩm lỗi do nhà sản xuất hoặc giao sai sản phẩm theo đơn hàng.',
                ],
                [
                    'question' => 'Có tư vấn dinh dưỡng và chăm sóc mẹ sau sinh không?',
                    'answer' => 'Đội ngũ tư vấn viên hỗ trợ trực tiếp qua hotline và fanpage để gợi ý sản phẩm phù hợp theo nhu cầu thực tế.',
                ],
            ],
            'hotPosts' => Post::query()->where('status', 'published')->latest('published_at')->limit(3)->get(),
        ]);
    }

    public function account(Request $request, BehaviorAnalyticsService $analytics)
    {
        $user = $request->user();
        $score = $user->customerScore ?: $analytics->calculateForUser($user);

        return view('storefront.account', [
            'orders' => $user->orders()->with('items')->latest()->paginate(6)->withQueryString(),
            'score' => $score,
            'recommendedProducts' => $analytics->recommendedProducts($user, 6),
        ]);
    }

    public function updateAccountProfile(Request $request)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'regex:/^(0|\\+84)[0-9]{9,10}$/'],
                'address' => ['nullable', 'string', 'max:500'],
                'gender' => ['nullable', 'in:male,female,other'],
                'date_of_birth' => ['nullable', 'date'],
            ],
            [
                'name.required' => 'Vui lòng nhập họ và tên.',
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'phone.regex' => 'Số điện thoại chưa hợp lệ (ví dụ: 09xxxxxxxx hoặc +84xxxxxxxxx).',
            ]
        );

        $request->user()->update($data);

        return redirect()->route('account', ['tab' => 'profile'])->with('success', 'Cập nhật thông tin tài khoản thành công.');
    }

    public function updateAccountPassword(Request $request)
    {
        $data = $request->validate(
            [
                'current_password' => ['required'],
                'password' => ['required', 'confirmed', Password::min(6)],
            ],
            [
                'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
                'password.required' => 'Vui lòng nhập mật khẩu mới.',
                'password.confirmed' => 'Xác nhận mật khẩu chưa khớp.',
            ]
        );

        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('account', ['tab' => 'password'])->with('success', 'Đổi mật khẩu thành công.');
    }
}
