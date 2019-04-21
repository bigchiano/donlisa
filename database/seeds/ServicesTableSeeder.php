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
            'name' => 'Buy data'
        ]);
        DB::table('services')->insert([
            'name' => 'Buy airtime'
        ]);
        DB::table('services')->insert([
            'name' => 'Buy power'
        ]);
        DB::table('services')->insert([
            'name' => 'Subscribe tv'
        ]);
    }
}
