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
    


    $_cliente = $_POST['cliente'];
    $_articolo = $_POST['articolo'];

    // Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"100%\" border=1 cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";

    echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";


    echo "<span class=\"testo_blu\"><br><b>Visualizzazione Prezzi cliente $_cliente</b></span><br><br>";

    echo "<center><A HREF=\"#\" onClick=\"history.back()\">Trova un altro articolo</A>";

    $query = sprintf("select * from articoli where articolo=\"%s\"", $_articolo);

    // Esegue la query...
    $res = mysql_query($query, $conn);
    mysql_num_rows($res);
    $dati = mysql_fetch_array($res);

    echo "<form action=\"modificacod.php\" method=\"POST\">";
    echo "<table width=\"80%\" border=\"1\"";
// CAMPO Articolo ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
    printf("<td class=\"colonna\" align=\"left\"><b>%s</b></td><tr>\n", $dati['articolo']);

// CAMPO Descrizione ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Descrizione:&nbsp;</b></span></td>";
    printf("<td class=\"colonna\" align=\"left\"><b>%s</b></td></tr>\n", $dati['descrizione']);

    echo "</table><br>";
    $_anno = date('Y');

// inizio calcolo giacenza

    $query = sprintf("select sum(qtacarico) AS qtacarico, sum(qtascarico) AS qtascarico from magazzino where anno=\"%s\" and articolo=\"%s\"", $_anno, $_articolo);

    $res = mysql_query($query, $conn) or die(mysql_error());
    $dati = mysql_fetch_array($res);

    $_qtacarico = $dati['qtacarico'];
    $_qtascarico = $dati['qtascarico'];

    $_giacenza = ($_qtacarico - $_qtascarico);

// fine calcolo giacenza

    echo "<table width=\"80%\" border=\"1\">";

    echo "<tr><td colspan=4 align=\"right\"><span class=\"testo_blu\">Gicenza articolo in magazzino ==>&nbsp;</span></td>";
    echo "<td colspan=4 align=\"center\"><b>$_giacenza</b></td></tr>";


    // inizio muovimenti uscita
    echo "<tr><td colspan=7 align=\"left\"><span class=\"testo_blu\"><b>Ultimi Muovimenti Vendita anno in corso in ordine scalare</b> &nbsp;</span></td></tr>";

    echo "<tr><td align=\"left\">Data Reg.</td><td>Tipo Doc. </td><td> Numero Doc.</td><td>Cliente</td><td>Q.ta scarico</td><td>Valore</td><td>Netto Vendita</td></tr> ";

    // Stringa contenente la query di ricerca... solo dei fornitori
    $query = sprintf("SELECT * FROM magazzino INNER JOIN clienti ON magazzino.utente=clienti.codice WHERE articolo=\"%s\" AND anno=\"%s\" AND tut='c' AND utente=\"%s\" ORDER BY datareg DESC LIMIT 30", $_articolo, $_anno, $_cliente);

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
		$_nettovend = $dati['valorevend'] / $dati['qtascarico'];
		printf("<tr><td align=\"center\">%s</td><td align=\"left\">%s</td><td align=\"center\"><a href=\"../../vendite/docubase/visualizzadoc.php?tdoc=%s&anno=%s&ndoc=%s\">%s</td><td align=\"center\">%s</td><td align=\"right\">%s</td><td align=\"right\">%s</td><td align=\"right\">%s</td></tr>", $dati['datareg'], $dati['tdoc'], $dati['tdoc'], $dati['anno'], $dati['ndoc'], $dati['ndoc'], $dati['ragsoc'], $dati['qtascarico'], $dati['valorevend'], number_format(($_nettovend), 2));
	    }
	}
    }

    echo "</tr></table>";

// chiusura tabelle interna
    echo "<br>";

// inizio muovimenti uscita muovimenti anni precedenti
    echo "<table width=\"80%\" border=\"1\">";
    echo "<tr><td colspan=7 align=\"left\"><span class=\"testo_blu\"><b>Ultimi Muovimenti Vendita Anni precedenti in ordine scalare</b> &nbsp;</span></td></tr>";

    echo "<tr><td align=\"left\">Data Reg.</td><td>Tipo Doc. </td><td> Numero Doc.</td><td>Cliente</td><td>Q.ta scarico</td><td>Valore</td><td>Netto Vendita</td></tr> ";

    // Stringa contenente la query di ricerca... solo dei fornitori
    $query = sprintf("SELECT * FROM magastorico INNER JOIN clienti ON magastorico.utente=clienti.codice WHERE articolo=\"%s\" AND tut='c' AND utente=\"%s\" ORDER BY datareg DESC LIMIT 30", $_articolo, $_cliente);

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
		$_nettovend = $dati['valorevend'] / $dati['qtascarico'];
		printf("<tr><td align=\"center\">%s</td><td align=\"left\">%s</td><td align=\"center\"><a href=\"../../vendite/docubase/visualizzadoc.php?tdoc=%s&anno=%s&ndoc=%s\">%s</td><td align=\"center\">%s</td><td align=\"right\">%s</td><td align=\"right\">%s</td><td align=\"right\">%s</td></tr>", $dati['datareg'], $dati['tdoc'], $dati['tdoc'], $dati['anno'], $dati['ndoc'], $dati['ndoc'], $dati['ragsoc'], $dati['qtascarico'], $dati['valorevend'], number_format(($_nettovend), 2));
	    }
	}
    }

    echo "</tr></table>"; // chiusura tabelle interna


    echo "</td></tr></table>"; //chiusura seconda tabella
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>