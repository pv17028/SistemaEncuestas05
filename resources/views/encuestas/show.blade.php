@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalles de Encuesta</h2>
            <div>
                <a href="{{ route('encuestas.edit', $encuesta->idEncuesta) }}" class="btn btn-warning btn-sm">Editar
                    Encuesta</a>
                <a href="{{ route('encuestas.index') }}" class="btn btn-secondary btn-sm">Volver al listado de Encuestas</a>
            </div>
        </div>
        <hr>

        <div class="card">
            <div class="card-header">
                {{ $encuesta->titulo }}
            </div>
            <div class="card-body">
                <p><strong>Objetivo:</strong> {{ $encuesta->objetivo }}</p>
                <p><strong>Descripción:</strong> {{ $encuesta->descripcionEncuesta }}</p>
                <p><strong>Grupo Meta:</strong> {{ $encuesta->grupoMeta }}</p>
                <p><strong>Fecha de Creación:</strong> {{ \Carbon\Carbon::parse($encuesta->created_at)->format('d-m-Y g:i:s A') }}</p>
                <p><strong>Fecha de Vencimiento:</strong> {{ \Carbon\Carbon::parse($encuesta->fechaVencimiento)->format('d-m-Y') }}</p>
                {{-- Aquí puedes agregar cualquier otra información que desees mostrar --}}
            </div>
        </div>
    </main>
@endsection
