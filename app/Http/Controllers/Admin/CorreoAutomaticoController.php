<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorreoAutomatico;
use Illuminate\Http\Request;

class CorreoAutomaticoController extends Controller
{
    public function index()
    {
        $correos = CorreoAutomatico::query()
            ->orderBy('tipo')
            ->get();

        return view(
            'admin.correos.index',
            compact('correos')
        );
    }


    public function update(
        Request $request,
        CorreoAutomatico $correo
    ) {
        $datos = $request->validate([
            'asunto' => [
                'required',
                'string',
                'max:255',
            ],

            'mensaje' => [
                'required',
                'string',
            ],
        ]);


        $datos['activo'] =
            $request->boolean('activo');


        $correo->update($datos);


        return redirect()
            ->route('admin.correos.index')
            ->with(
                'success',
                'Correo automático actualizado correctamente.'
            );
    }
}