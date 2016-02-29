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

require_once $_percorso . "librerie/motore_anagrafiche.php";

if ($_SESSION['user']['user'] != "")
{
    base_html("", $_percorso);
    echo "</head>";

    testata_html($_cosa, $_percorso);
    menu_tendina($_cosa, $_percorso);


    echo "<table align=\"left\" width=\"100%\">\n";
    echo "<tr><td valign=\"top\" align=\"center\">\n";
    echo "<h3 align=\"center\">Lista cose Da fare</h3>";
    
    
    $result = tabella_todo("elenco_completo", $_anno, $_numero, $_SESSION['user']['id'], $_data_end, $_completato, $_SESSION['user']['id']);
    
    echo "<table width=\"100%\" align=\"center\" border=\"0\" rules=\"cols\" cellpadding=\"0\" cellspacing=\"0\">\n";
    
    echo "<tr><td>Titolo</td><td>Anno</td><td>NR.</td><td>Data End</td><td>Ute Start</td><td>Ute. End</td><td>Pri</td><td>Compl.</td></tr>\n";
    foreach ($result AS $dati)
    {
        echo "<tr><td align=\"left\"><b><a href=\"todo.php?azione=modifica&anno=$dati[anno]&numero=$dati[numero]\">$dati[titolo]</b></a></font></td><td>$dati[anno]</td><td>$dati[numero]</td><td>$dati[data_end]</td><td>$dati[utente_start]</td><td>$dati[utente_end]</td><td>$dati[priorita]</td><td>$dati[completato]</td></td></tr>\n";
    }

    echo "</table>\n";
   echo "</td></tr></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>