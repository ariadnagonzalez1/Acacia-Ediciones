@props([
    'titulo',
    'valor',
    'detalle' => null,
])

<article class="stat-card">

    <span class="stat-card-title">
        {{ $titulo }}
    </span>

    <strong class="stat-card-value">
        {{ $valor }}
    </strong>

    @if($detalle)

        <span class="stat-card-detail">
            {{ $detalle }}
        </span>

    @endif

</article>