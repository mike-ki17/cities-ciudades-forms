<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Correo</title>
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
        .success {
            background-color: #d4edda;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
        .success p {
            margin: 0;
            color: #155724;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎬 Smart Films</h1>
        </div>
        
        <div class="content">
            <h2>¡Prueba de Correo Exitosa!</h2>
            
            <p>Hola,</p>
            
            <p>Este es un correo de prueba desde el sistema Smart Films. Si recibes este mensaje, significa que la configuración de correo está funcionando correctamente.</p>
            
            <div class="success">
                <p>✅ Configuración de correo verificada</p>
            </div>
            
            <p>Los correos de notificación de participación ahora se enviarán automáticamente cuando los usuarios completen formularios.</p>
            
            <p>Fecha y hora de envío: <strong>{{ now()->format('d/m/Y H:i:s') }}</strong></p>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático de prueba.</p>
            <p>&copy; {{ date('Y') }} Smart Films. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
