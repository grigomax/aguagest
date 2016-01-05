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
require $_percorso . "librerie/motore_anagrafiche.php";
require $_percorso . "librerie/motore_primanota.php";
include $_percorso . "../setting/par_conta.inc.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

// file unico di inserimento e aggiornamento banche
// Inizio tabella pagina principale ----------------------------------------------------------

	echo "<table width=\"90%\"><tr><td width=\"100\" valign=\"top\">";

// **********************************************************************
	echo "<span class=\"testo_blu\"><font size=\"3\">Verifica inserimento Banca</span></font>";

	echo "<table border=\"0\" align=\"center\" width=\"80%\">";

// inserisci
	$_azione = $_POST['azione'];

	//verifichiamo che i campi base siano pieni..

	if (($_POST['codice'] == "") OR ($_POST['banca'] == ""))
	{
		echo "<tr><td><b>Impossibile Proseguire in quanto i seguenti campi risultano vuoti</td></tr>\n";
		echo "<tr><td><b>Codice banca = $_POST[codice]</td></tr>\n";
		echo "<tr><td><b>Nome Istituto = $_POST[banca]</td></tr>\n";
		echo "<tr><td><b>Vogliate Tornare indietro e verificare.</td></tr>\n";
		exit;
	}



	//aggiungiamo gli slasces ai campi necesssari.. e preparo i campi per la funzione
	$_codice = $_POST['codice'];
	$_parametri['banca'] = addslashes($_POST['banca']);
	$_parametri['indirizzo'] = addslashes($_POST['indirizzo']);
	$_parametri['note'] = addslashes($_POST['note']);
	$_abi = $_POST['abi'];
	$_cab = $_POST['cab'];
	$_parametri['cin'] = $_POST['cin'];
	$_parametri['iban'] = $_POST['iban'];
	$_parametri['cc'] = $_POST['cc'];
	$_parametri['swift'] = $_POST['swift'];
	$_parametri['telefono'] = $_POST['telefono'];
	$_parametri['cell'] = $_POST['cell'];
	$_parametri['fax'] = $_POST['fax'];
        $_parametri['es_selezione'] = $_POST['es_selezione'];

	if ($_azione == "Inserisci")
	{

		//verifichiamo se l'istituto già esiste..

		$dati = tabella_banche("verifica", $_POST['codice'], $_abi, $_cab, $_parametri);

		//verifichiamo che non ci siamo errori

		if ($dati['errori']['descrizione'] != "")
		{
			echo "<tr><td><b>Errore... " . $dati['errori']['descrizione'] . "</td></tr>\n";
			exit;
		}

		if ($dati['result'] == TRUE)
		{

			echo "<tr><td><b>Istituto inserito &egrave; gi&agrave; esistente nell'archivio.</td></tr>\n";

			echo "<tr><td>Controllare i seguenti campi nell'archivio per verificare la presenza</td></tr>\n";

			printf("<tr><td>Codice istituto immesso = \"%s\", codice istituto trovato nell'archivio \"%s\"</td></tr>\n", $_POST['codice'], $dati['codice']);

			printf("<tr><td>Abi e cab = \"%s\", \"%s\" inserito, istituto trovati nell'archivio \"%s\", \"%s\" </td></tr>\n", $_POST['abi'], $_POST['cab'], $dati['abi'], $dati['cab']);

			echo "<tr><td>Fai indietro con il browser per non perdere i dati inseriti.<br> Poi cambia codice istituto</td></tr>\n";

			exit;
		}
		else
		{

			//inseriamo i dati..

			$result = tabella_banche("inserisci", $_codice, $_abi, $_cab, $_parametri);

			//vediamo se ci sono errori..

			if ($result['result'] == FALSE)
			{
				echo "<tr><td><b>Si è verificato un errore durante l'inserimento della banca..<br>
					ERRORE = $result[errori]</td></tr>\n";
				echo "<tr><td><b>Errore registrato</td></tr>\n";
				exit;
			}
			else
			{
				//verifichiamo che non ci si il collegamento con la contabilità.
				//-------INIZIO
				//verifico che se il post contabilità è stato selezionato ad esercizio avviato ci siano i campi dei parametri già inserii
				if ($CONTABILITA == "SI")
				{

					//verifico che ci siano i parametri banche, clienti, fornitori
					if (($MASTRO_BANCHE == "") OR ($CONTO_EFFETTI_INCASSO == "") OR ($CONTO_EFFETTI_SBF == "") OR ($CONTO_EFFETTI_INSOLUTI == ""))
					{
						echo "<center>";
						echo "<h2>Controllo dati inseriti</h2>";
						echo "<h3>Impossibile La banca inquanto</h3>";
						echo "Non sono stati impostati i parametri Banche";
						echo "La banca è ststa inserita, ma non a livello contabile, bisognerà eliminarla, correggere i parametri contabilità<br> ed provare ad reinserirla..";
						echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A>";
						exit;
					}
					else
					{

						//inseriamo le banche in nel piano dei conti..:
						//azzeriamo
						$_parametri = "";
						$_codconto = $MASTRO_BANCHE . $_codice;
						$_parametri['descrizione'] = addslashes($_POST['banca']);
						$_parametri['natconto'] = "A";
						$_parametri['livello'] = "2";
						$_parametri['tipo_cf'] = "B";

						//inseriamo

						$result = tabella_piano_conti("inserisci", $_codconto, $_parametri);

						if ($result['result'] == FALSE)
						{
							echo "<tr><td><b>Si è verificato un errore durante l'inserimento della banca.. nel piano dei conti<br>
								ERRORE = $result[errori]</td></tr>\n";
							echo "<tr><td><b>Errore registrato</td></tr>\n";
							exit;
						}


						//inseriamo il tutto nel piano dei conti..
						$_parametri = "";
						$_codconto = $CONTO_EFFETTI_SBF . $_codice;
						$_parametri['descrizione'] = "Effetti S.B.F. " . addslashes($_POST['banca']);
						$_parametri['natconto'] = "A";
						$_parametri['livello'] = "3";
						$_parametri['tipo_cf'] = "B";

						//inseriamo

						$result = tabella_piano_conti("inserisci", $_codconto, $_parametri);

						if ($result['result'] == FALSE)
						{
							echo "<tr><td><b>Si è verificato un errore durante l'inserimento della banca.. nel piano dei conti<br>
								ERRORE = $result[errori]</td></tr>\n";
							echo "<tr><td><b>Errore registrato</td></tr>\n";
							exit;
						}

						//inseriamo il tutto nel piano dei conti..
						$_parametri = "";
						$_codconto = $CONTO_EFFETTI_INCASSO . $_codice;
						$_parametri['descrizione'] = "Effetti all incasso " . addslashes($_POST['banca']);
						$_parametri['natconto'] = "A";
						$_parametri['livello'] = "3";
						$_parametri['tipo_cf'] = "B";

						//inseriamo

						$result = tabella_piano_conti("inserisci", $_codconto, $_parametri);

						if ($result['result'] == FALSE)
						{
							echo "<tr><td><b>Si è verificato un errore durante l'inserimento della banca.. nel piano dei conti<br>
								ERRORE = $result[errori]</td></tr>\n";
							echo "<tr><td><b>Errore registrato</td></tr>\n";
							exit;
						}

						//inseriamo il tutto nel piano dei conti..
						$_parametri = "";
						$_codconto = $CONTO_EFFETTI_INSOLUTI . $_codice;
						$_parametri['descrizione'] = "Effetti Insoluti " . addslashes($_POST['banca']);
						$_parametri['natconto'] = "A";
						$_parametri['livello'] = "3";
						$_parametri['tipo_cf'] = "B";

						//inseriamo

						$result = tabella_piano_conti("inserisci", $_codconto, $_parametri);

						if ($result['result'] == FALSE)
						{
							echo "<tr><td><b>Si è verificato un errore durante l'inserimento della banca.. nel piano dei conti<br>
								ERRORE = $result[errori]</td></tr>\n";
							echo "<tr><td><b>Errore registrato</td></tr>\n";
							exit;
						}
					}
				}

				//---------------FINE

				echo "<tr><td> istituto inserito correttamente</td></tr>\n";
			}
		}// fine graffa else
	}// fine graffa funzione
