<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
require $_percorso ."../setting/vars.php";
session_start(); $_SESSION['keepalive']++;
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


if ($_SESSION['user']['anagrafiche'] > "1")
{
    
    $_tipo = $_GET['tipo'];
// Inizio tabella pagina principale ----------------------------------------------------------

    echo "<table border=\"0\" width=\"80%\" align=\"center\"><tr><td align=\"center\">";
    echo "<h2> Selezionare il file <font color=\"green\">$_tipo </font>da caricare nella cartella imm-art</h2>\n";
    echo "<h4>Massima grandezza del file 16 MB 16777216 byte</h4>\n";
    echo "<h4>Tutte le immagini saranno in jpg e convertite in automatico a 500px per 500px</h4>\n";
    echo "<h4>In caso dello tesso nome il file verr&agrave; sovrascritto senza preavvisi.</h4>\n";

#<!--apriamo il form e specifichiamo il tipo di dati e il metodo di invio-->
    echo "<form action=\"carica_imm.php\" enctype=\"multipart/form-data\" method=\"post\">\n";
#<!--settiamo la dimensione massima dei file in byte, nel nostro caso 1MB=1024000byte-->
    echo "<input name=\"MAX_FILE_SIZE\" type=\"hidden\" value=\"16777216\" />\n";
    echo "<input name=\"tipo\" type=\"hidden\" value=\"$_tipo\" />\n";
    echo "File da caricare: <br>\n";
#<!--campo per la scelta del file-->
    echo "<input size=\"50\" id=\"file\" name=\"file\" type=\"file\"  />\n";
#<!--bottone di invio-->
    echo "<input name=\"azione\" type=\"submit\" value=\"Carica\" />\n";
    echo "</form>\n";

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