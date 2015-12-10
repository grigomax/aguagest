<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso . "../setting/vars.php";

session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carico le librerie necessarie
require "../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("", $_percorso);
java_script($_cosa, $_percorso);

jquery_datapicker($_cosa, $_percorso);
echo "</head>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['scadenziario'] > "1")
{

    echo "<table align=\"left\" width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\">\n";

    echo "<form action=\"scadenziario_avvisi.php\" target=\"_blanck\" method=\"POST\">\n";
    echo "<h3 align=\"center\">Stampa Scadenze</h3>\n";

    echo "<h4>Seleziona la data di partenza</h4>\n";

    $_hoy = date('d-m-Y');

    echo "<input type=\"text\" size=\"12\" name=\"data_scad\" class=\"data\" value=\"$_hoy\">\n";

    echo "<h4>Eventuale data fine</h4>\n";
    echo "<input type=\"text\" size=\"12\" name=\"data_fine\" class=\"data\" value=\"00-00-0000\"> <br>Lascia a zero per vedere tutto\n";

    echo "<h4>Seleziona la modalita di Stampa</h4>\n";

    //prendiamoci le banche

    $banche = tabella_banche("elenca", $_codice, $_abi, $_cab, $_parametri);

    echo "<select name=\"tipo\">\n";
    echo "<option value=\"Tutte\">Tutte le scadenze complete</option>\n";
    echo "<option value=\"Libere\">Libere - che non hanno banca assegnata</option>\n";

    foreach ($banche AS $dati)
    {
        echo "<option value=\"$dati[codice]\">$dati[banca]</option>\n";
    }

    echo "</select>\n";

    echo "<br><input type=\"submit\" value=\"Stampa\"></form>\n";

    echo "</td></tr></table>\n";

    echo "</td></tr></body></html>";

    $conn->null;
    $conn = null;
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>