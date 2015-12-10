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
require "../../librerie/motore_anagrafiche.php";


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

function svuota_cartella($dirpath) {
  $handle = opendir($dirpath);
  while (($file = readdir($handle)) !== false) {
    echo "Cancellato: " . $file . "<br/>";
    @unlink($dirpath . $file);
  }
  closedir($handle);
}
 
// esempio di utilizzo:
// svuotiamo la cartella dell'utente "pippo"
svuota_cartella("../../../spool/");

    echo " Se non appaiono errori a video la ricostruzione dei muovimenti è andata a buon fine<br>";
   
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
