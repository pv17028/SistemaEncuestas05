<!-- resources/views/opciones/index.blade.php -->

@extends('layouts.app')

@section('content')
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
    <h2>Opciones para: {{ $preguntas->contenidoPregunta }}</h2>
    <a href="{{ route('preguntas.opciones.create', $preguntas->idPregunta) }}" class="btn btn-primary btn-sm">Agregar Opción</a>
    <a href="{{ route('preguntas.index') }}" class="btn btn-secondary btn-sm">Volver al listado de preguntas</a>
    <hr>
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

    <ul class="list-group">
        @foreach ($opciones as $opcion)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $opcion->contenidoOpcion }}
                <div>
                    <a href="{{ route('preguntas.opciones.edit', [$preguntas->idPregunta, $opcion->idOpcion]) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('preguntas.opciones.destroy', [$preguntas->idPregunta, $opcion->idOpcion]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta opción?')">Eliminar</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</main>
@endsection
