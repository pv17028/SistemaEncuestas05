@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="text-center">
            <h2>Crear Rol</h2>
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

        <form method="POST" action="{{ route('roles.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombreRol">Nombre del Rol</label>
                        <input type="text" class="form-control" id="nombreRol" name="nombreRol" placeholder="Ingresa el nombre del rol" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="descripcionRol">Descripción del Rol</label>
                        <textarea class="form-control" id="descripcionRol" name="descripcionRol" placeholder="Ingresa la descripción del rol"></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Privilegios</label>
                        <div style="max-height: 500px; overflow-y: auto; border: 1px solid #ccc; border-radius: 5px; padding: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    Seleccionar todos
                                </label>
                            </div>
                            <hr>
                            @foreach ($privilegios as $modulo => $privilegiosModulo)
                                <div class="form-check">
                                    <input class="form-check-input modulo-checkbox" type="checkbox" id="modulo{{ $modulo }}">
                                    <label class="form-check-label" for="modulo{{ $modulo }}">
                                        {{ $nombresModulos[$modulo] ?? $modulo }}
                                    </label>
                                    <div class="ml-4">
                                        @foreach ($privilegiosModulo as $privilegio)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $privilegio->idPrivilegio }}"
                                                    id="privilegio{{ $privilegio->idPrivilegio }}" name="privilegios[]">
                                                <label class="form-check-label" for="privilegio{{ $privilegio->idPrivilegio }}">
                                                    {{ $privilegio->nombrePrivilegio }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <script>
                            document.getElementById('selectAll').addEventListener('change', function(e) {
                                var state = this.checked;
                                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                                checkboxes.forEach(function(checkbox) {
                                    checkbox.checked = state;
                                });
                            });

                            document.querySelectorAll('.modulo-checkbox').forEach(function(checkbox) {
                                checkbox.addEventListener('change', function(e) {
                                    var state = this.checked;
                                    var childCheckboxes = this.parentNode.querySelectorAll('input[type="checkbox"]');
                                    childCheckboxes.forEach(function(childCheckbox) {
                                        childCheckbox.checked = state;
                                    });
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
        </form>
    </main>
@endsection
