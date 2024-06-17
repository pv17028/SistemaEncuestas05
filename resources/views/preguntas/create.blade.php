@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Crear Pregunta</h2>
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

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('preguntas.store', ['idEncuesta' => $idEncuesta]) }}">
            @csrf
            <input type="hidden" name="idEncuesta" value="{{ $idEncuesta }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombreRol">Contenido de la pregunta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="contenidoPregunta" name="contenidoPregunta"
                            placeholder="Ingrese el contenido de la pregunta" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="descripcionPregunta">Descripción de la pregunta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="descripcionPregunta" name="descripcionPregunta"
                            placeholder="Ingrese la descripción de la pregunta" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="criterioValidacion">Criterio de validación</label>
                        <input type="text" class="form-control" id="criterioValidacion" name="criterioValidacion"
                            placeholder="Ingrese el criterio de validación" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="idTipoPregunta">Tipo de pregunta <span class="text-danger">*</span></label>
                        <select class="form-select" id="idTipoPregunta" name="idTipoPregunta" required
                            onchange="updateMessage(this)">
                            <option value="">Seleccione un tipo de pregunta</option>
                            @foreach ($tiposPreguntas as $tipoPregunta)
                                @if ($tipoPregunta->habilitado)
                                    <!-- Agrega esta línea -->
                                    <option value="{{ $tipoPregunta->nombreTipoPregunta }}"
                                        data-description="{{ $tipoPregunta->descripcionTipoPregunta }}">
                                        {{ $tipoPregunta->nombreTipoPregunta }}
                                    </option>
                                @endif <!-- Agrega esta línea -->
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

                    <div id="Preguntas dicotómicas" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="opcion1">Opción 1 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="opcion1" name="opcionesDicotomicas[]">
                        </div>
                        <div class="form-group">
                            <label for="opcion2">Opción 2 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="opcion2" name="opcionesDicotomicas[]">
                        </div>
                    </div>

                    <div id="Preguntas politómicas" style="display: none;">
                        <div class="form-group">
                            <label for="opcionesPolitomicas">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opcionesPolitomicas" name="opcionesPolitomicas" rows="3"
                                placeholder="Ingrese las opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3"></textarea>
                        </div>
                    </div>

                    <div id="Preguntas de elección múltiple" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="opciones">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opciones" name="opcionesMultiple" rows="3"
                                placeholder="Ingrese las opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3"></textarea>
                        </div>
                    </div>

                    <div id="Preguntas de tipo ranking" style="display: none;">
                        <div class="form-group">
                            <label for="opcionesRanking">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opcionesRanking" name="opcionesRanking" rows="3"
                                placeholder="Ingrese 4 o 5 opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3"></textarea>
                        </div>
                    </div>

                    <div id="Escala de Likert" style="display: none;">
                        <div class="form-group">
                            <label for="opcionesLikert">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opcionesLikert" name="opcionesLikert" rows="3"
                                placeholder="Ingrese las opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3"></textarea>
                        </div>
                    </div>

                    <div id="Escala numérica" style="display: none;">
                        <div class="form-group">
                            <label for="escalaNumerica">Rango máximo de la escala <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="100" class="form-control" id="escalaNumerica"
                                name="escalaNumerica" placeholder="10" style="width: 80px;">
                        </div>
                    </div>

                    <div id="Preguntas mixtas" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="opcionesMixtas">Opciones de respuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="opcionesMixtas" name="opcionesMixtas" rows="3"
                                placeholder="Ingrese las opciones de respuesta, separadas por comas. Si una opción contiene una coma, colóquela entre paréntesis. Ejemplo: Opción 1, (Opción 2, con una coma), Opción 3"></textarea>
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
                            var selectedOption = this.options[this.selectedIndex].value;
                            document.getElementById(selectedOption).style.display = 'block';
                        });
                    </script>
                </div>
            </div>
            <p><span class="text-danger">*</span> Indica un campo obligatorio</p>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary btn-sm">Guardar Pregunta y Añadir Otra</button>
                <button type="submit" name="save_and_close" value="1" class="btn btn-success btn-sm">Guardar y
                    Cerrar</button>
                <a href="{{ route('preguntas.index', ['idEncuesta' => $idEncuesta]) }}"
                    class="btn btn-secondary btn-sm">Cancelar</a>
            </div>
        </form>
    </main>
@endsection
