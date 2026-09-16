@extends('layouts.public')

@section('title', 'Hubo un problema | Acacia Ediciones')

@section('content')
<section class="public-section">
    <div class="public-container">
        <h1>No pudimos procesar el pago</h1>
        <p>Podés intentar de nuevo desde el carrito.</p>
        <a href="{{ route('carrito.index') }}" class="public-primary-button">Volver al carrito</a>
    </div>
</section>
@endsection