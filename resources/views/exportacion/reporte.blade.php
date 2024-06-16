
<html>
    <head>
        <title>Reporte general </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        body {
                font-family: 'Times New Roman', sans-serif;
            }
            .container {
                width: 100%;
                margin: 0 auto;
            }
            .table-responsive {
                width: 100%;
                overflow-x: auto;
                font-size: 11px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 1rem;
                background-color: transparent;
                table-layout: fixed; /* Ajusta la tabla al contenido */
            }
            th, td {
                border: 1px solid #dee2e6;
                padding: 0.75rem;
                vertical-align: top;
                word-wrap: break-word; /* Ajusta las palabras largas */
            }
            th {
                background-color: #f8f9fa;
                text-align: left;
            }
            thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }
            tbody + tbody {
                border-top: 2px solid #dee2e6;
            }
        </style>
    </head>
<body>
<h1>Reporte general de encuestas</h1>
    <div>
        <h2>Encuestas realizadas</h2>
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>Nombre del encuestado</th>
                    <th>Apellido del encuestado</th>
                    <th>Nombre de Usuario</th>
                    <th>Correo Electrónico</th>
                    <th>Total de encuestas realizadas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($encuestas as $encuesta)
                    <tr>
                        <td>{{ $encuesta->nombre }} </td>
                        <td>{{ $encuesta->apellido }} </td>
                        <td>{{ $encuesta->username }} </td>
                        <td>{{ $encuesta->correoElectronico }} </td>
                        <td>{{ $encuesta->total_encuestas}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h2>Encuestas respondidas</h2>
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Título de la encuesta</th>
                    <th>Desripción de la encuesta</th>
                    <th>Cantidad de preguntas</th>
                    <th>Cantidad de respuestas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($preguntas as $pregunta)
                    <tr>
                        <td>{{ $pregunta->username }} </td>
                        <td>{{ $pregunta->titulo }} </td>
                        <td>{{ $pregunta->descripcionEncuesta }} </td>
                        <td>{{ $pregunta->total_preguntas }} </td>
                        <td> {{$pregunta->total_respuestas }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card">
        <div class="card-body">
            <h2 class="row justify-content-center">Cantidad de encuestas respondidas</h2>
            <img id="chart" src="{{ $chartUrl }}" alt="Gráfico 2">
        </div>
    </div>
</body>
</html>
