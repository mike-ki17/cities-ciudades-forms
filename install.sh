#!/bin/bash

echo "🚀 Instalando Formularios por Ciudad - Laravel 12"
echo "=================================================="

# Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: No se encontró el archivo artisan. Asegúrate de estar en el directorio del proyecto Laravel."
    exit 1
fi

echo "📦 Instalando dependencias PHP..."
composer install

echo "📦 Instalando dependencias Node.js..."
npm install

echo "🔧 Configurando archivo .env..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "✅ Archivo .env creado desde .env.example"
else
    echo "⚠️  El archivo .env ya existe, no se sobrescribirá"
fi

echo "🔑 Generando clave de aplicación..."
php artisan key:generate

echo "🗄️  Ejecutando migraciones..."
php artisan migrate

echo "🌱 Ejecutando seeders..."
php artisan db:seed --class=CitySeeder
php artisan db:seed --class=FormSeeder
php artisan db:seed --class=AdminUserSeeder

echo "🎨 Compilando assets..."
npm run build

echo "⚡ Optimizando aplicación..."
php artisan optimize

echo ""
echo "✅ ¡Instalación completada!"
echo ""
echo "📋 Información importante:"
echo "   - Usuario admin: admin@example.com"
echo "   - Contraseña admin: password"
echo "   - URL: http://localhost:8000"
echo ""
echo "🚀 Para iniciar el servidor:"
echo "   php artisan serve"
echo ""
echo "📚 Para más información, consulta el README.md"
