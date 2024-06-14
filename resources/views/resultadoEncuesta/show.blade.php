@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Resultados de: {{ $encuesta->titulo }}</h2>
            <div>
                <a href="{{ route('resultadoEncuesta.index') }}" class="btn btn-sm btn-secondary">Volver a la lista de
                    encuestas</a>
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

        @forelse ($encuesta->preguntas as $pregunta)
            @if ($pregunta->opciones->count() > 0)
                <h3>{{ $pregunta->contenidoPregunta }}</h3>
                <p><strong>Tipo de pregunta:</strong> {{ $pregunta->tipoPregunta->nombreTipoPregunta }}</p>
        
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Opción</th>
                            <th>Respuestas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pregunta->opciones as $opcion)
                            <tr>
                                <td>{{ $opcion->contenidoOpcion }}</td>
                                <td>{{ $opcion->seleccion_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif($pregunta->respuestas->count() > 0)
                <h3>{{ $pregunta->contenidoPregunta }}</h3>
                <h4>Respuestas más recientes:</h4>
                <ul>
                    @foreach ($pregunta->respuestas->sortByDesc('created_at')->take(5) as $respuesta)
                        <li>{{ $respuesta->respuesta_abierta }}</li>
                    @endforeach
                </ul>
            @endif
        @empty
            <p>No hay resultados para mostrar.</p>
        @endforelse
    </main>
@endsection
