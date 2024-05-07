<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SurveyPro') }}</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        body {
            background-image: url('{{ asset('img/logo.png') }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .dropdown-btn {
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            outline: none;
        }

        .dropdown-container {
            display: none;
            padding-left: 8px;
        }

        .fa-caret-down {
            float: right;
            padding-right: 8px;
        }
    </style>

    <!-- Estilos personalizados para esta plantilla -->
    <link href="{{ asset('dashboard.css') }}" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div id="app">
        @auth
            <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow" style="height: 49px">
                <a class="navbar-brand col-md-3 col-lg-2 me-0 px-4" href="#">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" width="30" height="30">
                    SurveyPro
                </a>
                <button class="navbar-toggler position-absolute d-md-none collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                {{-- <input class="form-control form-control-dark w-100" type="text" placeholder="Buscar" aria-label="Buscar"> --}}
                <div class="container-fluid d-flex justify-content-center">
                    <form class="d-flex" style="height: 40px">
                        <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search"
                            style="border-radius: 5px; width: 400px;">
                        <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="dropdown" style="height: 49px;">
                    <div class="nav-item dropdown text-nowrap h-100">
                        <a class="nav-link dropdown-toggle px-3 text-light h-100 d-flex align-items-center" href="#"
                            id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="#">Configuración</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Cerrar sesión') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="container-fluid">
                <div class="row">
                    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                        <div class="position-sticky pt-3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}"
                                        aria-current="page" href="{{ route('home') }}">
                                        <span data-feather="home"></span>
                                        Panel
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::currentRouteName() == 'encuestas.index' ? 'active' : '' }}"
                                        href="{{ route('encuestas.index') }}">
                                        <span data-feather="file-text"></span>
                                        Encuestas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::currentRouteName() == '' ? 'active' : '' }}"
                                        href="">
                                        <span data-feather="share-2"></span>
                                        Encuestas Compartidas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::currentRouteName() == '' ? 'active' : '' }}"
                                        href="">
                                        <span data-feather="bar-chart-2"></span>
                                        Resultados
                                    </a>
                                </li>
                                @if (Auth::user()->isAdmin())
                                    <!-- Asegúrate de tener una función que verifique si el usuario es administrador -->
                                    <li class="nav-item">
                                        <button class="nav-link dropdown-btn">
                                            <span data-feather="tool"></span>
                                            Administración
                                            <i class="fa fa-caret-down"></i>
                                        </button>
                                        <div class="dropdown-container">
                                            <a class="nav-link {{ Route::currentRouteName() == '' ? 'active' : '' }}" href="">
                                                <span data-feather="user"></span>
                                                Gestionar Usuarios
                                            </a>
                                            <a class="nav-link {{ Route::currentRouteName() == '' ? 'active' : '' }}" href="">
                                                <span data-feather="file-text"></span>
                                                Gestionar Encuestas
                                            </a>
                                            <a class="nav-link {{ Route::currentRouteName() == '' ? 'active' : '' }}" href="">
                                                <span data-feather="users"></span>
                                                Gestionar Roles
                                            </a>
                                            <a class="nav-link {{ Route::currentRouteName() == '' ? 'active' : '' }}" href="">
                                                <span data-feather="lock"></span>
                                                Gestionar Privilegios
                                            </a>
                                        </div>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::currentRouteName() == '' ? 'active' : '' }}"
                                        href="">
                                        <span data-feather="settings"></span>
                                        Configuración
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link {{ Route::currentRouteName() == 'usuarios.index' ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                                        <span data-feather="users"></span>
                                        Usuarios
                                    </a>
                                </li> --}}
                            </ul>

                            <h6
                                class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                                <span>Informes guardados</span>
                                <a class="link-secondary" href="#" aria-label="Agregar un nuevo informe">
                                    <span data-feather="plus-circle"></span>
                                </a>
                            </h6>
                            <ul class="nav flex-column mb-2">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <span data-feather="file-text"></span>
                                        Mes actual
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <span data-feather="file-text"></span>
                                        Último trimestre
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <span data-feather="file-text"></span>
                                        Participación social
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        @endauth
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-zdZpZR/zdBkz7zZnj1Nhxx3Wfb1qCGF9JcfZJkTmIuZYkKhxM2+I2BXJmNVQDpr2" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
        integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
    </script>
    <script src="{{ asset('dashboard.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var dropdown = document.getElementsByClassName("dropdown-btn");
        var i;

        for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
    </script>
</body>
@auth
    <footer class="bg-dark text-white text-center py-2 fixed-bottom">
        Sistema de encuestas - SurveyPro - Grupo 05 &copy; {{ date('Y') }}
    </footer>
@endauth

</html>
