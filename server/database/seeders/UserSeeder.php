<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $prefixes = ['032', '033', '034', '035', '036', '037', '038', '039', '070', '079', '077', '076', '078', '083', '084', '085', '081', '082'];
        $faker = Faker::create('vi_VN');
        $roleIds = Role::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            $name = $faker->lastName . ' ' . $faker->firstName;
            $email = Str::slug($name, '.') . $i . '@gmail.com';

            User::create([
                'username' => $faker->unique()->userName,
                'password' => bcrypt('password123'),
                'name' => $name,
                'email' => strtolower($email),
                'phone' => $faker->randomElement($prefixes) . $faker->numberBetween(1000000, 9999999),
                'date_of_birth' => $faker->dateTimeBetween('-40 years', '-18 years')->format('Y-m-d'),
                'role_id' => $faker->randomElement($roleIds),
            ]);
        }
    }
}
