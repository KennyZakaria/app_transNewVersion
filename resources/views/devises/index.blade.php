@extends('layouts.default')

@section('content')
<h2 class="titre-accepted-offres">Liste <span>devise</span></h2>
<form action="{{ route('devises.index') }}" method="GET" id="searchForm">
        @csrf
        <div class="row">
     

            <div class="col-md-2 main-dashboard-item">
                <label for="placeDepart">Prix:</label>
                <input type="text" class="form-control" name="prix" placeholder="Entrez le Prix"
                    value="{{ request('prix') }}">
            </div>

            <div class="col-md-3 main-dashboard-item">
                <label for="placeArrivee">Type Vehicule :</label>
                <input type="text" class="form-control" name="typeVehicule" placeholder="Entrez le type de vehicule"
                    value="{{ request('typeVehicule') }}">
            </div>
            <div class="col-md-2 main-dashboard-item">
                <label for="dateDebut">Date :</label>
                <input type="date" class="form-control" name="date" placeholder="Entrez la date"
                    value="{{ request('date') }}">
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
                    <th><span>Transporteur</span></th>
                    <th><span>Date</span></th>
                    <th><span>prix</span></th>
                    <th><span>status</span></th>
                    <th><span>Type Vehicule</span></th>

                </tr>
            </thead>
            <tbody>
                @foreach ($devisesArray['data'] as $devi)
                    <tr>
                         <td> 
                             {{$devi['transporteur']['user']['firstName']}} {{ $devi['transporteur']['user']['lastName']}}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($devi['date'])->format('d/m/Y') }}</td>
                        <td> 
                             {{$devi['prix']}}
                        </td>
                        <td> 
                            <span
                                class="badge
                                @if ($devi['status'] === 'Accepte') text-bg-success
                                @elseif ($devi['status'] === 'EnCours')
                                    text-bg-warning
                                @elseif ($devi['status'] === 'Annule')
                                    text-bg-danger
                                @elseif ($devi['status'] === 'Terminer')
                                    text-bg-info @endif">
                                    {{ $devi['status'] }}
                            </span>
                          
                        </td>
                        <td> 
                             {{$devi['typeVehicule']}}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
       @include('parts.custom_pagination', ['paginator' => $devises]) 
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchForm = document.getElementById('searchForm');
            var resetButton = document.getElementById('resetButton');
            resetButton.addEventListener('click', function() {
                document.getElementsByName('date')[0].value = '';
                document.getElementsByName('typeVehicule')[0].value = '';
                document.getElementsByName('prix')[0].value = '';


                var categorieDropdown = document.getElementById('categorie');
                categorieDropdown.selectedIndex = 0;
                searchForm.submit();
            });
        });
    </script>
@stop
