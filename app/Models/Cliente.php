<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'documento',
        'email',
        'telefono',
        'acepta_promociones',
    ];

    protected function casts(): array
    {
        return [
            'acepta_promociones' => 'boolean',
        ];
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function enviosCorreo(): HasMany
    {
        return $this->hasMany(EnvioCorreo::class);
    }
}