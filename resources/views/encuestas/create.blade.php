@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Agregar Encuesta</h2>
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

        <form method="POST" action="{{ route('encuestas.store') }}">
            @csrf

            {{-- Agregar el campo oculto para el idUsuario --}}
            <input type="hidden" name="idUsuario" value="{{ auth()->user()->id }}">
            
            <div class="form-group mb-3">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="objetivo">Objetivo</label>
                <textarea name="objetivo" id="objetivo" class="form-control" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcionEncuesta" id="descripcion" class="form-control" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="grupoMeta">Grupo Meta</label>
                <input type="text" name="grupoMeta" id="grupoMeta" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="fechaVencimiento">Fecha de Vencimiento</label>
                <input type="date" name="fechaVencimiento" id="fechaVencimiento" class="form-control" required>
            </div>

            <div class="text-center">
                <a href="{{ route('encuestas.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
        </form>
    </main>
@endsection
