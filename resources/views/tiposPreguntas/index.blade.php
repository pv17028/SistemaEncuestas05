@extends('layouts.app')

@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Tipo de Preguntas</h2>
            <a href="{{ route('tiposPreguntas.create') }}" class="btn btn-primary btn-sm">Crear Tipo de Pregunta</a>
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

        @if ($tiposPreguntas->isEmpty())
            <p>No hay tipos de preguntas.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tiposPreguntas as $tipoPregunta)
                        <tr>
                            <td>{{ $tipoPregunta->nombreTipoPregunta }}</td>
                            <td>{{ $tipoPregunta->descripcionTipoPregunta }}</td>
                            <td>
                                <a href="{{ route('tiposPreguntas.show', $tipoPregunta->idTipoPregunta) }}" class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ route('tiposPreguntas.edit', $tipoPregunta->idTipoPregunta) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('tiposPreguntas.destroy', $tipoPregunta->idTipoPregunta) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de querer eliminar este tipo de pregunta?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </main>


@endsection
