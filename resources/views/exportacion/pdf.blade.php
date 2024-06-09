
<html>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<head>
    <title>Reporte encuesta</title>
</head>
<body>
<h2>Reporte de Encuesta</h2>
        <table class="table ">
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
</body>
</html>
