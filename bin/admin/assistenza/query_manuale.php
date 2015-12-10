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
require "../../librerie/motore_anagrafiche.php";


//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{
    echo "<form action=\"query_manuale_2.php\" METHOD=\"post\">\n";

    echo "<h2 align=\"center\"> Inserisci query manuale da inviare al database</h2>\n";
    echo "<center>\n";
    
    echo "<textarea rows=\"4\" cols=\"150\" name=\"query\">Scrivi la query</textarea>\n";
   
    echo "<h2 align=\"center\"> Attenzione non viene fatto nessun controllo sulla query</h2>\n";
    
    echo "<input type=\"submit\" name=\"azione\" value=\"query\"> - <input type=\"submit\" name=\"azione\" value=\"exec\">\n";
    
    echo "</form>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
