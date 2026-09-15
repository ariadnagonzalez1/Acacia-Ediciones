<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $ventas = Venta::query()
            ->with([
                'cliente',
                'detalles.libro',
            ])
            ->when(
                $request->filled('buscar'),
                function ($query) use ($request) {

                    $buscar = trim($request->buscar);

                    $query->where(function ($subquery) use ($buscar) {

                        $subquery
                            ->where(
                                'numero_orden',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhereHas(
                                'cliente',
                                function ($clienteQuery) use ($buscar) {

                                    $clienteQuery
                                        ->where(
                                            'nombre',
                                            'like',
                                            "%{$buscar}%"
                                        )
                                        ->orWhere(
                                            'email',
                                            'like',
                                            "%{$buscar}%"
                                        );
                                }
                            );
                    });
                }
            )
            ->latest('fecha_pago')
            ->paginate(20)
            ->withQueryString();

        $recaudado = Venta::sum('total');

        $cantidadVentas = Venta::count();

        $titulosVendidos = VentaDetalle::count();

        return view(
            'admin.ventas.index',
            compact(
                'ventas',
                'recaudado',
                'cantidadVentas',
                'titulosVendidos'
            )
        );
    }
}