<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
require $_percorso . "../setting/vars.php";
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

// aggiungiamo tutte le librerie necessarie
require $_percorso . "librerie/motore_primanota.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

#addslashes($str)

if ($_SESSION['user']['contabilita'] > "2")
{
// Inizio tabella pagina principale ----------------------------------------------------------


	echo "<table width=\"80%\" border=\"0\">\n";
	echo "<tr><td width=\"80%\" align=\"center\">";



// **********************************************************************
	

	echo "<table width=\"80%\" align=\"center\" border=\"0\">";
	
	echo "<h2><font color=\"blue\"><center>Verifica causali contabili</h2></font>\n";

// inserisci
	$_azione = $_POST['azione'];
	$_codice = $_POST['codice'];

//controllo campi.
	if (($_POST['codice'] == "") OR ($_POST['descrizione'] == ""))
	{
		echo "<h3> Attenzione alcuni campi obbligatori sono mancanti</h3>\n";
		exit;
	}



	if ($_azione == "Inserisci")
	{

//verifica codice esistente
		$result = tabella_causali_contabili("verifica", $_percorso, $_codice, $_parametri);

		if ($result['result'] == TRUE)
		{
			echo "<tr><td><b>Il codice scelto &egrave; gi&agrave; esistente nell'archivio.</td></tr>\n";
			echo "<tr><td>Fai indietro con il browser per non perdere i dati inseriti.<br> Poi cambia codice</td></tr>\n";
		}
		else
		{
			// inserimento causale
			//preparazione veriabili..
			$_parametri['descrizione'] = addslashes($_POST['descrizione']);
			$_parametri['conto_1'] = $_POST['conto_1'];
			$_parametri['conto_2'] = $_POST['conto_2'];
			$_parametri['conto_3'] = $_POST['conto_3'];
			$_parametri['conto_4'] = $_POST['conto_4'];
			$_parametri['conto_5'] = $_POST['conto_5'];
			$_parametri['conto_6'] = $_POST['conto_6'];
			$_parametri['conto_7'] = $_POST['conto_7'];
			$_parametri['conto_8'] = $_POST['conto_8'];
			$_parametri['conto_9'] = $_POST['conto_9'];
			$_parametri['conto_10'] = $_POST['conto_10'];

			$result = tabella_causali_contabili($_azione, $_percorso, $_codice, $_parametri);

			// Esegue la query...
			if ($result == FALSE)
			{
				echo "Si è verificato un errore nell'inserimento della causale<br>\n\"$result[errori]\"\n";
				exit;
			}
			else
			{
				echo "<tr><td>Codice Causale inserita correttamente</td></tr>\n";
			}
		}// fine graffa else
		
	}// fine graffa funzione


	if ($_azione == "Aggiorna")
	{

		//aggiorniamo la causale
			//preparazione veriabili..
			$_parametri['descrizione'] = addslashes($_POST['descrizione']);
			$_parametri['conto_1'] = $_POST['conto_1'];
			$_parametri['conto_2'] = $_POST['conto_2'];
			$_parametri['conto_3'] = $_POST['conto_3'];
			$_parametri['conto_4'] = $_POST['conto_4'];
			$_parametri['conto_5'] = $_POST['conto_5'];
			$_parametri['conto_6'] = $_POST['conto_6'];
			$_parametri['conto_7'] = $_POST['conto_7'];
			$_parametri['conto_8'] = $_POST['conto_8'];
			$_parametri['conto_9'] = $_POST['conto_9'];
			$_parametri['conto_10'] = $_POST['conto_10'];

			$result = tabella_causali_contabili($_azione, $_percorso, $_codice, $_parametri);

			// Esegue la query...
			if ($result == FALSE)
			{
				echo "Si è verificato un errore nella modifica della causale<br>\n\"$result[errori]\"\n";
				exit;
			}
			else
			{
				echo "<tr><td>Causale modificata correttamente</td></tr>\n";
			}
		

// graffa di fine funzione aggiornamento
	}

	if ($_azione == "Elimina")
	{

		$result = tabella_causali_contabili($_azione, $_percorso, $_codice, $_parametri);
		
		if ($result == FALSE)
			{
				echo "Si è verificato un errore nella Elimina della causale<br>\n\"$result[errori]\"\n";
				exit;
			}
			else
			{
				echo "<tr><td>Causale Eliminata correttamente</td></tr>\n";
			}
	}

	echo "</td></tr></table>";
	
	//chiudiamo le connessioni
	$conn->null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>