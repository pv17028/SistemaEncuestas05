<!-- resources/views/opciones/edit.blade.php -->

@extends('layouts.app')

@section('content')
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
    <h2>Editar Opción</h2>
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
    <form action="{{ route('preguntas.opciones.update', [ $opcion->idOpcion, $preguntas->idPregunta]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="textoOpcion" class="form-label">Texto de Opción</label>
            <input type="text" class="form-control" id="contenidoOpcion" name="contenidoOpcion" value="{{ $opcion->contenidoOpcion }}">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('preguntas.opciones.index', $preguntas->idPregunta) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</main>
@endsection
