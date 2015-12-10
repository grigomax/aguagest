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

    //qui parte l'avventura del sig. buonaventura...
    //includo file per generazione pdf
    define('FPDF_FONTPATH','../../tools/fpdf/font/');
    require('../../tools/fpdf/fpdf.php');

    //selezioniamo il libro
    if($_POST['libro'] == "IA")
    {
        $_title = "REGISTRO IVA ACQUISTI";
    }
    elseif($_POST['libro'] == "IV")
    {
        $_title = "REGISTRO IVA VENDITE";
    }
    else
    {
        $_title = "LIBRO GIORNALE";
    }



//inizio generazione file in formato pdf..
    //richiamo le funzioni che sono all'intrerno del file stampe_doc_pdf.inc.php'
    // setto le variabili standard creazione pdf
 
    $pdf=new FPDF('P','mm','A4');
    $pdf->SetAutoPageBreak('off', 5);
    $pdf->SetTitle($_title);
    $pdf->SetCreator('Gestionale AGUA GEST - aguagest.sourceforge.net');
    $pdf->SetAuthor($azienda);

    $_end = $_POST['pagine'] + $_POST['numero_start'];


    for ($_pg = $_POST['numero_start']; $_pg <= $_end; $_pg++)
    {
        // utility per inserire la pagina o creare la pagina.
	$pdf->AddPage();

        $pdf->SetXY(5,5);
	    $pdf->SetFont('Arial','B',10);
	    $pdf->SetX(5);
	    $pdf->Cell(80,5,$azienda,0,1,'L');
	    $pdf->SetFont('Arial','',9);
	    $pdf->SetX(5);
	    $pdf->Cell(80,4,$indirizzo,0,1,'L');
	    $pdf->SetX(5);
	    $pdf->Cell(11,4,$cap,0,0,'L');
	    $pdf->Cell(50,4,$citta,0,0,'L');
	    $pdf->Cell(10,4,$prov,0,1,'L');
	    $pdf->SetX(5);
	    $pdf->Cell(40,4,"P.I. ".$piva,0,0,'L');
	    $pdf->Cell(40,4,"C.F. ".$codfisc,0,1,'L');

	    //provo a lasciare il puntatore
	    $pdf->SetXY(87,5);
            $pdf->SetFont('Arial','B',10);
	    $pdf->Cell(75,5,$_title,0,0,'L');
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,5,'Pag.',0,0,'L');
	    $pdf->Cell(10,5,$_POST['anno'],0,0,'C');
            $pdf->Cell(3,5,'/',0,0,'C');
            $pdf->Cell(15,5,$_pg,0,1,'L');


        //echo "cua";

        


    }

    //generazione del files..
    $_pdf = "$_title.pdf";
    $pdf->Output("../../../spool/$_pdf",'D');


?>