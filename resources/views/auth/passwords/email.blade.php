@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center" style="height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Restablecer Contraseña') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="row mb-3">
                                    <label for="correoElectronico"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Dirección de Correo Electrónico') }}</label>

                                    <div class="col-md-6">
                                        <input id="correoElectronico" type="email"
                                            class="form-control @error('correoElectronico') is-invalid @enderror" name="correoElectronico"
                                            value="{{ old('correoElectronico') }}" required autocomplete="correoElectronico" autofocus>

                                        @error('correoElectronico')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Enviar enlace de restablecimiento de contraseña') }}
                                        </button>
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