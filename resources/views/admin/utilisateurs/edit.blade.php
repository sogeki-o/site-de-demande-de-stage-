@extends('layouts.admin')

@section('title', 'Modifier un compte utilisateur')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Modifier le compte</h1>
            <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline-secondary">Retour</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.utilisateurs.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom', $user->nom) }}"
                                class="form-control @error('nom') is-invalid @enderror" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prenom</label>
                            <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}"
                                class="form-control @error('prenom') is-invalid @enderror" required>
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" id="telephone-field">
                            <label class="form-label">Telephone</label>
                            <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                                class="form-control @error('telephone') is-invalid @enderror"
                                {{ old('role', $user->role) === 'service' ? '' : 'required' }}>
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nouveau mot de passe (optionnel)</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmation nouveau mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror"
                                required>
                                @foreach (['admin', 'rh', 'service', 'demandeur'] as $role)
                                    <option value="{{ $role }}"
                                        {{ old('role', $user->role) === $role ? 'selected' : '' }}>{{ strtoupper($role) }}
                                    </option>
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
                                        {{ old('service_uca_id', $user->service_uca_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->nom }}</option>
                                @endforeach
                            </select>
                            @error('service_uca_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="actif" value="1" id="actif"
                                    {{ old('actif', $user->actif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">Compte actif</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const roleSelect = document.getElementById('role');
            const telephoneField = document.getElementById('telephone-field');
            const telephoneInput = telephoneField ? telephoneField.querySelector('input[name="telephone"]') : null;

            function toggleTelephoneField() {
                if (!roleSelect || !telephoneField || !telephoneInput) {
                    return;
                }

                const isService = roleSelect.value === 'service';
                telephoneField.style.display = isService ? 'none' : '';
                telephoneInput.required = !isService;

                if (isService) {
                    telephoneInput.value = '';
                }
            }

            toggleTelephoneField();
            roleSelect && roleSelect.addEventListener('change', toggleTelephoneField);
        })();
    </script>
@endsection
