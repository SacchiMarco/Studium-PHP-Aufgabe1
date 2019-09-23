<?php 
require_once("class_webshop.php");
require_once("texte.php");
require_once("funktionen.php");
#Upload
$result = false;
$login = false;
if(isset($_FILES['xml']))
{
	$result = xmlUpload("xml");
}

$a = new webshop("artikeldaten.xml");
if(isset($_REQUEST['pdf']))
			$a->pdfliste();
if(isset($_REQUEST['pdfrech'])) 
	$a->pdfRechnung();
if(isset($_REQUEST['user']))
{
	$login = $a->anmelden($_REQUEST['user'],$_REQUEST['pwd']);
}
$a->setTitel("Webshop");

#Login & Logout
if(isset($_REQUEST['abmelden']))
{
	$a->abmelden();
}

if($a->getKundennummmer() == 1)
{
	echo $a->uploadform();
}
else
{
	echo $a->loginform();
	if($login)
		echo $login;
}

#ausgabe von Upload
if($result)
{
	echo $result;
}

#Seite
$a->kopf();
	if(isset($_REQUEST['wk']))
	{
		$a->inhalt($text[3]);
		$a->auswahl();
		$a->inhalt($text[2]);
		$a->inhalt($text[5]);
	}
	elseif(isset($_REQUEST['order']))
	{
		$a->inhalt($text[4]);
		$a->bestellen($a->getKundennummmer());
		$a->inhalt($text[5]);
	}
	else
	{
		$a->inhalt($text[0]);	
		if(!empty($_REQUEST['id']))
			$a->waehlen($_REQUEST['id']);


		$a->anzeigen();
		$a->inhalt($text[1]);
	
	}
	$a->fuss();
?>