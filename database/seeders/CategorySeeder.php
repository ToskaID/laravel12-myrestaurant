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
        //query builder
        $categories = [
            ['cat_name' => 'Makanan', 'description' => 'Kategori Makanan','created_at' => now(), 'updated_at' => now()],
            ['cat_name' => 'Minuman', 'description' => 'Kategori Minuman','created_at' => now(), 'updated_at' => now()],

        ];
        DB::table('categories')->insert($categories);
    }
}
