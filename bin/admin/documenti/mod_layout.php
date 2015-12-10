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
require $_percorso . "librerie/motore_anagrafiche.php";

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
    echo "<table width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\" width=\"80%\" valign=\"top\">\n";
    echo "<span class=\"intestazione\"><br><b>Aspetto formato documenti</b></span><br>\n";
    echo "<span class=\"intestazione\"><br><b>Scegliere il documento da modificare</b></span><br>\n";
    echo "<form action=\"layout.php\" method=\"GET\">\n";

    //selezioniamo il tipo di etichette richiamando le etichette

    $etichetta = tabella_stampe_layout("elenco", $_percorso, $_tdoc);

    //echo "Selezionare il tipo di etichetta da stampare<br>";
    echo "<br><br><select name=\"tdoc\">\n";

    foreach ($etichetta AS $dati_eti)
    {
        echo "<option value=\"$dati_eti[tdoc]\">$dati_eti[ST_NDOC]</option>";
    }
    echo "</select>\n";
    echo "<br><br><br>\n";

    echo "<input type=\"submit\" name=\"azione\" value=\"Modifica\">\n";

    echo "<br><br>Oppure<br>\n";
    echo "Nel Caso si voglia creare una etichetta nuova o un listino.. <br>\n";
    echo "<input type=\"checkbox\" name=\"eti\" value=\"SI\"> <- Barrare in caso di etichetta</input><br>\n";
    echo "<input type=\"checkbox\" name=\"lis\" value=\"SI\"> <- Barrare in caso di un listino </input><br>\n";
    echo "<br><input type=\"submit\" name=\"azione\" value=\"Nuovo\">\n";
    echo "</td></tr>\n";

    echo "</form>\n";
    echo "</td></tr></table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>