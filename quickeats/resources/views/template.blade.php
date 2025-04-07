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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <header>
        <nav class="navbar navbar-expand-sm navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('index') }}"><img src="{{ asset('images/quick_logo.png') }}" style="width: 150px;"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-principal">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="nav-principal">
                    <ul class="navbar-nav ms-auto">
                        @yield('nav-buttons')
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer text-white pb-5 mt-auto">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <p class="col-md-4 mb-0">&copy; 2025 QuickEats, Inc</p>
                <ul class="nav col-md-4 justify-content-end">
                    <li class="nav-item"><a href="{{ route('faqs') }}" class="nav-link px-2">Ajuda</a></li>
                    <li class="nav-item"><a href="{{ route('sobre') }}" class="nav-link px-2">Sobre</a></li>
                    <li class="nav-item"><a href="{{ route('contato') }}" class="nav-link px-2">Contato</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>