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
$conn = permessi_sessione("verifica", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{

	echo "<center><br><br><br> Inizio procedura<br><br>";

//recupero le variabili
	$_anno = $_POST['anno'];
	$_azione = $_POST['azione'];
	$_annonuovo = $_anno + 1;

//ora procedo alla verifica che tutti di documenti importanti siano evasi..
// come le bolle e fatture siano evasi..
	if ($CONTABILITA == "SI")
	{
		$query = sprintf("(select status, anno, ndoc, utente from bv_bolle where status != 'evaso' and anno=\"%s\") UNION (select status, anno, ndoc, utente from fv_testacalce where status != 'saldato' AND anno=\"%s\")", $_anno, $_anno);
	}
	else
	{
		$query = sprintf("(select status, anno, ndoc, utente from bv_bolle where status != 'evaso' and anno=\"%s\") UNION (select status, anno, ndoc, utente from fv_testacalce where status != 'evaso' AND anno=\"%s\")", $_anno, $_anno);
	}
// Esegue la query...
#echo $query;
	$res = mysql_query($query, $conn);

#echo $res;

	@$_risultato = mysql_num_rows($res) or mysql_error();
#echo $_risultato;
// La query e' stata eseguita con successo...
// Non so' se ci sono articoli con quel codice...
	if ($_risultato > "0")
	{
		echo " Impossibile proseguire perch&egrave; risultano muovimentati i seguenti muovimenti. e/o non saldati in contabilità";
		echo " <table border=1><tr>";
		echo " <td>status</td><td>anno</td><td>n. doc.</td><td>utente</td></tr>";
		while ($dati = mysql_fetch_array($res))
		{

			printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $dati['status'], $dati['anno'], $dati['ndoc'], $dati['utente']);
		}
		echo "</table>";
	}
	else
	{

		if ($_azione == "Aggiusta")
		{

// il programma gi aggiustamento non fa altro che copiare i muovimenti relativi agli anni sbagliati nel magazzino storico..
// si il muovimento c'è gia lo aggiorno altrimenti lo inserisco..

			echo "lettura muovimenti errati.... <br>\n";
			echo "Progress...30%<br>\n";
			$query = sprintf("SELECT * FROM magazzino WHERE anno <= \"%s\" ORDER BY datareg DESC", $_anno);

// Esegue la query...
			$res = mysql_query($query, $conn) or mysql_error();

			while ($dati = mysql_fetch_array($res))
			{

				echo "Rimozione muovimenti vecchi inseriti per aggiornamento.. \n";

// elimino in ogni caso l'anno prima di inserirlo
				$query = "DELETE FROM magastorico WHERE anno='$dati[anno]' AND datareg='$dati[datareg] AND articolo='$dati[articolo]' AND tut='$dati[tut]' LIMIT 1 ";

// Esegue la query...
				mysql_query($query, $conn) or mysql_error();

				echo "Eseguito <br>";


				echo "Inizio Copie da Magazzino attuare a Storico movimenti errati.. <br>";
				echo "Progress...<br>\n";

				$query = "INSERT INTO `agua`.`magastorico` (`tdoc` ,`anno` ,`ndoc` ,`datareg` ,`tut` ,`rigo` ,`utente` ,`articolo` ,`qtacarico` ,`valoreacq` ,`qtascarico` ,`valorevend` ,`ddtfornitore` ,`fatturacq` ,`protoiva` ,`ts` )
            VALUES ('$dati[tdoc]', '$dati[anno]', '$dati[ndoc]', '$dati[datareg]', '$dati[tut]', '$dati[rigo]', '$dati[utente]', '$dati[articolo]', '$dati[qtacarico]', '$dati[valoreacq]', '$dati[qtascarico]', '$dati[valorevend]', '$dati[ddtfornitore]', '$dati[fatturacq]', '$dati[protoiva]', CURRENT_TIMESTAMP )";
// Esegue la query...
				if (mysql_query($query, $conn) != 1)
				{
					echo "impossibile proseguire errore numero 1<br>";
					echo $query;
					exit;
				}
				else
				{
					echo "Eseguito... <br>";
				}

				echo "Eliminazione dal magazzino attuale... <br>\n";

				// elimino in ogni caso l'anno prima di inserirlo
				$query = "DELETE FROM magazzino WHERE anno='$dati[anno]' AND datareg='$dati[datareg]' AND articolo='$dati[articolo]' AND tut='$dati[tut]' limit 1";

				if (mysql_query($query, $conn) != 1)
				{
					echo "impossibile proseguire errore numero 2<br>";
					echo $query;
					echo mysql_error();
					exit;
				}
				else
				{
					echo "Eseguito... <br>";
				}
			}

			echo "Progress.... 100% <br>\n";
		}
		else
		{

			// ora devo copiare tutto il magazzino attuale e copiarlo in quello storico
			// mi conviene ordinarlo per data
			// prima di tutto mi conviene eliminare eventuali tracce di record presenti per l'anno corrente prima
			// di mettere le righe nuove
			echo "Rimozione eventuale prova precedente.....";

			// elimino in ogni caso l'anno prima di inserirlo
			$query0 = sprintf("DELETE FROM magastorico WHERE anno=\"%s\"", $_anno);

			// Esegue la query...
			if (mysql_query($query0, $conn) != 1)
			{
				echo "impossibile proseguire errore numero 1";
				exit;
			}
			else
			{
				echo "Eseguito <br><br>";
			}

			echo "Inizio Copie da Magazzino attuare a Storico........";

			// se tutto ok procedo prendo tutto

			$query1 = sprintf("SELECT * FROM magazzino WHERE anno=\"%s\" ORDER BY datareg", $_anno);

			// Esegue la query...
			if ($res1 = mysql_query($query1, $conn))
			{//1
				// La query ?stata eseguita con successo...
				// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
				if (mysql_num_rows($res1))
				{//2
					// prendo tutti i dati fin che li trova
					while ($dati1 = mysql_fetch_array($res1))
					{//3
						// ora procedo ad inserirli nel magazzino storico
						// sono arrivato qui
						$query2 = sprintf(" INSERT INTO magastorico (tdoc, anno, ndoc, datareg, tut, rigo, utente, articolo, qtacarico, valoreacq, qtascarico, valorevend, ddtfornitore, fatturacq, protoiva, ts) values ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\")", $dati1['tdoc'], $dati1['anno'], $dati1['ndoc'], $dati1['datareg'], $dati1['tut'], $dati1['rigo'], $dati1['utente'], $dati1['articolo'], $dati1['qtacarico'], $dati1['valoreacq'], $dati1['qtascarico'], $dati1['valorevend'], $dati1['ddtfornitore'], $dati1['fatturacq'], $dati1['protoiva'], $dati1['ts']);

						if (mysql_query($query2, $conn) != 1)
						{
							echo $query2;
							echo "Per sicurezza blocco la funzione\n";
							exit;
						}
					}//3
				}//2
			}//1

			echo "Eseguito. <br><br>";
			echo "Inizio svuotamento archivio di magazzino attuale.....";
			// Ora ho finito di copiare tutto
			// procedo allo svuotamento del magazzino attuale
			// elimino in ogni caso l'anno prima di inserirlo
			$query3 = "DELETE FROM magazzino WHERE anno='$_anno'";

			// Esegue la query...
			if (mysql_query($query3, $conn) != 1)
			{
				echo "impossibile proseguire errore numero 3";
				exit;
			}
			else
			{
				echo "Eseguito. <br><br>";
			}
			echo "Inizio riporto giacenze finale in iniziale nell' archivio di magazzino attuale.....";

			// ora per poter iniziare a riportare le rimanenze finali per l'anno nuovo devo prendere tutta l'anagrafica articoli.. quindi

			$query4 = "SELECT articolo FROM articoli ORDER BY articolo";

			// Esegue la query...
			if ($res4 = mysql_query($query4, $conn))
			{//1
				// prendo tutti i dati fin che li trova
				while ($dati4 = mysql_fetch_array($res4))
				{//3
					// ora che ho tutti gli articoli ordinati procedo a prendermeli dal magastorico
					$query5 = sprintf("SELECT (SUM(qtacarico) - SUM(qtascarico)) AS qtafinale, (SUM(valoreacq) / SUM(qtacarico)) * (SUM(qtacarico) - SUM(qtascarico)) AS valorefin FROM `magastorico` where articolo=\"%s\" AND anno=\"%s\"", $dati4['articolo'], $_anno);
					//echo $query5;
					//eseguo la query
					if ($res5 = mysql_query($query5, $conn))
					{//1
						mysql_num_rows($res5);

						if ($res5 >= 1)
						{//2
							$dati5 = mysql_fetch_array($res5);
							// ora procedo ad inserirli nel magazzino nuovo
							$_tut = "giain";
							$_mezzo = "-01.01";
							$_data = $_annonuovo . $_mezzo;
							$query6 = sprintf(" INSERT INTO magazzino (anno, datareg, tut, articolo, qtacarico, valoreacq ) values ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\")", $_annonuovo, $_data, $_tut, $dati4['articolo'], $dati5['qtafinale'], $dati5['valorefin']);
							//echo $query6;
							if (mysql_query($query6, $conn) != 1)
							{
								echo mysql_error();
								echo "Per sicurezza blocco la funzione\n";
								exit;
							}
						}//chiusuradomanda magazzino
					}
				}
			}
			// fine parte lavorativa ora inizia quella visiva
			echo "Eseguito. <br><br>";

			echo "Se Non appaiono messaggi d'errore tutto il travaso &egrave; stato eseguito con successo";
		}// fine protezione documenti
	}


	
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>