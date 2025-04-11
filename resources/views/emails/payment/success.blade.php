<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
        .details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Confirmation de Paiement</h1>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $user->name }},</p>
            
            <p>Nous vous confirmons que votre paiement pour la formation <strong>{{ $formation->titre }}</strong> a été traité avec succès.</p>
            
            <div class="details">
                <h3>Détails du paiement:</h3>
                <p><strong>Date:</strong> {{ $date }}</p>
                <p><strong>Heure:</strong> {{ $time }}</p>
                <p><strong>Montant:</strong> {{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</p>
                <p><strong>Méthode:</strong> {{ ucfirst($payment->payment_method) }}</p>
                <p><strong>Référence:</strong> 
                    @if($payment->payment_method == 'stripe')
                        {{ $payment->stripe_payment_intent_id }}
                    @else
                        {{ $payment->paypal_order_id }}
                    @endif
                </p>
            </div>
            
            <p>Vous pouvez désormais accéder à votre formation en vous connectant à votre compte.</p>
            
            <a href="{{ config('app.url') }}/formations/{{ $formation->id }}" class="btn">Accéder à ma formation</a>
            
            <p>Si vous avez des questions concernant votre achat ou votre formation, n'hésitez pas à nous contacter.</p>
            
            <p>Merci pour votre confiance et bonne formation !</p>
            
            <p>Cordialement,<br>
            L'équipe {{ config('app.name') }}</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>