<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Participación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .content h2 {
            color: #34495e;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .content p {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .details h3 {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        .detail-value {
            flex: 1;
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }
        .highlight {
            background-color: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #27ae60;
            margin: 20px 0;
        }
        .highlight p {
            margin: 0;
            color: #2c3e50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎬 Smart Films</h1>
        </div>
        
        <div class="content">
            <h2>¡Gracias por tu participación!</h2>
            
            <p>Hola <strong>{{ $participant->name }}</strong>,</p>
            
            <p>Hemos recibido exitosamente tu formulario de participación. Recibirás una notificación por correo electrónico con los detalles de tu participación.</p>
            
            <div class="highlight">
                <p>✅ Tu participación ha sido registrada correctamente</p>
            </div>
            
            <div class="details">
                <h3>Detalles de tu participación:</h3>
                
                <div class="detail-row">
                    <div class="detail-label">Nombre:</div>
                    <div class="detail-value">{{ $participant->name }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">{{ $participant->email }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Teléfono:</div>
                    <div class="detail-value">{{ $participant->phone }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Documento:</div>
                    <div class="detail-value">{{ $participant->document_type }} - {{ $participant->document_number }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Formulario:</div>
                    <div class="detail-value">{{ $form->name }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Fecha de envío:</div>
                    <div class="detail-value">{{ $submission->submitted_at->format('d/m/Y H:i:s') }}</div>
                </div>
                
                @if($form->event)
                <div class="detail-row">
                    <div class="detail-label">Evento:</div>
                    <div class="detail-value">{{ $form->event->name }}</div>
                </div>
                @endif
            </div>
            
            <p>Si tienes alguna pregunta o necesitas más información, no dudes en contactarnos.</p>
            
            <p>¡Esperamos verte pronto en nuestros eventos!</p>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>&copy; {{ date('Y') }} Smart Films. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
