{{-- resources/views/encuestas/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div>
            <h2>Exportación</h2>
            <p>Seleccione la encuesta y el formato para exportarla</p>

            <div class="d-flex justify-content-between align-items-center">
                <h2>Lista de Encuestas</h2>
                <div>
                    <a class="btn btn-primary btn-sm" href="{{ route('exportacion.reporteGeneralPdf') }}">Generar reporte
                        general</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('exportacion.grafico') }}">Graficos generales</a>
                </div>
            </div>
            <hr>
            <table class="table" id="exportacion">
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
            </table>
            <script>
                $(document).ready(function() {
                    $('#exportacion').DataTable({
                        language: {
                            processing: "Procesando...",
                            search: "Buscar:",
                            lengthMenu: "Mostrar _MENU_ elementos",
                            info: "Mostrando de _START_ a _END_ de _TOTAL_ elementos",
                            infoEmpty: "Mostrando 0 de 0 de 0 elementos",
                            infoFiltered: "(filtrado de _MAX_ elementos en total)",
                            infoPostFix: "",
                            loadingRecords: "Cargando registros...",
                            zeroRecords: "No se encontraron registros",
                            emptyTable: "No hay datos disponibles en la tabla",
                            paginate: {
                                first: "Primero",
                                previous: "Anterior",
                                next: "Siguiente",
                                last: "Último"
                            },
                            aria: {
                                sortAscending: ": activar para ordenar la columna de manera ascendente",
                                sortDescending: ": activar para ordenar la columna de manera descendente"
                            }
                        }
                    });
                });
            </script>
        </div>
    </main>
@endsection
