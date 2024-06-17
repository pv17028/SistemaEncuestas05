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
                        <label for="contenidoPregunta" class="form-label">Contenido de la pregunta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="contenidoPregunta" name="contenidoPregunta"
                            value="{{ $preguntas->contenidoPregunta }}">
                    </div>

                    <div class="mb-3">
                        <label for="descripcionPregunta" class="form-label">Descripción de la pregunta <span class="text-danger">*</span></label>
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
                        <label for="idTipoPregunta">Tipo de pregunta <span class="text-danger">*</span></label>
                        <select class="form-select" id="idTipoPregunta" name="idTipoPregunta" required
                            onchange="updateMessage(this)">
                            <option value="">Seleccione un tipo de pregunta</option>
                            @foreach ($tiposPreguntas as $tipoPregunta)
                                @if ($tipoPregunta->habilitado || $preguntas->idTipoPregunta == $tipoPregunta->idTipoPregunta)
                                    <option value="{{ $tipoPregunta->nombreTipoPregunta }}"
                                        data-description="{{ $tipoPregunta->descripcionTipoPregunta }}"
                                        {{ $preguntas->idTipoPregunta == $tipoPregunta->idTipoPregunta ? 'selected' : '' }}>
                                        {{ $tipoPregunta->nombreTipoPregunta }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <p id="tipoPreguntaMessage"></p>
                    </div>

                    <script>
                        function updateMessage(selectElement) {
                            var messageElement = document.getElementById('tipoPreguntaMessage');

                            if (selectElement.value) {
                                var selectedOption = selectElement.options[selectElement.selectedIndex];
                                messageElement.textContent = selectedOption.getAttribute('data-description');
                            } else {
                                messageElement.textContent = '';
                            }
                        }

                        // Actualiza el mensaje inicialmente al cargar la página
                        window.onload = function() {
                            updateMessage(document.getElementById('idTipoPregunta'));
                        };
                    </script>

                    <div id="Preguntas dicotómicas"
                        style="display: {{ $preguntas->idTipoPregunta == 'Preguntas dicotómicas' ? 'block' : 'none' }};">
                        <div class="form-group mb-3">
                            <label for="opcion1">Opción 1 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="opcion1" name="opcionesDicotomicas[]"
                                value="{{ $preguntas->opciones[0]->contenidoOpcion ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="opcion2">Opción 2 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="opcion2" name="opcionesDicotomicas[]"
                                value="{{ $preguntas->opciones[1]->contenidoOpcion ?? '' }}">
                        </div>
                    </div>

                    <div id="Preguntas politómicas"
                         style="display: {{ $preguntas->idTipoPregunta == 'Preguntas politómicas' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label for="opcionesPolitomicas">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opcionesPolitomicas" name="opcionesPolitomicas" rows="3"
                                      placeholder="Ingrese las opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3">{{ implode(', ', array_map(function($opcion) { return strpos($opcion->contenidoOpcion, ',') !== false ? '(' . $opcion->contenidoOpcion . ')' : $opcion->contenidoOpcion; }, $preguntas->opciones->all())) }}</textarea>
                        </div>
                    </div>

                    <div id="Preguntas de elección múltiple"
                         style="display: {{ $preguntas->idTipoPregunta == 'Preguntas de elección múltiple' ? 'block' : 'none' }};">
                        <div class="form-group mb-3">
                            <label for="opciones">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opciones" name="opcionesMultiple" rows="3"
                                      placeholder="Ingrese las opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3">{{ implode(', ', array_map(function($opcion) { return strpos($opcion->contenidoOpcion, ',') !== false ? '(' . $opcion->contenidoOpcion . ')' : $opcion->contenidoOpcion; }, $preguntas->opciones->all())) }}</textarea>
                        </div>
                    </div>

                    <div id="Preguntas de tipo ranking"
                         style="display: {{ $preguntas->idTipoPregunta == 'Preguntas de tipo ranking' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label for="opcionesRanking">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opcionesRanking" name="opcionesRanking" rows="3"
                                      placeholder="Ingrese 4 o 5 opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3">{{ implode(', ', array_map(function($opcion) { return strpos($opcion->contenidoOpcion, ',') !== false ? '(' . $opcion->contenidoOpcion . ')' : $opcion->contenidoOpcion; }, $preguntas->opciones->all())) }}</textarea>
                        </div>
                    </div>

                    <div id="Escala de Likert"
                         style="display: {{ $preguntas->idTipoPregunta == 'Escala de Likert' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label for="opcionesLikert">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opcionesLikert" name="opcionesLikert" rows="3"
                                      placeholder="Ingrese las opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3">{{ implode(', ', array_map(function($opcion) { return strpos($opcion->contenidoOpcion, ',') !== false ? '(' . $opcion->contenidoOpcion . ')' : $opcion->contenidoOpcion; }, $preguntas->opciones->all())) }}</textarea>
                        </div>
                    </div>

                    <div id="Escala numérica"
                        style="display: {{ $preguntas->idTipoPregunta == 'Escala numérica' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label for="escalaNumerica">Rango máximo de la escala <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="100" class="form-control"
                                id="escalaNumerica" name="escalaNumerica" value="{{ $preguntas->opciones->count() }}"
                                style="width: 80px;">
                        </div>
                    </div>

                    <div id="Preguntas mixtas"
                         style="display: {{ $preguntas->tipoPregunta->nombreTipoPregunta == 'Preguntas mixtas' ? 'block' : 'none' }};">
                        <div class="form-group mb-3">
                            <label for="opcionesMixtas">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opcionesMixtas" name="opcionesMixtas" rows="3"
                                      placeholder="Ingrese las opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3">{{ implode(', ', array_map(function($opcion) { return strpos($opcion->contenidoOpcion, ',') !== false ? '(' . $opcion->contenidoOpcion . ')' : $opcion->contenidoOpcion; }, $preguntas->opciones->all())) }}</textarea>
                            <small class="form-text text-muted">Añade "Otra" al final si quieres permitir una respuesta
                                abierta.</small>
                        </div>
                    </div>

                    <script>
                        document.getElementById('idTipoPregunta').addEventListener('change', function() {
                            // Oculta todos los divs
                            document.getElementById('Preguntas dicotómicas').style.display = 'none';
                            document.getElementById('Preguntas politómicas').style.display = 'none';
                            document.getElementById('Preguntas de elección múltiple').style.display = 'none';
                            document.getElementById('Preguntas de tipo ranking').style.display = 'none';
                            document.getElementById('Escala de Likert').style.display = 'none';
                            document.getElementById('Escala numérica').style.display = 'none';
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
            <p><span class="text-danger">*</span> Indica un campo obligatorio</p>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                <a href="{{ route('preguntas.index', ['idEncuesta' => $preguntas->idEncuesta]) }}"
                    class="btn btn-secondary btn-sm">Cancelar</a>
            </div>
        </form>
    </main>
@endsection
