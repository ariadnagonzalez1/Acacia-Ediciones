<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Libro;
use App\Models\Venta;
use App\Models\VentaDetalle;

class DashboardController extends Controller
{
    public function index()
    {
        $recaudado = Venta::sum('total');

        $cantidadVentas = Venta::count();

        $titulosPublicados = Libro::query()
            ->where('estado', 'publicado')
            ->count();

        $correosGuardados = Cliente::count();

        $titulosVendidos = VentaDetalle::count();

        $ultimasVentas = Venta::query()
            ->with([
                'cliente',
                'detalles.libro'
            ])
            ->latest('fecha_pago')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'recaudado',
            'cantidadVentas',
            'titulosPublicados',
            'correosGuardados',
            'titulosVendidos',
            'ultimasVentas'
        ));
    }
}