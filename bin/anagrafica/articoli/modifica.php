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


if ($_SESSION['user']['anagrafiche'] > "2")
{

// Inizio tabella pagina principale ----------------------------------------------------------

    echo "<table align=\"center\" width=\"100%\"><tr><td align=\"center\">";

    
    printf("<form action=\"modificacod.php\" method=\"POST\">\n");
    echo "<table width=\"400\" border=\"0\">\n";


// CAMPO DOVE ---------------------------------------------------------------------------------------
    echo "<tr><td colspan=2 align=\"center\"><span class=\"testo_blu\"><br><br><b>Inserisci il codice da modificare:</b><br></span></td></tr>\n";
    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
    printf("<td class=\"colonna\" width=\"200\" align=\"center\"><input type=\"text\" name=\"articolo\" value=\"%s\" size=\"40\" maxlength=\"40\"></td></tr>\n", $_descrizione);


// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "<tr><td>&nbsp;</td><td align=center><input type=\"submit\" name=\"azione\" value=\"Modifica\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";

    echo "</table>";
// ************************************************************************************** -->
    echo "</td></tr></table>\n";
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>