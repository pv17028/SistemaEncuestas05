@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center" style="height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('Registro') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <label for="nombre"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Nombre') }}</label>
                                    <div class="col-md-6">
                                        <input id="nombre" type="text"
                                            class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                                            value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>
                                        @error('nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="apellido"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Apellido') }}</label>
                                    <div class="col-md-6">
                                        <input id="apellido" type="text"
                                            class="form-control @error('apellido') is-invalid @enderror" name="apellido"
                                            value="{{ old('apellido') }}" required autocomplete="apellido" autofocus>
                                        @error('apellido')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="fechaNacimiento"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Fecha de Nacimiento') }}</label>
                                    <div class="col-md-6">
                                        <input id="fechaNacimiento" type="date"
                                            class="form-control @error('fechaNacimiento') is-invalid @enderror"
                                            name="fechaNacimiento" value="{{ old('fechaNacimiento') }}" required
                                            autocomplete="fechaNacimiento" autofocus
                                            max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                                        @error('fechaNacimiento')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="imagenPerfil"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Imagen de Perfil') }}</label>
                                    <div class="col-md-6">
                                        <input id="imagenPerfil" type="file"
                                            class="form-control @error('imagenPerfil') is-invalid @enderror"
                                            name="imagenPerfil" value="{{ old('imagenPerfil') }}" accept="image/*"
                                            onchange="previewImage(event)">
                                        @error('imagenPerfil')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <img id="preview" src="" alt="Imagen de Perfil"
                                            style="max-width: 150px; margin-top: 10px;">
                                    </div>
                                </div>

                                <script>
                                    function previewImage(event) {
                                        var reader = new FileReader();
                                        reader.onload = function() {
                                            var output = document.getElementById('preview');
                                            output.src = reader.result;
                                        }
                                        reader.readAsDataURL(event.target.files[0]);
                                    }
                                </script>

                                <div class="row mb-3">
                                    <label for="correoElectronico"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Correo Electrónico') }}</label>
                                    <div class="col-md-6">
                                        <input id="correoElectronico" type="email"
                                            class="form-control @error('correoElectronico') is-invalid @enderror"
                                            name="correoElectronico" value="{{ old('correoElectronico') }}" required
                                            autocomplete="correoElectronico">
                                        @error('correoElectronico')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="username"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Nombre de Usuario') }}</label>

                                    <div class="col-md-6">
                                        <input id="username" type="text"
                                            class="form-control @error('username') is-invalid @enderror" name="username"
                                            value="{{ old('username') }}" required autocomplete="username">

                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password-confirm"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Confirmar Contraseña') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="row mb-0 justify-content-center">
                                    <div class="col-md-6 text-center">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Crear Cuenta') }}
                                        </button>
                                        <p class="mt-3">
                                            ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
