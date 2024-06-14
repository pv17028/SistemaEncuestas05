@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Encuestas Compartidas</h2>
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

        @if ($encuestasCompartidas->isEmpty())
            <p>No hay encuestas compartidas.</p>
        @else
            <table class="table" id="encuestasCompartidas">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Anonima</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($encuestasCompartidas as $encuesta)
                        <tr>
                            <td>{{ $encuesta->titulo }}</td>
                            <td>{{ \Carbon\Carbon::parse($encuesta->fechaVencimiento)->format('d-m-Y g:i:s A') }}</td>
                            <td>{{ $encuesta->es_anonima ? 'Sí' : 'No' }}</td>
                            <td>
                                @if($encuesta->encuesta_usuario)
                                    @if($encuesta->encuesta_usuario->completa)
                                        <span>Ya respondí la encuesta</span>
                                    @elseif($encuesta->encuesta_usuario->preguntas_no_respondidas)
                                        <a href="{{ route('ecompartidas.edit', $encuesta->idEncuesta) }}" class="btn btn-sm btn-info">Continuar respondiendo</a>
                                    @else
                                        <a href="{{ route('ecompartidas.show', $encuesta->idEncuesta) }}" class="btn btn-sm btn-info">Responder</a>
                                    @endif
                                @else
                                    <a href="{{ route('ecompartidas.show', $encuesta->idEncuesta) }}" class="btn btn-sm btn-info">Responder</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
            $('#encuestasCompartidas').DataTable();
        });
                </script>
        @endif
    </main>
@endsection
