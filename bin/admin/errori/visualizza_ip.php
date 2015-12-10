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
    echo "<br><br><br> Visualizzazione accessi IP clicca l'indirizzo per eliminarlo..<br><br>";

    $result = tabella_banned_ip("elenca", $ip_provenienza, $volte, $user_agent);


    echo "<table width=\"100%\" border=\"0\" align=\"left\">\n";
    echo "<tr><td>Indirizzo prov. </td><td>Numero accessi</td><td>Utente</td></tr>\n";

    foreach ($result AS $dati)
    {
        echo "<tr><td><a href=\"visualizza_ip_result.php?ip_remoto=$dati[ip_remoto]\">$dati[ip_remoto]</td><td>$dati[n_volte]</td><td>$dati[user_agent]</td></tr>\n";
    }


    echo "</table>\n";
    echo "</td></tr>\n";

    echo "</table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>