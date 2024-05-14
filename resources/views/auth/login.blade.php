@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center" style="height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('Iniciar Sesión') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="row mb-3">
                                    <label for="correoElectronico"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Correo Electrónico') }}</label>

                                    <div class="col-md-6">
                                        <input id="correoElectronico" type="email"
                                            class="form-control @error('correoElectronico') is-invalid @enderror" name="correoElectronico"
                                            value="{{ old('correoElectronico') }}" required autocomplete="correoElectronico" autofocus>

                                        @error('correoElectronico')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
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
                                            required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Recuerdame') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">

                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Iniciar Sesión') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('¿Olvidaste tu contraseña?') }}
                                            </a>
                                        @endif

                                        @if (Route::has('register'))
                                            <p class="mt-2">{{ __('¿No tienes una cuenta?') }} <a
                                                    href="{{ route('register') }}">{{ __('Registrarse aquí') }}</a></p>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Incluye jQuery y js-cookie -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
        
        <!-- Script del temporizador -->
        <script>
        $(document).ready(function() {
            var minutes = Cookies.get('minutes') || 3;
            var seconds = Cookies.get('seconds') || 0;
        
            var interval = setInterval(function() {
                seconds--;
                if (seconds < 0) {
                    seconds = 59;
                    minutes--;
                }
        
                if (minutes < 0) {
                    clearInterval(interval);
                    Cookies.remove('minutes');
                    Cookies.remove('seconds');
                    $('#timer').text('Tiempo agotado');
                } else {
                    Cookies.set('minutes', minutes, { expires: 1/480 }); // expires after 3 minutes
                    Cookies.set('seconds', seconds, { expires: 1/480 }); // expires after 3 minutes
                    $('#timer').text(minutes + ' minutos ' + seconds + ' segundos');
                }
            }, 1000);
        });
        </script>
    @endsection
