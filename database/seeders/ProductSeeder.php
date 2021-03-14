<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'ID' => 1,
            'name' => 'Plan #1',
            'cpu' => 1,
            'ram' => 1024,
            'disk_size' => 8,
        ]);

        DB::table('products')->insert([
            'ID' => 2,
            'name' => 'Plan #2',
            'cpu' => 2,
            'ram' => 2048,
            'disk_size' => 16,
        ]);
    }
}
