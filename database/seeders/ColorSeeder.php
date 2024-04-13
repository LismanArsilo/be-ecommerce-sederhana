<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colors')->insert([
            'color_name' => 'green',
        ]);

        DB::table('colors')->insert([
            'color_name' => 'red',
        ]);

        DB::table('colors')->insert([
            'color_name' => 'blue',
        ]);
    }
}
