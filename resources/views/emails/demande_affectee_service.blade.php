<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de stage affectee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
        }

        .meta {
            margin: 10px 0;
            padding: 12px;
            background-color: #fff;
            border-radius: 6px;
        }

        .label {
            font-weight: 700;
        }

        .action-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Nouvelle demande de stage affectee a votre service</h1>
        <p>Bonjour,</p>
        <p>Une nouvelle demande de stage a ete acceptee par la RH et affectee a votre service
            <strong>{{ $demande->service->nom_service ?? ($demande->service->nom ?? '-') }}</strong>.
        </p>

        <div class="meta">
            <p><span class="label">Demandeur:</span> {{ $demande->user->prenom }} {{ $demande->user->nom }}</p>
            <p><span class="label">Email:</span> {{ $demande->user->email }}</p>
            <p><span class="label">Niveau d'etude:</span> {{ $demande->niveau_etude }}</p>
            <p><span class="label">Etablissement:</span> {{ $demande->etablissement }}</p>
            <p><span class="label">Filiere:</span> {{ $demande->filiere }}</p>
            <p><span class="label">Duree prevue:</span> {{ $demande->duree_stage }} mois</p>
            <p><span class="label">Date debut prevue:</span>
                {{ optional($demande->date_debut_prevue)->format('d/m/Y') }}</p>
        </div>

        <p>Accedez a votre tableau de bord du service pour consulter les details et planifier l'entretien.</p>

        <a href="{{ route('service.dashboard', ['demande' => $demande->id]) }}" class="action-button">Consulter
            la demande</a>

        <p>Cordialement,<br>Le systeme de gestion des stages</p>
    </div>
</body>

</html>
