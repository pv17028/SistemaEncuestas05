@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Crear Rol</h2>
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

        <form method="POST" action="{{ route('roles.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombreRol">Nombre del Rol</label>
                        <input type="text" class="form-control" id="nombreRol" name="nombreRol" placeholder="Ingresa el nombre del rol" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="descripcionRol">Descripción del Rol</label>
                        <textarea class="form-control" id="descripcionRol" name="descripcionRol" placeholder="Ingresa la descripción del rol"></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Privilegios</label>
                        @foreach ($privilegios as $privilegio)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $privilegio->idPrivilegio }}"
                                    id="privilegio{{ $privilegio->idPrivilegio }}" name="privilegios[]">
                                <label class="form-check-label" for="privilegio{{ $privilegio->idPrivilegio }}">
                                    {{ $privilegio->nombrePrivilegio }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
        </form>
    </main>
@endsection
