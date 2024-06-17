@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Registro de Bloqueos</h2>
            <a href="{{ route('bloqueos.create') }}" class="btn btn-primary btn-sm">Bloquear Usuario</a>
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

        @if ($bloqueos->isEmpty())
            <p>No hay bloqueos.</p>
        @else
            <table class="table" id="bloqueos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Fecha de Bloqueo</th>
                        <th>Fecha de Desbloqueo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bloqueos as $bloqueo)
                        <tr>
                            <td>{{ $bloqueo->id }}</td>
                            <td>
                                @if ($bloqueo->user)
                                    {{ $bloqueo->user->username }}
                                @else
                                    {{ $bloqueo->username_historico }}
                                @endif
                            </td>
                            <td>{{ $bloqueo->blocked_at }}</td>
                            <td>{{ $bloqueo->unblocked_at }}</td>
                            <td style="white-space: nowrap;">
                                <!-- Botón de desbloqueo -->
                                @if ($bloqueo->status == 'blocked')
                                    <!-- Verificar si la fecha de desbloqueo está establecida O el estado es 'blocked' -->
                                    <form action="{{ route('bloqueos.desbloquear', $bloqueo->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-primary btn-sm">Desbloquear</button>
                                    </form>
                                @endif
                                <!-- Botón de ver -->
                                <a href="{{ route('bloqueos.show', $bloqueo->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <!-- Botón de editar -->
                                @if ($bloqueo->user)
                                    <a href="{{ route('bloqueos.edit', $bloqueo->id) }}"
                                        class="btn btn-warning btn-sm">Editar</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $('#bloqueos').DataTable({
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
