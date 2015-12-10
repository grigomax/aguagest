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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require $_percorso . "librerie/motore_doc_pdo.php";


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);


$_azione = $_POST['azione'];
$id = session_id();
$_numero = $_SESSION['datidoc']['numero'];
$_cosa = $_GET['cosa'];

#Prendiamo eventuali get

$_tdoc = $_GET['tdoc'];
$_anno = $_GET['anno'];
$_ndoc = $_GET['ndoc'];
$id = session_id();



if ($_POST['azione'] == "")
{
	$_azione = "annulla";
}


if ($_azione == "annulla")
{
	printf("<form action=\"annulladoc.php?cosa=$_cosa&tdoc=$_tdoc&anno=$_anno&ndoc=$_ndoc\" method=\"POST\">");
	printf("<p align=\"center\" class=\"testo_blu\">Sei sicuro di Abbandonare il documento ? <BR> $_tdoc NR. $_ndoc del $_anno<br>
            <input type=\"submit\" name=\"azione\" value=\"Abbandona\"> - <A HREF=\"#\" onClick=\"history.back()\">Torna</A> </form>");

	exit;
}


if ($_azione == "Abbandona")
{
	menu_tendina($_cosa, $_percorso);


	$_archivio = archivio_tdoc($_tdoc);



	//svuotiamo il cestino
	//
	$_errori = tabella_doc_basket("azzera_sessione", $id, $_rigo, $_anno, $_ndoc, $_utente, $_articolo, $_parametri);

	if ($_errori != "OK")
	{
		echo $_errori['descrizione'];
	}

	if ($_tdoc != "")
	{

// setto lo status dei documenti a modificato..
		if ($_cosa == "parziale")
		{
			foreach ($_numero as $_ndoc_start)
			{
				$_errori = status_documento("cambia", $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc_start, $_form_action, $_azione, $_SESSION['status'][$_ndoc_start]);
				//funzione che mi permette di modificare il documento e mi inserisce il tutto nel carrello

				if ($_errori != "OK")
				{
					echo $_errori['descrizione'];
				}
			}
		}
		else
		{
			if($_SESSION['status'][$_ndoc] == "parziale")
			{
				$_status = $_SESSION['status'][$_ndoc];
			}
			else
			{
				$_status = "modificato";
			}
			$_errori = status_documento("cambia", $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc, $_form_action, $_azione, $_status);
			//funzione che mi permette di modificare il documento e mi inserisce il tutto nel carrello

			if ($_errori != "OK")
			{
				echo $_errori['descrizione'];
			}
		}
	}

//chiudiamo tutte le sessioni..

	chiudi_sessioni();

	echo "<center><h2>Operazione Annullata con successo</h2></center>";
	echo "<center><h3><a href=\"../../index.php\">Premere qui! ritornare alla pagina principale</a></h3></center>";
}
?>