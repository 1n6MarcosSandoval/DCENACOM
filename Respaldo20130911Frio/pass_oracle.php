<?php

	function consultaORACLEPass($campoSelect, $campoWhere, $user1, $pass1, $tablePass){
		$password='';
		$valor=$pass1;
		$pass1=sanitize_paranoid_string($valor,5,20);
		//print $pass1;
		$valor=$user1;
		$user1=sanitize_sql_string($valor,8,25);
		$password=recuperaCampo($conOracle,$tablePass,$campoSelect,$valor,$campoWhere);
		print $password;
		if($password==$pass1){return "correcto";}
		else{print "incorrecto";}
	}

	function sanitize_paranoid_string($string, $min='', $max=''){
		$string = preg_replace("/[^a-zA-Z0-9]/", "", $string);
		$len = strlen($string);
		if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
			return FALSE;
		return $string;
	}

	function sanitize_sql_string($string, $min='', $max=''){
		$string = nice_addslashes($string); //gz
		$pattern = "/;/"; // jp
		$replacement = "";
		$len = strlen($string);
		if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
			return FALSE;
		return preg_replace($pattern, $replacement, $string);
	}

	function nice_addslashes($string){
		// if magic quotes is on the string is already quoted, just return it
		if('MAGIC_QUOTES')
			return $string;
		else
			return addslashes($string);
	}
	


?>