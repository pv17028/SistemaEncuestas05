@extends('layouts.app')

@section ('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalles del Tipo de pregunta</h2>
            <div>
                <a href="{{ route('tiposPreguntas.edit', $tipoPregunta->idTipoPregunta) }}" class="btn btn-warning btn-sm">Editar Tipo de pregunta</a>
                <a href="{{ route('tiposPreguntas.index') }}" class="btn btn-secondary btn-sm">Volver al listado de tipo de preguntas</a>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header">{{ $tipoPregunta->nombreTipoPregunta }}</div>
            <div class="card-body">
                <p><strong>Descripción: </strong>{{ $tipoPregunta->descripcionTipoPregunta }}</p>
                <p><strong>Habilitado: </strong>{{ $tipoPregunta->habilitado ? 'Sí' : 'No' }}</p> <!-- Agrega esta línea -->
            </div>
        </div>
    </main>

@endsection