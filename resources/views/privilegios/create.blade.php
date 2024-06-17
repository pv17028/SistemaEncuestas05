@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Crear Privilegio</h2>
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

        <form method="POST" action="{{ route('privilegios.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombrePrivilegio">Nombre del Privilegio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombrePrivilegio" name="nombrePrivilegio" placeholder="Ingresa el nombre del privilegio" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="url">URL <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="url" name="url" placeholder="Ingresa la URL" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="descripcionPrivilegio">Descripción del Privilegio <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="descripcionPrivilegio" name="descripcionPrivilegio" placeholder="Ingresa la descripción del privilegio"></textarea>
                    </div>
                </div>
            </div>
            <p><span class="text-danger">*</span> Indica un campo obligatorio</p>
            <div class="text-center">
                <a href="{{ route('privilegios.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
        </form>
    </main>
@endsection