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

        <form method="POST" action="{{ route('encuestas.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Agregar el campo oculto para el idUsuario --}}
            <input type="hidden" name="idUsuario" value="{{ auth()->user()->id }}">

            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Ingresa el título de la encuesta" required>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="objetivo">Objetivo</label>
                    <textarea name="objetivo" id="objetivo" class="form-control" placeholder="Ingresa el objetivo de la encuesta" required></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group mb-3">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcionEncuesta" id="descripcion" class="form-control" placeholder="Ingresa la descripción de la encuesta" required></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label for="grupoMeta">Grupo Meta</label>
                    <input type="text" name="grupoMeta" id="grupoMeta" class="form-control" placeholder="Ingresa el grupo meta de la encuesta" required>
                </div>

                @php
                    $today = (new DateTime())->format('Y-m-d\TH:i');
                @endphp
                
                <div class="col-md-6 form-group mb-3">
                    <label for="fechaVencimiento">Fecha de Vencimiento</label>
                    <input type="datetime-local" name="fechaVencimiento" id="fechaVencimiento" class="form-control" min="{{ $today }}" required>
                </div>
            </div>

            {{-- Campos de personalización --}}
            <div class="row">
                <div class="col-md-4 form-group mb-3">
                    <label for="logo">Logo</label>
                    <input type="file" name="logo" id="logo" class="form-control-file">
                </div>

                <div class="col-md-4 form-group mb-3">
                    <label for="color_principal">Color Principal</label>
                    <input type="color" name="color_principal" id="color_principal" class="form-control" value="#ffffff">
                </div>

                <div class="col-md-4 form-group mb-3">
                    <label for="color_secundario">Color Secundario</label>
                    <input type="color" name="color_secundario" id="color_secundario" class="form-control" value="#000000">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group mb-3">
                    <label for="color_terciario">Color Tercero</label>
                    <input type="color" name="color_terciario" id="color_terciario" class="form-control" value="#000000">
                </div>
                <div class="col-md-4 form-group mb-3">
                    <label for="color_cuarto">Color cuarto</label>
                    <input type="color" name="color_cuarto" id="color_cuarto" class="form-control" value="#000000">
                </div>
                <div class="col-md-4 form-group mb-3">
                    <label for="color_quinto">Color Quinto</label>
                    <input type="color" name="color_quinto" id="color_quinto" class="form-control" value="#000000">
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('encuestas.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Agregar Preguntas</button>
            </div>
        </form>
    </main>
@endsection
