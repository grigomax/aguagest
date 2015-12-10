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
$conn = permessi_sessione("verifica", $_percorso);

require "../../librerie/motore_anagrafiche.php";
//includo file per generazione pdf
define('FPDF_FONTPATH', '../../tools/fpdf/font/');
require('../../tools/fpdf/fpdf.php');
require "libri_contabili_pdf.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{



	$_azione = $_POST['azione'];

	if ($_POST['azione'] == "definitivo")
	{


		//mettiamo in definitivo il giornale..

		$_anno = $_POST['anno'];
		$query = "UPDATE prima_nota set status='chiuso' where anno='$_POST[anno]' AND giornale >= '$_POST[prog_start]' AND giornale <='$_POST[prog_end]'";

		mysql_query($query, $conn);

		$query = "SELECT giornale, SUM(DARE) AS dare from prima_nota where anno='$_anno' AND giornale != ''";
		$result = mysql_query($query, $conn);

		$dati_val = mysql_fetch_array($result);

		$query = "SELECT giornale from prima_nota where anno='$_anno' ORDER BY giornale DESC limit 1";
		$result = mysql_query($query, $conn);

		$dati = mysql_fetch_array($result);



		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
		echo "<tr>";

		echo "<td>";

		echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
		echo "<h2>Stampa libro giornale</h2>";

		echo "<h3>Giornale Stampato in definitivo </h3>\n";
		echo "<h4>Ultimo numero = <input type=\"radio\" name=\"prog\" value=\"$dati[giornale]\" checked>$dati[giornale] &nbsp; Valore = <input type=\"radio\" name=\"valore\" value=\"$dati_val[dare]\" checked>$dati_val[dare] </h4>\n";

		echo "</td>\n";
		echo "</table></body></html>";
	}
	elseif ($_POST['azione'] == "Annulla")
	{
		//mettiamo in definitivo il giornale..

		$query = "UPDATE prima_nota set giornale='0' where anno='$_POST[anno]' AND giornale >= '$_POST[prog_start]' AND giornale <='$_POST[prog_end]'";
		//echo $query;
		mysql_query($query, $conn);


		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
		echo "<tr>";

		echo "<td>";

		echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
		echo "<h2>Stampa libro giornale</h2>";

		echo "<h3>Giornale Annullato come Richiesta.. </h3>\n";

		echo "</td>\n";
		echo "</table></body></html>";
	}
	else
	{
//qui parte l'avventura del sig. buonaventura...
		//verifichiamo tutte le date...
		$_checkdate = verifica_data($cosa, $_POST['data_start']);
		if ($_checkdate['errore'] == "error")
		{
			echo "$_checkdate[descrizione] - data partenza $_POST[data_start]";

			exit;
		}
		$_checkdate = "";

//verifichiamo tutte le date...
		$_checkdate = verifica_data($cosa, $_POST['data_end']);
		if ($_checkdate['errore'] == "error")
		{
			echo "$_checkdate[descrizione] - data fine $_POST[data_end]";

			exit;
		}
		$_checkdate = "";


		$_title = "libro_giornale";
//Recupero i post.. e giro le date..

		$_data_start = cambio_data("us", $_POST['data_start']);
		$_data_end = cambio_data("us", $_POST['data_end']);
		$_anno = cambio_data("anno_it", $_POST['data_start']);
		$_prog = $_POST['prog'];
		$_prog_start = $_prog + 1;
		$dare = $_POST['valore'];
		$avere = $_POST['valore'];

		function update_primanota($_anno, $_nreg, $_prog)
		{
			global $conn;
			$query = "UPDATE prima_nota SET giornale='$_prog' WHERE anno='$_anno' AND nreg='$_nreg'";

			$res = mysql_query($query, $conn);
			//echo $query;
		}

//query

		$query = "SELECT *, date_format(data_reg, '%d-%m-%Y') AS data_vista FROM prima_nota WHERE status != 'chiuso' AND data_reg >= '$_data_start' AND data_reg <= '$_data_end' ORDER BY data_reg, nreg, rigo";


		$result = mysql_query($query, $conn) or mysql_error();

		if (mysql_num_rows($result) < "1")
		{
			//Vuol dire che no ghe se righe..
			//inizio parte visiva..


			echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
			echo "<tr>";

			echo "<td>";

			echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
			echo "<h2>Stampa libro giornale</h2>";
			echo "<h4>Attenzione Data immessa non valida .. </h4>";
			echo "<h4>Oppure non ci sono righe da stampare per la data immessa </h4>";
			echo "<h4>Oppure libro giornale appartenete a questa data gi&agrave; STAMPATO.</h4>";

			echo "</td>\n";
			echo "</table></body></html>";

			exit;
		}
		else
		{

//inizio generazione file in formato pdf..
//richiamo le funzioni che sono all'intrerno del file stampe_doc_pdf.inc.php'
// setto le variabili standard creazione pdf
			//creiamo un nuovo files pdf

			new_pdf($_cosa, $_title);


			intesta_colonna("inizio");

			while ($dati = mysql_fetch_array($result))
			{

				$_y = $pdf->GetY();

				if ($_y >= "280")
				{
					intesta_colonna("");
				}


				if ($dati['nreg'] != $_nreg)
				{
					$pdf->SetX(10);
					$pdf->Cell(191, 1, '', 'T', 1, '');
				}

				if ($dati['rigo'] == "1")
				{
					$_prog = $_prog + 1;
					registrazione("prima", $dare, $avere);
					registrazione("", $dare, $avere);
				}
				else
				{
					registrazione("", $dare, $avere);
				}
				$_nreg = $dati['nreg'];

				update_primanota($_anno, $_nreg, $_prog);


//    $sbilancio = $dare - $avere;
//    echo "dare = $dare Avere = $avere  reg. nr $_nreg sbilancio $sbilancio<br>\n";
			}



			$_y = $pdf->GetY();

			if ($_y >= "280")
			{
				intesta_colonna("");
			}

			chiusura("", $dare, $avere, $_anno);

			if ($_y >= "280")
			{
				intesta_colonna("");
			}
			sbarra();

//generazione del files..
			$_pdf = "$_title.pdf";
			$pdf->Output("../../../spool/$_pdf", 'F');

//inizio parte visiva..



			echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
			echo "<tr>";

			echo "<td>";

			echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
			echo "<h2>Stampa libro giornale</h2>";
			echo "<form action=\"libro_giornale_pdf.php\" method=\"POST\">\n";
			echo "<h3>Confermare in definitivo la stampa del giornale.. ? </h3>\n";
			echo "<h4>Primo numero = <input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>$_anno\n</h4>";
			echo "<h4>Primo numero = <input type=\"radio\" name=\"prog_start\" value=\"$_prog_start\" checked>$_prog_start &nbsp;  AL numero <input type=\"radio\" name=\"prog_end\" value=\"$_prog\" checked>$_prog </h4>\n";


			echo "<input type=\"submit\" name=\"azione\" value=\"definitivo\" onclick=\"if(!confirm('Confermi la Stampa in definitivo ? Una Volta confermato non sar&agrave; pi&ugrave; possibile tornare indietro. !')) return false;\" > Oppure <input type=\"submit\" name=\"azione\" value=\"Annulla\">\n";
			echo "</form>\n";
			echo "<br>\n";
			echo "<h4 align=\"center\"><a href=\"../../../spool/$_pdf\">Clicca qui per prelevare il file in pdf con il libro giornale</a></h4>";

			echo "</td>\n";
			echo "</table></body></html>";
		}
	}
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>