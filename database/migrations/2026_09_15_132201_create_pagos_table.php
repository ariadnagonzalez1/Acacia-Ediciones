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
    Schema::create('pagos', function (Blueprint $table) {
        $table->id();

        $table->foreignId('venta_id')
            ->unique()
            ->constrained('ventas')
            ->cascadeOnDelete();

        $table->string('mercado_pago_payment_id')
            ->unique();

        $table->string('mercado_pago_preference_id')
            ->nullable();

        $table->decimal('monto', 12, 2);

        $table->string('metodo_pago')
            ->nullable();

        $table->timestamp('fecha_pago');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
