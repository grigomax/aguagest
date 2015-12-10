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


if ($_SESSION['user']['anagrafiche'] > "1")
{
   
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
	<tr>
	<td width=\"85%\" align=\"center\" valign=\"top\">
	<span class=\"intestazione\">Gestione Prezzi cliente<br><br> <b>Scegliere il cliente</b></span>
	</td></tr>";

    printf("<br><br><form action=\"l5-2.php\" method=\"POST\">");

    echo "<tr><td align=center><br>";
    echo "<select name=\"cliente\">\n";
    echo "<option value=\"\"></option>";

    // Stringa contenente la query di ricerca... solo dei fornitori
    $query = sprintf("select codice, ragsoc from clienti order by ragsoc");

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
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codice'], $dati['ragsoc'], $dati['codice']);
	    }
	}
    }
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td align=center><br> Ora seleziona l'articolo <br>";
    echo "<select name=\"articolo\">\n";
    echo "<option value=\"\"></option>";

    // Stringa contenente la query di ricerca... solo dei fornitori
    $query = sprintf("select articolo, descrizione from articoli order by articolo");

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
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['articolo'], $dati['articolo'], $dati['descrizione']);
	    }
	}
    }
    echo "</select>\n";
    echo "</td></tr>\n";



    echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Avanti\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>