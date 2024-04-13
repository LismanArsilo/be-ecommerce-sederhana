<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'cate_id' => 3,
            'name' => 'Headscraf Sporty',
            'price' => '200000',
            'description' => 'Bahan lembut',
        ]);

        DB::table('products')->insert([
            'cate_id' => 3,
            'name' => 'Headscraf Motif',
            'price' => '200000',
            'description' => 'Bahan lembut',
        ]);

        DB::table('products')->insert([
            'cate_id' => 3,
            'name' => 'Headscraf Sporty Full Color',
            'price' => '200000',
            'description' => 'Bahan lembut',
        ]);

        DB::table('products')->insert([
            'cate_id' => 2,
            'name' => 'Soccer Shoes',
            'price' => '200000',
            'description' => 'Kulit Kangguru, tidak mudah rusak',
        ]);

        DB::table('products')->insert([
            'cate_id' => 2,
            'name' => 'Running Shoes',
            'price' => '200000',
            'description' => 'Bahan lembut',
        ]);

        DB::table('products')->insert([
            'cate_id' => 2,
            'name' => 'Sporty Shoes',
            'price' => '200000',
            'description' => 'Bahan lembut',
        ]);
    }
}
