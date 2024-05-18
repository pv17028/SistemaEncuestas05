@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalles de Usuario</h2>
            <div>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Editar Usuario</a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Volver al listado de Usuarios</a>
            </div>
        </div>
        <hr>

        <div class="card">
            <div class="card-header">
                {{ $user->nombre }} {{ $user->apellido }}
            </div>
            <div class="card-body">
                <p><strong>Correo Electrónico:</strong> {{ $user->correoElectronico }}</p>
                <p><strong>Fecha de Nacimiento:</strong> {{ $user->fechaNacimiento }}</p>
                <p><strong>Usuario:</strong> {{ $user->username }}</p>
                <p><strong>Rol:</strong> {{ $user->role ? $user->role->nombreRol : 'N/A' }}</p>
                <p>
                    <strong>Estado:</strong>
                    @if ($user->bloqueosUsuario->where('status', 'blocked')->count() > 0)
                        Bloqueado
                    @else
                        Desbloqueado
                    @endif
                </p>
                <p><strong>Último Acceso:</strong> {{ $user->ultimaAcceso }}</p>
            </div>
        </div>
    </main>
@endsection
