<style>
    .gold{
        color:gold;
    }
</style>

@extends('layouts.default')

@section('content')
    <h2 class="titre-accepted-offres">Details <span>Transporteur</span></h2>
    <form class="pg-contact">
        <div class="row justify-content-center profile">
    
        <div class="col-md-10 my-4">
                    <label>Notation :</label>
                    
                    <i class="@if($rating<=0) fa-solid fa-sm fa-star
                     @elseif($rating>=1) fa-solid fa-sm fa-star gold 
                     @else fa-solid fa-sm fa-star-half-stroke gold 
                     @endif"></i>
                    <i class="@if($rating<=1) fa-solid fa-sm fa-star
                     @elseif($rating>=2) fa-solid fa-sm fa-star gold 
                     @else fa-solid fa-sm fa-star-half-stroke gold 
                     @endif"></i>
                    <i class="@if($rating<=2) fa-solid fa-sm fa-star
                     @elseif($rating>=3) fa-solid fa-sm fa-star gold 
                     @else fa-solid fa-sm fa-star-half-stroke gold 
                     @endif"></i>
                    <i class="@if($rating<=3) fa-solid fa-sm fa-star
                     @elseif($rating>=4) fa-solid fa-sm fa-star gold 
                     @else fa-solid fa-sm fa-star-half-stroke gold 
                     @endif"></i>
                    <i class="@if($rating<=4) fa-solid fa-sm fa-star
                     @elseif($rating>=5) fa-solid fa-sm fa-star gold 
                     @else fa-solid fa-sm fa-star-half-stroke gold 
                     @endif"></i>
                </div>
            <div class="col-md-5">
                <div>
                    <label>Name</label>
                    <input type="text" class="form-control" readonly value="{{ $transporteur->user->lastName }}">
                </div>
                <div>
                    <label>Phone</label>
                    <input type="text" class="form-control" readonly value="{{ $transporteur->user->tel }}">
                </div>

            </div>
            <div class="col-md-5">
                <div>
                    <label>First name</label>
                    <input type="text" class="form-control" readonly value="{{ $transporteur->user->firstName }}">
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" class="form-control" readonly value="{{ $transporteur->user->email }}">
                </div>

            </div>


        </div>
        <div class="row justify-content-center profile">
            <div class="col-md-5">
                <div>
                    <label>Type</label>
                    <input type="text" class="form-control" readonly value="{{ $transporteur->status }}">
                </div>
                <div >
                        <label>Cine</label>
                        <img id="cinFront" width="100px" src="{{ $transporteur->CinRectoURU }}" />
                        <img id="cinBack"  width="100px" src="{{ $transporteur->CinVersoURU }}" />



                </div>

            </div>
            <div class="col-md-5">
                <div>
                    <label>Ville</label>
                    <input type="text" class="form-control" readonly value="{{ $transporteur->ville->villeNameFr }}">
                </div>
                <div>
                    <label>Véhicule</label>
                    <img id="vehImg" width="100px" src="{{ $transporteur->VehicleURUS }}" />
                </div>

            </div>


        </div>
    </form>
    <script>
    var cinFront = document.getElementById('cinFront');
    var cinBack = document.getElementById('cinBack');
    var vehImg = document.getElementById('vehImg');


    cinFront.addEventListener('click', function() {
        var imgSrc = cinFront.getAttribute('src');
        window.open('{{ $transporteur->CinRectoURU }}', '_blank');
    });
    cinBack.addEventListener('click', function() {
        window.open('{{ $transporteur->CinVersoURU }}', '_blank');
    });
    vehImg.addEventListener('click', function() {
        window.open('{{ $transporteur->VehicleURUS }}', '_blank');
    });
</script>

@stop
