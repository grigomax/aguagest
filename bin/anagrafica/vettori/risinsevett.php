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

session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require $_percorso . "librerie/motore_anagrafiche.php";
//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "2")
{

// Inizio tabella pagina principale ----------------------------------------------------------

	echo "<table with=150><tr><td>";


	echo "<span class=\"testo_blu\"><font size=\"3\">Verifica inserimento vettori</span></font>";

	echo "<table border=\"1\" align=\"center\" withd=\"80%\">";


	$_azione = $_POST['azione'];

//verifichiamo che i campi principali siano stati riempiti..

	if (($_POST['codice'] == "") OR ($_POST['vettore'] == ""))
	{
		echo "<h2>ATTENZIONE TROVATO CAMPO OBBLIGATORIO MANCANTE</h2>";

		echo "<br>Codice Vettore = $_POST[codice]\n";
		echo "<br>Nome Vettore = $_POST[vettore]\n";
		echo "<br>Vi preghiamo di tornare indietro e verificare\n";

		exit();
	}


// verificati i dati prepariamo l'array parametri..    
	$_parametri['vettore'] = $_POST['vettore'];
	$_parametri['indirizzo'] = $_POST['indirizzo'];
	$_parametri['telefono'] = $_POST['telefono'];
	$_parametri['cell'] = $_POST['cell'];
	$_parametri['fax'] = $_POST['fax'];
	$_parametri['email'] = $_POST['email'];
	$_parametri['web'] = $_POST['web'];
	$_parametri['traking'] = $_POST['traking'];
	$_parametri['note'] = addslashes($_POST['note']);



	if ($_azione == "inserisci")
	{

		$_check = tabella_vettori("check", $_percorso, $_POST['codice'], $_parametri);
		if ($_check['result'] == "1")
		{
			echo $_check['errori'];
		}
		else
		{
			//inseriamo il vettore
			$_result = tabella_vettori("inserisci", $_percorso, $_POST['codice'], $_parametri);

			echo "<tr><td> $_result[errori] </td></tr>\n";
		}// fine graffa else
	}// fine graffa funzione


	if ($_azione == "Aggiorna")
	{

		$_result = tabella_vettori("modifica", $_percorso, $_POST['codice'], $_parametri);

		echo "<tr><td> $_result[errori] </td></tr>\n";
	}

	if ($_azione == "Elimina")
	{
		$_result = tabella_vettori("elimina", $_percorso, $_POST['codice'], $_parametri);

		echo "<tr><td> $_result[errori] </td></tr>\n";
	}


	echo "</td></tr></table>";
	
	//chiudiamo le connessioni
	
	$conn -> null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>