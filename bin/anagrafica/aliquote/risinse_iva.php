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
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
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


if ($_SESSION['user']['anagrafiche'] > "1")
{

// Inizio tabella pagina principale ----------------------------------------------------------

    echo "<table with=150><tr><td>";



// **********************************************************************
    echo "<span class=\"testo_blu\"><font size=\"3\">Verifica Aliquota Iva</span></font>";

    echo "<table border=\"1\">";

// inserisci
    $_azione = $_POST['azione'];

    if ($_azione == "inserisci")
    {
// verifica inserimento cliente

	$query = sprintf("select codice from aliquota where codice=\"%s\"", $_POST['codice']);
	//esegue la query
	$res = mysql_query($query, $conn);
//	echo $query;
	if (mysql_num_rows($res) > 0)
	{
	    echo "<tr><td><b>L'aliquota iva inserita &egrave; gi&agrave; esistente nell'archivio.</td></tr>\n";
	    echo "<tr><td>Fai indietro con il browser per non perdere i dati inseriti.<br> Poi cambia codice</td></tr>\n";
	}
	else
	{
	    // inserimento pagamento

	    $query = sprintf("INSERT INTO aliquota ( codice, descrizione, ivacee, eseniva, aliquota, ventilazione, colonnacli, colonnafor, plafond, modello1012 ) VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_POST['codice'], $_POST['descrizione'], $_POST['ivacee'], $_POST['eseniva'], $_POST['aliquota'], $_POST['ventilazione'], $_POST['colonnacli'], $_POST['colonnafor'], $_POST['plafond'], $_POST['modello1012']);

	    // Esegue la query...
	    if (mysql_query($query, $conn) != 1)
	    {
		echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
		return -1;
	    }

	    //mysql_query( $query, $conn );
	    //}

	    echo "<tr><td>Aliquota inserita correttamente</td></tr>\n";
	}// fine graffa else
    }// fine graffa funzione


    if ($_azione == "Aggiorna")
    {

//	Query modifica includo files variabili
	// Stringa contenente aggiornamento
	// eccola

	$query = sprintf("UPDATE aliquota SET descrizione=\"%s\", ivacee=\"%s\", eseniva=\"%s\", aliquota=\"%s\", ventilazione=\"%s\", colonnacli=\"%s\", colonnafor=\"%s\", plafond=\"%s\", modello1012=\"%s\" WHERE codice = \"%s\"", $_POST['descrizione'], $_POST['ivacee'], $_POST['eseniva'], $_POST['aliquota'], $_POST['ventilazione'], $_POST['colonnacli'], $_POST['colonnafor'], $_POST['plafond'], $_POST['modello1012'], $_POST['codice']);

//	echo $query;
	// Esegue la query...
	if (mysql_query($query, $conn) != 1)
	{
	    echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
	    return -1;
	}
//	return 0

	echo "<tr><td>Aliquota modificata con successo</td></tr></table>\n";

// graffa di fine funzione aggiornamento
    }

    if ($_azione == "Elimina")
    {



	$query = sprintf("DELETE FROM aliquota WHERE codice=\"%s\" limit 1", $_POST['codice']);

	if (mysql_query($query, $conn) != 0)
	{
	    echo "Eliminazione aliquota riuscita";
	}
	else
	{
	    echo "Eliminazione aliquota Non riuscita";
	}
    }

    echo "</td></tr></table>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>