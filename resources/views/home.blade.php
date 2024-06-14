@extends('layouts.app')

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Panel</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary">Compartir</button>
                    <a href="{{ route('exportacion.index') }}" class="btn btn-sm btn-outline-secondary role="button" aria-pressed="true">Exportar</a>
                </div>
                <div class="btn-group">
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
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="row justify-content-center">Gráfico de las encuestas</h2>
                <canvas id="graficoPrincipal" width="600" height="200"></canvas>
            </div>
        </div>

        <h2>Título de la sección</h2>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Encabezado</th>
                        <th scope="col">Encabezado</th>
                        <th scope="col">Encabezado</th>
                        <th scope="col">Encabezado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1,001</td>
                        <td>aleatorio</td>
                        <td>datos</td>
                        <td>espacio reservado</td>
                        <td>texto</td>
                    </tr>
                    <!-- Puedes agregar más filas de datos aquí -->
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
        
        var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
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
                borderWidth: 1
            }]
        },
        options: {
            scales: {
            y: {
                beginAtZero: true
            }
            },
            //responsive: false,  // Asegúrate de que el gráfico no se redimensione automáticamente
            //maintainAspectRatio: false  // Desactiva el mantenimiento de la relación de aspecto
        }
        });

    </script>
@endsection
