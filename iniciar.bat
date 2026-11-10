@echo off
cd /d "%~dp0"

echo Iniciando MySQL en segundo plano...
start "" /B "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini"

echo Iniciando Pagina en http://localhost:8000
start http://localhost:8000/index.html
C:\xampp\php\php.exe -S localhost:8000
pause