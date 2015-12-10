<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso . "../setting/vars.php";

session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//includo file per generazione pdf
define('FPDF_FONTPATH', '../tools/fpdf/font/');
require('../tools/fpdf/fpdf.php');

require $_percorso . "librerie/stampe.inc.php";
require $_percorso . "librerie/stampe_doc_pdf.inc.php";
require $_percorso . "librerie/invia_posta_allegato.php";
require $_percorso . "librerie/motore_anagrafiche.php";
require_once $_percorso . "librerie/motore_doc_pdo.php";

// Programma per la stampa e la preparazione del decumento alla stampa
if ($_SESSION['user']['vendite'] > "1")
{
    $_tdoc = $_GET['tdoc'];
    $_ndoc = $_GET['ndoc'];
    $_anno = $_GET['anno'];
    $_azione = $_GET['azione'];
    $_lingua = $_GET['lingua'];
    $_docfine = $_GET['docfine'];
    $_multi = $_GET['multi'];
    $_salva = $_GET['salva'];
    $_ricevuta = $_GET['ricevuta'];
    $_suffix = $_GET['suffix'];

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

//dividiamo i documenti
// funzione selezione
// seleziono il tipo di documento

    $_archivio = archivio_tdoc($_tdoc);
    $datidoc = layout_doc("singola", $_tdoc, $conn);

    $_file = $_archivio['file'];
    $_utente = $_archivio['utente'];
    $_documento = $_archivio['testacalce'];
    $_docdetta = $_archivio['dettaglio'];

//qui c'e la differenza tra la stampa singola o la multipla..
//se multi è selezionato carichiamo tutti i docuemnti e li stampiamo su un file pdf unico
//

    if ($_multi == "SI")
    {

        if (empty($_docfine))
        {
            echo "<h3>Impossibile proseguire in quanto il numero documento di inizio risulta vuoto</h3>\n";
            exit;
        }

        if ($_docfine > $_ndoc)
        {
            echo "<h3>Impossibile proseguire in quanto il numero documento di inizio risulta vuoto<br> superiore a quello finale</h3>\n";
            exit;
        }


// Stringa contenente la query di ricerca... solo dei fornitori
        //iniziamo creando il file pdf..
        if ($_azione != "Inoltra")
        {
            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->SetAutoPageBreak('off', 5);

            $pdf->SetCreator('Gestionale AGUA GEST - aguagest.sourceforge.net');
            $pdf->SetAuthor($azienda);
        }

        $query = sprintf("SELECT * FROM $_documento where ndoc >=\"%s\" and ndoc <= \"%s\" and anno=\"%s\" order by ndoc", $_docfine, $_ndoc, $_anno);

        //echo $query;
        // Esegue la query...
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "stampa_doc.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        //inizio generazione multipla------------------------------------------------------------------------------------------------------------
        //ora per ogni documento selezionato va inserito all'inerno della pagina..
        foreach ($result as $dati)
        {

            $_status = $dati['status'];
            $_codpagamenti = $dati['modpag'];
            $_dataus = $dati['datareg'];

            // qui metto due variabili uguali ma che servono allo scopo diverso..

            if ($_azione == "Inoltra")
            {
                $pdf = new FPDF('P', 'mm', 'A4');
                $pdf->SetAutoPageBreak('off', 5);

                $pdf->SetCreator('Gestionale AGUA GEST - aguagest.sourceforge.net');
                $pdf->SetAuthor($azienda);
            }


            //AZZERO LE VARIABILI..

            $corpo_doc = "";

            //echo $dati['ndoc'];
            //purtroppo l'archivio delle fatture contiene una molteplicità di documenti
// quindi devo selezionarli in base alla lettura dell'archivio..
            if ($dati['tdoc'] == $nomedoc)
            {
                $datidoc = layout_doc("singola", "immediata", $conn);
            }

            if (($dati['tdoc'] == "FATTURA") OR ( $dati['tdoc'] == "NOTA CREDITO") OR ( $dati['tdoc'] == "NOTA DEBITO" ))
            {
                $datidoc = layout_doc("singola", "fattura", $conn);
                $datidoc['ST_NDOC'] = $dati['tdoc'];
                #echo "ciao";
            }


            if ($_status == "in-uso")
            {
                echo "<p align=\"center\" class=\"testo_blu\">ATTENZIONE il documento &egrave; gi&agrave; in uso da un altro utente<br>
	Impossibile procedere con la stampa del documento";
                //echo "<br>ciao 3";
                exit;
            }

// Setto il status docuemnto ad stampato
            if (($_status != "evaso") AND ( $_status != "saldato"))
            {
                $query = "UPDATE $_documento SET status='stampato' where anno='$_anno' AND suffix='$_suffix' AND ndoc='$dati[ndoc]'";

                $result = $conn->query($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query 3 = $query - $_errore[2]";
                    $_errori['files'] = "stampa_doc.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }
            }



            if ($_utente == "fornitori")
            {
                $dati2 = tabella_fornitori("singola", $dati['utente'], $_parametri);
            }
            else
            {
                $dati2 = tabella_clienti("singola", $dati['utente'], $_parametri);
            }

// cerco il tipo di iva abbinato al cliente
            $_ivacliente = $dati2['iva'];

            if ($_ivacliente == "")
            {
                $_ivacliente = $ivasis;
            }

//verifico la e-mail
            if ($dati['tdoc'] == ("FATTURA" OR "NOTA CREDITO" OR "NOTA DEBITO" OR "$nomedoc"))
            {
                $_emaildestino = $dati2['email3'];
                $_emailmittente = $email3;
            }
            else
            {
                $_emaildestino = $dati2['email2'];
                $_emailmittente = $email2;
            }

            $dativa = tabella_aliquota("singola", $_ivacliente, $_percorso);
            $desciva = $dativa['descrizione'];
            $_ivadiversa = $dativa['eseniva'];


// cerco la modalita di pagamento e da codice passo ad estesa

            $dati10 = tabella_pagamenti("singola", $_codpagamenti, $_parametri);
            $_pagamento = $dati10['descrizione'];


// converto la data da americana ad italiana
// invio $_dataus ricevo $_datait
            $_datait = cambio_data("it", $_dataus);


            /*             * *
             * Programma base gestione documenti..
             * questo programma crea i documentii partendo da semplici variabili
             */

            if ($_tdoc == "fornitore")
            {
                // questa selezione mi permette di avere il numero di pagine ed il numero di righe in anticipo
                $query = sprintf("select *, substring(articolo,1,'$datidoc[ST_ARTICOLO_CT]') AS articolo, substring(artfor,1,'$datidoc[ST_ARTFOR_CT]') AS artfor, substring(descrizione,1,'$datidoc[ST_DESCRIZIONE_CT]') AS descrizione, scaa AS scva, scab AS scvb, scac AS scvc from $_docdetta where anno=\"%s\" and ndoc=\"%s\" order by rigo", $_anno, $dati['ndoc']);
            }
            else
            {
                $query = "select *, substring(articolo,1,'$datidoc[ST_ARTICOLO_CT]') AS articolo, substring(descrizione,1,'$datidoc[ST_DESCRIZIONE_CT]') AS descrizione from $_docdetta where anno='$_anno' AND suffix='$_suffix' AND and ndoc='$dati[ndoc]' order by rigo";
            }

            $result = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query 3 = $query - $_errore[2]";
                $_errori['files'] = "stampa_doc.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }
//cerco il numero di righe
            $righe = $result->rowCount();

            //verifichiamo se la scritta di fine documento è attivata, in tal caso dobbiamo togliere una riga al documento
            //verifichiamo st avviso
            if ($datidoc['ST_AVVISO'] == "SI")
            {
                $datidoc['ST_RPP'] = $datidoc['ST_RPP'] - 1;
            }

            //inserisco il numero di righe per pagina
            $_pagine = $righe / $datidoc['ST_RPP'];
            //arrotondo per eccesso
            $pagina = ceil($_pagine);


            //inizio generazione file in formato pdf..
            //richiamo le funzioni che sono all'intrerno del file stampe_doc_pdf.inc.php'
            // setto le variabili standard creazione pdf
            $_title = "$datidoc[ST_NDOC] n. $dati[ndoc]";
            $pdf->SetTitle($_title);



            for ($_pg = 1; $_pg <= $pagina; $_pg++)
            {
                // utility per inserire la pagina o creare la pagina.
                $pdf->AddPage();

                //funzione del logo..
                intestazione_doc_pdf($datidoc, $LINGUA);

                //creiamo la testata
                testata_doc_pdf($datidoc, $dati, $dati2, $_datait, $_pg, $pagina, $_pagamento, $LINGUA);

                //creiamo il corpo del documento
                $corpo_doc = (corpo_doc_pdf($datidoc, $result, $LINGUA, $corpo_doc));

                //CREIAMO LA CALCE DEL DOCUMENTO
                calce_doc_pdf($datidoc, $pagina, $_pg, $corpo_doc['netto'], $corpo_doc['iva'], $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento);
            }

            //echo "<br>ciao fine";

            if ($_azione == "Inoltra")
            {
                //azzeriamo l'oggetto..:
                $_oggetto = null;
                //generazione del files..
                $_pdf = "$_file" . "_" . "$dati[ndoc].pdf";
                $pdf->Output("../../spool/$_pdf", F);
                //oggeto
                $_oggetto = "Invio $_tdoc $dati[ndoc] $azienda";
                //qui richiamiamo la funzione del file invia posta allegato.
                $_invio = invio_posta($_cosa, $_percorso, $_pdf, $_emailmittente, $_emaildestino, $_emaildestinoCC, $_emaildestinoBCC, $_oggetto, $datidoc['BODY'], $_ricevuta, $_tdoc, $_anno, $dati['ndoc'], $_allegato, $_allegato2, $_parametri);
            }
        }

        //generazione del files..
        //$_pdf = "$_file" . "_" . "$_ndoc.pdf";
        if ($_salva == "SI")
        {
            $pdf->Output("../../spool/stampa_doc.pdf", 'D');
        }
        elseif ($_azione == "Inoltra")
        {

            base_html("chiudi", $_percorso);

            if ($_multi != "SI")
            {
                echo "<span class=\"testo_blu\"><center><br><b>Risulato Invio e-mail</b></center></span><br>";
                print "<H2>Invio della email effettuato.</H2>";
                echo " Email inviata correttamente all'indirizzo $_POST[AddAddress]";
                echo "<br> Questo non vuol dire che il destinatario ricever&agrave; l'e-mail, ma che &egrave; stata presa in consegna dal provider";
            }
            echo "<br>ORA SI PUO' CHIUDERE LA FINESTRA";
            exit;
        }
        else
        {
            $pdf->Output("../../spool/stampa_doc.pdf", 'I');
        }

        //echo "<br>fine generazione";
        //----------------------------------------------------------------FINE GENERAZIONE MULTIPLA-------------------------------------------------------------------------------
    }
    else
    {

        $dati = seleziona_documento("leggi_riga_testata", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

        $_status = $dati['status'];
        $_codpagamenti = $dati['modpag'];
        $_dataus = $dati['datareg'];

//purtroppo l'archivio delle fatture contiene una molteplicità di documenti
// quindi devo selezionarli in base alla lettura dell'archivio..
        if ($dati['tdoc'] == $nomedoc)
        {
            $datidoc = layout_doc("singola", "immediata", $conn);
        }
//echo $dati['tdoc'];

        if (($dati['tdoc'] == "FATTURA") OR ( $dati['tdoc'] == "NOTA CREDITO") OR ( $dati['tdoc'] == "NOTA DEBITO"))
        {
            $datidoc = layout_doc("singola", "fattura", $conn);
            $datidoc['ST_NDOC'] = $dati['tdoc'];
        }


//echo $datidoc['ST_NDOC'];
//echo $datidoc['ST_LOGOG'];


        if ($_status == "in-uso")
        {
            echo "<p align=\"center\" class=\"testo_blu\">ATTENZIONE il documento &egrave; gi&agrave; in uso da un altro utente<br>
	Impossibile procedere con la stampa del documento";
            exit;
        }



// Setto il status docuemnto ad stampato
        if (($_status != "evaso") AND ( $_status != "parziale") AND ( $_status != "saldato"))
        {
            status_documento("cambia", $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc, $_form_action, $_azione, "stampato");
        }

        if ($_utente == "fornitori")
        {
            $dati2 = tabella_fornitori("singola", $dati['utente'], $_parametri);
        }
        else
        {
            $dati2 = tabella_clienti("singola", $dati['utente'], $_parametri);
        }

// cerco il tipo di iva abbinato al cliente
        $_ivacliente = $dati2['iva'];

        if ($_ivacliente == "")
        {
            $_ivacliente = $ivasis;
        }

//verifico la e-mail
        if ($dati['tdoc'] == ("FATTURA" OR "NOTA CREDITO" OR "NOTA DEBITO" OR "$nomedoc"))
        {
            $_emaildestino = $dati2['email3'];
            $_emailmittente = $email3;
        }
        else
        {
            $_emaildestino = $dati2['email2'];
            $_emailmittente = $email2;
        }

        $dativa = tabella_aliquota("singola", $_ivacliente, $_percorso);
        $desciva = $dativa['descrizione'];
        $_ivadiversa = $dativa['eseniva'];


// cerco la modalita di pagamento e da codice passo ad estesa

        $dati10 = tabella_pagamenti("singola", $_codpagamenti, $_parametri);
        $_pagamento = $dati10['descrizione'];


// converto la data da americana ad italiana
// invio $_dataus ricevo $_datait
        $_datait = cambio_data("it", $_dataus);


        /*         * *
         * Programma base gestione documenti..
         * questo programma crea i documentii partendo da semplici variabili
         */

        //echo $datidoc['ST_ARTICOLO_LC'];

        if ($_tdoc == "fornitore")
        {
            // questa selezione mi permette di avere il numero di pagine ed il numero di righe in anticipo
            $query = sprintf("select *, substring(articolo,1,'$datidoc[ST_ARTICOLO_CT]') AS articolo, substring(artfor,1,'$datidoc[ST_ARTFOR_CT]') AS artfor, substring(descrizione,1,'$datidoc[ST_DESCRIZIONE_CT]') AS descrizione, scaa AS scva, scab AS scvb, scac AS scvc from $_docdetta where anno=\"%s\" AND suffix=\"%s\", and ndoc=\"%s\" order by rigo", $_anno, $_suffix, $_ndoc);
        }
        else
        {
            $query = sprintf("select *, substring(articolo,1,\"%s\") AS articolo, substring(descrizione,1,\"%s\") AS descrizione from $_docdetta where anno='$_anno' AND suffix='$_suffix' and ndoc='$_ndoc' order by rigo", $datidoc['ST_ARTICOLO_CT'], $datidoc['ST_DESCRIZIONE_CT']);
        }

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query 10 = $query - $_errore[2]";
            $_errori['files'] = "stampa_doc.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
//cerco il numero di righe
        $righe = $result->rowCount();

        //verifichiamo se la scritta di fine documento è attivata, in tal caso dobbiamo togliere una riga al documento
        //verifichiamo st avviso
        if ($datidoc['ST_AVVISO'] == "SI")
        {
            $datidoc['ST_RPP'] = $datidoc['ST_RPP'] - 1;
        }

        //inserisco il numero di righe per pagina
        @$_pagine = $righe / $datidoc['ST_RPP'];
        //arrotondo per eccesso
        $pagina = ceil($_pagine);


//verifichiamo se dobbiamo generare un file pdf oppure un file html normale
        if ($_azione == "Invia")
        {

            //inizio generazione file in formato pdf..
            //richiamo le funzioni che sono all'intrerno del file stampe_doc_pdf.inc.php'
            // setto le variabili standard creazione pdf
            $_title = "$datidoc[ST_NDOC] n. $_ndoc";
            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->SetAutoPageBreak('off', 5);
            $pdf->SetTitle($_title);
            $pdf->SetCreator('Gestionale AGUA GEST - aguagest.sourceforge.net');
            $pdf->SetAuthor($azienda);


            for ($_pg = 1; $_pg <= $pagina; $_pg++)
            {
                // utility per inserire la pagina o creare la pagina.
                $pdf->AddPage();

                //funzione del logo..
                intestazione_doc_pdf($datidoc, $LINGUA);

                //creiamo la testata
                testata_doc_pdf($datidoc, $dati, $dati2, $_datait, $_pg, $pagina, $_pagamento, $LINGUA);

                //creiamo il corpo del documento
                $corpo_doc = (corpo_doc_pdf($datidoc, $result, $LINGUA, $corpo_doc));

                //CREIAMO LA CALCE DEL DOCUMENTO
                calce_doc_pdf($datidoc, $pagina, $_pg, $corpo_doc['netto'], $corpo_doc['iva'], $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento);
            }

            //generazione del files..
            $_pdf = "$_file" . "_" . "$_ndoc.pdf";
            $pdf->Output("../../spool/$_pdf", F);

            //passiamo alla funzione maschera documenti..
            $_parametri['tdoc'] = $_tdoc;
            $_parametri['ndoc'] = $_ndoc;
            $_parametri['anno'] = $_anno;
            $_parametri['BODY'] = $datidoc['BODY'];

            maschera_invio_posta("documento", $_percorso, $_pdf, $_emailmittente, $_emaildestino, "", $_parametri);


            #require "invia_posta.php";
        }//fine funzione di generazione documento in pdf-----------------------------------------------------------------------------------------------------------------------------------------------------
        else
        {
//inizio generazione file stampa documenti normale..

            base_html_stampa("chiudi", $_parametri);


            echo "<center>\n";

            /* In caso che si voglia spedire il documento in formato PDF via e-mail
             * faccio apparire questa scritta che mi rimanda alla creazione del files ed il successivo invio.
             *
             */
            if ($_azione == "Inoltra")
            {
                echo "<center><a href=\"stampa_doc.php?tdoc=$_tdoc&anno=$dati[anno]&ndoc=$dati[ndoc]&azione=Invia&intesta=$_GET[intesta]&prezzi=$_GET[prezzi]&dataora=$_GET[dataora]&lingua=$_GET[lingua]\n";
                echo "\">Per inoltrare il documento via E-mail a " . "$_emaildestino" . " clicca QUI !</a><br>";
            }


            for ($_pg = 1; $_pg <= $pagina; $_pg++)
            {

                echo "<center>\n";
                //funzione del logo..
                intestazione_doc($datidoc, $LINGUA, $_percorso);

                //creiamo la testata
                testata_doc($datidoc, $dati, $dati2, $_datait, $_pg, $pagina, $_pagamento, $LINGUA, $_percorso);

                echo "<br>\n";

                //creiamo il corpo del documento
                $corpo_doc = (corpo_doc($datidoc, $result, $LINGUA, $corpo_doc, $_percorso));

                //CREIAMO LA CALCE DEL DOCUMENTO
                calce_doc($datidoc, $pagina, $_pg, $corpo_doc['netto'], $corpo_doc['iva'], $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento, $_percorso);

                //chiudiamo la pagina del documento.
                # echo "</td></tr></table>\n";
                #  echo "</CENTER>\n";
            }

            // fine programma che genera i documenti in html..

            echo "</body></html>\n";
        }
    }
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