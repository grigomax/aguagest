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
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['magazzino'] > "1")
{

// ***********************************************************************************************************
    printf("<form action=\"elencodoc2.php\" method=\"POST\">\n");
    echo "<table width=\"400\" align=\"center\" border=\"0\"\n";

    echo "<td align=\"left\" valign=\"top\">
	<span class=\"intestazione\">D.D.T. fornitore <b>Cerca documento</b></span>
	</td></tr><tr><td>&nbsp </td></tr>";

// CAMPO DOVE ---------------------------------------------------------------------------------------
    echo "<tr><td width=\"200\" align=\"center\"><span class=\"testo_blu\">Dove:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"center\">";
    echo "<select name=\"campi\">\n";
    echo "<option value=\"ragsoc\">Ragione sociale</option>\n";
    echo "<option value=\"ndoc\">Numero Documento</option>\n";
    echo "<option value=\"ddtfornitore\">D.D.T. fornitore</option>\n";
    echo "<option value=\"fatturacq\">N. fattura Fornitore</option>\n";
    echo "<option value=\"protoiva\">N. protocollo IVA</option>\n";
    echo "<option value=\"utente\">Codice Fornitore</option>\n";
    echo "<option value=\"anno\">Anno</option>\n";
    echo "<option value=\"articolo\">Codice Articolo</option>\n";
    echo "<option value=\"datareg\">Data</option>\n";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
    printf("<td class=\"colonna\" width=\"200\" align=\"center\"><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"60\" maxlength=\"40\"></td></tr>\n", $_descrizione);

// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Cerca !\">\n";
    echo "</td></tr></form>";

// ************************************************************************************** -->
    echo "</table>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>