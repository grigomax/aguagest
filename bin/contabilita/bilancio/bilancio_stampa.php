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
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


require "../../../setting/par_conta.inc.php";

//prendiamo i GET dalla pagina precedente..
if ($_SESSION['user']['contabilita'] > "1")
{
	$_start = cambio_data("us", $_GET['data_start']);
	$_end = cambio_data("us", $_GET['data_end']);

	function intestazione()
	{
		include "../../../setting/vars.php";
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"TOP\" align=\"left\">";
		echo "<tr><td colspan=\"4\">\n";
		echo "<h4>$azienda</h4>\n";
		echo "$indirizzo - $cap<br>\n";
		echo "$citta - $prov<br>\n";
		echo "Partita iva $piva e C.F. $codfisc<br>\n";
		echo "</td></tr>\n";
		echo "<tr><td colspan=\"3\" width=\"100%\" align=\"center\" valign=\"top\">\n";
		echo "<h3>Bilancio al $_GET[data_end]</h3>";
		echo "</td></tr>\n";
		echo "</table>\n";
	}

	function testata($_titolo, $_prima, $_seconda)
	{
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"TOP\" align=\"left\">";
		echo "<tr><td colspan=\"7\" class=\"tabella\">$_titolo</td></tr>\n";
		echo "<tr><td class=\"tabella\" colspan=\"3\">$_prima</td>\n";
		echo "<td class=\"tabella\" colspan=\"1\">&nbsp;</td>\n";
		echo "<td class=\"tabella\" colspan=\"3\">$_seconda</td></tr>\n";
		echo "<tr><td class=\"tabella_elenco\">Conto</td><td class=\"tabella_elenco\">Descrizione</td><td alingn=\"right\" class=\"tabella_elenco\" >Valore</td>\n";
		echo "<td class=\"tabella_elenco\">\n";
		echo "<td class=\"tabella_elenco\">Conto</td><td class=\"tabella_elenco\">Descrizione</td><td alingn=\"right\" class=\"tabella_elenco\" >Valore</td></tr>\n";
//apriamo la cella di sinistra e mettiamo tutti i dare come attività..
	}

	function corpo($_cosa, $row, $row2, $_totale, $_primo, $_secondo)
	{
		global $MASTRO_CLI;
		global $MASTRO_FOR;
		global $clienti;
		global $fornitori;
		
		if ($_cosa == "fornitori")
		{
			if ($fornitori['saldo'] > "0.00")
			{
				$_scritta_p = "D";
				$_totale[$_secondo] = $_totale[$_secondo] - abs($fornitori[saldo]);
			}
			else
			{
				$_scritta_p = "A";
				$_totale[$_secondo] = $_totale[$_secondo] + abs($fornitori[saldo]);
			}

			echo "<td class=\"tabella_elenco\">$MASTRO_FOR</td><td class=\"tabella_elenco\">FORNITORI</td><td align=\"right\" class=\"tabella_elenco\">" . abs($fornitori[saldo]) . " $_scritta_p</td></tr> \n";
			
		}
		elseif ($_cosa == "clienti")
		{
			if ($clienti['saldo'] > "0.00")
			{
				$_scritta_p = "D";
				$_totale[$_primo] = $_totale[$_primo] + abs($clienti[saldo]);
			}
			else
			{
				$_scritta_p = "A";
				$_totale[$_primo] = $_totale[$_primo] - abs($clienti[saldo]);
			}
			echo "<tr><td class=\"tabella_elenco\">$MASTRO_CLI</td><td class=\"tabella_elenco\">CLIENTI</td><td align=\"right\" class=\"tabella_elenco\">" . abs($clienti[saldo]) . " $_scritta_p</td> \n";
			echo "<td class=\"tabella_elenco\">&nbsp;</td>\n";
		}
		else
		{
			if ($row)
			{
				$_scritta_p = "D";
				echo "<tr><td class=\"tabella_elenco\">$row[conto]&nbsp;</td><td class=\"tabella_elenco\">$row[desc_conto]</td><td align=\"right\" class=\"tabella_elenco\">" . number_format(abs($row[saldo]), '2', '.', '') . " $_scritta_p</td> \n";
				$_totale[$_primo] = $_totale[$_primo] + $row['saldo'];
			}
			else
			{
				echo "<tr><td class=\"tabella_elenco\">&nbsp;</td><td class=\"tabella_elenco\">&nbsp;</td><td align=\"right\" class=\"tabella_elenco\">&nbsp;</td> \n";
			}

			echo "<td class=\"tabella_elenco\">&nbsp;</td>\n";

			if ($row2)
			{
				$_scritta_p = "A";
				echo "<td class=\"tabella_elenco\">$row2[conto]</td><td class=\"tabella_elenco\">$row2[desc_conto]</td><td align=\"right\" class=\"tabella_elenco\">" . number_format(abs($row2[saldo]), '2', '.', '') . " $_scritta_p</td></tr> \n";
				$_totale[$_secondo] = $_totale[$_secondo] + abs($row2['saldo']);
			}
			else
			{
				echo "<td class=\"tabella_elenco\">&nbsp;</td><td class=\"tabella_elenco\">&nbsp;</td><td align=\"right\" class=\"tabella_elenco\">&nbsp;</td></tr> \n";
			}
		}


		return $_totale;
	}

	function calce($_totale, $_primo, $_secondo)
	{
		echo "<tr><td colspan=\"7\"><hr></td></tr>\n";
		echo "<tr><td align=\"center\" colspan=\"4\">Totale $_primo = $_totale[$_primo] </td>\n";
		echo "<td align=\"center\" colspan=\"3\"> Totale $_secondo = $_totale[$_secondo] </td></tr>\n";
		echo "<tr><td colspan=\"7\"><hr></td></tr>\n";
		echo "</table>\n";

		echo "&nbsp;<br>&nbsp;\n";
	}

	$_righe = "70";

	base_html_stampa("chiudi", $_parametri);

	echo "<body>";

#echo "<h4>La stampa in formato pdf verr&agrave; inviata in linea al Browser</h4>\n";
	/* devo fare un query per i clienti
	 * una per i fornitori una per il resto in ordine di categoria..
	 *
	 */
//query per i clienti
	$query = "SELECT data_cont, desc_conto, conto, (SUM(dare) - SUM(avere)) AS saldo from prima_nota where data_cont >= '$_start' AND data_cont <= '$_end' AND conto LIKE '$MASTRO_CLI%' HAVING saldo != '0.00'";
	$result = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
		echo $_errore['2'];
		//aggiungiamo la gestione scitta dell'errore..
		$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
		$_errori['files'] = "bilancio_stampa_63.php";
		scrittura_errori($_cosa, $_percorso, $_errori);
	}
	foreach ($result AS $clienti)
		;


	$query = "SELECT data_cont, desc_conto, conto, (SUM(dare) - SUM(avere)) AS saldo from prima_nota where data_cont >= '$_start' AND data_cont <= '$_end' AND conto LIKE '$MASTRO_FOR%' HAVING saldo != '0.00'";
	$result = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
		echo $_errore['2'];
		//aggiungiamo la gestione scitta dell'errore..
		$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
		$_errori['files'] = "bilancio_stampa_63.php";
		scrittura_errori($_cosa, $_percorso, $_errori);
	}
	foreach ($result AS $fornitori)
		;



