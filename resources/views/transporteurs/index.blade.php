@extends('layouts.default')

@section('content')
    <h2 class="titre-accepted-offres">Liste <span>Transporteurs</span></h2>


    <div class="row justify-content-center">
        <form action="{{ route('transporteurs.index') }}" method="GET" id="searchForm">
            @csrf
            <div class="row">
                <div class="col-md-3 main-dashboard-item">
                    <label for="firstName">Prénom :</label>
                    <input type="text" class="form-control" name="firstName" placeholder="Entrez le prénom" value="{{ request('firstName') }}">
                </div>
                <div class="col-md-3 main-dashboard-item">
                    <label for="lastName">Nom de famille :</label>
                    <input type="text" class="form-control" name="lastName" placeholder="Entrez le nom de famille" value="{{ request('lastName') }}">
                </div>
                <div class="col-md-3 main-dashboard-item">
                    <label for="email">E-mail :</label>
                    <input type="text" class="form-control" name="email" placeholder="Entrez l'e-mail" value="{{ request('email') }}">
                </div>
                <div class="col-md-3 main-dashboard-item">
                    <label for="status">Statut actuel :</label>
                    <select class="form-control" name="status">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Sélectionner</option>
                        <option value="false" {{ request('status') == 'false' ? 'selected' : '' }}>Activer</option>
                        <option value="true" {{ request('status') == 'true' ? 'selected' : '' }}>Désactiver</option>
                    </select>
                </div>
            </div>
            <div class="row justify-content-end py-3">
                <div class="col-md-5 col-12 btn-pq">
                    <button class="btn btn-primary a3 reset" id="resetButton" type="button">Annuler</button>
                    <button class="btn btn-primary a3" id="submitButton" type="submit">Rechercher</button>
                </div>
            </div>
        </form>
        <div class="col-md-11 col-lg-11 pad0">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive demand">
                <table class="table">
                    <thead>
                        <tr>
                            <td><span>Prénom</span></td>
                            <td><span>Nom</span></td>
                            <td><span>Email</span></td>
                            <td><span>Téléphone</span></td>
                            <td><span>Devi</span></td>
                            <td>Approuver</td>
                            <td><span>Statut actuel </span></td>
                            <td><span>Details</span></td>
                            <td><span>Opérations</span></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transporteurs as $transporteur)
                            <tr>
                                <td>{{ $transporteur->user->firstName }}</td>
                                <td>{{ $transporteur->user->lastName }}</td>
                                <td>{{ $transporteur->user->email }}</td>
                                <td>{{ $transporteur->user->tel }}</td>
                                <td>{{ $transporteur->devis_count }}</td>
                                <td>
                                    <div>
                                        <span class="badge {{ $transporteur->approuver ? 'text-bg-success' : 'text-bg-warning' }}">
                                            {{ $transporteur->approuver ? 'Approuvé' : 'Non approuvé' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span
                                            class="badge {{ !$transporteur->desactiver ? 'text-bg-success' : 'text-bg-warning' }}">
                                            {{ !$transporteur->desactiver ? 'Activé' : 'Désactivé' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('transporteur.details', ['id' => $transporteur->id]) }}">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>

                                    </a>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn-action btn-secondary" type="button" id="dropdownAction"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            ...
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownAction">
                                            <!-- <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('desactiver.client', ['id' => $transporteur->id]) }}">
                                                    <i class="fa-solid fa-user-slash"></i>
                                                    {{ $transporteur->desactiver ? 'Activer' : 'Désactiver' }} transporteur
                                                </a>
                                            </li> 
                                            <li class="separate"></li>-->
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('transporteurs.toggleApprouver', ['id' => $transporteur->id]) }}">
                                                    <i class="fa-solid fa-check"></i>
                                                    {{ $transporteur->approuver ? 'Désapprouver' : 'Approuver' }} transporteur
                                                </a>
                                            </li>
                                        </ul>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @include('parts.custom_pagination', ['paginator' => $transporteurs])
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchForm = document.getElementById('searchForm');
            var resetButton = document.getElementById('resetButton');
            resetButton.addEventListener('click', function() {
                var statusDropdown = document.getElementsByName('status')[0];
                statusDropdown.selectedIndex = 0;
                document.getElementsByName('firstName')[0].value = '';
                document.getElementsByName('lastName')[0].value = '';
                document.getElementsByName('email')[0].value = '';
                searchForm.submit();
            });
        });
    </script>
@stop
