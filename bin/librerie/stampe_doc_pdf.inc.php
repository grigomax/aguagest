<?php

/*
  Programma con funzioni per la creazione dei documenti.
 * Agua gest file di libreria per la preparazione alla stampa..
 * il tutto in formato PDF
 * Grigolin Massimo massimo@mcetechnik.it
 */

//dimensioni e dinamica del programma di stampa pdf..
//paametri univuoci
//margine sinistro:
$MARGINE_SINISTRO = "7";
//margine superione
$MARGINE_SUPERIORE = "5";


/* Funzioni di creazione documenti l'estenzione pdf sarà per gli stessi.
 */

//funzione che mi genera il castelletto d'iva
function castello_iva_pdf($_ivadiversa, $_castiva, $pagina, $_pg, $_x_iva, $_y_iva, $datidoc, $LINGUA, $desciva, $dati)
{
    global $pdf;
    global $conn;
    global $_percorso;
    global $dec;
    
    //per prima cosa passiamo l'inclusione dei files vars.php
    require $_percorso."../setting/vars.php";

    // includiamo il file delle lingue
    include $_percorso."librerie/$LINGUA";
    require_once $_percorso."librerie/lib_html.php";
    require_once $_percorso."librerie/motore_anagrafiche.php";

    //dobbiamo inserire le spese di trasporto nel castelletto iva
    //considerando le spese sono con iva 20%
    if ($dati['datareg'] < $DATAIVA)
    {
	$ivasis = $ivasis - 1;
    }

    //dobbiamo inserire le spese di trasporto nel castelletto iva
    //considerando le spese sono con iva 20%
    if ($_ivadiversa != "2")
    {
	$_castiva[$ivasis] = ($_castiva[$ivasis] + (($dati['imballo'] + $dati['trasporto'] + $dati['spesevarie'] + $dati['sp_bancarie']) - $dati['scoinco']));
    }


    //riordino l castello iva
    arsort($_castiva);

    // devo creare il castelletto dell'iva...
    // che sia unico per tutti i tipi di documento.

    $pdf->SetXY($_x_iva, $_y_iva);
    $pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 10);
    $pdf->Cell(35, 6, $CI001, 0, 0, 'R');
    $pdf->Cell(19, 6, $CI002, 0, 0, 'C');
    $pdf->Cell(21, 6, $CI003, 0, 0, 'R');

    //lascio la prima riga
    //passo a variabile al acastello cosi mi si costruisce.
    $_ycast = $_y_iva;
    //verifico che valori pubblicare

    if ($pagina == $_pg)
    {
	// se il cliente e esente lo faccio apparire
	if ($_ivadiversa == "2")
	{
	    $pdf->SetXY($_x_iva, $_ycast);
	    $pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	    $pdf->Cell(77, 18, $desciva, 1, 1, 'C');
	}
	else
	{
	    // Visualizzo tatali iva diverse
	    while (@list($indice, $valore) = each($_castiva))
	    {
		$_aliquota = tabella_aliquota("singola_aliquota", $indice, $_percorso);

		$_ivasep = number_format((($valore * $_aliquota) / 100), $dec, '.', '');
		if ($indice != "")
		{
		    $_ycast = $_ycast + 5;
		    $pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
		    $pdf->SetXY($_x_iva, $_ycast);
		    $pdf->Cell(35, 4, $valore, 0, 0, 'R');
		    $pdf->Cell(19, 4, $indice . '%', 0, 0, 'C');
		    $pdf->Cell(21, 4, $_ivasep, 0, 0, 'R');
		}
	    }
	}
    }

    //creo una cella che mi ridisegni il quadrato
    $pdf->SetXY($_x_iva, $_y_iva);
    $pdf->Cell(77, 18, '', 1, 1, 'C');
}

/* * *
 * Funzione che mi crea il logo del   in formato pdf
 * quattro tipi di logo
 * 1=con logo grande
 * 2= con mezzo logo
 * 3=con intestazione tutta scritta da variabili
 * 4=con spazio relativo ad un logo pre esistente in carta.
 *
 */

function intestazione_doc_pdf($datidoc, $LINGUA)
{//inizio funzioni testata documenti
//
    global $conn;
    global $dec;
    global $_percorso;
    
//per prima cosa passiamo l'inclusione dei files vars.php
    require $_percorso."../setting/vars.php";
    // includiamo il file delle lingue
    include $_percorso."librerie/$LINGUA";
    // passo la variabile globale..

    GLOBAL $pdf;
    global $MARGINE_SINISTRO;
    global $MARGINE_SUPERIORE;


    if ($_GET['intesta'] == "no")
    {
	$pdf->SetXY($MARGINE_SINISTRO, 45);
    }
    else
    {

	if ($datidoc[ST_TLOGO] == "1")
	{
	    // inserisco l'immagine con l'intestazione
	    $pdf->Image("../../setting/loghiazienda/$datidoc[ST_LOGOG]", $MARGINE_SINISTRO, $MARGINE_SUPERIORE, 193, 30, jpg);

	    //provo a lasciare il puntatore
	    $pdf->SetXY($MARGINE_SINISTRO, 45);
	}

	if ($datidoc[ST_TLOGO] == "2")
	{
	    //provo a lasciare il puntatore
	    $pdf->SetXY($MARGINE_SINISTRO, 9);
	    $pdf->SetFont('Arial', 'B', 12);
	    $pdf->SetX($MARGINE_SINISTRO);
	    $pdf->Cell(80, 5, $azienda, 0, 1, 'L');
	    $pdf->SetFont('Arial', '', 12);
	    $pdf->SetX($MARGINE_SINISTRO);
	    $pdf->Cell(80, 4, $azienda2, 0, 1, 'L');
	    $pdf->SetFont('Arial', '', 10);
	    $pdf->SetX($MARGINE_SINISTRO);
	    $pdf->Cell(80, 4, $indirizzo, 0, 1, 'L');
	    $pdf->SetX($MARGINE_SINISTRO);
	    $pdf->Cell(15, 4, $cap, 0, 0, 'L');
	    $pdf->Cell(60, 4, $citta, 0, 0, 'L');
	    $pdf->Cell(10, 4, $prov, 0, 1, 'L');
	    $pdf->SetX($MARGINE_SINISTRO);
	    $pdf->Cell(40, 4, $LG001 . "  " . $piva, 0, 0, 'L');
	    $pdf->Cell(40, 4, $LG002 . "  " . $codfisc, 0, 1, 'L');
	    $pdf->SetX($MARGINE_SINISTRO);
	    $pdf->Cell(40, 4, $LG003 . "  " . $telefono, 0, 0, 'L');
	    $pdf->Cell(40, 4, $LG004 . "  " . $fax, 0, 1, 'L');
	    $pdf->SetX($MARGINE_SINISTRO);
	    $pdf->Cell(50, 4, $LG005 . "   " . $sitointernet, 0, 0, 'L');
	    $pdf->Cell(50, 4, $LG006 . "   " . $email1, 0, 1, 'L');

	    //provo a lasciare il puntatore
	    $pdf->SetXY($MARGINE_SINISTRO, 40);
	}

	if ($datidoc[ST_TLOGO] == "3")
	{
	    //logo aziendale a sinistra e intestazione a destra
	    // inserisco l'immagine con l'intestazione
	    $pdf->SetXY($MARGINE_SINISTRO, 9);
	    $pdf->Image("../../setting/loghiazienda/$datidoc[ST_LOGOM]", 100, 35, 100, 30, jpg);


	    $pdf->SetXY(110, 9);
	    $pdf->SetFont('Arial', 'B', 12);
	    $pdf->SetX(110);
	    $pdf->Cell(80, 5, $azienda, 0, 1, 'L');
	    $pdf->SetFont('Arial', '', 12);
	    $pdf->SetX(110);
	    $pdf->Cell(80, 4, $azienda2, 0, 1, 'L');
	    $pdf->SetFont('Arial', '', 10);
	    $pdf->SetX(110);
	    $pdf->Cell(80, 4, $indirizzo, 0, 1, 'L');
	    $pdf->SetX(110);
	    $pdf->Cell(15, 4, $cap, 0, 0, 'L');
	    $pdf->Cell(60, 4, $citta, 0, 0, 'L');
	    $pdf->Cell(10, 4, $prov, 0, 1, 'L');
	    $pdf->SetX(110);
	    $pdf->Cell(40, 4, $LG001 . "  " . $piva, 0, 0, 'L');
	    $pdf->Cell(40, 4, $LG002 . "  " . $codfisc, 0, 1, 'L');
	    $pdf->SetX(110);
	    $pdf->Cell(40, 4, $LG003 . "  " . $telefono, 0, 0, 'L');
	    $pdf->Cell(40, 4, $LG004 . "  " . $fax, 0, 1, 'L');
	    $pdf->SetX(110);
	    $pdf->Cell(50, 4, $LG005 . "   " . $sitointernet, 0, 0, 'L');
	    $pdf->Cell(50, 4, $LG006 . "   " . $email1, 0, 1, 'L');

	    //provo a lasciare il puntatore
	    $pdf->SetXY($MARGINE_SINISTRO, 40);
	}
        
        if ($datidoc[ST_TLOGO] == "5")
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
            $pdf->SetXY(10, 30);
        }
        
        
    }
}

