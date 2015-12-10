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
require $_percorso . "librerie/motore_doc_pdo.php";

//passaiamo la funzione alla sessione
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

	//prima di tutto ci prendiamo il tipo di documento

	$_tdoc = $_GET['tdoc'];

	if ($_POST['anno'] == "")
	{
		$_anno = date('Y');
	}
	else
	{
		$_anno = $_POST['anno'];
	}

//poi ci selezioniamo il documento..
//selezioniamo il database documenti..
	$_dbdoc = archivio_tdoc($_tdoc);
	$_oggi = date('Y-m-d');

	echo "<span class=\"testo_blu\"><center><br><h2>Elenco $_tdoc non evasi per Utente</h2></center></span><br>";

	if ($_tdoc == "fornitore")
	{
		$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN fornitori ON $_dbdoc[testacalce].utente = fornitori.codice where status != 'evaso' order by anno, ndoc");
	}
	elseif ($_tdoc == "FATTURA")
	{
		$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where status != 'saldato' and status !='evaso' order by anno, ndoc");
	}
	elseif($_tdoc == "preventivo")
	{
		$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where status != 'evaso' AND data_scad >= '$_oggi' order by anno, ndoc");
	}
	else
	{
		$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where status != 'evaso' order by anno, ndoc");
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

        // Tutto procede a meraviglia...
	echo "<table width=\"95%\" align=\"center\">";
	echo "<tr>";

	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
	echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
	echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
	echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Inoltro</span></td>";
	echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
	echo "</tr>";

	
	foreach ($result as $dati)
	{
            
		if(($_tdoc == "FATTURA") OR ($_tdoc == "NOTA CREDITO") OR ($_tdoc == "NOTA DEBITO") OR ($_tdoc == $nomedoc))
		{
			echo "<form action=\"../docubase/visualizzadoc.php?tdoc=$dati[tdoc]\" method=\"POST\">\n";
		}
                else
                {
                    echo "<form action=\"../docubase/visualizzadoc.php?tdoc=$_tdoc\" method=\"POST\">\n";
                }

		echo "<tr>";
		printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
		printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s/%s</b></span></td>", $dati['ndoc'], $dati['suffix']);
		printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['totimpo']);
		printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['status']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['invio']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><button type=\"submit\" name=\"annondoc\" value=\"%s%s%s\">$dati[ndoc]</button>\n", $dati['anno'], $dati['suffix'], $dati['ndoc']);
		echo "</tr>";
		echo "<tr>";
		echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "</tr>";
		$_imponibile = $_imponibile + $dati['totimpo'];
		$_valoretot = $_valoretot + $dati['totdoc'];
		echo "</form>\n";
	}
	
	echo "</td></tr>\n";

        echo "</table>\n";
        echo "<table align=\"center\" width=\"90%\">\n";
	echo "<tr><td colspan=\"7\"><hr/></td></tr>\n";
	echo "<tr><td colspan=\"3\" align=\"right\">Valore totale Imponibile</td><td>$_imponibile</td><td colspan=\"2\"align=\"right\">Valore totale ivato</td><td>$_valoretot</td></tr>\n";


	echo "</table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>