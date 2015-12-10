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

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{
//prendiamoci i post..

    $_vecchio = $_POST['vecchio'];
    $_nuovo = $_POST['nuovo'];

    if (($_vecchio == "") OR ($_nuovo == ""))
    {
	echo "<h2>Attenzione non &egrave; stato selezionato uno dei due fornitori</h2>\n";
	exit;
    }
    else
    {
	//procediamo alla sostituzione del fornitore in anagrafica articoli sia in fornitore 1 che in due..
	//una query per il fornitore 1
	$query = ("UPDATE articoli SET fornitore='$_nuovo' where fornitore = '$_vecchio'");

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
	{
	    $righe = $result->rowCount();
	    echo "<center><h3>Sostituzione avvenuta a $righe articoli </h3>\n";
	}

	//una query per il forntitore 2
	$query = ("UPDATE articoli SET fornitore2='$_nuovo' where fornitore2 = '$_vecchio'");

	$result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
	{
	    $righe = $result->rowCount();
	    echo "<center><h3>Sostituzione avvenuta a $righe articoli </h3>\n";
	}
        
        //una query per il forntitore 3
	$query = ("UPDATE articoli SET fornitore_3='$_nuovo' where fornitore_3 = '$_vecchio'");

	$result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
	{
	    $righe = $result->rowCount();
	    echo "<center><h3>Sostituzione avvenuta a $righe articoli </h3>\n";
	}
        
    }


    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>