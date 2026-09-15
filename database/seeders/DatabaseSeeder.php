<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdministradorSeeder::class,
            CategoriaSeeder::class,
            LibroSeeder::class,
            PromocionSeeder::class,
            CorreoAutomaticoSeeder::class,
        ]);
    }
}