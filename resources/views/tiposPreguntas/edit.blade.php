@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Editar Tipo de pregunta</h2>
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

        <form method="POST" action="{{ route('tiposPreguntas.update', $tipoPregunta->idTipoPregunta) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="tipoPregunta">Nombre del tipo de pregunta</label>
                        <input type="text" class="form-control" id="tipoPregunta" name="tipoPregunta"
                            value="{{ $tipoPregunta->nombreTipoPregunta }}" placeholder="Ingresa el tipo de pregunta"
                            required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="descripcionTipo">Descripción del tipo de pregunta</label>
                        <textarea class="form-control" id="descripcionTipo" name="descripcionTipo" placeholder="Ingresa la descripción del rol">{{ $tipoPregunta->descripcionTipoPregunta }}</textarea>
                    </div>
                </div>
                <div class="col-md-6"> <!-- Agrega este bloque -->
                    <div class="form-group mb-3">
                        <label for="habilitado">Habilitado</label>
                        <select class="form-control" id="habilitado" name="habilitado">
                            <option value="1" {{ $tipoPregunta->habilitado ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ !$tipoPregunta->habilitado ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <a href="{{ route('tiposPreguntas.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
        </form>
    </main>
@endsection
