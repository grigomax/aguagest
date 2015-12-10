<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
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
    	<td align="left" valign="top">
    	    <span class="intestazione"><b>Intestatario DDT Reso conto lavoro</b></span><br>
    	</td></tr>
        <tr><td><?php

// Cerco il post inviatomi dalla pagina precente ed lo cerco sul database clienti
    $query = sprintf("SELECT *, clienti.codice AS codcli, fornitori.codice AS codfor FROM clienti INNER JOIN fornitori ON clienti.piva=fornitori.piva where clienti.piva=\"%s\"", $_POST['fornitore']);

// Esegue la query...
    if ($res = mysql_query($query, $conn))
    {
	// La query ?stata eseguita con successo...
	// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
	if (mysql_num_rows($res))
	{
	    // Tutto procede a meraviglia...
	    $dati = mysql_fetch_array($res);

	    echo "<tr>";
	    printf("<td align=\"left\"><span class=\"testo_blu\">%s<br>", $dati['ragsoc']);
	    printf("%s<br>", $dati['indirizzo']);
	    printf("%s %s %s<br>", $dati['cap'], $dati['citta'], $dati['provincia']);
	    echo "</span></td></tr>";
	    echo "<tr>";
	    echo "<td width=\"100\" height=\"1\" align=\"center\" class=\"logo\"></td>";
	    echo "</tr></table>";
	    $_bloccocli = $dati['bloccocli'];
	}
    }

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">";
    echo "<span class=\"intestazione\"><b>Selezionare gli ordini da importare</b></span></td></tr>";
    printf("<br><br><form action=\"eseguimp_brcl.php\" method=\"POST\">");

// settiamo le variabili
    $_SESSION['fornitore'] = $dati['codcli'];
    $_SESSION['cliente'] = $dati['codfor'];
    $_utente = $dati['codcli'];
    $_annodoc = date("Y");
    $_mesedoc = date("m");
    $_daydoc = date("d");
    $_annodaimp = $_POST['anno'];

    echo "<tr><td align=center>Anno documenti da importare <input type=\"text\" name=\"annodaimp\" size=\"4\" maxlenght=\"4\" value=\"$_annodaimp\"></td></tr>";
    echo "<tr><td align=center><br>";
    echo "<select name=\"codini\">\n";
    echo "<option value=\"\">Da Numero</option>";

// Stringa contenente la query di ricerca...
    $query = sprintf("select * from bv_bolle INNER JOIN clienti ON bv_bolle.utente = clienti.codice where utente=\"%s\" and anno=\"%s\" AND ( status='stampato' OR status='parziale') AND causale != 'VENDITA' order by ndoc", $_utente, $_annodaimp);


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
		printf("<option value=\"%s\">%s - %s - %s</option>\n", $dati['ndoc'], $dati['ndoc'], $dati['ragsoc'], $dati['status']);
	    }
	}
    }
    echo "</select>\n";
    echo "</td></tr>\n";

// inserimento fine numero

    echo "<tr><td align=center><br>";
    echo "<select name=\"codfine\">\n";
    echo "<option value=\"\">A Numero</option>";

// Stringa contenente la query di ricerca...
    $query = sprintf("select * from bv_bolle INNER JOIN clienti ON bv_bolle.utente = clienti.codice where utente=\"%s\" and anno=\"%s\" AND ( status='stampato' OR status='parziale') AND causale != 'VENDITA' order by ndoc", $_utente, $_annodaimp);


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
		printf("<option value=\"%s\">%s - %s - %s</option>\n", $dati['ndoc'], $dati['ndoc'], $dati['ragsoc'], $dati['status']);
	    }
	}
    }
    echo "</select>\n";
    echo "</td></tr>\n";

// Dobbiamo inserire due campi di riferimento alla bolla fonitore e fattura fornitore di riferimento.

    printf("<tr><td align=\"center\" valign=\"top\"><br>N. ddt Fornitore: <input type=\"text\" name=\"ddtacq\" value=\"%s\" size=\"20\" maxlength=\"20\">      ", $_ddtacq);
    printf("Fattura fornitore: <input type=\"text\" name=\"fatturacq\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $_fatturacq);


// cerco l'ultimo numero di bolla inserito e lo propongo
// Stringa contenente la query di ricerca...
// mi piglio l'ultimo n. documento inserito...
    $query = sprintf("SELECT ndoc FROM magazzino where anno=\"%s\" and tdoc='ddtacq' ORDER BY ndoc DESC LIMIT 1", $_annodoc);

// Esegue la query...
    if ($res = mysql_query($query, $conn))
    {
	$dati = mysql_fetch_array($res);
	$_undoc = $dati['ndoc'];
	// una volta trovato ne aggiungo uno.
	$_ndoc = $_undoc + 1;
    }
//	Prendo le variabili abiente come il giorno

    printf("<tr><td align=\"center\"><br>Numero progressivo suggertito <input type=\"text\" name=\"ndoc\" size=\"6\" value=\"%s\"> Anno <input type=\"text\" name=\"annondoc\" size=\"4\" value=\"%s\"></td></tr>", $_ndoc, $_annodoc);

    printf("<tr><td align=\"center\"><br>Data suggertita <input type=\"text\" name=\"daydoc\" size=\"2\" value=\"%s\"> Mese <input type=\"text\" name=\"mesedoc\" size=\"2\" value=\"%s\"> Anno <input type=\"text\" name=\"annodoc\" size=\"4\" value=\"%s\"></td></tr>", $_daydoc, $_mesedoc, $_annodoc);

// invio di sette post

    echo "</table><center><br><input type=\"submit\" name=\"azione\" value=\"Annulla\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Parziale\">";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>