<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" aria-current="page"
                   href="{{ route('home') }}">
                    <span data-feather="home"></span>
                    Panel
                </a>
            </li>
            @if (Auth::user()->hasPrivilege('encuestas.index'))
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'encuestas.index' ? 'active' : '' }}"
                       href="{{ route('encuestas.index') }}">
                        <span data-feather="file-text"></span>
                        Encuestas
                    </a>
                </li>
            @endif
            @if (Auth::user()->hasPrivilege('ecompartidas.index'))
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'ecompartidas.index' ? 'active' : '' }}"
                       href="{{ route('ecompartidas.index') }}">
                        <span data-feather="share-2"></span>
                        Encuestas Compartidas
                    </a>
                </li>
            @endif
            @if (Auth::user()->hasPrivilege('resultadoEncuesta.index'))
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'resultadoEncuesta.index' ? 'active' : '' }}"
                       href="{{ route('resultadoEncuesta.index') }}">
                        <span data-feather="bar-chart-2"></span>
                        Resultados
                    </a>
                </li>
            @endif
            @if (Auth::user()->hasPrivilege('roles.index') || Auth::user()->hasPrivilege('privilegios.index') || Auth::user()->hasPrivilege('bloqueos.index') || Auth::user()->hasPrivilege('gestionEncuestas.index') || Auth::user()->hasPrivilege('users.index'))
                <li class="nav-item">
                    <button class="nav-link dropdown-btn">
                        <span data-feather="tool"></span>
                        Administración
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="dropdown-container">
                        @if (Auth::user()->hasPrivilege('users.index'))
                            <a class="nav-link {{ Route::currentRouteName() == 'users.index' ? 'active' : '' }}"
                               href="{{ route('users.index') }}">
                                <span data-feather="users"></span>
                                Gestionar Usuarios
                            </a>
                        @endif
                        @if (Auth::user()->hasPrivilege('bloqueos.index'))
                            <a class="nav-link {{ Route::currentRouteName() == 'bloqueos.index' ? 'active' : '' }}"
                               href="{{ route('bloqueos.index') }}">
                                <span data-feather="lock"></span>
                                Gestionar Bloqueos
                            </a>
                        @endif
                        @if (Auth::user()->hasPrivilege('gestionEncuestas.index'))
                            <a class="nav-link {{ Route::currentRouteName() == 'gestionEncuestas.index' ? 'active' : '' }}"
                               href="{{ route('gestionEncuestas.index') }}">
                                <span data-feather="file-text"></span>
                                Gestionar Encuestas
                            </a>
                        @endif
                        @if (Auth::user()->hasPrivilege('roles.index'))
                            <a class="nav-link {{ Route::currentRouteName() == 'roles.index' ? 'active' : '' }}"
                               href="{{ route('roles.index') }}">
                                <span data-feather="users"></span>
                                Gestionar Roles
                            </a>
                        @endif
                        @if (Auth::user()->hasPrivilege('privilegios.index'))
                            <a class="nav-link {{ Route::currentRouteName() == 'privilegios.index' ? 'active' : '' }}"
                               href="{{ route('privilegios.index') }}">
                                <span data-feather="lock"></span>
                                Gestionar Privilegios
                            </a>
                        @endif
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>

<script>
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function () {
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
