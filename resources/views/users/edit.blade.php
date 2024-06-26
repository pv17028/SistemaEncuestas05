@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Editar Usuario</h2>
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

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="{{ $user->nombre }}" placeholder="Ingresa tu nombre">
                    </div>

                    <div class="form-group mb-3">
                        <label for="apellido">Apellido <span class="text-danger">*</span></label>
                        <input type="text" id="apellido" name="apellido" class="form-control" value="{{ $user->apellido }}" placeholder="Ingresa tu apellido">
                    </div>

                    <div class="form-group mb-3">
                        <label for="correoElectronico">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" id="correoElectronico" name="correoElectronico" class="form-control" value="{{ $user->correoElectronico }}" placeholder="ejemplo@dominio.com">
                    </div>

                    <div class="form-group mb-3">
                        <label for="idRol">Rol <span class="text-danger">*</span></label>
                        <select id="idRol" name="idRol" class="form-control">
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->idRol }}" {{ $user->idRol == $rol->idRol ? 'selected' : '' }}>
                                    {{ $rol->nombreRol }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    @php
                        $eighteenYearsAgo = (new DateTime('-18 years'))->format('Y-m-d');
                    @endphp

                    <div class="form-group mb-3">
                        <label for="fechaNacimiento">Fecha de Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" id="fechaNacimiento" name="fechaNacimiento" class="form-control" value="{{ $user->fechaNacimiento }}" max="{{ $eighteenYearsAgo }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="username">Usuario <span class="text-danger">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ $user->username }}" placeholder="Ingresa tu usuario">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Nueva Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa tu nueva contraseña">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirma tu nueva contraseña">
                    </div>
                </div>
            </div>
            <p><span class="text-danger">*</span> Indica un campo obligatorio</p>
            <div class="text-center">
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar cambios</button>
            </div>
        </form>
    </main>
@endsection
