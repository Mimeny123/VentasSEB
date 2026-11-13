#!/bin/bash

cd "$(dirname "$0")"

echo "Verificando servicio MySQL de XAMPP..."
/Applications/XAMPP/xamppfiles/bin/mysql.server start 2>/dev/null

echo "Iniciando proyecto portable en http://localhost:8000 ..."
(sleep 1 && open http://localhost:8000/index.html) &
/Applications/XAMPP/xamppfiles/bin/php -S localhost:8000