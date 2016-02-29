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

$_file = $_GET['files'];

if ($_SESSION['user']['setting'] > "3")
{

    echo "<table width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\" width=\"80%\" valign=\"top\">\n";
    echo "<br><br><br> Risultato eliminazione.. IP<br><br>";

    $result = tabella_banned_ip("elimina", $_GET['ip_remoto'], $volte, $user_agent);


    if ($result == "OK")
    {
        echo "Eliminazione Riuscita";
    }
    else
    {
        echo "Errore Eliminazione";
    }


    echo "</td></tr>\n";

    echo "</table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>