<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Faker\Factory as Faker;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        foreach (range(1, 30) as $index) {
            Voucher::create([
                'code' => strtoupper($faker->bothify('??-######')),
                'value' => $faker->numberBetween(10000, 100000),
                'usage_limit' => $faker->numberBetween(100, 500),
                'used_count' => $faker->numberBetween(0, 50),
                'start_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'end_date' => $faker->dateTimeBetween('now', '+1 year'),
                'status' => $faker->randomElement([0, 1]),
            ]);
        }
    }
}
