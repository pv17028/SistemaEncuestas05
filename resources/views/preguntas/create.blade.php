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

        <form method="POST" action="{{ route('preguntas.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombreRol">Contenido de la pregunta</label>
                        <input type="text" class="form-control" id="contenidoPregunta" name="contenidoPregunta" placeholder="Ingrese el contenido de la pregunta" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="descripcionPregunta">Descripción de la pregunta</label>
                        <input type="text" class="form-control" id="descripcionPregunta" name="descripcionPregunta" placeholder="Ingrese la descripción de la pregunta" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="idTipoPregunta">Tipo de pregunta</label>
                        <select class="form-select" id="idTipoPregunta" name="idTipoPregunta" required>
                            <option value="">Seleccione un tipo de pregunta</option>
                            @foreach ($tiposPreguntas as $tipoPregunta)
                                <option value="{{ $tipoPregunta->idTipoPregunta }}">{{ $tipoPregunta->nombreTipoPregunta }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="criterioValidacion">Criterio de validación</label>
                        <input type="text" class="form-control" id="criterioValidacion" name="criterioValidacion" placeholder="Ingrese el criterio de validación" required>
                    </div>

                    <!--<div class="form-group mb-3">
                        <label for="posicionPregunta">Posicion de la pregunta</label>
                        <input type="number" class="form-control" id="posicionPregunta" name="posicionPregunta" placeholder="Ingrese la posición de la pregunta" required>
                    </div>-->

                    
                </div>

            </div>

            <div class="text-center">
                <a href="{{ route('preguntas.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
        </form>
    </main>
@endsection
