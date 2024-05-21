@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <h2>Editar pregunta</h2>
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
        <form action="{{ route('preguntas.update', $preguntas->idPregunta) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="contenidoPregunta" class="form-label">Pregunta</label>
                <input type="text" class="form-control" id="contenidoPregunta" name="contenidoPregunta" value="{{ $preguntas->contenidoPregunta }}">
            </div>
            <div class="mb-3">
                <label for="descripcionPregunta" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcionPregunta" name="descripcionPregunta" rows="3">{{ $preguntas->descripcionPregunta }}</textarea>
            </div>
            <div class="mb-3">
                <label for="idTipoPregunta" class="form-label">Tipo de pregunta</label>
                <select class="form-select" id="idTipoPregunta" name="idTipoPregunta">
                    <option value="">Selecciona un tipo de pregunta</option>
                    @foreach ($tiposPreguntas as $tipoPregunta)
                        <option value="{{ $tipoPregunta->idTipoPregunta }}" {{ $tipoPregunta->idTipoPregunta == $preguntas->idTipoPregunta ? 'selected' : '' }}>{{ $tipoPregunta->nombreTipoPregunta }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="criterioValidacion" class="form-label">Criterio de validación</label>
                <input type="text" class="form-control" id="criterioValidacion" name="criterioValidacion" value="{{ $preguntas->criterioValidacion }}">
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('preguntas.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </main>
@endsection