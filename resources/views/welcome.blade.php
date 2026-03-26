@extends('layouts.app')

@section('title', 'Accueil - Gestion des Stages UCA')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4">Bienvenue sur la plateforme de gestion des stages</h1>
                <p class="lead">Université Cadi Ayyad</p>

                @guest
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-3">Se connecter</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">S'inscrire</a>
                    </div>
                @endguest

                @auth
                    <div class="mt-4">
                        <p>Vous êtes connecté en tant que {{ Auth::user()->role }}.</p>
                        <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="btn btn-success btn-lg">Accéder au
                            dashboard</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection
