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
$conn = permessi_sessione("verifica", $_percorso);


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


		<?php

		printf("<br><br><form action=\"stampa_for.php\" target=\"sotto\" method=\"POST\">");

		echo "<tr><td align=center>Selezionare il Fornitore da stampare<br>";
		echo "<select name=\"codice\">\n";
		echo "<option value=\"\"></option>";

		// Stringa contenente la query di ricerca...
		$query = sprintf("select ragsoc, codice from fornitori order by ragsoc");
		// Esegue la query...
		if ($res = mysql_query($query, $conn))
		{
		    // La query ?stata eseguita con successo...
		    // MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
		    if (mysql_num_rows($res))
		    {
			// Tutto procede a meraviglia...
			echo "<span class=\"testo_blu\">";
			while ($dati = mysql_fetch_array($res))
			{
			    printf("<option value=\"%s\">%s</option>\n", $dati['codice'], $dati['ragsoc']);
			}
		    }
		}
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