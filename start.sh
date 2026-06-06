#!/bin/bash
set -e

echo "=== FidelX - Iniciando el proyecto ==="

# ── 1. Levantar MySQL si no está corriendo ──
if ! pgrep -x mysqld >/dev/null 2>&1; then
    echo "[1/4] Iniciando MySQL..."
    sudo mysqld --user=mysql --datadir=/var/lib/mysql &
    sleep 2
    # Esperar a que MySQL esté listo
    for i in {1..15}; do
        if mysqladmin ping --silent 2>/dev/null; then
            echo "      MySQL está listo."
            break
        fi
        if [ "$i" -eq 15 ]; then
            echo "ERROR: MySQL no arrancó a tiempo. Intenta iniciarlo manualmente."
            exit 1
        fi
        sleep 1
    done
else
    echo "[1/4] MySQL ya está corriendo."
fi

# ── 2. Crear base de datos si no existe ──
echo "[2/4] Creando base de datos 'fidelx' (si no existe)..."
mysql -u root --socket=/tmp/mysql.sock <<SQL
CREATE DATABASE IF NOT EXISTS fidelx CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SQL

# ── 3. Crear tabla si no existe ──
echo "[3/4] Creando tabla 'usuarios' (si no existe)..."
mysql -u root --socket=/tmp/mysql.sock fidelx <<SQL
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    creado DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL

# ── 4. Iniciar servidores PHP ──
echo "[4/4] Iniciando servidores PHP..."

PHPPID=""
PHPMYADMIN_PID=""

cleanup() {
    echo ""
    echo "Deteniendo servidores..."
    [ -n "$PHPPID" ] && kill "$PHPPID" 2>/dev/null
    [ -n "$PHPMYADMIN_PID" ] && kill "$PHPMYADMIN_PID" 2>/dev/null
    wait
    echo "Servidores detenidos."
    exit 0
}
trap cleanup SIGINT SIGTERM

# Servidor del proyecto (puerto 8000)
php -S localhost:8000 router.php &
PHPPID="$!"

# Servidor de phpMyAdmin (puerto 8080)
php -S localhost:8080 -t /opt/lampp/phpmyadmin &
PHPMYADMIN_PID="$!"

echo ""
echo "      Proyecto:    http://localhost:8000"
echo "      phpMyAdmin:  http://localhost:8080"
echo "      Presiona Ctrl+C para detener todo."
echo ""

wait
