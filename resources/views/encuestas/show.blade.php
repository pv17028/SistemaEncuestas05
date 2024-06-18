@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalles de Encuesta</h2>
            <div>
                <a href="{{ route('encuestas.edit', $encuesta->idEncuesta) }}" class="btn btn-warning btn-sm mb-3">Editar
                    Encuesta</a>
                {{-- <a href="{{ route('preguntas.create', ['idEncuesta' => $encuesta->idEncuesta]) }}" class="btn btn-primary btn-sm">Agregar Pregunta</a> --}}
                <a href="{{ route('preguntas.index', ['idEncuesta' => $encuesta->idEncuesta]) }}"
                    class="btn btn-info btn-sm mb-3">Ver Preguntas</a>
                <a href="{{ route('encuestas.index') }}" class="btn btn-secondary btn-sm mb-3">Volver al listado de
                    Encuestas</a>
            </div>
        </div>
        
        <div class="row justify-content-end">
            <div class="col-md-6">
                <!-- Si la encuesta está compartida, muestra el botón para dejar de compartirla -->
                @if ($encuesta->compartida == true)
                    <form method="POST" action="{{ route('encuestas.unshare', $encuesta->idEncuesta) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm float-end">Dejar de Compartir Encuesta</button>
                    </form>
                    <!-- Si la encuesta no está compartida, muestra las opciones para compartirla -->
                @else
                    <form method="POST" action="{{ route('encuestas.compartir', $encuesta->idEncuesta) }}"
                        class="d-inline">
                        @csrf
                        <div class="form-group">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    <label for="shareOptions">Compartir con:</label>
                                </div>
                                <div class="mx-2">
                                    <select id="shareOptions" name="shareOptions"
                                        class="form-control form-control-sm d-inline-block w-auto"
                                        onchange="shareWith(this.value)">
                                        <option value="">Selecciona una opción</option>
                                        <option value="todos">Todos</option>
                                        <option value="algunos">Algunos</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success btn-sm">Compartir Encuesta</button>
                                </div>
                            </div>
                        </div>
                        <div id="userSelection" style="display: none;" class="text-end">
                            <br>
                            <label for="users">Selecciona los usuarios:</label>
                            <select id="users" name="users[]" class="form-control form-control-sm" multiple
                                style="width: 50%;">
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <script>
                            function shareWith(value) {
                                var userSelection = document.getElementById('userSelection');
                                if (value == 'algunos') {
                                    userSelection.style.display = 'block';
                                } else {
                                    userSelection.style.display = 'none';
                                }
                            }

                            $(document).ready(function() {
                                $('#users').select2({
                                    width: '50%', // Ajusta el ancho del select al 50% del contenedor
                                    language: {
                                        noResults: function() {
                                            return "No se encontraron resultados";
                                        }
                                    }
                                });
                                shareWith($('#shareOptions')
                                    .val()); // Oculta o muestra el div 'userSelection' basado en la opción seleccionada inicialmente
                            });
                        </script>
                @endif
            </div>
        </div>


        @if (session('success'))
            <br>
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <br>
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <hr>

        <div class="card">
            <div class="card-header">
                {{ $encuesta->titulo }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Logo:</strong></p>
                        <img src="{{ asset('images/' . $encuesta->logo) }}" alt="Logo de la encuesta" style="max-width: 60%;">
                    </div>
                    <div class="col-md-8">
                        <p><strong>Objetivo:</strong> {{ $encuesta->objetivo }}</p>
                        <p><strong>Descripción:</strong> {{ $encuesta->descripcionEncuesta }}</p>
                        <p><strong>Grupo Meta:</strong> {{ $encuesta->grupoMeta }}</p>
                        <p><strong>Encuesta Anónima:</strong> {{ $encuesta->es_anonima ? 'Sí' : 'No' }}</p>
                        <p><strong>Fecha de Creación:</strong>
                            {{ \Carbon\Carbon::parse($encuesta->created_at)->format('d-m-Y g:i:s A') }}</p>
                        <p><strong>Fecha de Vencimiento:</strong>
                            {{ \Carbon\Carbon::parse($encuesta->fechaVencimiento)->format('d-m-Y g:i:s A') }}</p>
                        <p><strong>Número de Preguntas:</strong> {{ $encuesta->preguntas->count() }}</p>
                        <p><strong>Total de Respuestas:</strong> {{ $encuesta->respuestas_agrupadas ?? '0' }}</p>
                        {{-- Aquí puedes agregar cualquier otra información que desees mostrar --}}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
