<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande de stage</title>
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

        h1 {
            margin-top: 0;
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Nouvelle demande de stage soumise</h1>
        <p>Une nouvelle demande de stage vient d'etre soumise par un demandeur.</p>

        <div class="meta">
            <p><span class="label">Demandeur:</span> {{ $demande->user?->prenom }} {{ $demande->user?->nom }}</p>
            <p><span class="label">Email:</span> {{ $demande->user?->email }}</p>
            <p><span class="label">Etablissement:</span> {{ $demande->etablissement }}</p>
            <p><span class="label">Filiere:</span> {{ $demande->filiere }}</p>
            <p><span class="label">Niveau d'etude:</span> {{ $demande->niveau_etude }}</p>
            <p><span class="label">Duree:</span> {{ $demande->duree_stage }} mois</p>
            <p><span class="label">Date debut prevue:</span>
                {{ optional($demande->date_debut_prevue)->format('d/m/Y') }}</p>
            <p><span class="label">Service souhaite:</span>
                {{ $demande->service?->nom_service ?? ($demande->service?->nom ?? 'Non precise') }}</p>
            <p><span class="label">Date de soumission:</span>
                {{ optional($demande->date_soumission)->format('d/m/Y H:i') }}</p>
        </div>

        <p>Merci de vous connecter a l'espace RH pour traiter cette demande.</p>
    </div>
</body>

</html>
