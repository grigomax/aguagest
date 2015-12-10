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

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['magazzino'] > "1")
{

    $_azione = $_POST['azione'];

    if ($_azione == "Prosegui")
    {

//recupero i post

	$_nfattura = $_POST['codini'];
	$_tdocevaso = $_POST['tdocevaso'];
	$_evasonum = $_POST['ndoc'];
	$_evasoanno = $_POST['annodoc'];
	$_anno = $_POST['anno'];

// eseguo l'aggiornamento dell'archivio fatture


	$query = sprintf("UPDATE bv_bolle SET tdocevaso=\"%s\", evasonum=\"%s\", evasoanno=\"%s\", status='evaso' where ndoc=\"%s\" and anno=\"%s\"", $_tdocevaso, $_evasonum, $_evasoanno, $_nfattura, $_anno);

	// Esegue la query...
	if ($res = mysql_query($query, $conn) != 1)
	{
	    echo "<center> Errore nell' aggiornamento del documento<br>";

	    echo $query;
	    
	    unset($_SESSION['cliente']);
	}
	else
	{
	    echo "<center><b>Documento aggiornato perfettamente</a><br>";
	    echo "<a href=\"importa_bv.php\">Permi qui per chiudere un'altro documento</a>\n";
	    unset($_SESSION['cliente']);
	}
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>