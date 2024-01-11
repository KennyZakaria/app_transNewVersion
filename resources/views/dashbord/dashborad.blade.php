@extends('layouts.default')

@section('content')
    <style>
        .order-card {
            color: #fff;
        }

        .bg-c-blue {
            background: linear-gradient(45deg, #4099ff, #73b4ff);
        }

        .bg-c-green {
            background: linear-gradient(45deg, #2ed8b6, #59e0c5);
        }

        .bg-c-yellow {
            background: linear-gradient(45deg, #FFB64D, #ffcb80);
        }

        .bg-c-pink {
            background: linear-gradient(45deg, #FF5370, #ff869a);
        }


        .card {
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4, 26, 55, 0.16);
            box-shadow: 0 1px 2.94px 0.06px rgba(4, 26, 55, 0.16);
            border: none;
            margin-bottom: 30px;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .card .card-block {
            padding: 25px;
        }

        .order-card i {
            font-size: 26px;
        }

        .f-left {
            float: left;
        }

        .f-right {
            float: right;
        }
    </style>
    <!-- Add this within the <head> section of your HTML file -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <h2 class="titre-accepted-offres">Dash <span>Board</span></h2>
    <div class="row">

        <div class="col-md-4 col-xl-3">
            <a href="{{ route('clients.index') }}" style="text-decoration: none;">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h2 class="text-right"><i class="fa fa-user f-left"></i><span>{{ $numClients }}</span></h2>
                        <p class="m-b-0">Client<span class="f-right"> </span></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="{{ route('transporteurs.index') }}" style="text-decoration: none;">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h2 class="text-right"><i class="fa fa-rocket f-left"></i><span>{{ $numTransporters }}</span></h2>
                        <p class="m-b-0">Transporteur<span class="f-right"> </span></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="{{ route('offres.index') }}" style="text-decoration: none;">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h2 class="text-right"><i class="fa fa-refresh f-left"></i><span>{{ $numDemande }}</span></h2>
                        <p class="m-b-0">Denamnde<span class="f-right"> </span></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h2 class="text-right"><i class="fa fa-credit-card f-left"></i><span>{{ $numDevis }}</span></h2>
                    <p class="m-b-0">Devis<span class="f-right"> </span></p>
                </div>
            </div>
        </div>
    </div>

    <h5>Top 10 des clients par nombre de demandes</h5>
    <div class="chart-container">
        <canvas id="myChart" width="200" height="20"></canvas>
        <canvas id="categoryOfferChart" width="200" height="20"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Nombre de demandes',
                    data: @json($data),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        var ctx2 = document.getElementById('categoryOfferChart').getContext('2d');
        var categoryOfferChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: {!! json_encode($categoriesWithCounts->pluck('nomFr')) !!},
                datasets: [{
                    label: 'Nombre de demandes par catégorie',
                    data: {!! json_encode(array_values($categoryOfferCounts)) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

@stop