//$query = "SELECT data_cont, desc_conto, conto, (SUM(dare) - SUM(avere)) AS saldo from prima_nota where data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto ORDER BY conto";
//prima domanda solo per il conto patrimoniale..
	$query = "SELECT data_cont, desc_conto, conto, codconto, natcon, tipo_cf, (SUM( dare ) - SUM( avere ) ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto = piano_conti.codconto WHERE (natcon='A' OR natcon='P') AND data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto HAVING saldo != '0.00' AND saldo > '0.00'";
	$result = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
		echo $_errore['2'];
		//aggiungiamo la gestione scitta dell'errore..
		$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
		$_errori['files'] = "bilancio_stampa_63.php";
		scrittura_errori($_cosa, $_percorso, $_errori);
	}

	$query = "SELECT data_cont, desc_conto, conto, codconto, natcon, tipo_cf, (SUM( dare ) - SUM( avere ) ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto = piano_conti.codconto WHERE (natcon='A' OR natcon='P') AND data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto HAVING saldo != '0.00' AND saldo < '0.00'";
	$result2 = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
		echo $_errore['2'];
		//aggiungiamo la gestione scitta dell'errore..
		$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
		$_errori['files'] = "bilancio_stampa_63.php";
		scrittura_errori($_cosa, $_percorso, $_errori);
	}

	$quante = "1";

	for ($pagina = "1"; $pagina <= $quante; $pagina++)
	{
		intestazione();
		testata("STATO PATRIMONIALE", "Attività", "Passività");

		if($_apparizione != "SI")
		{
			//facciamo apparire i clienti
			$_totale = corpo("clienti", $row, $row2, $_totale, "attivita", "passivita");
			$_totale = corpo("fornitori", $row, $row2, $_totale, "attivita", "passivita");
			$_righe = $_righe -1;
			$_apparizione = "SI";
		}
		
		
		for ($a = "0"; $a <= $_righe; $a++)
		{
			//foreach ($costi AS $row);

			$row = $result->fetch(PDO::FETCH_ASSOC);
			$row2 = $result2->fetch(PDO::FETCH_ASSOC);
			$_totale = corpo($_cosa, $row, $row2, $_totale, "attivita", "passivita");
		}

		if (($row) OR ($row2))
		{
			$quante = "2";
		}


		calce($_totale, "attivita", "passivita");


		
	}


