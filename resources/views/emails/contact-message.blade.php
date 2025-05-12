<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
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
            border-bottom: 2px solid #4a86e8;
            margin-bottom: 25px;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #4a86e8;
            margin: 0;
            font-size: 24px;
        }
        .info-row {
            margin-bottom: 15px;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            width: 100px;
            color: #555;
        }
        .info-value {
            flex: 1;
        }
        .message-section {
            margin-top: 25px;
            background-color: #f5f7fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #4a86e8;
        }
        .message-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 10px;
        }
        .message-content {
            white-space: pre-line;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        @media only screen and (max-width: 500px) {
            body {
                padding: 10px;
            }
            .email-container {
                padding: 15px;
            }
            .info-row {
                flex-direction: column;
            }
            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Nouvelle soumission de formulaire de contact</h1>
        </div>
        
        <div class="info-row">
            <div class="info-label">Nom:</div>
            <div class="info-value">{{ $contact->name }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Prénom:</div>
            <div class="info-value">{{ $contact->first_name }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $contact->email }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Numéro de téléphone:</div>
            <div class="info-value">{{ $contact->phone ?? 'Not provided' }}</div>
        </div>
        
        <div class="message-section">
            <div class="message-label">Message:</div>
            <div class="message-content">{{ $contact->message }}</div>
        </div>
        
        <div class="footer">
            Horodatage: {{ date('Y-m-d H:i:s') }}
        </div>
    </div>
</body>
</html>