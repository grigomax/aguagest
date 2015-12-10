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

require "../../../setting/par_conta.inc.php";
//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{


//prendiamo i post dalla pagina precedente..

	$_start = cambio_data("us", $_POST['data_start']);
	$_end = cambio_data("us", $_POST['data_end']);

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
	echo "<tr>";

	echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
	echo "<h3>Bilancio al $_POST[data_end]</h3>";
	echo "<h3>Stampa Bilancio di Verifica<a href=\"bilancio_verifica.php?data_start=$_POST[data_start]&data_end=$_POST[data_end]\" target=\"_blanck\">Stampa Qui!</a></h3>";
	echo "<h3>Stampa Bilancio <a href=\"bilancio_stampa.php?data_start=$_POST[data_start]&data_end=$_POST[data_end]\" target=\"_blanck\">Stampa Qui!</a></h3>";

#echo "<h4>La stampa in formato pdf verr&agrave; inviata in linea al Browser</h4>\n";
	/* devo fare un query per i clienti
	 * una per i fornitori una per il resto in ordine di categoria..
	 *
	 */
//query per i clienti
	$query = "SELECT data_cont, desc_conto, conto, (SUM(dare) - SUM(avere)) AS saldo from prima_nota where data_cont >= '$_start' AND data_cont <= '$_end' AND conto LIKE '$MASTRO_CLI%'";
	#echo $query;
	
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
		foreach ($result AS $clienti);
		
		//azzeriamo la variabile
		$result = "";
		

	$query = "SELECT data_cont, desc_conto, conto, (SUM(dare) - SUM(avere)) AS saldo from prima_nota where data_cont >= '$_start' AND data_cont <= '$_end' AND conto LIKE '$MASTRO_FOR%'";
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
		foreach ($result AS $fornitori);

		$result="";
		
		
//$query = "SELECT data_cont, desc_conto, conto, (SUM(dare) - SUM(avere)) AS saldo from prima_nota where data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto ORDER BY conto";

	$query = "SELECT data_cont, desc_conto, conto, codconto, natcon, tipo_cf, (SUM( dare ) - SUM( avere ) ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto = piano_conti.codconto WHERE data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto";

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

//creiamo una tabella dove mettere i dati dentro..

	echo "<table class=\"table\" width=\"60%\">\n";
	echo "<tr><td class=\"tabella\">conto</td><td class=\"tabella\">Descrizione</td><td class=\"tabella\">Saldo</td><td class=\"tabella\">Tipo conto</td></tr> \n";

	foreach ($result AS $row)
	{
		//ora iniziamo ad elencare solo i dati differenti da 0..
		//poi se positivi sono in dare se negativi sono in avere..
		if ($row['natcon'] != $natcon2)
		{
			echo "<tr><td class=\"tabella_elenco\" colspan=\"4\"><hr></td></tr>\n";
		}

		//mi prendo il mastro
		$_mastro = substr($row['conto'], 0, 2);

		if (($_mastro > $MASTRO_CLI) AND ($_cliappa != "1"))
		{
			if ($clienti['saldo'] > "0.00")
			{
				$_scritta_p = "D";
			}
			else
			{
				$_scritta_p = "A";
			}
			echo "<tr><td class=\"tabella_elenco\">$MASTRO_CLI</td><td class=\"tabella_elenco\" align=\"left\" >CLIENTI</td><td align=\"right\" class=\"tabella_elenco\">" . abs($clienti[saldo]) . " $_scritta_p</td><td class=\"tabella_elenco\">A</td></tr> \n";
			$_totale['A'] = $_totale['A'] + abs($clienti[saldo]);
			$_cliappa = "1";
		}

		if (($_mastro > $MASTRO_FOR) AND ($_forappa != "1"))
		{
			if ($fornitori['saldo'] > "0.00")
			{
				$_scritta_p = "D";
			}
			else
			{
				$_scritta_p = "A";
			}
			echo "<tr><td class=\"tabella_elenco\">$MASTRO_FOR</td><td class=\"tabella_elenco\" align=\"left\">FORNITORI</td><td align=\"right\" class=\"tabella_elenco\">" . abs($fornitori[saldo]) . " $_scritta_p</td><td class=\"tabella_elenco\">P</td></tr> \n";
			$_totale['P'] = $_totale['P'] + abs($fornitori[saldo]);
			$_forappa = "1";
		}

		if ($row['saldo'] != "0.00")
		{

			if ($row['saldo'] > "0.00")
			{
				$_scritta_p = "D";
			}
			else
			{
				$_scritta_p = "A";
			}


			echo "<tr><td class=\"tabella_elenco\" align=\"left\">$row[conto]</td><td class=\"tabella_elenco\" align=\"left\">$row[desc_conto]</td><td align=\"right\" class=\"tabella_elenco\">" . abs($row[saldo]) . " $_scritta_p</td><td class=\"tabella_elenco\">$row[natcon]</td></tr> \n";
			$natcon2 = $row['natcon'];
			$_totale[$row[natcon]] = $_totale[$row[natcon]] + abs($row[saldo]);
		}
	}


	echo "<tr><td colspan=\"4\"><hr></td></tr>\n";
	echo "</table>\n";

	echo "<h4>Totali... </h4>";

	echo "Totale A euro = $_totale[A]<br>";
	echo "Totale P euro = $_totale[P]<br>";
	$_patrimoniale = $_totale['A'] - $_totale['P'];
	echo "<b>Differenza A - P = $_patrimoniale <br></b>";

	echo "<br>Totale R euro = $_totale[R]<br>";
	echo "Totale C euro = $_totale[C]<br>";
	$_economico = $_totale['R'] - $_totale['C'];
	echo "<b>Differenza R - C = $_economico </b><br>";

	echo "<br>Totale O euro = $_totale[O]<br>";

	$_sbilanciamento = $_patrimoniale - $_economico;


	echo "<h3>Sbilanciamento da 0= $_sbilanciamento</h3>\n";
	echo "</td></tr>\n";
	echo "</table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>