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


//carico il motore anagrafiche
require_once $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{


// Inizio tabella pagina principale ----------------------------------------------------------

	echo "<table align=\"center\" width=\"100%\">\n";
	echo "<tr><td width=\"100%\" valign=TOP>";

// *************************************************************************************************************
	echo "<span class=\"testo_blu\"><font size=\"3\"><h2 align=\"center\">Verifica Articolo</h2></span></font>";

	echo "<table align=\"center\" width=\"80%\" border=\"0\">";


//controllo se la variabile post è piena altrimenti mi prendo il get

	
	//pulisco eventuali spazi vuoti..
	
	if ($_GET['azione'] != "")
	{
		$_azione = $_GET['azione'];
		$_articolo = trim($_GET['codice']);
	}
	else
	{
		$_articolo = trim($_POST['articolo']);
		$_azione = $_POST['azione'];
	}

	if ($_azione == "Inserisci")
	{

		//controllo campi articolo..
		if (($_articolo == "") OR ($_POST['descrizione'] == ""))
		{
			echo "<tr><td><b>L'articolo inserito Contiene degli errori </td></tr>\n";
			echo "<tr><td><b>Codice articolo mancante o descrizione</td></tr>\n";
			exit(0);
		}

		if (tabella_articoli("check", $_articolo, $_parametri) == true)
		{
			// Se l'articolo esiste già...
			echo "<tr><td><b>L'articolo inserito &egrave; gi&agrave; esistente nel magazino.</td></tr>\n";

			exit(0);
		}

		$dati = tabella_articoli($_azione, $_articolo, $_POST);

		if ($dati['errori'] != "")
		{
			// Inizio tabella pagina principale ----------------------------------------------------------
			echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
			// includo la barra di navigazione
		
			echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $dati[errori]</h3></span>";
			echo "<center><h2><br> Errore inserimento articolo si prega Verificare</h2>\n";
			echo "<center><h2><br> Errore errore Registrato</h2>\n";
			exit;
		}
		else
		{
			//se tutto ok inserisco i prezzi nei listini
			// inserisco i prezzi articoli nel listino

			echo "<tr><td align=\"left\"> Articolo $_articolo inserito in anagrafica..</td></tr>\n";
			
			$_errori = tabella_listini($_azione, $_articolo, $_nlv, $_POST);

			if ($_errori['errori'] != "")
			{
				// Inizio tabella pagina principale ----------------------------------------------------------
				echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
				// includo la barra di navigazione
			
				echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $_errori[errori]</h3></span>";
				echo "<center><h2><br> Errore inserimento prezzi articolo si prega di Verificare</h2>\n";
				echo "<center><h2><br> Errore errore Registrato</h2>\n";
				exit;
			}
			else
			{
				echo "<tr><td align=\"left\"> Articolo $_articolo inserito in listino prezzi..</td></tr>\n";
			}
			
			//inserimento codice a barre..
			
			//Inseriamo il codice a barre..
			
			$_errori = tabella_barcode($_azione, $_articolo, $_articolo, '1');
			if ($_errori['errori'] != "OK")
			{
				// Inizio tabella pagina principale ----------------------------------------------------------
				echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
				// includo la barra di navigazione
			
				echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $_errori[descrizione]</h3></span>";
				echo "<center><h2><br> Errore inserimento prezzi articolo si prega di Verificare</h2>\n";
				echo "<center><h2><br> Errore errore Registrato</h2>\n";
				exit;
			}
			else
			{
				echo "<tr><td align=\"left\"> Articolo $_articolo inserito in codice a barre..</td></tr>\n";
			}
			
			
			
		}


		echo "<tr><td align=\"left\"> Articolo $_articolo inserito completamente</td></tr>\n";
	}

	
	
	

	if ($_azione == "Aggiorna")
	{
		// chiamo la funzione che mi scrive i dati nella tabella magazzino e
		// mi Aggiorno dati nel articoli

		$dati = tabella_articoli($_azione, $_articolo, $_POST);

		if ($dati['errori'] != "")
		{
			// Inizio tabella pagina principale ----------------------------------------------------------
			echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
			// includo la barra di navigazione
			
			echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $dati[errori]</h3></span>";
			echo "<center><h2><br> Errore articolo si prega Verificare</h2>\n";
			echo "<center><h2><br> Errore errore Registrato</h2>\n";
			exit;
		}
		else
		{
			//se tutto ok inserisco i prezzi nei listini
			// inserisco i prezzi articoli nel listino

			$_errori = tabella_listini($_azione, $_articolo, $_nlv, $_POST);

			if ($_errori['errori'] != "")
			{
				// Inizio tabella pagina principale ----------------------------------------------------------
				echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
				// includo la barra di navigazione
				
				echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $_errori[errori]</h3></span>";
				echo "<center><h2><br> Errore tabella listini prezzi articolo si prega di Verificare</h2>\n";
				echo "<center><h2><br> Errore errore Registrato</h2>\n";
				exit;
			}
		}



		echo "<tr><td align=\"center\"> Articolo $_articolo Modificato</td></tr></table>\n";
	}

	if ($_azione == "Elimina")
	{

		$_errori = tabella_articoli($_azione, $_articolo, $_parametri);

		if ($_errori['errori'] != "")
		{
			// Inizio tabella pagina principale ----------------------------------------------------------
			echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";

			echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $_errori[errori]</h3></span>";
			exit;
		}
		else
		{
			// Inizio tabella pagina principale ----------------------------------------------------------
			echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
			// includo la barra di navigazione
			
			echo "<span class=\"testo_blu\"><h3>Risultato.. = $_errori[conferma1]</h3></span>";
			echo "<span class=\"testo_blu\"><h3>Risultato.. = $_errori[conferma2]</h3></span>";
			echo "<span class=\"testo_blu\"><h3>Risultato.. = $_errori[conferma3]</h3></span>";
			echo "<span class=\"testo_blu\"><h3>Risultato.. = $_errori[conferma4]</h3></span>";
			echo "<center><h2><br>Articolo $_articolo Eliminato Definitivamente</h2>\n";

			exit;
		}
	}

	if ($_azione == "Forza")
	{
		$_errori = tabella_articoli($_azione, $_articolo, $_parametri);

		if ($_errori['errori'] != "")
		{
			// Inizio tabella pagina principale ----------------------------------------------------------
			echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
			// includo la barra di navigazione
		
			echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $_errori[errori]</h3></span>";
			echo "<center><h2><br> Errore tabella listini prezzi articolo si prega di Verificare</h2>\n";
			echo "<center><h2><br> Errore errore Registrato</h2>\n";
			exit;
		}
		else
		{
			// Inizio tabella pagina principale ----------------------------------------------------------
			echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
			// includo la barra di navigazione
		
			echo "<span class=\"testo_blu\"><h3>Risultato.. = $_errori[conferma1]</h3></span>";
			echo "<span class=\"testo_blu\"><h3>Risultato.. = $_errori[conferma2]</h3></span>";
			echo "<span class=\"testo_blu\"><h3>Risultato.. = $_errori[conferma3]</h3></span>";
			echo "<span class=\"testo_blu\"><h3>Risultato.. = $_errori[conferma4]</h3></span>";
			echo "<span class=\"testo_blu\"><h3>Risultato.. = $_errori[conferma5]</h3></span>";
			echo "<center><h2><br>Articolo $_articolo Eliminato Definitivamente</h2>\n";

			exit;
		}
	}


	echo "</td></tr></table>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>