@extends('layouts.default')

@section('content')
    <form action="{{ route('offres.index') }}" method="GET" id="searchForm">
        @csrf
        <div class="row">
            <div class="col-md-2 main-dashboard-item">
                <label for="dateDebut">Date Début :</label>
                <input type="date" class="form-control" name="dateDebut" placeholder="Entrez la date de début"
                    value="{{ request('dateDebut') }}">
            </div>

            <div class="col-md-2 main-dashboard-item">
                <label for="dateFin">Date Fin :</label>
                <input type="date" class="form-control" name="dateFin" placeholder="Entrez la date de fin"
                    value="{{ request('dateFin') }}">
            </div>

            <div class="col-md-2 main-dashboard-item">
                <label for="placeDepart">Lieu de Départ :</label>
                <input type="text" class="form-control" name="placeDepart" placeholder="Entrez le lieu de départ"
                    value="{{ request('placeDepart') }}">
            </div>

            <div class="col-md-2 main-dashboard-item">
                <label for="placeArrivee">Lieu d'Arrivée :</label>
                <input type="text" class="form-control" name="placeArrivee" placeholder="Entrez le lieu d'arrivée"
                    value="{{ request('placeArrivee') }}">
            </div>
            <div class="col-md-4 main-dashboard-item">
                <label for="categorie">Catégorie :</label>
                <select class="form-control" name="categorie" id="categorie">
                    <option value="" {{ !request('categorie') ? 'selected' : '' }}>Sélectionnez une catégorie</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('categorie') == $category->id ? 'selected' : '' }}>
                            {{ $category->nomFr }}
                        </option>
                    @endforeach
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
    <div class="table-responsive demand">
        <table class="table">
            <thead>
                <tr>
                    <th><span>Catégorie</span></th>
                    <th><span>Availablity</span></th>
                    <th><span>Status</span></th>
                    <th><span>Nombre Devis</span></th>
                    <th><span>Route</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($offres['data'] as $offre)
                    <tr>
                        <td>{{ $offre['categorie']['nomFr'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($offre['dateDebut'])->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($offre['dateFin'])->format('d/m/Y') }}</td>
                        <td>
                            <span
                                class="badge
                                @if ($offre['status'] === 'Valide') text-bg-success
                                @elseif ($offre['status'] === 'EnAttenteDeValidation')
                                    text-bg-warning
                                @elseif ($offre['status'] === 'Rejete')
                                    text-bg-danger
                                @elseif ($offre['status'] === 'Termine')
                                    text-bg-info @endif">
                                {{ $offre['status'] }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('liste.devis.by.deamnde', ['IdDemande' => $offre['id']]) }}">
                                {{$offre['devis_count']}}
                            </a>


                        </td>
                        <td class="td_table">
                            <div>
                                <span>
                                    {{ Illuminate\Support\Str::limit($offre['place_depart']['nomFr'], 8, '') }}
                                    <img src="../assets/img/transexpress.ma.png">
                                    {{ Illuminate\Support\Str::limit($offre['place_arrivee']['nomFr'], 8, '') }}
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
                                    <li class="nav-item dropdown">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('change.status.offre', ['id' => $offre['id'], 'status' => 'Valide']) }}">
                                            Valide
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('change.status.offre', ['id' => $offre['id'], 'status' => 'Rejete']) }}">
                                            Rejeté
                                        </a>
                                    </li>
                                    <li class="separate"></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- @include('parts.custom_pagination', ['paginator' => $offres]) --}}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchForm = document.getElementById('searchForm');
            var resetButton = document.getElementById('resetButton');
            resetButton.addEventListener('click', function() {
                document.getElementsByName('dateDebut')[0].value = '';
                document.getElementsByName('dateFin')[0].value = '';

                // Reset text inputs
                document.getElementsByName('placeDepart')[0].value = '';
                document.getElementsByName('placeArrivee')[0].value = '';

                // Reset select dropdown
                var categorieDropdown = document.getElementById('categorie');
                categorieDropdown.selectedIndex = 0;
                searchForm.submit();
            });
        });
    </script>
@stop
