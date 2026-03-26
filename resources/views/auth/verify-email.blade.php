@extends('layouts.app')

@section('title', 'Verification email')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark text-center">
                    <h4 class="mb-0">Verification de votre email</h4>
                </div>
                <div class="card-body p-4">
                    <p>Merci pour votre inscription. Avant de continuer, veuillez verifier votre adresse email via le lien
                        que nous venons de vous envoyer.</p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-warning">Renvoyer l'email de verification</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
