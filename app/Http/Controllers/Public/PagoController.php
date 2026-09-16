<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function exito(Request $request)
    {
        session()->forget('carrito');

        return view('public.pago.exito', [
            'externalReference' => $request->query('external_reference'),
        ]);
    }

    public function pendiente(Request $request)
    {
        return view('public.pago.pendiente', [
            'externalReference' => $request->query('external_reference'),
        ]);
    }

    public function error(Request $request)
    {
        return view('public.pago.error');
    }
}