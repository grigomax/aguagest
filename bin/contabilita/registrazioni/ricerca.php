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
session_start();
$_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

// Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\" border=\"0\">\n";
    echo "<tr>\n";

    echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

// ***********************************************************************************************************
    echo "<br><br><form action=\"result_ricerca.php\" method=\"POST\">\n";
    echo "<table width=\"80%\" border=\"0\"\n";

// CAMPO DOVE ---------------------------------------------------------------------------------------
    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Dove:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"center\">";
    echo "<select name=\"campi\">\n";
    echo "<option value=\"descrizione\">Descrizione della operazione</option>\n";
    echo "<option value=\"desc_conto\">Descrizione conto PDC</option>\n";
    echo "<option value=\"nreg\">Numero di registrazione</option>\n";
    echo "<option value=\"data_reg\">Data Registrazione</option>\n";
    echo "<option value=\"data_cont\">Data Giornale</option>\n";
    echo "<option value=\"causale\">Causale</option>\n";
    echo "<option value=\"nproto\">Numero Protocollo</option>\n";
    echo "<option value=\"ndoc\">Numero documento</option>\n";
    echo "<option value=\"conto\">Conto Piano dei conti</option>\n";
    echo "<option value=\"status\">Status</option>\n";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td width=\"200\" align=\"right\" autofocus><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"left\"><input type=\"text\" name=\"descrizione\" size=\"60\" maxlength=\"40\"></td></tr>\n";

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Numero Registrazione => &nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"left\"><input type=\"text\" name=\"nreg\" size=\"16\" maxlength=\"15\"></td></tr>\n";

// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "</table>\n<input type=\"submit\" value=\"Cerca !\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
// ************************************************************************************** -->
    echo "</table>\n";
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>