@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Détail du cahier de charge</h2>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Sujet : {{ $cahier->sujet_stage }}</h5>
                <p class="card-text"><strong>Description :</strong> {{ $cahier->description }}</p>
                <p class="card-text"><strong>Date de partage :</strong> {{ $cahier->date_partage }}</p>
                <p class="card-text"><strong>Fichier :</strong>
                    @if ($cahier->fichier_path)
                        <a href="{{ route('service.cahiers.download', ['cahier' => $cahier->id]) }}"
                            class="btn btn-outline-primary btn-sm">Télécharger le fichier</a>
                    @else
                        Aucun fichier
                    @endif
                </p>
                <a href="{{ route('service.cahiers.edit', ['cahier' => $cahier->id]) }}" class="btn btn-warning">Modifier</a>
                <a href="{{ route('service.cahiers.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
@endsection
