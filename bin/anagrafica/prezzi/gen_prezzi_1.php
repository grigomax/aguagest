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


if ($_SESSION['user']['anagrafiche'] > "2")
{

   
    ?>
    <table width="80%" cellspacing="0" cellpadding="0" border="1" align="center">
        <tr>
    	<td width="100%" align="center" valign="top" colspan="2">
    	    <span class="intestazione"><b>Programma che genera i prezzi di vendita</b><br>Attenzione si consiglia di eseguire le copie</span></td></tr>
	<?php

	printf("<form action=\"gen_prezzi_2.php\" target=\"sotto\"method=\"POST\">");
	$_anno = date('Y');

	echo "<tr><td align=center colspan=\"2\"><br>";
	echo "<select name=\"listino\">\n";
	for ($_nlv = 1; $_nlv <= $nlv; $_nlv++)
	{
	    printf("<option value=\"%s\">Listino prezzi %s </option>", $_nlv, $_nlv);
	}
	echo "</select>\n";
	echo "</td></tr>\n";
	echo "<tr><td align=center colspan=\"2\">dal codice <input type=\"text\" name=\"ccodini\" size=\"10\"> al codice <input type=\"text\" name=\"ccodfin\" size=\"10\"></td></tr>";
	echo "<tr><td align=center colspan=\"2\"><br>";
	echo "<select name=\"codini\">\n";
	echo "<option value=\"\"></option>";

	if (!( $conn = @mysql_connect($db_server, $db_user, $db_password) ))
	{
	    echo "<span class=\"testo_blu\"><br>Non trovo il database server</span><br>";
	    exit(0);
	}
	//Uso il database canis...
	if (!@mysql_select_db($db_nomedb, $conn))
	{
	    echo "<span class=\"testo_blu\"><br><b>Non trovo il database</b></span><br>";
	    exit(0);
	}
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

	echo "<tr><td align=center colspan=\"2\"><br>";

	echo "<select name=\"codfin\">\n";
	echo "<option value=\"\"></option>";

	// Stringa contenente la query di ricerca... dell'articolo
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
		while ($dati2 = mysql_fetch_array($res))
		{
		    printf("<option value=\"%s\">%s - %s</option>\n", $dati2['articolo'], $dati2['articolo'], $dati2['descrizione']);
		}
	    }
	}
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr><td align=center colspan=\"2\"><br>";
	echo "<select name=\"calcolo\">\n";
	echo "<option value=\"prezzo_1\">Calcolo sul prezzo di acquisto 1</option>";
	echo "<option value=\"prezzo_2\">Calcolo sul prezzo di acquisto 2</option>";
	echo "<option value=\"ultimo\">Calcolo sull'ultimo prezzo di acquisto </option>";
	echo "<option value=\"media\">Calcolo sulla media degli acquisti anno in corso</option>";
	echo "<option value=\"media_p1p2\">Calcolo sulla media di acquisto 1 e 2</option>";
	echo "<option value=\"media_p1uc\">Calcolo sulla media di acquisto 1 e ultimo acquisto</option>";
	echo "<option value=\"media_p2uc\">Calcolo sulla media di acquisto 2 e ultimo acquisto</option>";
	echo "<option value=\"prezzo_1_ma\">Calcolo sul prezzo acquisto 1 e media acquisti</option>";
	echo "<option value=\"prezzo_2_ma\">Calcolo sul prezzo acquisto 2 e media acquisti</option>";
	echo "<option value=\"totale\">Calcolo totale sulla media di tutto !</option>";

	echo "</select>\n";
	echo "</td></tr>\n";
//prima moltiplicazione
	echo "<tr><td align=\"center\" colspan=\"2\"><br>Parametri per il calcolo del prezzo finale <br> VALORI DI ESEMPIO DA VARIARE A PIACIMENTO</td></tr>\n";
	echo "<tr><td width=\"50%\" align=\"right\">Se il prezzo di acquisto &egrave; inferiore o uguale a:</td><td>Moltiplica per</td></tr>\n";
	echo "<tr><td align=\"right\"><input type=\"text\" name=\"basso\" value=\"130.00\" size=\"10\"></td>
		  <td align=\"left\"><input type=\"text\" name=\"prima\" value=\"3\" size=\"4\">";
//seconda moltiplicazione
	echo "<tr><td width=\"50%\" align=\"right\">Altrimenti Se il prezzo di acquisto &egrave; <br> superiore a prima ma inferiore o uguale a:</td><td>Moltiplica per</td></tr>\n";
	echo "<tr><td align=\"right\"><input type=\"text\" name=\"medio\" value=\"250.00\" size=\"10\"></td>
		  <td align=\"left\"><input type=\"text\" name=\"seconda\" value=\"2.7\" size=\"4\">";
//ultima moltiplicazione
	echo "<tr><td width=\"50%\" align=\"right\">Altrimenti per tutto il resto <br> superiore a alla seconda</td><td>Moltiplica per</td></tr>\n";
	echo "<tr><td align=\"right\"><input type=\"text\" name=\"alto\" value=\"251.00\" size=\"10\"></td>
		  <td align=\"left\"><input type=\"text\" name=\"terza\" value=\"2.5\" size=\"4\">";


	//echo "<tr><td align=center> Se diverso da 0 viene stampato un listino prezzi netti già scontati ==><input type=\"text\" name=\"sconto\" value=\"0\" size=\"5\" maxlenght=\"4\"></td></tr>";

	echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Genera\");>\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";


	echo "</body></html>";
    }
    else
    {
	permessi_sessione($_cosa, $_percorso);
    }
    ?>