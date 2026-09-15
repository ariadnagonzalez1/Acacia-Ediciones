<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';

    protected $fillable = [
        'nombre',
        'tipo',
        'porcentaje_descuento',
        'precio_kit',
        'mensaje',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'mostrar_inicio',
        'enviar_correo',
    ];

    protected function casts(): array
    {
        return [
            'porcentaje_descuento' => 'decimal:2',
            'precio_kit' => 'decimal:2',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'activa' => 'boolean',
            'mostrar_inicio' => 'boolean',
            'enviar_correo' => 'boolean',
        ];
    }

    public function libros(): BelongsToMany
    {
        return $this->belongsToMany(
            Libro::class,
            'libro_promocion'
        );
    }

    public function ventaDetalles(): HasMany
    {
        return $this->hasMany(
            VentaDetalle::class
        );
    }
}