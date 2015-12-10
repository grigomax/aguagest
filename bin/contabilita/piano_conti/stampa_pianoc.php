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

//includiamo i parametri di contabilita
//
require $_percorso . "../setting/par_conta.inc.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);

//carichiamo la testata del programma.
#testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

	$_data = date('d-m-Y');


	echo "<table class=\"elenco\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
	echo "<thead>\n";
	echo "<tr>";

	echo "<th colspan=\"5\" align=\"center\"><b>Stampa Piano dei conti $azienda al $_data</b></th>";




	$query = "select * from piano_conti order by codconto";

	$result = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
		echo $_errore['2'];
		//aggiungiamo la gestione scitta dell'errore..
		$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
		$_errori['files'] = "stampa_pianoc.php";
		scrittura_errori($_cosa, $_percorso, $_errori);
	}

	//se la richiesta è che vengan inclusi anche i clienti e fornitori.. elenchiamo la scelta
	if ($_GET['azione'] == "clifor")
	{

		$query = "select codice, ragsoc from clienti ORDER BY codice";

		$clienti = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "stampa_pianoc.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}


		$query = "select codice, ragsoc from fornitori ORDER BY codice";

		$fornitori = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "stampa_pianoc.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}
	}

// Esegue la query...
	// La query ?stata eseguita con successo...
	// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
	// Tutto procede a meraviglia...
	echo "<tr>";
	echo "<th width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Conto</span></th>";
	echo "<th width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></th>";
	echo "<th width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Natura</span></th>";
	echo "<th width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Tipo Mastro</span></th>";
	echo "<th width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Livello Conto</span></th>";

	echo "</tr>";
	echo "</head>\n";
	echo "<tbody>\n";

	foreach ($result as $dati)
	{
		if ($dati['livello'] == "1")
		{
			echo "<tr><td colspan=\"5\"><hr></td><tr>\n";
			echo "<tr>\n";
			printf("<td width=\"50\" align=\"left\"><span class=\"testo_blu\"><b>%s</b></span></td>\n", $dati['codconto']);
			printf("<td width=\"250\" align=\"left\"><span class=\"testo_blu\"><b>%s</b></span></td>\n", $dati['descrizione']);
			printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>\n", $dati['natcon']);
			printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>\n", $dati['tipo_cf']);
			printf("<td width=\"60\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>\n", $dati['livello']);
			echo "</tr>\n";
			echo "<tr>";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"250\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "</tr>";


			//elenchiamo i clienti solamente se la scelta è clifor..

			if ($_GET['azione'] == "clifor")
			{
				//elenco clienti
				if ($dati['codconto'] == $MASTRO_CLI)
				{
					foreach ($clienti as $daticli)
					{
						echo "<tr>\n";
						printf("<td width=\"50\" align=\"left\"><span class=\"testo_blu\">$MASTRO_CLI%s</span></td>\n", $daticli['codice']);
						printf("<td width=\"250\" align=\"left\"><span class=\"testo_blu\">%s</span></td>\n", $daticli['ragsoc']);
						echo "<td width=\"50\" align=\"center\"><span class=\"testo_blu\">A</span></td>\n";
						echo "<td width=\"50\" align=\"center\"><span class=\"testo_blu\">C</span></td>\n";
						echo "<td width=\"60\" align=\"center\"><span class=\"testo_blu\">2</span></td>\n";
						echo "</tr>\n";
						echo "<tr>";
						echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "<td width=\"250\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "</tr>";
					}
				}

				//elenco fornitori
				if ($dati['codconto'] == $MASTRO_FOR)
				{
					foreach ($fornitori as $datifor)
					{
						echo "<tr>\n";
						printf("<td width=\"50\" align=\"left\"><span class=\"testo_blu\">$MASTRO_FOR%s</span></td>\n", $datifor['codice']);
						printf("<td width=\"250\" align=\"left\"><span class=\"testo_blu\">%s</span></td>\n", $datifor['ragsoc']);
						echo "<td width=\"50\" align=\"center\"><span class=\"testo_blu\">P</span></td>\n";
						echo "<td width=\"50\" align=\"center\"><span class=\"testo_blu\">F</span></td>\n";
						echo "<td width=\"60\" align=\"center\"><span class=\"testo_blu\">2</span></td>\n";
						echo "</tr>\n";
						echo "<tr>";
						echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "<td width=\"250\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
						echo "</tr>";
					}
				}
			}
		}
		else
		{
			echo "<tr>\n";
			printf("<td width=\"50\" align=\"left\"><span class=\"testo_blu\">%s</span></td>\n", $dati['codconto']);
			printf("<td width=\"250\" align=\"left\"><span class=\"testo_blu\">%s</span></td>\n", $dati['descrizione']);
			printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['natcon']);
			printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['tipo_cf']);
			printf("<td width=\"60\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['livello']);
			echo "</tr>\n";
			echo "<tr>";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"250\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "</tr>";
		}
	}
	echo "</table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>