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
                <div
                    style="max-height: 350px; overflow-y: auto; border: 1px solid #ccc; border-radius: 5px; padding: 0px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);">
                    <ul
                        style="column-count: 3; -moz-column-count: 3; -webkit-column-count: 3; list-style-type: none; padding-left: 0px; padding-bottom: 0px; column-gap: 0px; display: flex; flex-wrap: wrap; margin-bottom: 0;">
                        @foreach ($rol->privilegios as $index => $privilegio)
                            <li
                                style="padding: 10px 15px; background-color: {{ $index % 2 == 0 ? '#f2f2f2' : 'transparent' }}; line-height: 1.5; word-wrap: break-word; flex: 1 0 calc(33.333% - 20px); box-sizing: border-box; display: flex; align-items: center;">
                                {{ $privilegio->nombrePrivilegio }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </main>
@endsection
