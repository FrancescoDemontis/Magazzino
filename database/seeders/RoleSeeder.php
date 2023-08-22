<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Importa la facciata DB
use Illuminate\Support\Facades\Hash; // Importa la facciata Hash
use Illuminate\Support\Str; // Importa la facciata Str

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            'role' => 'user',
        ]);

        DB::table('role')->insert([
            'role' => 'admin',
        ]);
    }
}
