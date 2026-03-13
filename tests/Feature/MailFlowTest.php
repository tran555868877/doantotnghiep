<?php

namespace Tests\Feature;

use App\Mail\ContactSupportMail;
use App\Mail\OrderPlacedAdminMail;
use App\Mail\OrderPlacedCustomerMail;
use App\Mail\SuggestionProductsMail;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\EmailLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_sends_support_mail(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name' => 'Nguyen Van A',
            'phone' => '0912345678',
            'email' => 'khach@example.com',
            'topic' => 'Hỗ trợ đơn hàng',
            'message' => 'Mình cần hỗ trợ kiểm tra trạng thái đơn.',
        ]);

        $response->assertRedirect(route('contact'));
        Mail::assertSent(ContactSupportMail::class);
    }

    public function test_checkout_sends_order_and_suggestion_emails(): void
    {
        Mail::fake();

        $user = User::create([
            'name' => 'Khach Thu',
            'email' => 'khachthu@gmail.com',
            'phone' => '0911222333',
            'role' => 'customer',
            'status' => 'active',
            'password' => Hash::make('123456'),
        ]);

        $category = Category::create([
            'name' => 'Sữa công thức',
            'slug' => 'sua-cong-thuc',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Sữa công thức A',
            'slug' => 'sua-cong-thuc-a',
            'sku' => 'SKU-SUA-A',
            'price' => 250000,
            'stock' => 20,
            'is_active' => true,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 250000,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'customer_name' => 'Khach Thu',
            'customer_email' => 'khachthu@gmail.com',
            'customer_phone' => '0911222333',
            'shipping_address' => '123 Duong ABC, Q7, TP.HCM',
            'notes' => 'Giao giờ hành chính',
            'payment_method' => 'cod',
        ]);

        $response->assertRedirect();
        Mail::assertSent(OrderPlacedCustomerMail::class);
        Mail::assertSent(OrderPlacedAdminMail::class);
        Mail::assertSent(SuggestionProductsMail::class);
    }

    public function test_ai_auto_command_sends_suggestion_mail_and_write_log(): void
    {
        Mail::fake();

        $user = User::create([
            'name' => 'User Demo',
            'email' => 'demo@example.com',
            'phone' => '0909000111',
            'role' => 'customer',
            'status' => 'active',
            'password' => Hash::make('123456'),
        ]);

        $category = Category::create([
            'name' => 'Đồ chơi',
            'slug' => 'do-choi',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Đồ chơi thông minh',
            'slug' => 'do-choi-thong-minh',
            'sku' => 'SKU-TOY-1',
            'price' => 199000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->artisan('analytics:send-suggestions --limit=10 --force')
            ->assertExitCode(0);

        Mail::assertSent(SuggestionProductsMail::class);
        $this->assertTrue(EmailLog::query()->where('user_id', $user->id)->where('status', 'sent')->exists());
    }
}
