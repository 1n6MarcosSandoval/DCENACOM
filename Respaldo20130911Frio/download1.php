<?php
session_start();

if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}


$path = $_SERVER['DOCUMENT_ROOT']."\cenacom\\";
$fullPath = $path.$_GET['download_file'];

if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);
    switch ($ext) {
        case "docx":
        header("Content-type: application/docx"); // diferentes extensiones
        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // usar 'attachment' para forzar descarga
        break;
        default;
        header("Content-type: application/octet-stream");
        header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
    }
    header("Content-length: $fsize");
    header("Cache-control: private"); //usar para abrir archivos directamente
    while(!feof($fd)) {
        $buffer = fread($fd, 2048);
        echo $buffer;
    }
}
fclose ($fd);
exit;

?>