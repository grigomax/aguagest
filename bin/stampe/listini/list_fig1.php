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



if ($_SESSION['user']['stampe'] > "1")
{
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";

    echo "<td align=\"left\">\n";

    echo "<table width=\"60%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";



    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
    echo "<span class=\"intestazione\"><br><b>Scegliere il Listino Da stampare</b><br></span><br>\n";
    echo "</td></tr>\n";


    printf("<form action=\"listino_pdf_figurato2.php\" target=\"sotto\" method=\"POST\">");

    $_anno = date("Y");
    echo "<tr><td align=center><br>";
    echo "<select name=\"listino\">\n";
    for ($_nlv = 1; $_nlv <= $nlv; $_nlv++)
    {
        printf("<option value=\"%s\">Listino prezzi %s </option>", $_nlv, $_nlv);
    }
    echo "</select>\n";
    echo "</td></tr>\n";


    // Stringa contenente la query di ricerca...
    $query = sprintf("select * from catmer order by catmer");

    $result = domanda_db("query", $query, $_cosa, $_ritorno, $_parametri);

    foreach ($result AS $dati)
    {
        echo "<tr><td align=left><input type=checkbox name=\"catmer[]\" value=\"$dati[codice]\"> $dati[catmer]</td></tr>\n";
    }


    //facciamo selezionare il logo per il listino..

    echo "<tr>\n";
    echo "<td valign=\"top\"><br>Seleziona il logo per la stampa..<select name=\"ST_LOGOG\">\n";
    echo "<option value=\"\"></option>";
    exec("ls ../../../setting/loghiazienda/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {

        echo "<option value=\"$val\">$val\n";
    }
    echo "</select>\n";




    echo "<tr><td align=\"left\"><br>";
    echo "<span class=\"intestazione\">Impaginazione</span><br>\n";
    echo "<input type=\"checkbox\" name=\"aggarticolo\" value=\"SI\"><span class=\"testo_blu\">Aggiornare la pagina catalogo nella anagrafica articoli ?<br>";
    echo "<input type=\"checkbox\" name=\"doppia\" value=\"SI\">Stampare doppia pagina per impaginazione A3 per la tipografia ?<br>";
    echo "<input type=\"checkbox\" name=\"aggpagina\" value=\"SI\">Aggiungere una pagina  per l'indice separato ?<br>";
    echo "<input type=\"checkbox\" name=\"ordine_cat\" value=\"SI\">Seguire il nuovo indice di stampa separato.. ?<br>";
    echo "</td></tr>\n";
    echo "<tr><td align=center><br>";

    echo "<select name=\"stampa\">\n";
    echo "<option value=\"listino_pdf_figurato\">Catalistino figurato per i clienti</option>";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Stampa\");>\n";
    echo "</form><br><br>\n";
    include "version.php";
    echo "</td>\n";

    echo "</td>\n</tr>\n";
    echo "</body></html>";

    $conn->null;
    $conn = null;
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>