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

require "../../librerie/motore_doc_pdo.php";


$_tdoc = $_GET['tdoc'];
$_anno = $_GET['anno'];
$_utente = $_GET['utente'];
$_tut = $_GET['tut'];
$_scadenza = $_GET['scadenza'];

//selezioniamo il database documenti..
$_dbdoc = archivio_tdoc($_tdoc);

//INIZIAMO LA PERTE VISIVA..
        base_html_stampa("chiudi", $_parametri);


echo "<body>\n";

echo "<center><br><b>Elenco $_tdoc inevasi di quest'anno data " . date('d-m-Y') . " Ore " . date('H:m') . "</b></center> <br>";

if (($_tdoc == "fornitore") AND ($_tut != "sing_fornitore"))
{
	$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN fornitori ON $_dbdoc[testacalce].utente = fornitori.codice where status != 'evaso' order by ragsoc, anno, ndoc");
}
elseif ($_tut == "singolo")
{
	$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where clienti.codice = '$_utente' AND status != 'evaso' order by ragsoc, anno, ndoc");
}
elseif ($_tut == "sing_fornitore")
{
	$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN fornitori ON $_dbdoc[testacalce].utente = fornitori.codice where fornitori.codice = '$_utente' AND status != 'evaso' order by ragsoc, anno, ndoc");
}
else
{

	$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where status != 'evaso' order by ragsoc, anno, ndoc");
}



//connettiamoci al database con la prima soluzione--
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


// Esegue la query...
if ($result)
{
// Tutto procede a meraviglia...
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr>";

	echo "<td width=\"80\" colspan=\"2\" align=\"center\"><font size=\"2\">Num.<br>Data</td>";
	echo "<td colspan=\"4\" width=\"600\" align=\"left\"><font size=\"2\">Ragione Sociale</td>";
	echo "<td colspan=\"3\" width=\"150\" align=\"center\"><font size=\"2\">barcode</td>";
	echo "</tr>";

	foreach ($result as $dati)
	{

		//prepariamo il codice a barre..
		$_barcode = "";
		$_barcode = ".$dati[anno].$dati[ndoc]";

		echo "<tr>";
		printf("<td width=\"80\" align=\"center\" colspan=\"2\"><font size=\"2\"><b>%s</b><br>%s </td>", $dati['ndoc'], $dati['datareg']);
		printf("<td colspan=\"3\" width=\"550\" align=\"left\"><font size=\"2\"><b>%s</b><br>status %s | Inoltrato  %s | <b>VALORE = %s</b></td>", $dati['ragsoc'], $dati['status'], $dati['invio'], $dati['totimpo']);
		//printf("<td width=\"50\" align=\"center\"><font size=\"2\">%s<br><b>%s</b></td>", );
		echo "<td colspan=\"4\" width=\"150\" align=\"right\"><img src=$_percorso/tools/barcode/barcode.php?barcode=$_barcode&width=260&height=30&text=0></td>\n";
		echo "</tr>";

//ora per ogni orndine facciamo apparire le righe con lo status..
		echo "<tr>";
		echo "<td width=\"30\" align=\"center\"><font size=\"2\">Rigo </td>";
		echo "<td width=\"50\" align=\"center\"><font size=\"2\">Codice </td>";
		echo "<td width=\"500\" align=\"left\" ><font size=\"2\">Descrizione </td>";
		echo "<td width=\"50\" align=\"right\"><font size=\"2\">Q.ta Ord.</td>";
		echo "<td width=\"50\" align=\"right\"><font size=\"2\"><b>Q.ta cons.</b></td>";
		echo "<td width=\"50\" align=\"right\"><font size=\"2\"><b>Q.ta Prep.</b></td>";
		echo "<td width=\"50\" align=\"center\"><font size=\"2\">Data</td>";
		echo "<td width=\"30\" align=\"right\"><font size=\"2\">Giac.</td>";
		echo "<td width=\"30\" align=\"right\"><font size=\"2\">Ordinati</td>";
		echo "</tr>";

		if ($_scadenza == "due")
		{
			$query = "select * from $_dbdoc[dettaglio] where anno='$dati[anno]' and ndoc='$dati[ndoc]' AND consegna like '%15%' order by rigo";
		}
		else
		{
			$query = "select * from $_dbdoc[dettaglio] where anno='$dati[anno]' and ndoc='$dati[ndoc]' order by rigo";
		}

//connettiamoci al database con la prima soluzione--
		$resdett = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "stampa_pianoc.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}


// Esegue la query...
		if ($resdett)
		{

			foreach ($resdett as $dati_dett)
			{
//elenco righe dettaglio ordine
//chiamo la funzione impegni
				$_impegni = impegni_articolo($_cosa, $dati_dett['articolo'], $_anno);

				if ($dati_dett['quantita'] == "0.00")
				{
					$dati_dett['quantita'] = "";
				}
				if ($dati_dett['qtaestratta'] == "0.00")
				{
					$dati_dett['qtaestratta'] = "";
				}
				if ($dati_dett['qtaevasa'] == "0.00")
				{
					$dati_dett['qtaevasa'] = "";
				}

				if ($dati_dett['qtasaldo'] != "0.00")
				{
//ora per ogni orndine facciamo apparire le righe con lo status..
					echo "<tr>";
					echo "<td width=\"30\" align=\"center\"><font size=\"1\">$dati_dett[rigo] </td>";
					echo "<td width=\"50\" align=\"center\"><font size=\"1\">$dati_dett[articolo] </td>";
					echo "<td width=\"450\" align=\"left\"><font size=\"1\">" . substr($dati_dett[descrizione], 0, 50) . " </td>";
					echo "<td width=\"50\" align=\"right\"><font size=\"1\">$dati_dett[quantita] </td>";
					echo "<td width=\"50\" align=\"right\"><font size=\"1\"><b>$dati_dett[qtaevasa]</b> </td>";
					echo "<td width=\"50\" align=\"right\"><font size=\"1\"><B>$dati_dett[qtaestratta]</b> </td>";
					echo "<td width=\"50\" align=\"center\"><font size=\"1\">". substr($dati_dett[consegna], 0, 5) ."</td>";
					echo "<td width=\"30\" align=\"right\"><font size=\"1\">$_impegni[giacenza] </td>";
					echo "<td width=\"30\" align=\"right\"><font size=\"1\">$_impegni[ordinato] </td>";
					echo "</tr>";
				}
			}
		}



		echo "<tr><td colspan=\"9\"><hr></td></tr>";
	}
}
else
{
	echo "<h2>Nessun Documento trovato</h2>\n";
}
echo "</table></body></html>";

$conn->null;
$conn = null;
?>