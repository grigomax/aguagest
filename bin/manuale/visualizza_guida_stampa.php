<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
        base_html_stampa("chiudi", $_parametri);



if ($_SESSION['user']['stampe'] > "1")
{
    echo "<table align=\"center\" width=\"80%\" border=\"0\">\n";
    
    echo "<tr><td>\n";
    $_file = $_GET['file'];
    
    $_contenuto = file_get_contents("contenuti/$_file");
    
    echo stripslashes($_contenuto);
    
    echo "</td></tr></table>\n";
    
    
    
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>