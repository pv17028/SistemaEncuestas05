@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Editar Pregunta</h2>
        </div>
        <hr>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form
            action="{{ route('preguntas.update', ['idEncuesta' => $preguntas->idEncuesta, 'preguntas' => $preguntas->idPregunta]) }}"
            method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="idEncuesta" value="{{ $preguntas->idEncuesta }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contenidoPregunta" class="form-label">Pregunta</label>
                        <input type="text" class="form-control" id="contenidoPregunta" name="contenidoPregunta"
                            value="{{ $preguntas->contenidoPregunta }}">
                    </div>

                    <div class="mb-3">
                        <label for="descripcionPregunta" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcionPregunta" name="descripcionPregunta" rows="3">{{ $preguntas->descripcionPregunta }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="criterioValidacion" class="form-label">Criterio de validación</label>
                        <input type="text" class="form-control" id="criterioValidacion" name="criterioValidacion"
                            value="{{ $preguntas->criterioValidacion }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="idTipoPregunta">Tipo de pregunta</label>
                        <select class="form-select" id="idTipoPregunta" name="idTipoPregunta" required>
                            <option value="">Seleccione un tipo de pregunta</option>
                            @foreach ($tiposPreguntas as $tipoPregunta)
                                <option value="{{ $tipoPregunta->nombreTipoPregunta }}"
                                    {{ $preguntas->idTipoPregunta == $tipoPregunta->idTipoPregunta ? 'selected' : '' }}>
                                    {{ $tipoPregunta->nombreTipoPregunta }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="Preguntas de elección múltiple"
                        style="display: {{ $preguntas->idTipoPregunta == 'Preguntas de elección múltiple' ? 'block' : 'none' }};">
                        <div class="form-group mb-3">
                            <label for="opciones">Opciones de respuesta</label>
                            <textarea class="form-control" id="opciones" name="opcionesMultiple" rows="3">{{ implode(',', $preguntas->opciones->pluck('contenidoOpcion')->toArray()) }}</textarea>
                        </div>
                    </div>

                    <div id="Preguntas mixtas"
                        style="display: {{ $preguntas->tipoPregunta->nombreTipoPregunta == 'Preguntas mixtas' ? 'block' : 'none' }};">
                        <div class="form-group mb-3">
                            <label for="opcionesMixtas">Opciones de respuesta</label>
                            <textarea class="form-control" id="opcionesMixtas" name="opcionesMixtas" rows="3">{{ implode(',', $preguntas->opciones->pluck('contenidoOpcion')->toArray()) }}</textarea>
                            <small class="form-text text-muted">Añade "Otra" al final si quieres permitir una respuesta
                                abierta.</small>
                        </div>
                    </div>

                    <script>
                        document.getElementById('idTipoPregunta').addEventListener('change', function() {
                            // Oculta todos los divs
                            document.getElementById('Preguntas de elección múltiple').style.display = 'none';
                            document.getElementById('Preguntas mixtas').style.display = 'none';

                            // Muestra el div correspondiente al tipo de pregunta seleccionado
                            var selectedOption = this.options[this.selectedIndex].text; // Cambia value por text
                            if (document.getElementById(selectedOption)) {
                                document.getElementById(selectedOption).style.display = 'block';
                            }
                        });

                        // Muestra el div correspondiente al tipo de pregunta guardado cuando se carga la página
                        var savedOption = '{{ $preguntas->tipoPregunta->nombreTipoPregunta }}';
                        if (document.getElementById(savedOption)) {
                            document.getElementById(savedOption).style.display = 'block';
                        }
                    </script>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                <a href="{{ route('preguntas.index', ['idEncuesta' => $preguntas->idEncuesta]) }}"
                    class="btn btn-secondary btn-sm">Cancelar</a>
            </div>
        </form>
    </main>
@endsection
