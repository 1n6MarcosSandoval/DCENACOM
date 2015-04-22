<?php
require_once 'C:\php\phpdocx\classes\CreateDocx.inc';

$docx = new CreateDocx();

$CENAPRED = array(
    'name' => 'cenapred.jpg',
    'scaling' => 100,
    'spacingTop' => 100,
    'spacingBottom' => 0,
    'spacingLeft' => 100,
    'spacingRight' => 0,
    'textWrap' => 1,
    'border' => 1,
    'borderDiscontinuous' => 1
);


$docx->addImage($CENAPRED);


$nombrep=$_POST["namep"];
$docx->addText('Nombre: '.$nombrep);
$tipo=$_POST["type"];
$docx->addText('Tipo: '.$tipo);
$dia=$_POST["day"];
$docx->addText('Dia: '.$dia);
$nom = $_POST["name"];
$ruta="C:\inetpub\wwwroot\cenacom\\";
$docx->createDocx($ruta.$nom);


//echo hola;




?>


<a href="download1.php?download_file=<?= $nom ?>.docx" target="_blank">Click para descargar</a>

