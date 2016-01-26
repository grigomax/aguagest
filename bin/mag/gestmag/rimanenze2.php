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

if ($_SESSION['user']['magazzino'] > "1")
{

        //includo file per generazione pdf
    define('FPDF_FONTPATH', '../../tools/fpdf/font/');
    require('../../tools/fpdf/fpdf.php');

    require "../../librerie/stampe.inc.php";
    require "../../librerie/motore_anagrafiche.php";
    require $_percorso . "librerie/stampe_doc_pdf.inc.php";
    require $_percorso . "librerie/invia_posta_allegato.php";


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

    $result = domanda_db("query", $query, $_cosa, $_ritorno, $_parametri);

    $datianno = $result->fetch(PDO::FETCH_ASSOC);

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

    domanda_db("exec", $query, $_cosa, $_ritorno, $_parametri);
    
    $query = "TRUNCATE TABLE `rimanenze`";

    domanda_db("exec", $query, $_cosa, $_ritorno, $_parametri);
    
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
    $result = domanda_db("query", $query, $_cosa, $_ritorno, $_parametri);

    foreach ($result AS $datia)
    {//5
        // iserisco i dati nel database provvisorio
        $query = sprintf("INSERT INTO rimanenze ( articolo, descrizione, quantita, totriga ) values ( \"%s\", \"%s\", \"%s\", \"%s\" )", $datia['articolo'], $desc_array[$datia[$_tipo]], $datia['qtafinale'], $datia['valore']);

        //echo $queryt;
        $result = domanda_db("exec", $query, $_cosa, $_ritorno, $_parametri);
        
        $result = $conn->exec($query);

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
    
    $result = domanda_db("query", $query, $_cosa, $_ritorno, $_parametri);
    
    // questa selezione mi permette di avere il numero di pagine ed il numero di
    //righe in anticipo
    //cerco il numero di righe
    $righe = $result->rowCount();
    //echo $righe;
    //inserisco il numero di righe per pagina
    @$_pagine = $righe / $datidoc['ST_RPP'];
    //arrotondo per eccesso
    $pagina = ceil($_pagine);


    //qui creiamo già la base della pagina globale..
    $_title = "Inventario Magazzino";
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetAutoPageBreak('off', 5);
    $pdf->SetTitle($_title);
    $pdf->SetCreator('Gestionale AGUA GEST - aguagest.sourceforge.net');
    $pdf->SetAuthor($azienda);
    $corpo_doc = "";



    for ($_pg = 1; $_pg <= $pagina; $_pg++)
    {

        // utility per inserire la pagina o creare la pagina.
        $pdf->AddPage();

        //funzione del logo..
        intestazione_doc_pdf($datidoc, $LINGUA);
        //testata_doc($datidoc, $dati, $dati2, $_datait, $_pg, $pagina, $_pagamento, $LINGUA, $_percorso);
        testata_doc_pdf($datidoc, $dati, $dati2, $_datait, $_pg, $pagina, $_pagamento, $LINGUA, $_parametri);
        
        //creiamo il corpo del documento
        //$corpo_doc = (corpo_doc($datidoc, $result, $LINGUA, $corpo_doc, $_percorso));
        $corpo_doc = (corpo_doc_pdf($datidoc, $result, $LINGUA, $corpo_doc));

        //CREIAMO LA CALCE DEL DOCUMENTO
        //calce_doc($datidoc, $pagina, $_pg, $corpo_doc, $_iva, $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento, $_percorso);
        calce_doc_pdf($datidoc, $pagina, $_pg, $corpo_doc, $_iva, $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento);

        //chiudiamo la pagina del documento.
        # echo "</td></tr></table>\n";
        #  echo "</CENTER>\n";
    }
    
    //finito il ciclo inviamo il file..
    $_pdf = "inventario.pdf";
    $pdf->Output("../../../spool/$_pdf", "I");
    
    
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>