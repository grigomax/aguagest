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
$conn = permessi_sessione("verifica_PDO", $_percorso);


if ($_SESSION['user']['magazzino'] > "1")
{

    require "../../librerie/stampe.inc.php";
    require "../../librerie/motore_anagrafiche.php";


//Cambio le variabili e le faccio vedere
    $_anno = $_POST['anno'];
    $_catmer = $_POST['catmer'];
    $_tipo = $_POST['stampa'];
    $_cvalore = $_POST['cvalore'];
    $_cgiac = $_POST['cgiac'];
    $_datareg = cambio_data("us", $_POST['datareg']);

    if ($_GET['tipo'] == "merce")
    {
        $_tipologia = "Inventario per gruppo Merceologico";
        $_database = "catmer";
        #$query = "select * from catmer order by catmer";
        $_colonna = "catmer";
        
        $categoria = tabella_catmer("Array", $_POST['catmer'], $_parametri);
    }
    else
    {
        $_tipologia = "Inventario per tipologia articolo";
        $_database = "tipart";
        #$query = "select * from tipart order by tipoart";
        $_colonna = "tipoart";
        
        $categoria = tabella_tipart("Array", $_POST['catmer'], $_parametri);
    }
    
    
    $query = "SELECT anno FROM magazzino WHERE tut = 'giain' ORDER BY anno LIMIT 1";

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 1= $query - $_errore[2]";
        $_errori['files'] = "inventario_merc.php";
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

    
// per calcolare le giacenze di magazzino creo una tabella temporanea dove mettere i dati prima dell'impaginazione
// in modo da avere anche le righe qiuste.

    $query = " CREATE TEMPORARY TABLE IF NOT EXISTS `inventario` (
  			`articolo` varchar(15) default '',
  			`descrizione` varchar(100) default '',
  			`forn` varchar(15) default '',
  			`quantita` float(10,2) default '0.00',
  			`qtaevasa` float(10,2) default '0.00',
  			`qtaestratta` float(10,2) default '0.00',
  			`qtasaldo` float(10,2) default '0.00',
  			`netto` float(10,2) default '0.00',
  			`totriga` float(10,2) default '0.00'
			)";

    $result = $conn->exec($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 2= $query - $_errore[2]";
        $_errori['files'] = "inventario_merc.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
// se la query �andata a buon fine proseguiamo.
// se piena la svuoto prima di proseguire

    $query = "TRUNCATE TABLE `inventario`";

    $result = $conn->exec($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 3= $query - $_errore[2]";
        $_errori['files'] = "inventario_merc.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
// se la query �andata a buon fine proseguiamo.
// proseguiamo per fare i conti
//Ora procedo a selezionare gli articoli che mi interessano dall'anagrafica.

    $query = sprintf("SELECT articolo, substring(descrizione,1,40), fornitore, preacqnetto, ultacq, $_database FROM articoli WHERE $_database=\"%s\" order by articolo", $_catmer);
    
    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 4= $query - $_errore[2]";
        $_errori['files'] = "inventario_merc.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    //divido il numero per 100..
    foreach ($result AS $datia)
    {//5
        // effettuata la prima cernita passo con la seconda
        // ora per ogni articolo cerco nel magazzino le giacenze iniziali
        $query = sprintf("SELECT qtacarico, valoreacq FROM %s WHERE articolo=\"%s\" and tut='giain' AND anno=\"%s\"", $_magazzino, $datia['articolo'], $_anno);

        $result2 = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query 5= $query - $_errore[2]";
            $_errori['files'] = "inventario_merc.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        foreach ($result2 AS $datimi)
            ;

        $_qtaini = $datimi['qtacarico'];
        $_valoreini = $datimi['valoreacq'];


        // presa la giacenza iniziale, prendiamo le somme del venduto e l'acquistato senza la giacenza iniziale

        $query = sprintf("SELECT SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq, SUM(qtascarico) AS qtascarico, SUM(valorevend) AS valorevend FROM %s WHERE articolo=\"%s\" and tut != 'giain' AND datareg <=\"%s\" order by articolo", $_magazzino, $datia['articolo'], $_datareg);
        $result3 = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query 6= $query - $_errore[2]";
            $_errori['files'] = "inventario_merc.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        foreach ($result3 AS $datim)
            ;

        if ($_cgiac == "MUOVI")
        {
            if (($datimi['qtacarico'] != 0.00) OR ( $datim['qtacarico'] != 0.00) OR ( $datim['qtascarico'] != 0.00))
            {
                $_giacfin = ($datimi['qtacarico'] + $datim['qtacarico']) - $datim['qtascarico'];

                if ($_cvalore == "S")
                {
                    $_prezzoacq = $datia['preacqnetto'];
                    $_valore = $_giacfin * $_prezzoacq;
                }
                elseif ($_cvalore == "U")
                {
                    $_prezzoacq = $datia['ultacq'];
                    $_valore = $_giacfin * $_prezzoacq;
                }
                else
                {
                    @$_prezzoacq = number_format((($datimi['valoreacq'] + $datim['valoreacq']) / ($datimi['qtacarico'] + $datim['qtacarico'])), $dec, '.', '');
                    $_valore = $_giacfin * $_prezzoacq;
                }
                // iserisco i dati nel database provvisorio
                $query = sprintf(" INSERT INTO inventario (articolo, descrizione, forn, quantita, qtaevasa, qtaestratta, qtasaldo, netto, totriga) values ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\")", $datia['articolo'], $datia['substring(descrizione,1,40)'], $datia['fornitore'], $datimi['qtacarico'], $datim['qtacarico'], $datim['qtascarico'], $_giacfin, $_prezzoacq, $_valore);

                //echo $queryt;
                $conn->exec($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query 6bis= $query - $_errore[2]";
                    $_errori['files'] = "inventario_merc.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }
                
                               
                // se la query �andata a buon fine proseguiamo
            }// fine calcolo muovimento
        }// fine if muovimento
        else
        {
            if ((($datimi['qtacarico'] + $datim['qtacarico']) - $datim['qtascarico']) != 0.00)
            {
                $_giacfin = ($datimi['qtacarico'] + $datim['qtacarico']) - $datim['qtascarico'];

                if ($_cvalore == "S")
                {
                    $_prezzoacq = $datia['preacqnetto'];
                    $_valore = $_giacfin * $_prezzoacq;
                }
                elseif ($_cvalore == "U")
                {
                    $_prezzoacq = $datia['ultacq'];
                    $_valore = $_giacfin * $_prezzoacq;
                }
                else
                {
                    @$_prezzoacq = number_format((($datimi['valoreacq'] + $datim['valoreacq']) / ($datimi['qtacarico'] + $datim['qtacarico'])), $dec, '.', '');
                    $_valore = $_giacfin * $_prezzoacq;
                }


                // iserisco i dati nel database provvisorio
                $query = sprintf(" INSERT INTO inventario (articolo, descrizione, forn, quantita, qtaevasa, qtaestratta, qtasaldo, netto, totriga) values ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\")", $datia['articolo'], $datia['substring(descrizione,1,40)'], $datia['fornitore'], $datimi['qtacarico'], $datim['qtacarico'], $datim['qtascarico'], $_giacfin, $_prezzoacq, $_valore);

                //echo $queryt;
                $conn->exec($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query 7= $query - $_errore[2]";
                    $_errori['files'] = "inventario_merc.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }

                // se la query �andata a buon fine proseguiamo
            }// fine calcolo muovimento
        }// fine else muovimento
        
        //azzero l'array..
                $datim="";
                $datimi="";
        
    }// fien ciclo di while
//
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

    $_documento = "inventario";
    $_tdoc = "inventario";

    $datidoc = layout_doc("singola", $_tdoc, $conn);

    $datidoc['tipo'] = $categoria;

    
    $query = "SELECT * FROM inventario ORDER BY articolo";

//Inventario magazzino
//effettuo la prima selezione in ordine di codice e di gruppo merceologico
// Esegue la query...

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query 8= $query - $_errore[2]";
        $_errori['files'] = "inventario_merc.php";
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
        testata_doc($datidoc, $dati, $dati2, $_POST['datareg'], $_pg, $pagina, $_pagamento, $LINGUA, $_percorso);

        echo "<br>\n";

        //creiamo il corpo del documento
        $corpo_doc = (corpo_doc($datidoc, $result, $LINGUA, $corpo_doc, $_percorso));

        //CREIAMO LA CALCE DEL DOCUMENTO
        calce_doc($datidoc, $pagina, $_pg, $corpo_doc, $_iva, $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento, $_percorso);

        //chiudiamo la pagina del documento.
        # echo "</td></tr></table>\n";
        #  echo "</CENTER>\n";
    } // chiusura connessione
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>