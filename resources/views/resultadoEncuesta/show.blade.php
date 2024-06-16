@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Resultados de: {{ $encuesta->titulo }}</h2>
            <div>
                <a href="{{ route('resultadoEncuesta.index') }}" class="btn btn-sm btn-secondary">Volver a la lista de
                    encuestas</a>
            </div>
        </div>
        <hr>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @forelse ($encuesta->preguntas as $key => $pregunta)
            <div style="display: flex; justify-content: space-around; align-items: center; width: 100%; height: 100%;">
                <div style="display: flex; justify-content: center; align-items: center; width: 500px; height: 200px;">
                    <canvas id="lineChart"></canvas>
                </div>

                <div style="display: flex; justify-content: center; align-items: center; width: 500px; height: 200px;">
                    <canvas id="completeIncompleteChart"></canvas>
                </div>
            </div>

            <script>
                var ctx = document.getElementById('lineChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_keys($respuestasPorFecha)) !!},
                        datasets: [{
                            label: 'Número de respuestas',
                            data: {!! json_encode(array_values($respuestasPorFecha)) !!},
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Número de respuestas a lo largo del tiempo'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                var ctx2 = document.getElementById('completeIncompleteChart').getContext('2d');
                var myChart2 = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($respuestasCompletasPorFecha)) !!},
                        datasets: [{
                            label: 'Respuestas completas',
                            data: {!! json_encode(array_values($respuestasCompletasPorFecha)) !!},
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Respuestas incompletas',
                            data: {!! json_encode(array_values($respuestasIncompletasPorFecha)) !!},
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Respuestas completas e incompletas a lo largo del tiempo'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            </script>

            @if ($pregunta->opciones->count() > 0)
                <h3>{{ $pregunta->contenidoPregunta }}</h3>
                <p><strong>Tipo de pregunta:</strong> {{ $pregunta->tipoPregunta->nombreTipoPregunta }}</p>

                <div style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
                    <div style="display: flex; justify-content: center; align-items: center; width: 500px; height: 200px;">
                        <canvas id="chart{{ $key }}"></canvas>
                    </div>
                </div>

                <script>
                    var ctx = document.getElementById('chart{{ $key }}').getContext('2d');
                    console.log({!! $pregunta->opciones->pluck('contenidoOpcion') !!});
                    console.log({!! $pregunta->opciones->pluck('seleccion_count') !!});
                    var myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: {!! $pregunta->opciones->pluck('contenidoOpcion') !!},
                            datasets: [{
                                data: {!! $pregunta->opciones->pluck('seleccion_count') !!},
                                backgroundColor: [
                                    // Agrega los colores que quieras para cada opción
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ]
                            }]
                        }
                    });
                </script>

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Opción</th>
                            <th>Respuestas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pregunta->opciones as $opcion)
                            <tr>
                                <td>{{ $opcion->contenidoOpcion }}</td>
                                <td>{{ $opcion->seleccion_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif($pregunta->respuestas->count() > 0)
                <h3>{{ $pregunta->contenidoPregunta }}</h3>
                <h4>Respuestas más recientes:</h4>
                <ul>
                    @foreach ($pregunta->respuestas->sortByDesc('created_at')->take(5) as $respuesta)
                        <li>{{ $respuesta->respuesta_abierta }}</li>
                    @endforeach
                </ul>
            @endif
        @empty
            <p>No hay resultados para mostrar.</p>
        @endforelse
    </main>
@endsection