//--------------------------------------------------------------------------------------------------------------------------------------------------------------	
	// funzione aggiornamento..
	if ($_azione == "Aggiorna")
	{


		$result = tabella_banche("aggiorna", $_codice, $_abi, $_cab, $_parametri);

		//vediamo se ci sono errori..

		if ($result['result'] == FALSE)
		{
			echo "<tr><td><b>Si è verificato un errore durante l'aggiornamento della banca..<br>
					ERRORE = $result[errori]</td></tr>\n";
			echo "<tr><td><b>Errore registrato</td></tr>\n";
			exit;
		}
		else
		{
			//verifichiamo che non ci si il collegamento con la contabilità.
			//-------INIZIO
			//verifico che se il post contabilità è stato selezionato ad esercizio avviato ci siano i campi dei parametri già inserii
			if ($CONTABILITA == "SI")
			{



				//verifico che ci siano i parametri banche, clienti, fornitori
				if (($MASTRO_BANCHE == "") OR ($CONTO_EFFETTI_INCASSO == "") OR ($CONTO_EFFETTI_SBF == "") OR ($CONTO_EFFETTI_INSOLUTI == ""))
				{
					echo "<center>";
					echo "<h2>Controllo dati inseriti</h2>";
					echo "<h3>Impossibile La banca inquanto</h3>";
					echo "Non sono stati impostati i parametri Banche";
					echo "La banca è ststa modificata, ma non a livello contabile, bisognerà sistemare e correggere i parametri contabilità<br> ed provare a rimodificarla..";
					echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A>";
					exit;
				}
				else
				{

					//inseriamo le banche in nel piano dei conti..:
					//azzeriamo
					$_parametri = "";
					$_codconto = $MASTRO_BANCHE . $_codice;
					$_parametri['descrizione'] = addslashes($_POST['banca']);
					$_parametri['natconto'] = "A";
					$_parametri['livello'] = "2";
					$_parametri['tipo_cf'] = "B";

					//inseriamo
					$result = tabella_piano_conti("aggiorna", $_codconto, $_parametri);

					if ($result['result'] == FALSE)
					{
						echo "<tr><td><b>Si è verificato un errore durante l'aggiormaneto della banca.. nel piano dei conti<br>
								ERRORE = $result[errori]</td></tr>\n";
						echo "<tr><td><b>Errore registrato</td></tr>\n";
						exit;
					}


					//inseriamo il tutto nel piano dei conti..
					$_parametri = "";
					$_codconto = $CONTO_EFFETTI_SBF . $_codice;
					$_parametri['descrizione'] = "Effetti S.B.F. " . addslashes($_POST['banca']);
					$_parametri['natconto'] = "A";
					$_parametri['livello'] = "3";
					$_parametri['tipo_cf'] = "B";

					//inseriamo

					$result = tabella_piano_conti("aggiorna", $_codconto, $_parametri);

					if ($result['result'] == FALSE)
					{
						echo "<tr><td><b>Si è verificato un errore durante l'aggiornamento della banca.. nel piano dei conti<br>
								ERRORE = $result[errori]</td></tr>\n";
						echo "<tr><td><b>Errore registrato</td></tr>\n";
						exit;
					}

					//inseriamo il tutto nel piano dei conti..
					$_parametri = "";
					$_codconto = $CONTO_EFFETTI_INCASSO . $_codice;
					$_parametri['descrizione'] = "Effetti all incasso " . addslashes($_POST['banca']);
					$_parametri['natconto'] = "A";
					$_parametri['livello'] = "3";
					$_parametri['tipo_cf'] = "B";

					//inseriamo

					$result = tabella_piano_conti("aggiorna", $_codconto, $_parametri);

					if ($result['result'] == FALSE)
					{
						echo "<tr><td><b>Si è verificato un errore durante l'aggiornamento della banca.. nel piano dei conti<br>
								ERRORE = $result[errori]</td></tr>\n";
						echo "<tr><td><b>Errore registrato</td></tr>\n";
						exit;
					}

					//inseriamo il tutto nel piano dei conti..
					$_parametri = "";
					$_codconto = $CONTO_EFFETTI_INSOLUTI . $_codice;
					$_parametri['descrizione'] = "Effetti Insoluti " . addslashes($_POST['banca']);
					$_parametri['natconto'] = "A";
					$_parametri['livello'] = "3";
					$_parametri['tipo_cf'] = "B";

					//inseriamo

					$result = tabella_piano_conti("aggiorna", $_codconto, $_parametri);

					if ($result['result'] == FALSE)
					{
						echo "<tr><td><b>Si è verificato un errore durante l'aggiornamento della banca.. nel piano dei conti<br>
								ERRORE = $result[errori]</td></tr>\n";
						echo "<tr><td><b>Errore registrato</td></tr>\n";
						exit;
					}
				}
			}
			echo "<tr><td> Istituto modificato con successo</td></tr></table>\n";
		}

		// graffa di fine funzione aggiornamento
	}




