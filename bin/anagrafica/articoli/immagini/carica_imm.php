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

session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

    $_azione = $_POST['azione'];

    echo "<table border=\"0\" width=\"80%\" ><tr><td align=\"center\">";

    if ($_azione == "Elimina")
    {
        //elinazione file..

        unlink("../../../../imm-art/$_POST[file]");

        echo "<h3>File Eliminato con successo... !</h3>\n";
    }
    else
    {
        if ($_azione == "Carica")
        {
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
                    move_uploaded_file($_FILES["file"]["tmp_name"], "../../../../imm-art/" . $_FILES["file"]["name"]);
                    echo "File caricato nella cartella immagini   " . $_FILES["file"]["name"];
                }
            }
            else
            {
                echo "File troppo grande!!";
                exit;
            }

            echo "<br>Inizio conversione immagine.. ";
//qui iniziamo ad alaborare il file... per ridimensionarlo a 500 per 500 pixel..
// Ottengo le informazioni sull'immagine originale
            list($width, $height, $type, $attr) = getimagesize('../../../../imm-art/' . $_FILES['file']['name']);

//se l'immagine ' è più grande inizio a convertirla.
//
            if (($width > "500") OR ( $height > "500"))
            {
                //converto l'immagine cercando di mantenere il rapporto..

                if ($width > "500")
                {
                    $_fattore = ($width / "500");
                    $_width = ($width / $_fattore);
                    $_height = ($height / $_fattore);
                }

                if ($height > "500")
                {
                    $_fattore = ($height / "500");
                    $_width = ($width / $_fattore);
                    $_height = ($height / $_fattore);
                }


                // Creo la versione 120*90 dell'immagine (thumbnail)
                $thumb = imagecreatetruecolor($_width, $_height);
                $source = imagecreatefromjpeg('../../../../imm-art/' . $_FILES['file']['name']);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $_width, $_height, $width, $height);

                // Salvo l'immagine ridimensionata
                imagejpeg($thumb, '../../../../imm-art/' . $_FILES['file']['name'], 90);
            }

            $_file = $_FILES['file']['name'];
        }
        else
        {
            //rinomina file...

            rename("../../../../imm-art/$_POST[orfile]", "../../../../imm-art/$_POST[file]");

            $_file = $_POST['file'];

            echo "File Rinominato con Successo";
        }
        echo "<form action=\"carica_imm.php\" method=\"post\">\n";
        echo "<h3>Cosa desideri fare con questa immagine.. ?</h3>";
        echo "<input type=\"radio\" name=\"orfile\" value=\"$_file\" checked>" . $_FILES['file']['name'] . " <br>";
        echo "<input type=\"text\" name=\"file\" value=\"$_file\" size=\"50\" maxlength=\"50\" ><br>";
        echo "<input type=\"submit\" name=\"azione\" value=\"Elimina\" onclick=\"if(!confirm('Sicuro di voler Eliminare L immagine ?')) return false;\"> Oppure <input type=\"submit\" name=\"azione\" value=\"Rinomina\">\n";
//visualizziamo l'immagine e chiediamo se la si vuole cancellare rinominare oppure se è tutto ok..
        echo "</form>\n";

        echo "<p style='text-align:center;'><img src=\"../../../../imm-art/$_file\" >";

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