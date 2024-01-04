@extends('layouts.default')

@section('content')
    <h2 class="titre-accepted-offres">Liste <span>Clients</span></h2>
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-11 pad0">
            <form action="{{ route('clients.index') }}" method="GET" id="searchForm">
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
                            <td><span>Statut actuel </span></td>
                            <td><span>Opérations</span></td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clients as $client)
                            <tr>
                                <td>{{ $client->firstName }}</td>
                                <td>{{ $client->lastName }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->tel }}</td>
                                <td>
                                    <div>
                                        <span
                                            class="badge {{ !$client->desactiver ? 'text-bg-success' : 'text-bg-warning' }}">
                                            {{ !$client->desactiver ? 'Activé' : 'Désactivé' }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn-action btn-secondary" type="button" id="dropdownAction"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            ...
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownAction">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('desactiver.client', ['id' => $client->id]) }}">
                                                    <i class="fa-solid fa-user-slash"></i>
                                                    {{ $client->desactiver ? 'Activer' : 'Désactiver' }} client
                                                </a>
                                            </li>
                                            <li class="separate"></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td style="text-align: center" colspan="6">La liste est vide.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('parts.custom_pagination', ['paginator' => $clients])
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
