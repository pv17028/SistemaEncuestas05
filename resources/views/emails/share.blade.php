<!DOCTYPE html>
<html>
<head>
    <title>Compartir Encuesta</title>
</head>
<body>
    <h1>Encuesta Compartida</h1>
    <p>¡Hola! Te han compartido una encuesta. Haz clic en el enlace de abajo para acceder a ella.</p>
    <a href="{{ route('ecompartidas.show', $encuesta->idEncuesta) }}">Ver Encuesta</a>
</body>
</html>