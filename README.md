# FindIt TLU - Admin Panel

<p align="center">
  <img src="https://via.placeholder.com/400x200/4285f4/ffffff?text=FindIt+TLU+Admin" alt="FindIt TLU Admin Logo" width="400">
</p>

## Giới thiệu

FindIt TLU Admin Panel là hệ thống quản trị web dành cho ứng dụng FindIt của Trường Đại học Thủy lợi. Hệ thống cho phép quản trị viên quản lý người dùng, nội dung và các hoạt động của ứng dụng mobile FindIt TLU.

## Tính năng chính

- 🔐 **Xác thực và phân quyền**: Đăng nhập bảo mật cho admin
- 👥 **Quản lý người dùng**: Quản lý tài khoản sinh viên và giảng viên
- 📱 **Quản lý nội dung**: Quản lý thông tin, tin tức, sự kiện của trường
- 📊 **Thống kê**: Báo cáo và phân tích dữ liệu sử dụng ứng dụng
- 🔧 **Cấu hình hệ thống**: Thiết lập các tham số và cấu hình ứng dụng
- 📋 **Quản lý bài đăng**: Duyệt và quản lý các bài đăng từ người dùng

## Công nghệ sử dụng

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates, Bootstrap, jQuery
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage

## Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM
- Web server (Apache/Nginx)

## Cài đặt

### 1. Clone repository

```bash
git clone https://github.com/your-username/findit-tlu-admin.git
cd findit-tlu-admin
```

### 2. Cài đặt dependencies

```bash
composer install
npm install
```

### 3. Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

Chỉnh sửa file `.env` với thông tin database và cấu hình khác:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=findit_tlu_admin
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Chạy migration và seeder

```bash
php artisan migrate
php artisan db:seed
```

### 5. Build assets

```bash
npm run build
```

### 6. Khởi chạy server

```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## Sử dụng

### Đăng nhập Admin

- Email: `admin@tlu.edu.vn`
- Password: `admin123`

### Cấu trúc thư mục chính

```
app/
├── Http/Controllers/Admin/    # Controllers cho admin panel
├── Models/                    # Eloquent models
└── Services/                  # Business logic services

resources/
├── views/admin/              # Blade templates cho admin
└── js/admin/                 # JavaScript files

routes/
├── web.php                   # Web routes
└── api.php                   # API routes
```

## API Documentation

API endpoints cho mobile app:

- `GET /api/users` - Lấy danh sách người dùng
- `POST /api/posts` - Tạo bài đăng mới
- `GET /api/news` - Lấy tin tức
- `GET /api/events` - Lấy sự kiện

## Đóng góp

1. Fork project
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## Liên hệ

- **Developer**: [Phát Đỗ]
- **Email**: dotienphat1742004@gmail.com
- **Project**: [[Link GitHub Project](https://github.com/PhatDo744/findit-tlu-admin.git)]

## License

Dự án này được phát triển cho mục đích học tập tại Trường Đại học Thủy lợi.

---

© 2025 FindIt TLU - Trường Đại học Thủy lợi
