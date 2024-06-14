@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Editar Privilegio</h2>
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

        <form method="POST" action="{{ route('privilegios.update', $privilegio->idPrivilegio) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombrePrivilegio">Nombre del Privilegio</label>
                        <input type="text" class="form-control" id="nombrePrivilegio" name="nombrePrivilegio" value="{{ $privilegio->nombrePrivilegio }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="url">URL</label>
                        <input type="text" class="form-control" id="url" name="url" value="{{ $privilegio->url }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="descripcionPrivilegio">Descripción del Privilegio</label>
                        <textarea class="form-control" id="descripcionPrivilegio" name="descripcionPrivilegio">{{ $privilegio->descripcionPrivilegio }}</textarea>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('privilegios.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
        </form>
    </main>
@endsection