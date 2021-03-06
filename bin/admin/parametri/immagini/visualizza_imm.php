<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
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



if ($_SESSION['user']['setting'] > "1")
{

    if ($_GET['azione'] == "")
    {
        $_azione = $_POST['azione'];
        $_file = $_POST['file'];
        $_orfile = $_POST['orfile'];
    }
    else
    {
        $_azione = $_GET['azione'];
        $_file = $_GET['file'];
    }

    echo "<table width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\" width=\"80%\" valign=\"top\">\n";

    if ($_azione == "Elimina")
    {
        //elinazione file..

        unlink("../../../../setting/loghiazienda/$_POST[file]");

        echo "<h3>File Eliminato con successo... !</h3>\n";



        echo "Aggiornamento dabase eseguito<br>";
    }
    else
    {
        if ($_azione == "Rinomina")
        {
            //rinomina file...
            echo "<h3>Rinominazione File.. </h3>\n";

            rename("../../../../setting/loghiazienda/$_orfile", "../../../../setting/loghiazienda/$_file");

            echo "<h3>File Rinominato con Successo</h3>\n";

            echo "<h3>Aggiornamento database...</h3>\n";
        }


        echo "<form action=\"visualizza_imm.php\" method=\"post\">\n";
        echo "<h3>Cosa desideri fare con questa immagine.. ?</h3>";
        echo "<input type=\"radio\" name=\"orfile\" value=\"$_file\" checked>$_file <br>";
        echo "<input type=\"text\" name=\"file\" value=\"$_file\" size=\"50\" maxlength=\"50\" ><br>";
        echo "<input type=\"submit\" name=\"azione\" value=\"Elimina\" onclick=\"if(!confirm('Sicuro di voler Eliminare L immagine ?')) return false;\"> Oppure <input type=\"submit\" name=\"azione\" value=\"Rinomina\">\n";
//visualizziamo l'immagine e chiediamo se la si vuole cancellare rinominare oppure se è tutto ok..
        echo "</form>\n";

        echo "<p style='text-align:center;'><img src=\"../../../../setting/loghiazienda/$_file\" >";

        echo "</table>";
// ************************************************************************************** -->
    }



    echo "</td></tr></table>\n";
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>