<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use App\Models\Promocion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);

        /*
        |--------------------------------------------------------------------------
        | Refrescar precios actuales desde la base
        |--------------------------------------------------------------------------
        */

        foreach ($carrito as $clave => &$item) {

            if (($item['tipo'] ?? null) === 'libro') {

                $libro = Libro::find($item['id']);

                if ($libro) {
                    $item['precio'] = (float) $libro->precio;

                    $item['subtotal'] =
                        $item['precio'] *
                        ($item['cantidad'] ?? 1);
                }
            }

            if (($item['tipo'] ?? null) === 'kit') {

                $promocion = Promocion::find($item['id']);

                if ($promocion && $promocion->precio_kit !== null) {
                    $item['precio'] =
                        (float) $promocion->precio_kit;

                    $item['subtotal'] =
                        $item['precio'] *
                        ($item['cantidad'] ?? 1);
                }
            }
        }

        unset($item);

        session()->put('carrito', $carrito);

        $total = collect($carrito)->sum('subtotal');

        return view(
            'public.carrito.index',
            compact('carrito', 'total')
        );
    }

    public function agregarLibro(
        Libro $libro,
        Request $request
    ) {
        if ($libro->estado !== 'publicado') {
            abort(404);
        }

        $carrito = session()->get('carrito', []);

        $clave = 'libro_' . $libro->id;

        /*
        |--------------------------------------------------------------------------
        | Precio actual desde la base
        |--------------------------------------------------------------------------
        */

        $precioActual = (float) $libro->precio;

        if (isset($carrito[$clave])) {

            $cantidadActual =
                $carrito[$clave]['cantidad'] ?? 1;

            $cantidadActual++;

            /*
            |--------------------------------------------------------------------------
            | Actualizar datos y precio actual
            |--------------------------------------------------------------------------
            */

            $carrito[$clave]['titulo'] =
                $libro->titulo;

            $carrito[$clave]['autor'] =
                $libro->autor;

            $carrito[$clave]['portada'] =
                $libro->portada;

            $carrito[$clave]['precio'] =
                $precioActual;

            $carrito[$clave]['cantidad'] =
                $cantidadActual;

            $carrito[$clave]['subtotal'] =
                $precioActual * $cantidadActual;

        } else {

            $carrito[$clave] = [
                'tipo' => 'libro',

                'id' => $libro->id,

                'titulo' => $libro->titulo,

                'autor' => $libro->autor,

                'portada' => $libro->portada,

                'precio' => $precioActual,

                'cantidad' => 1,

                'subtotal' => $precioActual,
            ];
        }

        session()->put(
            'carrito',
            $carrito
        );

        $cantidadTotal =
            collect($carrito)
                ->sum(function ($item) {
                    return $item['cantidad'] ?? 1;
                });

        if ($request->expectsJson()) {

            return response()->json([
                'ok' => true,

                'message' =>
                    'Libro agregado al carrito.',

                'count' =>
                    $cantidadTotal,

                'quantity' =>
                    $carrito[$clave]['cantidad'] ?? 1,

                'subtotal' =>
                    $carrito[$clave]['subtotal'],
            ]);
        }

        return back()->with(
            'success',
            'Libro agregado al carrito.'
        );
    }

    public function actualizarCantidad(
        Request $request,
        string $clave
    ) {
        $datos = $request->validate([
            'operacion' => [
                'required',
                'in:incrementar,decrementar',
            ],
        ]);

        $carrito = session()->get(
            'carrito',
            []
        );

        if (!isset($carrito[$clave])) {
            return redirect()
                ->route('carrito.index');
        }

        $cantidad =
            $carrito[$clave]['cantidad'] ?? 1;

        if ($datos['operacion'] === 'incrementar') {
            $cantidad++;
        }

        if ($datos['operacion'] === 'decrementar') {
            $cantidad--;
        }

        if ($cantidad < 1) {

            unset($carrito[$clave]);

        } else {

            /*
            |--------------------------------------------------------------------------
            | Volver a consultar el precio actual
            |--------------------------------------------------------------------------
            */

            if (($carrito[$clave]['tipo'] ?? null) === 'libro') {

                $libro = Libro::find(
                    $carrito[$clave]['id']
                );

                if ($libro) {
                    $carrito[$clave]['precio'] =
                        (float) $libro->precio;
                }
            }

            if (($carrito[$clave]['tipo'] ?? null) === 'kit') {

                $promocion = Promocion::find(
                    $carrito[$clave]['id']
                );

                if (
                    $promocion &&
                    $promocion->precio_kit !== null
                ) {
                    $carrito[$clave]['precio'] =
                        (float) $promocion->precio_kit;
                }
            }

            $carrito[$clave]['cantidad'] =
                $cantidad;

            $carrito[$clave]['subtotal'] =
                (float) $carrito[$clave]['precio']
                * $cantidad;
        }

        session()->put(
            'carrito',
            $carrito
        );

        return redirect()
            ->route('carrito.index');
    }

    public function agregarKit(
        Promocion $promocion,
        Request $request
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validar promoción
        |--------------------------------------------------------------------------
        */

        if (
            !$promocion->activa ||
            $promocion->tipo !== 'kit'
        ) {

            if ($request->expectsJson()) {

                return response()->json([
                    'ok' => false,

                    'message' =>
                        'Esta promoción no está disponible.',
                ], 404);
            }

            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Validar fecha de inicio
        |--------------------------------------------------------------------------
        */

        if (
            $promocion->fecha_inicio &&
            today()->lt($promocion->fecha_inicio)
        ) {

            if ($request->expectsJson()) {

                return response()->json([
                    'ok' => false,

                    'message' =>
                        'Esta promoción todavía no comenzó.',
                ], 422);
            }

            return back()->with(
                'error',
                'Esta promoción todavía no comenzó.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validar fecha de finalización
        |--------------------------------------------------------------------------
        */

        if (
            $promocion->fecha_fin &&
            today()->gt($promocion->fecha_fin)
        ) {

            if ($request->expectsJson()) {

                return response()->json([
                    'ok' => false,

                    'message' =>
                        'Esta promoción ya finalizó.',
                ], 422);
            }

            return back()->with(
                'error',
                'Esta promoción ya finalizó.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cargar libros del kit
        |--------------------------------------------------------------------------
        */

        $promocion->load('libros');

        if ($promocion->libros->isEmpty()) {

            if ($request->expectsJson()) {

                return response()->json([
                    'ok' => false,

                    'message' =>
                        'Este kit no tiene libros asociados.',
                ], 422);
            }

            return back()->with(
                'error',
                'Este kit no tiene libros asociados.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validar precio
        |--------------------------------------------------------------------------
        */

        if ($promocion->precio_kit === null) {

            if ($request->expectsJson()) {

                return response()->json([
                    'ok' => false,

                    'message' =>
                        'Este kit no tiene un precio configurado.',
                ], 422);
            }

            return back()->with(
                'error',
                'Este kit no tiene un precio configurado.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Obtener carrito
        |--------------------------------------------------------------------------
        */

        $carrito = session()->get(
            'carrito',
            []
        );

        $clave =
            'kit_' . $promocion->id;

        $precioActual =
            (float) $promocion->precio_kit;

        /*
        |--------------------------------------------------------------------------
        | Si ya existe, aumentar cantidad
        |--------------------------------------------------------------------------
        */

        if (isset($carrito[$clave])) {

            $cantidadActual =
                $carrito[$clave]['cantidad'] ?? 1;

            $cantidadActual++;

            /*
            |--------------------------------------------------------------------------
            | Refrescar datos y precio actual
            |--------------------------------------------------------------------------
            */

            $carrito[$clave]['titulo'] =
                $promocion->nombre;

            $carrito[$clave]['libros'] =
                $promocion
                    ->libros
                    ->pluck('titulo')
                    ->values()
                    ->toArray();

            $carrito[$clave]['precio'] =
                $precioActual;

            $carrito[$clave]['cantidad'] =
                $cantidadActual;

            $carrito[$clave]['subtotal'] =
                $precioActual * $cantidadActual;

        } else {

            $carrito[$clave] = [
                'tipo' => 'kit',

                'id' =>
                    $promocion->id,

                'titulo' =>
                    $promocion->nombre,

                'libros' =>
                    $promocion
                        ->libros
                        ->pluck('titulo')
                        ->values()
                        ->toArray(),

                'precio' =>
                    $precioActual,

                'cantidad' => 1,

                'subtotal' =>
                    $precioActual,
            ];
        }

        session()->put(
            'carrito',
            $carrito
        );

        $cantidadTotal =
            collect($carrito)
                ->sum(function ($item) {

                    return $item['cantidad'] ?? 1;
                });

        if ($request->expectsJson()) {

            return response()->json([
                'ok' => true,

                'message' =>
                    'Kit agregado al carrito.',

                'count' =>
                    $cantidadTotal,

                'quantity' =>
                    $carrito[$clave]['cantidad'] ?? 1,

                'subtotal' =>
                    $carrito[$clave]['subtotal'],
            ]);
        }

        return back()->with(
            'success',
            'Kit agregado al carrito.'
        );
    }

    public function eliminar(
        string $clave
    ): RedirectResponse {
        $carrito = session()->get(
            'carrito',
            []
        );

        unset($carrito[$clave]);

        session()->put(
            'carrito',
            $carrito
        );

        return back()->with(
            'success',
            'Producto eliminado del carrito.'
        );
    }

    public function vaciar(): RedirectResponse
    {
        session()->forget('carrito');

        return redirect()
            ->route('carrito.index')
            ->with(
                'success',
                'Carrito vaciado.'
            );
    }
}