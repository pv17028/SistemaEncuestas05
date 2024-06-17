<!-- resources/views/emails/account_blocked.blade.php -->

<h1>Tu cuenta ha sido bloqueada</h1>

<p>Estimado(a) {{ $user->nombre }} {{ $user->apellido }} ({{ $user->username }}),</p>

<p>Lamentamos informarte que tu cuenta ha sido bloqueada debido a {{ $bloqueo->reason }}.</p>

<p>Si crees que esto es un error o necesitas más información, por favor, ponte en contacto con nuestro equipo de soporte (surveyprosv@gmail.com).</p>

<p>Saludos,</p>
<p>El equipo de soporte</p>