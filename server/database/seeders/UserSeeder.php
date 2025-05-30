<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    private const PHONE_PREFIXES = ['032', '033', '034', '035', '036', '037', '038', '039', '070', '079', '077', '076', '078', '083', '084', '085', '081', '082'];
    private const PROVINCES = [
        31 => [
            303 => [11296, 11299, 11302, 11305, 11311, 11320, 11323, 11587, 11599, 11602],
            304 => [11329, 11335, 11338, 11341, 11344, 11350, 11359, 11365],
            306 => [11410, 11411, 11413, 11414, 11416, 11419, 11422, 11425],
            307 => [11431, 11434, 11437, 11440, 11443, 11446, 11449],
            309 => [11683, 11686, 11689, 11692, 11707, 11740],
            311 => [11473, 11485, 11488, 11500, 11503, 11506, 11512, 11518, 11521, 11527, 11530, 11533, 11539, 11542, 11545, 11551, 11557, 11560, 11569, 11572, 11578],
            312 => [11581, 11584, 11590, 11593, 11596, 11608, 11614, 11617, 11623, 11626],
            313 => [11629, 11632, 11635, 11638, 11641, 11644, 11647, 11650, 11653, 11656, 11659, 11662, 11665, 11668, 11671, 11674, 11677],
            314 => [11680, 11695, 11698, 11701, 11704, 11710, 11713, 11716, 11722, 11725, 11728, 11734, 11743, 11746],
        ]
    ];
    
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $roleIds = Role::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            $cityId = $faker->randomElement(array_keys(self::PROVINCES));
            $districts = self::PROVINCES[$cityId];
            $districtId = $faker->randomElement(array_keys($districts));
            $wardId = $faker->randomElement($districts[$districtId]);
            User::create([
                'username' => 'test' . $i,
                'password' => bcrypt('test'),
                'name' => $faker->lastName . ' ' . $faker->firstName,
                'email' => $faker->email,
                'phone' => $faker->randomElement(self::PHONE_PREFIXES) . $faker->numberBetween(1000000, 9999999),
                'date_of_birth' => $faker->dateTimeBetween('-40 years', '-18 years')->format('Y-m-d'),
                'address' => $faker->address,
                'role_id' => null,
                'city_id' => $cityId,
                'district_id' => $districtId,
                'ward_id' => $wardId,
            ]);
        }

        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role_id' => Role::where('name', 'admin')->first()->id,
            'name' => 'Admin',
        ]);

        for ($i = 0; $i < 4; $i++) {
            User::create([
                'username' => 'nv' . $i,
                'password' => bcrypt('nv' . $i),
                'name' => $faker->lastName . ' ' . $faker->firstName,
                'phone' => $faker->randomElement(self::PHONE_PREFIXES) . $faker->numberBetween(1000000, 9999999),
                'email' => $faker->email,
                'date_of_birth' => $faker->dateTimeBetween('-40 years', '-18 years')->format('Y-m-d'),
                'role_id' => $faker->randomElement($roleIds),
            ]);
        }
    }
}
