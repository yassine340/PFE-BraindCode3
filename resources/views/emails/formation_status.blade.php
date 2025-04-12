<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification de statut de formation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 600;
            margin: 10px 0;
        }
        .status-validated {
            background-color: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .message {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Notification de statut de formation</h1>
        </div>
        
        <p>Bonjour {{ $formation->user->name ?? 'utilisateur' }},</p>
        
        <p>Nous vous informons que votre formation <strong>"{{ $formation->titre }}"</strong> a été :</p>
        
        @if($statut == 'Validée')
            <div class="status status-validated">
                {{ strtoupper($statut) }}
            </div>
            <div class="message">
                <p><strong>Félicitations !</strong> Votre formation est maintenant disponible sur notre plateforme.</p>
                <p>Les apprenants peuvent désormais s'y inscrire et accéder à votre contenu pédagogique.</p>
                <a href="#" class="btn">Voir ma formation</a>
            </div>
        @else
            <div class="status status-rejected">
                {{ strtoupper($statut) }}
            </div>
            <div class="message">
                <p>Nous sommes désolés de vous annoncer que votre formation n'a pas été approuvée.</p>
                <p>Si vous avez des questions ou souhaitez connaître les raisons de cette décision, notre équipe est à votre disposition.</p>
                <a href="#" class="btn">Contacter le support</a>
            </div>
        @endif
        
        <p>Vous pouvez accéder à votre espace formateur pour consulter les détails ou apporter des modifications à votre contenu.</p>
        
        <div class="footer">
            <p>Cordialement,<br>L'équipe de formation</p>
            <p>Si vous avez des questions, n'hésitez pas à nous contacter à <a href="mailto:support@formation.fr">support@formation.fr</a></p>
        </div>
    </div>
</body>
</html>