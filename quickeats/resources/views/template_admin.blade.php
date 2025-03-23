<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'QuickEats')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/quick_logo2.ico') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" type="text/css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="{{ asset('css/estilos.css') }}" type="text/css" rel="stylesheet">
</head>
<body class="bg-body-secondary d-flex flex-column min-vh-100">
<header>
        <nav class="navbar navbar-expand-sm navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home_admin') }}">
                    <img src="{{ asset('images/quick_logo.png') }}" style="width: 150px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar padrão -->
                <div class="collapse navbar-collapse" id="nav-principal">
                    <ul class="navbar-nav me-auto d-flex flex-nowrap">
                        <li class="nav-item">
                            <a class="nav-link text-white text-nowrap" href="{{ route('admin_restaurantes') }}">Restaurantes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white text-nowrap" href="{{ route('admin_clientes') }}">Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white text-nowrap" href="{{ route('chamados_admin') }}">Mensagens</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i id="minhaConta" class='fas fa-user-alt' style="color: white;"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="">Meu perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout_admin') }}">Log out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Offcanvas Navbar -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('adm_cliente') }}">Minha conta</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('admin_restaurantes') }}">Restaurantes</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('admin_clientes') }}">Clientes</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('chamados_admin') }}">Mensagens</a></li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Log out</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>