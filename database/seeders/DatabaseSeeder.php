<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin TLU',
            'email' => 'admin@tlu.edu.vn',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create regular user
        User::create([
            'name' => 'Người dùng test',
            'email' => 'user@tlu.edu.vn',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create more test users
        $users = User::factory(10)->create();

        // Add the test user to users collection
        $testUser = User::where('email', 'user@tlu.edu.vn')->first();
        $users->push($testUser);

        // Create test posts
        $categories = ['Điện thoại', 'Laptop', 'Sách', 'Quần áo', 'Túi xách', 'Ví', 'Chìa khóa', 'Khác'];
        $locations = [
            'Tòa A - Thủy Lợi',
            'Tòa B - Thủy Lợi', 
            'Tòa C - Thủy Lợi',
            'Thư viện TLU',
            'Căng tin TLU',
            'Sân bóng TLU',
            'Ký túc xá A',
            'Ký túc xá B',
            'Cổng chính TLU',
            'Bãi xe TLU'
        ];

        $lostItems = [
            'iPhone 15 màu xanh',
            'Laptop Dell Inspiron 15',
            'Sách Toán Cao Cấp A1',
            'Áo khoác Nike màu đen',
            'Túi xách da nâu',
            'Ví da đen có thẻ sinh viên',
            'Chìa khóa có móc BMW',
            'Tai nghe AirPods Pro',
            'Đồng hồ Apple Watch',
            'Kính mắt gọng đen'
        ];

        $foundItems = [
            'Điện thoại Samsung Galaxy',
            'Laptop MacBook Air',
            'Quyển sách Vật Lý Đại Cương',
            'Áo sweater màu xám',
            'Balo Adidas màu xanh',
            'Ví nữ màu hồng',
            'Bộ chìa khóa xe máy',
            'Chuột không dây Logitech',
            'Sạc dự phòng Xiaomi',
            'Thẻ sinh viên có tên'
        ];

        // Create lost posts
        foreach ($lostItems as $index => $item) {
            Post::create([
                'user_id' => $users->random()->id,
                'title' => "Mất $item",
                'description' => "Tôi đã làm mất $item vào hôm qua. Nếu ai nhặt được xin vui lòng liên hệ với tôi. Tôi sẽ có phần thưởng xứng đáng cho người tìm thấy. Cảm ơn mọi người rất nhiều!",
                'type' => 'lost',
                'category' => $categories[array_rand($categories)],
                'location' => $locations[array_rand($locations)],
                'image_url' => "https://picsum.photos/400/300?random=" . ($index + 1),
                'status' => ['pending', 'approved', 'rejected'][array_rand(['pending', 'approved', 'rejected'])],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Create found posts
        foreach ($foundItems as $index => $item) {
            Post::create([
                'user_id' => $users->random()->id,
                'title' => "Nhặt được $item",
                'description' => "Tôi đã nhặt được $item. Chủ nhân của vật phẩm này vui lòng liên hệ với tôi để nhận lại. Tôi sẽ hỏi một số thông tin để xác minh chủ sở hữu. Mong sớm tìm được chủ nhân!",
                'type' => 'found',
                'category' => $categories[array_rand($categories)],
                'location' => $locations[array_rand($locations)],
                'image_url' => "https://picsum.photos/400/300?random=" . ($index + 11),
                'status' => ['pending', 'approved'][array_rand(['pending', 'approved'])],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Create some returned posts
        for ($i = 0; $i < 5; $i++) {
            Post::create([
                'user_id' => $users->random()->id,
                'title' => "Đã tìm thấy " . $lostItems[array_rand($lostItems)],
                'description' => "Cảm ơn mọi người đã giúp đỡ. Tôi đã tìm thấy vật phẩm của mình rồi!",
                'type' => 'lost',
                'category' => $categories[array_rand($categories)],
                'location' => $locations[array_rand($locations)],
                'image_url' => "https://picsum.photos/400/300?random=" . ($i + 21),
                'status' => 'returned',
                'created_at' => now()->subDays(rand(1, 15)),
            ]);
        }

        // Seed settings
        $this->call(SettingsSeeder::class);
    }
}