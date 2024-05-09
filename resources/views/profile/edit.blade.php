@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Configuración de perfil</h2>
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

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ auth()->user()->nombre }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="apellido">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido"
                            value="{{ auth()->user()->apellido }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="fechaNacimiento">Fecha de nacimiento</label>
                        <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento"
                            value="{{ auth()->user()->fechaNacimiento }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="correoElectronico">Correo electrónico</label>
                        <input type="email" class="form-control" id="correoElectronico" name="correoElectronico"
                            value="{{ auth()->user()->correoElectronico }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ auth()->user()->username }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar cambios</button>
            </div>
        </form>
    </main>
@endsection
