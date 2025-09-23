#!/bin/bash

echo "========================================"
echo "   SISTEMA DE FORMULARIOS - TEST SUITE"
echo "========================================"
echo

echo "[INFO] Iniciando suite completa de tests..."
echo

echo "[1/8] Ejecutando tests de Eventos..."
php artisan test --filter=EventManagementTest --verbose
if [ $? -ne 0 ]; then
    echo "[ERROR] Tests de eventos fallaron"
    exit 1
fi
echo "[OK] Tests de eventos completados"
echo

echo "[2/8] Ejecutando tests de Formularios..."
php artisan test --filter=FormManagementTest --verbose
if [ $? -ne 0 ]; then
    echo "[ERROR] Tests de formularios fallaron"
    exit 1
fi
echo "[OK] Tests de formularios completados"
echo

echo "[3/8] Ejecutando tests de Campos..."
php artisan test --filter=FieldManagementTest --verbose
if [ $? -ne 0 ]; then
    echo "[ERROR] Tests de campos fallaron"
    exit 1
fi
echo "[OK] Tests de campos completados"
echo

echo "[4/8] Ejecutando tests de Envíos..."
php artisan test --filter=FormSubmissionTest --verbose
if [ $? -ne 0 ]; then
    echo "[ERROR] Tests de envíos fallaron"
    exit 1
fi
echo "[OK] Tests de envíos completados"
echo

echo "[5/8] Ejecutando tests de Integración..."
php artisan test --filter=EndToEndIntegrationTest --verbose
if [ $? -ne 0 ]; then
    echo "[ERROR] Tests de integración fallaron"
    exit 1
fi
echo "[OK] Tests de integración completados"
echo

echo "[6/8] Ejecutando tests de Rendimiento..."
php artisan test --filter=PerformanceTest --verbose
if [ $? -ne 0 ]; then
    echo "[ERROR] Tests de rendimiento fallaron"
    exit 1
fi
echo "[OK] Tests de rendimiento completados"
echo

echo "[7/8] Ejecutando tests de Modelos..."
php artisan test --filter=ModelTest --verbose
if [ $? -ne 0 ]; then
    echo "[ERROR] Tests de modelos fallaron"
    exit 1
fi
echo "[OK] Tests de modelos completados"
echo

echo "[8/8] Ejecutando tests existentes..."
php artisan test --filter="AuthenticationTest|FormRelationalMigrationTest" --verbose
if [ $? -ne 0 ]; then
    echo "[ERROR] Tests existentes fallaron"
    exit 1
fi
echo "[OK] Tests existentes completados"
echo

echo "========================================"
echo "   TODOS LOS TESTS COMPLETADOS EXITOSAMENTE"
echo "========================================"
echo
echo "[INFO] Suite de tests ejecutada completamente"
echo "[INFO] Todos los tests pasaron correctamente"
echo
echo "Para ejecutar tests individuales:"
echo "  php artisan test --filter=NombreDelTest"
echo
echo "Para ejecutar con cobertura:"
echo "  php artisan test --coverage"
echo
echo "Para ejecutar en paralelo:"
echo "  php artisan test --parallel"
echo
