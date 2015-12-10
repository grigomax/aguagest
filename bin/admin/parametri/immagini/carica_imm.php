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



if ($_SESSION['user']['setting'] > "2")
{


    $_azione = $_POST['azione'];

    echo "<table width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\" width=\"80%\" valign=\"top\">\n";


    if (chmod("../../../../setting/loghiazienda/.", 0755))
    {
        echo "va..";
    }
    else
    {
        echo "non va";
    }


//controlliamo che il file rispetti le dimensioni impostate
    if ($_FILES["file"]["size"] < "16777216")
    {
        //controlliamo se ci sono stati errori durante l'upload
        if ($_FILES["file"]["error"] > 0)
        {
            echo "Codice Errore: " . $_FILES["file"]["error"] . "";
        }
        else
        {
            //stampo alcune informazioni sul file
            //il nome originale
            echo "Nome File: " . $_FILES["file"]["name"] . "<br>";
            //il mime-type
            echo "Tipo File: " . $_FILES["file"]["type"] . "<br>";

            // Ottengo le informazioni sull'immagine
            list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);

            // Controllo che il file sia in uno dei formati GIF, JPG o PNG
            //if (($type!=1) && ($type!=2) && ($type!=3))
            if (($type != 1) && ($type != 2) && ($type != 3))
            {

                echo "<br><h3>ATTENZIONE il file caricato non &egrave; tipo JPEG</h3>\n";
                echo "<br><h3>Operazione Abortita si prega di tornare indietro</h3>\n";
                exit;
            }

            //la dimensione in byte
            echo "Dimensione [byte]: " . $_FILES["file"]["size"] . "<br>";
            //il nome del file temporaneo
            //	echo "Nome Temporaneo: " . $_FILES["file"]["tmp_name"] . "<br>";
            //controllo se il file esiste già sul server
            //sposto il file caricato dalla cartella temporanea alla destinazione finale
            if (move_uploaded_file($_FILES["file"]["tmp_name"], "../../../../setting/loghiazienda/" . $_FILES["file"]["name"]))
            {
                echo "File caricato nella cartella immagini   " . $_FILES["file"]["name"];
                $_file = $_FILES['file']['name'];
            }
            else
            {
                echo "Errore";
            }
        }
    }
    else
    {
        echo "File troppo grande!!";
        exit;
    }


    echo "<form action=\"carica_imm.php\" method=\"post\">\n";
    echo "<h3>Cosa desideri fare con questa immagine.. ?</h3>";
    echo "<input type=\"radio\" name=\"orfile\" value=\"$_file\" checked>" . $_FILES['file']['name'] . " <br>";
    echo "<input type=\"text\" name=\"file\" value=\"$_file\" size=\"50\" maxlength=\"50\" ><br>";
    echo "<input type=\"submit\" name=\"azione\" value=\"Elimina\" onclick=\"if(!confirm('Sicuro di voler Eliminare L immagine ?')) return false;\"> Oppure <input type=\"submit\" name=\"azione\" value=\"Rinomina\">\n";
//visualizziamo l'immagine e chiediamo se la si vuole cancellare rinominare oppure se è tutto ok..
    echo "</form>\n";

    echo "<p style='text-align:center;'><img src=\"../../../../setting/loghiazienda/$_file\" >";

    echo "</table>";




    echo "</td></tr></table>\n";
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>