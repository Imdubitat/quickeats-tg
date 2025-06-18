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
<body class="bg-body-secondary d-flex flex-column min-vh-100">
<header>
        <nav class="navbar navbar-expand-sm navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home_cliente') }}">
                    <img src="{{ asset('images/quick_logo.png') }}" style="width: 150px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar padrão -->
                <div class="collapse navbar-collapse" id="nav-principal">
                    <ul class="navbar-nav me-auto d-flex flex-nowrap">
                        <li class="nav-item">
                            <a class="nav-link text-white text-nowrap" href="{{ route('catalogo_restaurantes') }}">Restaurantes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white text-nowrap" href="{{ route('catalogo_produtos') }}">Produtos</a>
                        </li>
                        <li>
                            <a class="nav-link text-white text-nowrap" href="{{ route('pedidos_cliente') }}">Meus pedidos</a>
                        </li>
                    </ul>

                    <!-- Barra de Pesquisa -->
                    <div class="container mx-5">
                        <form method="post" action="{{ route('pesquisa') }}" class="d-flex">
                            @csrf
                            <input class="form-control" type="text" id="termo_pesquisa" name="termoPesquisa" placeholder="Estou procurando por..." autocomplete="off">
                            <button class="btn btn-danger" type="submit">
                                <i class='fas fa-search'></i>
                            </button>
                        </form>
                        <!-- Container para os resultados do autocomplete -->
                        <div id="sugestoes" class="list-group" style="position: absolute;"></div>
                    </div>

                    <ul class="navbar-nav ms-auto">
                        <a class="nav-link me-4" href="{{ route('exibir_favoritos') }}">
                            <i id="favoritos" class='fas fa-heart' style="color: white;"></i>
                        </a>
                        <a class="nav-link me-4" href="{{ route('carrinho') }}">
                            <i id="carrinho" class='fas fa-shopping-cart' style="color: white;"></i>
                        </a>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i id="minhaConta" class='fas fa-user-alt' style="color: white;"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('adm_cliente') }}">Meu perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('enderecos') }}">Meus Endereços</a></li>
                                <li><a class="dropdown-item" href="{{ route('listar_chamados_cliente') }}">Suporte</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout_cliente') }}">Log out</a></li>
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
                    <li class="nav-item">
                        <form method="post" action="" class="d-flex">
                            <input class="form-control" type="text" id="pesquisa" name="pesquisa" placeholder="Estou procurando por...">
                            <button class="btn btn-danger" type="submit">
                                <i class='fas fa-search'></i>
                            </button>
                        </form>
                    </li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('exibir_favoritos') }}">Favoritos</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('adm_cliente') }}">Minha conta</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('pedidos_cliente') }}">Meus pedidos</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('enderecos') }}">Meus Endereços</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('listar_chamados_cliente') }}">Suporte</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('carrinho') }}">Meu carrinho</a></li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalogo_restaurantes') }}">Restaurantes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalogo_produtos') }}">Produtos</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('logout_cliente') }}">Log out</a></li>
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
                    <li class="nav-item"><a href="{{ route('listar_chamados_cliente') }}" class="nav-link px-2">Ajuda</a></li>
                    <li class="nav-item"><a href="{{ route('sobre') }}" class="nav-link px-2">Sobre</a></li>
                    <li class="nav-item"><a href="{{ route('contato') }}" class="nav-link px-2">Contato</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let timeout; // Variável para armazenar o timeout do debounce

            $('#termo_pesquisa').on('input', function() {
                let termo = $(this).val();
                clearTimeout(timeout); // Limpa o timeout anterior

                if (termo.length >= 1) { // Só busca se tiver 2 ou mais caracteres
                    timeout = setTimeout(function() { // Adiciona debounce de 300ms
                        $.ajax({
                            url: '{{ route("autocomplete") }}', // Rota para o autocomplete
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', // Token CSRF para segurança
                                termoPesquisa: termo
                            },
                            success: function(response) {
                                let sugestoes = '';
                                if (response.produtos.length > 0 || response.estabelecimentos.length > 0) {
                                    // Produtos
                                    response.produtos.forEach(function(produto) {
                                        sugestoes += `<a href="#" class="list-group-item list-group-item-action" data-tipo="produto" data-valor="${produto.nome}">${produto.nome}<br><small class="text-muted">produto</small></a>`;
                                    });
                                    // Estabelecimentos
                                    response.estabelecimentos.forEach(function(estabelecimento) {
                                        sugestoes += `<a href="#" class="list-group-item list-group-item-action" data-tipo="estabelecimento" data-valor="${estabelecimento.nome_fantasia}">${estabelecimento.nome_fantasia}<br><small class="text-muted">estabelecimento</small></a>`;
                                    });
                                } else {
                                    sugestoes = '<div class="list-group-item">Nenhum resultado encontrado</div>';
                                }
                                $('#sugestoes').html(sugestoes).show();
                                $('#sugestoes').width($('#termo_pesquisa').outerWidth());
                            },
                            error: function() {
                                $('#sugestoes').html('<div class="list-group-item">Erro ao buscar</div>').show();
                            }
                        });
                    }, 300); // Aguarda 300ms após o último caractere digitado
                } else {
                    $('#sugestoes').hide(); // Esconde se o termo for muito curto
                }
            });

            // Evento de clique nas sugestões
            $('#sugestoes').on('click', '.list-group-item-action', function(e) {
                e.preventDefault(); // Evita comportamento padrão do link
                let valor = $(this).data('valor'); // Pega o valor do item clicado
                $('#termo_pesquisa').val(valor); // Preenche o campo de busca
                $('#sugestoes').hide(); // Esconde as sugestões
            });

            // Esconder sugestões ao clicar fora
            $(document).click(function(e) {
                if (!$(e.target).closest('#termo_pesquisa, #sugestoes').length) {
                    $('#sugestoes').hide();
                }
            });
        });
    </script>
</body>
</html>