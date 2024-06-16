{{-- resources/views/encuestas/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 pt-4" style="max-height: 700px; overflow-y: auto;" >

            <br>
            <div class="card mb-3">
                <div class="card-body">
                    <h2>Cantidad de preguntas por encuesta</h2>
                    <canvas id="grafico1" width="600" height="200"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h2>Cantidad de respuestas por encuesta</h2>
                    <canvas id="grafico2" width="600" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        
        var labels = {!! json_encode($titulos) !!};
        var data = {!! json_encode($totalPreguntas) !!};
        var respuestas = {!! json_encode($totalRespuestas) !!};
        
        // Función para generar un color aleatorio
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
        }
            return color;
        }
        // Arreglos para guardar los colores de fondo y borde de las barras
        var backgroundColors = [];
        var borderColors = [];

        // Generar colores aleatorios para cada barra
        for (var i = 0; i < data.length; i++) {
            var color = getRandomColor();
            backgroundColors.push(color + '4D'); // Con transparencia
            borderColors.push(color);
        }

        // Gráfico de preguntas por encuesta
        const ctx = document.getElementById('grafico1');
        new Chart(ctx, {
            type: 'bar',
            data: {
            labels: labels,
            datasets: [{
                label: 'Preguntas',
                data: data,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                    beginAtZero: true,
                }
            }
            }
        });

        // Gráfico de respuestas por encuesta
        const ctx2 = document.getElementById('grafico2');
        new Chart(ctx2, {
            type: 'bar',
            data: {
            labels: labels,
            datasets: [{
                label: 'Respuestas',
                data: respuestas,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
            }]
            },
            options: {
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
@endsection
