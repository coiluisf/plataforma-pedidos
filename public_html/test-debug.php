<?php
echo "PHP está funcionando corretamente!\n";
echo "Versão PHP: " . phpversion() . "\n";
echo "Session status: " . session_status() . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";

$file = __DIR__ . '/views/landing.php';
echo "Arquivo landing.php existe: " . (file_exists($file) ? "SIM" : "NÃO") . "\n";
echo "Tamanho do arquivo: " . filesize($file) . " bytes\n";
echo "Última modificação: " . date('Y-m-d H:i:s', filemtime($file)) . "\n";
?>
