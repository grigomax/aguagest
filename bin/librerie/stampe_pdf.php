<?php

/*
  Programma con funzioni per la creazione di stampe generiche in formato pdf
 * Agua gest file di libreria per la preparazione alla stampa..
 * il tutto in formato PDF
 * Grigolin Massimo massimo@mcetechnik.it
 */


//includiamo i file per la generazione dei pdf
//includo file per generazione pdf
define('FPDF_FONTPATH', $_percorso . 'tools/fpdf/font/');
require_once($_percorso . 'tools/fpdf/fpdf.php');
require_once($_percorso . 'tools/fpdf/html_table.php');

/**
 * La prima funzione mi crea un file pdf con dimensioni, titolo grandezz ecc..
 * @param <type> $_cosa
 * @param <type> $_orientamento Esprimere se orizzontale o verticale
 * @param <type> $_titolo titolo della stampa
 */
function crea_file_pdf($_cosa, $_orientamento, $_titolo)
{
    global $pdf;
    global $azienda;

    if($_cosa == "pdf_table")
    {
        $pdf=new PDF_table();
    }
    else
    {
        $pdf = new FPDF('P', 'mm', 'A4');
    }
    // setto le variabili standard creazione pdf
    
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak('Off', 2);
    $pdf->SetTitle($_titolo, true);
    $pdf->SetAuthor($azienda, true);
    $pdf->SetCreator('Agua Gest - FPDF');
    $pdf->SetSubject($_nomelist);
}

function crea_pagina_pdf()
{
    global $pdf;

    $pdf->AddPage();
}

