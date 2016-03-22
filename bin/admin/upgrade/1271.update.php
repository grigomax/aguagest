<?php
session_start();
$_SESSION['keepalive'] ++;
//carichiamo la base del programma includendo i file minimi
//prima di tutto bisogna verificare l'installazione..
$_percorso = "../../";
//siccome il programma è di aggiornamento non sappiamo se il file di configurazione sia in directory
//report oppure setting.. quindi vediamo se c'è lo in cludiamo altrimenti includiamo un'altro..

require_once "../../../setting/vars.php";
require_once "../../librerie/lib_html.php";


base_html("chiudi", $_percorso);
/*
  File di aggiornamento programma fisico..
 * eliminiamo un po di files che non servono più
 * come le stampe inventario ecc..
 * Ma prima dobbiamo verificare che ci siano i permessi di scritura
 */

//versione file 1.2.7-1

//Spostamento cartella immagini all'interno della cartella setting
//creazione della cartella /setting/fattura_acq/

//includiamo il per il lavoro della cartella..

$_percorso = "../../";
require_once $_percorso . "tools/librerie_files.php";


//creiamo la directru

gestisci_directory("crea", "../setting/fatture_acq", "verbose");

copia_tutto("../../../imm-art", "../../../setting/imm-art");

svuota_cartella("../../../imm-art");

//creiamo la nuova cartella 

gestisci_directory("crea", "../setting/imm-art/prestazioni", "verbose");





?>