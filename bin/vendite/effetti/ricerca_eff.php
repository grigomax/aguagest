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

if ($_SESSION['user']['vendite'] > "1")
{


// ***********************************************************************************************************
    echo "<br><br><form action=\"risultato_eff.php\" method=\"POST\">\n";
    echo "<table width=\"400\" border=\"0\" align=\"center\">\n";

// CAMPO DOVE ---------------------------------------------------------------------------------------
    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Dove:&nbsp;</span></td>\n";
    echo "<td width=\"200\" align=\"center\">";
    echo "<select name=\"campi\">\n";
    echo "<option value=\"ragsoc\">Ragione Sociale</option>\n";
    echo "<option value=\"numeff\">Numero effetto</option>\n";
    echo "<option value=\"tipeff\">Tipo Effetto</option>\n";
    echo "<option value=\"dataeff\">Data Effetto</option>\n";
    echo "<option value=\"scadeff\">Scadenza Effetto</option>\n";
    echo "<option value=\"impeff\">Importo effetto</option>\n";
    echo "<option value=\"datadoc\">Data Fattura</option>\n";
    echo "<option value=\"totdoc\">Totale Fattura</option>\n";
    echo "<option value=\"numdoc\">Numero Fattura</option>\n";
    echo "<option value=\"bancadist\">Banca Presentazione</option>\n";
    echo "<option value=\"ndistinta\">Numero distinta</option>\n";
    echo "<option value=\"datadist\">Data distinta</option>\n";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
    printf("<td width=\"200\" align=\"center\"><input type=\"text\" autofocus name=\"descrizione\" value=\"%s\" size=\"60\" maxlength=\"40\"></td></tr></span>\n", $_descrizione);

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Numero effetto => &nbsp;</span></td>\n";
    echo "<td width=\"200\" align=\"left\"><input type=\"text\" name=\"numeff\" size=\"16\" maxlength=\"15\"> <input type=\"checkbox\" name=\"sospesi\" value=\"SI\"> Cerca solo sospesi</td></tr>\n";


    echo "<tr><td align=\"center\" colspan=\"2\"><input align=\"center\" type=\"submit\" value=\"Cerca !\"></td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";

// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>