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

require $_percorso . "librerie/motore_doc_pdo.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{

//recupero varia/bili e sessioni
//tutte le variabili vengono passate con il POST

    $_tdoc = $_POST['tdoc'];
    $_ndoc = $_POST['ndoc'];
    $_anno = $_POST['anno'];
    $_suffix = $_POST['suffix'];

    $id = session_id();
    $_azione = $_POST['azione'];


//Seleziono il documento

    $_archivio = archivio_tdoc($_tdoc);

#echo $_tdoc;
//ritorna $_archivio['testacalce']
//$_archivio['dettaglio']
//
//controllo status documento..
// verifica su il documento selezionato � in uso..
//controllo status documento.
//la funzione restituisce prosegui o aspetta
// azione annulla documento....
    if ($_azione == "Annulla")
    {
        annulla_doc_vendite($_dove, $_tdoc, $_anno, $_suffix, $_ndoc);
        exit;
    }
    else
    {
        $_status = status_documento("modifica", $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc, "modificadoc.php", $_azione, $_status);
    }

    //echo $_fattura . "<br/>";

    if ($_status == "modifica")
    {

        $_risultato = modifica_documento($_cosa, $id, $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc);

        //verifichiamo il ritorno, se tutto ok apro le sessioni e passo a corpo altrimenti se cambia
        if ($_risultato['verifica'] == "tutto_ok")
        {

            $_codutente = $_risultato['utente'];


            //sostituisco la variabile tdoc, passata dal get con la variabile letta dal documento facendo così dovremo risolvere il problema
            //della seleziode del documento anche in fase di cancellazione..

            if ($_tdoc == "FATTURA")
            {
                $_tdoc = $_risultato['tdoc'];
            }


            //ottimo passiamo il tutto alla funzione corpo ed apriamo le sessioni di modifica:
            $_SESSION['utente'] = $_codutente;
            $_SESSION['tdoc'] = $_tdoc;
            $_SESSION['anno'] = $_anno;
            $_SESSION['suffix'] = $_suffix;
            $_SESSION['ndoc'] = $_ndoc;
            $_SESSION['calce'] = "calce2";
            //$_SESSION['status'] = "";

            if ($_tdoc == "fornitore")
            {
                $_SESSION['programma'] = "ACQUISTO";
                $dati = tabella_fornitori("singola", $_codutente, $_parametri);
            }
            elseif ($_tdoc == "ddtacq")
            {
                $_SESSION['programma'] = "DDT_ACQ";
                $dati = tabella_fornitori("singola", $_codutente, $_parametri);
            }
            else
            {

                $_SESSION['programma'] = "VENDITA";
                $dati = tabella_clienti("singola", $_codutente, $_parametri);
            }


            //IMPOSTO LA SESSIONE DELL 'UTENTE
            $_SESSION['datiutente'] = $dati;

            //visualizzo il corpo documento..



            intesta_html($_tdoc, "", $dati, "");
            //richiamo la funzione che mi mostra compilato il carrello
            mostra_carrello($_SESSION['programma'], $id, $_tdoc, $IVAMULTI, $ivasis);

            annulla_doc_vendite($_dove, $_tdoc, $_anno, $_suffix, $_ndoc);

            echo "<br></body></html>";
        }
        else
        {
            echo "<center><h3>Si &egrave; verificato un errore generale durante la modifica del documento</h3></center>";
        }
    }// fine funzione modifica documento

    if ($_status == "prosegui")
    {


        $_return = elimina_documento($_status, $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio);

        if ($_return == "tuttok")
        {
            echo "<center><h3><a href=\"../../index.php\">Premere qui! ritornare alla pagina principale</a></h3></center>";
        }
        elseif ($_return == "aspetta")
        {
            echo "<center><h3>Verifica Documento</h3></center>";
        }
        else
        {
            echo "<center><h3>Si &egrave; verificato un errore durante la cancellazione del documento</h3></center>";
        }
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>