<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::query()
            ->withCount('ventas')
            ->withMax(
                'ventas',
                'fecha_pago'
            )
            ->when(
                $request->filled('buscar'),
                function ($query) use ($request) {

                    $buscar = trim(
                        $request->buscar
                    );

                    $query->where(function ($subquery) use ($buscar) {

                        $subquery
                            ->where(
                                'nombre',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhere(
                                'documento',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhere(
                                'telefono',
                                'like',
                                "%{$buscar}%"
                            );
                    });
                }
            )
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        $cantidadClientes = Cliente::count();

        return view(
            'admin.clientes.index',
            compact(
                'clientes',
                'cantidadClientes'
            )
        );
    }
}