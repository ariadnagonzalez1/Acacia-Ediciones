<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CorreoAutomatico;
use App\Models\Libro;
use App\Models\Pago;
use App\Models\Promocion;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class MercadoPagoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREAR ORDEN
    |--------------------------------------------------------------------------
    */

    public function crearOrden(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'documento' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
        ]);

        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()
                ->route('carrito.index')
                ->with('error', 'El carrito está vacío.');
        }

        try {

            $resultado = DB::transaction(function () use ($datos, $carrito) {

                /*
                |--------------------------------------------------------------------------
                | 1. CLIENTE
                |--------------------------------------------------------------------------
                */

                $cliente = Cliente::updateOrCreate(
                    [
                        'email' => $datos['email'],
                    ],
                    [
                        'nombre' => $datos['nombre'],
                        'documento' => $datos['documento'],
                        'telefono' => $datos['telefono'] ?? null,
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | 2. PREPARAR PEDIDO
                |--------------------------------------------------------------------------
                */

                $itemsMercadoPago = [];
                $productosProcesados = [];
                $total = 0;

                foreach ($carrito as $item) {

                    $tipo = $item['tipo'] ?? null;
                    $cantidad = max(1, (int) ($item['cantidad'] ?? 1));

                    /*
                    |--------------------------------------------------------------------------
                    | LIBRO INDIVIDUAL
                    |--------------------------------------------------------------------------
                    */

                    if ($tipo === 'libro') {

                        $libro = Libro::findOrFail($item['id']);

                        if ($libro->estado !== 'publicado') {
                            throw new \Exception(
                                "El libro {$libro->titulo} ya no está disponible."
                            );
                        }

                        $precio = (float) $libro->precio;
                        $subtotal = $precio * $cantidad;

                        $total += $subtotal;

                        $itemsMercadoPago[] = [
                            'title' => $libro->titulo,
                            'quantity' => $cantidad,
                            'unit_price' => number_format(
                                $precio,
                                2,
                                '.',
                                ''
                            ),
                        ];

                        $productosProcesados[] = [
                            'tipo' => 'libro',
                            'libro' => $libro,
                            'cantidad' => $cantidad,
                            'precio' => $precio,
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | KIT
                    |--------------------------------------------------------------------------
                    */

                    if ($tipo === 'kit') {

                        $promocion = Promocion::with('libros')
                            ->findOrFail($item['id']);

                        if (
                            !$promocion->activa ||
                            $promocion->tipo !== 'kit' ||
                            $promocion->precio_kit === null
                        ) {
                            throw new \Exception(
                                'Uno de los kits ya no está disponible.'
                            );
                        }

                        if (
                            $promocion->fecha_inicio &&
                            today()->lt($promocion->fecha_inicio)
                        ) {
                            throw new \Exception(
                                'Uno de los kits todavía no comenzó.'
                            );
                        }

                        if (
                            $promocion->fecha_fin &&
                            today()->gt($promocion->fecha_fin)
                        ) {
                            throw new \Exception(
                                'Uno de los kits ya finalizó.'
                            );
                        }

                        if ($promocion->libros->isEmpty()) {
                            throw new \Exception(
                                'Uno de los kits no contiene libros.'
                            );
                        }

                        $precioKit = (float) $promocion->precio_kit;
                        $subtotal = $precioKit * $cantidad;

                        $total += $subtotal;

                        $itemsMercadoPago[] = [
                            'title' => $promocion->nombre,
                            'quantity' => $cantidad,
                            'unit_price' => number_format(
                                $precioKit,
                                2,
                                '.',
                                ''
                            ),
                        ];

                        $productosProcesados[] = [
                            'tipo' => 'kit',
                            'promocion' => $promocion,
                            'cantidad' => $cantidad,
                            'precio' => $precioKit,
                        ];
                    }
                }

                if ($total <= 0) {
                    throw new \Exception(
                        'El total del pedido no es válido.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 3. VENTA
                |--------------------------------------------------------------------------
                */

                $externalReference = 'ACACIA-' . Str::uuid();

                $venta = Venta::create([
                    'cliente_id' => $cliente->id,
                    'numero_orden' => $externalReference,
                    'total' => $total,
                    'fecha_pago' => null,
                    'correo_enviado_at' => null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | 4. DETALLES DE VENTA
                |--------------------------------------------------------------------------
                */

                foreach ($productosProcesados as $producto) {

                    /*
                    |--------------------------------------------------------------------------
                    | LIBROS
                    |--------------------------------------------------------------------------
                    */

                    if ($producto['tipo'] === 'libro') {

                        $libro = $producto['libro'];

                        for (
                            $i = 0;
                            $i < $producto['cantidad'];
                            $i++
                        ) {

                            VentaDetalle::create([
                                'venta_id' => $venta->id,
                                'libro_id' => $libro->id,
                                'promocion_id' => null,
                                'precio_original' => $producto['precio'],
                                'descuento' => 0,
                                'precio_final' => $producto['precio'],
                            ]);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | KITS
                    |--------------------------------------------------------------------------
                    */

                    if ($producto['tipo'] === 'kit') {

                        $promocion = $producto['promocion'];

                        for (
                            $unidad = 0;
                            $unidad < $producto['cantidad'];
                            $unidad++
                        ) {
                            $this->crearDetallesKit(
                                $venta,
                                $promocion
                            );
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 5. CREAR ORDER EN MERCADO PAGO
                |--------------------------------------------------------------------------
                */

                $baseUrl = rtrim(
                    config('app.url'),
                    '/'
                );

                $response = Http::withToken(
                    config('services.mercadopago.access_token')
                )
                    ->acceptJson()
                    ->withHeaders([
                        'X-Idempotency-Key' => (string) Str::uuid(),
                    ])
                    ->post(
                        'https://api.mercadopago.com/v1/orders',
                        [
                            'type' => 'online',
                            'processing_mode' => 'manual',

                            'total_amount' => number_format(
                                $total,
                                2,
                                '.',
                                ''
                            ),

                            'external_reference' => $externalReference,

                            'description' =>
                                'Compra de ebooks - Acacia Ediciones',

                            'payer' => [
                                'email' => $cliente->email,
                            ],

                            'items' => $itemsMercadoPago,

                            'config' => [
                                'online' => [

                                    'success_url' =>
                                        $baseUrl .
                                        '/checkout/exito',

                                    'failure_url' =>
                                        $baseUrl .
                                        '/checkout/error',

                                    'pending_url' =>
                                        $baseUrl .
                                        '/checkout/pendiente',

                                    'auto_return' => 'approved',
                                ],
                            ],
                        ]
                    );

                if ($response->failed()) {

                    Log::error(
                        'Error Mercado Pago creando Order',
                        [
                            'status' => $response->status(),
                            'body' => $response->body(),
                            'response' => $response->json(),
                        ]
                    );

                    throw new \Exception(
                        'Mercado Pago rechazó la creación de la orden.'
                    );
                }

                $orden = $response->json();

                if (
                    empty($orden['id']) ||
                    empty($orden['checkout_url'])
                ) {
                    throw new \Exception(
                        'Mercado Pago no devolvió los datos de la orden.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 6. GUARDAR PAGO
                |--------------------------------------------------------------------------
                */

                Pago::create([
                    'venta_id' => $venta->id,
                    'mercado_pago_order_id' => $orden['id'],
                    'mercado_pago_payment_id' => null,
                    'mercado_pago_preference_id' => null,
                    'monto' => $total,
                    'metodo_pago' => null,
                    'fecha_pago' => null,
                ]);

                return [
                    'checkout_url' => $orden['checkout_url'],
                ];
            });

            return redirect()->away(
                $resultado['checkout_url']
            );

        } catch (Throwable $e) {

            Log::error(
                'Error creando compra',
                [
                    'mensaje' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No se pudo iniciar la compra. Intentá nuevamente.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR DETALLES DEL KIT
    |--------------------------------------------------------------------------
    */

    private function crearDetallesKit(
        Venta $venta,
        Promocion $promocion
    ): void {

        $libros = $promocion->libros;

        $precioKit = (float) $promocion->precio_kit;

        $totalOriginal = $libros->sum(function ($libro) {
            return (float) $libro->precio;
        });

        $cantidadLibros = $libros->count();

        $acumuladoFinal = 0;

        foreach ($libros as $indice => $libro) {

            $precioOriginal = (float) $libro->precio;

            if ($totalOriginal > 0) {

                $proporcion =
                    $precioOriginal /
                    $totalOriginal;

                $precioFinal = round(
                    $precioKit * $proporcion,
                    2
                );

            } else {

                $precioFinal = round(
                    $precioKit /
                    max(1, $cantidadLibros),
                    2
                );
            }

            if ($indice === $cantidadLibros - 1) {

                $precioFinal = round(
                    $precioKit - $acumuladoFinal,
                    2
                );
            }

            $descuento = max(
                0,
                $precioOriginal - $precioFinal
            );

            VentaDetalle::create([
                'venta_id' => $venta->id,
                'libro_id' => $libro->id,
                'promocion_id' => $promocion->id,
                'precio_original' => $precioOriginal,
                'descuento' => $descuento,
                'precio_final' => $precioFinal,
            ]);

            $acumuladoFinal += $precioFinal;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | WEBHOOK MERCADO PAGO
    |--------------------------------------------------------------------------
    */

    public function webhook(Request $request)
    {
        try {

            $orderId = $this->obtenerOrderIdWebhook(
                $request
            );

            if (!$orderId) {

                Log::warning(
                    'Webhook Mercado Pago sin Order ID',
                    [
                        'query' => $request->query(),
                        'body' => $request->all(),
                    ]
                );

                return response()->json(
                    ['ok' => false],
                    400
                );
            }

            if (
                !$this->firmaWebhookValida(
                    $request,
                    $orderId
                )
            ) {

                Log::warning(
                    'Firma Mercado Pago inválida',
                    [
                        'order_id' => $orderId,
                    ]
                );

                return response()->json(
                    ['ok' => false],
                    401
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CONSULTAR ORDER REAL EN MERCADO PAGO
            |--------------------------------------------------------------------------
            */

            $response = Http::withToken(
                config(
                    'services.mercadopago.access_token'
                )
            )
                ->acceptJson()
                ->get(
                    'https://api.mercadopago.com/v1/orders/' .
                    urlencode($orderId)
                );

            if ($response->failed()) {

                Log::error(
                    'No se pudo consultar Order Mercado Pago',
                    [
                        'order_id' => $orderId,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]
                );

                return response()->json(
                    ['ok' => false],
                    500
                );
            }

            $orden = $response->json();

            /*
            |--------------------------------------------------------------------------
            | SOLO SI EL PAGO ESTÁ ACREDITADO
            |--------------------------------------------------------------------------
            */

            if (
                ($orden['status'] ?? null) !== 'processed' ||
                ($orden['status_detail'] ?? null) !== 'accredited'
            ) {

                return response()->json([
                    'ok' => true,
                    'message' =>
                        'Order todavía no acreditada.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | BUSCAR VENTA
            |--------------------------------------------------------------------------
            */

            $pago = Pago::with([
                'venta.cliente',
                'venta.detalles.libro',
            ])
                ->where(
                    'mercado_pago_order_id',
                    $orderId
                )
                ->first();

            if (!$pago) {

                Log::error(
                    'No existe Pago para la Order',
                    [
                        'order_id' => $orderId,
                    ]
                );

                return response()->json(
                    ['ok' => false],
                    404
                );
            }

            $venta = $pago->venta;

            /*
            |--------------------------------------------------------------------------
            | VALIDAR REFERENCIA
            |--------------------------------------------------------------------------
            */

            if (
                ($orden['external_reference'] ?? null)
                !== $venta->numero_orden
            ) {

                Log::error(
                    'External reference no coincide',
                    [
                        'order_id' => $orderId,
                        'esperado' => $venta->numero_orden,
                        'recibido' =>
                            $orden['external_reference']
                            ?? null,
                    ]
                );

                return response()->json(
                    ['ok' => false],
                    409
                );
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDAR MONTO
            |--------------------------------------------------------------------------
            */

            $montoPagado = (float) (
                $orden['total_paid_amount']
                ??
                $orden['total_amount']
                ??
                0
            );

            if (
                abs(
                    $montoPagado -
                    (float) $venta->total
                ) > 0.01
            ) {

                Log::error(
                    'Monto de Mercado Pago no coincide',
                    [
                        'venta' => $venta->id,
                        'esperado' => $venta->total,
                        'pagado' => $montoPagado,
                    ]
                );

                return response()->json(
                    ['ok' => false],
                    409
                );
            }

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR PAGO
            |--------------------------------------------------------------------------
            */

            $transaccion = data_get(
                $orden,
                'transactions.payments.0'
            );

            DB::transaction(function () use (
                $venta,
                $pago,
                $transaccion
            ) {

                $venta->update([
                    'fecha_pago' =>
                        $venta->fecha_pago
                        ?? now(),
                ]);

                $pago->update([
                    'mercado_pago_payment_id' =>
                        data_get(
                            $transaccion,
                            'id'
                        ),

                    'metodo_pago' =>
                        data_get(
                            $transaccion,
                            'payment_method.id'
                        )
                        ??
                        data_get(
                            $transaccion,
                            'payment_method.type'
                        ),

                    'fecha_pago' =>
                        $pago->fecha_pago
                        ?? now(),
                ]);
            });

            /*
            |--------------------------------------------------------------------------
            | ENVIAR EBOOKS
            |--------------------------------------------------------------------------
            */

            $this->enviarEbooks(
                $venta->fresh([
                    'cliente',
                    'detalles.libro',
                ])
            );

            return response()->json([
                'ok' => true,
            ]);

        } catch (Throwable $e) {

            Log::error(
                'Error procesando webhook Mercado Pago',
                [
                    'mensaje' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return response()->json(
                ['ok' => false],
                500
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | OBTENER ORDER ID DEL WEBHOOK
    |--------------------------------------------------------------------------
    */

    private function obtenerOrderIdWebhook(
        Request $request
    ): ?string {

        $orderId =
            $request->query('data.id')
            ??
            $request->query('data_id');

        if (!$orderId) {

            $orderId = data_get(
                $request->all(),
                'data.id'
            );
        }

        return $orderId
            ? (string) $orderId
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDAR FIRMA DEL WEBHOOK
    |--------------------------------------------------------------------------
    */

    private function firmaWebhookValida(
        Request $request,
        string $orderId
    ): bool {

        $secret = config(
            'services.mercadopago.webhook_secret'
        );

        $xSignature = $request->header(
            'x-signature'
        );

        $xRequestId = $request->header(
            'x-request-id'
        );

        if (
            !$secret ||
            !$xSignature ||
            !$xRequestId
        ) {
            return false;
        }

        $timestamp = null;
        $firmaRecibida = null;

        foreach (
            explode(',', $xSignature)
            as $parte
        ) {

            [$clave, $valor] = array_pad(
                explode(
                    '=',
                    trim($parte),
                    2
                ),
                2,
                null
            );

            if ($clave === 'ts') {
                $timestamp = $valor;
            }

            if ($clave === 'v1') {
                $firmaRecibida = $valor;
            }
        }

        if (
            !$timestamp ||
            !$firmaRecibida
        ) {
            return false;
        }

        $idFirma = strtolower($orderId);

        $manifest =
            "id:{$idFirma};" .
            "request-id:{$xRequestId};" .
            "ts:{$timestamp};";

        $firmaCalculada = hash_hmac(
            'sha256',
            $manifest,
            $secret
        );

        return hash_equals(
            $firmaCalculada,
            $firmaRecibida
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ENVIAR EBOOKS
    |--------------------------------------------------------------------------
    */

    private function enviarEbooks(
        Venta $venta
    ): void {

        /*
        |--------------------------------------------------------------------------
        | EVITAR DUPLICADOS
        |--------------------------------------------------------------------------
        */

        $reservado = Venta::where(
            'id',
            $venta->id
        )
            ->whereNull(
                'correo_enviado_at'
            )
            ->update([
                'correo_enviado_at' => now(),
            ]);

        if ($reservado === 0) {
            return;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | LIBROS ÚNICOS DE LA VENTA
            |--------------------------------------------------------------------------
            */

            $libros = $venta->detalles
                ->pluck('libro')
                ->filter()
                ->unique('id')
                ->values();

            if ($libros->isEmpty()) {
                throw new \Exception(
                    'La venta no contiene libros para enviar.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CORREO CONFIGURADO DESDE EL ADMIN
            |--------------------------------------------------------------------------
            */

            $correoAutomatico = CorreoAutomatico::where(
                'tipo',
                'entrega_compra'
            )->first();

            $asunto = $correoAutomatico?->asunto
                ?: 'Tu compra en Acacia Ediciones';

            $mensajeConfigurado = $correoAutomatico?->mensaje
                ?: '¡Gracias por tu compra! Adjuntamos los ebooks que adquiriste.';

            /*
            |--------------------------------------------------------------------------
            | PDF ADJUNTOS
            |--------------------------------------------------------------------------
            */

            $adjuntos = [];

            foreach ($libros as $libro) {

                $ruta = $this->obtenerRutaPdf(
                    $libro
                );

                if (!$ruta) {

                    throw new \Exception(
                        "No se encontró el PDF del libro: {$libro->titulo}"
                    );
                }

                $adjuntos[] = [
                    'ruta' => $ruta,
                    'nombre' =>
                        Str::slug($libro->titulo)
                        . '.pdf',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | LISTA DE LIBROS PARA EL EMAIL
            |--------------------------------------------------------------------------
            */

            $listaLibros = $libros
                ->map(function ($libro) {

                    return '<li>'
                        . e($libro->titulo)
                        . '</li>';
                })
                ->implode('');

            $nombreCliente = e(
                $venta->cliente->nombre
            );

            $mensajeSeguro = nl2br(
                e($mensajeConfigurado)
            );

            /*
            |--------------------------------------------------------------------------
            | CUERPO DEL CORREO
            |--------------------------------------------------------------------------
            */

            $html = <<<HTML
                <div style="
                    font-family:Arial,sans-serif;
                    line-height:1.6;
                    color:#222;
                    max-width:650px;
                    margin:auto;
                ">

                    <h2 style="color:#22352b;">
                        Acacia Ediciones
                    </h2>

                    <p>
                        Hola {$nombreCliente},
                    </p>

                    <p>
                        {$mensajeSeguro}
                    </p>

                    <p>
                        <strong>
                            Ebooks incluidos en tu compra:
                        </strong>
                    </p>

                    <ul>
                        {$listaLibros}
                    </ul>

                    <p>
                        Número de orden:
                        <strong>
                            {$venta->numero_orden}
                        </strong>
                    </p>

                    <p>
                        Los archivos PDF se encuentran
                        adjuntos a este correo.
                    </p>

                    <hr style="
                        border:none;
                        border-top:1px solid #ddd;
                        margin:25px 0;
                    ">

                    <p style="
                        font-size:13px;
                        color:#777;
                    ">
                        Acacia Ediciones
                    </p>

                </div>
            HTML;

            /*
            |--------------------------------------------------------------------------
            | ENVIAR CORREO
            |--------------------------------------------------------------------------
            */

            Mail::html(
                $html,
                function ($message) use (
                    $venta,
                    $adjuntos,
                    $asunto
                ) {

                    $message
                        ->to(
                            $venta->cliente->email,
                            $venta->cliente->nombre
                        )
                        ->subject($asunto);

                    foreach ($adjuntos as $adjunto) {

                        $message->attach(
                            $adjunto['ruta'],
                            [
                                'as' => $adjunto['nombre'],
                                'mime' => 'application/pdf',
                            ]
                        );
                    }
                }
            );

            Log::info(
                'Ebooks enviados correctamente',
                [
                    'venta_id' => $venta->id,
                    'email' =>
                        $venta->cliente->email,
                    'libros' =>
                        $libros
                            ->pluck('titulo')
                            ->toArray(),
                ]
            );

        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | PERMITIR REINTENTO SI EL EMAIL FALLÓ
            |--------------------------------------------------------------------------
            */

            Venta::where(
                'id',
                $venta->id
            )->update([
                'correo_enviado_at' => null,
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ENCONTRAR PDF
    |--------------------------------------------------------------------------
    */

    private function obtenerRutaPdf(
        Libro $libro
    ): ?string {

        if (!$libro->archivo_pdf) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | STORAGE PRIVADO
        |--------------------------------------------------------------------------
        */

        if (
            Storage::disk('local')
                ->exists(
                    $libro->archivo_pdf
                )
        ) {

            return Storage::disk('local')
                ->path(
                    $libro->archivo_pdf
                );
        }

        /*
        |--------------------------------------------------------------------------
        | STORAGE PÚBLICO
        |--------------------------------------------------------------------------
        */

        if (
            Storage::disk('public')
                ->exists(
                    $libro->archivo_pdf
                )
        ) {

            return Storage::disk('public')
                ->path(
                    $libro->archivo_pdf
                );
        }

        return null;
    }
}