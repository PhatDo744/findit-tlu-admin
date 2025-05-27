<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'FindIt@TLU',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Tên Website',
                'description' => 'Tên hiển thị của website'
            ],
            [
                'key' => 'site_description',
                'value' => 'Hệ thống tìm đồ thất lạc Thủy Lợi University',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Mô tả Website',
                'description' => 'Mô tả ngắn về website'
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@tlu.edu.vn',
                'type' => 'email',
                'group' => 'general',
                'label' => 'Email Quản trị',
                'description' => 'Email liên hệ chính của hệ thống'
            ],
            [
                'key' => 'items_per_page',
                'value' => '15',
                'type' => 'number',
                'group' => 'general',
                'label' => 'Số bài đăng mỗi trang',
                'description' => 'Số lượng bài đăng hiển thị trên mỗi trang'
            ],
            [
                'key' => 'auto_approve_posts',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Tự động duyệt bài đăng',
                'description' => 'Tự động duyệt bài đăng mới không cần admin xét duyệt'
            ],
            [
                'key' => 'allow_image_upload',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Cho phép tải ảnh',
                'description' => 'Cho phép người dùng tải ảnh lên khi đăng bài'
            ],

            // Email Settings
            [
                'key' => 'email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'label' => 'Bật thông báo email',
                'description' => 'Gửi email thông báo cho các sự kiện quan trọng'
            ],
            [
                'key' => 'notify_admin_new_post',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'label' => 'Thông báo admin khi có bài đăng mới',
                'description' => 'Gửi email cho admin khi có bài đăng mới cần duyệt'
            ],
            [
                'key' => 'notify_user_post_approved',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'label' => 'Thông báo user khi bài đăng được duyệt',
                'description' => 'Gửi email cho người dùng khi bài đăng được duyệt'
            ],

            // UI Settings
            [
                'key' => 'theme_color',
                'value' => '#004E95',
                'type' => 'color',
                'group' => 'ui',
                'label' => 'Màu chủ đạo',
                'description' => 'Màu chủ đạo của giao diện'
            ],
            [
                'key' => 'show_statistics',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'ui',
                'label' => 'Hiển thị thống kê',
                'description' => 'Hiển thị thống kê trên trang chủ'
            ],

            // System Settings
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Chế độ bảo trì',
                'description' => 'Bật chế độ bảo trì website'
            ],
            [
                'key' => 'max_file_size',
                'value' => '5',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Kích thước file tối đa (MB)',
                'description' => 'Kích thước tối đa của file upload'
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'jpg,jpeg,png,gif',
                'type' => 'text',
                'group' => 'system',
                'label' => 'Định dạng file được phép',
                'description' => 'Các định dạng file được phép upload (cách nhau bởi dấu phẩy)'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
