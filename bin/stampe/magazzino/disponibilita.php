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



if ($_SESSION['user']['stampe'] > "1")
{
    ?>

    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
    	<td width="85%" align="center" valign="top">
    	    <span class="intestazione"><br><b>Scegliere gli articoli Da stampare</b><br></span><br>			</td></tr>
	<?php

	printf("<br><br><form action=\"disponibilita2.php\" target=\"sotto\"method=\"POST\">");
	$_anno = date("Y");

	echo "<tr><td align=center><br>";
	echo "<select name=\"listino\">\n";
	for ($_nlv = 1; $_nlv <= $nlv; $_nlv++)
	{
	    printf("<option value=\"%s\">Listino prezzi %s </option>", $_nlv, $_nlv);
	}
	echo "</select>\n";
	echo "</td></tr>\n";
	echo "<tr><td align=center>dal codice <input type=\"text\" name=\"ccodini\" size=\"10\"> al codice <input type=\"text\" name=\"ccodfin\" size=\"10\"></td></tr>";
	echo "<tr><td align=center><br>";
	echo "<select name=\"codini\">\n";
	echo "<option value=\"\"></option>";

	// Stringa contenente la query di ricerca...
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

	echo "<tr><td align=center><br>";

	echo "<select name=\"codfin\">\n";
	echo "<option value=\"\"></option>";

	// Stringa contenente la query di ricerca... dell'articolo
	$query = sprintf("select articolo, descrizione from articoli order by articolo"); // Esegue la query...
	if ($res = mysql_query($query, $conn))
	{
	    // La query ?stata eseguita con successo...
	    // MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
	    if (mysql_num_rows($res))
	    {
		// Tutto procede a meraviglia...
		echo "<span class=\"testo_blu\">";
		while ($dati2 = mysql_fetch_array($res))
		{
		    printf("<option value=\"%s\">%s - %s</option>\n", $dati2['articolo'], $dati2['articolo'], $dati2['descrizione']);
		}
	    }
	}
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr><td align=center><br>";
	echo "<select name=\"stampa\">\n";
	echo "<option value=\"rp_disponibilita\">Completo ad una colonna con logo azienda</option>";
	echo "</select>\n";
	echo "</td></tr>\n";


	echo "<tr><td align=center> Se diverso da 0 viene stampato un listino prezzi netti gi&agrave; scontati ==><input type=\"text\" name=\"sconto\" value=\"0\" size=\"5\" maxlenght=\"4\"></td></tr>";

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