<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvioCorreo extends Model
{
    use HasFactory;

    protected $table = 'envios_correo';

    protected $fillable = [
        'venta_id',
        'cliente_id',
        'tipo',
        'email_destino',
        'asunto',
        'enviado',
        'enviado_at',
        'error',
    ];

    protected function casts(): array
    {
        return [
            'enviado' => 'boolean',
            'enviado_at' => 'datetime',
        ];
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}