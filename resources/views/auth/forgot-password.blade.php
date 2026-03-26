@extends('layouts.app')

@section('title', 'Mot de passe oublie')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Reinitialiser le mot de passe</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Envoyer le lien de reinitialisation</button>
                    </form>
                    <hr>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">Retour a la connexion</a>
                </div>
            </div>
        </div>
    </div>
@endsection
