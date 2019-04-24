<?php

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'name' => 'buy_data'
        ]);
        DB::table('services')->insert([
            'name' => 'buy_airtime'
        ]);
        DB::table('services')->insert([
            'name' => 'buy_power'
        ]);
        DB::table('services')->insert([
            'name' => 'subscribe_tv'
        ]);
    }
}
