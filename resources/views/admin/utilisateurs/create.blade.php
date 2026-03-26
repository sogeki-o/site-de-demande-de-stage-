@extends('layouts.admin')

@section('title', 'Nouveau compte utilisateur')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Nouveau compte utilisateur</h1>
            <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline-secondary">Retour</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.utilisateurs.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom') }}"
                                class="form-control @error('nom') is-invalid @enderror" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prenom</label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}"
                                class="form-control @error('prenom') is-invalid @enderror" required>
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telephone</label>
                            <input type="text" name="telephone" value="{{ old('telephone') }}"
                                class="form-control @error('telephone') is-invalid @enderror" required>
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmation mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">-- Choisir --</option>
                                @foreach (['admin', 'rh', 'service', 'demandeur'] as $role)
                                    <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                        {{ strtoupper($role) }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service (optionnel)</label>
                            <select name="service_uca_id" class="form-select @error('service_uca_id') is-invalid @enderror">
                                <option value="">-- Aucun --</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ old('service_uca_id') == $service->id ? 'selected' : '' }}>{{ $service->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_uca_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="actif" value="1" id="actif"
                                    checked>
                                <label class="form-check-label" for="actif">Compte actif</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Creer le compte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
