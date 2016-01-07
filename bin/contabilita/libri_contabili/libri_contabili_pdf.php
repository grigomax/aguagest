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
require_once $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
if($conn == "")
{
    $conn = permessi_sessione("verifica", $_percorso);
}

function new_pdf($_cosa, $_title)
{
    global $pdf;
    global $azienda;

    if ($_cosa == "L")
    {
        $pdf = new FPDF('L', 'mm', 'A4');
    }
    else
    {
        $pdf = new FPDF('P', 'mm', 'A4');
    }
    $pdf->SetAutoPageBreak('off', 5);
    $pdf->SetTitle($_title);
    $pdf->SetCreator('Gestionale AGUA GEST - aguagest.sourceforge.net');
    $pdf->SetAuthor($azienda);
}

//facciamo delle funzioni da richiamare per la stampa del pdf.
function intesta_colonna($_cosa)
{
    global $pdf;
    global $dare;
    global $avere;

// utility per inserire la pagina o creare la pagina.
    $pdf->AddPage();

    if ($_cosa == "inizio")
    {
//qui inseriamo tutte le informazioni relative alla colonna
        $pdf->SetXY(5, 30);
        $pdf->SetFont('Arial', 'B', 10);
        //$pdf->SetX(10);
        $pdf->Cell(11, 5, 'Prog.', 1, 0, 'R');
        $pdf->Cell(20, 5, 'Data', 1, 0, 'L');
        $pdf->Cell(20, 5, 'Conto', 1, 0, 'L');
        $pdf->Cell(10, 5, 'Rigo', 1, 0, 'C');
        $pdf->Cell(80, 5, 'Descrizione', 1, 0, 'L');
        $pdf->Cell(25, 5, 'Dare', 1, 0, 'C');
        $pdf->Cell(25, 5, 'Avere', 1, 1, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->Cell(11, 5, '', 0, 0, 'R');
        $pdf->Cell(50, 5, '', 0, 0, 'L');
        $pdf->Cell(80, 5, 'Riporto Esercizio Precedente      ' . $_anno, 0, 0, 'L');
        $pdf->Cell(25, 5, $dare, 0, 0, 'C');
        $pdf->Cell(25, 5, $avere, 0, 1, 'C');
    }
    elseif ($_cosa == "inizio_acquisti")
    {
//qui inseriamo tutte le informazioni relative alla colonna
        $pdf->SetXY(5, 30);
        $pdf->SetFont('Arial', 'B', 10);
        //$pdf->SetX(10);
        $pdf->Cell(9, 5, 'Prot.', 1, 0, 'R');
        $pdf->Cell(19, 5, 'Data', 1, 0, 'C');
        $pdf->Cell(25, 5, 'N. Doc', 1, 0, 'L');
        $pdf->Cell(11, 5, 'Conto', 1, 0, 'C');
        $pdf->Cell(80, 5, 'Descrizione', 1, 0, 'L');
        $pdf->Cell(20, 5, 'Tot. Fatt.', 1, 0, 'C');
        $pdf->Cell(20, 5, 'Imp.', 1, 0, 'C');
        $pdf->Cell(10, 5, 'Iva', 1, 1, 'C');
//$pdf->Cell(10, 5, 'Val.', 1, 1, 'C');
    }
    elseif ($_cosa == "inizio_vendite")
    {
//qui inseriamo tutte le informazioni relative alla colonna
        $pdf->SetXY(5, 30);
        $pdf->SetFont('Arial', 'B', 10);
        //$pdf->SetX(10);
#$pdf->Cell(9, 5, 'Prot.', 1, 0, 'R');
        $pdf->Cell(19, 5, 'Data', 1, 0, 'C');
        $pdf->Cell(25, 5, 'N. Doc', 1, 0, 'L');
        $pdf->Cell(11, 5, 'Conto', 1, 0, 'C');
        $pdf->Cell(80, 5, 'Descrizione', 1, 0, 'L');
        $pdf->Cell(20, 5, 'Tot. Fatt.', 1, 0, 'C');
        $pdf->Cell(20, 5, 'Imp.', 1, 0, 'C');
        $pdf->Cell(10, 5, 'Iva', 1, 1, 'C');
//$pdf->Cell(10, 5, 'Val.', 1, 1, 'C');
    }
    else
    {
//qui inseriamo tutte le informazioni relative alla colonna
        $pdf->SetXY(5, 30);
        $pdf->SetFont('Arial', 'B', 9);
        //$pdf->SetX(10);
        $pdf->Cell(11, 5, 'Prog.', 1, 0, 'R');
        $pdf->Cell(20, 5, 'Data', 1, 0, 'L');
        $pdf->Cell(20, 5, 'Conto', 1, 0, 'L');
        $pdf->Cell(10, 5, 'Rigo', 1, 0, 'C');
        $pdf->Cell(80, 5, 'Descrizione', 1, 0, 'L');
        $pdf->Cell(25, 5, 'Dare', 1, 0, 'C');
        $pdf->Cell(25, 5, 'Avere', 1, 1, 'C');
    }
}

function registrazione($_cosa, $dare, $avere)
{
    global $pdf;
    global $dati;
    global $_prog;
    global $dare;
    global $avere;


    if ($_cosa == "prima")
    {
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(5);
        $pdf->Cell(11, 4, $_prog, 0, 0, 'R');
        $pdf->Cell(20, 4, $dati['data_vista'], 0, 0, 'C');
//$pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(30, 4, 'Causale ', 0, 0, 'C');
        $pdf->Cell(80, 4, $dati['descrizione'], 0, 0, 'L');
        $pdf->Cell(25, 4, '', 0, 0, 'C');
        $pdf->Cell(25, 4, '', 0, 1, 'C');
    }
    else
    {
        $dare = $dare + $dati['dare'];
        $avere = $avere + $dati['avere'];

        if ($dati['dare'] == "0.00")
        {
            $dati['dare'] = "";
        }

        if ($dati['avere'] == "0.00")
        {
            $dati['avere'] = "";
        }
//$pdf->SetXY(10, 30);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(5);
        $pdf->Cell(11, 4, '', 0, 0, 'R');
        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(20, 4, $dati['conto'], 0, 0, 'L');
        $pdf->Cell(10, 4, $dati['rigo'], 0, 0, 'C');
        $pdf->Cell(80, 4, $dati['desc_conto'], 0, 0, 'L');
        $pdf->Cell(25, 4, $dati['dare'], 0, 0, 'R');
        $pdf->Cell(25, 4, $dati['avere'], 0, 1, 'R');
    }

//$sbilancio = $dare - $avere;
//echo "dare = $dare Avere = $avere  reg. nr $_nreg sbilancio $sbilancio<br>\n";
}

function elenco_liquid($_cosa, $dati, $_chiudi)
{
    global $pdf;
    global $castiva;
    global $CONTO_IVA_ACQUISTI;
    global $CONTO_IVA_VENDITE;

    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(5);

    if ($dati['rigo'] == "1")
    {
        if ($_cosa == "vendite")
        {
            if ($dati['avere'] == "0.00")
            {
                $dati['avere'] = "";
                $_fattura = $dati['dare'];
                $castiva['totfatt'] = $castiva['totfatt'] + $dati['dare'];
            }
            else
            {
                $dati['dare'] = "";
                $castiva['totfatt'] = $castiva['totfatt'] - $dati['avere'];
                $_fattura = -$dati['avere'];
            }
        }
        else
        {
            if ($dati['dare'] == "0.00")
            {
                $dati['dare'] = "";
                $_fattura = $dati['avere'];
                $castiva['totfatt'] = $castiva['totfatt'] + $dati['avere'];
            }
            else
            {
                $dati['avere'] = "";
                $castiva['totfatt'] = $castiva['totfatt'] - $dati['dare'];
                $_fattura = -$dati['dare'];
            }
            $pdf->Cell(9, 5, $dati['nproto'].'/'.$dati['suffix_proto'] , 0, 0, 'R');
        }

        $pdf->Cell(19, 5, $dati['data_cont_2'], 0, 0, 'C');
        $pdf->Cell(25, 5, $dati['ndoc'].'/'.$dati['suffix_doc'], 0, 0, 'L');
        $pdf->Cell(11, 5, $dati['conto'], 0, 0, 'C');
        $pdf->Cell(80, 5, $dati['desc_conto'], 0, 0, 'L');
        $pdf->Cell(20, 5, $_fattura, 0, 0, 'R');
        $pdf->Cell(20, 5, '', 0, 0, 'R');
        $pdf->Cell(10, 5, '', 0, 1, 'C');
//$pdf->Cell(10, 5, '', 1, $_chiudi, 'C');
    }
    else
    {
        if ($_cosa == "vendite")
        {
            if ($dati['dare'] == "0.00")
            {
                $dati['dare'] = "";
                $_imponibile = $dati['avere'];
                if ($dati['conto'] == $CONTO_IVA_VENDITE)
                {
                    $castiva[$dati[iva]][imposta] = $castiva[$dati[iva]][imposta] + $dati['avere'];
                }
                else
                {
                    $castiva[$dati[iva]][imponibile] = $castiva[$dati[iva]][imponibile] + $dati['avere'];
                }
            }
            else
            {
                $dati['avere'] = "";
                if ($dati['conto'] == $CONTO_IVA_VENDITE)
                {
                    $castiva[$dati[iva]][imposta] = $castiva[$dati[iva]][imposta] - $dati['dare'];
                }
                else
                {
                    $castiva[$dati[iva]][imponibile] = $castiva[$dati[iva]][imponibile] - $dati['dare'];
                }
                $_imponibile = -$dati['dare'];
            }
        }
        else
        {
            if ($dati['avere'] == "0.00")
            {
                $dati['avere'] = "";
                $_imponibile = $dati['dare'];
                if ($dati['conto'] == $CONTO_IVA_ACQUISTI)
                {
                    $castiva[$dati[iva]][imposta] = $castiva[$dati[iva]][imposta] + $dati['dare'];
                }
                else
                {
                    $castiva[$dati[iva]][imponibile] = $castiva[$dati[iva]][imponibile] + $dati['dare'];
                }
            }
            else
            {
                $dati['dare'] = "";
                if ($dati['conto'] == $CONTO_IVA_ACQUISTI)
                {
                    $castiva[$dati[iva]][imposta] = $castiva[$dati[iva]][imposta] - $dati['avere'];
                }
                else
                {
                    $castiva[$dati[iva]][imponibile] = $castiva[$dati[iva]][imponibile] - $dati['avere'];
                }
                $_imponibile = -$dati['avere'];
            }
            $pdf->Cell(9, 5, $dati['nproto'].'/'.$dati['suffix_proto'], 0, 0, 'R');
        }


        $pdf->Cell(19, 5, $dati['data_cont_2'], 0, 0, 'C');
        $pdf->Cell(25, 5, $dati['ndoc'].'/'.$dati['suffix_doc'], 0, 0, 'L');
        $pdf->Cell(11, 5, $dati['conto'], 0, 0, 'C');
        $pdf->Cell(80, 5, $dati['desc_conto'], 0, 0, 'L');
        $pdf->Cell(20, 5, '', 0, 0, 'R');
        $pdf->Cell(20, 5, $_imponibile, 0, 0, 'R');
        $pdf->Cell(10, 5, $dati['iva'], 0, 1, 'C');
//$pdf->Cell(10, 5, '', 1, $_chiudi, 'C');
    }
}

function chiusura($_cosa, $dare, $avere, $_anno)
{
    global $pdf;
    global $castiva;
    global $conn;

    if ($_cosa == "liquid")
    {

//leggiamo le aliquote dall'archivio..
        $iva_res = tabella_aliquota("elenca", $_codiva, $_percorso);

//qui inseriamo tutte le informazioni relative alla colonna
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetX(10);
        $pdf->Cell(189, 5, '', 'T', 1, 'L');
        $pdf->SetX(10);
        $pdf->Cell(189, 5, 'Totale fatture Periodo' . $_periodo . ' ' . $castiva['totfatt'], 1, 1, 'C');

        $pdf->SetX(10);
        $pdf->Cell(15, 5, 'Cod. Iva', 1, 0, 'L');
        $pdf->Cell(80, 5, 'Descrizione', 1, 0, 'L');
        $pdf->Cell(10, 5, 'Al.', 1, 0, 'R');
        $pdf->Cell(20, 5, 'Imponibile', 1, 0, 'R');
        $pdf->Cell(20, 5, 'Imposta', 1, 1, 'R');
//con un ciclo di while creiamo una tabellina..

        $iva_res = tabella_aliquota("elenca", $_codiva, $_percorso);
        foreach ($iva_res AS $iva)
        {
            if ($castiva[$iva[codice]][imponibile] != 0.00)
            {
                if ($iva['colonnacli'] != "1")
                {
                    $pdf->SetX(10);
                    $pdf->Cell(15, 5, $iva['codice'], 1, 0, 'L');
                    $pdf->Cell(80, 5, $iva['descrizione'], 1, 0, 'L');
                    $pdf->Cell(10, 5, $iva['aliquota'], 1, 0, 'R');
                    $pdf->Cell(20, 5, $castiva[$iva[codice]][imponibile], 1, 0, 'R');
                    $pdf->Cell(20, 5, $castiva[$iva[codice]][imposta], 1, 1, 'R');
                    $_imponibile = $_imponibile + $castiva[$iva[codice]][imponibile];
                    $_imposte = $_imposte + $castiva[$iva[codice]][imposta];
                }
                else
                {

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->SetX(10);
                    $pdf->Cell(15, 5, $iva['codice'], 1, 0, 'L');
                    $pdf->Cell(80, 5, $iva['descrizione'], 1, 0, 'L');
                    $pdf->Cell(10, 5, $iva['aliquota'], 1, 0, 'R');
                    $pdf->Cell(20, 5, $castiva[$iva[codice]][imponibile], 1, 0, 'R');
                    //$pdf->Cell(20, 5, $castiva[''][imponibile], 1, 1, 'R');
                    $pdf->Cell(20, 5, '', 1, 1, 'R');
                }
            }
        }

        //fine tabella
        $pdf->SetX(10);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(15, 5, '', 1, 0, 'L');
        $pdf->Cell(80, 5, 'Totale', 1, 0, 'L');
        $pdf->Cell(10, 5, '', 1, 0, 'R');
        $pdf->Cell(20, 5, $_imponibile, 1, 0, 'R');
        $pdf->Cell(20, 5, $_imposte, 1, 1, 'R');
    }
    else
    {

//qui inseriamo tutte le informazioni relative alla colonna
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(10);
        $pdf->Cell(61, 5, '', 1, 0, 'R');
        $pdf->Cell(80, 5, 'Totali Progressivi Esercizio   ' . $_anno, 1, 0, 'L');
        $pdf->Cell(25, 5, $dare, 1, 0, 'R');
        $pdf->Cell(25, 5, $avere, 1, 1, 'R');
    }
}

function sbarra()
{
    global $pdf;
    $pdf->SetX(10);
    $pdf->Cell(30, 3, '**********************', 0, 1, 'R');
    $pdf->SetX(10);
    $pdf->Cell(55, 3, '**********************', 0, 1, 'R');
    $pdf->SetX(10);
    $pdf->Cell(80, 3, '**********************', 0, 1, 'R');
    $pdf->SetX(10);
    $pdf->Cell(105, 3, '**********************', 0, 1, 'R');
    $pdf->SetX(10);
    $pdf->Cell(130, 3, '**********************', 0, 1, 'R');
    $pdf->SetX(10);
    $pdf->Cell(150, 3, '**********************', 0, 1, 'R');
    $pdf->SetX(10);
    $pdf->Cell(175, 3, '**********************', 0, 1, 'R');
    $pdf->SetX(10);
    $pdf->Cell(195, 3, '**********************', 0, 1, 'R');
}

function liquidazione($_datanuova, $_anno, $_periodo, $dati_acq, $dati_ven, $dati_liquid, $_per_vc)
{
    global $pdf;
    global $castiva;
    global $_iva_acquisti;
    global $conn;


    //andiamo su una pagina nuova..
    $pdf->AddPage();

    $pdf->SetXY(10, 30);

    //facciamo una cella larga quanto la pagina con l'intestazione
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(195, 3, 'Liquidazione IVA periodica Mese di ' . $_datanuova[$_periodo] . ' Anno ' . $_anno, 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $_y = $pdf->GetY();
    $_y = $_y + 10;
    $pdf->SetXY(10, $_y);

    $pdf->SetX(10);
    $pdf->Cell(110, 5, 'IVA VENDITE', 0, 1, 'L');
    $pdf->SetX(10);
    $pdf->Cell(15, 5, 'Cod. Iva', 1, 0, 'L');
    $pdf->Cell(80, 5, 'Descrizione', 1, 0, 'L');
    $pdf->Cell(10, 5, 'Al.', 1, 0, 'R');
    $pdf->Cell(20, 5, 'Imponibile', 1, 0, 'R');
    $pdf->Cell(20, 5, 'Imposta', 1, 1, 'R');
//con un ciclo di while creiamo una tabellina..
    $pdf->SetX(10);
    $pdf->Cell(15, 5, '', 1, 0, 'L');
    $pdf->Cell(80, 5, '', 1, 0, 'L');
    $pdf->Cell(10, 5, '', 1, 0, 'R');
    $pdf->Cell(20, 5, $castiva[''][imponibile], 1, 0, 'R');
    $pdf->Cell(20, 5, '', 1, 1, 'R');
    $iva_res = tabella_aliquota("elenca", $_codiva, $_percorso);
    foreach ($iva_res AS $iva)
    {
        if ($castiva[$iva[codice]][imponibile] != 0.00)
        {
            $pdf->SetX(10);
            $pdf->Cell(15, 5, $iva['codice'], 1, 0, 'L');
            $pdf->Cell(80, 5, $iva['descrizione'], 1, 0, 'L');
            $pdf->Cell(10, 5, $iva['aliquota'], 1, 0, 'R');
            $pdf->Cell(20, 5, $castiva[$iva[codice]][imponibile], 1, 0, 'R');
            $pdf->Cell(20, 5, $castiva[$iva[codice]][imposta], 1, 1, 'R');
            $_imponibile = $_imponibile + $castiva[$iva[codice]][imponibile];
            $_imposte_vend = $_imposte_vend + $castiva[$iva[codice]][imposta];
        }
    }

    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(15, 5, '', 1, 0, 'L');
    $pdf->Cell(80, 5, 'Totale', 1, 0, 'L');
    $pdf->Cell(10, 5, '', 1, 0, 'R');
    $pdf->Cell(20, 5, $_imponibile, 1, 0, 'R');
    $pdf->Cell(20, 5, $_imposte_vend, 1, 1, 'R');
    $pdf->SetFont('Arial', '', 10);

    $_imponibile = "";
    $_imposte = "";


    $_y = $pdf->GetY();
    $_y = $_y + 10;
    $pdf->SetXY(10, $_y);

    $pdf->SetX(10);
    $pdf->Cell(110, 5, 'IVA ACQUISTI', 0, 1, 'L');
    $pdf->SetX(10);
    $pdf->Cell(15, 5, 'Cod. Iva', 1, 0, 'L');
    $pdf->Cell(80, 5, 'Descrizione', 1, 0, 'L');
    $pdf->Cell(10, 5, 'Al.', 1, 0, 'R');
    $pdf->Cell(20, 5, 'Imponibile', 1, 0, 'R');
    $pdf->Cell(20, 5, 'Imposta', 1, 1, 'R');
//con un ciclo di while creiamo una tabellina..

    $iva_res = tabella_aliquota("elenca", $_codiva, $_percorso);
    foreach ($iva_res AS $iva)
    {
        if ($_iva_acquisti[$iva[codice]][imponibile] != 0.00)
        {
            if ($iva['colonnacli'] != "1")
            {
                $pdf->SetX(10);
                $pdf->Cell(15, 5, $iva['codice'], 1, 0, 'L');
                $pdf->Cell(80, 5, $iva['descrizione'], 1, 0, 'L');
                $pdf->Cell(10, 5, $iva['aliquota'], 1, 0, 'R');
                $pdf->Cell(20, 5, $_iva_acquisti[$iva[codice]][imponibile], 1, 0, 'R');
                $pdf->Cell(20, 5, $_iva_acquisti[$iva[codice]][imposta], 1, 1, 'R');
                $_imponibile = $_imponibile + $_iva_acquisti[$iva[codice]][imponibile];
                $_imposte = $_imposte + $_iva_acquisti[$iva[codice]][imposta];
            }
            else
            {
                $_indetraibile = $_indetraibile + $_iva_acquisti[$iva[codice]][imponibile];
            }
        }
    }

    //stampiamo il l'inderaibile
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetX(10);
    $pdf->Cell(15, 5, "", 1, 0, 'L');
    $pdf->Cell(80, 5, 'Non Detraibile', 1, 0, 'L');
    $pdf->Cell(10, 5, "", 1, 0, 'R');
    #$pdf->Cell(20, 5, $_iva_acquisti[$_array_iva[codice]][imponibile], 1, 0, 'R');
    //$pdf->Cell(20, 5, $_iva_acquisti[''][imponibile], 1, 1, 'R');
    #$pdf->Cell(20, 5,'', 1, 1, 'R');
    $pdf->Cell(20, 5, $_indetraibile, 1, 0, 'R');
    $pdf->Cell(20, 5, $_iva_acquisti[$_array_iva[codice]][imponibile], 1, 1, 'R');



    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(15, 5, '', 1, 0, 'L');
    $pdf->Cell(80, 5, 'Totale', 1, 0, 'L');
    $pdf->Cell(10, 5, '', 1, 0, 'R');
    $pdf->Cell(20, 5, $_imponibile, 1, 0, 'R');
    $pdf->Cell(20, 5, $_imposte, 1, 1, 'R');



    //riportiamo tutti i dati relativi alla liquidazione
    $_y = $pdf->GetY();
    $_y = $_y + 10;
    $pdf->SetXY(10, $_y);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(195, 3, 'Liquidazione Iva:', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);

    $_y = $pdf->GetY();
    $_y = $_y + 10;
    $pdf->SetXY(40, $_y);
    $pdf->Cell(90, 8, 'Totale Iva vendite per il mese di ' . $_datanuova[$_GET[periodo]] . ' = ', 0, 0, 'L');
    $pdf->Cell(100, 8, $_imposte_vend, 0, 1, 'L');
    $pdf->SetX(40);
    $pdf->Cell(90, 8, 'Totale Iva acquisti per il mese di ' . $_datanuova[$_GET[periodo]] . ' = ', 0, 0, 'L');
    $pdf->Cell(60, 8, $_imposte, 0, 1, 'L');
    $pdf->SetX(40);
    $pdf->Cell(90, 8, 'Iva Credito mesi precedente = ', 0, 0, 'L');
    $pdf->Cell(60, 8, $dati_liquid[cred_residuo], 0, 1, 'L');


    //prendiamo i dati relativi all'anticipo versato che corrispondono al mese numero 13
    if ($_periodo == "12")
    {
        //andiamoci a prendere l'acconto relativo all'anno già versato.

        $query = "SELECT * FROM liquid_iva_periodica where periodo = '13' and anno = '$_anno' AND versato = 'SI' limit 1";

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        $dati_acc = $result->fetch(PDO::FETCH_ASSOC);
        
        
        if($dati_acc['val_liquid'] == null)
        {
            $dati_acc['val_liquid'] = "0.00";
        }
        
        $pdf->SetX(40);
        $pdf->Cell(90, 8, 'Acconto iva mese di Dicembre = ', 0, 0, 'L');
        $pdf->Cell(60, 8, $dati_acc[val_liquid], 0, 1, 'L');
        
    }

    //vuol dire che è una ristampa
    if (($dati_ven['vendite'] == "") AND ($dati_acq['acquisti'] == ""))
    {
        $_differenza = $_imposte_vend - $_imposte;
        $_per_vc = $_per_vc + 2;
        if ($_differenza < "0.00")
        {
            $pdf->SetX(40);
            $pdf->Cell(90, 8, 'Totale Iva a credito = ', 0, 0, 'L');
            $pdf->Cell(60, 8, $_differenza, 0, 1, 'L');
        }
        else
        {
            $pdf->SetX(40);
            $pdf->Cell(90, 3, 'Totale iva a Debito ' . $_datanuova[$_per_vc] . ' = ', 0, 0, 'L');
            $pdf->Cell(60, 3, $_differenza, 0, 1, 'L');
        }
    }
    else
    {
        if ($dati_liquid['diff_periodo'] < "0.00")
        {
            $_differenza = ($dati_ven['vendite'] - $dati_acq['acquisti'] - $dati_acc['val_liquid'] - abs($dati_liquid['cred_residuo']));
        }
        else
        {
            $_differenza = ($dati_ven['vendite'] - $dati_acq['acquisti'] - $dati_acc['val_liquid'] - abs($dati_liquid['cred_residuo']));
        }

        
        
        $_per_vc = $_per_vc + 2;

        $pdf->SetFont('Arial', 'B', 12);

        if ($_differenza < "0.00")
        {
            $pdf->SetX(40);
            $pdf->Cell(90, 8, 'Totale Iva a credito = ', 0, 0, 'L');
            $pdf->Cell(60, 8, $_differenza, 0, 1, 'L');
        }
        else
        {
            $pdf->SetX(40);
            $pdf->Cell(90, 3, 'Totale iva da versare entro il 16 ' . $_datanuova[$_per_vc] . ' = ', 0, 0, 'L');
            $pdf->Cell(60, 3, $_differenza, 0, 1, 'L');
        }
    }

    $pdf->SetFont('Arial', '', 10);
}

?>