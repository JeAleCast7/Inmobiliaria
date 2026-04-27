<?php
header('Content-Type: text/plain');
echo "Versión de PHP Activa: " . phpversion() . "\n";
echo "SAPI: " . php_sapi_name() . "\n";
echo "Host: " . gethostname() . "\n";
