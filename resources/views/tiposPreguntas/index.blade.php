@extends('layouts.app')

@section('content')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Tipo de Preguntas</h2>
            <div>
                <a href="{{ route('tiposPreguntas.create') }}" class="btn btn-primary btn-sm">Crear Tipo de Pregunta</a>
                <a href="{{ route('gestionEncuestas.index') }}" class="btn btn-secondary btn-sm">Volver a Gestion de
                    Encuestas</a>
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

        @if ($tiposPreguntas->isEmpty())
            <p>No hay tipos de preguntas.</p>
        @else
            <table class="table" id="tiposPreguntas">
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
                                <div class="d-flex">
                                    <a href="{{ route('tiposPreguntas.show', $tipoPregunta->idTipoPregunta) }}"
                                        class="btn btn-sm btn-info me-1">Ver</a>
                                    <a href="{{ route('tiposPreguntas.edit', $tipoPregunta->idTipoPregunta) }}"
                                        class="btn btn-sm btn-warning me-1">Editar</a>
                                    <form action="{{ route('tiposPreguntas.destroy', $tipoPregunta->idTipoPregunta) }}"
                                        method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Estás seguro de querer eliminar este tipo de pregunta?')">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $('#tiposPreguntas').DataTable({
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
        @endif
    </main>
@endsection
