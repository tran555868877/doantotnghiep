<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use App\Services\BehaviorAnalyticsService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $productImagePool = collect(range(1, 31))
            ->map(fn ($index) => '/babymart-assets/img/product/post-card1-'.$index.'.png')
            ->all();

        $categoryImagePool = [
            '/babymart-assets/img/category/category_card1_1.png',
            '/babymart-assets/img/category/category_card1_2.png',
            '/babymart-assets/img/category/category_card1_3.png',
            '/babymart-assets/img/category/category_card1_4.png',
            '/babymart-assets/img/category/category_card1_5.png',
            '/babymart-assets/img/category/category_card1_6.png',
            '/babymart-assets/img/category/category_card2_1.png',
            '/babymart-assets/img/category/category_card2_2.png',
            '/babymart-assets/img/category/category_card2_3.png',
            '/babymart-assets/img/category/category_card2_4.png',
        ];

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'phone' => '0909000001',
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make('123456'),
        ]);

        $customerSeeds = [
            ['Nguyễn Minh Châu', 'chau@example.com'],
            ['Trần Thu Hà', 'ha@example.com'],
            ['Lê Gia Hân', 'han@example.com'],
            ['Phạm Bảo Ngân', 'ngan@example.com'],
            ['Đỗ Khánh Linh', 'linh@example.com'],
            ['Võ Ngọc Trinh', 'trinh@example.com'],
            ['Nguyễn Thảo My', 'my@example.com'],
            ['Trần Bảo Vy', 'vy@example.com'],
            ['Lê Thu Trang', 'trang@example.com'],
            ['Phan Kim Ngân', 'kimngan@example.com'],
            ['Nguyễn Hoàng Anh', 'hoanganh@example.com'],
            ['Trương Diễm Quỳnh', 'quynh@example.com'],
            ['Bùi Gia Bảo', 'giabao@example.com'],
            ['Phạm Thanh Nhã', 'thanhnha@example.com'],
            ['Ngô Ý Nhi', 'ynhi@example.com'],
            ['Đặng Bảo Trân', 'baotran@example.com'],
            ['Nguyễn Hồng Hạnh', 'honghanh@example.com'],
            ['Lê Minh Thư', 'minhthu@example.com'],
            ['Võ Bảo Châu', 'baochau@example.com'],
            ['Trần Gia Linh', 'gialinh@example.com'],
        ];

        $customers = collect($customerSeeds)->map(function ($row, $index) {
            return User::create([
                'name' => $row[0],
                'email' => $row[1],
                'phone' => '0909'.str_pad((string) ($index + 2), 6, '0', STR_PAD_LEFT),
                'role' => 'customer',
                'status' => 'active',
                'password' => Hash::make('123456'),
                'address' => collect([
                    'Quận 7, TP.HCM',
                    'Thủ Đức, TP.HCM',
                    'Biên Hòa, Đồng Nai',
                    'Dĩ An, Bình Dương',
                    'Gò Vấp, TP.HCM',
                ])->random(),
            ]);
        });

        $tree = [
            'Sữa và thực phẩm' => ['Sữa công thức', 'Bột ăn dặm', 'Bánh ăn vặt', 'Nước trái cây', 'Vitamin cho bé'],
            'Tã bỉm và chăm sóc' => ['Tã dán', 'Tã quần', 'Khăn ướt', 'Tắm gội', 'Kem hăm'],
            'Đồ dùng cho bé' => ['Bình sữa', 'Máy hút sữa', 'Xe đẩy', 'Ghế ăn dặm', 'Máy tiệt trùng'],
            'Đồ chơi và học tập' => ['Đồ chơi phát triển', 'Sách vải', 'Lego', 'Bút màu', 'Bảng học chữ cái'],
            'Thời trang mẹ và bé' => ['Quần áo sơ sinh', 'Váy bé gái', 'Set đồ bé trai', 'Phụ kiện', 'Đồ ngủ'],
            'Chăm sóc cho mẹ' => ['Sữa cho mẹ bầu', 'Máy massage', 'Dụng cụ sau sinh', 'Túi trữ sữa', 'Mỹ phẩm cho mẹ'],
            'Phòng ngủ cho bé' => ['Nôi cũi', 'Chăn gối', 'Mùng mền', 'Đèn ngủ', 'Camera theo dõi'],
        ];

        $allLeafCategories = collect();

        foreach (array_values(array_keys($tree)) as $parentIndex => $parentName) {
            $parent = Category::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
                'image' => $categoryImagePool[array_rand($categoryImagePool)],
                'icon' => $categoryImagePool[array_rand($categoryImagePool)],
                'age_group' => '0-6 tuổi',
                'description' => 'Tổng hợp sản phẩm '.$parentName.' chính hãng, giá tốt và phù hợp nhu cầu chăm sóc hằng ngày.',
                'sort_order' => $parentIndex + 1,
                'is_featured' => true,
                'is_active' => true,
            ]);

            foreach ($tree[$parentName] as $childIndex => $childName) {
                $child = Category::create([
                    'parent_id' => $parent->id,
                    'name' => $childName,
                    'slug' => Str::slug($parentName.' '.$childName),
                    'image' => $categoryImagePool[array_rand($categoryImagePool)],
                    'icon' => $categoryImagePool[array_rand($categoryImagePool)],
                    'age_group' => collect(['0-6 tháng', '6-12 tháng', '1-3 tuổi', '3-6 tuổi', 'Mẹ bầu và sau sinh'])->random(),
                    'description' => 'Nhóm '.$childName.' đa dạng mẫu mã, dễ chọn theo nhu cầu và độ tuổi.',
                    'sort_order' => $childIndex + 1,
                    'is_featured' => $childIndex < 3,
                    'is_active' => true,
                ]);

                $allLeafCategories->push($child);
            }
        }

        Banner::insert([
            [
                'title' => 'Chăm sóc mẹ và bé toàn diện',
                'subtitle' => 'Hàng ngàn sản phẩm chính hãng cho gia đình trẻ',
                'button_text' => 'Mua ngay',
                'button_link' => '/shop',
                'image' => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?q=80&w=1200&auto=format&fit=crop',
                'theme' => 'sunrise',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Ưu đãi cho bé yêu',
                'subtitle' => 'Combo tiết kiệm thay đổi mỗi tuần',
                'button_text' => 'Xem ưu đãi',
                'button_link' => '/shop',
                'image' => 'https://images.unsplash.com/photo-1543340903-dc1d4ed0cce7?q=80&w=1200&auto=format&fit=crop',
                'theme' => 'peach',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Đồ chơi phát triển trí tuệ',
                'subtitle' => 'Gợi ý theo từng độ tuổi cho bé',
                'button_text' => 'Khám phá',
                'button_link' => '/shop',
                'image' => 'https://images.unsplash.com/photo-1516627145497-ae6968895b74?q=80&w=1200&auto=format&fit=crop',
                'theme' => 'mint',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $namePool = [
            'Sữa công thức cao cấp', 'Sữa non tăng sức đề kháng', 'Bột ăn dặm hữu cơ', 'Bánh gạo cho bé', 'Nước ép trái cây',
            'Vitamin tổng hợp', 'Tã dán mềm mại', 'Tã quần thoáng khí', 'Khăn ướt kháng khuẩn', 'Sữa tắm em bé',
            'Kem chống hăm', 'Bình sữa PPSU', 'Máy hút sữa điện đôi', 'Xe đẩy siêu nhẹ', 'Ghế ăn dặm đa năng',
            'Máy tiệt trùng UV', 'Đồ chơi xếp hình', 'Sách vải phát âm', 'Lego mini', 'Bút màu không độc hại',
            'Bảng học chữ cái', 'Set body cotton', 'Váy công chúa', 'Set đồ bé trai', 'Nón len em bé',
            'Đồ ngủ cotton', 'Sữa cho mẹ bầu', 'Máy massage mini', 'Dụng cụ chăm sóc sau sinh', 'Túi trữ sữa',
            'Mỹ phẩm cho mẹ', 'Nôi cũi đa năng', 'Chăn gối lụa', 'Mùng mền cao cấp', 'Đèn ngủ thông minh',
            'Camera theo dõi', 'Máy hâm sữa', 'Ty ngậm silicone', 'Yếm ăn chống thấm', 'Xe chòi chân',
        ];

        $brands = ['Pigeon', 'Aptamil', 'Merries', 'Huggies', 'HiPP', 'Avent', 'Combi', 'Moony', 'Bobby', 'Mamamy', 'Medela', 'Spectra'];
        $origins = ['Việt Nam', 'Nhật Bản', 'Hàn Quốc', 'Đức', 'Úc', 'Mỹ', 'Thái Lan'];
        $ageGroups = ['0-6 tháng', '6-12 tháng', '1-3 tuổi', '3-6 tuổi', 'Mẹ bầu', 'Sau sinh'];
        $imagePool = [
            '/babymart-assets/img/hero/hero-thumb2-2.png',
            '/babymart-assets/img/hero/hero-thumb2-3.png',
            '/babymart-assets/img/hero/hero-thumb3-1.png',
            '/babymart-assets/img/normal/ad1-1.png',
            '/babymart-assets/img/normal/ad1-2.png',
        ];

        foreach ($allLeafCategories as $category) {
            foreach (range(1, 12) as $index) {
                $baseName = $namePool[array_rand($namePool)];
                $brand = $brands[array_rand($brands)];
                $productName = $baseName.' '.$brand.' '.$index;
                $price = fake()->numberBetween(69000, 1490000);
                $salePrice = fake()->boolean(65) ? fake()->numberBetween((int) ($price * 0.65), (int) ($price * 0.92)) : null;
                $imageBase = fake()->numberBetween(1, 31);
                $imageStep1 = (($imageBase + fake()->numberBetween(1, 2) - 1) % 31) + 1;
                $imageStep2 = (($imageBase + fake()->numberBetween(3, 5) - 1) % 31) + 1;
                $imageStep3 = (($imageBase + fake()->numberBetween(6, 8) - 1) % 31) + 1;
                $thumbnailImage = '/babymart-assets/img/product/post-card1-'.$imageBase.'.png';

                Product::create([
                    'category_id' => $category->id,
                    'name' => $productName,
                    'slug' => Str::slug($productName).'-'.Str::random(4),
                    'sku' => 'SKU-'.strtoupper(Str::random(10)),
                    'brand' => $brand,
                    'origin_country' => $origins[array_rand($origins)],
                    'age_group' => $ageGroups[array_rand($ageGroups)],
                    'unit' => 'sản phẩm',
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'stock' => fake()->numberBetween(30, 250),
                    'sold_count' => fake()->numberBetween(10, 350),
                    'view_count' => fake()->numberBetween(30, 1500),
                    'rating' => fake()->randomFloat(1, 4, 5),
                    'thumbnail' => $thumbnailImage,
                    'gallery' => [
                        '/babymart-assets/img/product/post-card1-'.$imageStep1.'.png',
                        '/babymart-assets/img/product/post-card1-'.$imageStep2.'.png',
                        '/babymart-assets/img/product/post-card1-'.$imageStep3.'.png',
                    ],
                    'short_description' => 'Sản phẩm chất lượng cao, phù hợp nhu cầu chăm sóc mẹ và bé hằng ngày.',
                    'description' => 'Sản phẩm được chọn lọc theo tiêu chí an toàn, tiện lợi và phù hợp từng giai đoạn phát triển của bé. Thiết kế hiện đại, dễ sử dụng, vật liệu thân thiện và đáp ứng tốt nhu cầu mua sắm của gia đình trẻ.',
                    'attributes' => [
                        'Thương hiệu' => $brand,
                        'Model' => strtoupper($brand).'-'.str_pad((string) fake()->numberBetween(10, 9999), 4, '0', STR_PAD_LEFT),
                        'Nhà sản xuất' => $brand.' Việt Nam',
                        'Chất liệu' => collect(['Nhựa PP an toàn', 'Silicone y tế', 'Cotton hữu cơ', 'Sợi tre thiên nhiên'])->random(),
                        'Kích thước' => collect(['15 x 8 x 6 cm', '20 x 12 x 7 cm', '25 x 18 x 10 cm', '30 x 20 x 12 cm'])->random(),
                        'Khối lượng' => collect(['120g', '250g', '500g', '1kg'])->random(),
                        'Hạn sử dụng' => collect(['12 tháng', '18 tháng', '24 tháng', '36 tháng'])->random(),
                        'Bảo hành' => collect(['Không áp dụng', '06 tháng', '12 tháng'])->random(),
                        'Bảo quản' => 'Nơi khô ráo, thoáng mát',
                        'Xuất xứ' => $origins[array_rand($origins)],
                    ],
                    'is_featured' => $index <= 2,
                    'is_active' => true,
                ]);
            }
        }

        foreach (range(1, 24) as $i) {
            Post::create([
                'user_id' => $admin->id,
                'title' => 'Cẩm nang mua sắm và chăm sóc bé số '.$i,
                'slug' => 'cam-nang-cham-soc-me-va-be-so-'.$i,
                'thumbnail' => $imagePool[array_rand($imagePool)],
                'excerpt' => 'Tổng hợp kinh nghiệm hữu ích giúp ba mẹ chọn đúng sản phẩm và chăm sóc bé thuận tiện hơn mỗi ngày.',
                'content' => 'Bài viết chia sẻ mẹo chọn sữa, tã bỉm, đồ dùng ăn dặm, đồ chơi và các sản phẩm thiết yếu theo từng giai đoạn phát triển của bé. Nội dung được biên soạn theo phong cách tư vấn mua sắm thực tế để phù hợp với website bán hàng.',
                'status' => 'published',
                'published_at' => now()->subDays($i),
            ]);
        }

        $analytics = app(BehaviorAnalyticsService::class);

        $customers->each(function ($customer) use ($analytics) {
            $visitLoops = fake()->numberBetween(15, 40);

            foreach (range(1, $visitLoops) as $loop) {
                $product = Product::inRandomOrder()->first();

                $analytics->record('visit', ['user_id' => $customer->id]);
                $analytics->record('product_view', [
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'event_value' => $product->final_price,
                ]);

                if ($loop % fake()->numberBetween(2, 4) === 0) {
                    $analytics->record('search', [
                        'user_id' => $customer->id,
                        'search_keyword' => collect([$product->brand, $product->category->name, $product->age_group])->random(),
                    ]);
                }

                if ($loop % fake()->numberBetween(3, 5) === 0) {
                    $analytics->record('add_to_cart', [
                        'user_id' => $customer->id,
                        'product_id' => $product->id,
                        'category_id' => $product->category_id,
                        'event_value' => $product->final_price,
                    ]);
                }
            }

            foreach (range(1, fake()->numberBetween(2, 6)) as $orderIndex) {
                $orderProducts = Product::inRandomOrder()->take(fake()->numberBetween(2, 5))->get();
                $subtotal = 0;

                $order = Order::create([
                    'user_id' => $customer->id,
                    'order_number' => 'ODSEED'.random_int(10000, 99999).$orderIndex,
                    'customer_name' => $customer->name,
                    'customer_email' => $customer->email,
                    'customer_phone' => $customer->phone,
                    'shipping_address' => $customer->address,
                    'subtotal' => 0,
                    'discount_total' => fake()->numberBetween(0, 80000),
                    'shipping_fee' => fake()->numberBetween(0, 40000),
                    'grand_total' => 0,
                    'payment_method' => collect(['cod', 'bank_transfer'])->random(),
                    'payment_status' => collect(['paid', 'paid', 'pending'])->random(),
                    'status' => collect(['completed', 'completed', 'shipping', 'confirmed'])->random(),
                    'ordered_at' => now()->subDays(fake()->numberBetween(1, 60)),
                ]);

                foreach ($orderProducts as $product) {
                    $quantity = fake()->numberBetween(1, 3);
                    $lineTotal = $product->final_price * $quantity;
                    $subtotal += $lineTotal;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'sku' => $product->sku,
                        'quantity' => $quantity,
                        'unit_price' => $product->final_price,
                        'line_total' => $lineTotal,
                    ]);

                    $product->increment('sold_count', $quantity);
                }

                $order->update([
                    'subtotal' => $subtotal,
                    'grand_total' => $subtotal - $order->discount_total + $order->shipping_fee,
                ]);

                $analytics->record('purchase', [
                    'user_id' => $customer->id,
                    'event_value' => $order->grand_total,
                ]);
            }

            $analytics->queueSuggestionEmail($customer);
        });

        $analytics->calculateAll();
    }
}
