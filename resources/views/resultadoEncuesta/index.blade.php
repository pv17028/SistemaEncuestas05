@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Lista de Encuestas</h2>
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
            <table class="table" id="resultados">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha de vencimiento</th>
                        <th>Total de respuestas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($encuestas as $encuesta)
                        <tr>
                            <td>{{ $encuesta->titulo }}</td>
                            <td>{{ \Carbon\Carbon::parse($encuesta->fechaVencimiento)->format('d-m-Y g:i:s A') }}</td>
                            <td>{{ $encuesta->respuestas_agrupadas }}</td>
                            <td>
                                <a href="{{ route('resultadoEncuesta.show', $encuesta->idEncuesta) }}"
                                    class="btn btn-sm btn-info">Ver Resultados</a>
                                <a href="{{ route('exportacion.excel', $encuesta->idEncuesta) }}"
                                    class="btn btn-sm btn-success">Exportar EXCEL</a>
                                <a href="{{ route('exportacion.pdf', $encuesta->idEncuesta) }}"
                                    class="btn btn-sm btn-danger">Exportar PDF</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $('#resultados').DataTable();
                });
            </script>
        @endif
    </main>
@endsection
