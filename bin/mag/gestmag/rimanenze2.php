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


if ($_SESSION['user']['magazzino'] > "1")
{

    require "../../librerie/stampe.inc.php";


//Cambio le variabili e le faccio vedere
    $_anno = $_POST['anno'];
    $_tipo = $_POST['tipo'];
    $_stampa = $_POST['stampa'];
    $_annoi = date('Y');
//recuperiamo la data.. e la giriamo

    $_data = cambio_data("us", $_POST['data']);

#echo "data di riferimento $_POST[data]";
// verifico l'anno passato per vedere cosa che archivio prendere

    $query = "SELECT anno FROM magazzino WHERE tut = 'giain' ORDER BY anno LIMIT 1";

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 1= $query - $_errore[2]";
        $_errori['files'] = "rimanenze2.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    foreach ($result AS $datianno)
        ;

    if ($_anno < $datianno['anno'])
    {
        $_magazzino = "magastorico";
    }
    else
    {
        $_magazzino = "magazzino";
    }


// per calcolare le rimanenze magazzino creo una tabella temporanea dove mettere i dati prima dell'impaginazione
// in modo da avere anche le righe qiuste.

    $query = " CREATE TEMPORARY TABLE IF NOT EXISTS `rimanenze` (
  			`articolo` varchar(15) default '',
  			`descrizione` varchar(100) default '',
			`tipart` varchar(100) default '',
  			`quantita` float(10,2) default '0.00',
  			`totriga` float(10,2) default '0.00'
  			)";

    $result = $conn->exec($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 2= $query - $_errore[2]";
        $_errori['files'] = "rimanenze2.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    
    $query = "TRUNCATE TABLE `rimanenze`";

    $result = $conn->exec($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 2bis= $query - $_errore[2]";
        $_errori['files'] = "inventario_merc.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    
    //carico su un array le categorie mercerologiche..
    
    if($_tipo == "catmer")
    {
        $desc_array = tabella_catmer("Array", $_codice, $_parametri);
    }
    else
    {
        $desc_array = tabella_tipart("Array", $_codice, $_parametri);
    }
    
    
// se la query �andata a buon fine proseguiamo.
//Ora procedo a prendermi dal magazzino gia tutti i conti per poterli inserire nel database provvisorio

    if ($_data != "--")
    {
        $query = sprintf("SELECT %s.articolo, datareg, catmer, tipart, ((SUM(qtacarico) - SUM(qtascarico))) AS qtafinale, ((SUM(valoreacq) /   SUM(qtacarico)) * (SUM(qtacarico) - SUM(qtascarico))) AS valore, %s.anno FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE %s.anno=\"%s\" AND %s.datareg <= '$_data' GROUP BY %s.articolo", $_magazzino, $_magazzino, $_magazzino, $_magazzino, $_magazzino, $_anno, $_magazzino, $_magazzino);
    }
    else
    {
        $query = sprintf("SELECT %s.articolo, datareg, catmer, tipart, ((SUM(qtacarico) - SUM(qtascarico))) AS qtafinale, ((SUM(valoreacq) /   SUM(qtacarico)) * (SUM(qtacarico) - SUM(qtascarico))) AS valore, %s.anno FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE %s.anno=\"%s\" GROUP BY %s.articolo", $_magazzino, $_magazzino, $_magazzino, $_magazzino, $_magazzino, $_anno, $_magazzino);
    }


// ora procediamo ad inserire il tutto nel database provvisorio
// Esegue la query...
    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 3= $query - $_errore[2]";
        $_errori['files'] = "rimanenze2.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    foreach ($result AS $datia)
    {//5
        // iserisco i dati nel database provvisorio
        $query = sprintf("INSERT INTO rimanenze ( articolo, descrizione, quantita, totriga ) values ( \"%s\", \"%s\", \"%s\", \"%s\" )", $datia['articolo'], $desc_array[$datia[$_tipo]], $datia['qtafinale'], $datia['valore']);

        //echo $queryt;
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query 4= $query - $_errore[2]";
            $_errori['files'] = "rimanenze2.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        // se la query �andata a buon fine proseguiamo
    }// fine calcolo muovimento
// echo $_anno;
#include "../../../setting/$_tipo.php";
//
//
//
//------------------------------------------------------------------------------
//Fatto questo iniziamo a stampare..
// a questo punto iniziamo la pagina della stampa..


            base_html_stampa("chiudi", $_parametri);


//selezioniamo il file di lingua

    if ($_lingua == "EN")
    {
        $LINGUA = "doc_inglese.php";
    }
    elseif ($_lingua == "ES")
    {
        $LINGUA = "doc_spagnolo.php";
    }
    else
    {
        $LINGUA = "doc_italiano.php";
    }

    $_documento = "rimanenze";
    $_tdoc = "rimanenze";

    $datidoc = layout_doc("singola", $_tdoc, $conn);

    $query = "SELECT descrizione, SUM(quantita) AS quantita, SUM(totriga) AS totriga FROM rimanenze GROUP BY descrizione ";

//Inventario magazzino
//effettuo la prima selezione in ordine di codice e di gruppo merceologico
// Esegue la query...
    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 5= $query - $_errore[2]";
        $_errori['files'] = "rimanenze2.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    // questa selezione mi permette di avere il numero di pagine ed il numero di
    //righe in anticipo
    //cerco il numero di righe
    $righe = $result->rowCount();
    //echo $righe;
    //inserisco il numero di righe per pagina
    @$_pagine = $righe / $datidoc['ST_RPP'];
    //arrotondo per eccesso
    $pagina = ceil($_pagine);

// ciclo di for per estrarmi le pagine
    #parte nuova..

            base_html_stampa("chiudi", $_parametri);




    echo "<BODY LANG=\"it-IT\" DIR=\"LTR\">";
    echo "<center>\n";




    for ($_pg = 1; $_pg <= $pagina; $_pg++)
    {

        //settiamo la pagina documento
        //creiamo una tabella grande quanto la pagina dove dentro mettiamo tutto
        #echo "<table border=\"0\" align=\"center\" CELLPADDING=\"0\" CELLSPACING=\"0\" width=\"700\" height=\"950\" style=\"page-break-inside: avoid;\">\n";
        # echo "<table border=\"0\" align=\"center\" CELLPADDING=\"0\" CELLSPACING=\"0\">\n";
        # echo "<tr><td align=\"center\" valign=\"top\" width=\"95%\" height=\"100%\">\n";
        echo "<center>\n";
        //funzione del logo..
        #intestazione_doc($datidoc, $LINGUA, $_percorso);
        //creiamo la testata
        testata_doc($datidoc, $dati, $dati2, $_datait, $_pg, $pagina, $_pagamento, $LINGUA, $_percorso);

        echo "<br>\n";

        //creiamo il corpo del documento
        $corpo_doc = (corpo_doc($datidoc, $result, $LINGUA, $corpo_doc, $_percorso));

        //CREIAMO LA CALCE DEL DOCUMENTO
        calce_doc($datidoc, $pagina, $_pg, $corpo_doc, $_iva, $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento, $_percorso);

        //chiudiamo la pagina del documento.
        # echo "</td></tr></table>\n";
        #  echo "</CENTER>\n";
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>