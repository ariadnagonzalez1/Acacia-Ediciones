<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromocionSeeder extends Seeder
{
    public function run(): void
    {
        $promocionId = DB::table('promociones')->insertGetId([
            'nombre' => 'Kit Lecturas Acacia',
            'tipo' => 'kit',
            'porcentaje_descuento' => null,
            'precio_kit' => 12500,
            'mensaje' => 'Llevá tres libros digitales a un precio especial.',
            'fecha_inicio' => now()->toDateString(),
            'fecha_fin' => now()->addMonth()->toDateString(),
            'activa' => true,
            'mostrar_inicio' => true,
            'enviar_correo' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $libros = DB::table('libros')
            ->whereIn('titulo', [
                'Cartografía del silencio',
                'Donde termina el río',
                'Manual del lector paciente',
            ])
            ->pluck('id');

        foreach ($libros as $libroId) {
            DB::table('libro_promocion')->insert([
                'libro_id' => $libroId,
                'promocion_id' => $promocionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}