<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()
                ->route('catalogo')
                ->with(
                    'error',
                    'Tu carrito está vacío.'
                );
        }

        $total = collect($carrito)->sum('subtotal');

        return view('public.checkout.index', compact(
            'carrito',
            'total'
        ));
    }
}