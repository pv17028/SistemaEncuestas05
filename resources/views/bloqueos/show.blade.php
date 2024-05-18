@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalles de Bloqueo</h2>
            <div>
                @if ($bloqueo->user)
                    <a href="{{ route('bloqueos.edit', $bloqueo->id) }}" class="btn btn-warning btn-sm">Editar Bloqueo</a>
                @endif
                <a href="{{ route('bloqueos.index') }}" class="btn btn-secondary btn-sm">Volver al registro de Bloqueos</a>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header">
                @if ($bloqueo->user)
                    {{ $bloqueo->user->nombre }} {{ $bloqueo->user->apellido }}
                @else
                    {{ $bloqueo->nombre_historico }} {{ $bloqueo->apellido_historico }}
                @endif
            </div>
            <div class="card-body">
                <p><strong>Usuario: </strong>
                    @if ($bloqueo->user)
                        {{ $bloqueo->user->username }}
                    @else
                        {{ $bloqueo->username_historico }}
                    @endif
                </p>
                <p><strong>Correo Electrónico: </strong>
                    @if ($bloqueo->user)
                        {{ $bloqueo->user->correoElectronico }}
                    @else
                        {{ $bloqueo->email_historico }}
                    @endif
                </p>
                <p><strong>Razón: </strong>{{ $bloqueo->reason }}</p>
                <p>
                    <strong>Estado de la Cuenta del Usuario:</strong>
                    @if ($bloqueo->user)
                        Activa
                    @else
                        Eliminada
                    @endif
                </p>
                <p>
                    <strong>Estado del Bloqueo:</strong>
                    @if ($bloqueo->status == 'blocked')
                        Bloqueado permanentemente
                    @elseif($bloqueo->status == 'temporarily_blocked')
                        Temporalmente bloqueado
                    @else
                        Desbloqueado
                    @endif
                </p>
                <p><strong>Fecha de Bloqueo: </strong>{{ $bloqueo->blocked_at }}</p>
                <p><strong>Fecha de Desbloqueo: </strong>{{ $bloqueo->unblocked_at }}</p>
                @php
                    if (!function_exists('formatDuration')) {
                        function formatDuration($seconds)
                        {
                            $periods = [
                                'año' => 365 * 24 * 60 * 60,
                                'mes' => 30 * 24 * 60 * 60,
                                'día' => 24 * 60 * 60,
                                'hora' => 60 * 60,
                                'minuto' => 60,
                                'segundo' => 1,
                            ];

                            $parts = [];
                            foreach ($periods as $name => $duration) {
                                $value = floor($seconds / $duration);
                                if ($value > 0) {
                                    $seconds -= $value * $duration;
                                    $parts[] = $value . ' ' . $name . ($value > 1 ? 's' : '');
                                }
                            }

                            return implode(' ', $parts);
                        }
                    }
                @endphp

                <p><strong>Duración del Bloqueo: </strong>{{ formatDuration($bloqueo->block_duration) }}</p>
            </div>
        </div>
    </main>
@endsection
