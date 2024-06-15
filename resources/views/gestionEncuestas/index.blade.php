@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Gestión de Encuestas</h2>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6 mb-4">
                <div class="card border-info mb-3 shadow">
                    <div class="card-header bg-light text-dark"><strong>Tipos de Preguntas</strong></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="card-title mb-3"><strong>Cantidad:</strong> {{ $cantidadTiposPreguntas }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="card-title mb-3"><strong>Habilitadas:</strong> {{ $cantidadTiposPreguntasHabilitadas }}</p>
                                <p class="card-title mb-3"><strong>Deshabilitadas:</strong> {{ $cantidadTiposPreguntasDeshabilitadas }}</p>
                            </div>
                        </div>
                        <a href="{{ route('tiposPreguntas.index') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-arrow-right"></i> Ir a Tipos de Preguntas
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-4">
                <div class="card border-info mb-3 shadow">
                    <div class="card-header bg-light text-dark"><strong>Otra Opción</strong></div>
                    <div class="card-body">
                        <p class="card-title mb-3"><strong>Cantidad:</strong> 0</p>
                        <a href="#" class="btn btn-info btn-sm">
                            <i class="fas fa-arrow-right"></i> Ir a Otra Opción
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection