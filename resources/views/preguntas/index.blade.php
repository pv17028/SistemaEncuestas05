@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Lista de preguntas - {{ $encuesta->titulo }}</h2>
            <div>
                <a href="{{ route('preguntas.create', ['idEncuesta' => $idEncuesta]) }}" class="btn btn-primary btn-sm">Agregar Pregunta</a>
                <a href="{{ route('encuestas.show', ['encuesta' => $idEncuesta]) }}" class="btn btn-secondary btn-sm">Volver a detalles de la Encuesta</a>
            </div>
        </div>
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

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pregunta</th>
                    <th>Descripcion</th>
                    <th>Tipo de pregunta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($preguntas as $pregunta)
                    @if ($pregunta->idEncuesta == $idEncuesta)
                        <tr>
                            <td>{{ $pregunta->idPregunta}}</td>
                            <td>{{ $pregunta->contenidoPregunta }}</td>
                            <td>{{ $pregunta->descripcionPregunta }}</td>
                            <td>
                                @if ($pregunta->idTipoPregunta)
                                    {{ $pregunta->tipoPregunta->nombreTipoPregunta }}
                                @else
                                    <span class="text-danger">Sin tipo</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    {{-- <a href="{{ route('preguntas.opciones.index', ['idEncuesta' => $encuesta->id, 'idPregunta' => $pregunta->idPregunta]) }}" class="btn btn-primary btn-sm">Opciones</a> --}}
                                    <a href="{{ route('preguntas.show', ['idEncuesta' => $encuesta->idEncuesta, 'preguntas' => $pregunta->idPregunta]) }}" class="btn btn-sm btn-info me-1">Ver</a>
                                    @if($encuesta->compartida)
                                        <div title="La encuesta está compartida, por lo que no se puede editar.">
                                            <button class="btn btn-warning btn-sm me-1" disabled>Editar</button>
                                        </div>
                                    @else
                                        <a href="{{ route('preguntas.edit', ['idEncuesta' => $encuesta->idEncuesta, 'preguntas' => $pregunta->idPregunta]) }}" class="btn btn-warning btn-sm me-1">Editar</a>
                                    @endif
                                    <form action="{{ route('preguntas.destroy', ['idEncuesta' => $encuesta->idEncuesta, 'preguntas' => $pregunta->idPregunta]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta pregunta?')">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </main>
@endsection