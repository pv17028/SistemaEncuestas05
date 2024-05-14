@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Realizar Bloqueo</h2>
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

        <form action="{{ route('bloqueos.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="user_id">Usuario</label>
                <select class="form-control" id="user_id" name="user_id">
                    <option value="">Seleccione un usuario</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="reason">Motivo</label>
                <select class="form-control" id="reason" name="reason">
                    <option value="">Selecciona un motivo</option>
                    <option value="Spam">Spam</option>
                    <option value="Comportamiento abusivo">Comportamiento abusivo</option>
                    <option value="Cuenta falsa">Cuenta falsa</option>
                    <!-- Agrega más opciones según sea necesario -->
                </select>
            </div>

            <div class="text-center">
                <a href="{{ route('bloqueos.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Bloquear</button>
            </div>
        </form>
    </main>
@endsection
