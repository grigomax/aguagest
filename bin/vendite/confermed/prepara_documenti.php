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

//ora dobbiamo chiarire una cosa..
//Passiamo tutto alle librerie nuove direttamente in pdo e le chiamiamo 
//motore doc_2.php
require "../../librerie/motore_anagrafiche.php";
require "../../librerie/motore_doc_pdo.php";


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

    echo "<h2> Elaborazione file.. </h2>\n";

    //gestiamo il caricamento del file..
    //controlliamo che il file rispetti le dimensioni impostate
    if ($_FILES["file"]["size"] < 1024000)
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
            //	echo "Tipo File: " . $_FILES["file"]["type"] . "<br>";
            //la dimensione in byte
            echo "Dimensione [byte]: " . $_FILES["file"]["size"] . "<br>";
            //il nome del file temporaneo
            //	echo "Nome Temporaneo: " . $_FILES["file"]["tmp_name"] . "<br>";
            //controllo se il file esiste già sul server
            //sposto il file caricato dalla cartella temporanea alla destinazione finale
            move_uploaded_file($_FILES["file"]["tmp_name"], "../../../spool/" . $_FILES["file"]["name"]);
            //	echo "File caricato in: " . "../../../upload/" . $_FILES["file"]["name"];
        }
    }
    else
    {
        echo "File troppo grande!!";
        exit;
    }

    //Ora che il file è caricato lo elaboriamo

    if (file_exists("../../../spool/" . $NOME_FILECODBAR))
    {

        $_archivio['testacalce'] = "co_testacalce";

        $news = fopen("../../../spool/$NOME_FILECODBAR", "r"); //apre il file
        $_a = 0;
        while (!feof($news))
        {
            $buffer = fgets($news, 4096);
            $_a = $_a + 1;
            //    echo "Riga n. $_a valore = $buffer <BR>"; //riga letta
            $_arre[$_a] = $buffer;
        }

//echo "riga A = $_a <br>";
//nuova riga
        $_a = $_a - $RIGHE_FILECODBAR;

        //azzeriamo gli anni e ndoc
        $_ndoc = "";
        $_anno = "";

        for ($_b = 1; $_b <= $_a; $_b++)
        {
            //la stringa da dividere
            $stringa = $_arre[$_b];
            //il separatore
            $separatore = "|";
            //explode
            $suddivisa = explode($separatore, $stringa);
            $_documento = substr($suddivisa[0], '0', '1');

            #echo $_documento;
            //verifichiamo se la prima cosa è un punto..
            if ($_documento == ".")
            {
                $_ordine = explode(".", $suddivisa[0]);

                //qui prendiamo il numero ordine

                echo "Conferma Ordine anno n. $_ordine[1]  numero $_ordine[2]  <br>\n";

                $_anno = $_ordine['1'];
                $_ndoc = $_ordine['2'];
                //select dal dettablio del documento..

                echo "Modifica Status Ordine.. <br>";

                $result_stat = status_documento("cambia", $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc, $_form_action, $_azione, "parziale");

                if ($result['errori'] != "OK")
                {
                    echo $result['descrizione'];
                }
                else
                {
                    echo "Documento aggiornato correttamente <br>\n";
                }
            }
            else
            {

                //qui elaboriamo il file..

                echo "Estrazione riga..\n";
                echo "Codice $suddivisa[0] Qta  $suddivisa[1]  <br>\n";


                //dobbiamo prima prenderci il codice a barre dalla anagrafica..

                $_articolo = tabella_barcode("singola", $suddivisa[0], $_articolo, $_rigo);

                #echo $_articolo;
                // sommiamo la riga evasa a quella precedente..
                //update riga
                if ($suddivisa[0] != "")
                {
                    $result = tabella_co_dettaglio("aggiorna_estratta", $_anno, $_ndoc, $_rigo, $_status, $_articolo, $suddivisa['1']);

                    if ($result['errori'] != "OK")
                    {
                        echo $result['descrizione'];
                    }
                    else
                    {
                        echo "Riga aggiornata correttamente <br>\n";
                    }
                }
            }
        }

        fclose($news); #chiude il file

        echo "<h3>Elaborazione Del file Eseguita..</h3>\n";
    }
    else
    {
        echo "<h3>File non trovato $NOME_FILECODBAR</h3>\n";
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>