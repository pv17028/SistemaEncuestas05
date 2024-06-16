
<html>
    <head>
        <title>Reporte encuesta</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        body {
                font-family: 'Times New Roman', sans-serif;
            }
            .container {
                width: 100%;
                margin: 1 auto;
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
<h2>Reporte de Encuesta</h2>
    <div>
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>Nombre del encuestado</th>
                    <th>Apellido del encuestado </th>
                    <th>Nombre de Usuario </th>
                    <th>Correo Electrónico </th>
                    <th>Título de la encuesta </th>
                    <th>Descripción de la encuesta </th>
                    <th>Contenido de la pregunta </th>
                    <th>Respuesta seleccionada </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($encuestas as $encuesta)
                    <tr>
                        <td>{{ $encuesta->nombre }} </td>
                        <td>{{ $encuesta->apellido }} </td>
                        <td>{{ $encuesta->username }} </td>
                        <td>{{ $encuesta->correoElectronico }} </td>
                        <td>{{ $encuesta->titulo }} </td>
                        <td>{{ $encuesta->descripcionEncuesta }} </td>
                        <td>{{ $encuesta->contenidoPregunta }} </td>
                        <td>{{ $encuesta->contenidoOpcion }} </td>
                    </tr>
                @endforeach
            </tbody>
    </table>
    </div>
</body>
</html>
