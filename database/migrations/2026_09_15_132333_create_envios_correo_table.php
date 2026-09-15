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
    Schema::create('envios_correo', function (Blueprint $table) {
        $table->id();

        $table->foreignId('venta_id')
            ->nullable()
            ->constrained('ventas')
            ->cascadeOnDelete();

        $table->foreignId('cliente_id')
            ->constrained('clientes')
            ->cascadeOnDelete();

        $table->enum('tipo', [
            'entrega_compra',
            'promocion'
        ]);

        $table->string('email_destino');

        $table->string('asunto');

        $table->boolean('enviado')
            ->default(false);

        $table->timestamp('enviado_at')
            ->nullable();

        $table->text('error')
            ->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envios_correo');
    }
};
