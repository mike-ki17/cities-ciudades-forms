# Configuración de Mailgun para Envío de Correos

## Variables de Entorno Requeridas

Agrega las siguientes variables a tu archivo `.env`:

```env
# Configuración de Mail
MAIL_MAILER=mailgun
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="Smart Films"

# Configuración de Mailgun
MAILGUN_DOMAIN=tu-dominio.mailgun.org
MAILGUN_SECRET=tu-clave-secreta-de-mailgun
MAILGUN_ENDPOINT=api.mailgun.org
```

## Pasos para Configurar Mailgun

1. **Crear cuenta en Mailgun**:
   - Ve a [mailgun.com](https://www.mailgun.com)
   - Crea una cuenta gratuita
   - Verifica tu dominio

2. **Obtener credenciales**:
   - En el dashboard de Mailgun, ve a "API Keys"
   - Copia tu "Private API key" (MAILGUN_SECRET)
   - Tu dominio será algo como "mg.tudominio.com" (MAILGUN_DOMAIN)

3. **Configurar el dominio**:
   - En Mailgun, ve a "Domains"
   - Agrega tu dominio o usa el dominio de prueba
   - Configura los registros DNS según las instrucciones

4. **Probar la configuración**:
   - Usa el dominio de prueba de Mailgun para desarrollo
   - Para producción, configura tu propio dominio

## Funcionalidad Implementada

- ✅ Envío automático de correo al completar formulario
- ✅ Template HTML responsivo y profesional
- ✅ Manejo de errores sin afectar el envío del formulario
- ✅ Logging de envíos exitosos y errores
- ✅ Cola de correos para mejor rendimiento

## Template de Correo

El correo incluye:
- Confirmación de participación
- Detalles del participante
- Información del formulario
- Fecha y hora de envío
- Diseño profesional y responsivo

## Comandos Útiles

```bash
# Limpiar cache de configuración
php artisan config:clear

# Probar envío de correo
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });

# Ver logs de correo
tail -f storage/logs/laravel.log
```
