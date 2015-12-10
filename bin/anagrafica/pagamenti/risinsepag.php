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
$conn = permessi_sessione("verifica", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

// file unico di inserimento e aggiornamento clienti
// Inizio tabella pagina principale ----------------------------------------------------------

    echo "<table with=150><tr><td>";



// **********************************************************************
    echo "<span class=\"testo_blu\"><font size=\"3\">Verifica inserimento Pagamento</span></font>";

    echo "<table border=\"1\">";

// inserisci
    $_azione = $_POST['azione'];

    if ($_azione == "inserisci")
    {
// verifica inserimento cliente

	$query = sprintf("select codice from pagamenti where codice=\"%s\"", $_POST['codpag']);
	//esegue la query
	$res = mysql_query($query, $conn);
//	echo $query;
	if (mysql_num_rows($res) > 0)
	{
	    echo "<tr><td><b>Il pagamento inserito &egrave; gi&agrave; esistente nell'archivio.</td></tr>\n";
	    echo "<tr><td>Fai indietro con il browser per non perdere i dati inseriti.<br> Poi cambia codice cliente</td></tr>\n";
	}
	else
	{
	    // inserimento pagamento

	    $query = sprintf("INSERT INTO pagamenti ( codice, descrizione, sconto, rataiva, scadfissa, unomese, duemese, tipopag, nscad, ggprimascad, ggtrascad, dffm ) VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_POST['codpag'], $_POST['descrizione'], $_POST['sconto'], $_POST['rataiva'], $_POST['scadfissa'], $_POST['unomese'], $_POST['duemese'], $_POST['tipopag'], $_POST['nscad'], $_POST['ggprimascad'], $_POST['ggtrascad'], $_POST['dffm']);

	    // Esegue la query...
	    if (mysql_query($query, $conn) != 1)
	    {
		echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
		return -1;
	    }

	    //mysql_query( $query, $conn );
	    //}

	    echo "<tr><td> Pagamento inserito correttamente</td></tr>\n";
	}// fine graffa else
    }// fine graffa funzione


    if ($_azione == "Aggiorna")
    {

//	Query modifica includo files variabili
	// Stringa contenente aggiornamento
	// eccola

	$query = sprintf("UPDATE pagamenti SET descrizione=\"%s\", sconto=\"%s\", rataiva=\"%s\", scadfissa=\"%s\", unomese=\"%s\", duemese=\"%s\", tipopag=\"%s\", nscad=\"%s\", ggprimascad=\"%s\", ggtrascad=\"%s\", dffm=\"%s\" WHERE codice = \"%s\"", $_POST['descrizione'], $_POST['sconto'], $_POST['rataiva'], $_POST['scadfissa'], $_POST['unomese'], $_POST['duemese'], $_POST['tipopag'], $_POST['nscad'], $_POST['ggprimascad'], $_POST['ggtrascad'], $_POST['dffm'], $_POST['codpag']);

//	echo $query;
	// Esegue la query...
	if (mysql_query($query, $conn) != 1)
	{
	    echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
	    return -1;
	}
//	return 0

	echo "<tr><td>Pagamento modificato con successo</td></tr></table>\n";

// graffa di fine funzione aggiornamento
    }

    if ($_azione == "Elimina")
    {



	$query = sprintf("DELETE FROM pagamenti WHERE codice=\"%s\" limit 1", $_POST['codpag']);

	if (mysql_query($query, $conn) != 0)
	{
	    echo "Eliminazione pagamento riuscita";
	}
	else
	{
	    echo "Eliminazione pagamento Non riuscita";
	}
    }

    echo "</td></tr></table>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>