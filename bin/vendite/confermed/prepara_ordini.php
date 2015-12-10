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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);



//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{
    echo "<table width=\"100%\">\n";
    echo "<tr>\n";

    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";

    echo "<h2>Programma Prepara Ordini</h2>\n";

    echo "<h3>File da caricare $NOME_FILECODBAR</h3>\n";
    echo "<h3>Il programma provvedere a estrarre dalle conferme <br>il materiale per farlo preparare</h3>\n";


    echo "<form action=\"prepara_documenti.php\" enctype=\"multipart/form-data\" method=\"post\">\n";


    #<!--settiamo la dimensione massima dei file in byte, nel nostro caso 1MB=1024000byte-->
    echo "<input name=\"MAX_FILE_SIZE\" type=\"hidden\" value=\"1024000\" />\n";
    #<!--campo per la scelta del file-->
    echo "<input id=\"file\" name=\"file\" type=\"file\" />\n";
    #<!--bottone di invio-->
    echo "<input name=\"submit\" type=\"submit\" value=\"Carica\" />\n";
    echo "</form>\n";
    echo "</center>";

    echo "</form>\n";

    echo "</td></tr>	</table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>