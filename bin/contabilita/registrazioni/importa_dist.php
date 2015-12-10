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

//carichiamo le librerie necessarie..
require $_percorso . "librerie/motore_anagrafiche.php";


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

	echo "<table align=\"center\" width=\"80%\" border=\"0\">";
	echo "<tr><td align=\"center\" valign=\"center\">";
	echo "<span class=\"testo_blu\"><center><h3><b>Registrazione Distinte Bancarie in contabilit&agrave;</h3></b>";
	echo "</td>";
	echo "<tr><td align=\"center\" valign=\"center\">";
	echo "<span class=\"testo_blu\"><center><h3>Seleziona i documenti, una volta importati non sar&agrave; pi&ugrave; possibile modificarli nel reparto vendite</h3>";
	echo "</td></tr>\n";
	echo "</table>";
// Stringa contenente la query di ricerca...
// 	prendo l'anno in corso

	$query = "select *, SUM(impeff) as impeff from effetti INNER JOIN banche ON effetti.bancadist = banche.codice where presenta='SI' AND contabilita != 'SI' GROUP BY ndistinta, datadist order by annoeff, ndistinta ASC ";

// Esegue la query...
	$result = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
		echo $_errore['2'];
		//aggiungiamo la gestione scitta dell'errore..
		$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
		$_errori['files'] = "importa_dist";
		scrittura_errori($_cosa, $_percorso, $_errori);
	}

	// Tutto procede a meraviglia...
	echo "<table width=\"80%\" align=\"center\" border=\"0\">";
	echo "<form action=\"importa_dist2.php\" method=\"POST\">\n";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Dist.</span></td>";
	echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
	echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Banca Presentazione</span></td>";
	echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Tipo Pres.</span></td>";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Seleziona</span></td>";
	echo "</tr>";

	foreach ($result AS $dati)
	{

		echo "<tr>";
		echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"30\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"40\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";

		echo "</tr>";
		//azzero le variabili
		//      $_valoretot = 0;
		echo "<tr>";
		printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datadist']);
		printf("<td width=\"30\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['ndistinta']);
		printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['banca']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['impeff']);
		printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['tipo_pres']);
		printf("<td width=\"30\" align=\"center\"><input type=checkbox name=\"numero[]\" value=\"%s%s\"></td>\n", $dati['annoeff'], $dati['ndistinta']);
		echo "</tr>";
	}


	echo "</td></tr>\n";
	echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
	echo "<tr><td colspan=\"6\" align=\"right\" class=\"testo_blu\"><input type=\"submit\" name=\"azione\" value=\"vai\"></td>";
	echo "</FORM></table></body></html>";

	$conn->null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>