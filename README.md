# Formularios por Ciudad - Laravel 12

Sistema profesional de formularios dinámicos por ciudad desarrollado en Laravel 12+ con arquitectura MVC estricta.

## 🚀 Características

- Arquitectura MVC Estricta con Services y Repositories
- Autenticación propia sin Jetstream/Livewire/Inertia
- Formularios dinámicos con campos configurables
- Multi-ciudad con fallback general
- Panel de administración completo
- Frontend moderno con TailwindCSS
- Base de datos MySQL 8 optimizada

## 📋 Requisitos

- PHP 8.3+
- Composer 2
- Node.js 18+
- MySQL 8.0+
- Laravel 12+

## 🛠️ Instalación

```bash
cd form-ciudades
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configurar `.env`:
```env
DB_CONNECTION=mysql
DB_DATABASE=cities_db
DB_USERNAME=root
DB_PASSWORD=
```

Ejecutar migraciones y seeders:
```bash
php artisan migrate
php artisan db:seed --class=CitySeeder
php artisan db:seed --class=FormSeeder
php artisan db:seed --class=AdminUserSeeder
```

Compilar assets:
```bash
npm run build
php artisan optimize
php artisan serve
```

## 🔐 Acceso

**Admin**: admin@example.com / password

## 📝 Formularios Dinámicos

Tipos de campos soportados:
- text, email, number, date
- select, textarea, checkbox

Configuración JSON con validaciones, opciones y orden.

## 🏗️ Arquitectura

- **Models**: Eloquent con relaciones
- **Services**: Lógica de negocio
- **Repositories**: Acceso a datos
- **Controllers**: Delgados, delegando a Services
- **FormRequests**: Validaciones HTTP
- **Policies**: Autorización granular

## 🚦 Rutas Principales

- `/` → Formulario general
- `/formularios/{city}` → Formulario por ciudad
- `/admin/dashboard` → Panel admin
- `/admin/forms` → Gestión formularios
- `/admin/submissions` → Respuestas

## 🛡️ Seguridad

- CSRF Protection
- Rate Limiting
- Validación server-side
- Middleware de autorización
- Protección SQL Injection
- Escape XSS automático

## 📊 Funcionalidades

### Admin
- Dashboard con estadísticas
- CRUD de formularios
- Gestión de respuestas
- Exportación CSV
- Filtros y búsqueda

### Público
- Formularios dinámicos
- Registro de usuarios
- Autenticación completa
- Responsive design

## 🚀 Despliegue

1. Configurar variables de entorno
2. `php artisan optimize`
3. `npm run build`
4. Configurar servidor web
5. Configurar SSL
6. Backup de BD

---

**Desarrollado con Laravel 12+ y TailwindCSS**