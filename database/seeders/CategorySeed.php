<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1
        DB::table('categories')->insert([
            'name' => 'test delete',
        ]);
        // 2
        DB::table('categories')->insert([
            'name' => 'test update',
        ]);
        // 3
        DB::table('categories')->insert([
            'name' => 'testting',
        ]);
        // 3
        DB::table('categories')->insert([
            'name' => 'setting',
        ]);
        // 3
        DB::table('categories')->insert([
            'name' => 'test',
        ]);
    }
}
