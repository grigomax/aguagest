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
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "2")
{


// Creiamo due arre per le tabelle

    $dataiva['1'] = "1 - Iva divisa sulle varie rate";
    $dataiva['2'] = "2 - Iva applicata totalmente sulla prima rata";
    $dataiva['3'] = "3 - Iva applicata totalmente sull' ultima rata";
    $dataiva['4'] = "4 - La prima rata &egrave; solo l'iva";

// e per la tipologia pagamento

    $paga['1'] = "1 - Rimessa diretta";
    $paga['2'] = "2 - Contanti";
    $paga['3'] = "3 - Ricevuta bancaria";
    $paga['4'] = "4 - Tratta o cambiale";
    $paga['5'] = "5 - Contrassegno";
    $paga['6'] = "6 - Bonifico Bancario";
    $paga['7'] = "7 - Ricevimento Fattura";


    echo "<table><tr><td>";

// Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";
    echo "<td width=\"100%\" align=\"center\" valign=\"top\" class=\"foto\">\n";
    echo "<span class=\"testo_blu\"><br><b>Modifica Anagrafica Pagamenti</b></span><br><br>";

    $_codcli = $_GET['codice'];


    $dati = tabella_pagamenti("singola", $_codcli, $_parametri);


    echo "<form action=\"risinsepag.php\" method=\"POST\">";
    echo "<table width=\"100%\" border=\"0\"";

// CAMPO Codice ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
    printf("<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"codpag\" value=\"%s\" checked>%s</td><tr>", $dati['codice'], $dati['codice']);

// CAMPO descrizione---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Descrizione:&nbsp;</b></span></td>";
    printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"81\" maxlength=\"80\"></td></tr>", $dati['descrizione']);

// // CAMPO sconto ---------------------------------------------------------------------------------------
// 	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Sconto:&nbsp;</span></td>";
// 	printf("<td><input type=\"text\" name=\"sconto\" value=\"%s\" size=\"3\" maxlength=\"2\"> applicato al pagamento</td>\n",$dati['sconto']);
// CAMPO rataiva------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Rata Iva : &nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    echo "<select name=\"rataiva\">\n";
    printf("<option value=\"%s\"> %s </option>", $dati['rataiva'], $dataiva[$dati['rataiva']]);
    echo "<option value=\"1\"> 1 - Iva divisa sulle varie rate</option>\n";
    echo "<option value=\"2\"> 2 - Iva applicata totalmente sulla prima rata </option>\n";
    echo "<option value=\"3\"> 3 - Iva applicata totalmente sull' ultima rata </option>\n";
    echo "<option value=\"4\"> 4 - La prima rata � solo l'iva </option>\n";
    echo "</select>";


// CAMPO giorno fisso ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Giorno Fisso :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"scadfissa\" value=\"%s\" size=\"2\" maxlength=\"3\"> Giorno di scadenza fissa della rata se vuoto non viene preso in considerazione</td></tr>", $dati['scadfissa']);

// CAMPO esclusione primo mese ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Esclusione 1 :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"unomese\" value=\"%s\" size=\"3\" maxlength=\"2\"> Primo Mese di esclusione scadenza es. agosto </td></tr>", $dati['unomese']);

// CAMPO esclusione secondo mese ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Esclusione 2 :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"duemese\" value=\"%s\" size=\"3\" maxlength=\"2\"> Secondo Mese di esclusione scadenza es. dicembre </td></tr>", $dati['duemese']);


// CAMPO Tipo pagamento------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Tipo pagamento : &nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    echo "<select name=\"tipopag\">\n";
    printf("<option value=\"%s\"> %s </option>", $dati['tipopag'], $paga[$dati['tipopag']]);
    echo "<option value=\"1\"> 1 - Rimessa diretta</option>\n";
    echo "<option value=\"2\"> 2 - Contanti</option>\n";
    echo "<option value=\"3\"> 3 - Ricevuta bancaria </option>\n";
    echo "<option value=\"4\"> 4 - Tratta o cambiale </option>\n";
    echo "<option value=\"5\"> 5 - Contrassegno </option>\n";
    echo "<option value=\"6\"> 6 - Bonifico Bancario</option>\n";
    echo "<option value=\"7\"> 7 - Ricevimento Fattura</option>\n";
    echo " </select></tr>";

// CAMPO N. scadenze
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">N. di scadenze :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"nscad\" value=\"%s\" size=\"3\" maxlength=\"2\"> Il numero di rate che compongono il pagamento </td></tr>", $dati['nscad']);

// CAMPO gg prima scad
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Giorni prima scadenza :&nbsp;</span></td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"ggprimascad\" value=\"%s\" size=\"4\" maxlength=\"3\"> Il numero di giorni che passano tra la fattura e la prima rata </td></tr>", $dati['ggprimascad']);

// CAMPO gg tra scdenza
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Giorni tra le scadenze :&nbsp;</span></td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"ggtrascad\" value=\"%s\" size=\"4\" maxlength=\"3\"> Il numero di giorni che passano le scadenze </td></tr>", $dati['ggtrascad']);

    // CAMPO data fattura FM
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Fine mese : &nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    echo "<select name=\"dffm\">\n";
    printf("<option value=\"%s\"> %s </option>", $dati['dffm'], $dati['dffm']);
    echo "<option value=\"DF\">DF - Data fattura</option>\n";
    echo "<option value=\"FM\">FM - Fine mese</option>\n";




// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "</table>\n<br><input type=\"submit\" name=\"azione\" value=\"Aggiorna\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Elimina\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
// ************************************************************************************** -->
    echo "</table>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>