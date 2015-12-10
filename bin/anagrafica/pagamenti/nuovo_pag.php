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


if ($_SESSION['user']['anagrafiche'] > "2")
{
    echo "<table with=\"100%\" align=\"left\"><tr><td>";
// Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";
    echo "<tr>";
    echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";


    echo "<span class=\"testo_blu\"><br><b>Inserimento tipo Pagamento</b></span><br><br>";

    echo "<form action=\"risinsepag.php\" method=\"POST\">";
    echo "<table width=\"80%\" border=\"1\"";
// CAMPO Codice ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
    echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"codpag\" size=\"6\" maxlength=\"5\"> Inserire un codice Progressivo anche alfanumerico max 5 caratteri </td><tr>\n";

// CAMPO descrizione---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Descrizione:&nbsp;</b></span></td>";
    echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"descrizione\" size=\"81\" maxlength=\"80\"></td></tr>\n";

// CAMPO sconto ---------------------------------------------------------------------------------------/**/
// 	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Sconto:&nbsp;</span></td>";
// 	echo  "<td><input type=\"text\" name=\"sconto\"  size=\"3\" maxlength=\"2\"> applicato al pagamento</td>\n";
// CAMPO rataiva------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Rata Iva : &nbsp;</span></td>\n";
    echo "<td width=\"200\" align=\"left\">";
    echo "<select name=\"rataiva\">\n";
    echo "<option value=\"1\">1 - Iva divisa sulle varie rate</option>\n";
    echo "<option value=\"2\">2 - Iva aggiunta totalmente sulla prima rata </option>\n";
    echo "<option value=\"3\">3 - Iva aggiunta totalmente sull' ultima rata </option>\n";
    echo "<option value=\"4\">4 - La prima rata &egrave; solo l'iva </option>\n";
    echo " </select>";


// CAMPO giorno fisso ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Giorno Fisso :&nbsp;</span></td>";
    echo "<td><input type=\"text\" name=\"scadfissa\"  size=\"3\" maxlength=\"2\"> Giorno di scadenza fissa della rata se vuoto non viene preso in considerazione</td></tr>";

// CAMPO esclusione primo mese ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Esclusione 1 :&nbsp;</span></td>";
    echo "<td><input type=\"text\" name=\"unomese\"  size=\"3\" maxlength=\"2\"> Primo Mese di esclusione scadenza es. agosto </td></tr>";

// CAMPO esclusione secondo mese ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Esclusione 2 :&nbsp;</span></td>";
    echo "<td><input type=\"text\" name=\"duemese\"  size=\"3\" maxlength=\"2\"> Secondo Mese di esclusione scadenza es. dicembre </td></tr>";


// CAMPO Tipo pagamento------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Tipo pagamento : &nbsp;</span></td>\n";
    echo "<td width=\"200\" align=\"left\">";
    echo "<select name=\"tipopag\">\n";
    echo "<option value=\"1\">1 - Rimessa diretta</option>\n";
    echo "<option value=\"2\">2 - Contanti</option>\n";
    echo "<option value=\"3\">3 - Ricevuta bancaria </option>\n";
    echo "<option value=\"4\">4 - Tratta o cambiale </option>\n";
    echo "<option value=\"5\">5 - Contrassegno </option>\n";
    echo "<option value=\"6\">6 - Bonifico Bancario</option>\n";
    echo "<option value=\"7\">7 - Ricevimento Fattura</option>\n";
    echo " </select>";

// CAMPO N. scadenze
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">N. di scadenze :&nbsp;</span></td>";
    echo "<td><input type=\"text\" name=\"nscad\"  size=\"3\" maxlength=\"2\" value=\"1\"> Il numero di rate che compongono il pagamento </td></tr>";

// CAMPO gg prima scad
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Giorni prima scadenza :&nbsp;</span></td>";
    echo "<td><input type=\"text\" name=\"ggprimascad\"  size=\"4\" maxlength=\"3\" value=\"0\"> Il numero di giorni che passano tra la fattura e la prima rata </td></tr>";

// CAMPO gg tra scdenza
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Giorni tra le scadenze :&nbsp;</span></td>";
    echo "<td><input type=\"text\" name=\"ggtrascad\"  size=\"4\" maxlength=\"3\" value=\"0\"> Il numero di giorni che passano le scadenze </td></tr>";

    // CAMPO data fattura FM
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Fine mese : &nbsp;</span></td>\n";
    echo "<td width=\"200\" align=\"left\">";
    echo "<select name=\"dffm\">\n";
    echo "<option value=\"DF\">Data fattura</option>\n";
    echo "<option value=\"FM\">Fine mese</option>\n";


// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "</table>\n<br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"inserisci\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
// ************************************************************************************** -->
    echo "</table>\n";
// Fine tabella pagina princ
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>