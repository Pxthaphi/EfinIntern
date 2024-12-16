@echo off

cd /d C:\MAMP

start "MAMP.exe" "C:\MAMP\MAMP.exe"

cd /d C:\MAMP\htdocs\EfinIntern\template

timeout /t 2

code .

exit
