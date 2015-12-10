<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso ."../setting/vars.php";
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);


//Cambio le variabili e le faccio vedere
$_listino = $_POST['listino'];
$_tipo = $_POST['stampa'];
//echo $_POST['ccodini'];
//echo $_POST['ccodfin'];

	if ($_POST['ccodini'] != 0 ) {
	   $_codini = $_POST['ccodini'];
	   $_codfin = $_POST['ccodfin'];
		}
	else {
		$_codini = $_POST['codini'];
		$_codfin = $_POST['codfin'];
		}

$_listino = $_POST['listino'];
$_sconto = $_POST['sconto'];


$_nomelist = "Listino Prezzi N. $_listino";

	$query = sprintf( "select articolo, substring(descrizione,1,50) AS descrizione, substring(desrid,1,30) AS desrid, unita, listino, disponibilita from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where rigo=\"%s\" and articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_listino, $_codini, $_codfin );

	include ("$_tipo.php");


?>