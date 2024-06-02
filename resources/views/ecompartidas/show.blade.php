@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ $encuestaCompartida->titulo }}</h2>
            <div>
                <a href="{{ route('ecompartidas.index') }}" class="btn btn-secondary btn-sm">Volver a las encuestas compartidas</a>
            </div>
        </div>
        <hr>

        <form action="{{ route('ecompartidas.store', $encuestaCompartida->idEncuesta) }}" method="POST">
            @csrf

            <div class="card mb-3">
                {{-- <div class="card-header">
                    {{ $encuestaCompartida->titulo }}
                </div> --}}
                <div class="card-body">
                    <p><strong>Descripción:</strong> {{ $encuestaCompartida->descripcionEncuesta }}</p>
                    <p><strong>Objetivo:</strong> {{ $encuestaCompartida->objetivo }}</p>
                    <p><strong>Grupo Meta:</strong> {{ $encuestaCompartida->grupoMeta }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Preguntas
                </div>
                <div class="card-body">
                    @foreach ($encuestaCompartida->preguntas as $pregunta)
                        <div class="card mt-2">
                            <div class="card-header">
                                <label>{{ $pregunta->contenidoPregunta }}</label>
                            </div>
                            <div class="card-body">
                                <p><strong>Descripción:</strong> {{ $pregunta->descripcionPregunta }}</p>
                                <p><strong>Criterio de Validación:</strong> {{ $pregunta->criterioValidacion }}</p>
                                @foreach ($pregunta->opciones as $opcion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->idPregunta }}]" id="opcion{{ $opcion->idOpcion }}" value="{{ $opcion->idOpcion }}">
                                        <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                            {{ $opcion->contenidoOpcion }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <br>
            <button type="submit" class="btn btn-primary btn-sm">Enviar Respuestas</button>
        </form>
    </main>
@endsection