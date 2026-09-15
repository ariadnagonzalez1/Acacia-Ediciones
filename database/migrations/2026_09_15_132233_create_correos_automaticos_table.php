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
    Schema::create('correos_automaticos', function (Blueprint $table) {
        $table->id();

        $table->enum('tipo', [
            'entrega_compra',
            'promocion'
        ])->unique();

        $table->string('asunto');

        $table->text('mensaje');

        $table->boolean('activo')
            ->default(true);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correos_automaticos');
    }
};
