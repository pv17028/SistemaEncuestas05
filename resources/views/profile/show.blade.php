@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Perfil de usuario</h2>
            <div>
                <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-sm">Editar perfil</a>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label><strong>Imagen de perfil</strong></label>
                    <br>
                    <img src="{{ auth()->user()->profile_image }}" alt="Imagen de perfil" class="img-thumbnail">
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label><strong>Nombre</strong></label>
                    <p>{{ auth()->user()->nombre }}</p>
                </div>

                <div class="form-group">
                    <label><strong>Apellido</strong></label>
                    <p>{{ auth()->user()->apellido }}</p>
                </div>

                <div class="form-group">
                    <label><strong>Fecha de nacimiento</strong></label>
                    <p>{{ \Carbon\Carbon::parse(auth()->user()->fechaNacimiento)->format('d-m-Y') }}</p>
                </div>

                <div class="form-group">
                    <label><strong>Correo electrónico</strong></label>
                    <p>{{ auth()->user()->correoElectronico }}</p>
                </div>

                <div class="form-group">
                    <label><strong>Nombre de usuario</strong></label>
                    <p>{{ auth()->user()->username }}</p>
                </div>
            </div>
        </div>
    </main>
@endsection
