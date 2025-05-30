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
- Tạo migration mới:
```bash
php artisan make:migration create_users_table
```
#### Seeder
Seeder là 1 class cho phép xử lý dữ liệu trong database, hỗ trợ tạo ra các data test, thay đổi cập nhật dữ liệu khi cần thiết.
- Chạy tất cả seeders trong `DatabaseSeeder.php`:
```bash
php artisan db:seed
```
- Tạo seeder mới:
```bash
php artisan make:seeder UserSeeder
```
- Chạy 1 seeder cụ thể:
```bash
php artisan db:seed --class=UserSeeder
```
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
- Tạo shortcut giúp truy cập ảnh qua url:
```bash
php artisan storage:link
```
- Tạo form request:
```bash
php artisan make:request UserRequest
```
## **PostgreSql**
```bash
psql -U postgres -W -h localhost -p 5432 sanhua_dev
```
#### **Các kiểu join phổ biến**
```bash
- INNER JOIN: lấy dữ liệu khi có bản ghi khớp từ 2 bảng
- LEFT/RIGHT JOIN: lấy tất cả bản ghi từ bảng bên trái/phải nếu không khớp bảng phải/trái trả về null
- FULL OUTER JOIN: lấy tất cả bản ghi từ 2 bảng, khớp thì ghép lại không khớp trả về null
- CROSS JOIN: kết hợp mỗi bản ghi ở bảng A với mọi bản ghi ở bản B (số lượng kết quả = n * m cách chọn)
- SELF JOIN: join chính bảng đó với chính nó
```
#### **Các lệnh cơ bản trong PostgreSQL**
```bash
\q: thoát
\l: xem danh sách database
\c <ten_db>: kết nối tới database
\d: xem danh sách các bảng
\d <ten_bang>: xem thông tin chi tiết về bảng
\du: liệt kê user
```
#### **Giải thích các định nghĩa**
```bash
- NEW: giá trị chuẩn bị được chèn vào bảng (INSERT, UPDATE) - OLD: giá trị cũ của bảng (UPDATE, DELETE)
- AS $$ ... $$ cách định nghĩa thân hàm
- RETURN: trả về giá trị trong thân hàm - RETURNS: khai báo kiểu trả về của hàm
- := là gán, = là so sánh, IS DISTINCT FROM giống với != nhưng so sánh cả kiểu NULL
- COALESCE(a, b, ...): trả về giá trị đầu tiên khác NULL
- TG_OP: xác định sự kiện CREATE, UPDATE, DELETE
- cascade: khi bảng cha bị xóa/cn, bảng con cũng bị xóa/cn theo. restrict: ngăn chặn việc xóa/cn bảng cha. set null: bảng cha bị xóa/cn bảng con đặt thành null.
```
#### **Trigger**
```bash
// Trigger trong sql là 1 loại hàm tự động được kích hoạt khi có hành động INSERT, UPDATE, DELETE, TRUNCATE xảy ra
// Xóa mềm không được tính là DELETE mà là UPDATE (update trường deleted_at)

CREATE FUNCTION ten_ham_trigger()       -- tạo function trigger
RETURNS TRIGGER AS $$
BEGIN
    -- Logic của bạn ở đây
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER ten_trigger      -- gắn trigger vào bảng
{BEFORE | AFTER} {INSERT | UPDATE | DELETE} ON ten_bang
FOR EACH ROW
EXECUTE FUNCTION ten_ham_trigger();

DROP TRIGGER IF EXISTS ten_trigger       -- xóa trigger
```
## **Ghi chú**
#### **Command**
```bash
php artisan make:command NameCommand
// là lệnh tùy chỉnh chạy trong terminal bằng php artisan
// ví dụ các command có sẵn như: php artisan serve, php artisan migrate...
// tạo dữ liệu tự động, đồng bộ, backup dữ liệu...

```
#### **Accessor & Mutator**
```php
// Accessor là cách tùy chỉnh giá trị khi lấy dữ liệu từ model (get)
// Mutator là cách tùy chỉnh giá trị trước khi lưu vào db (set)
public function get/set...Attribute() {  // khai báo trong model
    // thực hiện chức năng tương ứng
}
```
#### **Observer events**
```bash
php artisan make:observer UserObserver --model=User
// là nơi viết các hành động tự xảy ra khi 1 model bị tạo, cập nhật, xóa...
- retrieved: sau khi bản ghi được lấy từ db
- creating: trước khi bản ghi được tạo
- created: sau khi bản ghi được tạo
- updating: trước khi bản ghi được cập nhật
- updated: sau khi bản ghi được cập nhật
- saving: trước khi bản ghi được lưu (tạo hoặc cập nhật)
- saved: sau khi bản ghi được lưu (tạo hoặc cập nhật)
- deleting: trước khi bản ghi bị xóa (hoặc xóa mềm)
- deleted: sau khi bản ghi bị xóa (hoặc xóa mềm)
- restoring: trước khi bản ghi được khôi phục
- restored: sau khi bản ghi được khôi phục
```
#### **Thuộc tính trong Eloquent**
```bash
- $fillable: là mảng trong model chỉ định rõ những trường được mass assign (tức được phép create() hoặc update()), các trường nằm ngoài sẽ bị bỏ qua
- $guarded: ngược lại với $fillable dùng để chặn các field (chỉ nên dùng 1 trong 2)
- $hidden: các field trong nó sẽ không xuất hiện khi chuyển model sang json hoặc array (dùng cho các dữ liệu nhạy cảm)
- $visible: ngược lại với $hidden, chỉ hiển thị field liệt kê
- $casts: tự động ép kiểu cho các field (ví dụ hashed cho password, datetime cho email_verified_at)
- $appends: thêm các accessor vào json (tạo field ảo)
```
#### **Trait**
```bash
// là tái sử dụng code giữa các class mà không cần dùng kế thừa
- Notifiable: cho phép model nhận thông báo (email, sms, thông báo khi có đơn hàng mới, ...)
- SoftDeletes: xóa mềm
- HasFactory: fake dữ liệu (thường dùng trong testing hoặc seed db)

```
#### **Kiến thức khác**
```bash
syncWithoutDetaching(): thêm hoặc cập nhật các bản ghi trong pivot
updateExistingPivot(): chỉ cập nhật 1 bản ghi được chỉ định trong pivot 
```

- Laravel chỉ xử lý upload file từ form data khi đó là phương thức POST.
- Service là tập hợp các lớp có trách nhiệm chính là thực thi logic nghiệp vụ phức tạp. Thường dùng khi muốn tách biệt logic xử lý ra khỏi controller.
