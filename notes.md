
# Project Title

A brief description of what this project does and who it's for

# **Notes**

File này dùng để ghi chú lại những kiến thức quan trọng trong quá trình phát triển dự án.

## **Laravel**
#### Migration
Migration cho phép quản lý bảng trong database, thao tác với database mà không cần chỉnh lại code sql.
- Xóa toàn bộ bảng và chạy migrations từ đầu:
```bash
php artisan migrate:fresh
php artisan migrate:fresh --seed
```
- Nếu muốn giữ lại dữ liệu cũ, sử dụng:
```bash
php artisan migrate:refresh
```
#### Seeder
Seeder là 1 class cho phép xử lý dữ liệu trong database, hỗ trợ tạo ra các data test, thay đổi cập nhật dữ liệu khi cần thiết.
- Chạy tất cả seeders trong `DatabaseSeeder.php`:
```bash
php artisan db:seed
```
- Tạo seeder mới:
```bash
php artisan make:seeder UsersTableSeeder
```
- Chạy 1 seeder cụ thể:
```bash
php artisan db:seed --class=UsersTableSeeder
```
Ngoài ra, để tạo một lượng dữ liệu lớn như 100 hoặc 1000 bản ghi mà không cần nhập thủ công, chúng ta có thể sử dụng **Factory**.
- Tạo Factory mới:
```bash
php artisan make:factory UserFactory --model=User
```
#### **Các lệnh Artisan quan trọng**
Danh sách các tùy chọn phổ biến:
```bash
-m: migration
-c: controller
-r: resource controller     //bao gồm cả create(), edit()
-f: factory
-s: seeder
--api: api controller       //không gồm create(), edit()
```
- Tạo model mới:
```bash
php artisan make:model User -mcs
```
- Tạo controller mới:
```bash
php artisan make:controller UserController --api
```
### Code
#### [**Validation**](https://laravel.com/docs/12.x/validation#available-validation-rules)
* Tạo 1 Form Request:
```bash
php artisan make:request UserRequest
```
## **PostgreSql**
```bash
psql -U postgres -W -h localhost -p 5432 sanhua_dev
```
#### **Các lệnh cơ bản trong PostgreSQL**

[*Các loại lỗi HTTP thường gặp khi truy cập website*](https://tltvietnam.vn/99-loi-http-thuong-gap-khi-truy-cap-vao-website.html)