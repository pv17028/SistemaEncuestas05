@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Editar Bloqueo</h2>
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
        
        <form method="POST" action="{{ route('bloqueos.update', $bloqueo->id) }}">
            @csrf
            @method('PUT')


            <div class="form-group mb-3">
                <label for="reason">Motivo</label>
                <select class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" required>
                    <option value="">Selecciona un motivo</option>
                    <option value="Spam">Spam</option>
                    <option value="Comportamiento abusivo">Comportamiento abusivo</option>
                    <option value="Cuenta falsa">Cuenta falsa</option>
                    @foreach($razones as $razon)
                        <option value="{{ $razon->reason }}" @if(old('reason', $bloqueo->reason) == $razon->reason) selected @endif>{{ $razon->reason }}</option>
                    @endforeach
                </select>

                @error('reason')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="status">{{ __('Estado') }}</label>
                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                    <option value="blocked" @if (old('status', $bloqueo->status) == 'blocked') selected @endif>Bloqueado</option>
                    <option value="active" @if (old('status', $bloqueo->status) == 'active') selected @endif>Desbloqueado</option>
                </select>

                @error('status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="text-center">
                <a href="{{ route('bloqueos.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
        </form>
    </main>
@endsection
