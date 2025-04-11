@extends('template_restaurante')

@section('title', 'Adm Restaurante')

@section('nav-buttons')
@endsection

@section('content')
<section class="container px-5 mx-auto" style="margin-top: 15rem;">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-info h-100">
                    <div class="card-header">Média de Avaliações</div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <h5 class="card-title">{{ $avaliacao[0]->media_avaliacao }}</h5>
                        <h5 class="text-warning d-inline">&#9733;</h5>
                    </div>
                </div>
            </div>
            <!-- Total de Clientes -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary h-100">
                    <div class="card-header">Total de Clientes</div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <h5 class="card-title">{{ $data['total_clientes'] }}</h5>
                    </div>
                </div>
            </div>
            
            <!-- Total de Pedidos -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-header">Total de Pedidos Finalizados</div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <h5 class="card-title">{{ $data['total_pedidos'] }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <!-- Pratos Populares -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-warning h-100">
                    <div class="card-header">Produto Mais Popular</div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <h5 class="card-title">{{ $data['prato_mais_vendido'] }}</h5>
                    </div>
                </div>
            </div>

            <!-- Faturamento Mensal -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-danger h-100">
                    <div class="card-header">Faturamento Mensal</div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <h5 class="card-title">R$ {{ $data['faturamento_mensal'] }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        Pedidos Finalizados por Mês
                    </div>
                    <div class="card-body">
                        <canvas id="pedidosChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        Pedidos Cancelados por Mês
                    </div>
                    <div class="card-body">
                        <canvas id="canceladosChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        Faturamento por Mês
                    </div>
                    <div class="card-body">
                        <canvas id="faturamentoChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        Produtos Populares
                    </div>
                    <div class="card-body">
                        <canvas id="produtosChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        Categorias Populares
                    </div>
                    <div class="card-body">
                        <canvas id="categoriasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    Chart.register(ChartDataLabels);

    // Gráfico de Pedidos por Mês
    var pedidosPorMes = @json($data['pedidos_por_mes']);  // Dados da procedure
    var nomeMeses = [
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", 
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
    ];

    var labelsPedidos = [];
    var pedidosData = [];
    pedidosPorMes.forEach(function(item) {
        var mes = item.mes ? nomeMeses[item.mes - 1] : 'Mês desconhecido';
        var ano = item.ano ? item.ano : 'Ano desconhecido';
        labelsPedidos.push(mes + ' ' + ano);
        pedidosData.push(item.total_pedidos);
    });

    var ctx1 = document.getElementById('pedidosChart').getContext('2d');
    var pedidosChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: labelsPedidos,
            datasets: [{
                label: 'Pedidos',
                data: pedidosData,
                backgroundColor: 'rgba(75, 192, 192)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false  // Oculta o label da legenda
                },
                datalabels: {
                    anchor: 'end',
                    align: 'right',
                    color: '#000',
                    formatter: (value) => value
                }
            }
        }
    });

    // Gráfico de Produtos Populares
    var produtosPopulares = @json($data['produtos_populares']);
    var labelsProdutos = [];
    var dataProdutos = [];
    produtosPopulares.forEach(function(produto) {
        labelsProdutos.push(produto.nome_produto);
        dataProdutos.push(produto.total_vendas);
    });

    var ctx2 = document.getElementById('produtosChart').getContext('2d');
    var produtosChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: labelsProdutos,
            datasets: [{
                label: 'Vendas',
                data: dataProdutos,
                backgroundColor: [
                    'rgba(120, 255, 235)',
                    'rgba(75, 192, 192)',
                    'rgba(255, 206, 86)',
                    'rgba(153, 102, 255)',
                    'rgba(255, 159, 64)',
                    'rgba(255, 99, 132)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    color: '#000',
                    formatter: (value) => value
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(...dataProdutos) * 1.2
                }
            }
        }
    });

    // Gráfico de Categorias Populares
    var categoriasPopulares = @json($data['categorias_populares']);
    var labelsCategorias = [];
    var dataCategorias = [];
    categoriasPopulares.forEach(function(categoria) {
        labelsCategorias.push(categoria.nome_categoria);
        dataCategorias.push(categoria.total_vendas);
    });

    var ctx4 = document.getElementById('categoriasChart').getContext('2d');
    var categoriasChart = new Chart(ctx4, {
        type: 'pie',
        data: {
            labels: labelsCategorias,
            datasets: [{
                label: 'Vendas por Categoria',
                data: dataCategorias,
                backgroundColor: [
                    'rgba(54, 162, 235)',
                    'rgba(75, 192, 192)',
                    'rgba(255, 206, 86)',
                    'rgba(153, 102, 255)',
                    'rgba(255, 159, 64)',
                    'rgba(255, 99, 132)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    anchor: 'center',
                    align: 'end',
                    color: '#000',
                    formatter: (value) => value
                }
            }
        }
    });

    // Gráfico de Pedidos Cancelados por Mês
    var canceladosPorMes = @json($data['cancelados_por_mes']);  // Dados da procedure
    var labelsCancelados = [];
    var canceladosData = [];
    canceladosPorMes.forEach(function(item) {
        var mes = item.mes ? nomeMeses[item.mes - 1] : 'Mês desconhecido';
        var ano = item.ano ? item.ano : 'Ano desconhecido';
        labelsCancelados.push(mes + ' ' + ano);
        canceladosData.push(item.total_pedidos);
    });

    var ctx3 = document.getElementById('canceladosChart').getContext('2d');
    var canceladosChart = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: labelsCancelados,
            datasets: [{
                label: 'Pedidos Cancelados',
                data: canceladosData,
                backgroundColor: 'rgba(255, 99, 132)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false  // Oculta o label da legenda
                },
                datalabels: {
                    anchor: 'end',
                    align: 'right',
                    color: '#000',
                    formatter: (value) => value
                }
            }
        }
    });

    // Faturamento Mensal
    var faturamentoMensal = @json($data['faturamento']);  
    var labelsFaturamento = [];
    var faturamentoData = [];

    faturamentoMensal.forEach(function(item) {
        var mes = item.mes ? nomeMeses[item.mes - 1] : 'Mês desconhecido';
        var ano = item.ano ? item.ano : 'Ano desconhecido';
        labelsFaturamento.push(mes + ' ' + ano);
        faturamentoData.push(item.faturamento);
    });

    var ctxFaturamento = document.getElementById('faturamentoChart').getContext('2d');
    var faturamentoChart = new Chart(ctxFaturamento, {
        type: 'line',
        data: {
            labels: labelsFaturamento,
            datasets: [{
                label: 'Faturamento (R$)',
                data: faturamentoData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 0 });
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false  // Oculta o label da legenda
                },
                datalabels: {
                    anchor: 'end',
                    align: 'right',
                    color: '#000',
                    formatter: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 0 });
                    }
                }
            }
        }
    });
</script>


@endsection