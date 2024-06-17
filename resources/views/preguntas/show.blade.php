@extends('layouts.app')

@section ('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Detalles de la pregunta</h2>
        <div title="{{ $encuesta->compartida ? 'La encuesta está compartida, por lo que no se puede editar.' : '' }}">
            @if($encuesta->compartida)
                <button class="btn btn-warning btn-sm" disabled>Editar pregunta</button>
            @else
                <a href="{{ route('preguntas.edit', ['idEncuesta' => $preguntas->idEncuesta, 'preguntas' => $preguntas->idPregunta]) }}" class="btn btn-warning btn-sm">Editar pregunta</a>
            @endif
            <a href="{{ route('preguntas.index', ['idEncuesta' => $preguntas->idEncuesta]) }}" class="btn btn-secondary btn-sm">Volver al listado de preguntas</a>
        </div>
    </div>
    <hr>
    <div class="card">
        <div class="card-header">{{ $preguntas->contenidoPregunta }}</div>
        <div class="card-body">
            <p><strong>Descripción: </strong>{{ $preguntas->descripcionPregunta }}</p>
            <p><strong>Tipo de pregunta: </strong>{{ $preguntas->tipoPregunta->nombreTipoPregunta }}</p>
            <p><strong>Criterio de validación: </strong>{{ $preguntas->criterioValidacion }}</p>

            <!-- Mostrar las opciones para la pregunta -->
            <h5 class="mt-4">Opciones:</h5>
            <ul class="list-group">
                @foreach ($preguntas->opciones as $opcion)
                    <li class="list-group-item">{{ $opcion->contenidoOpcion }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</main>
@endsection