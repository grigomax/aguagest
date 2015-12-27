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

require $_percorso . "librerie/motore_doc_pdo.php";

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
	
	echo "<table align=\"center\" width=\"95%\" border=\"0\">\n";
	echo "<tr><td align=\"center\" valign=\"center\">\n";
	echo "<span class=\"logo\"><center><b><style=\"font-size: 2em;\">Elenco $_tdoc non evasi per utente</b>\n";
	echo "</td>\n";

	echo "<td align=\"center\" valign=\"center\">\n";
	echo "<a href=\"../docubase/stampa_elenco_doc_xcli.php?anno=$_anno&tdoc=$_tdoc\" target=no >Stampa Questa Pagina ==><img src=\"../../images/printer.png\" valign=\"center\" width=\"50\"></a></span></center>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "<br>\n";
// Stringa contenente la query di ricerca...
// 	prendo l'anno in corso 


	if ($_tdoc == "fornitore")
	{
		$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN fornitori ON $_dbdoc[testacalce].utente = fornitori.codice where status != 'evaso' order by ragsoc, anno, ndoc");
	}
	elseif($_tdoc == "preventivo")
	{
		$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where status != 'evaso' AND data_scad >= '$_oggi' order by ragsoc, anno, ndoc");
	}
	else
	{
		$query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where status != 'evaso' AND status != 'saldato' order by ragsoc, anno, ndoc");
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

        

	// La query ?stata eseguita con successo...
	// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...

	echo "<form action=\"../docubase/visualizzadoc.php?tdoc=$_tdoc\" method=\"POST\">\n";
	// Tutto procede a meraviglia...
	echo "<table align=\"center\" width=\"95%\" border=\"0\">\n";
	echo "<tr>\n";

	echo "<td width=\"100\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>\n";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>\n";
	echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>\n";
	echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>\n";
	echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>\n";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>\n";
        echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Inoltro</span></td>\n";
	echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>\n";
	echo "</tr>\n";

// azzero la variabile
	$_valoretot = 0;
	$_totdoc = "0";
	foreach ($result as $dati)
	{

		$_codcli = $dati['codice'];

		if ($_prima != "NO")
		{
			$_codcli1 = $_codcli;
		}

		if ($_codcli1 != $_codcli)
		{
			echo "<tr><td></td><td></td><td></td><td align=\"right\"><font size=\"2\">Valore ivato == </font>  </td><td> <font size=\"2\">$_valoretot</font> </td><td></td><td></td></tr>\n";
			echo "<tr>\n";
			echo "<td width=\"100\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
			echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
			echo "<td width=\"40\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
			echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
			echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
                        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
			echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
			echo "</tr>\n";
			//azzero le variabili
			$_valoretot = 0;
			echo "<tr>\n";

			printf("<td width=\"100\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['datareg']);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s / %s</b></span></td>\n", $dati['ndoc'], $dati['suffix']);
			printf("<td width=\"40\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['codice']);
			printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>\n", $dati['ragsoc']);
			printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['totimpo']);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['status']);
                        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['invio']);
			printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><button type=\"submit\" name=\"annondoc\" value=\"%s%s%s\">$dati[ndoc]</button></td>\n", $dati['anno'], $dati['suffix'], $dati['ndoc']);
			#echo "</form>
			echo "</tr>\n";
			$_valoretot = $_valoretot + $dati['totdoc'];
			//$_totimpo = $_totimpo + $dati['totimpo'];
			$_codcli1 = $_codcli;
		}
		else
		{
			echo "<tr>";
			#printf("<form action=\"../docubase/visualizzadoc.php?tdoc=$_tdoc&anno=%s\" method=\"POST\">\n", $dati['anno']);
			printf("<td width=\"100\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['datareg']);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s / %s</b></span></td>\n", $dati['ndoc'], $dati['suffix']);
			printf("<td width=\"40\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['codice']);
			printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>\n", $dati['ragsoc']);
			printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['totimpo']);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['status']);
                        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>\n", $dati['invio']);
			printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><button type=\"submit\" name=\"annondoc\" value=\"%s%s%s\">$dati[ndoc]</button>\n", $dati['anno'], $dati['suffix'], $dati['ndoc']);

			$_valoretot = $_valoretot + $dati['totdoc'];
			//$_totimpo = $_totimpo + $dati['totimpo'];

			$_codcli1 = $_codcli;
			$_prima = "NO";
		}


		$_totdoc = $_totdoc + $dati['totdoc'];
		$_totimpo = $_totimpo + $dati['totimpo'];
	}


	echo "</form></tr>\n";
        echo "</table>\n";
        echo "<table align=\"center\" width=\"95%\" border=\"0\">\n";
	echo "<tr><td colspan=\"7\"><hr/></td></tr>\n";
	echo "<tr><td colspan=\"2\" align=\"right\">Valore totale Imponibile</td><td>$_totimpo</td><td colspan=\"3\"align=\"right\">Valore totale ivato</td><td>$_totdoc</td></tr>\n";
	echo "</table></body></html>\n";

	//chiudiamo le connessioni
	$conn->null;
	$conn = null;
}
else
{

	permessi_sessione($_cosa, $_percorso);
}
?>