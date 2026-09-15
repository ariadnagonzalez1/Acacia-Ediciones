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
    Schema::create('promociones', function (Blueprint $table) {
        $table->id();

        $table->string('nombre');

        $table->enum('tipo', [
            'descuento',
            'kit'
        ]);

        $table->decimal('porcentaje_descuento', 5, 2)
            ->nullable();

        $table->decimal('precio_kit', 12, 2)
            ->nullable();

        $table->text('mensaje')->nullable();

        $table->date('fecha_inicio')->nullable();
        $table->date('fecha_fin')->nullable();

        $table->boolean('activa')
            ->default(false);

        $table->boolean('mostrar_inicio')
            ->default(false);

        $table->boolean('enviar_correo')
            ->default(false);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
