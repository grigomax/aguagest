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
ini_set('session.gc_maxlifetime', $SESSIONTIME);
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



if ($_SESSION['user']['stampe'] > "1")
{


    $_utente = $_GET['utente'];


    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr><td width=\"85%\" align=\"center\" valign=\"top\">";
    echo "<span class=\"intestazione\"><br><b>Scegliere i $_utente da stampare</b><br></span><br></td></tr>\n";

    printf("<br><br><form action=\"clifor_selezione.php?utente=$_utente\" target=\"sotto\"method=\"POST\">");
    $_anno = date("Y");

    echo "<tr><td align=center><br>";
    // Stringa contenente la query di ricerca...

    if ($_GET['utente'] == "fornitori")
    {
        $result = tabella_fornitori("elenca_select_ragsoc", "codini", $_parametri);
    }
    else
    {
        $result = tabella_clienti("elenca_select_ragsoc", "codini", $_parametri);
    }

    // Esegue la query...

    echo "</td></tr>\n";

    echo "<tr><td align=center><br>";

    if ($_GET['utente'] == "fornitori")
    {
        $result = tabella_fornitori("elenca_select_ragsoc", "codfine", $_parametri);
    }
    else
    {
        $result = tabella_clienti("elenca_select_ragsoc", "codfine", $_parametri);
    }

    echo "</td></tr>\n";

    echo "<tr><td align=center><br>";
    echo "<select name=\"stampa\">\n";
    echo "<option value=\"clifor\">Completa con intestazione</option>";
    #echo "<option value=\"clifor_pdf\">Stampa comleta su file PDF</option>";
    echo "</select>\n";
    echo "</td></tr>\n";


    echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Stampa\");>\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</td>\n</tr>\n";

    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>