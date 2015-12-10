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

require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_anagrafiche.php";
//carichiamo la base delle pagine:
base_html($_cosa, $_percorso);
java_script($_cosa, $_percorso);
jquery_datapicker($_cosa, $_percorso);

echo "</head>";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{


    $_azione = $_GET['azione'];


    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">";
    echo "<tr>";

    $_datanuova = cambio_data("listamesi", $_data);

    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";

    echo "<h3>Liquidazione iva Annuale</h3>";

    echo "<h4>Il programma effettua una serie di conti e poi porta i dati in una nuova tabella<br>
    in una nuova finestra, si può decidere di stamparla oppura salvarla in PDF </h4>\n";
    echo "<h4>L'operazione può essere effettuata tutte le volte che si vuole<br> in quanto non viene riportata da nessuna parte</h4>\n";

    echo "<form action=\"liquid_iva_annuale_2.php\" method=\"POST\" target=\"_blank\">\n";

    //leggiamo gli anni presenti in contabilità

    $query = "SELECT anno FROM prima_nota GROUP BY anno ORDER BY anno DESC";

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }




    echo "Scegliere l'anno dall'archivio prima nota.\n";

    echo "<select name=\"anno\" width=\"60px\">\n";

    foreach ($result AS $dati)
    {
        echo "<option value=\"$dati[anno]\">$dati[anno]</option>\n";
    }

    echo "</select>\n";

    echo "<br><br>\n";
    echo "<CENTER><input type=\"submit\" name=\"azione\" value=\"Stampa\">\n";

    echo "<br>\n";
    echo "<br>\n";
    echo "<h5>Oppure stampa tabella liquidazioni mensili IVA</h5>\n";


    echo "<CENTER><input type=\"submit\" name=\"azione\" value=\"Stampa_liquid\">\n";
    echo "</form>\n";

    echo "</td></tr></table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>