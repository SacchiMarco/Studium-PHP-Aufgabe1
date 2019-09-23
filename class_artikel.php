<?php 
require_once("class_sitzungsSeite.php");

class artikel extends sitzungsSeite
{
	protected $artikelArray = array();
	
	public function __construct($dateiname)
	{
		parent::__construct();
		$daten = simplexml_load_file($dateiname);
		foreach($daten as $key => $value)
		{
			$artNr = intval($value->attributes());
			foreach($value as $key1 => $value1)
			{
				$this->artikelArray[$artNr][$key1] = (string)$value1; #$this->artikelArray['1010']['name'] = "Herr der Ringe";
			}
		}
	}
	public function anzeigen()
	{
		$anzahl = 1;
		print "<table>\n";
		print "<tr>\n <th>Artikel</th><th>Preis</th>\n </tr>\n";
		foreach($this->artikelArray as $key => $value)
		{
			if($anzahl % 2)
			{
				$anzahl++;
				$farbe = "#CCCCCC";
			}
			else
			{
				$anzahl++;
				$farbe = "#FFFFFF";
			}
			
			print "<tr bgcolor=".$farbe.">\n";
			foreach($value as $subKey => $subValue)
			{
				if($subKey == 'name')
				{
					print "<td>".$subValue."</td>";
				}
				else
				{
					print "<td>".$subValue." &euro;</td>";
					print"<td><a href=\"".$_SERVER['PHP_SELF']."?id=".$key."\">In den Warenkorb</a></td>\n";
				}
			}
			print "</tr>\n";
		}
		print "</table>\n";
	}
	
	public function waehlen($artikelnummer)
	{
		$this->korb[$artikelnummer]++;
		
	}
	
	public function bestellen($kunde ,$file = "bestellung.xml")
	{
		if(!is_int($kunde))
		{
			throw new Exception('Keine Kundennummer');
		}
		else
		{
			if(count($this->korb) > 0)
			{
				$xml = new SimpleXMLElement("<bestellung></bestellung>");
				$xml->addAttribute("kunde", $kunde);
				foreach($this->korb as $key => $value)
				{
					$art = $xml->addChild("artikel");
					$art->addAttribute("nummer", $key);
					$art->addAttribute("anzahl", $value);
				}
				$error = $xml->asXML($kunde.$file);
				if($error)
				{
					$this->korb = array();
				}
				return $error;
			}
			return false;
		}
	}
}
?>