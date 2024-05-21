@extends('layouts.app')

@section ('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalles de la pregunta</h2>
            <div>
                <a href="{{ route('preguntas.edit', $preguntas->idPregunta) }}" class="btn btn-warning btn-sm">Editar pregunta</a>
                <a href="{{ route('preguntas.index') }}" class="btn btn-secondary btn-sm">Volver al listado de preguntas</a>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header">{{ $preguntas->contenidoPregunta }}</div>
            <div class="card-body">
                <p><strong>Descripción: </strong>{{ $preguntas-> descripcionPregunta }}</p>
            </div>
        </div>
    </main>
@endsection
