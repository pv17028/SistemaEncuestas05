@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalles de Rol</h2>
            <div>
                <a href="{{ route('roles.edit', $rol->idRol) }}" class="btn btn-warning btn-sm">Editar Rol</a>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">Volver al listado de Roles</a>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header">{{ $rol->nombreRol }}</div>
            <div class="card-body">
                <p><strong>Descripción: </strong>{{ $rol->descripcionRol }}</p>
                <p><strong>Privilegios: </strong></p>
                <ul>
                    @foreach ($rol->privilegios as $privilegio)
                        <li>{{ $privilegio->nombrePrivilegio }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </main>
@endsection
