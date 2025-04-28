<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $userIds = User::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            $startDate = $faker->dateTimeBetween('-2 months', '+1 month')->format('Y-m-d');
            $endDate = Carbon::parse($startDate)->addDays($faker->numberBetween(15, 60))->format('Y-m-d');

            $today = Carbon::now();
            if ($today->lt($startDate)) {
                $status = 0; // lên lịch
            } elseif ($today->between($startDate, $endDate)) {
                $status = 1; // đang hoạt động
            } else {
                $status = 2; // hết hạn
            }
            
            Voucher::create([
                'created_by' => $faker->optional(0.8)->randomElement($userIds),
                'approved_by' => $faker->optional(0.5)->randomElement($userIds),
                'code' => strtoupper($faker->bothify('VC####??')),
                'value' => $faker->randomElement([50000, 100000, 150000, 200000]),
                'usage_limit' => $faker->randomElement([50, 100, 200, 500]),
                'used_count' => $faker->numberBetween(0, 100),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
            ]);
        }
    }
}
