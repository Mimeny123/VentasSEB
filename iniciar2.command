#!/bin/bash

cd "$(dirname "$0")"

echo "Iniciando proyecto portable en http://localhost:8000 ..."
open http://localhost:8000
/Applications/XAMPP/xamppfiles/bin/php -S localhost:8000