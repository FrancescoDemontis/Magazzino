<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Cancelleria', 'background_color' => '#FF5733', 'text_color' => '#FFFFFF'],
            ['name' => 'Alimentari', 'background_color' => '#00A896', 'text_color' => '#FFFFFF'],
            ['name' => 'Elettronica', 'background_color' => '#007BFF', 'text_color' => '#FFFFFF'],
            // Aggiungi altre categorie se necessario
        ]);
    }
}
