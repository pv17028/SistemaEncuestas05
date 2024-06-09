@extends('layouts.app')
@section('content')
<style>
        /* Aplicar color de fondo y color de texto 
        main {
          
            color: {{ $encuestaCompartida->color_secundario ?? 'default_color' }};
        }
        #preguntas{
            background-color: {{ $encuestaCompartida->color_principal ?? 'default_color' }};
            color: {{ $encuestaCompartida->color_texto ?? 'default_color' }};
        }*/
    </style>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                @if($encuestaCompartida->logo)
                    <img src="/images/{{ $encuestaCompartida->logo }}" alt="Logo de la Encuesta" height="50" class="me-2">
                @endif
                <h2 class="mb-0">{{ $encuestaCompartida->titulo }}</h2>
            </div>
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

                <div class="card-body" id="preguntas">
                    @foreach ($encuestaCompartida->preguntas as $pregunta)
                        <div class="card mt-2">
                            <div class="card-header">
                                <label>{{ $pregunta->contenidoPregunta }}</label>
                            </div>
                            <div class="card-body">
                                <p><strong>Descripción:</strong> {{ $pregunta->descripcionPregunta }}</p>
                                <p><strong>Criterio de Validación:</strong> {{ $pregunta->criterioValidacion }}</p>
                                @switch($pregunta->tipoPregunta->nombreTipoPregunta)
                                    @case('Preguntas dicotómicas')
                                    @case('Preguntas politómicas')
                                    @case('Preguntas de elección múltiple')
                                        @foreach ($pregunta->opciones as $opcion)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->idPregunta }}]" id="opcion{{ $opcion->idOpcion }}" value="{{ $opcion->idOpcion }}">
                                                <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                                    {{ $opcion->contenidoOpcion }}
                                                </label>
                                            </div>
                                        @endforeach
                                        @break
                                    @case('Preguntas de tipo ranking')
                                        @foreach ($pregunta->opciones as $opcion)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="respuestas[{{ $pregunta->idPregunta }}][]" id="opcion{{ $opcion->idOpcion }}" value="{{ $opcion->idOpcion }}">
                                                <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                                    {{ $opcion->contenidoOpcion }}
                                                </label>
                                            </div>
                                        @endforeach
                                        @break
                                    @case('Escala numérica')
                                        @foreach ($pregunta->opciones as $opcion)
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->idPregunta }}]" id="opcion{{ $opcion->idOpcion }}" value="{{ $opcion->idOpcion }}">
                                                <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                                    {{ $opcion->contenidoOpcion }}
                                                </label>
                                            </div>
                                        @endforeach
                                        @break
                                    @case('Escala de Likert')
                                        @foreach ($pregunta->opciones as $opcion)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->idPregunta }}]" id="opcion{{ $opcion->idOpcion }}" value="{{ $opcion->idOpcion }}">
                                                <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                                    {{ $opcion->contenidoOpcion }}
                                                </label>
                                            </div>
                                        @endforeach
                                        @break
                                    @case('Preguntas mixtas')
                                        @foreach ($pregunta->opciones as $opcion)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->idPregunta }}][]" id="opcion{{ $opcion->idOpcion }}" value="{{ $opcion->idOpcion }}" onchange="toggleInput('{{ $pregunta->idPregunta }}', '{{ $opcion->contenidoOpcion }}')">
                                                <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                                    {{ $opcion->contenidoOpcion }}
                                                </label>
                                            </div>
                                        @endforeach
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="otra{{ $pregunta->idPregunta }}" name="otra[{{ $pregunta->idPregunta }}]" disabled>
                                        </div>
                                        @break
                                    @case('Preguntas abiertas')
                                        <div class="form-group">
                                            <label for="respuesta{{ $pregunta->idPregunta }}">Respuesta:</label>
                                            <textarea class="form-control" id="respuesta{{ $pregunta->idPregunta }}" name="respuestas[{{ $pregunta->idPregunta }}]"></textarea>
                                        </div>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <br>
            <button type="submit" class="btn btn-primary btn-sm">Enviar Respuestas</button>
            <br>
            <br>
            <script>
                function toggleInput(idPregunta, contenidoOpcion) {
                    var input = document.getElementById('otra' + idPregunta);
                    if (contenidoOpcion === 'Otra') {
                        input.disabled = false;
                    } else {
                        input.disabled = true;
                    }
                }
            </script>
        </form>
    </main>
@endsection