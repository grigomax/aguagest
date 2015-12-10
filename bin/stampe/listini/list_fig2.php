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
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//creiamo una directory se non esistente dove mettere i pdf

if (!file_exists($_percorso . "../spool/listino"))
{
	if (mkdir($_percorso . "../spool/listino", 0755))
	{
		echo("<br>Directory creata!<br>");
		$fine = "1";
	}
	else
	{
		echo("<br>Non posso creare la directory! $_percorso.../spool/listino<br>Contattare l'amministratore");
		$fine = "0";
		exit;
	}
}
//Cambio le variabili e le faccio vedere

$_catmer = $_POST['catmer'];

foreach ($_catmer as $value)
{
	$_catmer1 = " OR catmer='$value'";
	$_catmer2 = "$_catmer2 $_catmer1";
}

$_catmer = ltrim($_catmer2, " OR ");

$_listino = $_POST['listino'];
$_tipo = $_POST['stampa'];
$_listino = $_POST['listino'];
$_aggpagina = $_POST['aggpagina'];
$_aggarticolo = $_POST['aggarticolo'];
$_doppia = $_POST['doppia'];
$_ordine_cat = $_POST['ordine_cat'];

$_nomelist = "Listino Prezzi N. $_listino";

include "listino_pdf_figurato2.php";
?>