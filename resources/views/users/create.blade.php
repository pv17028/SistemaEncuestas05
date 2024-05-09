@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Crear Usuario</h2>
        </div>
        <hr>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingresa tu nombre">
                    </div>

                    <div class="form-group mb-3">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Ingresa tu apellido">
                    </div>

                    <div class="form-group mb-3">
                        <label for="correoElectronico">Correo Electrónico</label>
                        <input type="email" id="correoElectronico" name="correoElectronico" class="form-control" placeholder="ejemplo@dominio.com">
                    </div>

                    <div class="form-group mb-3">
                        <label for="idRol">Rol</label>
                        <select id="idRol" name="idRol" class="form-control">
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->idRol }}">{{ $rol->nombreRol }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    @php
                        $eighteenYearsAgo = (new DateTime('-18 years'))->format('Y-m-d');
                    @endphp
                    
                    <div class="form-group mb-3">
                        <label for="fechaNacimiento">Fecha de Nacimiento</label>
                        <input type="date" id="fechaNacimiento" name="fechaNacimiento" class="form-control" max="{{ $eighteenYearsAgo }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="username">Usuario</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Ingresa tu usuario">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa tu contraseña">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirma tu contraseña">
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Crear Usuario</button>
            </div>
        </form>
    </main>
@endsection
