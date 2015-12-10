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

require "../../librerie/motore_anagrafiche.php";
require "../../librerie/motore_doc_pdo.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

// prendiamo i post in arrivo

	if ($_POST['utente'] != null)
	{
		$_SESSION['utente'] = $_POST['utente'];
                $_SESSION['suffix'] = $_POST['suffix'];
		$_codutente = $_POST['utente'];
		$_tdoc = $_POST['tipodoc'];
		$_SESSION['tdoc'] = $_tdoc;
		@$_forza = $_GET['forza'];

		if ($_tdoc == "fornitore")
		{
			$_SESSION['programma'] = "ACQUISTO";
		}
		elseif ($_tdoc == "ddtacq")
		{
			$_SESSION['programma'] = "DDT_ACQ";
		}
		else
		{
			$_SESSION['programma'] = "VENDITA";
		}
	}
	elseif ($_SESSION['utente'] != "")
	{
		$_codutente = $_SESSION['utente'];
                
		$_tdoc = $_SESSION['tdoc'];
		$_forza = $_GET['forza'];

		if ($_tdoc == "fornitore")
		{
			$_SESSION['programma'] = "ACQUISTO";
		}
		elseif ($_tdoc == "ddtacq")
		{
			$_SESSION['programma'] = "DDT_ACQ";
		}
		else
		{
			$_SESSION['programma'] = "VENDITA";
		}
	}
	else
	{
		#faccio questo controllo per inpedire di fare un documento vuoto.. !
		echo "<h1>Attenzione nessun utente selezionato</h1>";
		exit;
	}


	if (($_SESSION['programma'] == "ACQUISTO") OR ($_SESSION['programma'] == "DDT_ACQ"))
	{
		$dati = tabella_fornitori("singola", $_codutente, $_parametri);
	}
	else
	{
		$dati = tabella_clienti("singola", $_codutente, $_parametri);
	}

//fissiamo una sessione con i dati dell'utenza selezionata 
	$_SESSION['datiutente'] = $dati;

//facciamo apparire la schermata
	intesta_html($_tdoc, "", $dati, "");

//controlliamo l'utente
// in caso di blocco di inchioda tutto.
	@blocco_utente($dati);

//verifichiamo la privacy:
	if ($_forza == "")
	{
		privacy($dati);
	}

// controlliamo se il cliente ha qualche documento inevaso
	$_righe = documenti_inevasi($_codutente, $_tdoc);

	if ($_righe < 1)
	{
		if ($_SESSION['programma'] == "ACQUISTO")
		{
			schermata_seleziona("ord_for", $_tdoc);
		}
		elseif ($_SESSION['programma'] == "DDT_ACQ")
		{
			schermata_seleziona("ddt_acq", $_tdoc);
		}
		else
		{
			schermata_seleziona("vendita", $_tdoc);
		}
	}

	printf("<form action=\"annulladoc.php\" method=\"POST\">");
	printf("<table><tr><td colspan=\"10 \" align=\"center\" class=\"testo_blu\"><br>Per annullare l'operazione  <input type=\"submit\" name=\"azione\" value=\"annulla\"></form></td>");
	printf("</tr>");
	echo "</table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>