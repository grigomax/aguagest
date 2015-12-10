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


if ($_SESSION['user']['anagrafiche'] > "1")
{
    ?>
    <CENTER>
        <tr>
    	<td align="center" valign="top" colspan="2">
    	    <span class="intestazione"><b>Scegliere il fornitore da sostituire..</b><br></span><br>
    	    Il programma permette di sostituire tutti gli articoli che presentano<br> un fornitore vecchio e di sostituirlo con il nuovo..
    	</td></tr>
	<?php
	echo "<br><br><form action=\"sost_forn2.php\" method=\"POST\">\n";
	echo "<br><tr><td align=center colspan=\"2\"><b>Selezionare il fornitore da cambiare</b><br>";
	echo "<select name=\"vecchio\">\n";
	echo "<option value=\"\"></option>";
	// Stringa contenente la query di ricerca... solo dei fornitori

	$query = "SELECT codice, ragsoc from fornitori ORDER BY ragsoc";


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
	echo "<br><br>\n";

	echo "<b> Selezionale il nuovo Intestatario</b><br>";
	echo "<select name=\"nuovo\">\n";
	echo "<option value=\"\"></option>";
	// Stringa contenente la query di ricerca... solo dei fornitori

	$query = "SELECT codice, ragsoc from fornitori ORDER BY ragsoc";


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

	echo "<center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Cambia\">\n";
	echo "</form>\n</td>\n";

	echo "</body></html>";
    }
    else
    {
	permessi_sessione($_cosa, $_percorso);
    }
    ?>