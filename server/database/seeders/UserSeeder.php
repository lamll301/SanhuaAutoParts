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
        $faker = Faker::create('vi_VN');
        $roleIds = Role::pluck('id')->toArray();

        $prefixes = ['032', '033', '034', '035', '036', '037', '038', '039', // Viettel
                     '070', '079', '077', '076', '078',                     // Mobifone
                     '083', '084', '085', '081', '082'];                   // Vinaphone

        foreach (range(1, 50) as $index) {
            $name = $faker->lastName . ' ' . $faker->firstName;
            $email = Str::slug($name, '.') . $faker->numberBetween(1, 99) . '@gmail.com';

            $prefix = $faker->randomElement($prefixes);
            $suffix = $faker->numberBetween(1000000, 9999999);
            $phone = $prefix . $suffix;

            User::create([
                'username' => $faker->unique()->userName,
                'password' => bcrypt('password123'),
                'status' => $faker->randomElement([0, 1]),

                'name' => $name,
                'email' => strtolower($email),
                'phone' => $phone,
                'date_of_birth' => $faker->dateTimeBetween('-40 years', '-18 years')->format('Y-m-d'),

                'role_id' => $faker->randomElement($roleIds),
            ]);
        }
    }
}
