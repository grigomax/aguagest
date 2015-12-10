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



if ($_SESSION['user']['setting'] > "3")
{

    echo "<table width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\" width=\"80%\" valign=\"top\">\n";

	if ($_GET['azione'] == "")
	{

		echo "<span class=\"intestazione\"><b>Programma che elimina i preventivi inevasi dall'archivio</span><br>\n";
		echo "<span class=\"intestazione\"><b><font color=\"RED\">ATTENZIONE L'OPERAZIONE E IRREVERSIBILE</FONT></span><br>\n";
		echo "<span class=\"intestazione\"><b><br>Selezionare Anno</span><br>\n";



		//query Sql

		$query = "SELECT * FROM pv_testacalce WHERE status != 'evaso' GROUP BY anno ORDER BY anno";

		$result = $conn->query($query);

		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_doc_pdo.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$_errori['errori'] = "NO";
		}


		echo "<table width=\"80%\" align=\"center\">\n";
		echo "<form action=\"elimina_doc.php\" method=\"GET\">\n";

		foreach ($result AS $datidoc)
		{
			echo "<tr><td> Preventivi anno </td><td><a href=\"elimina_doc.php?azione=$datidoc[anno]\">$datidoc[anno]</td></tr>\n";
		}

		echo "</form>\n";
		
		
		echo "</table>\n";
	}
	else
	{
		echo "<span class=\"intestazione\"><b>Eliminazione Anno..$_GET[azione]</span><br>\n";

		//prima di eliminarli devo selezionarli

		echo "<br>Selezioniamo L'anno\n";
		$query = "SELECT anno, ndoc, status FROM pv_testacalce where status != 'evaso' AND anno='$_GET[azione]'";

		$result = $conn->query($query);

		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_doc_pdo.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$_errori['errori'] = "NO";
			echo $query;
		}

		foreach ($result AS $datidoc)
		{
			//ora per ogni uno elimino tutto..

			$query = "DELETE FROM pv_dettaglio where anno='$datidoc[anno]' AND ndoc='$datidoc[ndoc]'";


			$result = $conn->exec($query);

			if ($conn->errorCode() != "00000")
			{
				$_errore = $conn->errorInfo();
				echo $_errore['2'];
				//aggiungiamo la gestione scitta dell'errore..
				$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
				$_errori['files'] = "motore_doc_pdo.php";
				scrittura_errori($_cosa, $_percorso, $_errori);
				$_errori['errori'] = "NO";
			}
			else
			{
				echo "<br>Eliminazione Detteglio Avvenuta con successo $datidoc[ndoc]";
			}
			
			 $query = "DELETE FROM pv_testacalce where anno='$datidoc[anno]' AND ndoc='$datidoc[ndoc]'";
			
			 $result = $conn->exec($query);

			if ($conn->errorCode() != "00000")
			{
				$_errore = $conn->errorInfo();
				echo $_errore['2'];
				//aggiungiamo la gestione scitta dell'errore..
				$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
				$_errori['files'] = "motore_doc_pdo.php";
				scrittura_errori($_cosa, $_percorso, $_errori);
				$_errori['errori'] = "NO";
			}
			else
			{
				echo "<br>Eliminazione testata Avvenuta con successo $datidoc[ndoc]";
			}
			
			
			
		}
		
		
		
		
	}



	echo "</td></tr>\n";
	echo "</table>\n";
	echo "</body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>