<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="noindex">
    <title>TransExpress.ma - Dashboard</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="../assets/img/transexpress-logo-white.png" />
    <!-- Font Awesome icons (free version)-->
    <script src="../js/fontawesome.com_releases_v6.3.0_js_all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="{{ asset('/css/styles.css') }}">
    <link href="../css/dashboard.css" rel="stylesheet" />
    <style>
        .scrollable-card-body {
            max-height: 70vh; 
            overflow-y: auto; 
            }

    </style>
</head>

<body>
@php
    
    $currentRoute = Route::currentRouteName();
@endphp
    <header class="navbar sticky-top flex-md-nowrap p-0 shadow" data-bs-theme="dark">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="{{ route('dashboard.dashboard') }}"><img
                src="../assets/img/transexpress-logo.svg"></a>
        <div class="dropdown">
            <button class=" btn-secondary dropdown-toggle" type="button" id="dropouz" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa fa-bell" aria-hidden="true"></i>
                <span
                    class="position-absolute top-13 start-50 translate-middle p-35 bg-danger border border-light rounded-circle">
                </span>
            </button>
            <ul class="dropdown-menu scrollable-card-body" aria-labelledby="dropouz">
            @foreach($notifications as $notification)
                <li>
                    {{ $notification->notificationContent }}
                    <a href="{{ route('change.status.notification', ['id' => $notification->id, 'status' => 1]) }}" style="float: right;color:red;"><i class="fa-solid fa-trash-can"></i></a>
              </li>

                <li class="separate"></li>

                @endforeach
            </ul>
        </div>
        <ul class="navbar-nav flex-row ">
            <li class="nav-item text-nowrap">
                <button class="px-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
            </li>
        </ul>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="sidebar border-rightt col-md-3 col-lg-2 p-0 bg-body-tertiary">
                <div class="offcanvas-lg offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu"
                    aria-labelledby="sidebarMenuLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="sidebarMenuLabel"><img src="../assets/img/transexpress-logo.svg"
                                width="100%"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            data-bs-target="#sidebarMenu" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 {{ $currentRoute == 'dashboard.dashboard' ? 'active' : '' }}" aria-current="page"
                                href="{{ route('dashboard.dashboard') }}">
                                    <i class="fa fa-home" aria-hidden="true"></i>
                                    Dashboard
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 {{ $currentRoute == 'clients.index' ? 'active' : '' }}" href="{{ route('clients.index') }}">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    Client
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 {{ $currentRoute == 'transporteurs.index' ? 'active' : '' }}"
                                    href="{{ route('transporteurs.index') }}">
                                    <img src="{{ asset('assets/img/driver.png') }}" alt="Driver Icon" width="20" height="20">
                                    Transporteur
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 {{ $currentRoute == 'offres.index' ? 'active' : '' }}" href="{{ route('offres.index') }}">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    Demande
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 {{ $currentRoute == 'devises.index' ? 'active' : '' }}" href="{{ route('devises.index') }}">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    Devis
                                </a>
                            </li>
                        </ul>

                        <hr class="my-3">

                        <ul class="nav flex-column mb-auto">

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="{{ route('auth.logout') }}">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    Sign out
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-dashboard">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script>
        var element = document.getElementsByClassName("td_table");

        for (var i = element.length - 1; i >= 0; i--) {
            element[i].onclick = function() {
                document.location = 'details.html';
            }
        }
        
        
    </script>
</body>

</html>
