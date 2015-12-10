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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

$_tdoc = $_GET['tdoc'];

if ($_SESSION['user']['vendite'] > "1")
{


// ***********************************************************************************************************
    echo "<form action=\"elencodoc_2.php?tdoc=$_tdoc\" method=\"POST\">\n";
    echo "<table width=\"400\" align=\"center\" border=\"0\"\n";

    echo "<td align=\"left\" valign=\"top\">
			<span class=\"intestazione\"><br><b>Cerca $_tdoc</b></span>
			</td></tr><tr><td>&nbsp </td></tr>";

// CAMPO DOVE ---------------------------------------------------------------------------------------
    echo "<tr><td width=\"200\" align=\"center\"><span class=\"testo_blu\">Dove:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"center\">";
    echo "<select name=\"campi\">\n";
    echo "<option value=\"ragsoc\">Ragione sociale</option>\n";
    echo "<option value=\"ndoc\">Numero Documento</option>\n";
    echo "<option value=\"barcode\">Codice a Barre</option>\n";
    echo "<option value=\"utente\">Codice cliente</option>\n";
    echo "<option value=\"anno\">Anno</option>\n";

//echo "<option value=\"descrizione\">Descrizione articolo</option>\n";
    echo "<option value=\"status\">Stato documento</option>\n";
    echo "<option value=\"vettore\">Vettore</option>\n";
    echo "<option value=\"datareg\">Data</option>\n";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
    printf("<td class=\"colonna\" width=\"200\" align=\"center\"><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"60\" maxlength=\"40\"></td></tr>\n", $_descrizione);

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Codice a Barre => &nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"left\"><input type=\"text\" placeholder=\"anno+numero\" name=\"barcode\" size=\"20\" maxlength=\"20\"></td></tr>\n";

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
