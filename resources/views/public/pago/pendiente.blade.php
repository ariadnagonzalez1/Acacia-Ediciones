@extends('layouts.public')

@section('title', 'Pago pendiente | Acacia Ediciones')

@section('content')
<section class="public-section">
    <div class="public-container">
        <h1>Tu pago está en proceso</h1>
        <p>Te vamos a avisar por correo apenas se confirme.</p>
        <a href="{{ route('inicio') }}" class="public-primary-button">Volver al inicio</a>
    </div>
</section>
@endsection
