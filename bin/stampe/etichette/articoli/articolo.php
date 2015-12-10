<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['stampe'] > "1")
{

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
	echo "<tr><td width=\"85%\" align=\"center\" valign=\"top\">\n";
	echo "<h2>Programma di stampe etichette articolo</h2>\n";
	echo "<form action=\"eti_articolo.php\" target=\"_blank\" method=\"GET\">";


	echo "<tr><td align=\"center\" height=\"60\">Selezionare il Codice Iniziale da stampare<br>";
	echo "<select name=\"codice\">\n";
	echo "<option value=\"\"></option>";

	$elenco = tabella_articoli("elenco_select", $_codice, $_parametri);
	
	foreach ($elenco AS $dati)
	{
		echo "<option value=\"$dati[articolo]\">$dati[articolo] - $dati[descrizione]</option>\n";
	}

	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr><td align=\"center\" height=\"60\">Selezionare quante volte stampare la prima etichetta<br>";
	echo "<input type=\"number\" name=\"number\" value=\"1\"><br></td></tr>\n";

	echo "<tr><td align=\"center\" height=\"60\">Selezionare il Codice Finale da stampare<br>";
	echo "<select name=\"codice_end\">\n";
	echo "<option value=\"\"></option>";

	
	$elenco = tabella_articoli("elenco_select", $_codice, $_parametri);
	
	foreach ( $elenco AS $dati)
	{
		echo "<option value=\"$dati[articolo]\">$dati[articolo] - $dati[descrizione]</option>\n";
	}

	echo "</select>\n";
	echo "</td></tr>\n";

	//selezioniamo il tipo di etichette richiamando le etichette
	
	$etichetta = tabella_stampe_layout("elenco_etichette", $_percorso, $_tdoc);
	
	echo "<tr><td align=\"center\" height=\"60\">Selezionare il tipo di etichetta da stampare<br>";
	echo "<select name=\"tdoc\">\n";
	
	foreach ($etichetta AS $dati_eti)
	{
	echo "<option value=\"$dati_eti[tdoc]\">$dati_eti[ST_NDOC]</option>";
	}
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr><td align=\"center\" height=\"60\"><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Stampa\"></td></tr>\n";
	echo "</table></form>\n</td>\n";



	echo "</td>\n</tr>\n";


	echo "</body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>