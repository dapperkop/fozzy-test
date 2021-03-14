<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'ID' => 1,
            'created' => now(),
            'modified' => now(),
            'user_id' => 1,
            'product_id' => 1,
            'name' => 'Service #1 by Test1 User'
        ]);

        DB::table('services')->insert([
            'ID' => 2,
            'created' => now(),
            'modified' => now(),
            'user_id' => 1,
            'product_id' => 2,
            'name' => 'Service #2 by Test1 User'
        ]);
    }
}
