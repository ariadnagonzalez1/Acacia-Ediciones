<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorreoAutomatico extends Model
{
    use HasFactory;

    protected $table = 'correos_automaticos';

    protected $fillable = [
        'tipo',
        'asunto',
        'mensaje',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }
}