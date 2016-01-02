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

//carichiamo la base delle pagine:
base_html("", $_percorso);

java_script($_cosa, $_percorso);

jquery_datapicker($_cosa, $_percorso);

echo "</head>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

	require "../../../setting/par_conta.inc.php";

	$_azione = $_POST['azione'];

	if ($_azione == "Esporta")
	{
		
		//eliminiamo le date ed affidiamoci all'anno selezionato..
	$_anno = $_POST['anno'];

	$_data_start = $_POST['anno'].'-01-01';
	$_data_end = $_POST['anno'].'-12-31';

// il nome del files
//$nfile="/agua/plugins/espfatt.txt";
		$nfile = "../../../spool/exp_clifor.csv";
// creo il files e nascondo la soluzione
		$fp = fopen($nfile, "w");
//controllo l'esito
		if (!$fp)
			die("Errore.. non sono riuscito a creare il file.. Permessi ?");

		#scriviamo una riga di commento per chiarire le posizioni
		$_commento = ";Portafoglio Clienti;;\n";

		fwrite($fp, $_commento);


#scriviamo una riga di commento per chiarire le posizioni
		$_commento = "Conto;Ragione Sociale;Saldo in Euro;Anno\n";

		fwrite($fp, $_commento);

		$query = "SELECT conto, descrizione, desc_conto, data_cont, (SUM( dare ) - SUM( avere ) ) AS diff FROM prima_nota WHERE data_cont BETWEEN '$_data_start' AND '$_data_end' AND descrizione NOT LIKE 'Chiusu%' AND conto like '$MASTRO_CLI%' GROUP BY conto ORDER BY desc_conto";

		//echo $query;
		
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


		#echo $query;
		foreach ($result as $dati)
		{

			if ($dati['diff'] != "0.00")
			{
				$_corpo = sprintf("%s;%s;%s;%s\n", $dati['conto'], $dati['desc_conto'], number_format($dati['diff'], $decdoc, ',', ''), $_POST['anno']);

				// nella speranza che sia ok la scriviamo
				fwrite($fp, $_corpo);
				if (!$fp)
					die("Errore.. Riga non inserita ?");
				// in caso di errori
				// per il momento ci fermiamo e proviamo
                                $_clienti = $_clienti + $dati['diff'];
			}
		}

                //inseriamo una riga con i totali calcolati..
                
                $_commento = ";Totale Clienti;$_clienti;;\n";

		fwrite($fp, $_commento);
                
                $_commento = "\n\n\n";

		fwrite($fp, $_commento);
		//
		//lato fornitori
		#scriviamo una riga di commento per chiarire le posizioni
                
		$_commento = ";Portafoglio Fornitori;;\n";

		fwrite($fp, $_commento);


#scriviamo una riga di commento per chiarire le posizioni
		$_commento = "Conto;Ragione Sociale;Saldo in Euro;Anno\n";

		fwrite($fp, $_commento);

                $query = "SELECT conto, descrizione, desc_conto, data_cont, (SUM( avere ) - SUM( dare ) ) AS diff FROM prima_nota WHERE data_cont BETWEEN '$_data_start' AND '$_data_end' AND descrizione NOT LIKE 'Chiusu%' AND conto like '$MASTRO_FOR%' GROUP BY conto ORDER BY desc_conto";
		//$query = "SELECT * , (SUM( avere ) - SUM( dare ) ) AS diff FROM prima_nota WHERE conto != '$BILANCIO_CHIUSURA' AND data_cont >= '$_data_start' AND data_cont <= '$_data_end' AND conto like '$MASTRO_FOR%'  AND conto != '9802' GROUP BY conto ORDER BY desc_conto";

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

		foreach ($result as $dati)
		{

			if ($dati['diff'] != "0.00")
			{
				$_corpo = sprintf("%s;%s;%s;%s\n", $dati['conto'], $dati['desc_conto'], number_format($dati['diff'], $decdoc, ',', ''), $_POST['anno']);

                                $_fornitori = $_fornitori + $dati['diff'];
				// nella speranza che sia ok la scriviamo
				fwrite($fp, $_corpo);
				if (!$fp)
					die("Errore.. Riga non inserita ?");
				// in caso di errori
				// per il momento ci fermiamo e proviamo
			}
		}

                $_commento = ";Totale Fornitori;$_fornitori;;\n";

		fwrite($fp, $_commento);

// chiudiamo il files
		fclose($fp);
		//chiudiamo il database..
		
		$conn->null;
		$conn = null;

// iniziamo parte visiva..

		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
		echo "<tr>";
		echo "<td>";
		echo "<center>";
		echo "<h3>Se non appaiono errori a video<br> la esportazione dei dati &egrave; stata <br>eseguita con successo</h3>";
		echo "<br>";
		echo "<h3>Per prelevare il file clicca di destro qui a lato e fare salva con nome<br> <a href=\"$nfile\"> Clicca Qui!</a></h3>";
	}
	else
	{

		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
		echo "<tr>";
		echo "<td>";
		echo "<h3>Esportazione saldi schede clienti fornitori</h3>";
		echo "<p><font type=\"arial\" size=\"2\">La Esportazione in formato CSV per la comunicazione annuale allo studio contabile<br>
    Dei saldi delle schede contabili.</p>\n";

		echo "<form action=\"export_clifor.php\" method=\"POST\">\n";

		echo "<br><br>Selezionare anno = <input type=\"text\" name=\"anno\" size=\"5\" maxlength=\"4\" value=\"\" >\n";

		echo "<br><br><input type=\"submit\" name=\"azione\" value=\"Esporta\">\n";
	}
	echo "</body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>