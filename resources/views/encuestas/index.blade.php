{{-- resources/views/encuestas/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Lista de Encuestas</h2>
            <a href="{{ route('encuestas.create') }}" class="btn btn-primary btn-sm">Crear Encuesta</a>
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

        @if ($encuestas->isEmpty())
            <p>No hay encuestas.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha de Creación</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Total de Respuestas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($encuestas as $encuesta)
                        <tr>
                            <td>{{ $encuesta->titulo }}</td>
                            <td>{{ \Carbon\Carbon::parse($encuesta->created_at)->format('d-m-Y g:i:s A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($encuesta->fechaVencimiento)->format('d-m-Y g:i:s A') }}</td>
                            <td>{{ $encuesta->respuestasCount->first()->total ?? '0' }}</td>
                            <td>
                                <a href="{{ route('encuestas.show', $encuesta->idEncuesta) }}"
                                    class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ route('encuestas.edit', $encuesta->idEncuesta) }}"
                                    class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('encuestas.destroy', $encuesta->idEncuesta) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de querer eliminar esta encuesta?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </main>
@endsection
