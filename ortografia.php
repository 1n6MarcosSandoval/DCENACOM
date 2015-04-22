<?php
//Corrector Ortografico
	#PHP Source Code
  require "phpspellcheck/include.php";

  function ortografia(){
  
  $mySpell = new SpellCheckButton();
  $mySpell->InstallationPath = "phpspellcheck/";
  $mySpell->Fields = "TEXTAREAS";
  $mySpell->Language = "Espanol";
  $mySpell->UserInterfaceLanguage = "es";
  echo $mySpell->SpellImageButton();


  $mySpell = new SpellAsYouType();
  $mySpell->InstallationPath = "phpspellcheck/";
  $mySpell->Fields = "TEXTAREAS";
  $mySpell->Language = "Espanol";
  $mySpell->UserInterfaceLanguage = "es";
  echo $mySpell->Activate();
  }
  ?>