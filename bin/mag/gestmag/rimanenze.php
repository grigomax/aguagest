<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso ."../setting/vars.php";
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['magazzino'] > "1")
{
    ?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
    	<td width="85%" align="center" valign="top">
    	    <span class="intestazione"><b>Stampa Rimanenze di magazzino</b><br><br>
    		<red>ATTENZIONE il programma impieghera' un bel po' di tempo per fare tutti i conti.</span><br>
    	    Questo dipendera' dal tipo di computer oppure la quantita' di dati da elaborare.</td></tr>
	<?php

	printf("<br><br><form action=\"rimanenze2.php\" target=\"sotto\" method=\"POST\">");
	$_anno = date("Y");
	$_annov = $_anno - 1;

	echo "<tr><td align=center><br>";
	echo "<select name=\"anno\">\n";

	printf("<option value=\"%s\">Anno = %s</option>\n", $_anno, $_anno);
	printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 1, $_anno - 1);
	printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 2, $_anno - 2);
	printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 3, $_anno - 3);
	printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 4, $_anno - 4);
	printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 5, $_anno - 5);
	printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 6, $_anno - 6);
	printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 7, $_anno - 7);
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr><td align=\"center\"><input type=\"text\" name=\"data\" size=\"11\" maxlength=\"10\">Inserisci eventuale data</td></tr>\n";

	echo "<tr><td align=center><br>";
	echo "<select name=\"tipo\">\n";
	echo "<option value=\"catmer\">Per Categoria Merceologica</option>\n";
	echo "<option value=\"tipart\">Per Tipologia Articolo</option>\n";
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr><td align=center><br><br>Scegliere il tipo di stampa<br></td></tr>";
	echo "<tr><td align=center><br>";
	echo "<select name=\"stampa\">\n";
	echo "<option value=\"rep_rimanenze\">Rimanenze in carta comune </option>";
	#echo "<option value=\"rimanenzepdf\">Rimanenze in formato pdf </option>";
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Stampa\");>\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";


	echo "</body></html>";
    }
    else
    {
	permessi_sessione($_cosa, $_percorso);
    }
    ?>