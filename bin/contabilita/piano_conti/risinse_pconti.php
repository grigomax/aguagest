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



if ($_SESSION['user']['contabilita'] > "2")
{
// file unico di inserimento e aggiornamento clienti
// Inizio tabella pagina principale ----------------------------------------------------------

    echo "<table with=150><tr><td>";

// **********************************************************************
    echo "<span class=\"testo_blu\"><font size=\"3\"><center>Verifica Piano dei conti</span></font>";

    echo "<table border=\"1\">";

// inserisci
    $_azione = $_POST['azione'];

//controllo campi.
    if (($_POST['mastro'] == "") OR ($_POST['descrizione'] == "") OR ($_POST['natcon'] == "") OR ($_POST[tipo_cf] == ""))
    {
	echo "<h3> Attenzione alcuni campi obbligatori sono mancanti</h3>\n";
	exit;
    }


//componiamo se mastro o sotto conto
    $_mastro = $_POST['mastro'];
    $_conto = $_POST['conto'];

    //controlliamo se esite un sottoconto..

    $_sottoconto = substr($_POST['conto'], '2', '4');

    if ($_conto == "")
    {
	//vuol dire che è un mastro
	$_codconto = $_mastro;
	$_livello = "1";
    }
    elseif ($_sottoconto == "")
    {
	$_codconto = "$_mastro$_conto";

	$_livello = "2";
    }
    else
    {
	$_codconto = "$_mastro$_conto";

	$_livello = "3";
    }


    if ($_azione == "Inserisci")
    {

//verifica codice esistente

	$query = sprintf("select codconto from piano_conti where codconto=\"%s\"", $_codconto);
	//esegue la query
	$res = mysql_query($query, $conn);
//	echo $query;
	if (mysql_num_rows($res) > 0)
	{
	    echo "<tr><td><b>Il Mastro od il conto scelto &egrave; gi&agrave; esistente nell'archivio.</td></tr>\n";
	    echo "<tr><td>Fai indietro con il browser per non perdere i dati inseriti.<br> Poi cambia codice</td></tr>\n";
	}
	else
	{
	    // inserimento pagamento

	    $query = sprintf("INSERT INTO piano_conti ( codconto, descrizione, natcon, livello, tipo_cf, cod_cee )
            VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_codconto, $_POST['descrizione'], $_POST['natcon'], $_livello, $_POST['tipo_cf'], $_POST['cod_cee']);

	    // Esegue la query...
	    if (mysql_query($query, $conn) != 1)
	    {
		echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
		return -1;
	    }

	    //mysql_query( $query, $conn );
	    //}

	    echo "<tr><td>Codice Piano dei conti inserito correttamente</td></tr>\n";
	}// fine graffa else
    }// fine graffa funzione


    if ($_azione == "Aggiorna")
    {

//	Query modifica includo files variabili
	// Stringa contenente aggiornamento
	// eccola

	$query = sprintf("UPDATE piano_conti SET descrizione=\"%s\", natcon=\"%s\", livello=\"%s\", tipo_cf=\"%s\", cod_cee=\"%s\" WHERE codconto = \"%s\"", $_POST['descrizione'], $_POST['natcon'], $_livello, $_POST['tipo_cf'], $_POST['cod_cee'], $_codconto);

//	echo $query;
	// Esegue la query...
	if (mysql_query($query, $conn) != 1)
	{
	    echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
	    return -1;
	}
//	return 0

	echo "<tr><td>Voce Piano dei conti modificata con successo</td></tr></table>\n";

// graffa di fine funzione aggiornamento
    }

    if ($_azione == "Elimina")
    {

	$query = sprintf("DELETE FROM piano_conti WHERE codconto=\"%s\" limit 1", $_codconto);

	if (mysql_query($query, $conn) != 0)
	{
	    echo "Eliminazione codice piano dei conti riuscita";
	}
	else
	{
	    echo "Eliminazione codice piano conti Non riuscita";
	}
    }

    echo "</td></tr></table>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>