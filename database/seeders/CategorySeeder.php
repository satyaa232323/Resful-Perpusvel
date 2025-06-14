<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiksi'],
            ['name' => 'Non-Fiksi'],
            ['name' => 'Biografi'],
            ['name' => 'Sejarah'],
            ['name' => 'Teknologi'],
            ['name' => 'Sains'],
            ['name' => 'Kesehatan'],
            ['name' => 'Pendidikan'],
            ['name' => 'Agama'],
            ['name' => 'Seni dan Budaya'],
        ];

        $categories = DB::table('categories')->insert($categories);
    }
}