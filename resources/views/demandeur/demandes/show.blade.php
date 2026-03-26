@extends('layouts.app')

@section('title', 'Detail de la demande')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Detail de la demande</h1>
            <a href="{{ route('demandeur.demandes.index') }}" class="btn btn-outline-secondary">Retour</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Statut:</strong>
                        <div>@include('partials.statut-badge', ['statut' => $demande->statut, 'large' => true])</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Service souhaite:</strong>
                        <div>{{ $demande->service?->nom_service ?? ($demande->service?->nom ?? '-') }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Niveau d'etude:</strong>
                        <div>{{ $demande->niveau_etude }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Etablissement:</strong>
                        <div>{{ $demande->etablissement }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Filiere:</strong>
                        <div>{{ $demande->filiere }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Duree (mois):</strong>
                        <div>{{ $demande->duree_stage }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Date debut prevue:</strong>
                        <div>{{ optional($demande->date_debut_prevue)->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Date de soumission:</strong>
                        <div>{{ optional($demande->date_soumission)->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-12">
                        <strong>CV:</strong>
                        <div>
                            @if ($demande->cv_path)
                                <a href="{{ route('demandes.cv', $demande) }}" target="_blank" rel="noopener">Voir le CV</a>
                            @else
                                <span class="text-muted">Aucun CV joint</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                        <h5 class="mb-3">Suivi de la demande</h5>
                    </div>

                    <div class="col-md-6">
                        <strong>Entretien:</strong>
                        <div>
                            @if ($demande->entretien)
                                Planifie le {{ optional($demande->entretien->date_entretien)->format('d/m/Y') }}
                                a {{ optional($demande->entretien->date_entretien)->format('H:i') }}
                                @if (!empty($demande->entretien->lieu))
                                    <br><span class="text-muted">Lieu: {{ $demande->entretien->lieu }}</span>
                                @endif
                            @else
                                <span class="text-muted">Aucun entretien planifie pour le moment.</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <strong>Cahier des charges:</strong>
                        <div>
                            @if ($demande->cahierCharge)
                                @if ($demande->cahierCharge->fichier_path)
                                    <a href="{{ route('demandeur.demandes.cahier', $demande) }}"
                                        class="btn btn-sm btn-outline-success mt-1">
                                        Telecharger le cahier
                                    </a>
                                @else
                                    <span class="text-muted">Cahier disponible sans fichier joint.</span>
                                @endif
                            @else
                                <span class="text-muted">Pas encore partage par le service.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
