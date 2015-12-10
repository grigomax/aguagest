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

    printf("<br><br><form action=\"risprov.php\" method=\"POST\">\n");
    echo "<table width=\"400\" border=\"0\"\n";

    // selezioniamo gli agenti

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Seleziona anno:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"center\">";

    echo "<select name=\"annorif\">\n";
    printf("<option value=\"%s\">%s</option>", date("Y"), date("Y"));
    printf("<option value=\"%s\">%s</option>", date("Y") - 1, date("Y") - 1);

    echo "</select></td></tr>";


    $result = tabella_agenti("elenco", $_codage, $_parametri);
            
    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Sel. Agente:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"center\">";

    echo "<select name=\"agente\">\n";
    foreach ($result AS $dati)
    {
	printf("<option value=\"%s\">%s</option>", $dati['codice'], $dati['ragsoc']);
    }

    echo "</select>\n";
    echo "</td></tr>\n";

        echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Sel. Mese:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"center\">";
    
    select_mese($_cosa, "mese");
        
    
    echo "</td></tr>\n";
    //seleziona mesi
    
    
    
// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "</table>\n<input type=\"submit\" value=\"Vedi !\">\n";
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