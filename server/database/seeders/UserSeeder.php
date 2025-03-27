<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['username' => 'lamll301', 'password' => Hash::make('123456'), 'name' => 'Lê Lý Lâm', 'email' => 'lamll999@gmail.com', 'phone' => '0815369258', 'address' => 'Số 12, Tập thể 999', 'city_id' => 31, 'district_id' => 306, 'ward_id' => 11414, 'date_of_birth' => '2003-01-30', 'role_id' => 1],
            ['username' => 'qwerty123', 'password' => Hash::make('123456'), 'name' => 'Nguyễn Văn An', 'email' => 'annguyen@gmail.com', 'phone' => '0901234567', 'address' => 'Số 30, Đường Lý Thái Tổ, Khu 3', 'city_id' => 31, 'district_id' => 308, 'ward_id' => 11461, 'date_of_birth' => '2003-01-30'],
            ['username' => 'nguyenhoang', 'password' => Hash::make('123456'), 'name' => 'Nguyễn Hoàng', 'email' => 'nguyenhoang@gmail.com', 'phone' => '0987654321', 'address' => '12 Nguyễn Trãi', 'city_id' => 1, 'district_id' => 2, 'ward_id' => 49, 'date_of_birth' => '1990-05-15'],
            ['username' => 'tranthanh', 'password' => Hash::make('123456'), 'name' => 'Trần Thanh', 'email' => 'tranthanh@gmail.com', 'phone' => '0978654321', 'address' => '45 Lê Lợi', 'city_id' => 1, 'district_id' => 17, 'ward_id' => 505, 'date_of_birth' => '1985-07-20', 'role_id' => 2],
            ['username' => 'phamvanan', 'password' => Hash::make('123456'), 'name' => 'Phạm Văn An', 'email' => 'phamvanan@gmail.com', 'phone' => '0968543210', 'address' => '78 Hai Bà Trưng', 'city_id' => 1, 'district_id' => 7, 'ward_id' => 241, 'date_of_birth' => '1992-09-10', 'role_id' => 3],
            ['username' => 'dangthuytrang', 'password' => Hash::make('123456'), 'status' => 0, 'name' => 'Đặng Thùy Trang', 'email' => 'dangthuytrang@gmail.com', 'phone' => '0937456789', 'address' => '90 Trần Hưng Đạo', 'city_id' => 79, 'district_id' => 760, 'ward_id' => 26752, 'date_of_birth' => '1995-12-01'],
            ['username' => 'buitienmanh', 'password' => Hash::make('123456'), 'status' => 0, 'name' => 'Bùi Tiến Mạnh', 'email' => 'buitienmanh@gmail.com', 'phone' => '0912345678', 'address' => '100 Cầu Giấy', 'city_id' => 1, 'district_id' => 8, 'ward_id' => 304, 'date_of_birth' => '1988-04-25', 'role_id' => 2],
            ['username' => 'lethihuyen', 'password' => Hash::make('123456'), 'status' => 0, 'name' => 'Lê Thị Huyền', 'email' => 'lethihuyen@gmail.com', 'phone' => '0923456789', 'address' => '25 Nguyễn Du', 'city_id' => 94, 'district_id' => 943, 'ward_id' => 31552, 'date_of_birth' => '1997-06-18', 'role_id' => 3],
            ['username' => 'hoangminhtri', 'password' => Hash::make('123456'), 'status' => 2, 'name' => 'Hoàng Minh Trí', 'email' => 'hoangminhtri@gmail.com', 'phone' => '0904567890', 'address' => '123 Phan Đình Phùng', 'city_id' => 27, 'district_id' => 261, 'ward_id' => 9385, 'date_of_birth' => '1993-11-30'],
            ['username' => 'vuthithuyduong', 'password' => Hash::make('123456'), 'status' => 2, 'name' => 'Vũ Thị Thùy Dương', 'email' => 'vuthithuyduong@gmail.com', 'phone' => '0985674321', 'address' => '50 Lý Thường Kiệt', 'city_id' => 24, 'district_id' => 217, 'ward_id' => 7417, 'date_of_birth' => '1989-02-22'],
            ['username' => 'doanquocbao', 'password' => Hash::make('123456'), 'status' => 3, 'name' => 'Đoàn Quốc Bảo', 'email' => 'doanquocbao@gmail.com', 'phone' => '0976543210', 'address' => '300 Hoàng Diệu', 'city_id' => 11, 'district_id' => 94, 'ward_id' => 3139, 'date_of_birth' => '1996-08-05', 'role_id' => 3],
            ['username' => 'tranthiminhngoc', 'password' => Hash::make('123456'), 'status' => 3, 'name' => 'Trần Thị Minh Ngọc', 'email' => 'tranthiminhngoc@gmail.com', 'phone' => '0967890123', 'address' => '200 Nguyễn Văn Cừ', 'city_id' => 1, 'district_id' => 1, 'ward_id' => 31, 'date_of_birth' => '1994-03-12'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        User::factory()->count(100)->create();
    }
}
