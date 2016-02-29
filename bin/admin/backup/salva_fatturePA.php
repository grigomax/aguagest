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
//programma che serve ad salvare tutte le impostazioni di sistema..
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

// --no-data=TRUE
if ($_SESSION['user']['setting'] > "3")
{
    
    $zip = new ZipArchive();
    $filename = "AGFPA_aguagest_fatturePA_" . date("Ymd") . ".zip";
    
    //rimuoviamo il vecchio se cè
    
    if (file_exists("../../../spool/$filename"))
    {
        unlink("../../../spool/$filename");
    }


    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Content-Transfer-Encoding: binary');
    
    if ($zip->open("../../../spool/$filename", ZipArchive::CREATE) !== TRUE)
    {
        exit("cannot open <$filename>\n");
    }
    
    $options = array('add_path' => '/', 'remove_all_path' => TRUE);
    $zip->addGlob("../../../setting/fatture_PA/*.*", GLOB_BRACE, $options);
    
//    echo "numfiles: " . $zip->numFiles . "\n";
//    echo "status:" . $zip->status . "\n";
    $zip->close();
    header("Content-Length: " . filesize("../../../spool/$backupFile"));
    readfile("../../../spool/$filename");



    echo "<h1 align=\"center\"> Copie eseguite si può chiuedere la finestra.. </h1>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>