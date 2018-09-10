<?php

use Illuminate\Database\Seeder;
use App\Coupon;

class CouponTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::create([
          'code' => 'ASD123',
          'type' => 'fixed',
          'value' => 3000
        ]);

        Coupon::create([
          'code' => 'FGH123',
          'type' => 'percent',
          'percent_off' => 50
        ]);
    }
}
