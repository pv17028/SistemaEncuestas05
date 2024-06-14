{{-- resources/views/privilegios/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Lista de Privilegios</h2>
            <a href="{{ route('privilegios.create') }}" class="btn btn-primary btn-sm">Crear Privilegio</a>
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

        @if ($privilegios->isEmpty())
            <p>No hay privilegios.</p>
        @else
            <table class="table" id="privilegios">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>URL</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($privilegios as $privilegio)
                        <tr>
                            <td>{{ $privilegio->nombrePrivilegio }}</td>
                            <td>{{ $privilegio->descripcionPrivilegio }}</td>
                            <td>{{ $privilegio->url }}</td>
                            <td>
                                {{-- <a href="{{ route('privilegios.show', $privilegio->idPrivilegio) }}" class="btn btn-sm btn-info">Ver</a> --}}
                                <a href="{{ route('privilegios.edit', $privilegio->idPrivilegio) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('privilegios.destroy', $privilegio->idPrivilegio) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de querer eliminar este privilegio?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $('#privilegios').DataTable();
                });
            </script>
        @endif
    </main>
@endsection