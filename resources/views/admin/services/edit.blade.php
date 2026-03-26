@extends('layouts.admin')

@section('title', 'Modifier un service')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Modifier : {{ $serviceUCA->nom }}</h1>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary"><i
                class="fas fa-arrow-left me-2"></i>Retour</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.services.update', $serviceUCA) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom du service <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom"
                            name="nom" value="{{ old('nom', $serviceUCA->nom) }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="responsable_nom" class="form-label">Nom du responsable</label>
                        <input type="text" class="form-control @error('responsable_nom') is-invalid @enderror"
                            id="responsable_nom" name="responsable_nom"
                            value="{{ old('responsable_nom', $serviceUCA->responsable_nom) }}">
                        @error('responsable_nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="responsable_email" class="form-label">Email du responsable</label>
                        <input type="email" class="form-control @error('responsable_email') is-invalid @enderror"
                            id="responsable_email" name="responsable_email"
                            value="{{ old('responsable_email', $serviceUCA->responsable_email) }}">
                        @error('responsable_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3">{{ old('description', $serviceUCA->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="actif" name="actif" value="1"
                                {{ old('actif', $serviceUCA->actif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="actif">Service actif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>

            <hr class="my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="mb-1 text-danger">Suppression du service</h5>
                    <p class="mb-0 text-muted">
                        Cette action est definitive.
                        {{ $serviceUCA->users_count ?? 0 }} utilisateur(s) seront dissocies du service et
                        {{ $serviceUCA->demandes_count ?? 0 }} demande(s) associee(s) seront supprimee(s).
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.services.destroy', $serviceUCA) }}"
                    onsubmit="return confirm('Voulez-vous vraiment supprimer ce service ? Cette action supprimera aussi les demandes associees.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer le service
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
