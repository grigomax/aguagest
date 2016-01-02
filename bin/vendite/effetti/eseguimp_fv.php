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

if ($_SESSION['user']['vendite'] > "1")
{

    $_azione = $_POST['azione'];

//recupero i post

        
        //estraiamo i documenti dalla variabile unita..
        $_suffix = substr($_POST['codini'], "0", "1");
        $_ndoc = substr($_POST['codini'], "1", "11");
        
        
        //echo "<br>$_suffix\n";
        //echo "<br>$_ndoc\n";
        
        
        
        $_tdocevaso = $_POST['tdocevaso'];
        $_evasonum = $_POST['ndoc'];
        $_evasoanno = $_POST['annodoc'];
        $_anno = $_POST['anno'];

// eseguo l'aggiornamento dell'archivio fatture


        $query = "UPDATE fv_testacalce SET tdocevaso='$_tdocevaso', evasonum='$_evasonum', evasoanno='$_evasoanno', status='evaso' where ndoc='$_ndoc' and anno='$_anno' AND suffix='$_suffix'";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);


            echo "<center> Errore nell' aggiornamento della fattura..<br>";
        }
        else
        {
            echo "<center><b>Documento aggiornato perfettamente</a><br>";
            echo "<a href=\"importa_fv.php\">Permi qui per inserire un'altro cliente</a>\n";
        }

}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>