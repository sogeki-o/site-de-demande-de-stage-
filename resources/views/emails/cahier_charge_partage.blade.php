<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cahier des charges partage</title>
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
            margin: 12px 0;
            padding: 12px;
            background-color: #fff;
            border-radius: 6px;
        }

        .label {
            font-weight: 700;
        }

        .btn {
            display: inline-block;
            margin-top: 14px;
            background: #0d6efd;
            color: #fff !important;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Cahier des charges disponible</h1>

        <p>Bonjour {{ $demande->user?->prenom }} {{ $demande->user?->nom }},</p>
        <p>Le service a partage votre cahier des charges de stage.</p>

        <div class="meta">
            <p><span class="label">Service:</span>
                {{ $demande->service?->nom_service ?? ($demande->service?->nom ?? 'Non precise') }}</p>
            <p><span class="label">Sujet de stage:</span> {{ $cahierCharge->sujet_stage }}</p>
        </div>

        <a class="btn" href="{{ $pdfUrl }}">Telecharger le PDF du cahier des charges</a>

        <p style="margin-top: 16px;">Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
        <p><a href="{{ $pdfUrl }}">{{ $pdfUrl }}</a></p>

        <p>Cordialement,<br>L'equipe du service d'accueil</p>
    </div>
</body>

</html>
