<?php

use App\Services\BehaviorAnalyticsService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('analytics:refresh', function (BehaviorAnalyticsService $analytics) {
    $analytics->calculateAll();
    $this->info('Đã cập nhật điểm khách hàng và dữ liệu gợi ý.');
})->purpose('Recalculate customer analytics and recommendation data');

Artisan::command('analytics:send-suggestions {--limit=50} {--force}', function (BehaviorAnalyticsService $analytics) {
    $limit = (int) $this->option('limit');
    $force = (bool) $this->option('force');

    $sent = $analytics->autoSendSuggestionEmails($limit, $force);
    $this->info("Đã xử lý gửi {$sent} email gợi ý sản phẩm.");
})->purpose('Send AI suggestion emails to customers');

Schedule::command('analytics:refresh')->hourly();
Schedule::command('analytics:send-suggestions --limit=50')->twiceDaily(9, 16);
