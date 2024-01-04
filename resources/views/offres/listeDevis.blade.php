@extends('layouts.default')

@section('content')
    <h2 class="titre-accepted-offres">Liste <span>Devis</span></h2>

    <div class="table-responsive demand">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Prix</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Flexible Date</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @forelse  ($devis as $devi)
                    <tr>
                        <td>{{ $devi->date }}</td>
                        <td>{{ $devi->prix }}</td>
                        <td>
                            <span
                            class="badge
                            @if ($devi->status === 'Accepte') text-bg-success
                            @elseif ($devi->status=== 'EnCours')
                                text-bg-warning
                            @elseif ($devi->status === 'Annule')
                                text-bg-danger @endif">
                            {{ $devi->status }}
                        </span>
                        </td>
                        <td>{{ $devi->description }}</td>
                        <td>{{ $devi->flexibleDate ? 'Oui' : 'Non' }}</td>

                        <td>
                            <a href="{{ route('liste.chat', ['deviId' => $devi->id]) }}">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </a>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Aucun devis trouvé.</td>
                    </tr>
                @endforelse
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
