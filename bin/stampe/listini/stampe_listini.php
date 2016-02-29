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
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

// Programma per la stampa e la preparazione del decumento alla stampa
if ($_SESSION['user']['vendite'] > "1")
{


//Cambio le variabili e le faccio vedere
    $_listino = $_POST['listino'];
    $_tipo = $_POST['tipo'];
    $LINGUA = "doc_italiano.php";

    if ($_POST['tdoc'] == "")
    {
        echo "<h3>Impossibile proseguire nessun listino</h3>\n";
        echo "<h3>presente nel database layout</h3>\n";
        exit;
    }


//come prima cosa prendiamoci il tipo di listino dal database..
//includo file per generazione pdf
    define('FPDF_FONTPATH', $_percorso . 'tools/fpdf/font/');
    require($_percorso . 'tools/fpdf/fpdf.php');

    require $_percorso . "librerie/stampe_doc_pdf.inc.php";


    $datidoc = tabella_stampe_layout("singola", $_percorso, $_POST['tdoc']);

    //$dati = testata del documento

    if ($_tipo == "catmer")
    {
        $_catmer = $_POST['catmer'];

        //selezioniamo il nome dall'anagrafe..

        $_nomelist = tabella_catmer("singola_codice", $_catmer, $_parametri);

        $_parametri['tabella'] = $_nomelist['catmer'];

        if($_POST['pubblica'] == "SI")
        {
            $query = "SELECT articolo, descrizione, catmer, tipart, unita, listino from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where rigo='$_listino' AND catmer='$_catmer' and pubblica='SI' ORDER BY articolo";
        }
        else
        {
            $query = "SELECT articolo, descrizione, catmer, tipart, unita, listino from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where rigo='$_listino' AND catmer='$_catmer' ORDER BY articolo";
        }
        
    }


    if ($_tipo == "per_codice")
    {
        if ($_POST['ccodini'] != 0)
        {
            $_codini = $_POST['ccodini'];
            $_codfin = $_POST['ccodfin'];
        }
        else
        {
            $_codini = $_POST['codini'];
            $_codfin = $_POST['codfin'];
        }

        $_listino = $_POST['listino'];
        $_sconto = $_POST['sconto'];


        $_parametri['tabella'] = "Listino Prezzi N. $_listino";


        if($_POST['pubblica'] == "SI")
        {
            $query = sprintf("select articolo, descrizione, unita, listino from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where rigo=\"%s\" and articolo >= \"%s\" and articolo <= \"%s\" AND pubblica = 'SI' order by articolo", $_listino, $_codini, $_codfin);
        }
        else
        {
            $query = sprintf("select articolo, descrizione, unita, listino from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where rigo=\"%s\" and articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_listino, $_codini, $_codfin);
        }
        
    }

    $result = domanda_db("query", $query, $_cosa, $_ritorno, "block");

//Listini articolo..
// questa selezione mi permette di avere il numero di pagine ed il numero di
//righe in anticipo
//cerco il numero di righe
    $righe = $result->rowCount();
//echo $righe;
//inserisco il numero di righe per pagina
    $rpp = $datidoc['ST_RPP'];

    $_pagine = $righe / $rpp;
//arrotondo per eccesso
    $pagina = ceil($_pagine);

    //creiamo il pdf..
    crea_doc_file_pdf($_cosa, $_orientamento, "listino prezzi $_listino", $_nomelist);

    $_title = "LISTINO PREZZI";

    for ($_pg = 1; $_pg <= $pagina; $_pg++)
    {

        crea_doc_pagina_pdf();

        //funzione del logo..
        intestazione_doc_pdf($_cosa, $datidoc, $LINGUA, date('Y'), "listino prezzi $_listino", $_pg, $pagina, $_parametri);

        //creiamo la testata
        testata_doc_pdf($datidoc, $dati, $dati2, $_datait, $_pg, $pagina, $_pagamento, $LINGUA, $_parametri);

        //creiamo il corpo del documento
        $corpo_doc = (corpo_doc_pdf($datidoc, $result, $LINGUA, $corpo_doc));

        if ($corpo_doc['pagina'] == "altra")
        {
            $_pg--;
        }

        if ($corpo_doc['pagina'] == "chiudi")
        {
            $_pg = $pagina;
        }
        //$_pg = 2;
        //CREIAMO LA CALCE DEL DOCUMENTO
        calce_doc_pdf($datidoc, $pagina, $_pg, $corpo_doc['netto'], $corpo_doc['iva'], $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento);
    }

    //salviamo il discorso..

    chiudi_doc_files("listino", "I");

    //azzeriamo eventuali residui..

    $pdf->null;
    $pdf = null;


    //fine funzione di generazione documento in pdf-----------------------------------------------------------------------------------------------------------------------------------------------------
}
else
{
    echo "<table><tr>\n";
    echo "<td width=\"85%\" align=\"center\" valign=\"top\">";
    echo "<span class=\"intestazione\"><br><b>Gestione Vendite</b></span><br>";
    echo "<span class=\"intestazione\"><br><b>Non hai i permessi per entrare</b></span><br>";

    echo "</td>
		</tr>
		</table>
		</body>\n";
}
?>