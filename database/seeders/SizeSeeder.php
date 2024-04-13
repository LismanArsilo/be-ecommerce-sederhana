<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1
        DB::table('sizes')->insert([
            'size_name' => 'All Size',
        ]);
        // 2
        DB::table('sizes')->insert([
            'size_name' => 'S',
        ]);
        // 3
        DB::table('sizes')->insert([
            'size_name' => 'M',
        ]);
        // 2
        DB::table('sizes')->insert([
            'size_name' => 'L',
        ]);
        // 3
        DB::table('sizes')->insert([
            'size_name' => 'XL',
        ]);
        // 3
        DB::table('sizes')->insert([
            'size_name' => 'XXL',
        ]);
        // 3
        DB::table('sizes')->insert([
            'size_name' => 'XXXL',
        ]);
    }
}
