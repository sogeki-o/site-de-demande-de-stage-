<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande acceptee par le service</title>
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

        .ok {
            color: #137333;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Votre demande est prise en charge</h1>

        <p>Bonjour {{ $demande->user?->prenom }} {{ $demande->user?->nom }},</p>
        <p class="ok">Le service a accepte la prise en charge de votre demande de stage.</p>

        <div class="meta">
            <p><span class="label">Reference demande:</span> #{{ $demande->id }}</p>
            <p><span class="label">Service:</span>
                {{ $demande->service?->nom_service ?? ($demande->service?->nom ?? 'Non precise') }}</p>
            <p><span class="label">Statut actuel:</span> {{ $demande->statut }}</p>
        </div>

        <p>Vous recevrez un autre email lorsque l'entretien sera planifie.</p>
        <p>Cordialement,<br>L'equipe du service d'accueil</p>
    </div>
</body>

</html>
