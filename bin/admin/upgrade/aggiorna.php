<?php
session_start();
$_SESSION['keepalive'] ++;
//carichiamo la base del programma includendo i file minimi
//prima di tutto bisogna verificare l'installazione..
$_percorso = "../../";
//siccome il programma è di aggiornamento non sappiamo se il file di configurazione sia in directory
//report oppure setting.. quindi vediamo se c'è lo in cludiamo altrimenti includiamo un'altro..

require "../../../setting/vars.php";
require "../../librerie/lib_html.php";
include "../../include/version.inc.php";

$conn = connessione_mysql("PDO");


base_html("chiudi", $_percorso);


// questo programma aggiorna agua all'ultima versione..

echo "Aggiornamento in corso...<br> \n";

echo "Lettura ultima versione<br> \n";



echo "Versione nuova programma $AGUAGEST <br>";
echo "Versione nuova Archivi $AGUABASE <br>";
//leggiamo dal database le versioni
//Verifico Gli aggiornamenti se sono stati effettuati altrimenti a cascata li eseguo tutti
// prendo la versione vecchia e la nuova e per differenza li carico tutti!
// 	Ora mi connetto al database per prelevare le versioni del programma..
//proviamo la connessione ad oggetti;

foreach ($conn->query("SELECT * FROM version WHERE id='1'") as $dati)
	;


//verifico aggiornamento programma
$_basevecchia = $dati['aguabase'];
echo "Versione Database Esistente = $_basevecchia <br>";
$_aguavecchio = $dati['aguagest'];
echo "Versione Aguagest Esistente = $_aguavecchio <br>";

$fine = "1";

if ($_aguavecchio != $AGUAGEST)
{
	$_aguavecchio = $_aguavecchio + 1;

	for ($_a = $_aguavecchio; $_a <= $AGUAGEST; $_a++)
	{
		if ($fine != "1")
		{
			#blocco il programma ancor prima di iniziare..
			echo "Impossibile continuare con il programma causa errori <br>";
			echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
			exit;
		}

		if (file_exists("$_a.update.inc"))
		{
			echo "lettura file esterno <br>\n";
			include("$_a.update.inc");

			if ($fine != "1")
			{
				echo "Impossibile continuare con il programma causa errori <br>";
				exit;
			}
		}
//	else
//	{
//	    echo "Impossibile Trovare il file = $_a.update.inc<br>\n";
//	    echo "Verificare errore e richiedere assistenza<br>\n";
//	    echo "Impossibile continuare con il programma causa errori <br>";
//	    echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
//	    $fine = 0;
//	    exit;
//	}
	}

	//    Verifico che non ci siano stati errori
	if ($fine != "1")
	{
		#blocco il programma ancor prima di iniziare..
		echo "Impossibile continuare con il programma causa errori <br>";
		echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
		exit;
	}
	else
	{
		echo "Aggiornamento..... <br>";

		//proviamo l'aggiornamento ad oggetti'

		$_res = $conn->query("UPDATE version SET aguagest='$AGUAGEST', notegest='$_PROGRAM_VERSION'");

		#echo 'Count rows selected: ' . $_res->rowCount();
		if ($_res->rowCount() >= 0)
		{
			echo "Primo aggiornamento eseguito archivi <br>... \n";
			$fine = 1;
		}
		else
		{
			echo "Primo aggiornamento eseguito archivi Non eseguito<br>... \n";
			//scrivo gli errori
			$errorinfo = $conn->errorInfo();
			$_errori['descrizione'] = $errorinfo[2];
			$_errori['files'] = "aggiorna.php";
			scrittura_errori($_cosa, $_percorso, $_errori);

			$fine = 0;
		}
	}
}
else
{
	echo "Aggiornamento..... <br>";

	//proviamo l'aggiornamento ad oggetti'

	$_res = $conn->query("UPDATE version SET aguagest='$AGUAGEST', notegest='$_PROGRAM_VERSION'");

	//echo 'Count rows selected: ' . $_res->rowCount();
	if ($_res->rowCount() >= 0)
	{
		echo "Primo aggiornamento eseguito archivi <br>... \n";
		$fine = 1;
	}
	else
	{
		echo "Primo aggiornamento archivi Non eseguito<br>... \n";
		//scrivo gli errori
		$errorinfo = $conn->errorInfo();
		$_errori['descrizione'] = $errorinfo[2];
		$_errori['files'] = "aggiorna.php";
		scrittura_errori($_cosa, $_percorso, $_errori);

		$fine = 0;
	}
}




#Riverifico errori
//    Verifico che non ci siano stati errori
if ($fine != "1")
{
#blocco il programma ancor prima di iniziare..
	echo "Impossibile continuare con il programma causa errori <br>";
	echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
	exit;
}
else
{
	echo "Primo aggiornamento eseguito <br>... \n";
}

#Inizio aggiornamento database..

echo "Inizio con il secondo.. <br>\n";

if ($_basevecchia != $AGUABASE)
{
	//    Verifico che non ci siano stati errori
	if ($fine != "1")
	{
		#blocco il programma ancor prima di iniziare..
		echo "Impossibile continuare con il programma causa errori <br>";
		echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
		exit;
	}
	$_basevecchia = $_basevecchia + 1;

	for ($_a = $_basevecchia; $_a <= $AGUABASE; $_a++)
	{
		if (file_exists("$_a.sql_db.php"))
		{
			echo "lettura file esterno <br>\n";
			include("$_a.sql_db.php");
			if ($fine != "1")
			{
				echo "Impossibile continuare con il programma causa errori <br>";
				exit;
			}
		}
		else
		{
			echo "Impossibile Trovare il file = $_a.sql_db.php<br>\n";
			echo "Verificare errore e richiedere assistenza<br>\n";
			echo "Impossibile continuare con il programma causa errori <br>";
			echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
			$fine = 0;
			exit;
		}
	}

	//    Verifico che non ci siano stati errori
	if ($fine != "1")
	{
		#blocco il programma ancor prima di iniziare..
		echo "Impossibile continuare con il programma causa errori <br>";
		echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
		exit;
	}
	else
	{
		echo "secondo aggiornamento eseguito archivi <br>... \n";
	}
}
else
{
	echo "Archivi gi&agrave; alla versione corrente<br>... \n";
}

//chiudiamo la connessione

$conn->null;

echo "<h1>Gestione aggiornamenti finita..</h1><br>\n";
echo "<h2><a href=\"../../../index.php\">Fate clik qui per tornare alla pagina principale</a></h2>";
?>