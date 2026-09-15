<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CorreoAutomaticoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('correos_automaticos')->insert([
            [
                'tipo' => 'entrega_compra',
                'asunto' => 'Tu compra en Acacia Ediciones',
                'mensaje' => '¡Gracias por tu compra! Adjuntamos los ebooks que adquiriste.',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'tipo' => 'promocion',
                'asunto' => 'Novedades de Acacia Ediciones',
                'mensaje' => 'Tenemos nuevas lecturas y promociones para vos.',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}