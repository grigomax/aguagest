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
require $_percorso . "librerie/motore_doc_pdo.php";
require $_percorso . "librerie/motore_anagrafiche.php";


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['vendite'] > "1")
{

    echo "<table width=\"80%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">";
    echo "<tr>";
    echo "<td align=\"center\" valign=\"top\" colspan=\"2\">";
    echo "<span class=\"intestazione\"><b>Risultato modifica documento</b><br></span><br>";
    echo "</td></tr>";


    // prendo le variabili

    $_anno = $_POST['anno'];
    $_ndoc = $_POST['ndoc'];
    $_newcli = $_POST['newcli'];
    $_tdoc = $_GET['tdoc'];
    
    $_archivio = archivio_tdoc($_tdoc);
    
    // Stringa contenente la query di ricerca..
    // aggiorno l'intestazione nelle testa calce
    $query = sprintf("update $_archivio[testacalce] set utente=\"%s\" where anno=\"%s\" and ndoc=\"%s\"", $_newcli, $_anno, $_ndoc);

    $result = $conn->exec($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
        $_errori['errori'] = "NO";
    }

    // aggiorno il corpo documento
    $query = sprintf("update $_archivio[dettaglio] set utente=\"%s\" where anno=\"%s\" and ndoc=\"%s\"", $_newcli, $_anno, $_ndoc);

    $result = $conn->exec($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
        $_errori['errori'] = "NO";
    }

    if ($_tdoc == "ddt")
    {
        // aggiorno l'il magazzino 
        $query = sprintf("update magazzino set utente=\"%s\" where anno=\"%s\" and ndoc=\"%s\"", $_newcli, $_anno, $_ndoc);

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
    }



    // se tutto � andato a buon fine 

    echo "<tr><td align center><b> Documento aggiornato perfettamente </b><br>
	per procedere alla stampa ritornare all'indice e selezionare ristampa d.d.t.<br>
	Ricordandosi di aggiornare la pagina con ALT+F5</td></tr>\n";
    echo "<tr><td align=center><a href=\"../stampa_doc.php?tdoc=$_tdoc&anno=$_anno&ndoc=$_ndoc&prezzi=si&dataora=si\" target=\"_blank\">Clikka qui per andare direttamente</a></td></tr>";


    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>