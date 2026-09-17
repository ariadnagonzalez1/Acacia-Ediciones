<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('mercado_pago_order_id')
                ->nullable()
                ->unique()
                ->after('venta_id');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn('mercado_pago_order_id');
        });
    }
};