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

require $_percorso . "librerie/motore_anagrafiche.php";
require $_percorso . "librerie/motore_doc_pdo.php";
//carichiamo la base delle pagine:
base_html("", $_percorso);


jquery_datapicker($_cosa, $_percorso);

echo "</head>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{


//mi prendo i post
	$_codutente = $_POST['utente'];

// setto i post e le variabili
	$_anno = date("Y");
	$_start = $_POST['start'];
	$_end = $_POST['end'];
	$_azione = $_POST['azione'];
        $_suffix_end = $_POST['suffix'];

#mi prendo i documenti

	$_archivio_start = archivio_tdoc($_start);
	$_archivio_end = archivio_tdoc($_end);

	if ($_azione == "Annulla")
	{
		annulla_doc_vendite($_dove, $_tdoc, $_anno, $_suffix, $_ndoc);
	}
	elseif ($_codutente == "")
	{
		//controllo il campo documenti
		echo "<h3>ATTENZIONE NESSUN UTENTE SELEZIONATO<h3>\n";
		echo "<h4>Si prega di tornare indietro e verificare</h4>\n";
		exit;
	}
	else
	{

		if ($_start == "fornitore")
		{
			$dati = tabella_fornitori("singola", $_codutente, $_parametri);
		}
		else
		{
			//$dati = seleziona_utente("cliente", "singolo", $_codutente);
			$dati = tabella_clienti("singola", $_codutente, $_parametri);
		}

		intesta_html($_tdoc, "normale", $dati, "");


		//controlliamo l'utente
		// in caso di blocco di inchioda tutto.
		blocco_utente($dati);


		echo "<table width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">";
		echo "<tr><td colspan=\"10\" align=\"center\" valign=\"top\">";
		echo "<font color=\"red\">ATTENZIONE lo status dei documenti deve essere Stampato o parziale !</b></font></span></td></tr>";

		printf("<form action=\"eseguimp_doc.php\" method=\"POST\">");
		echo "<tr><td align=\"center\" colspan=\"5\"><input type=\"radio\" name=\"codutente\" value=\"$_codutente\" checked>codice utente $_codutente</td>\n";
		echo "<td align=center colspan=\"5\"><input type=\"radio\" name=\"start\" value=\"$_start\" checked>Elenco $_start disponibili</td></tr>";
		//qui facciamo un elenco puntato dove poter scegliere il documento da esportare
		// Stringa contenente la query di ricerca...

		$_oggi = date('Y-m-d');
	
		if ($_start == "fornitore")
		{
			$query = sprintf("select * from %s INNER JOIN fornitori ON %s.utente = %s.codice where utente=\"%s\" and (status='stampato' OR status='parziale') order by anno, ndoc", $_archivio_start['testacalce'], $_archivio_start['testacalce'], "fornitori", $_codutente);
		}
		elseif (($_start == "ddt") AND ($_end == "FATTURA"))
		{
			$query = "select * from bv_bolle INNER JOIN clienti ON bv_bolle.utente = clienti.codice where utente='$_codutente' AND causale = 'VENDITA' AND status = 'stampato' order by anno, ragsoc, ndoc";
		}
		elseif (($_start == "ddt_diretto") AND ($_end == "FATTURA"))
		{
			$query = "select * from bvfor_testacalce INNER JOIN clienti ON bvfor_testacalce.utente = clienti.codice where utente='$_codutente' AND causale = 'VENDITA' AND status = 'stampato' order by anno, ragsoc, ndoc";
		}
		elseif($_start == "preventivo")
		{
			$query = sprintf("select * from %s INNER JOIN clienti ON %s.utente = %s.codice where data_scad >= '$_oggi' AND utente=\"%s\" and (status='stampato' OR status='parziale') order by anno, ndoc", $_archivio_start['testacalce'], $_archivio_start['testacalce'], "clienti", $_codutente);
		}
		else
		{
			$query = sprintf("select * from %s INNER JOIN clienti ON %s.utente = %s.codice where utente=\"%s\" and (status='stampato' OR status='parziale') order by anno, ndoc", $_archivio_start['testacalce'], $_archivio_start['testacalce'], "clienti", $_codutente);
		}

		#echo $query;

		$result = $conn->query($query);

		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "filtro_doc.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}


		echo "<tr>";
		echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Seleziona doc</span></td>";
		echo "<td width=\"100\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>";
		echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
		echo "<td align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
		echo "<td width=\"100\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
		echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
		echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Incl. Righe</span></td>";

		if (($_start == "conferma") OR ($_start == "fornitore"))
		{
			echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Parziale</span></td>";
			echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Totale</span></td>";
		}
		else
		{
			echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Totale</span></td>";
		}

                echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Ultima Mod.</span></td>";

		echo "</tr>";

		// azzero la variabile
		foreach ($result AS $dati_start)
		{
			echo "<tr>";
			printf("<td align=\"center\"><input type=\"checkbox\" name=\"numero[]\" value=\"%s%s%s\"></td>\n", $dati_start['anno'], $dati_start['suffix'], $dati_start['ndoc']);
			echo "<td align=\"center\"><span class=\"testo_blu\">$dati_start[datareg]</span></td>\n";
			echo "<td align=\"center\"><span class=\"testo_blu\"><b>$dati_start[ndoc] / $dati_start[suffix]</b></span></td>\n";
			echo "<td align=\"left\"><span class=\"testo_blu\">$dati_start[ragsoc]</span></td>\n";
			echo "<td align=\"center\"><span class=\"testo_blu\">$dati_start[status]</span></td>\n";
			echo "<td align=\"center\"><span class=\"testo_blu\">$dati_start[totimpo]</span></td>\n";
			echo "<td align=\"center\"><input type=\"checkbox\" name=\"righe".$dati_start[anno].$dati_start[suffix].$dati_start[ndoc]."\" value=\"SI\" checked></td>\n";

			if (($_start == "conferma") OR ($_start == "fornitore"))
			{
				if($dati_start['status'] == "parziale")
				{
					echo "<td align=\"center\"><input type=\"radio\" name=\"tipologia".$dati_start[anno].$dati_start[suffix].$dati_start[ndoc]."\" value=\"parz\" checked></td>\n";
					echo "<td align=\"center\"><input type=\"radio\" name=\"tipologia".$dati_start[anno].$dati_start[suffix].$dati_start[ndoc]."\" value=\"totale\"></td>\n";
				}
				else
				{
					echo "<td align=\"center\"><input type=\"radio\" name=\"tipologia".$dati_start[anno].$dati_start[suffix].$dati_start[ndoc]."\" value=\"parz\"></td>\n";
					echo "<td align=\"center\"><input type=\"radio\" name=\"tipologia".$dati_start[anno].$dati_start[suffix].$dati_start[ndoc]."\" value=\"totale\" checked ></td>\n";
				}
				
				
			}
			else
			{
				echo "<td align=\"center\"><input type=\"radio\" name=\"tipologia".$dati_start[anno].$dati_start[suffix].$dati_start[ndoc]."\" value=\"totale\" checked></td>\n";
			}

                        echo "<td align=\"center\"><span class=\"testo_blu\">$dati_start[ts]</span></td>\n";

			echo "</tr>";
                        
                        
                        
			echo "<tr>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";

			if (($_start == "conferma") OR ($_start == "fornitore"))
			{
				echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
				echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			}
			else
			{
				echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			}
                        
                        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
                        
			echo "</tr>";

			if ($dati_start['status'] == "parziale")
			{
				$_parziale = "SI";
			}
                        
                        
		}

		if ($_end == "ddtacq")
		{
			//se si carica un ddt di acquisto mi serve anche il numero ddt fornitore
			// Dobbiamo inserire due campi di riferimento alla bolla fonitore e fattura fornitore di riferimento.

			echo "<tr><td align=\"center\" colspan=\"10\" valign=\"top\"><br>N. ddt Fornitore: <input type=\"text\" name=\"ddtfornitore\" size=\"20\" maxlength=\"20\">\n";
			echo "Fattura fornitore: <input type=\"text\" name=\"fatturacq\" size=\"20\" maxlength=\"20\"></td></tr>\n";
		}

		// cerco l'ultimo numero di bolla inserito e lo propongo
		$_ndoc = seleziona_documento("ultimo_numero", $_end, $_anno, $_suffix_end, $_ndoc, $_archivio_end, $_parametri);
		//$_ndoc = ultimo_documento($_end, $_anno);

		$_datareg = date("d-m-Y");

		echo "<tr><td align=\"center\" colspan=\"10\"><br><input type=\"radio\" name=\"end\" value=\"$_end\" checked>Documento da creare = $_end / <input type=\"radio\" name=\"suffix_end\" value=\"$_suffix_end\" checked>Serie $_suffix_end</td></tr>\n";
		printf("<tr><td align=\"center\" colspan=\"10\">Numero Documento<input type=\"number\" name=\"ndoc\" size=\"10\" value=\"%s\" required> Anno <input type=\"number\" name=\"anno\" size=\"10\" value=\"%s\" required>", $_ndoc, $_anno);

		echo " Data <input type=\"text\" class=\"data\"  size=\"12\" name=\"datareg\" value=\"$_datareg\"></td></tr>\n";

		if (($_end == "FATTURA") OR ($_end == $nomedoc))
		{
			echo "<tr><td align=\"center\" colspan=\"10\"><br>Eventuali spese Bancarie <input type=\"text\" name=\"speseb\" size=\"6\" value=\"0.00\"> </td></tr>";
		}

		// invio di sette post

		echo "</table>\n";

		echo "<center><br>\n";

		echo "<input type=\"submit\" name=\"azione\" value=\"Annulla\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Evadi\">\n";

		echo "</form>\n</td>\n";
		echo "</td>\n</tr>\n";
	}
	echo "</body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>