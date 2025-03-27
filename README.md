# **SanhuaAutoParts**

A brief description of what this project does and who it's for

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