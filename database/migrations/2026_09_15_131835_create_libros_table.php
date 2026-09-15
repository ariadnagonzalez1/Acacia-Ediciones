<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('libros', function (Blueprint $table) {
        $table->id();

        $table->foreignId('categoria_id')
            ->constrained('categorias')
            ->restrictOnDelete()
            ->cascadeOnUpdate();

        $table->string('titulo');
        $table->string('autor');

        $table->decimal('precio', 12, 2);

        $table->unsignedInteger('paginas')->nullable();
        $table->unsignedSmallInteger('anio_edicion')->nullable();

        $table->text('informacion')->nullable();
        $table->text('mensaje_correo')->nullable();

        $table->string('portada');
        $table->string('archivo_pdf');

        $table->enum('estado', [
            'borrador',
            'publicado'
        ])->default('borrador');

        $table->boolean('destacado')->default(false);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
