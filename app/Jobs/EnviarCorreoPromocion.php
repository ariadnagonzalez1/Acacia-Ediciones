<?php

namespace App\Jobs;

use App\Models\Cliente;
use App\Models\CorreoAutomatico;
use App\Models\EnvioCorreo;
use App\Models\Promocion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;


class EnviarCorreoPromocion implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public int $promocionId
    ) {}

    public function handle(): void
    {
        $promocion = Promocion::find($this->promocionId);

        if (!$promocion) {
            return;
        }

        $correoAutomatico = CorreoAutomatico::where(
            'tipo',
            'promocion'
        )
            ->where('activo', true)
            ->first();

        if (!$correoAutomatico) {
            Log::warning(
                'No hay correo automático de promoción activo'
            );

            return;
        }

        $asunto = $correoAutomatico->asunto;
        $mensajeConfigurado = $correoAutomatico->mensaje;

        Cliente::where('acepta_promociones', true)
            ->chunk(50, function ($clientes) use (
                $promocion,
                $asunto,
                $mensajeConfigurado
            ) {

                foreach ($clientes as $cliente) {
                    $this->enviarACliente(
                        $cliente,
                        $promocion,
                        $asunto,
                        $mensajeConfigurado
                    );
                }
            });
    }

    private function enviarACliente(
        Cliente $cliente,
        Promocion $promocion,
        string $asunto,
        string $mensajeConfigurado
    ): void {

        $envio = EnvioCorreo::create([
            'venta_id' => null,
            'cliente_id' => $cliente->id,
            'tipo' => 'promocion',
            'email_destino' => $cliente->email,
            'asunto' => $asunto,
            'enviado' => false,
        ]);

        try {

            $nombreCliente = e($cliente->nombre);
            $mensajeSeguro = nl2br(e($mensajeConfigurado));
            $nombrePromocion = e($promocion->nombre);
            $mensajePromo = $promocion->mensaje
                ? nl2br(e($promocion->mensaje))
                : null;

            $bloqueMensajePromo = $mensajePromo
                ? "<p>{$mensajePromo}</p>"
                : '';

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
                            {$nombrePromocion}
                        </strong>
                    </p>

                    {$bloqueMensajePromo}
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

            Mail::html(
                $html,
                function ($message) use ($cliente, $asunto) {
                    $message
                        ->to($cliente->email, $cliente->nombre)
                        ->subject($asunto);
                }
            );

            $envio->update([
                'enviado' => true,
                'enviado_at' => now(),
            ]);

        } catch (Throwable $e) {

            $envio->update([
                'enviado' => false,
                'error' => $e->getMessage(),
            ]);

            Log::error(
                'Error enviando correo de promoción',
                [
                    'cliente_id' => $cliente->id,
                    'promocion_id' => $promocion->id,
                    'mensaje' => $e->getMessage(),
                ]
            );
        }
    }
}