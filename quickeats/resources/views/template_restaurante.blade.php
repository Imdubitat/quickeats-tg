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
<body class="d-flex flex-column min-vh-100">
<header>
        <nav class="navbar navbar-expand-sm navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home_restaurante') }}">
                    <img src="{{ asset('images/quick_logo.png') }}" style="width: 150px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar padrão -->
                <div class="collapse navbar-collapse" id="nav-principal">
                    <ul class="navbar-nav me-auto d-flex flex-nowrap">
                        <li class="nav-item">
                            <a class="nav-link text-white text-nowrap" href="{{ route('pedidos_restaurante') }}">Pedidos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white text-nowrap" href="{{ route('produtos_restaurante') }}">Produtos</a>
                        </li>
                        <li>
                            <a class="nav-link text-white text-nowrap" href="{{ route('estoque_restaurante') }}">Estoque</a>
                        </li>
                        <li>
                            <a class="nav-link text-white text-nowrap" href="{{ route('grade_horario') }}">Grade de horários</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i id="minhaConta" class='fas fa-user-alt' style="color: white;"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard_restaurante') }}">Dashboard </a></li>
                                <li><a class="dropdown-item" href="{{ route('adm_restaurante') }}">Minha conta</a></li>
                                <li><a class="dropdown-item" href="{{ route('planos_restaurante') }}">Meus planos</a></li>
                                <li><a class="dropdown-item" href="{{ route('listar_chamados_estab') }}">Suporte</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout_estabelecimento') }}">Log out</a></li>
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
                    <li>
                        <a class="dropdown-item" href="{{ route('pedidos_restaurante') }}">Pedidos</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('produtos_restaurante') }}">Produtos</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('estoque_restaurante') }}">Estoque</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('grade_horario') }}">Grades de horários</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('dashboard_restaurante') }}">Dashboard </a></li>
                    <li><a class="dropdown-item" href="{{ route('adm_restaurante') }}">Minha conta</a></li>
                    <li><a class="dropdown-item" href="{{ route('listar_chamados_estab') }}">Suporte</a></li>
                    <li><a class="dropdown-item" href="{{ route('logout_estabelecimento') }}">Log out</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer text-white pb-5 mt-auto">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <p class="col-md-4 mb-0">&copy; 2025 QuickEats, Inc</p>
                <ul class="nav col-md-4 justify-content-end">
                    <li class="nav-item"><a href="{{ route('listar_chamados_estab') }}" class="nav-link px-2">Ajuda</a></li>
                    <li class="nav-item"><a href="{{ route('sobre') }}" class="nav-link px-2">Sobre</a></li>
                    <li class="nav-item"><a href="{{ route('contato') }}" class="nav-link px-2">Contato</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>