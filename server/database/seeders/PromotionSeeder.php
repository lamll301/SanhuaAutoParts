<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
use Faker\Factory as Faker;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        foreach (range(1, 30) as $index) {
            Promotion::create([
                'name' => $faker->words(3, true),
                'discount_percent' => $faker->numberBetween(5, 50),
                'max_discount_amount' => $faker->randomElement([null, $faker->numberBetween(500000, 2000000)]),
                'start_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'end_date' => $faker->dateTimeBetween('now', '+1 year'),
                'status' => $faker->randomElement([0, 1]),
            ]);
        }
    }
}