/* * *
 * Funzione che mi crea la testata del documento  in formato html..
 * qui ci dovrebbero essere quattro tipo di testate
 * una per le fatture
 * una per le fatture immediate
 * una per i DDT
 * una per le conferme ordieni preventivi ecc.
 */

function testata_doc_pdf($datidoc, $dati, $dati2, $_datait, $_pg, $pagina, $_pagamento, $LINGUA, $_parametri)
{//inizio funzioni testata documenti
    global $pdf;
    global $_percorso;
    global $_azione;
    global $conn;
    global $dec;
    
    global $MARGINE_SINISTRO;
    global $MARGINE_SUPERIORE;
    //per prima cosa passiamo l'inclusione dei files vars.php
    require $_percorso."../setting/vars.php";
    // includiamo il file delle lingue
    include $_percorso."librerie/$LINGUA";

    
    //qui mettiamo l'immagine per poterla inviare il documento
    if ($_azione == "Inoltra")
    {
        $_y = $pdf->GetY();
        $_x = $pdf->GetX();
        //invia files
        //
    //$pdf->Link(5, 5, 100, 10, "stampe_pdf.php&azione=invia");
        $pdf->SetFont('Arial', '', "15");
        $pdf->SetXY(100, 10);
        //$pdf->Cell(50, 4, "stampa_avviso.php&azione=invia", 0, 0, 'L');
        //$pdf->Write(5, "Invia Per E-mail", "stampa_avviso.php?ndoc=$_parametri[ndoc]&anno=$_anno&azione=Inoltra");
        $pdf->Image($_percorso."images/xfmail.png", 185, 8, 20, 20,'png', $_parametri['link']);
       // Image(string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]])
        
        //rilasciamo il puntatore corretto..
        $pdf->SetXY($_x, $_y);
    }
    
    
    

    if ($datidoc[ST_TIPOTESTATA] == "1")
    {// tipo di testata semplice per ddt o fatture immediate con la visualizzazione sulla destra della destinazione diversa
	//prendiamo l'altezza
	$_y = $pdf->GetY();

	//iniziamo con il lato sinistro

	$pdf->SetFont('Times', 'I', 12);
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //50
	$pdf->Cell(30, 5, $ID001, 0, 1, 'L');
	$pdf->SetFont($datidoc[ST_FONTINTEST], 'B', $datidoc[ST_FONTINTESTSIZE]);
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(80, 5, $dati2['ragsoc'], 0, 1, 'L');
	$pdf->SetFont($datidoc[ST_FONTINTEST], '', $datidoc[ST_FONTINTESTSIZE]);
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(80, 5, $dati2['ragsoc2'], 0, 1, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(80, 5, $dati2['indirizzo'], 0, 1, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(15, 5, $dati2['cap'], 0, 0, 'L');
	$pdf->Cell(60, 5, $dati2['citta'], 0, 0, 'L');
	$pdf->Cell(10, 5, $dati2['prov'], 0, 1, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(70, 5, $dati2['codnazione'], 0, 1, 'L');
        $pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(70, 5, $ID002 . $dati2['piva'], 0, 1, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(80, 5, $ID003 . $dati2['telefono'], 0, 1, 'L');

	//Parte Destra
	if ($dati['dragsoc'] != "")
	{

	    $pdf->SetFont('Times', '', '10');
	    $pdf->SetXY(($MARGINE_SINISTRO + 100), $_y); //50
	    $pdf->Cell(30, 5, $ID004, 0, 1, 'L');
	    $pdf->SetFont($datidoc[ST_FONTINTEST], 'B', $datidoc[ST_FONTINTESTSIZE]);
	    $pdf->SetX(($MARGINE_SINISTRO + 105));
	    $pdf->Cell(80, 5, $dati['dragsoc'], 0, 1, 'L');
	    $pdf->SetFont($datidoc[ST_FONTINTEST], '', $datidoc[ST_FONTINTESTSIZE]);
	    $pdf->SetX(($MARGINE_SINISTRO + 105));
	    $pdf->Cell(80, 5, $dati['dragsoc2'], 0, 1, 'L');
	    $pdf->SetX(($MARGINE_SINISTRO + 105));
	    $pdf->Cell(80, 5, $dati['dindirizzo'], 0, 1, 'L');
	    $pdf->SetX(($MARGINE_SINISTRO + 105));
	    $pdf->Cell(15, 5, $dati['dcap'], 0, 0, 'L');
	    $pdf->Cell(60, 5, $dati['dcitta'], 0, 0, 'L');
	    $pdf->Cell(10, 5, $dati['dprov'], 0, 1, 'L');
	    $pdf->SetX(($MARGINE_SINISTRO + 105));
	    $pdf->Cell(70, 5, $dati['dcodnazione'], 0, 1, 'L');
	    $pdf->SetX(($MARGINE_SINISTRO + 105));
	    $pdf->Cell(80, 5, $ID005 . $dati2['telefonodest'], 0, 1, 'L');
	}

	$_y = $_y + 46;
	//	Rilascio il puntatore a sinistra
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
    }//fine testata n. 1

    if ($datidoc[ST_TIPOTESTATA] == "2")
    {
	// tipo di testata complessa e completa.. tipo conferme preventivi ecc..
	//lato sinistro
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', $datidoc[ST_FONTESTASIZE]);
	$pdf->SetXY($MARGINE_SINISTRO, 45);
	$pdf->Cell(99, 30, '', 1, 0, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(10, 5, "$datidoc[ST_NDOC] n. ", 0, 0, 'L');
	$pdf->SetX(55);
	$pdf->Cell(10, 5, $dati['ndoc'], 0, 0, 'L');
        $pdf->Cell(3, 5, '/', 0, 0, 'L');
	$pdf->Cell(5, 5, $dati['suffix'], 0, 0, 'L');
	$pdf->Cell(3, 5, '/', 0, 0, 'L');
	$pdf->Cell(10, 5, $dati['anno'], 0, 0, 'L');
	$pdf->Cell(13, 5, 'Rev. n. ', 0, 0, 'L');
	$pdf->Cell(8, 5, $dati['rev'], 0, 1, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 50);
	$pdf->Cell(10, 5, $TC007, 0, 0, 'L');
	$pdf->SetX(55);
	$pdf->Cell(10, 5, $_datait, 0, 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 55);
	$pdf->Cell(10, 5, $TC003, 0, 0, 'L');
	$pdf->SetX(55);
	$pdf->Cell(10, 5, $_pg, 0, 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	$pdf->SetXY($MARGINE_SINISTRO, 60);
	$pdf->Cell(10, 5, $TC014, 0, 0, 'L');
	$pdf->SetX($MARGINE_SINISTRO + 13);
	$pdf->Cell(15, 5, $dati2['email2'], 0, 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 65);
	$pdf->Cell(10, 5, $TC006, 0, 0, 'L');
	$pdf->SetX($MARGINE_SINISTRO + 30);
	$pdf->Cell(10, 5, $dati['vettore'], 0, 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 70);
	$pdf->Cell(10, 5, $TC005, 0, 0, 'L');
	$pdf->SetX(50);
	$pdf->Cell(10, 5, $_look, 0, 0, 'L');
	// inizio riquadro basso
	$pdf->SetXY($MARGINE_SINISTRO, 75);
	$pdf->Cell(99, 25, '', 1, 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 75);
	$pdf->Cell(10, 5, $TC013, 0, 0, 'L');
	$pdf->SetX($MARGINE_SINISTRO + 20);
	$pdf->Cell(10, 5, $_pagamento, 0, 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 80);
	$pdf->Cell(15, 5, $TC015, 0, 0, 'L');
	$pdf->Cell(10, 5, $dati2['banca'], 0, 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 85);
	$pdf->Cell(15, 5, $TC016, 0, 0, 'L');
	$pdf->Cell(30, 5, $dati2['swift'], 0, 0, 'L');
	$pdf->Cell(13, 5, $TC017, 0, 0, 'L');
	$pdf->Cell(10, 5, $dati2['iban'], 0, 0, 'L');
	$pdf->Cell(10, 5, $TC018, 0, 0, 'L');
	$pdf->Cell(10, 5, $dati2['cin'], 0, 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 90);
	$pdf->Cell(10, 5, $TC019, 0, 0, 'L');
	$pdf->Cell(13, 5, $dati2['abi'], 0, 0, 'L');
	$pdf->Cell(10, 5, $TC020, 0, 0, 'L');
	$pdf->Cell(13, 5, $dati2['cab'], 0, 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 95);
	$pdf->Cell(10, 5, $TC021, 0, 0, 'L');
	$pdf->Cell(13, 5, $dati2['cc'], 0, 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, 100);

	// lato destro sopra
	$pdf->SetFont($datidoc[ST_FONTINTEST], 'B', $datidoc[ST_FONTINTESTSIZE]);
	$pdf->SetXY(($MARGINE_SINISTRO + 99), 45);
	$pdf->Cell(97, 30, '', 1, 0, 'L');
	$pdf->SetXY(($MARGINE_SINISTRO + 99), 45);
	$pdf->Cell(30, 5, $ID001, 0, 1, 'L');
	$pdf->SetX(($MARGINE_SINISTRO + 100));
	$pdf->Cell(85, 5, $dati2['ragsoc'], 0, 1, 'L');
	$pdf->SetFont($datidoc[ST_FONTINTEST], '', $datidoc[ST_FONTINTESTSIZE]);
	$pdf->SetX(($MARGINE_SINISTRO + 100));
	$pdf->Cell(85, 5, $dati2['ragsoc2'], 0, 1, 'L');
	$pdf->SetX(($MARGINE_SINISTRO + 100));
	$pdf->Cell(85, 5, $dati2['indirizzo'], 0, 1, 'L');
	$pdf->SetX(($MARGINE_SINISTRO + 100));
	$pdf->Cell(15, 5, $dati2['cap'], 0, 0, 'L');
	$pdf->Cell(52, 5, $dati2['citta'], 0, 0, 'L');
	$pdf->Cell(10, 5, $dati2['prov'], 0, 1, 'L');
	$pdf->SetX(($MARGINE_SINISTRO + 100));
	$pdf->Cell(70, 5, $dati2['codnazione'], 0, 1, 'L');

	// lato destro sotto
	$pdf->SetXY(($MARGINE_SINISTRO + 99), 75);
	$pdf->Cell(97, 25, '', 1, 0, 'L');
	$pdf->SetXY(($MARGINE_SINISTRO + 100), 75);
	$pdf->Cell(30, 5, $ID004, 0, 1, 'L');
	$pdf->SetX(($MARGINE_SINISTRO + 100));
	$pdf->Cell(85, 5, $dati['dragsoc'], 0, 1, 'L');
	$pdf->SetX(($MARGINE_SINISTRO + 100));
	$pdf->Cell(85, 5, $dati['dragsoc2'], 0, 1, 'L');
	$pdf->SetX(($MARGINE_SINISTRO + 100));
	$pdf->Cell(85, 5, $dati['dindirizzo'], 0, 1, 'L');
	$pdf->SetX(($MARGINE_SINISTRO + 100));
	$pdf->Cell(15, 5, $dati['dcap'], 0, 0, 'L');
	$pdf->Cell(52, 5, $dati['dcitta'], 0, 0, 'L');
	$pdf->Cell(10, 5, $dati['dprov'], 0, 1, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(60, 5, $TC022, 1, 0, 'L');
	$pdf->Cell(136, 5, $dati2['swift'] . $dati2['iban'] . $dati2['cin'] . $dati2['abi'] . $dati2['cab'] . $dati2['cc'], 1, 1, 'L');

	//Rilascio il puntatore alla fine della tabella a sx;
    }

    if ($datidoc[ST_TIPOTESTATA] == "3")
    {// tipo di testata a mono esposizione a dx tipica fatture
	// devo prendermi le variabili di dove sono in modo da creare la posizione in automatico.
	//prendiamo l'altezza
	$_y = $pdf->GetY();

	$pdf->SetFont('Times', '', 10);
	$pdf->SetXY(110, $_y); //50
	$pdf->Cell(30, 5, $ID001, 0, 1, 'L');
	$pdf->SetFont($datidoc[ST_FONTINTEST], 'B', $datidoc[ST_FONTINTESTSIZE]);
	$pdf->SetX(115);
	$pdf->Cell(80, 5, $dati2['ragsoc'], 0, 1, 'L');
	$pdf->SetFont($datidoc[ST_FONTINTEST], '', $datidoc[ST_FONTINTESTSIZE]);
	$pdf->SetX(115);
	$pdf->Cell(80, 5, $dati2['ragsoc2'], 0, 1, 'L');
	$pdf->SetX(115);
	$pdf->Cell(80, 5, $dati2['indirizzo'], 0, 1, 'L');
	$pdf->SetX(115);
	$pdf->Cell(15, 5, $dati2['cap'], 0, 0, 'L');
	$pdf->Cell(60, 5, $dati2['citta'], 0, 0, 'L');
	$pdf->Cell(10, 5, $dati2['prov'], 0, 1, 'L');
	$pdf->SetX(115);
	$pdf->Cell(70, 5, $dati2['codnazione'], 0, 1, 'L');

	$_y = $_y + 40;
	//	Rilascio il puntatore a sinistra
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
    }// fine testata n. 3

    if ($datidoc[ST_TIPOTESTATA] == "4")
    {// tipo di testata semplice per ddt o fatture immediate con la visualizzazione sulla destra della destinazione diversa
	//senza telefono
	//prendiamo l'altezza
	$_y = $pdf->GetY();

	//iniziamo con il lato sinistro

	$pdf->SetFont('Times', 'I', 12);
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //50
	$pdf->Cell(30, 5, $ID001, 0, 1, 'L');
	$pdf->SetFont($datidoc[ST_FONTINTEST], 'B', $datidoc[ST_FONTINTESTSIZE]);
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(80, 5, $dati2['ragsoc'], 0, 1, 'L');
	$pdf->SetFont($datidoc[ST_FONTINTEST], '', $datidoc[ST_FONTINTESTSIZE]);
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(80, 5, $dati2['ragsoc2'], 0, 1, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(80, 5, $dati2['indirizzo'], 0, 1, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(15, 5, $dati2['cap'], 0, 0, 'L');
	$pdf->Cell(60, 5, $dati2['citta'], 0, 0, 'L');
	$pdf->Cell(10, 5, $dati2['prov'], 0, 1, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	$pdf->Cell(70, 5, $dati2['codnazione'], 0, 1, 'L');
	$pdf->Cell(70, 5, $ID002 . $dati2['piva'], 0, 1, 'L');
	$pdf->SetX($MARGINE_SINISTRO);
	#$pdf->Cell(80,5,$ID003. $dati2['telefono'],0,1,'L');
	//Parte Destra
	if ($dati['dragsoc'] != "")
	{

	    $pdf->SetFont('Times', '', '10');
	    $pdf->SetXY(110, $_y); //50
	    $pdf->Cell(30, 5, $ID004, 0, 1, 'L');
	    $pdf->SetFont($datidoc[ST_FONTINTEST], 'B', $datidoc[ST_FONTINTESTSIZE]);
	    $pdf->SetX(115);
	    $pdf->Cell(80, 5, $dati['dragsoc'], 0, 1, 'L');
	    $pdf->SetFont($datidoc[ST_FONTINTEST], '', $datidoc[ST_FONTINTESTSIZE]);
	    $pdf->SetX(115);
	    $pdf->Cell(80, 5, $dati['dragsoc2'], 0, 1, 'L');
	    $pdf->SetX(115);
	    $pdf->Cell(80, 5, $dati['dindirizzo'], 0, 1, 'L');
	    $pdf->SetX(115);
	    $pdf->Cell(15, 5, $dati['dcap'], 0, 0, 'L');
	    $pdf->Cell(60, 5, $dati['dcitta'], 0, 0, 'L');
	    $pdf->Cell(10, 5, $dati['dprov'], 0, 1, 'L');
	    $pdf->SetX(115);
	    $pdf->Cell(70, 5, $dati['dcodnazione'], 0, 1, 'L');
	    $pdf->SetX(115);
	    #$pdf->Cell(80,5,$ID005. $dati2['telefonodest'],0,1,'L');
	}

	$_y = $_y + 46;
	//	Rilascio il puntatore a sinistra
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
    }//fine testata n. 4
    
    if ($datidoc[ST_TIPOTESTATA] == "5")
    {
        $pdf->Cell(190,10, "$datidoc[ST_NDOC] $datidoc[tipo]  Pagina $_pg di $pagina", 0,1,'C');
    }




    if ($datidoc[ST_SOTTOTESTATA] == "1")
    {// ottima per le bolle..
	//intestazione delle celle..
	//prendiamo le variabili di dove siamo
	$_y = $pdf->GetY();

	$pdf->SetXY($MARGINE_SINISTRO, $_y); //90
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', 10);
	$pdf->Cell(70, 10, $datidoc['ST_NDOC'], 1, 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', 10);
	$pdf->Cell(70, 10, $_causale, 1, 0, 'C');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', 10);
	$pdf->Cell(19, 10, $_pg, 1, 0, 'C');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', 12);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetX(169);
	$pdf->Cell(32, 10, '', 1, 0);
	$pdf->SetX(166);
	//$pdf->SetFillColor(166, 244, 249);
	$pdf->Cell(15, 10, $dati['ndoc'], 'LT', 0, 'R', true);
	$pdf->SetX(180);
        $pdf->Cell(3, 10, '/', 'T', 0, 'C', true);
	$pdf->SetX(183);
	$pdf->Cell(4, 10, $dati['suffix'], '', 0, 'L', true);
        $pdf->SetX(187);
	$pdf->Cell(3, 10, '/', 'T', 0, 'C', true);
	$pdf->SetX(190);
	$pdf->Cell(11, 10, $dati['anno'], 'TRB', 0, 'L', true);

	// righe inserimento tabella contabilita
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //90
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(70, 4, $TC001, '0', 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(70, 4, $TC002, '0', 0, 'L');
	$pdf->Cell(19, 4, $TC003, '0', 0, 'C');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'BI', 7);
	$pdf->Cell(32, 4, $TC004, '0', 0, 'C');

	$_y = $_y + 10;

	// inserimento variabili
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', 9);
	$pdf->Cell(70, 10, $_porto, '1', 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', 9);
	$pdf->Cell(89, 6, $dati['vettore'], '0', 'O', 'C');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', 12);
	$pdf->Cell(35, 10, $_datait, '1', 0, 'C');
	$_yv = $_y + 6;
	$pdf->SetXY(80, $_yv);
	$pdf->Cell(89, 6, $dativ['indirizzo'], '0', 'O', 'L');

	//fine inserikmento variabili
	// fine intestazione celle
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(70, 4, $TC005, '0', 0, 'L');
	$pdf->Cell(89, 4, $TC006, '0', 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'BI', 7);
	$pdf->Cell(32, 4, $TC007, '0', 0, 'C');

	$_y = $_y + 10;

	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(189, 4, $TC008, '0', 0, 'L');
	//fine intestazione

	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', 9);
	$pdf->Cell(151, 10, $dati2['contatto'], '1', 0, 'L');
        
        $_GET['saved'] = "yes";
        $_GET['codetype'] = "Code39";
        $_GET['size'] = "40";
        $_GET['text'] = $dati[anno].$dati[suffix].$dati[ndoc];
        include_once "../tools/barcode_2/barcode.php";
        $_Y_image = $pdf->GetY();
        $pdf->Image("../../spool/barcode_".$_SESSION['user']['user'].".png", '160',$_Y_image+1, 40, 8, png);
        $pdf->Cell(43, 10,'', '1', 1, 'L');
        
        //<img src=$_percorso/tools/barcode/barcode.php?barcode=$dati[anno]$dati[suffix]$dati[ndoc]&width=250&height=40&text=0>
        
        
        
        
	//fine inserikmento variabili
	//rilascio in automatico il puntatore..
	$_y = $_y + 10;
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
    }// fine seconda sottotestata.

    if ($datidoc[ST_SOTTOTESTATA] == "2")
    {// completa per fatture, anche accompagnatorie ecc...
	//intestazione delle celle..
	//prendiamo le variabili di dove siamo
	$_y = $pdf->GetY();

	$pdf->SetXY($MARGINE_SINISTRO, $_y); //90
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', 10);
	$pdf->Cell(42, 10, $datidoc['ST_NDOC'], 1, 0, 'C');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', 10);
	$pdf->Cell(11, 10, $dati['agente'], 1, 0, 'C');
	$pdf->Cell(23, 10, $dati['utente'], 1, 0, 'C');
	$pdf->Cell(39, 10, $dati2['codfisc'], 1, 0, 'C');
	$pdf->Cell(30, 10, $dati2['piva'], 1, 0, 'C');
	$pdf->Cell(18, 10, $_pg, 1, 0, 'C');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', 12);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetX($MARGINE_SINISTRO + 160);
	$pdf->Cell(32, 10, '', 1, 0);
	$pdf->SetX($MARGINE_SINISTRO + 160);
	$pdf->SetFillColor(166, 244, 249);
	$pdf->Cell(16, 10, $dati['ndoc'], 'LT', 0, 'R', true);
	$pdf->SetX($MARGINE_SINISTRO + 175);
	$pdf->Cell(2, 10, '/', 0, 0, 'C', true);
        $pdf->SetX($MARGINE_SINISTRO + 177);
	$pdf->Cell(15, 10, $dati['suffix'], 'RB', 0, 'L', true);
        $pdf->SetX($MARGINE_SINISTRO + 181);
	$pdf->Cell(2, 10, '/', 0, 0, 'C', true);
        $pdf->SetX($MARGINE_SINISTRO + 183);
	$pdf->Cell(12, 10, $dati['anno'], 'RB', 0, 'L', true);
       
        

	// righe inserimento tabella contabilita
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //90
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(42, 4, $TC001, '0', 0, 'C');
	$pdf->Cell(11, 4, $TC009, '0', 0, 'C');
	$pdf->Cell(23, 4, $TC010, '0', 0, 'C');
	$pdf->Cell(39, 4, $TC011, '0', 0, 'C');
	$pdf->Cell(30, 4, $TC012, '0', 0, 'C');
	$pdf->Cell(18, 4, $TC003, '0', 0, 'C');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'BI', 7);
	$pdf->Cell(32, 4, $TC004, '0', 0, 'C');

	$_y = $_y + 10;

	// inserimento variabili
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', 9);
	$pdf->Cell(100, 10, $_pagamento, '1', 0, 'L');
	$pdf->SetFillColor('255', '255', '255');
	$pdf->SetX($MARGINE_SINISTRO + 100);
	$pdf->MultiCell(63, 6, $dati2['email3'], '0', 'L', true);
	$pdf->SetXY($MARGINE_SINISTRO + 100, $_y);
	$pdf->Cell(63, 10, '', '1', 0, 'L');
	$pdf->SetFillColor(166, 244, 249);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', 12);
	$pdf->Cell(32, 10, $_datait, '1', 0, 'C', true);

	//fine inserikmento variabili
	// fine intestazione celle
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(100, 4, $TC013, '0', 0, 'L');
	$pdf->Cell(63, 4, $TC14, '0', 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'BI', 7);
	$pdf->Cell(32, 4, $TC007, '0', 0, 'C');

	$_y = $_y + 10;

	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(100, 4, $TC015, '0', 0, 'L');
	$pdf->Cell(24, 4, $TC016, '0', 0, 'C');
	$pdf->Cell(9, 4, $TC017, '0', 0, 'C');
	$pdf->Cell(8, 4, $TC018, '0', 0, 'C');
	$pdf->Cell(14, 4, $TC019, '0', 0, 'C');
	$pdf->Cell(14, 4, $TC020, '0', 0, 'C');
	$pdf->Cell(26, 4, $TC021, '0', 0, 'C');
	//fine intestazione

	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', 9);
	$pdf->Cell(100, 10, $dati['banca'], '1', 0, 'L');
	$pdf->Cell(24, 10, $dati['swift'], '1', 0, 'C');
	$pdf->Cell(9, 10, $dati['iban'], '1', 0, 'C');
	$pdf->Cell(8, 10, $dati['cin'], '1', 0, 'C');
	$pdf->Cell(14, 10, $dati['abi'], 1, 0, 'C');
	$pdf->Cell(14, 10, $dati['cab'], 1, 0, 'C');
	$pdf->Cell(26, 10, $dati['cc'], '1', 1, 'C');
	//fine inserikmento variabili
	//rilascio in automatico il puntatore..
	$_y = $_y + 10;
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
    }// fine seconda sottotestata.
}

// fine funzione testata documenti


/* * *
 * Funzione di creazione corpo documenti in formato html
 *  *
 */

function corpo_doc_pdf($datidoc, $result, $LINGUA, $corpo_doc)
{//qui passeremo tutti gli arrre per la gestione..
//recupero la variabile pdf
    global $pdf;
    global $MARGINE_SINISTRO;
    global $MARGINE_SUPERIORE;
    global $conn;
    global $_percorso;

    //per prima cosa passiamo l'inclusione dei files vars.php
    require $_percorso."../setting/vars.php";

    // includiamo il file delle lingue
    include $_percorso."librerie/$LINGUA";

    #Splitto gli importi solo se diversi da nullo..
    if($corpo_doc!= "")
    {
        $_nettovendita = $corpo_doc['netto'];
        $_castiva = $corpo_doc['iva'];
    }
    

    //dobbiamo creare un file pdf che sia compatibile con tutti i documenti che vengono creati
    //senza dover diventare matti, e quindi anche definire degli spazi corretti.
    //la tabella corpo io direi di farla iniziazio circa mezzo centimetro al di sotto della testata..
    //intastazione tabella
    //settiamo dove siamo ed il tipo di carattere
    $_y = $pdf->GetY();

    $_y = $_y + 5;

    $pdf->SetXY($MARGINE_SINISTRO, $_y);
    $larghezza = "0";

    $pdf->SetFont($datidoc[ST_FONTCORPO], 'B', $datidoc[ST_FONTCORPOSIZE]);

    //verifichiamo se vogliono la colonna righe
    if ($datidoc[ST_RIGA] == "SI")
    {
	//$datidoc[ST_RIGA_LC] = $datidoc[ST_RIGA_LC] * "1.93";
	$pdf->Cell($datidoc[ST_RIGA_LC], 5, $CD001, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_RIGA_LC];
    }
    //verifichiamo se vogliono la colonna articolo
    if ($datidoc[ST_ARTICOLO] == "SI")
    {
	//setto il rapporto millimetri percentuale 1.93
	//$datidoc[ST_ARTICOLO_LC] = $datidoc[ST_ARTICOLO_LC] * "1.93";
	$pdf->Cell($datidoc[ST_ARTICOLO_LC], 5, $CD002, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_ARTICOLO_LC];
    }

    //verifichiamo se vogliono la colonna articolo
    if ($datidoc[ST_ARTFOR] == "SI")
    {
	//$datidoc[ST_ARTFOR_LC] = $datidoc[ST_ARTFOR_LC] * "1.93";
	$pdf->Cell($datidoc[ST_ARTFOR_LC], 5, $CD003, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_ARTFOR_LC];
    }

    //verifichiamo se vogliono la colonna articolo
    if ($datidoc[ST_DESCRIZIONE] == "SI")
    {
	//$datidoc[ST_DESCRIZIONE_LC] = $datidoc[ST_DESCRIZIONE_LC] * "1.93";
	$pdf->Cell($datidoc[ST_DESCRIZIONE_LC], 5, $CD004, '1', 0, 'L');
        $larghezza = $larghezza + $datidoc[ST_DESCRIZIONE_LC];
    }
    //verifichiamo se vogliono la colonna articolo
    if ($datidoc[ST_UNITA] == "SI")
    {
	//$datidoc[ST_UNITA_LC] = $datidoc[ST_UNITA_LC] * "1.93";
	$pdf->Cell($datidoc[ST_UNITA_LC], 5, $CD005, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_UNITA_LC];
    }
    //verifichiamo se vogliono la colonna articolo
    if ($datidoc[ST_QUANTITA] == "SI")
    {
	//$datidoc[ST_QUANTITA_LC] = $datidoc[ST_QUANTITA_LC] * "1.93";
	$pdf->Cell($datidoc[ST_QUANTITA_LC], 5, $CD006, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_QUANTITA_LC];
    }

    //verifichiamo se vogliono la colonnna
    if ($datidoc[ST_QTAEVASA] == "SI")
    {
	//$datidoc[ST_QTAEVASA_LC] = $datidoc[ST_QTAEVASA_LC] * "1.93";
	$pdf->Cell($datidoc[ST_QTAEVASA_LC], 5, $CD007, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_QTAEVASA_LC];
    }

    //verifichiamo se vogliono la colonnna
    if ($datidoc[ST_QTAESTRATTA] == "SI")
    {
	//$datidoc[ST_QTAESTRATTA_LC] = $datidoc[ST_QTAESTRATTA_LC] * "1.93";
	$pdf->Cell($datidoc[ST_QTAESTRATTA_LC], 5, $CD008, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_QTAESTRATTA_LC];
    }

    //verifichiamo se vogliono la colonnna
    if ($datidoc[ST_QTASALDO] == "SI")
    {
	//$datidoc[ST_QTASALDO_LC] = $datidoc[ST_QTASALDO_LC] * "1.93";
	$pdf->Cell($datidoc[ST_QTASALDO_LC], 5, $CD009, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_QTASALDO_LC];
    }

    //verifichiamo se vogliono la colonna articolo
    if ($datidoc[ST_LISTINO] == "SI")
    {
	//$datidoc[ST_LISTINO_LC] = $datidoc[ST_LISTINO_LC] * "1.93";
	$pdf->Cell($datidoc[ST_LISTINO_LC], 5, $CD010, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_LISTINO_LC];
    }
    //verifichiamo se vogliono la colonna sconti
    if ($datidoc[ST_SCONTI] == "SI")
    {
	//$datidoc[ST_SCONTI_LC] = $datidoc[ST_SCONTI_LC] * "1.93";
	$pdf->Cell($datidoc[ST_SCONTI_LC], 5, $CD011, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_SCONTI_LC];
    }

    //verifichiamo se vogliono la colonna sconto
    if ($datidoc[ST_NETTOVENDITA] == "SI")
    {
	//$datidoc[ST_NETTOVENDITA_LC] = $datidoc[ST_NETTOVENDITA_LC] * "1.93";
	$pdf->Cell($datidoc[ST_NETTOVENDITA_LC], 5, $CD012, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_NETTOVENDITA_LC];
    }

    //verifichiamo se vogliono la colonna sconto
    if ($datidoc[ST_TOTRIGA] == "SI")
    {
	//$datidoc[ST_TOTRIGA_LC] = $datidoc[ST_TOTRIGA_LC] * "1.93";
	$pdf->Cell($datidoc[ST_TOTRIGA_LC], 5, $CD013, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_TOTRIGA_LC];
    }

    //verifichiamo se vogliono la colonna articolo
    if ($datidoc[ST_CODIVA] == "SI")
    {
	//$datidoc[ST_CODIVA_LC] = $datidoc[ST_CODIVA_LC] * "1.93";
	$pdf->Cell($datidoc[ST_CODIVA_LC], 5, $CD014, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_CODIVA_LC];
    }

    //verifichiamo se vogliono la colonna consegna
    if ($datidoc[ST_PESO] == "SI")
    {
	//$datidoc[ST_PESO_LC] = $datidoc[ST_PESO_LC] * "1.93";
	$pdf->Cell($datidoc[ST_PESO_LC], 5, $CD015, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_ST_PESO_LC];
    }

    //verifichiamo se vogliono la colonna consegna
    if ($datidoc[ST_RSALDO] == "SI")
    {
	//$datidoc[ST_RSALDO_LC] = $datidoc[ST_RSALDO_LC] * "1.93";
	$pdf->Cell($datidoc[ST_RSALDO_LC], 5, $CD016, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_RSALDO_LC];
    }

    //verifichiamo se vogliono la colonna consegna
    if ($datidoc[ST_CONSEGNA] == "SI")
    {
	//$datidoc[ST_CONSEGNA_LC] = $datidoc[ST_CONSEGNA_LC] * "1.93";
	$pdf->Cell($datidoc[ST_CONSEGNA_LC], 5, $CD017, '1', 0, 'C');
        $larghezza = $larghezza + $datidoc[ST_CONSEGNA_LC];
        
    }

    //impostiamo i caratteri ed il font
    //impostiamo  l'inizio della tabella
    $_y = $_y + 1;
    $pdf->SetXY($MARGINE_SINISTRO, $_y);

    // ciclo di estrazione dei dati
    for ($_nr = 1; $_nr <= $datidoc['ST_RPP']; $_nr++)
    {

	//verifichiamo se vogliono la colonna righe
	if (($datidoc[ST_AVVISO] == "SI") AND ($_nr == $datidoc['ST_RPP']))
	{
	    $_y = $_y + 4;
	    $pdf->SetXY($MARGINE_SINISTRO, $_y);
	    $pdf->SetFont($datidoc[ST_FONTCORPO], '', $datidoc[ST_FONTCORPOSIZE]);
	    $pdf->Cell(193, 4, $datidoc[ST_AVVISO_LC], 'L', 0, 'C');
	}
	else
	{

            
	    $dati3 = $result->fetch(PDO::FETCH_ASSOC);

	    //impostiamo dove siamo
	    $_y = $_y + 4;
	    $pdf->SetXY($MARGINE_SINISTRO, $_y);
	    $pdf->SetFont($datidoc[ST_FONTCORPO], '', $datidoc[ST_FONTCORPOSIZE]);

	    //verifichiamo se vogliono la colonna righe
	    if ($datidoc[ST_RIGA] == "SI")
	    {
		$pdf->Cell($datidoc[ST_RIGA_LC], 4, $dati3['rigo'], 'L', 0, 'C');
	    }

	    //verifichiamo se vogliono la colonna articoli
	    if ($datidoc[ST_ARTICOLO] == "SI")
	    {

		$_articolo = $dati3['articolo'];
		// eliminazione della scritta vuoto dalla stampa
		if ($_articolo == "vuoto")
		{
		    $_articolo = "";
		}
                
		$pdf->Cell($datidoc[ST_ARTICOLO_LC], 4, $_articolo, 'L', 0, $datidoc[ST_ARTICOLO_ALL]);
	    }

	    //verifichiamo se vogliono la colonna articoli
	    if ($datidoc[ST_ARTFOR] == "SI")
	    {

		$_artfor = $dati3['artfor'];
		// eliminazione della scritta vuoto dalla stampa
		if ($_artfor == "vuoto")
		{
		    $_artfor = "";
		}
		$datidoc[ST_ARTFOR_ALL] = substr($datidoc[ST_ARTFOR_ALL], 0, 1);
		$pdf->Cell($datidoc[ST_ARTFOR_LC], 4, $_artfor, 'L', 0, $datidoc[ST_ARTFOR_ALL]);
	    }

	    //verifichiamo se vogliono la colonna descrizione
	    if ($datidoc[ST_DESCRIZIONE] == "SI")
	    {
		$pdf->Cell($datidoc[ST_DESCRIZIONE_LC], 4, $dati3['descrizione'], 'L', 0, $datidoc[ST_DESCRIZIONE_ALL]);
	    }

	    //verifichiamo se vogliono la colonna unita
	    if ($datidoc[ST_UNITA] == "SI")
	    {
		$pdf->Cell($datidoc[ST_UNITA_LC], 4, $dati3['unita'], 'L', 0, $datidoc[ST_UNITA_ALL]);
	    }

	    //verifichiamo se vogliono la colonna descrizione
	    if ($datidoc[ST_QUANTITA] == "SI")
	    {
		$_quantita = $dati3['quantita'];
		if ($_quantita == 0)
		{
		    $_quantita = "";
		}
		$datidoc[ST_QUANTITA_ALL] = substr($datidoc[ST_QUANTITA_ALL], 0, 1);
		$pdf->Cell($datidoc[ST_QUANTITA_LC], 4, $_quantita, 'L', 0, $datidoc[ST_QUANTITA_ALL]);
	    }

	    //verifichiamo se vogliono la colonna descrizione
	    if ($datidoc[ST_QTAEVASA] == "SI")
	    {
		$_qtaevasa = $dati3['qtaevasa'];
		if ($_qtaevasa == 0)
		{
		    $_qtaevasa = "";
		}
		$pdf->Cell($datidoc[ST_QTAEVASA_LC], 4, $_qtaevasa, 'L', 0, $datidoc[ST_QTAEVASA_ALL]);
	    }

	    //verifichiamo se vogliono la colonna descrizione
	    if ($datidoc[ST_QTAESTRATTA] == "SI")
	    {
		$_qtaestratta = $dati3['qtaestratta'];
		if ($_qtaestratta == 0)
		{
		    $_qtaestratta = "";
		}
		$pdf->Cell($datidoc[ST_QTAESTRATTA_LC], 4, $_qtaestratta, 'L', 0, $datidoc[ST_QTAESTRATTA_ALL]);
	    }

	    //verifichiamo se vogliono la colonna descrizione
	    if ($datidoc[ST_QTASALDO] == "SI")
	    {
		$_qtasaldo = $dati3['qtasaldo'];
		if ($_qtasaldo == 0)
		{
		    $_qtasaldo = "";
		}
		$pdf->Cell($datidoc[ST_QTASALDO_LC], 4, $_qtasaldo, 'L', 0, $datidoc[ST_QTASALDO_ALL]);
	    }

	    //verifichiamo se vogliono la colonna descrizione
	    if ($datidoc[ST_LISTINO] == "SI")
	    {
		if (($datidoc['tdoc'] == "ddt" ) AND ($_GET['prezzi'] == "no"))
		{
		    $dati3['listino'] = "";
		}

		$_pne = "";
		$_listino = $dati3['listino'];
		if ($_listino == 0)
		{
		    $_listino = "";
		}

		if ($datidoc[ST_AVV_PN] == "SI")
		{
		    $_pne = "";
		    if (($_articolo != "") and ($_articolo != "vuoto"))
		    {
			if ($dati3['scva'] + $dati3['scvb'] + $dati3['scvc'] == 0.00)
			{
			    $_pne = '*';
			}
		    }
		}

		$_stlistino = "$_listino $_pne";
		$pdf->Cell($datidoc[ST_LISTINO_LC], 4, $_stlistino, 'LR', 0, $datidoc[ST_LISTINO_ALL]);
	    }

	    //verifichiamo se vogliono la colonna sconti
	    if ($datidoc[ST_SCONTI] == "SI")
	    {
		$_scva = $dati3['scva'];
		if ($_scva == 0)
		{
		    $_scva = "";
		}

		$_scvb = $dati3['scvb'];
		if ($_scvb == 0)
		{
		    $_scvb = "";
		}

		$_scvc = $dati3['scvc'];
		if ($_scvc == 0)
		{
		    $_scvc = "";
		}
		
		$_percentuale = "";
		
		if($_scva+$_scvb+$_scvc != "")
		{
		    $_percentuale = "%";
		}
		
		$_sconti = "$_scva $_scvb $_scvc$_percentuale";
		
		$pdf->Cell($datidoc[ST_SCONTI_LC], 4, $_sconti, 'L', 0, $datidoc[ST_SCONTI_ALL]);
				
		
	    }

	    if ($datidoc[ST_NETTOVENDITA] == "SI")
	    {
		$_nettovendita = $dati3['nettovendita'];
		if ($_nettovendita == 0)
		{
		    $_nettovendita = "";
		}
		$pdf->Cell($datidoc[ST_NETTOVENDITA_LC], 4, $_nettovendita, 'LR', 0, $datidoc[ST_NETTOVENDITA_ALL]);
	    }

	    if ($datidoc[ST_TOTRIGA] == "SI")
	    {
		$_totriga = $dati3['totriga'];
		if ($_totriga == 0)
		{
		    $_totriga = "";
		}
		
		$pdf->Cell($datidoc[ST_TOTRIGA_LC], 4, $_totriga, 'LR', 0, $datidoc[ST_TOTRIGA_ALL]);
	    }

	    //verifichiamo se vogliono la colonna unita
	    if ($datidoc[ST_CODIVA] == "SI")
	    {
		$_iva = $dati3['iva'];
		if ($_totriga == 0)
		{
		    $_iva = "";
		}
		$pdf->Cell($datidoc[ST_CODIVA_LC], 4, $_iva, 'LR', 0, $datidoc[ST_CODIVA_ALL]);
	    }

	    if ($datidoc[ST_PESO] == "SI")
	    {
		$_peso = $dati3['peso'];
		if ($_peso == "")
		{
		    $_peso = "";
		}
		$pdf->Cell($datidoc[ST_PESO_LC], 4, $_peso, 'LR', 0, $datidoc[ST_PESO_ALL]);
	    }

	    if ($datidoc[ST_RSALDO] == "SI")
	    {
		$_rsaldo = $dati3['rsaldo'];
		if ($_rsaldo == "")
		{
		    $_rsaldo = "";
		}
		$pdf->Cell($datidoc[ST_RSALDO_LC], 4, $_rsaldo, 'LR', 0, $datidoc[ST_RSALDO_ALL]);
	    }

	    if ($datidoc[ST_CONSEGNA] == "SI")
	    {
		$_consegna = $dati3['consegna'];
		if ($_consegna == "")
		{
		    $_consegna = "";
		}
                
		$pdf->Cell($datidoc[ST_CONSEGNA_LC], 4, substr($_consegna, 0, $datidoc[ST_CONSEGNA_CT]), 'LR', 0, $datidoc[ST_CONSEGNA_ALL]);
	    }

	    // cALCOLO DEL CASTELLETTO IVA:
	    $_ivariga = $dati3['iva'];
	    $_castiva[$_ivariga] = ($_castiva[$_ivariga] + $dati3['totriga']);
	    $_nettovendita = $_nettovendita + $dati3['totriga'];
            $_totini = $dati3['quantita'] + $_totini;
            $_totacq = $dati3['qtaevasa'] + $_totacq;
            $_totvend = $dati3['qtaestratta'] + $_totvend;
            
            //azzero l'aary
            $dati3 = "";
            
	}
    }
    
    
    
    //chiodo il corpo con la somma dei pubblicati..
    
    $_y = $_y + 4;
    $pdf->SetXY($MARGINE_SINISTRO, $_y);
    $pdf->Cell($larghezza, 1, '', 'T', 0, 'C');

    //LAscio il puntatore..
    $_y = $_y + 1;
    $pdf->SetXY($MARGINE_SINISTRO, $_y);

    //impostiamo i ritorni..
    //ritorno il netto vendita ed l'iva..'
    return array("netto" => $_nettovendita, "iva" => $_castiva, "iniziale" => $_totini, "acquisto" => $_totacq, "venduta" => $_totvend);
}

//fine funzione costruzione corpo documenti html

/* * *
 * Funzione che mi crea la calce del documento  in formato html..
 * qui ci dovrebbero essere quattro tipo di calce
 * una per le fatture
 * una per le fatture immediate
 * una per i DDT
 * una per le conferme ordieni preventivi ecc.
 */

function calce_doc_pdf($datidoc, $pagina, $_pg, $_nettovendita, $_castiva, $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento)
{//qui passeremo tutti gli arrre per la gestione..
//recupero la variabile, PDF
    global $pdf;
    global $MARGINE_SINISTRO;
    global $MARGINE_SUPERIORE;
    global $_percorso;
    global $conn;
    global $dec;
    
    //per prima cosa passiamo l'inclusione dei files vars.php
    require $_percorso."../setting/vars.php";

    // includiamo il file delle lingue
    include $_percorso."librerie/$LINGUA";

    //    Verifico la tipologia di stampa...


    if ($_GET['dataora'] == "no")
    {
	$ST_DATA = "";
	$ST_TIME = "";
    }
    elseif ($_ALLT['dataora'] == "no")
    {
	$ST_DATA = "";
	$ST_TIME = "";
    }
    else
    {
	$ST_DATA = date("d/n/Y");
	$ST_TIME = date("H:i");
    }


    //inizio calce tipo DDT
    if ($datidoc[ST_TIPOCALCE] == "1")
    {

	//mi prendo dove sono..
	$_y = $pdf->GetY();
	//setto i numeri per netto merce
	number_format($_nettovendita, $dec, '.', '');

	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 10);
	$pdf->Cell(190, 5, $TC023, '0', '1', 'C');
	// inizio calce documento

	$_y = $_y + 5;
	// intestazione campi
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(71, 4, $TC024, '0', 0, 'L');
	$pdf->Cell(15, 4, $TC025, '0', 0, 'C');
	$pdf->Cell(20, 4, $TC026, '0', 0, 'C');
	$pdf->Cell(25, 4, $TC027, '0', 0, 'C');
	$pdf->Cell(20, 4, $TC038, '0', 0, 'C');
	$pdf->Cell(20, 4, $TC028, '0', 0, 'C');
	$pdf->Cell(20, 4, $TC029, '0', 0, 'C');
	//variabili
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	// inizio eliminazione per segue pagina
	if ($pagina == $_pg)
	{
	    $_aspetto = $_look;
	}
	$pdf->Cell(71, 10, $_aspetto, 1, 0, 'L');

	if ($pagina == $_pg)
	{
	    $_colli = $dati['colli'];
	}
	$pdf->Cell(15, 10, $_colli, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_pesotot = $dati['pesotot'];
	}
	$pdf->Cell(20, 10, $_pesotot, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_tran = $dati['trasporto'];
	}
	$pdf->Cell(25, 10, $_tran, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_spese = $dati['spesevarie'];
	}
	$pdf->Cell(20, 10, $_spese, 1, 0, 'C');

	if ($pagina != $_pg)
	{
	    $ST_DATA = "";
	    $ST_TIME = "";
	}
	$pdf->Cell(20, 10, $ST_DATA, 1, 0, 'C');

	$pdf->Cell(20, 10, $ST_TIME, 1, 1, 'C', 0);

	$_y = $_y + 10;

	// intestazione campi
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'BI', 7);
	$pdf->Cell(189, 4, $TC030, '0', 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', $datidoc[ST_FONTESTASIZE]);
	$pdf->Cell(191, 10, $dati['note'], '1', 1, 'L');

	// chiusura finale le sue firme..
	$_y = $_y + 10;
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'IB', 7);
	$pdf->Cell(95, 4, $TC031, '0', 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'IB', 7);
	$pdf->Cell(96, 4, $TC032, '0', 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->Cell(95, 12, '', '1', 0, 'L');
	$pdf->Cell(96, 12, '', '1', 1, 'L');
    }//fine calce tipo 1
    //inizio calce tipo conferma
    if ($datidoc[ST_TIPOCALCE] == "2")
    {
	//setto i numeri per netto merce
	number_format($_nettovendita, $dec, '.', '');

	//mi prendo dove sono..
	$_y = $pdf->GetY();

	// inizio calce documento
	// intestazione campi
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', 10);
	$pdf->Cell(117, 44, '', 1, 0, 'C');
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->Cell(17, 4, $TC030, '0', 'L');
	$pdf->SetXY($MARGINE_SINISTRO, $_y + 4);
	if ($pagina == $_pg)
	{
	    $_annot = $dati['note'];
	}
	$pdf->MultiCell(112, 4, $_annot, 0, 'L');

	$pdf->SetXY(($MARGINE_SINISTRO + 117), $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 10);
	$pdf->Cell(40, 6, $TC034, 1, 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	if ($pagina == $_pg)
	{
	    $_nettov = $_nettovendita;
	}
	$pdf->Cell(37, 6, $_nettov, 1, 1, 'R');

	$pdf->SetXY(($MARGINE_SINISTRO + 117), $_y + 6);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 10);
	$pdf->Cell(40, 6, $TC027, 1, 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	if ($pagina == $_pg)
	{
	    $_tran = $dati['trasporto'];
	}
	$pdf->Cell(37, 6, $_tran, 1, 0, 'R');
	
	$pdf->SetXY(($MARGINE_SINISTRO + 117), $_y + 12);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 10);
	$pdf->Cell(40, 6, $TC038, 1, 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	if ($pagina == $_pg)
	{
	    $_spese = $dati['spesevarie'];
	}
	$pdf->Cell(37, 6, $_spese, 1, 0, 'R');


	
//recupero il castelletto dell'iva passando come parametro anche le coordinate sul dove costruirlo
	$_x_iva = ($MARGINE_SINISTRO + 117);
	$_y_iva = $_y + 18;
	castello_iva_pdf($_ivadiversa, $_castiva, $pagina, $_pg, $_x_iva, $_y_iva, $datidoc, $LINGUA, $desciva, $dati);

	$pdf->SetXY(($MARGINE_SINISTRO + 117), $_y + 36);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', 10);
	$pdf->Cell(40, 8, $TC040, 1, 0, 'L');
	if ($pagina == $_pg)
	{
	    $_totdoc = $dati['totdoc'];
	}
	else
	{
	    $_totdoc = "$TC043 $_pg+1";
	}
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', $datidoc[ST_FONTESTASIZE]);
	$pdf->Cell(37, 8, $_totdoc, 1, 1, 'R');
    }//fine calce tipo 2
    
    
    //inizio calce tipo classica fattura..
    if ($datidoc[ST_TIPOCALCE] == "3")
    {

	//mi prendo dove sono..
	$_y = $pdf->GetY();
	//setto i numeri per netto merce
	number_format($_nettovendita, $dec, '.', '');

	$pdf->SetXY($MARGINE_SINISTRO, $_y);

	$pdf->SetFont('', '', '7');
	$pdf->Cell(190, 3, $TC033, '0', '1', 'C');
	// inizio calce documento

	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);

	$_y = $_y + 3;
	// intestazione campi
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(32, 4, $TC034, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC035, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC036, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC027, '0', 0, 'C');
	$pdf->Cell(31, 4, $TC038, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC039, '0', 0, 'C');
	//variabili
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	// inizio eliminazione per segue pagina
	if ($pagina == $_pg)
	{
	    $_nettom = $dati['nettomerce'];
	}
	$pdf->Cell(32, 10, $_nettom, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_scoinco = $dati['scoinco'];
	}
	$pdf->Cell(32, 10, $_scoinco, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_imballo = $dati['imballo'];
	}
	$pdf->Cell(32, 10, $_imballo, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_tran = $dati['trasporto'];
	}
	$pdf->Cell(32, 10, $_tran, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_spesev = $dati['spesevarie'];
	}
	$pdf->Cell(31, 10, $_spesev, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_sp_banc = $dati['sp_bancarie'];
	}
	$pdf->Cell(36, 10, $_sp_banc, 1, 1, 'C', 0);
	// fine prima parte

	$_y = $_y + 10;

	// intestazione campi
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //266
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(70, 4, $TC030, '0', 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 8);
	$pdf->SetXY(164, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'BI', 10);
	$pdf->Cell(40, 4, $TC040, '0', 0, 'C');
	// fine intestazione
	//recupero le variabili per l'iva
	$_x_iva = $MARGINE_SINISTRO + 70;
	$_y_iva = $_y;

	// aggiungo lospessre celle
	$_y = $_y + 4;

	//variabili
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //270
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	// inizio eliminazione per segue pagina
	if ($pagina == $_pg)
	{
	    $_annot = $dati['note'];
	}
	$pdf->MultiCell(70, 5, $_annot, 0, 'L');

	//recupero il castelletto dell'iva passando come parametro anche le coordinate sul dove costruirlo
	castello_iva_pdf($_ivadiversa, $_castiva, $pagina, $_pg, $_x_iva, $_y_iva, $datidoc, $LINGUA, $desciva, $dati);

	if ($pagina == $_pg)
	{
	    $_totdoc = $dati['totdoc'];
	}
	else
	{
	    $_totdoc = "$TC043 $_pg+1";
	}
	$pdf->SetXY($MARGINE_SINISTRO +70+77, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', $datidoc[ST_FONTESTASIZE]);
	$pdf->Cell(48, 12, EURO . ' ' . $_totdoc, 0, 1, 'C');

	//creo una cella che mi ridisegni i quadrati
	$pdf->SetXY($MARGINE_SINISTRO, $_y_iva);
	$pdf->Cell(70, 18, '', 1, 0, 'C');
	$pdf->Cell(77, 18, '', 1, 0, 'C');
	$pdf->Cell(48, 18, '', 1, 1, 'C');
	
	
    }//fine calce tipo 3
    //
    //
    //inizio calce tipo classica fattura IMMEDIATA..
    if ($datidoc[ST_TIPOCALCE] == "4")
    {
	//mi prendo dove sono..
	$_y = $pdf->GetY();
	//setto i numeri per netto merce
	$_nettovendita = (number_format($_nettovendita, $dec, '.', ''));

	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont('', '', '7');
	$pdf->Cell(192, 3, $TC033, '0', '1', 'C');
	// inizio calce documento

	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);

	$_y = $_y + 3;
	// intestazione campi
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(34, 4, $TC034, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC035, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC036, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC027, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC038, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC039, '0', 0, 'C');
	//variabili
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	// inizio eliminazione per segue pagina
	if ($pagina == $_pg)
	{
	    $_nettom = $dati['nettomerce'];
	}
	$pdf->Cell(34, 10, $_nettom, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_scoinco = $dati['scoinco'];
	}
	$pdf->Cell(32, 10, $_scoinco, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_imballo = $dati['imballo'];
	}
	$pdf->Cell(32, 10, $_imballo, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_tran = $dati['trasporto'];
	}
	$pdf->Cell(32, 10, $_tran, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_spesev = $dati['spesevarie'];
	}
	$pdf->Cell(32, 10, $_spesev, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_sp_banc = $dati['sp_bancarie'];
	}
	$pdf->Cell(32, 10, $_sp_banc, 1, 1, 'C', 0);
	// fine prima parte

	$_y = $_y + 10;

	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', 10);
	$pdf->Cell(117, 24, '', 1, 0, 'C');
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->Cell(17, 4, $TC030, '0', 'L');
	$pdf->SetXY($MARGINE_SINISTRO, ($_y + 4));
	if ($pagina == $_pg)
	{
	    $_annot = $dati['note'];
	}
	$pdf->MultiCell(115, 4, $_annot, 0, 'L');

	$pdf->SetXY(124, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', $datidoc[ST_FONTESTASIZE]);
	$pdf->Cell(40, 6, $TC041, 1, 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	if ($pagina == $_pg)
	{
	    $_nettov = $_nettovendita;
	}
	$pdf->Cell(37, 6, $_nettov, 1, 1, 'R');

	//recupero il castelletto dell'iva passando come parametro anche le coordinate sul dove costruirlo
	$_x_iva = "124";
	$_y_iva = $_y + 6;
	castello_iva_pdf($_ivadiversa, $_castiva, $pagina, $_pg, $_x_iva, $_y_iva, $datidoc, $LINGUA, $desciva, $dati);

	$_y = $_y + 24;

	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(40, 4, $TC002, '0', 0, 'C');
	$pdf->Cell(50, 4, $TC006, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC005, '0', 0, 'C');

	//variabili
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	// inizio eliminazione per segue pagina
	if ($pagina == $_pg)
	{
	    $_causale = $_causale;
	}
	$pdf->Cell(40, 10, $_causale, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_vettore = $dati['vettore'];
	}
	$pdf->Cell(50, 10, $_vettore, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_porto = $_porto;
	}
	$pdf->Cell(32, 10, $_porto, 1, 0, 'C');


	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', $datidoc[ST_FONTESTASIZE]);
	$pdf->Cell(35, 10, $TC040, 1, 0, 'L');
	if ($pagina == $_pg)
	{
	    $_totdoc = $dati['totdoc'];
	}
	else
	{
	    $_totdoc = "$TC043 $_pg+1";
	}
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'B', $datidoc[ST_FONTESTASIZE]);
	$pdf->Cell(37, 10, $_totdoc, 1, 1, 'R');

	$_y = $_y + 10;

	// intestazione campi
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'I', 7);
	$pdf->Cell(76, 4, $TC024, '0', 0, 'L');
	$pdf->Cell(20, 4, $TC025, '0', 0, 'C');
	$pdf->Cell(21, 4, $TC026, '0', 0, 'C');
	$pdf->Cell(32, 4, $TC027, '0', 0, 'C');
	$pdf->Cell(23, 4, $TC028, '0', 0, 'C');
	$pdf->Cell(20, 4, $TC029, '0', 0, 'C');
	//variabili
	$pdf->SetXY($MARGINE_SINISTRO, $_y); //256
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], '', $datidoc[ST_FONTESTASIZE]);
	// inizio eliminazione per segue pagina
	if ($pagina == $_pg)
	{
	    $_aspetto = $_look;
	}
	$pdf->Cell(76, 10, $_aspetto, 1, 0, 'L');

	if ($pagina == $_pg)
	{
	    $_colli = $dati['colli'];
	}
	$pdf->Cell(20, 10, $_colli, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_pesotot = $dati['pesotot'];
	}
	$pdf->Cell(21, 10, $_pesotot, 1, 0, 'C');

	if ($pagina == $_pg)
	{
	    $_tran = $dati['trasporto'];
	}
	$pdf->Cell(32, 10, $_tran, 1, 0, 'C');

	if ($pagina != $_pg)
	{
	    $ST_TIME = "";
	    $ST_DATE = "";
	}
	$pdf->Cell(23, 10, $ST_DATA, 1, 0, 'C');

	$pdf->Cell(22, 10, $ST_TIME, 1, 1, 'C', 0);
	// chiusura finale le sue firme..
	$_y = $_y + 10;
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'IB', 7);
	$pdf->Cell(96, 4, $TC031, '0', 0, 'L');
	$pdf->SetFont($datidoc[ST_FONTESTACALCE], 'IB', 7);
	$pdf->Cell(98, 4, $TC032, '0', 0, 'L');
	$pdf->SetXY($MARGINE_SINISTRO, $_y);
	$pdf->Cell(96, 10, '', '1', 0, 'L');
	$pdf->Cell(98, 10, '', '1', 1, 'L');
    }//fine calce tipo 4

    if ($datidoc[ST_TIPOCALCE] == "5")
    {
        $pdf->Cell(200, 8, "Q.ta Iniziale = $_nettovendita[iniziale] / acquistata = $_nettovendita[acquisto] / Venduta = $_nettovendita[venduta] / Valore tot. = ".number_format(($_nettovendita[netto]), 2, '.', ''), 1, 1, 'L');
    }
    else
    {
        if ($_pagamento == "OMAGGIO CON RIVALSA IVA")
        {
            $_condi = "OMAGGIO CON RIVALSA IVA TOTALE DA PAGARE euro 0.00</b>";
        }
        else
        {
            if ($CGV == "SI")
            {
                $_condi = "$TC050 $sitointernet $TC051 $fax";
            }
        }
    }
    

    $pdf->SetX($MARGINE_SINISTRO);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(196, 3, $_condi, '0', 'J');
    $pdf->SetX($MARGINE_SINISTRO);
    $pdf->Cell(190, 2, 'Powered by AguaGest - http://aguagest.sourceforge.net/', '0', '1', 'R');
}

//fine funzione calce doc html
?>