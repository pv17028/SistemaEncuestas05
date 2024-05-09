@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Editar Encuesta</h2>
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
        
        <form method="POST" action="{{ route('encuestas.update', $encuesta->idEncuesta) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" value="{{ $encuesta->titulo }}" placeholder="Ingresa el título de la encuesta" required>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="objetivo">Objetivo</label>
                    <textarea name="objetivo" id="objetivo" class="form-control" placeholder="Ingresa el objetivo de la encuesta" required>{{ $encuesta->objetivo }}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group mb-3">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcionEncuesta" id="descripcion" class="form-control" placeholder="Ingresa la descripción de la encuesta" required>{{ $encuesta->descripcionEncuesta }}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label for="grupoMeta">Grupo Meta</label>
                    <input type="text" name="grupoMeta" id="grupoMeta" class="form-control" value="{{ $encuesta->grupoMeta }}" placeholder="Ingresa el grupo meta de la encuesta" required>
                </div>

                @php
                    $today = (new DateTime())->format('Y-m-d');
                @endphp
                
                <div class="col-md-6 form-group mb-3">
                    <label for="fechaVencimiento">Fecha de Vencimiento</label>
                    <input type="date" name="fechaVencimiento" id="fechaVencimiento" class="form-control" value="{{ $encuesta->fechaVencimiento }}" min="{{ $today }}" required>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('encuestas.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar cambios</button>
            </div>
        </form>
    </main>
@endsection
