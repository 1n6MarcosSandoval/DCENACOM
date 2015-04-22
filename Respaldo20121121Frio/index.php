<?php
include 'functions.php';
include 'vars.php';

session_cache_limiter('private');
$cache_limiter = session_cache_limiter();
session_start();

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);

 if((@$_POST["amigo"])&&(@$_POST["claveAmigo"])){
   //$canservero=consultaSQLPass("pass", "usuario", $_POST["amigo"], $_POST["claveAmigo"], "usuarios_dir");
   if($_POST["amigo"]=="amigo" && $_POST["claveAmigo"]=="clave")
   		$canservero="correcto";
   else
   	$canservero="Ooooops!";
   	
	if($canservero!="correcto"){
		$_SESSION['tmptxt']="Tu no pasaraaaaaaaas!";
		$_SESSION['login'] = "no";
		
	}else{
	 	$_SESSION['login'] = "si";
     	$_SESSION['nombre'] = $_POST["amigo"];
	 	header("Location:principal.php");
	}

 }elseif(@$_SESSION['login'] == "si"){
	header("Location:principal.php");
	exit();
	}

?>
<head><title>Reportes relevantes</title>
		<link rel="stylesheet" href="images/style.css" type="text/css" />
</head>
<body>
<div align="center">
	<br/>
	&nbsp;
	<br/>
	&nbsp;
	<br/>
	&nbsp;
	<br/>
	&nbsp;
	<br/>
	&nbsp;
<img src="images/logos.jpg">		
<h1>Captura de Reportes Relevantes</h1>
<div>



<form name="datosIngreso" method="post" action="index.php">
  <p>Usuario: 
    <input name="amigo" type="text" size="25" maxlength="25">
  </p>
  <p>Passkey: 
    <input name="claveAmigo" type="password" size="25" maxlength="25">
  </p>
  <p> 
    <input name="Submit" type="submit" id="Submit" value="Acceder">
  </p>
  <p>&nbsp; </p>
</form>



</div>
</p>
</div>
</div>
</body>
</html>