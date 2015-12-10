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



if ($_SESSION['user']['contabilita'] > "1")
{

    echo "<table align=\"center\" width=\"100%\" border=\"0\">\n";
    echo "<tr>\n";

    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
    echo "<span class=\"intestazione\"><br><b>Gestione spesometro.. <br> Conforme alla comunicazione 15/10/2013<br>Per modello Polivalente</b></span><br>\n";
    echo "Inserire anno di apparteneza per generazione spesometro..<br>\n";

    echo "<form action=\"stampa_reg.php\" method=\"POST\" target=\"_blank\">\n";

    echo "<br><br>Immettere anno di stampa <input type=\"text\" name=\"anno\" size=\"5\" maxlenght=\"4\">\n";

    echo "<br><br><input type=\"submit\" name=\"azione\" value=\"stampa\"> oppure <input type=\"submit\" name=\"azione\" value=\"creafile\">\n";

    echo "</td></tr>\n";
    echo "</table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>