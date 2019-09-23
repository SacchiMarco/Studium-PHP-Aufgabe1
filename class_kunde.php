<?php 
class kunde
{
	private $KndNr;
	private $user = "Admin";
	private $pwd = "Hallo";
	
	public function __construct()
	{
		if(!empty($_SESSION['id']))
		{
			$this->KndNr = $_SESSION['id'];
		}
		else
		{
			$this->KndNr = time();
		}
	}
	
	public function __destruct()
	{
		$_SESSION['id'] = $this->KndNr;
	}
	
	public function getKundennummmer()
	{
		return $this->KndNr;
	}
	
	#1. Aufgabe
	#Admin login
	public function anmelden($user = "Admin",$pwd = "Hallo")
	{
		if($user == $this->user && $pwd == $this->pwd)
		{
			$this->KndNr = 1;
		}
		else
		{
			return "Benutzername oder Passwort nicht korrekt";
		}
	}
	#Admin logout
	public function abmelden()
	{
		$this->KndNr = '';
		header("Location: webshop.php");
		unset($_SESSION['id']);
		
	}
}
?>