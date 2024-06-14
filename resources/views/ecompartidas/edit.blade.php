@extends('layouts.app')
@section('content')
    <style>
        /* Aplicar color de fondo y color de texto */
        main {
            background-color: {{ $encuestaCompartida->color_principal ?? 'default_color' }};
            color: {{ $encuestaCompartida->color_texto ?? 'default_color' }};
        }

        .card {
            border-color: {{ $encuestaCompartida->color_quinto == '#ffffff' ? '#000000' : $encuestaCompartida->color_quinto ?? 'default_color' }};
            background-color: {{ $encuestaCompartida->color_quinto ?? 'default_color' }};
            color: {{ $encuestaCompartida->color_quinto == '#000000' ? '#ffffff' : 'default_text_color' }};
        }

        .card-header {
            border-color: {{ $encuestaCompartida->color_septimo == '#ffffff' ? '#000000' : $encuestaCompartida->color_septimo ?? 'default_color' }};
            background-color: {{ $encuestaCompartida->color_septimo ?? 'default_color' }};
            color: {{ $encuestaCompartida->color_septimo == '#000000' ? '#ffffff' : 'default_text_color' }};
        }

        .title {
            background-color: {{ $encuestaCompartida->color_cuarto ?? 'default_color' }};
            color: {{ $encuestaCompartida->color_cuarto == '#000000' ? '#ffffff' : 'default_text_color' }};
        }

        .btn-primary {
            background-color: {{ $encuestaCompartida->color_sexto ?? 'default_color' }};
            border-color: {{ $encuestaCompartida->color_sexto ?? 'default_color' }};
            color: {{ $encuestaCompartida->color_sexto == '#ffffff' ? '#0056b3' : 'default_text_color' }};
        }
    </style>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center title" style="border-radius: 5px; padding: 10px;">
                @if ($encuestaCompartida->logo)
                    <img src="{{ asset('images/' . $encuestaCompartida->logo) }}" alt="Logo de la Encuesta" height="50"
                        class="me-2">
                @endif
                <h2 class="mb-0" style="color: {{ $encuestaCompartida->color_terciario ?? 'default_color' }};">
                    {{ $encuestaCompartida->titulo }}</h2>
            </div>
            <div>
                <a href="{{ route('ecompartidas.index') }}" class="btn btn-secondary btn-sm">Volver a las encuestas
                    compartidas</a>
            </div>
        </div>

        <hr>

        <form action="{{ route('ecompartidas.update', $encuestaCompartida->idEncuesta) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card mb-5"
                style="background-color: {{ $encuestaCompartida->color_secundario ?? 'default_color' }};
                        border-color: {{ $encuestaCompartida->color_secundario == '#ffffff' ? '#000000' : $encuestaCompartida->color_secundario ?? 'default_color' }};
                        color: {{ $encuestaCompartida->color_secundario == '#000000' ? '#ffffff' : 'default_text_color' }};">
                <div class="card-body d-flex flex-column justify-content-center align-items-start">
                    <p><strong>Descripción:</strong> {{ $encuestaCompartida->descripcionEncuesta }}</p>
                    <p><strong>Objetivo:</strong> {{ $encuestaCompartida->objetivo }}</p>
                    <p><strong>Grupo Meta:</strong> {{ $encuestaCompartida->grupoMeta }}</p>
                    @if ($encuestaCompartida->es_anonima)
                        <p class="my-auto"><strong>Esta es una encuesta anónima. Tu respuesta será registrada sin asociarla
                                a tu usuario.</strong></p>
                    @else
                        <p class="my-auto"><strong>Esta encuesta registrará tu usuario. Tu respuesta será asociada a tu
                                cuenta de usuario.</strong></p>
                    @endif
                </div>
            </div>

            @foreach ($encuestaCompartida->preguntas as $pregunta)
                <div class="card mt-2">
                    <div class="card-header">
                        <label><strong>{{ $pregunta->contenidoPregunta }}</strong></label>
                    </div>
                    <div class="card-body">
                        <p><strong>Descripción:</strong> {{ $pregunta->descripcionPregunta }}</p>
                        <p><strong>Criterio de Validación:</strong> {{ $pregunta->criterioValidacion }}</p>
                        @switch($pregunta->tipoPregunta->nombreTipoPregunta)
                            @case('Preguntas dicotómicas')
                                @foreach ($pregunta->opciones as $opcion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="respuestas[{{ $pregunta->idPregunta }}][opcion_id]" id="opcion{{ $opcion->idOpcion }}"
                                            value="{{ $opcion->idOpcion }}"
                                            {{ in_array((string) $opcion->idOpcion, $respuestas[$pregunta->idPregunta]['opcion_id'] ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                            {{ $opcion->contenidoOpcion }}
                                        </label>
                                    </div>
                                @endforeach
                            @break
                            
                            @case('Preguntas politómicas')
                                @foreach ($pregunta->opciones as $opcion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="respuestas[{{ $pregunta->idPregunta }}][opcion_id]" id="opcion{{ $opcion->idOpcion }}"
                                            value="{{ $opcion->idOpcion }}"
                                            {{ in_array((string) $opcion->idOpcion, $respuestas[$pregunta->idPregunta]['opcion_id'] ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                            {{ $opcion->contenidoOpcion }}
                                        </label>
                                    </div>
                                @endforeach
                            @break

                            @case('Preguntas de elección múltiple')
                                @foreach ($pregunta->opciones as $opcion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="respuestas[{{ $pregunta->idPregunta }}][]" id="opcion{{ $opcion->idOpcion }}"
                                            value="{{ $opcion->idOpcion }}"
                                            {{ in_array($opcion->idOpcion, $respuestas[$pregunta->idPregunta]['opcion_id'] ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                            {{ $opcion->contenidoOpcion }}
                                        </label>
                                    </div>
                                @endforeach
                            @break

                            @case('Preguntas de tipo ranking')
                                @foreach ($pregunta->opciones as $opcion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="respuestas[{{ $pregunta->idPregunta }}][opcion_id]" id="opcion{{ $opcion->idOpcion }}"
                                            value="{{ $opcion->idOpcion }}"
                                            {{ in_array((string) $opcion->idOpcion, $respuestas[$pregunta->idPregunta]['opcion_id'] ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                            {{ $opcion->contenidoOpcion }}
                                        </label>
                                    </div>
                                @endforeach
                            @break
                            
                            @case('Escala numérica')
                                @foreach ($pregunta->opciones as $opcion)
                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            name="respuestas[{{ $pregunta->idPregunta }}][opcion_id]" id="opcion{{ $opcion->idOpcion }}"
                                            value="{{ $opcion->idOpcion }}"
                                            {{ in_array((string) $opcion->idOpcion, $respuestas[$pregunta->idPregunta]['opcion_id'] ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                            {{ $opcion->contenidoOpcion }}
                                        </label>
                                    </div>
                                @endforeach
                            @break
                            
                            @case('Escala de Likert')
                                @foreach ($pregunta->opciones as $opcion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="respuestas[{{ $pregunta->idPregunta }}][opcion_id]" id="opcion{{ $opcion->idOpcion }}"
                                            value="{{ $opcion->idOpcion }}"
                                            {{ in_array((string) $opcion->idOpcion, $respuestas[$pregunta->idPregunta]['opcion_id'] ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                            {{ $opcion->contenidoOpcion }}
                                        </label>
                                    </div>
                                @endforeach
                            @break

                            @case('Preguntas mixtas')
                                @foreach ($pregunta->opciones as $opcion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="respuestas[{{ $pregunta->idPregunta }}][opcion_id]" id="opcion{{ $opcion->idOpcion }}"
                                            value="{{ $opcion->idOpcion }}"
                                            {{ in_array((string) $opcion->idOpcion, $respuestas[$pregunta->idPregunta]['opcion_id'] ?? []) ? 'checked' : '' }}
                                            onchange="toggleInput('{{ $pregunta->idPregunta }}', '{{ $opcion->contenidoOpcion }}')">
                                        <label class="form-check-label" for="opcion{{ $opcion->idOpcion }}">
                                            {{ $opcion->contenidoOpcion }}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="form-group">
                                    <input type="text" class="form-control" id="otra{{ $pregunta->idPregunta }}"
                                        name="respuestas[{{ $pregunta->idPregunta }}][respuesta_abierta]"
                                        value="{{ $respuestas[$pregunta->idPregunta]['respuesta_abierta'] ?? '' }}">
                                </div>
                            @break
                            
                            @case('Preguntas abiertas')
                                <div class="form-group">
                                    <label for="respuesta{{ $pregunta->idPregunta }}">Respuesta:</label>
                                    <textarea class="form-control" id="respuesta{{ $pregunta->idPregunta }}"
                                        name="respuestas[{{ $pregunta->idPregunta }}]">{{ $respuestas[$pregunta->idPregunta]['respuesta_abierta'] ?? '' }}</textarea>
                                </div>
                            @break
                        @endswitch
                    </div>
                </div>
            @endforeach

            <br>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-sm">Enviar Respuestas</button>
            </div>
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
