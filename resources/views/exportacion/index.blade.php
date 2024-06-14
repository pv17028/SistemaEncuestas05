{{-- resources/views/encuestas/index.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">   
    <div>
        <h2>Exportación</h2>
        <p>Seleccione la encuesta y el formato para exportarla</p>

        <h2>Lista de Encuestas</h2>
        <a class="btn btn-primary" href="{{ route('exportacion.reporteGeneralPdf') }}">Generar reporte general</a>
        <a class="btn btn-primary" href="{{ route('exportacion.grafico') }}">Generar gráfico</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha de Creación</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Exportar</th>
                </tr>
            </thead>
           <tbody>
                @foreach ($encuestas as $encuesta)
                    <tr>
                        <td>{{ $encuesta->titulo }}</td>
                        <td>{{ \Carbon\Carbon::parse($encuesta->created_at)->format('d-m-Y g:i:s A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($encuesta->fechaVencimiento)->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('exportacion.excel', $encuesta->idEncuesta) }}"
                                class="btn btn-sm btn-success">EXCEL</a>
                            <a href="{{ route('exportacion.pdf', $encuesta->idEncuesta) }}"
                                class="btn btn-sm btn-danger">PDF</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </div>
</main>
@endsection
