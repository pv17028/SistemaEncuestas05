@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Agregar Encuesta</h2>
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

        <form method="POST" action="{{ route('encuestas.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Agregar el campo oculto para el idUsuario --}}
            <input type="hidden" name="idUsuario" value="{{ auth()->user()->id }}">

            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control"
                        placeholder="Ingresa el título de la encuesta" required>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="objetivo">Objetivo</label>
                    <textarea name="objetivo" id="objetivo" class="form-control" placeholder="Ingresa el objetivo de la encuesta" required></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group mb-3">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcionEncuesta" id="descripcion" class="form-control"
                        placeholder="Ingresa la descripción de la encuesta" required></textarea>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 form-group mb-3">
                    <label for="grupoMeta">Grupo Meta</label>
                    <input type="text" name="grupoMeta" id="grupoMeta" class="form-control"
                        placeholder="Ingresa el grupo meta de la encuesta" required>
                </div>

                @php
                    $today = (new DateTime())->format('Y-m-d\TH:i');
                @endphp

                <div class="col-md-4 form-group mb-3">
                    <label for="fechaVencimiento">Fecha de Vencimiento</label>
                    <input type="datetime-local" name="fechaVencimiento" id="fechaVencimiento" class="form-control"
                        min="{{ $today }}" required>
                </div>

                <div class="col-md-4 form-group mb-3">
                    <label for="es_anonima">¿Es una encuesta anónima?</label>
                    <select class="form-control" id="es_anonima" name="es_anonima">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </div>
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
                            <img id="preview" src="" alt="Vista previa del logo"
                                style="max-width: 150px; display: none;">
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_principal">Color de fondo principal</label>
                                    <input type="color" name="color_principal" id="color_principal" class="form-control"
                                        value="#ffffff">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_terciario">Color de texto - Titulo de encuesta</label>
                                    <input type="color" name="color_terciario" id="color_terciario" class="form-control"
                                        value="#ffffff">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_cuarto">Color de fondo - Título y logo de encuesta</label>
                                    <input type="color" name="color_cuarto" id="color_cuarto" class="form-control"
                                        value="#ffffff">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_secundario">Color de fondo - Descripción de encuesta</label>
                                    <input type="color" name="color_secundario" id="color_secundario" class="form-control"
                                        value="#ffffff">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_septimo">Color de fondo - Pregunta</label>
                                    <input type="color" name="color_septimo" id="color_septimo" class="form-control"
                                        value="#ffffff">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color_quinto">Color de fondo - Cuerpo de la pregunta</label>
                                    <input type="color" name="color_quinto" id="color_quinto" class="form-control"
                                        value="#ffffff">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="color_sexto">Color de fondo - Botón de enviar encuesta</label>
                                    <input type="color" name="color_sexto" id="color_sexto" class="form-control"
                                        value="#ffffff">
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
                <a href="{{ route('encuestas.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Agregar Preguntas</button>
            </div>
        </form>
    </main>
@endsection
