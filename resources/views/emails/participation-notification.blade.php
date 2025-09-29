<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Participación - Smart Films</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #e2e8f0;
            margin: 0;
            padding: 0;
            background: #0f1419;
            min-height: 100vh;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .main-container {
            background: #1a2332;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            overflow: hidden;
            border: 1px solid #2d3748;
        }
        .header {
            background: linear-gradient(135deg, #00ffbd 0%, #00e6a8 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        .header h1 {
            color: #000000;
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }
        .header .subtitle {
            color: #000000;
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.8;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 40px 30px;
            background: #1a2332;
        }
        .greeting {
            font-size: 24px;
            color: #e2e8f0;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .main-message {
            font-size: 18px;
            color: #a0aec0;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .success-banner {
            background: linear-gradient(135deg, #00ffbd 0%, #00e6a8 100%);
            padding: 25px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 255, 189, 0.3);
            border: 1px solid #00ffbd;
        }
        .success-banner p {
            margin: 0;
            color: #000000;
            font-weight: bold;
            font-size: 18px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .details-section {
            background: linear-gradient(145deg, #2d3748 0%, #1a2332 100%);
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            border: 1px solid #2d3748;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }
        .details-section h3 {
            color: #00ffbd;
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            border-bottom: 2px solid #00ffbd;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #2d3748;
            align-items: center;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            width: 140px;
            color: #00ffbd;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detail-value {
            flex: 1;
            color: #e2e8f0;
            font-size: 16px;
            font-weight: 500;
        }
        .footer {
            background: #0f1419;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #2d3748;
        }
        .footer p {
            margin: 5px 0;
            color: #718096;
            font-size: 14px;
        }
        .footer .brand {
            color: #00ffbd;
            font-weight: 600;
        }
        .contact-info {
            background: linear-gradient(135deg, #bb2558 0%, #a01e4a 100%);
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            text-align: center;
        }
        .contact-info p {
            margin: 0;
            color: #ffffff;
            font-weight: 500;
        }
        .decorative-line {
            height: 3px;
            background: linear-gradient(90deg, #00ffbd 0%, #00e6a8 50%, #00ffbd 100%);
            border-radius: 2px;
            margin: 20px 0;
        }
        @media (max-width: 600px) {
            .email-container {
                padding: 10px;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .detail-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="main-container">
            <div class="header">
                <h1>🎬 SMARTFILMS</h1>
                <p class="subtitle">Festival de Cine con Celular</p>
            </div>
            
            <div class="content">
                <div class="greeting">
                    ¡Hola {{ $participant->name }}! 👋
                </div>
                
                <div class="main-message">
                    <p><strong>¡Excelente noticia!</strong> Hemos recibido exitosamente tu formulario de participación en nuestro festival. Tu respuesta ha sido procesada y registrada en nuestro sistema.</p>
                    
                    {{-- <p>Recibirás una notificación por correo electrónico con los detalles de tu participación y próximos pasos del proceso.</p> --}}
                </div>
                
                <div class="success-banner">
                    <p>✅ ¡Tu participación ha sido confirmada exitosamente!</p>
                </div>
                
                <div class="decorative-line"></div>
                
                <div class="details-section">
                    <h3>📋 Detalles de tu Participación</h3>
                    
                    <div class="detail-row">
                        <div class="detail-label">Nombre Completo</div>
                        <div class="detail-value">{{ $participant->name }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Correo Electrónico</div>
                        <div class="detail-value">{{ $participant->email }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Teléfono</div>
                        <div class="detail-value">{{ $participant->phone }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Documento</div>
                        <div class="detail-value">{{ $participant->document_type }} - {{ $participant->document_number }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Formulario</div>
                        <div class="detail-value">{{ $form->name }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Fecha de Envío</div>
                        <div class="detail-value">{{ $submission->submitted_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                    
                    @if($form->event)
                    <div class="detail-row">
                        <div class="detail-label">Evento</div>
                        <div class="detail-value">{{ $form->event->name }}</div>
                    </div>
                    @endif
                </div>
                
                <div class="contact-info">
                    <p>📞 Si tienes alguna pregunta o necesitas más información, no dudes en contactarnos</p>
                </div>
                
                <div class="main-message">
                    <p><strong>¡Gracias por ser parte de Smart Films!</strong> Esperamos verte pronto en nuestros eventos y actividades.</p>
                </div>
            </div>
            
            <div class="footer">
                <p>Este es un correo automático de confirmación</p>
                <p>Por favor no respondas a este mensaje</p>
                <p class="brand">&copy; {{ date('Y') }} Smart Films - Festival de Cine con Celular</p>
                <p>Todos los derechos reservados</p>
            </div>
        </div>
    </div>
</body>
</html>
