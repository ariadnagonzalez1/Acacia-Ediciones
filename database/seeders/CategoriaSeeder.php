<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            [
                'nombre' => 'Narrativa',
                'descripcion' => 'Novelas, cuentos y relatos.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Poesía',
                'descripcion' => 'Poemarios y obras poéticas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ensayo',
                'descripcion' => 'Ensayos y textos de reflexión.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Infantil',
                'descripcion' => 'Literatura destinada al público infantil.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Oficios',
                'descripcion' => 'Libros prácticos sobre técnicas y oficios.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}