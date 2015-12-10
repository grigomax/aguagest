<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);
echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
	<tr>
	<td align=\"center\" valign=\"top\">
	<span class=\"intestazione\"><br><b>Visualizzazione listino aggiornato</b><br></span><br></td></tr>";

//Cambio le variabili e le faccio vedere
$_listino = $_POST['listino'];
$_codini = $_POST['codini'];
$_codfin = $_POST['codfin'];
$_percento = $_POST['percento'];

// miconnetto al database
//prelevo gli articoli interessati dal database

$query = sprintf("select * from listini where rigo=\"%s\" and codarticolo >= \"%s\" and codarticolo <= \"%s\" order by codarticolo", $_listino, $_codini, $_codfin);

if ($res = mysql_query($query, $conn))
{
	// La query ?stata eseguita con successo...
	// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
	if (mysql_num_rows($res))
	{
		// Tutto procede a meraviglia...
		//con un ciclo di while procedo ad aggiornare tutti gli articoli

		echo "<span class=\"testo_blu\">";
		while ($dati = mysql_fetch_array($res))
		{

			//micreo le variabili
			$_articolo = $dati['codarticolo'];
			$_prezzov = $dati['listino'];

			//eseguo l'aumento del prezzo

			$_prezzon = (($_prezzov * $_percento) / 100) + $_prezzov;

			// eseguo l'aggiornamento della tabella listini

			$query = sprintf("update listini set listino=\"%s\" where codarticolo=\"%s\" and rigo=\"%s\"", $_prezzon, $_articolo, $_listino);

			if (mysql_query($query, $conn) != 1)
			{
				echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
				return -1;
			}

			echo "<tr><td>Aggiornamento prezzo per articolo \n\"$_articolo\"\n </td><td> Prezzo vecchio = \n\"$_prezzov\"\n</td><td>Prezzo nuovo = \n\"$_prezzon\"\n</td></tr>";
		}//fine while
	}//fine if res
}//fine if funzione
echo "</table></body></html>";
?>