//chiudiamo la tabella e apriamo un'altra pagina'
//ora iniziamo con il conto economico...
//prima domanda solo per il conto economico
	$query = "SELECT data_cont, desc_conto, conto, codconto, natcon, tipo_cf, (SUM( dare ) - SUM( avere ) ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto = piano_conti.codconto WHERE (natcon='C' OR natcon='R') AND data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto HAVING saldo > '0.00'";
	$result = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
		echo $_errore['2'];
		//aggiungiamo la gestione scitta dell'errore..
		$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
		$_errori['files'] = "bilancio_stampa_63.php";
		scrittura_errori($_cosa, $_percorso, $_errori);
	}

	$query = "SELECT data_cont, desc_conto, conto, codconto, natcon, tipo_cf, (SUM( dare ) - SUM( avere ) ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto = piano_conti.codconto WHERE (natcon='C' OR natcon='R') AND data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto HAVING saldo < '0.00'";
	$result2 = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
		echo $_errore['2'];
		//aggiungiamo la gestione scitta dell'errore..
		$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
		$_errori['files'] = "bilancio_stampa_63.php";
		scrittura_errori($_cosa, $_percorso, $_errori);
	}


	$quante = "1";

	for ($pagina = "1"; $pagina <= $quante; $pagina++)
	{
		
		intestazione();
		testata("STATO ECONOMICO", "Costi", "Ricavi");

		for ($a = "0"; $a <= $_righe; $a++)
		{
			//foreach ($costi AS $row);

			$row = $result->fetch(PDO::FETCH_ASSOC);
			$row2 = $result2->fetch(PDO::FETCH_ASSOC);
			$_totale = corpo($_cosa, $row, $row2, $_totale, "costi", "ricavi");
		}

		if (($row) OR ($row2))
		{
			$quante = "2";
		}


		calce($_totale, "costi", "ricavi");


		echo "</table>";
	}





	//echo "<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>\n";
	echo "<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>\n";
	

	intestazione();

	$_sbilanciamento_patrim = $_totale['attivita'] - $_totale['passivita'];


	$_sbilanciamento_econ = $_totale['costi'] - $_totale['ricavi'];


	$_differenza = abs($_sbilanciamento_patrim) - abs($_sbilanciamento_econ);

	echo "<tr><td colspan=\"7\"><h2>Saldi e Totali</h2></td></tr>\n";
	echo "<tr><td colspan=\"7\">&nbsp;</td></tr>\n";
	echo "<tr><td colspan=\"7\"><h3>Conto Patrimoniale </h3></td></tr>\n";
	echo "<tr><td colspan=\"7\">Totale Attivit&agrave; = $_totale[attivita]</td></tr>\n";
	echo "<tr><td colspan=\"7\">Totale Passivit&agrave; = $_totale[passivita]</td></tr>\n";
	echo "<tr><td colspan=\"7\"><h4>Sbilanciamento Patrimoniale = " . number_format($_sbilanciamento_patrim, $dec, '.', '') . "</h4></td></tr>\n";
	echo "<tr><td colspan=\"7\">&nbsp;</td></tr>\n";
	echo "<tr><td colspan=\"7\"><h3>Conto Economico</h3></td></tr>\n";
	echo "<tr><td colspan=\"7\">Totale Ricavi = $_totale[ricavi]</td></tr>\n";
	echo "<tr><td colspan=\"7\">Totale Costi = $_totale[costi]</td></tr>\n";
	echo "<tr><td colspan=\"7\"><h4>Sbilanciamento Conto economico = " . number_format($_sbilanciamento_econ, $dec, '.', '') . "</h4></td></tr>\n";
	echo "<tr><td colspan=\"7\">&nbsp;</td></tr>\n";
	echo "<tr><td colspan=\"7\">&nbsp;</td></tr>\n";
//echo "<tr><td colspan=\"2\"><h2>Situazione globale</h2></b></td></tr>\n";
//echo "<tr><td colspan=\"2\"><h3>Sbilanciamento complessivo.. $_differenza</h3></td></tr>\n";
//ora mettiamo lo sbilanciamento...



	echo "</table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>