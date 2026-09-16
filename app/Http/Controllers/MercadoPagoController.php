<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MercadoPagoController extends Controller
{
    public function crearOrden(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'documento' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
        ]);

        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()
                ->route('carrito.index')
                ->with('error', 'El carrito está vacío.');
        }

        $items = [];
        $total = 0;

        foreach ($carrito as $item) {

            $cantidad = (int) ($item['cantidad'] ?? 1);
            $precio = (float) ($item['precio'] ?? 0);

            $total += $precio * $cantidad;

            $items[] = [
                'title' => $item['titulo'] ?? 'Ebook Acacia Ediciones',
                'quantity' => $cantidad,
                'unit_price' => number_format($precio, 2, '.', ''),
            ];
        }

        if ($total <= 0) {
            return back()->with(
                'error',
                'No se pudo calcular correctamente el total del pedido.'
            );
        }

        $externalReference = 'ACACIA-' . Str::uuid();

        // Guardamos los datos del comprador y del pedido en sesión
        // para poder identificarlo cuando llegue el webhook.
        session()->put('pedido_pendiente', [
            'external_reference' => $externalReference,
            'nombre' => $datos['nombre'],
            'documento' => $datos['documento'],
            'email' => $datos['email'],
            'telefono' => $datos['telefono'] ?? null,
            'carrito' => $carrito,
            'total' => $total,
        ]);

        $documentoLimpio = preg_replace('/\D/', '', $datos['documento']);

        $payload = [

            'type' => 'online',

            'processing_mode' => 'manual',

            'total_amount' => number_format($total, 2, '.', ''),

            'external_reference' => $externalReference,

            'description' => 'Compra en Acacia Ediciones',

            'payer' => [
                'email' => $datos['email'],
                'identification' => [
                    'type' => 'DNI',
                    'number' => $documentoLimpio,
                ],
            ],

            'items' => $items,

            'config' => [
                'notification_url' => route('mercadopago.webhook'),
                'online' => [
                    'success_url' => route('pago.exito') . '?external_reference=' . $externalReference,
                    'failure_url' => route('pago.error'),
                    'pending_url' => route('pago.pendiente') . '?external_reference=' . $externalReference,
                    'auto_return' => 'approved',
                ],
            ],
        ];

        $response = Http::withToken(
            config('services.mercadopago.access_token')
        )
            ->acceptJson()
            ->withHeaders([
                'X-Idempotency-Key' => (string) Str::uuid(),
            ])
            ->post('https://api.mercadopago.com/v1/orders', $payload);

        if ($response->failed()) {

            Log::error('Mercado Pago: error al crear order', [
                'status' => $response->status(),
                'body' => $response->json(),
                'payload' => $payload,
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No pudimos iniciar el pago. Probá de nuevo en unos minutos.'
                );
        }

        $orden = $response->json();

        if (empty($orden['checkout_url'])) {

            Log::error(
                'Mercado Pago no devolvió checkout_url',
                [
                    'orden' => $orden,
                    'external_reference' => $externalReference,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Mercado Pago no devolvió la URL de pago.'
                );
        }

        return redirect()->away(
            $orden['checkout_url']
        );
    }

    public function webhook(Request $request)
    {
        Log::info('Mercado Pago webhook recibido', $request->all());

        // TODO: acá vamos a:
        // 1. Leer el "type"/"topic" y el "data.id" (o "resource") que manda MP.
        // 2. Consultar el order/payment real contra la API de MP con ese id.
        // 3. Si status == accredited/approved, buscar el 'pedido_pendiente'
        //    por external_reference (conviene guardarlo en una tabla, no solo
        //    en sesión, porque la sesión del comprador puede no existir más
        //    cuando llega el webhook) y disparar el envío de los ebooks.

        return response()->json(['received' => true]);
    }
}