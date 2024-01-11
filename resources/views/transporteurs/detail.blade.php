@extends('layouts.default')

@section('content')
    <h2 class="titre-accepted-offres">Details <span>Transporteur</span></h2>
    <form class="pg-contact">
        <div class="row justify-content-center profile">
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
                <div>
                        <label>Cine</label>
                        <img  width="100px" src="{{ $transporteur->CinRectoURU }}" />
                        <img   width="100px" src="{{ $transporteur->CinVersoURU }}" />



                </div>

            </div>
            <div class="col-md-5">
                <div>
                    <label>Ville</label>
                    <input type="text" class="form-control" readonly value="{{ $transporteur->ville->villeNameFr }}">
                </div>
                <div>
                    <label>Véhicule</label>
                    <img width="100px" src="{{ $transporteur->VehicleURUS }}" />
                </div>

            </div>


        </div>
    </form>


@stop
