@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Panel</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    {{-- <button type="button" class="btn btn-sm btn-outline-secondary">Compartir</button> --}}
                    <a href="{{ route('exportacion.index') }}" class="btn btn-sm btn-outline-secondary role="button"
                        aria-pressed="true">Generar reportes</a>
                </div>
                {{-- <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle unique-dropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-feather="calendar"></span>
                        Esta semana
                    </button>
                    <ul class="dropdown-menu unique-dropdown-menu">
                        <li><a class="dropdown-item" href="#">Opción 1</a></li>
                        <li><a class="dropdown-item" href="#">Opción 2</a></li>
                        <li><a class="dropdown-item" href="#">Opción 3</a></li>
                    </ul>
                </div> --}}
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <h2 class="row justify-content-center">Número de Encuestas Creadas por Mes</h2>
                <canvas id="graficoPrincipal" width="600" height="200"></canvas>
            </div>
        </div>

        <h2>Top 10 Usuarios con Más Encuestas</h2>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre de Usuario</th>
                        <th scope="col">Número de Encuestas</th>
                        <th scope="col">Encuesta con Más Respuestas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->encuestas_count }}</td>
                            <td>{{ $usuario->encuesta_mas_respondida->titulo ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            $(".unique-dropdown-menu a").click(function() {
                var selected = $(this).text();
                $(".unique-dropdown").text(selected);
            });
        });

        // Gráfico
        // Elementos para el gráfico
        var encuestas = {!! json_encode($encuestas) !!};

        var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre',
            'Noviembre', 'Diciembre'
        ];
        
        var data = [];
        var labels = [];

        encuestas.forEach(function(encuesta) {
            labels.push(encuesta.mes);
            data.push(encuesta.cantidad_encuestas);
        });

        console.log(data);
        const ctx = document.getElementById('graficoPrincipal');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Encuestas totales',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo de las barras
                    borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras
                    borderWidth: 1
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Número de Encuestas Creadas por Mes', // Título del gráfico
                    fontSize: 20
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Número de Encuestas' // Leyenda del eje Y
                        }
                    },
                    x: {
                        scaleLabel: {
                            display: true,
                            labelString: 'Mes' // Leyenda del eje X
                        }
                    }
                }
            }
        });
    </script>
@endsection
