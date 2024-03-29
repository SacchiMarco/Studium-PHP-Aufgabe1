<?php
require_once("class_kunde.php");
require_once("class_artikel.php");
require_once("class_pdf.php");
class webshop extends artikel
{
	private $pdf;
	private $kunde;
	private $pdfResult = array();
	private $pdfSumme = array();
	
	public function __construct($dateiname)
	{
		parent::__construct($dateiname);
		$this->pdf = new pdf;
		$this->kunde = new kunde;
	}
	
	public function anmelden($user, $pwd)#Admin login
	{
		return $this->kunde->anmelden($user,$pwd); 
	}
	
	public function abmelden()#Admin logout
	{
		$this->kunde->abmelden();
	}
	
	public function auswahl() #zeig ausgewählte Artikel
	{
		if(count($this->korb) > 0)
		{
			print"<table>\n";
			print"<tr>\n <th>Artikel</th><th>Anzahl</th>\n </ tr>\n";
			foreach($this->korb as $key => $value)
			{
				print"<tr>\n";
				print"<td>".$this->artikelArray[$key]['name'].
						 "</td><td>".$value."</td>\n";
				print"</tr>\n";
			}
			print"</table>\n";
		}
		else
			print "Keine Artikel im Warenkorb gefunden.";
	}
	
	public function pdfliste()
	{
		$this->pdf->setSeitentitel("Liste bestellter Artikel");
		$this->pdf->AliasNbPages(); #Seitennummern zähler
		$this->pdf->AddPage();
		$this->pdf->SetFont("Times",'',12);
		$text = "Sehr geehrter Kunde, \n\nvielen Dank, dass Sie sich".
						" für unser Angebot interessieren. Folgende Artikel".
						" können Sie in unserem Online-Shop (http://".
						$_SERVER['HTTP_HOST'].") bestellen.\n";
		$this->pdf->setText($text,30);
		$this->setArtikel($this->artikelArray);
		$text = "\n\nVielen Dank für Ihr Interesse.";
		$this->pdf->setText($text,30);
		$this->pdf->Output();
	}
	
	public function setArtikel($daten)
	{
		$this->pdf->SetLeftMargin(35);
		$this->pdf->Cell(30,5,"Artikelnummer",1,0,'C');
		$this->pdf->Cell(60,5,"Artikel",1,0,'C');
		$this->pdf->Cell(30,5,"Preis",1,0,'R');
		foreach($daten as $key => $value)
		{
			$this->pdf->ln();
			$this->pdf->Cell(30,5,$key,1,0,'C');
			$this->pdf->Cell(60,5,iconv('utf-8','iso-8859-15',$value['name']),1,0);
			$this->pdf->Cell(30,5,$value['preis']." Euro",1,0,'R');
		}
	}
	
	public function pdfRechnung()
	{
		$this->setPdfRechnung();
		$this->pdf->setSeitentitel("Rechnung zu Ihrer Bestellung");
		$this->pdf->AliasNbPages();
		$this->pdf->AddPage();
		$this->pdf->SetFont("Times",'',12);
		$text = "Kunden- Nr.: ".$this->getKundennummmer().
						"\n\nIhre Bestellung:";
		$this->pdf->setText($text,15);
		$this->pdf->ln();
		$this->pdf->SetFillColor(150,150,150);
		$this->pdf->Cell(30,5,"Artikel-Nr.",1,0,'C',1);
		$this->pdf->Cell(60,5,"Artikel",1,0,'C',1);
		$this->pdf->Cell(30,5,"Menge",1,0,'C',1);
		$this->pdf->Cell(30,5,"Preis/Stk.",1,0,'C',1);
		$this->pdf->Cell(30,5,"Gesamtpreis",1,0,'C',1);
		$this->pdf->Ln(6);
		define('EURO', chr(128));
		foreach($this->pdfResult as $key => $value)
		{
			foreach($value as $key1 => $value1)
			{
				if($key1 == 'artikel')
				{
					$this->pdf->Cell(60,5,iconv('utf-8','iso-8859-15',$value1),1,0,'C');
				}
				elseif($key1 == 'gesamt')
				{
					$this->pdf->Cell(30,5,iconv('utf-8','iso-8859-15',$value1).' '.EURO,1,0,'R');
				}
				else
				{
					$this->pdf->Cell(30,5,iconv('utf-8','iso-8859-15',$value1),1,0,'C');
				}
			}
			$this->pdf->Ln(6);
		}
		$this->pdf->Ln(5);
		$this->pdf->Cell(120,5,"",0,0,'C');
		$this->pdf->Cell(30,5,"Summe:",1,0,'C');
		$this->pdf->Cell(30,5,$this->pdfSumme['summe'].' '.EURO,1,0,'R');
		$this->pdf->Ln();
		$this->pdf->Cell(120,5,"",0,0,'C');
		$this->pdf->Cell(30,5,"Mwst (7%):",1,0,'C');
		$this->pdf->Cell(30,5,$this->pdfSumme['mwst'].' '.EURO,1,0,'R');
		$this->pdf->Ln(10);
		$text = "Vielen Dank für Ihren Einkauf\n\n".
						"Ihr Online- Shop Team";
		$this->pdf->setText($text,15);
		$this->pdf->Output();
	}
	
	public function setPdfRechnung()
	{
		$summe = 0;
		foreach($this->korb as $key => $value)
		{
			$this->pdfResult[] = array('artnr'   => $key,
																 'artikel' => $this->artikelArray[$key]['name'],
																 'menge'   => $value,
																 'stkpreis'=> $this->artikelArray[$key]['preis'],
																 'gesamt'  => number_format($this->artikelArray[$key]['preis'] * $value,2));
			$summe = number_format($summe + $this->artikelArray[$key]['preis'] * $value,2);
		}
		$this->pdfSumme['summe'] = $summe;
		$this->pdfSumme['mwst'] = number_format($this->pdfSumme['summe'] * 7 /100,2);
	}
	
	public function getKundennummmer()
	{
		return $this->kunde->getKundennummmer();
	}
	
}
?>