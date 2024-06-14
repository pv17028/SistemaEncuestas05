{{-- resources/views/encuestas/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 pt-4" style="max-height: 700px; overflow-y: auto;" >

            <br>
            <div class="card mb-3">
                <div class="card-body">
                    <h2>Gráfico de preguntas por encuesta</h2>
                    <canvas id="grafico1" width="600" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var labels = {!! json_encode($titulos) !!};
        var data = {!! json_encode($totalPreguntas) !!};
        // Función para generar un color aleatorio
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
        }
            return color;
        }

        var backgroundColors = [];
        var borderColors = [];

        
        for (var i = 0; i < data.length; i++) {
            var color = getRandomColor();
            backgroundColors.push(color + '4D'); // Con transparencia
            borderColors.push(color);
        }

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
                beginAtZero: true
                }
            }
            }
        });

    </script>
@endsection
