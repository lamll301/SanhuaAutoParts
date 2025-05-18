# **SanhuaAutoParts**
**SanhuaAutoParts** là một nền tảng thương mại điện tử chuyên cung cấp các thiết bị và phụ tùng ô tô dành cho các gara, đại lý phân phối, và người dùng cá nhân có nhu cầu mua sắm linh kiện thay thế. Website hỗ trợ quản lý sản phẩm, phân loại theo hệ thống (động cơ, điều hòa, truyền động,...), tìm kiếm nâng cao, thanh toán online qua nhiều cổng, và quản lý đơn hàng. Dự án được phát triển với mục tiêu mang lại trải nghiệm mua sắm dễ dàng, nhanh chóng và chuyên nghiệp cho khách hàng trong ngành phụ tùng ô tô.

## **Cài đặt & Chạy dự án**
Yêu cầu: 
- Vue.js 5.0.8
- Laravel 11.42.1
- PostgreSQL 17.2
### **Backend (Laravel)**
Vào thư mục server:
```bash
cd server
```
Cài đặt các dependency:
```bash
composer install
```
Sao chép file `.env.example` thành `.env` và cập nhật thông tin cấu hình:
```env
DB_DATABASE=sanhua_dev
DB_USERNAME=
DB_PASSWORD=
```
Tạo khóa ứng dụng:
```bash
php artisan key:generate
```
Chạy migration để tạo bảng trong database:
```bash
php artisan migrate --seed
```
Chạy dự án:
```bash
php artisan serve
```
### **Frontend (Vue.js)**
Vào thư mục client:
```bash
cd client
```
Cài đặt các dependency:
```bash
npm install
```
Cấu hình file `.env`:
```env
VUE_APP_API_BASE_URL=http://localhost:8000
VUE_APP_CLIENT_ID=
VUE_APP_API_KEY=
```
Chạy dự án:
```bash
npm run serve
```
### **Công cụ phát triển**
#### **Truy cập API cục bộ bằng Ngrok**
Cấu hình Authtoken (token lấy sau khi đăng ký tài khoản):
```bash
ngrok config add-authtoken <YOUR_AUTHTOKEN>
```
Mở terminal và chạy:
```bash
ngrok http 8000
```
Cập nhật file `.env` của backend với URL public do ngrok cung cấp:
```bash
https://abcd-xyz.ngrok-free.app -> http://localhost:8000
```