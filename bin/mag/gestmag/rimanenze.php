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
session_start();
$_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";


//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("", $_percorso);
jquery_datapicker($_cosa, $_percorso);

echo "</head>";
echo "<body>\n";

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

	printf("<br><form action=\"rimanenze2.php\" target=\"sotto\" method=\"POST\">");
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

	echo "<tr><td align=\"center\"><br>Inserisci eventuale data <br><input type=\"text\" class=\"data\" name=\"data\" size=\"11\" maxlength=\"10\"></td></tr>\n";

	echo "<tr><td align=center><br>";
	echo "<select name=\"tipo\">\n";
	echo "<option value=\"catmer\">Per Categoria Merceologica</option>\n";
	echo "<option value=\"tipart\">Per Tipologia Articolo</option>\n";
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Stampa\");>\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";


	echo "</body></html>";

     //   echo mb_convert_encoding("Quantità", "windows-1252", "UTF-8");
    }
    else
    {
	permessi_sessione($_cosa, $_percorso);
    }
    ?>