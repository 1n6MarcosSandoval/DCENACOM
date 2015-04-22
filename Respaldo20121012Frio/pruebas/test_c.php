<?php
//require_once 'C:\php\phpdocx\classes\CreateDocx.inc';
function word($text, $text2, $text3, $url, $nom){
	require_once 'C:\php\phpdocx\classes\CreateDocx.inc';
	$docx = new CreateDocx();
	
	$IMAGEN = array(
		'name' => $url,
		'scaling' => 100,
		'spacingTop' => 100,
		'spacingBottom' => 0,
		'spacingLeft' => 100,
		'spacingRight' => 0,
		'textWrap' => 1,
		'border' => 1,
		'borderDiscontinuous' => 1
	);
	
	$docx->addImage($IMAGEN);
	
	$docx->addText($text);
	$docx->addText($text2);
	$docx->addText($text3);
	
	
	$ruta="C:\inetpub\wwwroot\cenacom\\";
	$docx->createDocx($ruta.$nom);
}
echo 'hola';
word('Campo 1: algunos','Campo 2: ninguno','Campo 3: todos','cenapred.jpg','archivo');
?>
