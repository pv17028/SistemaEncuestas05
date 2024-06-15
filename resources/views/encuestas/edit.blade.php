@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Editar Encuesta</h2>
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

        <form method="POST" action="{{ route('encuestas.update', $encuesta->idEncuesta) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" value="{{ $encuesta->titulo }}"
                        placeholder="Ingresa el título de la encuesta" required>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="objetivo">Objetivo</label>
                    <textarea name="objetivo" id="objetivo" class="form-control" placeholder="Ingresa el objetivo de la encuesta" required>{{ $encuesta->objetivo }}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group mb-3">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcionEncuesta" id="descripcion" class="form-control"
                        placeholder="Ingresa la descripción de la encuesta" required>{{ $encuesta->descripcionEncuesta }}</textarea>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 form-group mb-3">
                    <label for="grupoMeta">Grupo Meta</label>
                    <input type="text" name="grupoMeta" id="grupoMeta" class="form-control"
                        value="{{ $encuesta->grupoMeta }}" placeholder="Ingresa el grupo meta de la encuesta" required>
                </div>

                @php
                    $today = (new DateTime())->format('Y-m-d\TH:i');
                @endphp

                <div class="col-md-4 form-group mb-3">
                    <label for="fechaVencimiento">Fecha de Vencimiento</label>
                    <input type="datetime-local" name="fechaVencimiento" id="fechaVencimiento" class="form-control"
                        value="{{ str_replace(' ', 'T', $encuesta->fechaVencimiento) }}" min="{{ $today }}" required>
                </div>

                <div class="col-md-4 form-group mb-3">
                    <label for="es_anonima">¿Es una encuesta anónima?</label>
                    <select class="form-control" id="es_anonima" name="es_anonima" onchange="updateMessage(this)">
                        <option value="0" {{ $encuesta->es_anonima == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $encuesta->es_anonima == 1 ? 'selected' : '' }}>Sí</option>
                    </select>
                    <p id="anonimaMessage"></p>
                </div>
                
                <script>
                function updateMessage(selectElement) {
                    var messageElement = document.getElementById('anonimaMessage');
                
                    if (selectElement.value == '1') {
                        messageElement.textContent = 'Si eliges que la encuesta sea anónima, se podrá responder muchas veces mientras esté compartida.';
                    } else {
                        messageElement.textContent = 'Si la encuesta no es anónima, los usuarios solo podrán responder una vez.';
                    }
                }
                
                // Actualiza el mensaje inicialmente al cargar la página
                window.onload = function() {
                    updateMessage(document.getElementById('es_anonima'));
                };
                </script>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    Personalizar Encuesta
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label for="logo">Logo</label>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="logo" name="logo"
                                    onchange="previewImage(event)">
                            </div>
                            <img id="preview" src="{{ asset('images/' . $encuesta->logo) }}" alt="Vista previa del logo"
                                style="max-width: 150px; display: block;">
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_principal">Color de fondo principal</label>
                                    <input type="color" name="color_principal" id="color_principal" class="form-control"
                                        value="{{ $encuesta->color_principal ?? '#ffffff' }}">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_terciario">Color de texto - Titulo de encuesta</label>
                                    <input type="color" name="color_terciario" id="color_terciario" class="form-control"
                                        value="{{ $encuesta->color_terciario ?? '#ffffff' }}">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_cuarto">Color de fondo - Título y logo de encuesta</label>
                                    <input type="color" name="color_cuarto" id="color_cuarto" class="form-control"
                                        value="{{ $encuesta->color_cuarto ?? '#ffffff' }}">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_secundario">Color de fondo - Descripción de encuesta</label>
                                    <input type="color" name="color_secundario" id="color_secundario" class="form-control"
                                        value="{{ $encuesta->color_secundario ?? '#ffffff' }}">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_septimo">Color de fondo - Pregunta</label>
                                    <input type="color" name="color_septimo" id="color_septimo" class="form-control"
                                        value="{{ $encuesta->color_septimo ?? '#ffffff' }}">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_quinto">Color de fondo - Cuerpo de la pregunta</label>
                                    <input type="color" name="color_quinto" id="color_quinto" class="form-control"
                                        value="{{ $encuesta->color_quinto ?? '#ffffff' }}">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="color_sexto">Color de fondo - Botón de enviar encuesta</label>
                                    <input type="color" name="color_sexto" id="color_sexto" class="form-control"
                                        value="{{ $encuesta->color_sexto ?? '#ffffff' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    function previewImage(event) {
                        var reader = new FileReader();
                        reader.onload = function() {
                            var output = document.getElementById('preview');
                            output.src = reader.result;
                            output.style.display = 'block';
                        };
                        reader.readAsDataURL(event.target.files[0]);
                    }
                </script>
            </div>

            <div class="text-center">
                <a href="{{ route('encuestas.show', ['encuesta' => $encuesta->idEncuesta]) }}"
                    class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar cambios</button>
            </div>
        </form>
    </main>
@endsection
