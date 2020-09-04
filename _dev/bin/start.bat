@ECHO OFF
SET BIN_TARGET=%~dp0/Boot.php
php "%BIN_TARGET%" %*
SET BIN_TARGET=%~dp0/start.bat
del "%BIN_TARGET%" %*