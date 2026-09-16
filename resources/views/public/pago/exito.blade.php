@extends('layouts.public')

@section('title', '¡Gracias por tu compra! | Acacia Ediciones')

@section('content')
<section class="public-section">
    <div class="public-container">
        <h1>¡Gracias por tu compra!</h1>
        <p>Ya recibimos tu pago. En breve vas a recibir los ebooks en tu correo.</p>
        <a href="{{ route('inicio') }}" class="public-primary-button">Volver al inicio</a>
    </div>
</section>
@endsection