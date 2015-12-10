<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso ."../setting/vars.php";
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);
require "../../../setting/par_conta.inc.php";

if ($_GET['stampa'] == "SI")
{
	base_html_stampa("chiudi", $_parametri);
}
else
{
//carichiamo la base delle pagine:
	base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
	testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
	menu_tendina($_cosa, $_percorso);

	echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\" border=\"0\">\n";
	echo "<tr>\n";
}


if ($_SESSION['user']['contabilita'] > "1")
{


	$_azione = $_GET['azione'];




	if ($_azione == "acq")
	{
		$_tipo = "fornitori acquisto";

		#$sql = "SELECT * , (SUM( dare ) - SUM( avere ) ) AS diff FROM prima_nota WHERE (causale='PA' OR causale='FA') AND conto = '3027' GROUP BY anno_proto, nproto ORDER BY anno_proto, nproto";
		$query = "SELECT * , date_format(data_doc, '%d-%m-%Y') as dataita, (SUM( avere ) - SUM( dare ) ) AS diff FROM prima_nota WHERE (causale='PA' OR causale='FA') AND conto like '$MASTRO_FOR%' GROUP BY conto, anno_proto, nproto ORDER BY desc_conto, anno, anno_proto, nproto";
		
		$_conto = "conto_acq";
	}
	elseif ($_azione == "conto_acq")
	{
		$_tipo = "fornitori in sospeso";
		$query = "SELECT * , (SUM( avere ) - SUM( dare ) ) AS diff FROM prima_nota WHERE conto like '$MASTRO_FOR%' GROUP BY conto ORDER BY desc_conto, anno";
		
	}
	elseif ($_azione == "conto_vend")
	{
		$_tipo = "clienti in sospeso";
		$query = "SELECT * , (SUM( dare ) - SUM( avere ) ) AS diff FROM prima_nota WHERE conto like '$MASTRO_CLI%' GROUP BY conto ORDER BY desc_conto, anno";
		
	}
	else
	{
		$_tipo = "fatture vendita";
		$query = "SELECT * , date_format(data_doc, '%d-%m-%Y') as dataita, (SUM( dare ) - SUM( avere ) ) AS diff FROM prima_nota WHERE (causale='FV' OR causale='IN') AND conto like '$MASTRO_CLI%' GROUP BY anno_doc, ndoc order by desc_conto, anno";
		
		$_conto = "conto_vend";
	}

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
	echo "<td><table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"2\" border=\"0\">\n";
	echo "<td colspan=\"6\" align=\"center\" valign=\"top\" class=\"foto\">\n";
	echo "<h2>Elenco $_tipo in sospeso </h2>\n";
	echo "<h2><a href=\"check_fatt.php?azione=$_conto\">Sospesi per conto</a> </h2>\n";
	echo "<h2><a href=\"check_fatt.php?azione=$_azione&stampa=SI\" target=\"_blank\">Stampa elenco</a> </h2>\n";
	echo "</td></tr>";

	echo "<tr><td align=\"left\"><font size=\"2\"><b>Reg.</b></td><td align=\"left\"><font size=\"2\"><b>Utente</b></td><td align=\"left\"><font size=\"2\"><b>Descrizione</b></td><td align=\"left\"><font size=\"2\"><b>Num. doc / data</b> </td><td align=\"left\"><font size=\"2\"><b>Prot. nr. e anno</b> </td><td align=\"left\"><font size=\"2\"><b> Valore diff.</b></td></tr>\n";
	
	$_conta = "";
	
        foreach ($result AS $row)
	{
		if ($row['diff'] != "0.00")
		{
			printf("<tr><td align=\"left\"><font size=\"1\">$row[nreg]</td><td align=\"left\"><font size=\"1\">  %s </td><td align=\"left\"><font size=\"1\">  %s </td><td align=\"left\"><font size=\"1\">nr. %s del  %s </td><td align=\"left\"><font size=\"1\"> nr. %s /  %s </td><td align=\"right\"><font size=\"1\" >€ %s</td></tr>\n", $row['desc_conto'], $row['descrizione'], $row['ndoc'], $row['dataita'], $row['nproto'], $row['anno_proto'], $row['diff']);
			$_conta = $_conta + 1;
		}
	}



	echo "<tr><td colspan=\"6\">Numero registrazioni = $_conta";
	echo "</td></tr>\n";
	echo "</table></body></html>\n";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>