{{-- resources/views/encuestas/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Lista de Roles</h2>
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">Crear Rol</a>
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

        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if ($roles->isEmpty())
            <p>No hay roles.</p>
        @else
            <table class="table" id="roles">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $rol)
                        <tr>
                            <td>{{ $rol->nombreRol }}</td>
                            <td>{{ $rol->descripcionRol }}</td>
                            <td>
                                <a href="{{ route('roles.show', $rol->idRol) }}" class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ route('roles.edit', $rol->idRol) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('roles.destroy', $rol->idRol) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de querer eliminar este rol?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $('#roles').DataTable({
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
