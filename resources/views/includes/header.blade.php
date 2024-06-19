<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow" style="height: 54px">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-4" href="{{ route('home') }}">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" width="30" height="30">
        SurveyPro
    </a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button"
            data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="dropdown" style="height: 54px;">
        <div class="nav-item dropdown text-nowrap h-100">
            <a class="nav-link dropdown-toggle px-3 text-light h-100 d-flex align-items-center" href="#"
               id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                @if (Auth::user()->imagenPerfil)
                    <img src="{{ asset('imagenPerfil/' . Auth::user()->imagenPerfil) }}"
                         style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                @else
                    <i class="fas fa-user-circle text-white" style="font-size: 30px; margin-right: 10px;"></i>
                @endif
                {{ Auth::user()->nombre }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

<script>
    var dropdowns = document.getElementsByClassName("dropdown-toggle");
    
    for (var i = 0; i < dropdowns.length; i++) {
        dropdowns[i].addEventListener("click", function() {
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>
