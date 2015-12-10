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
session_start();
$_SESSION['keepalive'] ++;

//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require "../../librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{
    echo "<table width=\"100%\">\n";
    echo "<tr><td align=\"center\" width=\"80%\" valign=\"top\">\n";

    echo "<span class=\"testo_blu\"><br><b>Visualizzazione Breve AGENTE</b></span><br><br>";

// mi prendo il GET appena passato

    $dati = tabella_agenti("singola", $_GET['codice'], $_parametri);

    echo "<form action=\"modificaage.php\" method=\"POST\">";
    echo "<table width=\"80%\" border=\"0\">";
// CAMPO Articolo ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
    printf("<td class=\"colonna\" align=\"left\"><b>%s</b></td><tr>\n", $dati['codice']);

// CAMPO Descrizione ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Ragione soc. :&nbsp;</b></span></td>";
    printf("<td class=\"colonna\" align=\"left\"><b>%s</b></td></tr>\n", $dati['ragsoc']);

// CAMPO Unita' di misura ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Ragsoc 2:&nbsp;</span></td>";
    printf("<td align=\"left\">%s</td></tr>", $dati['ragsoc2']);

// CAMPO iva -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Indirizzo:&nbsp;</span></td>";
    printf("<td align=\"left\">%s</td></tr>", $dati['indirizzo']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Città &nbsp;</span></td>";
    printf("<td align=\"left\">%s + %s</td></tr>", $dati['citta'], $dati['prov']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Telefono.:&nbsp;</span></td>";
    printf("<td align=\"left\">%s</td></tr>", $dati['telefono']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Telefono 2.:&nbsp;</span></td>";
    printf("<td align=\"left\">%s</td></tr>", $dati['telefono2']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Cell .:&nbsp;</span></td>";
    printf("<td align=\"left\">%s</td></tr>", $dati['cell']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Fax.:&nbsp;</span></td>";
    printf("<td align=\"left\">%s</td></tr>", $dati['fax']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Contatto .:&nbsp;</span></td>";
    printf("<td align=\"left\">%s</td></tr>", $dati['contatto']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Sito internet .:&nbsp;</span></td>";
    printf("<td align=\"left\"><a href=\"http://%s\" noframe>%s</td></tr>", $dati['sitocli'], $dati['sitocli']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Invia una email .:&nbsp;</span></td>";
    printf("<td align=\"left\"><a href=\"mailto:%s\">%s</td></tr>", $dati['email'], $dati['email']);



// CAMPO note articolo -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Note :&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" align=\"left\">";
    printf("%s</td></tr>", $dati['note']);
    echo "</table>";

    echo "<br><br><b> Visualizza tutti i dettagli e modifica Agente ==> &nbsp;<b><input type=\"submit\" name=\"codice\" value=\"$dati[codice]\">\n";
    echo "</form>";



    echo "<br>";

    echo "</td></tr></table>"; //chiusura seconda tabella
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>