<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Belajar Laravel untuk Pemula',
                'author' => 'John Doe',
                'number_book' => 'AB123',
                'publisher' => 'Penerbit Buku Indonesia',
                'cover' => 'https://example.com/covers/laravel.jpg',
                'publication_year' => 2023,
                'category_id' => 1,
                'stock' => 10,
                'slug' => 'belajar-laravel-untuk-pemula',
            ],
            [
                'title' => 'Pemrograman PHP Lanjutan',
                'author' => 'Jane Smith',
                'number_book' => 'CD456',
                'publisher' => 'Penerbit Teknologi',
                'cover' => 'https://example.com/covers/php.jpg',
                'publication_year' => 2022,
                'category_id' => 2,
                'stock' => 5,
                'slug' => 'pemrograman-php-lanjutan',
            ],
            [
                'title' => 'Dasar-dasar JavaScript',
                'author' => 'Alice Johnson',
                'number_book' => 'EF789',
                'publisher' => 'Penerbit Web Dev',
                'cover' => 'https://example.com/covers/javascript.jpg',
                'publication_year' => 2021,
                'category_id' => 3,
                'stock' => 8,
                'slug' => 'dasar-dasar-javascript',
            ],
            [
                'title' => 'Pengantar Machine Learning',
                'author' => 'Bob Brown',
                'number_book' => 'GH012',
                'publisher' => 'Penerbit AI',
                'cover' => 'https://example.com/covers/machine_learning.jpg',
                'publication_year' => 2020,
                'category_id' => 4,
                'stock' => 12,
                'slug' => 'pengantar-machine-learning',
            ],
        ];

       $books = DB::table('books')->insert($books);
    }

}