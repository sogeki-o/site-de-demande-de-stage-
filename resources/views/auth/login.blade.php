@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Connexion</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Se connecter
                            </button>
                        </div>

                        <div class="text-end mt-2">
                            <a href="{{ route('password.request') }}" class="small text-decoration-none">Mot de passe oublie
                                ?</a>
                        </div>

                        <hr class="my-4">

                        <p class="text-center mb-0">
                            Pas encore de compte ?
                            <a href="{{ route('register') }}" class="text-decoration-none">S'inscrire</a>
                        </p>
                    </form>
                </div>
            </div>

            <!-- Informations de test -->
            <div class="alert alert-warning mt-4 small">
                <strong>Comptes de test :</strong>
                <ul class="mb-0 mt-1">
                    <li><strong>Admin :</strong> admin@gmail.com / admin123</li>
                    <li><strong>RH :</strong> rh@gmail.com / testrh123</li>
                    <li><strong>Service informatique:</strong> service.info1@uca.ma / service123</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
