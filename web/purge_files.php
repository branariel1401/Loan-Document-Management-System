<?php
$data = json_decode(file_get_contents('php://input'), true);
$file = basename($data['file']);
$filePath = "/var/www/html/view/" . $file;

if (file_exists($filePath)) {
    unlink($filePath);
}
?>