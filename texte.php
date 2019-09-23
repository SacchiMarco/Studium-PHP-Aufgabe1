<?php 
$text = array();
$text[0][0]="<h1>Webshop</h1>";
$text[0][1]="<p>Folgende Artikel können Sie bei uns bestellen</p>";
$text[0][2]="<p><a href=\"".$_SERVER['PHP_SELF'].
						"?pdf\">Artikelliste als PDF</a></p>";
$text[3][0]="<h1>Warenkorb</h1><p>Im Warenkorb liegen:</p>";
$text[4][0]="<h1>Bestellung erfolgreich</h1><p>Wir haben Ihre Bestellung erhalten.</p>";

$text[1][0]="<br /><a href=\"".$_SERVER['PHP_SELF'].
						"?wk\">Zum Warenkorb</a>";
$text[2][0]="<br /><a href=\"".$_SERVER['PHP_SELF'].
						"?order\">bestellen</a>";
$text[2][1]="<br /><a href=\"".$_SERVER['PHP_SELF'].
						"?pdfrech\">PDF- Rechnung</a>";
$text[5][0]="<br /><a href=\"".$_SERVER['PHP_SELF'].
						"\">zurück zur Artikelauswahl</a>";
?>