function crea_intestazione_ditta_pdf($_cosa, $_title, $_anno, $_pg, $pagina, $_parametri)
{
    global $pdf;
    global $azienda;
    global $azienda2;
    global $indirizzo;
    global $cap;
    global $citta;
    global $prov;
    global $piva;
    global $codfisc;
    global $telefono;
    global $fax;
    global $sitointernet;
    global $email1;
    global $email2;
    global $email3;
    global $_azione;
    global $_percorso;


    if ($_azione == "Invia")
    {
        //invia files
        //
    //$pdf->Link(5, 5, 100, 10, "stampe_pdf.php&azione=invia");
        $pdf->SetFont('Arial', '', "15");
        $pdf->SetXY(100, 10);
        //$pdf->Cell(50, 4, "stampa_avviso.php&azione=invia", 0, 0, 'L');
        //$pdf->Write(5, "Invia Per E-mail", "stampa_avviso.php?ndoc=$_parametri[ndoc]&anno=$_anno&azione=Inoltra");
        $pdf->Image($_percorso."images/xfmail.png", 185, 8, 20, 20,'png', $_parametri['link']);
       // Image(string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]])
    }

    if ($_cosa == "conlogo")
    {
        // inserisco l'immagine con l'intestazione
        $pdf->Image("../../../setting/loghiazienda/$_parametri[intesta_immagine]", 10, 8, 193);
        // righe inserimento intestazione listino

        $pdf->SetXY(10, 40);
    }
    elseif ($_cosa == "nologo")
    {
        //provo a lasciare il puntatore
        $pdf->SetXY(10, 40);
    }
    elseif ($_cosa == "libricontabili")
    {
        $pdf->SetXY(5, 5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(5);
        $pdf->Cell(80, 5, $azienda, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(5);
        $pdf->Cell(80, 4, $indirizzo, 0, 1, 'L');
        $pdf->SetX(5);
        $pdf->Cell(11, 4, $cap, 0, 0, 'L');
        $pdf->Cell(50, 4, $citta, 0, 0, 'L');
        $pdf->Cell(10, 4, $prov, 0, 1, 'L');
        $pdf->SetX(5);
        $pdf->Cell(40, 4, "P.I. " . $piva, 0, 0, 'L');
        $pdf->Cell(40, 4, "C.F. " . $codfisc, 0, 1, 'L');

        //provo a lasciare il puntatore
        $pdf->SetXY(87, 5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(75, 5, $_title, 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(10, 5, 'Pag.', 0, 0, 'L');
        $pdf->Cell(10, 5, $_POST['anno'], 0, 0, 'C');
        $pdf->Cell(3, 5, '/', 0, 0, 'C');
        $pdf->Cell(15, 5, $_pg, 0, 1, 'L');
    }
    elseif ($_cosa == "schede_contabili")
    {
        $pdf->SetXY(5, 5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(5);
        $pdf->Cell(80, 5, $azienda, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(5);
        $pdf->Cell(80, 4, $indirizzo, 0, 1, 'L');
        $pdf->SetX(5);
        $pdf->Cell(11, 4, $cap, 0, 0, 'L');
        $pdf->Cell(50, 4, $citta, 0, 0, 'L');
        $pdf->Cell(10, 4, $prov, 0, 1, 'L');
        $pdf->SetX(5);
        $pdf->Cell(40, 4, "P.I. " . $piva, 0, 0, 'L');
        $pdf->Cell(40, 4, "C.F. " . $codfisc, 0, 1, 'L');

        //provo a lasciare il puntatore
        $pdf->SetXY(87, 5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(75, 5, $_title, 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(10, 5, 'Pag.', 0, 0, 'L');
        $pdf->Cell(10, 5, $_pg, 0, 0, 'C');
        $pdf->Cell(3, 5, '/', 0, 0, 'C');
        $pdf->Cell(15, 5, $pagina, 0, 1, 'L');

        //provo a lasciare il puntatore
        $pdf->SetXY(10, 40);
    }
    else
    {
        //provo a lasciare il puntatore
        $pdf->SetXY(10, 9);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetX(10);
        $pdf->Cell(80, 5, $azienda, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetX(10);
        $pdf->Cell(80, 4, $indirizzo, 0, 1, 'L');
        $pdf->SetX(10);
        $pdf->Cell(15, 4, $cap, 0, 0, 'L');
        $pdf->Cell(60, 4, $citta, 0, 0, 'L');
        $pdf->Cell(10, 4, $prov, 0, 1, 'L');
        $pdf->SetX(10);
        $pdf->Cell(40, 4, "P.I. " . $piva, 0, 0, 'L');
        $pdf->Cell(40, 4, "Cod.Fisc " . $codfisc, 0, 1, 'L');
        $pdf->SetX(10);
        $pdf->Cell(40, 4, "Tel. " . $telefono, 0, 0, 'L');
        $pdf->Cell(40, 4, "Tel. / Fax " . $fax, 0, 1, 'L');
        $pdf->SetX(10);

        if ($_parametri['email'] == "3")
        {
            $pdf->Cell(50, 4, "E-mail  " . $email3, 0, 1, 'L');
        }
        elseif ($_parametri['email'] == "2")
        {
            $pdf->Cell(50, 4, "E-mail  " . $email2, 0, 1, 'L');
        }
        else
        {
            $pdf->Cell(50, 4, "E-mail  " . $email1, 0, 1, 'L');
        }
        //provo a lasciare il puntatore
        $pdf->SetXY(10, 40);
    }
}

function intesta_tabella($_cosa, $_codconto, $_descrizione, $data)
{

    global $pdf;
    global $_percorso;

    if ($_cosa == "partitari")
    {
        $pdf->SetXY(10, 30);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 7, 'Estratto conto ', 0, 0, 'C');
        $pdf->SetFont('Arial', '', 12);

        $_printdata = "ANNO $data";
        $pdf->Cell(80, 7, $_printdata, 0, 1, 'C');

        $pdf->Cell(30, 7, 'Codice conto ', 0, 0, 'L');
        $pdf->Cell(20, 7, $_codconto, 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(120, 7, $_descrizione, 0, 1, 'L');
    }
    else
    {
        $pdf->SetXY(10, 30);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 7, 'Estratto conto contabile', 0, 0, 'C');
        $pdf->SetFont('Arial', '', 12);

        $_printdata = "Dalla Data $data[day_start]  Alla data $data[day_end]";
        $pdf->Cell(80, 7, $_printdata, 0, 1, 'C');

        $pdf->Cell(30, 7, 'Codice conto ', 0, 0, 'L');
        $pdf->Cell(20, 7, $_codconto, 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(120, 7, $_descrizione, 0, 1, 'L');
    }
}

function intesta_pagina($_cosa, $_titolo, $_parametri)
{
    global $pdf;
    global $_percorso;
    global $dati;

    if ($_cosa == "titolo")
    {
        //$y = $pdf->GetY();
        //$pdf->SetXY(10, $y);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(193, 10, $_titolo, 0, 0, 'C');
        //provo a lasciare il puntatore
        $pdf->SetXY(10, 50);
    }

    if ($_cosa == "sotto_titolo")
    {
        if($dati[immagine] != "")
        {
            // inserisco l'immagine con l'intestazione
            $pdf->Image("../../../imm-art/$dati[immagine]", 20, 50, 60, 60);
            if ($dati['immagine2'] != "")
            {
                $pdf->Image("../../../imm-art/disegni/$dati[immagine2]", 110, 50, 60, 60);
            }
        }
        

        //provo a lasciare il puntatore
        $pdf->SetXY(10, 50);
    }

    if ($_cosa == "effetti_tabella")
    {

        $pdf->Line(10, 50, 198, 50);
        $pdf->SetXY(10, 55);
        $pdf->SetFont('Arial', 'BI', 10);
        $pdf->Cell(90, 5, 'Spettabile', 0, 0, 'L', 0);
        $pdf->Cell(90, 5, $dati['tipodoc'], 0, 1, 'L', 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(90, 5, $_parametri['ragsoc'], 0, 0, 'L', 0);
        $pdf->Cell(90, 5, "Num. " . $dati['numdoc'] . "/" . $dati['annodoc'] . " del " . $dati['datadoc'], 0, 1, 'L', 0);
        $pdf->Cell(90, 5, $_parametri['indirizzo'], 0, 0, 'L', 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(90, 5, "Banca", 0, 1, 'L', 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(90, 5, "$_parametri[cap] $_parametri[citta] ($_parametri[prov])", 0, 0, 'L', 0);
        $pdf->Cell(90, 5, $dati['bancapp'], 0, 1, 'L', 0);
        $pdf->Cell(90, 5, "P.I. $_parametri[piva]", 0, 0, 'L', 0);
        $pdf->Cell(90, 5, "ABI $dati[abi]  CAB $dati[cab]  CIN $dati[cin]  C/C $dati[cc]", 0, 1, 'L', 0);
        $pdf->Cell(90, 5, "Tel. $_parametri[telefono]", 0, 0, 'L', 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(90, 5, "Pagamento", 0, 1, 'L', 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(90, 5, "Fax $_parametri[fax]", 0, 0, 'L', 0);
        $pdf->Cell(90, 5, "$dati[pagamento]", 0, 1, 'L', 0);
        $pdf->Cell(90, 5, "Email $_parametri[email3]", 0, 0, 'L', 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(90, 5, "Totale Documento $dati[totdoc]", 0, 1, 'L', 0);
        $pdf->SetFont('Arial', '', 10);
    }

    if ($_cosa == "indirizzo_cli")
    {
        $pdf->SetXY(100, 55);
        $pdf->SetFont('Arial', 'BI', 10);
        $pdf->Cell(90, 5, 'Spettabile', 0, 1, 'L', 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetX(100);
        $pdf->Cell(90, 5, $_parametri['ragsoc'], 0, 1, 'L', 0);
        $pdf->SetX(100);
        $pdf->Cell(90, 5, $_parametri['indirizzo'], 0, 1, 'L', 0);
        $pdf->SetX(100);
        $pdf->Cell(90, 5, "$_parametri[cap] $_parametri[citta] ($_parametri[prov])", 0, 1, 'L', 0);
        $pdf->SetX(100);
        $pdf->Cell(90, 5, "Fax $_parametri[fax]", 0, 1, 'L', 0);
        $pdf->SetX(100);
        $pdf->Cell(90, 5, "Email $_parametri[email1]", 0, 1, 'L', 0);
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 10);
    }
}

function corpo_pagina($_cosa, $dati, $_parametri)
{
    global $pdf;
    global $_percorso;
    global $azienda;
    global $indirizzo;
    global $citta;
    global $cap;
    global $prov;
    global $fax;

    if ($_cosa == "scheda_articolo")
    {
        $pdf->SetFillColor(204, 204, 200);
        $pdf->SetXY(10, 115);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, 'Codice', 1, 0, 'L', 1);
        $pdf->Cell(133, 5, 'Descrizione', 1, 0, 'L', 1);
        $pdf->Cell(15, 5, 'U.M.', 1, 0, 'C', 1);
        $pdf->Cell(25, 5, 'Listino', 1, 1, 'C', 1);

        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $pdf->SetFillColor(225, 225, 225);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $dati['articolo'], 1, 0, 'L', 1);
        $pdf->MultiCell(133, 5, $dati['descrizione'], 1, 'L', 1);
        $_finecell = $pdf->GetY();
        $pdf->SetXY($x + 153, $y);

        $pdf->Cell(15, 5, $dati['unita'], 1, 0, 'C', 1);
        if ($dati['listino'] == "0.00")
        {
            $dati['listino'] = "a richiesta";
        }
        $pdf->Cell(25, 5, $dati['listino'], 1, 1, 'C', 1);

        //lasciamo il puntatore corretto..
        $pdf->SetXY(10, $_finecell);
    }

    if ($_cosa == "articolo_correlato")
    {
        //qui inseriamo eventuali codici correlati
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $pdf->SetXY($x, $y);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $dati['articolo'], 1, 0, 'L', 0);
        $pdf->MultiCell(133, 5, $dati['descrizione'], 1, 'L', 0);
        $_finecell = $pdf->GetY();
        $pdf->SetXY($x + 153, $y);

        $pdf->Cell(15, 5, $dati['unita'], 1, 0, 'C', 0);
        if ($dati['listino'] == "0.00")
        {
            $dati['listino'] = "a richiesta";
        }
        $pdf->Cell(25, 5, $dati['listino'], 1, 1, 'C', 0);

        //lasciamo il puntatore corretto..
        $pdf->SetXY(10, $_finecell);
    }

    if ($_cosa == "effetti_tabella")
    {

        $pdf->SetXY(10, 100);
        $pdf->SetFont('Arial', 'I', 6);
        $pdf->Cell(110, 5, 'Tipo Pagamento', 0, 0, 'L', 0);
        $pdf->Cell(40, 5, 'Data Registrazione', 0, 0, 'C', 0);
        $pdf->Cell(40, 5, 'Numero Cedolino', 0, 0, 'C', 0);

        $pdf->SetXY(10, 100);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(110, 11, $_parametri['paga'], 1, 0, 'L', 0);
        $pdf->Cell(40, 11, $dati['datareg'], 1, 0, 'C', 0);
        $pdf->Cell(40, 11, "$dati[numeff]/$dati[annoeff]", 1, 1, 'C', 0);

        $pdf->SetXY(10, 111);
        $pdf->SetFont('Arial', 'I', 6);
        $pdf->Cell(40, 5, 'Scadenza Pagamento', 0, 0, 'L', 0);
        $pdf->Cell(70, 5, 'Importo Scadenza / Ev. Spese', 0, 0, 'C', 0);
        $pdf->Cell(40, 5, 'Importo da Pagare', 0, 0, 'C', 0);
        $pdf->Cell(40, 5, 'Status', 0, 1, 'C', 0);

        $totale = $dati['impeff'] + $dati['spese'];
        $pdf->SetXY(10, 111);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 11, $dati['scadeff'], 1, 0, 'L', 0);
        $pdf->Cell(70, 11, $dati['impeff'] . "+" . $dati['spese'], 1, 0, 'C', 0);
        $pdf->Cell(40, 11, $totale, 1, 0, 'C', 0);
        $pdf->Cell(40, 11, $dati['status'], 1, 1, 'C', 0);
        $pdf->SetFont('Arial', '', 10);
    }


    if ($_cosa == "calce_effetti")
    {

        $pdf->SetXY(60, 260);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(100, 8, "In attesa di un vostro riscontro Porgiamo distinti saluti $azienda", 0, 'L', 0);
    }

    if ($_cosa == "calce_scheda")
    {
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $pdf->SetXY($x, $y);
        $pdf->Line(10, $y + 5, 198, $y + 5);
        $pdf->SetXY(10, $y + 8);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, 'Dettagli Articolo', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, 'Peso Articolo  ' . $dati['pesoart'], 0, 1, 'L');

        $pdf->SetFont('Arial', '', 8);
        //$_note = strip_tags($dati['descsito'],'<table>, <tr>, <td>, colspan');
        $_note = mb_convert_encoding($dati['descsito'], "windows-1252", "UTF-8");
        
        $pdf->WriteHTML($_note);
        
        //$pdf->MultiCell(193, 5, strip_tags($dati['descsito'],'<table>, <tr>, <td>, colspan'), 0, 'L', 0);
        //$pdf->MultiCell(193, 5, $dati['descsito'], 0, 'L', 0);
        //$pdf->WriteHTML($dati['descsito']);
        //$pdf->Write(5,strip_tags($dati['descsito'],'<table>, <tr>, <td>'));
        
    }


    if ($_cosa == "privacy")
    {

        $pdf->SetXY(10, 100);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(180, 5, $_parametri['messaggio_1'], 0, 'L', 0);


        $pdf->MultiCell(180, 5, 'Pertanto, agli effetti del decreto, Le comunichiamo che', 0, 'L', 0);
        $pdf->MultiCell(180, 5, '* - i dati da voi forniti alla nostra societ&agrave; verranno trattati per la gestione amministrativa,
                                e per la gestione degli incarichi da voi affidatoci.', 0, 'L', 0);

        $pdf->MultiCell(180, 5, '* - Il trattamento sar&agrave; eseguito attraverso l\'utilizzo di strumenti prevalentemente informatici e solo in parte manuali.', 0, 'L', 0);

        $pdf->MultiCell(180, 5, '* - Il conferimento  di tali dati &egrave; obbligatorio e l\'eventuale rifiuto potrebbe
                                        comportare la mancanza di esecuzione dell\'incarico professionale affidatoci.', 0, 'L', 0);

        $pdf->MultiCell(180, 5, '* - I dati saranno trasmessi agli uffici ed organi di competenza, da noi incaricati per
                        assolvere tutti gli incar* - Alla diffusione dei dati personali per le finalit&agrave; e negli ambiti indicati.ichi da voi affidatoci, sempre nel rispetto degli obblighi di legge.', 0, 'L', 0);

        $pdf->MultiCell(180, 5, "* - Il responsabile del trattamento è la seguente  $azienda, con sede in $indirizzo $cap - $citta ($prov).", 0, 'L', 0);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(180, 5, "$azienda", 0, 'R', 0);
        $pdf->SetFont('Arial', '', 10);

        $pdf->SetXY(10, 200);
        $pdf->MultiCell(180, 5, "Il sottoscritto__________________________________________________
                Titolare/legale	rappresentante della Ditta", 0, 'L', 0);

        $pdf->SetXY(10, 215);
        $pdf->MultiCell(180, 5, "Acquisite le informazioni fornite dal responsabile del trattamento presta il suo consenso:", 0, 'L', 0);

        $pdf->MultiCell(180, 5, "* - Al trattamento dei dati personali ai fini indicati nella presente informativa;", 0, 'L', 0);
        $pdf->MultiCell(180, 5, "* - Alla comunicazione dei dati personali per le finalit&agrave; ed soggetti indicati;", 0, 'L', 0);
        $pdf->MultiCell(180, 5, "* - Alla diffusione dei dati personali per le finalit&agrave; e negli ambiti indicati.", 0, 'L', 0);

        $pdf->SetXY(10, 250);
        $pdf->MultiCell(180, 5, "Li _____________________________", 0, 'L', 0);
        $pdf->MultiCell(180, 5, "Timbro e firma", 0, 'R', 0);
        $pdf->MultiCell(180, 5, "Siprega di restituirla firmata anche per fax allo $fax; ", 0, 'L', 0);
    }
}

function corpo_tabella($_cosa, $res2, $rpp, $_return)
{
    global $pdf;


    $_dare = $_return['dare'];
    $_avere = $_return['avere'];
    $_saldo = $_return['saldo'];
    $_scritta_p = $_return['scritta_p'];

    //intestiamo le colonne...
    //e poi il corpo..

    if ($_cosa == "partitari")
    {
        $pdf->SetXY(5, 50);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(13, 5, 'N.Gio', 1, 0, 'L');
        $pdf->Cell(13, 5, 'N.Reg.', 1, 0, 'L');
        $pdf->Cell(19, 5, 'Data Cont.', 1, 0, 'C');
        $pdf->Cell(85, 5, 'Descrizione', 1, 0, 'L');
        $pdf->Cell(21, 5, 'Dare', 1, 0, 'C');
        $pdf->Cell(21, 5, 'Avere', 1, 0, 'C');
        $pdf->Cell(21, 5, 'Saldo', 1, 1, 'C');


        for ($_nr = 1; $_nr <= $rpp; $_nr++)
        {
            $dati = mysql_fetch_array($res2);

            if (($dati['dare'] == "") AND ( $dati['avere'] == ""))
            {

                $pdf->SetX(5);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(13, 4, '', RL, 0, 'L');
                $pdf->Cell(13, 4, '', R, 0, 'L');
                $pdf->Cell(19, 4, '', R, 0, 'L');
                $pdf->Cell(85, 4, '', R, 0, 'L');
                $pdf->Cell(21, 4, '', R, 0, 'L');
                $pdf->Cell(21, 4, '', R, 0, 'L');
                $pdf->Cell(21, 4, '', R, 1, 'L');
            }
            else
            {
                $pdf->SetX(5);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(13, 4, $dati[giornale], LR, 0, 'L');
                $pdf->Cell(13, 4, $dati[nreg], R, 0, 'L');
                $pdf->Cell(19, 4, $dati[data_cont], R, 0, 'C');
                $pdf->Cell(85, 4, $dati[descrizione], R, 0, 'L');

                if ($dati['dare'] == "0.00")
                {
                    $dati['dare'] = "";
                }
                if ($dati['avere'] == "0.00")
                {
                    $dati['avere'] = "";
                }

                $pdf->Cell(21, 4, $dati[dare], R, 0, 'R');
                $pdf->Cell(21, 4, $dati[avere], R, 0, 'R');

                $_dare = $_dare + $dati['dare'];
                $_avere = $_avere + $dati['avere'];
                $_saldo = $_saldo + $dati['dare'] - $dati['avere'];

                if ($_saldo > "0.00")
                {
                    $_scritta_p = "D";
                }
                else
                {
                    $_scritta_p = "A";
                }

                #echo "<td width=\"70\" align=\"right\"class=\"tabella_elenco\">" . number_format($_saldo, '2') . " $_scritta_p</span></td>\n";
                $pdf->Cell(21, 4, number_format($_saldo, '2') . $_scritta_p, R, 1, 'R');
            }
        }
        
        $_width = "193";
    }
    else
    {
        $pdf->SetXY(10, 50);
        $pdf->SetFillColor(0,0,255);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(13, 5, 'N.Reg.', 1, 0, 'L', true);
        $pdf->Cell(20, 5, 'Data Cont.', 1, 0, 'L', true);
        $pdf->Cell(10, 5, 'Proto', 1, 0, 'L', true);
        $pdf->Cell(80, 5, 'Descrizione', 1, 0, 'L', true);
        $pdf->Cell(23, 5, 'Dare', 1, 0, 'C', true);
        $pdf->Cell(23, 5, 'Avere', 1, 0, 'C', true);
        $pdf->Cell(23, 5, 'Saldo', 1, 1, 'C', true);

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(13, 4, '', RL, 0, 'L');
        $pdf->Cell(20, 4, '', R, 0, 'L');
        $pdf->Cell(10, 4, '', R, 0, 'L');
        $pdf->Cell(80, 4, 'Saldo a Riporto', R, 0, 'L');
        $pdf->Cell(23, 4, '', R, 0, 'L');
        $pdf->Cell(23, 4, '', R, 0, 'L');
        $pdf->Cell(23, 4, $_saldo ." ". $_scritta_p, R, 1, 'R');

        
        for ($_nr = 1; $_nr <= $rpp; $_nr++)
        {
            $dati = $res2->fetch(PDO::FETCH_ASSOC);

            if (($dati['dare'] == "") AND ( $dati['avere'] == ""))
            {
                #$pdf->SetXY(10,60);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(13, 4, '', RL, 0, 'L');
                $pdf->Cell(20, 4, '', R, 0, 'L');
                $pdf->Cell(10, 4, '', R, 0, 'L');
                $pdf->Cell(80, 4, '', R, 0, 'L');
                $pdf->Cell(23, 4, '', R, 0, 'L');
                $pdf->Cell(23, 4, '', R, 0, 'L');
                $pdf->Cell(23, 4, '', R, 1, 'L');
            }
            else
            {
                #$pdf->SetXY(10,60);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(13, 4, $dati[nreg], LR, 0, 'L');
                $pdf->Cell(20, 4, $dati[data_cont], R, 0, 'L');
                $pdf->Cell(10, 4, $dati[nproto]."/".$dati[suffix_proto], R, 0, 'L');
                $pdf->Cell(80, 4, $dati[descrizione], R, 0, 'L');

                if ($dati['dare'] == "0.00")
                {
                    $dati['dare'] = "";
                }
                if ($dati['avere'] == "0.00")
                {
                    $dati['avere'] = "";
                }

                $pdf->Cell(23, 4, $dati[dare], R, 0, 'R');
                $pdf->Cell(23, 4, $dati[avere], R, 0, 'R');

                $_dare = $_dare + $dati['dare'];
                $_avere = $_avere + $dati['avere'];
                $_saldo = $_saldo + $dati['dare'] - $dati['avere'];

                if ($_saldo > "0.00")
                {
                    $_scritta_p = "D";
                }
                else
                {
                    $_scritta_p = "A";
                }

                #echo "<td width=\"70\" align=\"right\"class=\"tabella_elenco\">" . number_format($_saldo, '2') . " $_scritta_p</span></td>\n";
                $pdf->Cell(23, 4, number_format($_saldo, '2') ." ". $_scritta_p, R, 1, 'R');
            }
        }
        
        $_width = "192";
    }

    $_return['dare'] = $_dare;
    $_return['avere'] = $_avere;
    $_return['saldo'] = $_saldo;
    $_return['width'] = $_width;
    return $_return;
}

function calce_tabella($_cosa, $_dare, $_avere, $_saldo, $_width)
{
    global $pdf;

    if ($_saldo > "0.00")
    {
        $_scritta = "Dare";
    }
    else
    {
        $_scritta = "Avere";
    }



    if ($_cosa == "partitari")
    {
        $pdf->SetX(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($_width, 5, '', 1, 1, 'L');
        $pdf->SetX(5);
        $pdf->Cell(13, 5, '', 1, 0, 'L');
        $pdf->Cell(13, 5, '', 1, 0, 'L');
        $pdf->Cell(19, 5, '', 1, 0, 'L');
        $pdf->Cell(85, 5, '', 1, 0, 'L');
        $pdf->Cell(21, 5, $_dare, 1, 0, 'R');
        $pdf->Cell(21, 5, $_avere, 1, 0, 'R');
        $pdf->Cell(21, 5, '', 1, 1, 'L');
        $pdf->SetX(5);
        $_print = "Totale Saldo = " . number_format($_saldo, '2') . " $_scritta";

        $pdf->Cell($_width, 5, $_print, 1, 1, 'R');
    }
    else
    {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($_width, 5, '', 1, 1, 'L');
        $pdf->Cell(13, 5, '', 1, 0, 'L');
        $pdf->Cell(20, 5, '', 1, 0, 'L');
        $pdf->Cell(10, 5, '', 1, 0, 'L');
        $pdf->Cell(80, 5, '', 1, 0, 'L');
        $pdf->Cell(23, 5, $_dare, 1, 0, 'R');
        $pdf->Cell(23, 5, $_avere, 1, 0, 'R');
        $pdf->Cell(23, 5, '', 1, 1, 'L');

        $_print = "Totale Saldo = " . number_format($_saldo, '2') . " $_scritta";

        $pdf->Cell($_width, 5, $_print, 1, 1, 'R');
    }
}

function chiudi_files($_title, $_percorso, $_modalita)
{
    global $pdf;
    //generazione del files..
    $_pdf = "$_title.pdf";
    $pdf->Output("$_percorso/spool/$_pdf", $_modalita);

    return $_pdf;
}

?>