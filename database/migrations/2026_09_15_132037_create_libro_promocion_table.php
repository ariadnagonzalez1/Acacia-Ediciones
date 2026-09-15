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
    Schema::create('libro_promocion', function (Blueprint $table) {
        $table->id();

        $table->foreignId('libro_id')
            ->constrained('libros')
            ->cascadeOnDelete();

        $table->foreignId('promocion_id')
            ->constrained('promociones')
            ->cascadeOnDelete();

        $table->timestamps();

        $table->unique([
            'libro_id',
            'promocion_id'
        ]);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_promocion');
    }
};
