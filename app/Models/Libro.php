<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';

    protected $fillable = [
        'categoria_id',
        'titulo',
        'autor',
        'precio',
        'paginas',
        'anio_edicion',
        'informacion',
        'mensaje_correo',
        'portada',
        'archivo_pdf',
        'estado',
        'destacado',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'paginas' => 'integer',
            'anio_edicion' => 'integer',
            'destacado' => 'boolean',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function promociones(): BelongsToMany
    {
        return $this->belongsToMany(
            Promocion::class,
            'libro_promocion'
        )->withTimestamps();
    }

    public function ventaDetalles(): HasMany
    {
        return $this->hasMany(VentaDetalle::class);
    }
}