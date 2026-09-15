<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        $poesia = DB::table('categorias')
            ->where('nombre', 'Poesía')
            ->value('id');

        $narrativa = DB::table('categorias')
            ->where('nombre', 'Narrativa')
            ->value('id');

        $ensayo = DB::table('categorias')
            ->where('nombre', 'Ensayo')
            ->value('id');

        DB::table('libros')->insert([
            [
                'categoria_id' => $poesia,
                'titulo' => 'Cartografía del silencio',
                'autor' => 'Tomás Beltrán',
                'precio' => 4200,
                'paginas' => 96,
                'anio_edicion' => 2026,
                'informacion' => 'Un recorrido poético por los silencios cotidianos.',
                'mensaje_correo' => 'Gracias por elegir Cartografía del silencio.',
                'portada' => 'portadas/cartografia-del-silencio.jpg',
                'archivo_pdf' => 'libros/cartografia-del-silencio.pdf',
                'estado' => 'publicado',
                'destacado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'categoria_id' => $narrativa,
                'titulo' => 'Donde termina el río',
                'autor' => 'Lucía Ferreyra',
                'precio' => 5900,
                'paginas' => 184,
                'anio_edicion' => 2026,
                'informacion' => 'Una historia sobre memoria, familia y raíces.',
                'mensaje_correo' => 'Esperamos que disfrutes esta lectura.',
                'portada' => 'portadas/donde-termina-el-rio.jpg',
                'archivo_pdf' => 'libros/donde-termina-el-rio.pdf',
                'estado' => 'publicado',
                'destacado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'categoria_id' => $ensayo,
                'titulo' => 'Manual del lector paciente',
                'autor' => 'Elena Campos',
                'precio' => 4800,
                'paginas' => 120,
                'anio_edicion' => 2026,
                'informacion' => 'Una reflexión sobre la lectura en tiempos acelerados.',
                'mensaje_correo' => 'Gracias por comprar en Acacia Ediciones.',
                'portada' => 'portadas/manual-del-lector-paciente.jpg',
                'archivo_pdf' => 'libros/manual-del-lector-paciente.pdf',
                'estado' => 'publicado',
                'destacado' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}