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
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_GET['azione'] == "cerca")
{
	$_campi = "articolo";
	$_descrizione = $_GET['articolo'];
	$_descrizione = "%$_descrizione%";
}
else
{
	if ($_POST['cliente'] != "")
	{

		$_campi = $_POST['cliente'];
		$_tipo = "cliente";
	}
	else
	{
		$_campi = $_POST['fornitore'];
		$_tipo = "fornitore";
	}

	$_descrizione = $_POST['descrizione'];
	$_descrizione = "%$_descrizione%";
}


if ($_SESSION['user']['magazzino'] > "1")
{
	if ($_tipo == "cliente")
	{
		if ($_POST['magazzino'] == "magastorico")
		{
			$query = "select tdoc, anno, suffix, ndoc, datareg, ragsoc, utente, datareg, articolo, qtascarico AS quantita from magastorico INNER JOIN clienti ON magastorico.utente=clienti.codice where tut='c' AND $_campi like '$_descrizione' order by anno DESC, ndoc ASC";
		}
		else
		{
			$query = "select tdoc, anno, suffix, ndoc, datareg, ragsoc, utente, datareg, articolo, qtascarico AS quantita from magazzino INNER JOIN clienti ON magazzino.utente=clienti.codice where tut='c' AND $_campi like '$_descrizione' order by anno DESC, ndoc ASC";
		}
	}
	else
	{
		if ($_POST['magazzino'] == "magastorico")
		{
			$query = "select tdoc, anno, suffix, ndoc, datareg, ragsoc, utente, ddtfornitore, fatturacq, datareg, protoiva, articolo, qtacarico AS quantita from magastorico INNER JOIN fornitori ON magastorico.utente=fornitori.codice where tdoc='ddtacq' and $_campi like '$_descrizione' order by anno DESC, ndoc ASC";
		}
		else
		{
			$query = "select tdoc, anno, suffix, ndoc, datareg, ragsoc, utente, ddtfornitore, fatturacq, datareg, protoiva, articolo, qtascarico AS quantita from magazzino INNER JOIN fornitori ON magazzino.utente=fornitori.codice where tdoc='ddtacq' and $_campi like '$_descrizione' order by anno DESC, ndoc ASC";
		}
	}

	echo "<span class=\"testo_blu\"><center><br><b>Elenco documenti trovati</b> </center></span><br>";


	// Stringa contenente la query di ricerca...
	//$query = "select anno, ndoc, ragsoc, utente, ddtfornitore, fatturacq, datareg from magazzino INNER JOIN fornitori ON magazzino.utente=fornitori.codice where tdoc='ddtacq' and $_campi like '$_descrizione' order by ndoc ";
	// Esegue la query...

        $result = domanda_db("query", $query, $_cosa, $_ritorno, "verbose");

	// Tutto procede a meraviglia...
	echo "<table align=\"center\" width=\"90%\">";
	echo "<tr>";

	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Anno</span></td>";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>";
	echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
	echo "<td width=\"20\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">n. Iva</span></td>";
	if ($_campi == "articolo")
	{
		echo "<td width=\"20\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.Ta.</span></td>\n";
	}
	echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
	echo "</tr>";

	foreach ($result as $dati)
	{
		// tolgo i numeri di documento doppi
		if ($dati['ndoc'] != $_ndoc)
		{
			$_ndoc = $dati['ndoc'];
			echo "<tr>";
			printf("<form action=\"visualizzadoc.php?magazzino=$_POST[magazzino]&tdoc=%s&anno=%s&suffix=$dati[suffix]\" method=\"POST\">", $dati['tdoc'], $dati['anno']);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['anno']);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s/$dati[suffix]</b></span></td>", $_ndoc);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
			printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['fatturacq']);
			printf("<td width=\"20\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['protoiva']);
			if ($_campi == "articolo")
			{
				printf("<td width=\"20\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['quantita']);
			}
			printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"submit\" name=\"ndoc\" value=\"%s\"></td>", $dati['ndoc']);
			echo "</tr>";
			echo "<tr>";
			echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"20\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			if ($_campi == "articolo")
			{
				echo "<td width=\"20\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			}
			echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";

			echo "</tr></form>";
		}
	}


	echo "</td></tr></table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>