//----------------------------------------------------------------------------------------------------------------------------------------------------------
	if ($_azione == "Elimina")
	{

		//verifico che se il post contabilità è stato selezionato ad esercizio avviato ci siano i campi dei parametri già inserii
		if ($CONTABILITA == "SI")
		{
			//PRIMA DI ELIMINARE UNA BANCA, BISOGNA VERE SE LA STESSA ritulta aperta in contabilità..
			// se la stessa è libera la passiamo ad eliminarla..
			//vediamo se la banca ha muovimenti nella prima nota..
			$_parametri = "";
			$_parametri['conto'] = $MASTRO_BANCHE . $_codice;
			$result = tabella_primanota("check", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso, $_percorso);

			if ($result['result'] == TRUE)
			{
				echo "<tr><td><b>Impossibile Eliminare l'istituto in quanto risulta ancora muovimentato nel piano dei conti..<br>
					Si Prega di verificare il conto $_parametri[conto]</td></tr>\n";
				exit;
			}

			//secondo controllo
			//vediamo se la banca ha muovimenti nella prima nota..
			$_parametri = "";
			$_parametri['conto'] = $CONTO_EFFETTI_SBF . $_codice;
			$result = tabella_primanota("check", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso, $_percorso);

			if ($result['result'] == TRUE)
			{
				echo "<tr><td><b>Impossibile Eliminare l'istituto in quanto risulta ancora muovimentato nel piano dei conti..<br>
					Si Prega di verificare il conto $_parametri[conto]</td></tr>\n";
				exit;
			}

			//secondo terzo
			//vediamo se la banca ha muovimenti nella prima nota..
			$_parametri = "";
			$_parametri['conto'] = $CONTO_EFFETTI_INCASSO . $_codice;
			$result = tabella_primanota("check", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso, $_percorso);

			if ($result['result'] == TRUE)
			{
				echo "<tr><td><b>Impossibile Eliminare l'istituto in quanto risulta ancora muovimentato nel piano dei conti..<br>
					Si Prega di verificare il conto $_parametri[conto]</td></tr>\n";
				exit;
			}

			//quarto
			//vediamo se la banca ha muovimenti nella prima nota..
			$_parametri = "";
			$_parametri['conto'] = $CONTO_EFFETTI_INSOLUTI . $_codice;
			$result = tabella_primanota("check", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso, $_percorso);

			if ($result['result'] == TRUE)
			{
				echo "<tr><td><b>Impossibile Eliminare l'istituto in quanto risulta ancora muovimentato nel piano dei conti..<br>
					Si Prega di verificare il conto $_parametri[conto]</td></tr>\n";
				exit;
			}
		}

		//se nessuna della funzioni precedenti ha bloccato la funzione vuol dire che è tutto ok passiamo ad eliminare la banca..

		$result = tabella_banche("elimina", $_codice, $_abi, $_cab, $_parametri);

		if ($result['result'] == FALSE)
		{
			echo "<tr><td><b>Si è verificato un errore durante la eliminazione della banca dall'archivio banche<br>
								ERRORE = $result[errori]</td></tr>\n";
			echo "<tr><td><b>Errore registrato</td></tr>\n";
			exit;
		}
		else
		{
			echo "<tr><td><b>Istituto eliminato correttamente..<br>
							</td></tr>\n";
		}
	}

	echo "</td></tr></table>";

	$conn->null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>