<?php
require_once("class_seite.php");

class sitzungsSeite extends seite
{
	protected $korb = array();
	
	public function __construct()
	{
		session_start();		
		if (!is_array($_SESSION['korb']))
		{
			$_SESSION['korb'] = array();
		}
		$this->korb = $_SESSION['korb'];
			
	}
	
	public function __destruct()
	{
		$_SESSION['korb'] = $this->korb;
	}

  public function setSessionDaten($dataArray)
  {
    $this->korb = $dataArray;
  }

  public function getSessionDaten()
  {
    return $this->korb;
  }

	
}
?>