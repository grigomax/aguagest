<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require_once $_percorso . "../setting/vars.php";
ini_set('session.gc_maxlifetime', $SESSIONTIME);
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require_once $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

define('FPDF_FONTPATH', $_percorso . "tools/fpdf/font/");
require_once $_percorso . "tools/fpdf/fpdf.php";

class RPDF extends FPDF
{

    function TextWithDirection($x, $y, $txt, $direction = 'R')
    {
        if ($direction == 'R')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 1, 0, 0, 1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        elseif ($direction == 'L')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', -1, 0, 0, -1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        elseif ($direction == 'U')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, 1, -1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        elseif ($direction == 'D')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, -1, 1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        else
            $s = sprintf('BT %.2F %.2F Td (%s) Tj ET', $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        if ($this->ColorFlag)
            $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
        $this->_out($s);
    }

    function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle = 0)
    {
        $font_angle+=90 + $txt_angle;
        $txt_angle*=M_PI / 180;
        $font_angle*=M_PI / 180;

        $txt_dx = cos($txt_angle);
        $txt_dy = sin($txt_angle);
        $font_dx = cos($font_angle);
        $font_dy = sin($font_angle);

        $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', $txt_dx, $txt_dy, $font_dx, $font_dy, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        if ($this->ColorFlag)
            $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
        $this->_out($s);
    }

}

$_catmerindice = $_catmer;
$_listinoindice = $_listino;
// variabili di impaginazione..
//grandezza immagini articoli
$_H_immagine = "40";
$_H_cella_immagine = $_H_immagine + 3;
$_H_cella = "10";

//spazio tra gli articoli
$_spazio_art = "5";
$_spazio_centr_arti = $_spazio_art + "2";
//spessore barra intestazione pagina
$_H_barra_int = "10";
$_barra_font = "20";

$data = date("d - m - Y");

//variabili comuni..
$MARGINE_SINISTRO = "13";
$FONT_INTESTACELLE = "Arial";
$FONTSIZE_INTESTACELLE = "10";
$LARG_CODICE = "15";
$LARG_DESCRIZIONE = "141";
$LARG_UM = "7";
$LARG_LISTINO = "18";
$FONT_RIGACELLE = "Arial";
$FONTSIZE_RIGACELLE = "8";
$ALT_RIGHE_CORPO = "5";
$MARGINE_INFERIORE = "287";
$LARGHEZZA_PAGINA = $LARG_CODICE + $LARG_DESCRIZIONE + $LARG_LISTINO + $LARG_UM;


// DA QUI INIZIO IL LISTINO ARTICOLI

$query = sprintf("select articolo, descrizione, catmer, tipart, unita, listino, immagine from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where (%s) AND pubblica='SI' and rigo=\"%s\" order by catmer, tipart, immagine, descrizione", $_catmer, $_listino);

// esuguo la query
$result = $conn->query($query);
if ($conn->errorCode() != "00000")
{
    $_errore = $conn->errorInfo();
    echo $_errore['2'];
    //aggiungiamo la gestione scitta dell'errore..
    $_errori['descrizione'] = "Errore Query prima = $query - $_errore[2]";
    $_errori['files'] = "listino_figurato_2.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
}

// variabili varie
$_immagine1 = "inizio";
$_intesta1 = "inizio";
$_intesta = "si";
$_catmer1 = "inizio";

function crea_pdf()
{
    global $pdf;
    global $_percorso;
    require $_percorso . "../setting/vars.php";
    global $_doppia;

    // setto le variabili standard creazione pdf
    $_title = "Listino Prezzi Figurato";
    if ($_doppia == "SI")
    {
        $pdf = new RPDF('L', 'mm', 'A3');
    }
    else
    {
        $pdf = new RPDF('P', 'mm', 'A4');
    }

    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak('Off', 2);
    $pdf->SetTitle($_title);
    $pdf->SetAuthor($azienda);
    $pdf->SetCreator('Agua Gest - FPDF');
    $pdf->SetSubject($_nomelist);
}

//elenco funzioni..
function aggiungi_pagina()
{
    global $pdf;
    global $numero;
    global $LARGHEZZA_PAGINA;
    global $MARGINE_SINISTRO;
    global $_percorso;
    global $logom;
    global $_doppia;


    if ($_doppia == "SI")
    {
        crea_pdf();
    }

    $numero = $numero + 1;

    $pdf->AddPage();
    // inserisco l'immagine con l'intestazione
    $pdf->Image($_percorso . "../setting/loghiazienda/" . $logom, $MARGINE_SINISTRO, 8, $LARGHEZZA_PAGINA, 20);
    if ($_doppia == "SI")
    {
        $pdf->Image($_percorso . "../setting/loghiazienda/" . $logom, $MARGINE_SINISTRO + 210, 8, $LARGHEZZA_PAGINA, 20);
    }


    // righe inserimento intestazione listino
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetXY($MARGINE_SINISTRO, 30);
}

function pagina_articolo($_aggarticolo, $_articolo, $_pagina)
{
    //funzione che mi permette di aggiornare gli articoli nelle pagine di dove sono
    global $conn;
    global $pdf;

    if ($_aggarticolo == "SI")
    {
        //aggiorniamo la pagina

        $query = "UPDATE articoli SET pagcat = '$_pagina' where articolo = '$_articolo'";

        $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query aggiornamto = $query - $_errore[2]";
            $_errori['files'] = "listino figurao2.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
    }
}

/**
 * La funzione mi permette di creare i file per la tipografia per poter stampare un doppio a4 per poi 
 * unirlo in A3
 * @global type $pdf
 * @global type $numero
 * @global type $_doppia
 * @global string $_percorso
 * @param type $_cosa 
 */
function salva_pagina($_cosa)
{
    //se aggiungiamo una pagina vuo dire che sicuramente ne abbiamo chiusa una..

    global $pdf;
    global $numero;
    global $_doppia;
    global $_percorso;

    $_numero = str_pad($numero, 4, '0', STR_PAD_LEFT);

    if ($_doppia == "SI")
    {
        if ($_cosa == "intesta")
        {
            $pdf->Output($_percorso . "../spool/listino/indi$_numero.pdf", F);
            #copy($_percorso . "../spool/listino/indi$_numero.pdf", $_percorso . "../spool/listino/Indi$_numero.pdf");
        }
        else
        {
            $pdf->Output($_percorso . "../spool/listino/List$_numero.pdf", F);
            #copy($_percorso . "../spool/listino/List$_numero.pdf", $_percorso . "../spool/listino/list$_numero.pdf");
        }
    }
    else
    {
        $pdf->Output($_percorso . "../spool/listino/Listino_figurato.pdf", F);
    }
}

function indice_intesta($_cosa)
{
    global $pdf;
    global $LARGHEZZA_PAGINA;
    global $MARGINE_SINISTRO;
    global $_doppia;

    if ($_cosa == "alfa")
    {
        $pdf->SetXY($MARGINE_SINISTRO, 27);
        $pdf->Cell($LARGHEZZA_PAGINA, 10, 'Indice Alfabetico', 0, 1, 'C');
        $pdf->SetXY(20, 40);
        $pdf->SetFont('Arial', 'B', 12);

        #azzardiamo un indice
        $pdf->Cell(110, 6, 'Categoria', 1, 0, 'L');
        $pdf->Cell(50, 6, 'Da pagina', 1, 1, 'C');

        if ($_doppia == "SI")
        {
            $pdf->SetXY($MARGINE_SINISTRO + 210, 27);
            $pdf->Cell($LARGHEZZA_PAGINA, 10, 'Indice Alfabetico', 0, 1, 'C');
            $pdf->SetXY(20 + 210, 40);
            $pdf->SetFont('Arial', 'B', 12);

            #azzardiamo un indice
            $pdf->Cell(110, 6, 'Categoria', 1, 0, 'L');
            $pdf->Cell(50, 6, 'Da pagina', 1, 1, 'C');
        }
    }
    else
    {
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY($MARGINE_SINISTRO, 28);
        $pdf->Cell($LARGHEZZA_PAGINA, 10, 'Indice Fotografico', 0, 1, 'C');
        $pdf->SetXY($MARGINE_SINISTRO, 40);

        if ($_doppia == "SI")
        {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetXY($MARGINE_SINISTRO + 210, 28);
            $pdf->Cell($LARGHEZZA_PAGINA, 10, 'Indice Fotografico', 0, 1, 'C');
            $pdf->SetXY($MARGINE_SINISTRO + 210, 40);
        }
    }
}

function titolo($_y, $_barra_font, $_H_barra_int, $_catmer)
{
    global $pdf;
    global $LARGHEZZA_PAGINA;
    global $_doppia;

    #$pdf->SetXY(13, $_y);
    #$pdf->SetFillColor('210');
    $pdf->SetFont('Arial', 'BI', $_barra_font);
    $pdf->TextWithDirection(3, 80, $_catmer, 'D');
    $pdf->TextWithDirection(205, 220, $_catmer, 'U');

    //facciamo la seconda pagina
    if ($_doppia == "SI")
    {
        $pdf->SetFont('Arial', 'BI', $_barra_font);
        $pdf->TextWithDirection(213, 80, $_catmer, 'D');
        $pdf->TextWithDirection(415, 220, $_catmer, 'U');
    }

    #$pdf->Cell($LARGHEZZA_PAGINA, $_H_barra_int, $_catmer, '0', '1', 'C', '1');
    //$pdf->SetFont('Arial', 'B', 14);
}

function intesta_celle()
{
    global $pdf;
    global $MARGINE_SINISTRO;
    global $FONT_INTESTACELLE;
    global $FONTSIZE_INTESTACELLE;
    global $LARG_CODICE;
    global $LARG_DESCRIZIONE;
    global $LARG_UM;
    global $LARG_LISTINO;
    global $_doppia;

    $pdf->SetFillColor('210');
    $pdf->SetX($MARGINE_SINISTRO);
    $pdf->SetFont($FONT_INTESTACELLE, 'B', $FONTSIZE_INTESTACELLE);
    $pdf->Cell($LARG_CODICE, 5, 'Codice', '1', 0, 'C', 1);
    $pdf->Cell($LARG_DESCRIZIONE, 5, 'Descrizione', '1', 0, 'L', 1);
    $pdf->Cell($LARG_UM, 5, 'Um', '1', 0, 'C', 1);
    $pdf->Cell($LARG_LISTINO, 5, 'Listino', '1', 1, 'R', 1);

    if ($_doppia == "SI")
    {
        $_Y = $pdf->GetY();
        $pdf->SetY($_Y - 5);
        $pdf->SetFillColor('210');
        $pdf->SetX($MARGINE_SINISTRO + 210);
        $pdf->SetFont($FONT_INTESTACELLE, 'B', $FONTSIZE_INTESTACELLE);
        $pdf->Cell($LARG_CODICE, 5, 'Codice', '1', 0, 'C', 1);
        $pdf->Cell($LARG_DESCRIZIONE, 5, 'Descrizione', '1', 0, 'L', 1);
        $pdf->Cell($LARG_UM, 5, 'Um', '1', 0, 'C', 1);
        $pdf->Cell($LARG_LISTINO, 5, 'Listino', '1', 1, 'R', 1);
    }
}

function riga_dati($_colore, $dati3, $_listino)
{


    //funzione che mi espone i dati..

    global $pdf;
    global $MARGINE_SINISTRO;
    global $FONT_RIGACELLE;
    global $FONTSIZE_RIGACELLE;
    global $LARG_CODICE;
    global $LARG_DESCRIZIONE;
    global $LARG_UM;
    global $LARG_LISTINO;
    global $ALT_RIGHE_CORPO;
    global $_doppia;

    if ($_colore == "c")
    {
        //$pdf->SetFillColor('210');
        $pdf->SetX($MARGINE_SINISTRO);
        $pdf->SetFont($FONT_RIGACELLE, '', $FONTSIZE_RIGACELLE);
        $pdf->Cell($LARG_CODICE, $ALT_RIGHE_CORPO, $dati3['articolo'], 'L', 0, 'C');
        $pdf->Cell($LARG_DESCRIZIONE, $ALT_RIGHE_CORPO, $dati3['descrizione'], 'L', 0, 'L');
        $pdf->Cell($LARG_UM, $ALT_RIGHE_CORPO, $dati3['unita'], 'L', 0, 'C');
        $pdf->Cell($LARG_LISTINO, $ALT_RIGHE_CORPO, $_listino, 'LR', 1, 'R');

        if ($_doppia == "SI")
        {
            $_Y = $pdf->GetY();
            $pdf->SetY($_Y - $ALT_RIGHE_CORPO);
            $pdf->SetX($MARGINE_SINISTRO + 210);
            $pdf->SetFont($FONT_RIGACELLE, '', $FONTSIZE_RIGACELLE);
            $pdf->Cell($LARG_CODICE, $ALT_RIGHE_CORPO, $dati3['articolo'], 'L', 0, 'C');
            $pdf->Cell($LARG_DESCRIZIONE, $ALT_RIGHE_CORPO, $dati3['descrizione'], 'L', 0, 'L');
            $pdf->Cell($LARG_UM, $ALT_RIGHE_CORPO, $dati3['unita'], 'L', 0, 'C');
            $pdf->Cell($LARG_LISTINO, $ALT_RIGHE_CORPO, $_listino, 'LR', 1, 'R');
        }
    }
    else
    {
        $pdf->SetFillColor('230');
        //$pdf->SetFillColor('210');
        $pdf->SetX($MARGINE_SINISTRO);
        $pdf->SetFont($FONT_RIGACELLE, '', $FONTSIZE_RIGACELLE);
        $pdf->Cell($LARG_CODICE, $ALT_RIGHE_CORPO, $dati3['articolo'], 'L', 0, 'C', 1);
        $pdf->Cell($LARG_DESCRIZIONE, $ALT_RIGHE_CORPO, $dati3['descrizione'], 'L', 0, 'L', 1);
        $pdf->Cell($LARG_UM, $ALT_RIGHE_CORPO, $dati3['unita'], 'L', 0, 'C', 1);
        $pdf->Cell($LARG_LISTINO, $ALT_RIGHE_CORPO, $_listino, 'LR', 1, 'R', 1);

        if ($_doppia == "SI")
        {
            $pdf->SetFillColor('230');
            $_Y = $pdf->GetY();
            $pdf->SetY($_Y - $ALT_RIGHE_CORPO);
            $pdf->SetX($MARGINE_SINISTRO + 210);
            $pdf->SetFont($FONT_RIGACELLE, '', $FONTSIZE_RIGACELLE);
            $pdf->Cell($LARG_CODICE, $ALT_RIGHE_CORPO, $dati3['articolo'], 'L', 0, 'C', 1);
            $pdf->Cell($LARG_DESCRIZIONE, $ALT_RIGHE_CORPO, $dati3['descrizione'], 'L', 0, 'L', 1);
            $pdf->Cell($LARG_UM, $ALT_RIGHE_CORPO, $dati3['unita'], 'L', 0, 'C', 1);
            $pdf->Cell($LARG_LISTINO, $ALT_RIGHE_CORPO, $_listino, 'LR', 1, 'R', 1);
        }
    }
}

function pie_pagina($numero, $data)
{
    global $pdf;
    global $LARGHEZZA_PAGINA;
    global $MARGINE_SINISTRO;
    global $MARGINE_INFERIORE;
    global $_doppia;

    $pdf->SetY($MARGINE_INFERIORE);
    #$_pag = $pdf->PageNo();
    //$_pagine = $pdf->AliasNbPages(nb);
    $pdf->SetX(7);
    #$pdf->Cell($LARGHEZZA_PAGINA, 4, '', '0', 1, 'C');
    //$pdf->SetXY(10,280);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(40, 4, $numero, '1', 0, 'L');
    $_vale = "Data emissione listino figurato $data ";
    $pdf->Cell(113, 4, $_vale, '1', 0, 'C');
    $pdf->Cell(40, 4, $numero, '1', 0, 'R');

    if ($_doppia == "SI")
    {
        $pdf->SetY($MARGINE_INFERIORE);
        $pdf->SetX(7 + 210);
        #$pdf->Cell($LARGHEZZA_PAGINA, 4, '', '0', '1', 'C');
        //$pdf->SetXY(10,280);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(40, 4, $numero, '1', 0, 'L');
        $_vale = "Data emissione listino figurato $data ";
        $pdf->Cell(113, 4, $_vale, '1', 0, 'C');
        $pdf->Cell(40, 4, $numero, '1', 0, 'R');
    }
}

//Parto con la prima pagina

crea_pdf();


// inizio listino vecchio.

foreach ($result AS $dati3)
{
    $_y = 0;
    $_immagine = $dati3['immagine'];
    $_listino = $dati3['listino'];
    $_tipart = $dati3['tipart'];
    $_catmer = $dati3['catmer'];
    if ($_listino == "0.00" or null)
    {
        $_listino = "a richiesta";
    }

    #inziamo con il creare la pagina
    //se la categoria merceologica è diversa iniziamo una nova pagina


    if (($_catmer != $_catmer1) AND ( $_catmer1 != "inizio"))
    {

        #prima di aggiungere una pagina bisogna chiudere la precendente.
        pie_pagina($numero, $data);

        if ($_doppia == "SI")
        {
            salva_pagina($_cosa);
        }
        #poi apriamo un'latra'
        //se diversa pagina nuova..
        aggiungi_pagina();
        //prova
        $_y = $pdf->GetY();
        if ($_y <= 50)
        {
            $_y = $_y + 5;
        }
        else
        {
            $_y = $_y + 25;
        }

        titolo($_y, $_barra_font, $_H_barra_int, $_catmer);


        $_posizione = "prima";
    }


    //funzione che mi crea l'intestazione

    if ($_intesta1 == "inizio")
    {
        $_immagine1 = $_immagine;
        $_intesta1 = "";
    }

    if ($_intesta == "si")
    {

        aggiungi_pagina();

        $_y = $pdf->GetY();
        if ($_y <= 50)
        {
            $_y = $_y + 5;
        }
        else
        {
            $_y = $_y + 25;
        }


        titolo($_y, $_barra_font, $_H_barra_int, $_catmer);

        $pdf->SetFont('Arial', 'BI', 14);
        $_posizione = "prima";
        //fine prova
        //riportiamo l'immagine
        if ($_immagine == $_immagine1)
        //  		if($_immagine != "")
        {
            $_y = $pdf->GetY();
            $_y = $_y + 2;
            $_y_img = $_y + 10;
            if ($_immagine != "")
            {
                $pdf->SetX($MARGINE_SINISTRO);
                $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella, $_tipart, '0', 1, 'L');
                if ($_doppia == "SI")
                {
                    $_Y = $pdf->GetY();
                    $pdf->SetY($_Y - $_H_cella);

                    $pdf->SetX($MARGINE_SINISTRO + 210);
                    $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella, $_tipart, '0', 1, 'L');
                }
                // devo verificare se la tipologia articolo è gia stata letta per le pagine
                $_ver = $merda[$_catmer][$_tipart];
                if ($_ver == "")
                {
                    $_pagi = $numero;
                    $merda[$_catmer][$_tipart] = $_pagi;
                    $cazzo[$_catmer][$_tipart] = $_immagine;
                }
                // scommentare per impaginare meglio
                $pdf->SetX($MARGINE_SINISTRO);
                $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella_immagine, '', '0', '1', 'L');
                #$pdf->Cell(115, $_H_cella, '', '0', 1, 'C');
                $_pathimg = $_percorso . "../imm-art/$_immagine";
                $pdf->Image($_pathimg, 50, $_y_img, 0, $_H_immagine);

                if ($_doppia == "SI")
                {
                    $_Y = $pdf->GetY();
                    $pdf->SetY($_Y - $_H_cella_immagine);

                    // scommentare per impaginare meglio
                    $pdf->SetX($MARGINE_SINISTRO + 210);
                    $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella_immagine, '', '0', '1', 'L');
                    #$pdf->Cell(115, $_H_cella, '', '0', 1, 'C');
                    $_pathimg = $_percorso . "../imm-art/$_immagine";
                    $pdf->Image($_pathimg, 50 + 210, $_y_img, 0, $_H_immagine);
                }



                intesta_celle();
                $_colore = "c";
            }
            //costrutione tabella base
        }
        $_tipart2 = $_tipart;
        //aggiorniamo l'articolo
        pagina_articolo($_aggarticolo, $dati3['articolo'], $numero);
    }

    //estraggo i dati
    if (($_immagine == $_immagine1) and ( $_tipart2 == $_tipart))
    {
        if ($_colore == "s")
        {
            riga_dati($_colore, $dati3, $_listino);
            $_colore = "c";
            $_immagine1 = $_immagine;
            $_intesta = "no";
        }
        else
        {
            $_colore = "c";
            riga_dati($_colore, $dati3, $_listino);
            $_colore = "s";
            $_immagine1 = $_immagine;
            $_intesta = "no";
        }
        $_tipart2 = $_tipart;
        pagina_articolo($_aggarticolo, $dati3['articolo'], $numero);
    }
    else
    {
        //qui dobbiamo creare una pagina nuova
        // se l'immagine è a fondo pagina bisogan andate in un'altrapagina
        //prendo la posizione
        $_y = $pdf->GetY();
        if ($_y >= 226)
        {
            //chiudo la pagina
            pie_pagina($numero, $data);

            if ($_doppia == "SI")
            {
                salva_pagina($_cosa);
            }
            aggiungi_pagina();


            titolo($_y, $_barra_font, $_H_barra_int, $_catmer);

            $pdf->SetFont('Arial', 'B', 14);
            //inserisco l'articolo letto
            // mi riprendo la posizione
            $_y = $pdf->GetY();
            #$_y = $_y + $_spazio_centr_arti;
            #$_y_img = $_y + 10;

            $_y = $_y + 2;
            $_y_img = $_y + 10;

            if ($_immagine != "")
            {
                // spazio tra articoli
                #$pdf->SetX($MARGINE_SINISTRO);
                #$pdf->Cell($LARGHEZZA_PAGINA, $_spazio_art, 'spazio', '1', 1, 'L');
                $pdf->SetFont('Arial', 'BI', 14);
                $pdf->SetX($MARGINE_SINISTRO);
                $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella, $_tipart, 0, 1, 'L');

                if ($_doppia == "SI")
                {
                    $_Y = $pdf->GetY();
                    $pdf->SetY($_Y - $_H_cella);

                    $pdf->SetX($MARGINE_SINISTRO + 210);
                    $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella, $_tipart, '0', 1, 'L');
                }

                // devo verificare se la tipologia articolo è gia stata letta per le pagine
                $_ver = $merda[$_catmer][$_tipart];
                if ($_ver == "")
                {
                    $_pagi = $numero;
                    $merda[$_catmer][$_tipart] = $_pagi;
                    $cazzo[$_catmer][$_tipart] = $_immagine;
                }
                // scommentare per impaginare meglio
                $pdf->SetX($MARGINE_SINISTRO);
                $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella_immagine, '', '0', '1', 'L');
                #$pdf->Cell(115, $_H_cella, '', '0', 1, 'C');
                $_pathimg = $_percorso . "../imm-art/$_immagine";
                $pdf->Image($_pathimg, 50, $_y_img, 0, $_H_immagine);

                if ($_doppia == "SI")
                {
                    $_Y = $pdf->GetY();
                    $pdf->SetY($_Y - $_H_cella_immagine);

                    // scommentare per impaginare meglio
                    $pdf->SetX($MARGINE_SINISTRO + 210);
                    $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella_immagine, '', '0', '1', 'L');
                    #$pdf->Cell(115, $_H_cella, '', '0', 1, 'C');
                    $_pathimg = $_percorso . "../imm-art/$_immagine";
                    $pdf->Image($_pathimg, 50 + 210, $_y_img, 0, $_H_immagine);
                }
            }
            // inizio inserimento corpo documento
            if ($_intesta != "si")
            {
                intesta_celle();
                $pdf->SetX($MARGINE_SINISTRO);
                ;
            }

            $_colore = "c";
            riga_dati($_colore, $dati3, $_listino);
            $_colore = "s";

            $_immagine1 = $_immagine;
            $_intesta = "no";
            $_tipart2 = $_tipart;
            //aggiorniamo l'articolo

            pagina_articolo($_aggarticolo, $dati3['articolo'], $numero);
        }
        else
        {
            // vuoldire che l'immagine con un articolo ci sta
            // tre quarti del listino e fatta qua
            $_y2 = $_y + $_spazio_centr_arti;



            if ($_immagine != "")
            {
                if ($_tipart2 == $_tipart)
                {
                    $_tipart = "";
                    $_H_cella_pos_3 = "5";
                    $_y_img = $_y2 + 3;
                }
                else
                {
                    $_tipart2 = $_tipart;
                    $_H_cella_pos_3 = $_H_cella;
                    $_y_img = $_y2 + 10;
                }

                $pdf->SetX($MARGINE_SINISTRO);
                $pdf->Cell($LARGHEZZA_PAGINA, $_spazio_art, '', 'T', 1, 'L');
                $pdf->SetFont('Arial', 'BI', 14);
                $pdf->SetX($MARGINE_SINISTRO);
                $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella_pos_3, $_tipart, 0, 1, 'L');

                if ($_doppia == "SI")
                {
                    $_Y = $pdf->GetY();
                    $pdf->SetY($_Y - $_spazio_art - $_H_cella_pos_3);
                    $pdf->SetX($MARGINE_SINISTRO+210);
                    $pdf->Cell($LARGHEZZA_PAGINA, $_spazio_art, '', 'T', 1, 'L');
                    //$_Y = $pdf->GetY();
                    //$pdf->SetY($_Y - $_H_cella_pos_3);
                    $pdf->SetFont('Arial', 'BI', 14);
                    $pdf->SetX($MARGINE_SINISTRO + 210);
                    $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella_pos_3, $_tipart, '0', 1, 'L');
                }

                // devo verificare se la tipologia articolo è gia stata letta per le pagine
                $_ver = $merda[$_catmer][$_tipart];
                if ($_ver == "")
                {
                    $_pagi = $numero;
                    $merda[$_catmer][$_tipart] = $_pagi;
                    $cazzo[$_catmer][$_tipart] = $_immagine;
                }
                // scommentare per impaginare meglio
                $pdf->SetX($MARGINE_SINISTRO);
                $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella_immagine, '', '0', '1', 'L');
                #$pdf->Cell(115, $_H_cella, '', '0', 1, 'C');
                $_pathimg = $_percorso . "../imm-art/$_immagine";
                $pdf->Image($_pathimg, 50, $_y_img, 0, $_H_immagine);

                if ($_doppia == "SI")
                {
                    $_Y = $pdf->GetY();
                    $pdf->SetY($_Y - $_H_cella_immagine);

                    // scommentare per impaginare meglio
                    $pdf->SetX($MARGINE_SINISTRO + 210);
                    $pdf->Cell($LARGHEZZA_PAGINA, $_H_cella_immagine, '', '0', '1', 'L');
                    #$pdf->Cell(115, $_H_cella, '', '0', 1, 'C');
                    $_pathimg = $_percorso . "../imm-art/$_immagine";
                    $pdf->Image($_pathimg, 50 + 210, $_y_img, 0, $_H_immagine);
                }



                $pagina['$_tipart'] = $numero;
            }
            // inizio inserimento corpo documento
            // 			if ($_intesta != "si")
            // 				{
            intesta_celle();

            $pdf->SetX($MARGINE_SINISTRO);
            // 				}
            $_colore = "c";
            riga_dati($_colore, $dati3, $_listino);

            $_colore = "s";
            $_immagine1 = $_immagine;
            $_intesta = "no";
            //aggiorniamo l'articolo

            pagina_articolo($_aggarticolo, $dati3['articolo'], $numero);
        }
    }
    //costruzione del fine pagine
    //Vediamo dove siamo
    $_y = $pdf->GetY();

    if ($_y >= 280)
    {
        pie_pagina($numero, $data);

        //setto l'intestazione
        $_intesta = "si";
        if ($_doppia == "SI")
        {
            salva_pagina($_cosa);
        }
    }
    $_catmer1 = $_catmer;
}
// incaso che la pagina finisse prima mettiamo un piè di pagian in più.

$_y = $pdf->GetY();

if ($_y <= 280)
{
    pie_pagina($numero, $data);

    //setto l'intestazione
    $_intesta = "si";
    if ($_doppia == "SI")
    {
        salva_pagina($_cosa);
    }
}

// fine listino articoli..
#Vediamo se serve una pagina in pià
if ($_aggpagina == "SI")
{
    if ($_doppia == "SI")
    {
        salva_pagina($_cosa);
    }

    aggiungi_pagina();
}



// bISOGNA INSERIRE UNA PAGINA BIANCA CHE MI PERMETTE DI FARE L'INDICE A PARTE..
//aggiungiamo una pagina
if ($_doppia == "SI")
{
    salva_pagina($_cosa);
}
aggiungi_pagina();


// inizio indice
// settiamo le variabili globali..
// grandezza immagine indice
$_imgx = 35;
$_imgy = 35;
$data = date('d/m/Y');

$catmer = "";
$valore = "";
$tipart = "";
$pagina = "";
$_posizione = "1";
reset($merda);

// bISOGNA INSERIRE UNA PAGINA BIANCA CHE MI PERMETTE DI FARE L'INDICE A PARTE..
//aggiungiamo una pagina
#salva_pagina("intesta");
#aggiungi_pagina();
//intestiamo l'indice
indice_intesta("foto");

while (@list($catmer, $valore) = each($merda))
{
    $_posizione = 1;
    if ($catmer != "")
    {
        //costruzione del fine pagine
        //Vediamo dove siamo
        $_y = $pdf->GetY();

        if ($_y >= 240)
        {
            //se siamo over.. chiudo ed apro una nuova pagina.

            if ($_doppia == "SI")
            {
                salva_pagina("intesta");
            }
            aggiungi_pagina();
            indice_intesta("foto");
        }

        $pdf->SetX($MARGINE_SINISTRO);
        $pdf->SetFillColor('200');
        $pdf->SetFont('Arial', 'BI', 20);
        $pdf->SetTextColor('255');
        $pdf->Cell($LARGHEZZA_PAGINA, 10, $catmer, '0', '1', 'C', '1');
        $pdf->SetTextColor('0');

        if ($_doppia == "SI")
        {
            $_Y = $pdf->GetY();
            $pdf->SetY($_Y - 10);

            $pdf->SetX($MARGINE_SINISTRO + 210);
            $pdf->SetFillColor('200');
            $pdf->SetFont('Arial', 'BI', 20);
            $pdf->SetTextColor('255');
            $pdf->Cell($LARGHEZZA_PAGINA, 10, $catmer, '0', '1', 'C', '1');
            $pdf->SetTextColor('0');
        }





        while (@list($tipart, $pagina) = each($valore))
        {
            if ($tipart != "")
            {
                $_y = $pdf->GetY();

                if (($_y >= 240) AND ( $_posizione == "1"))
                {
                    //se siamo over.. chiudo ed apro una nuova pagina.
                    if ($_doppia == "SI")
                    {
                        salva_pagina("intesta");
                    }
                    aggiungi_pagina();
                    indice_intesta("foto");
                    $pdf->SetX($MARGINE_SINISTRO);
                    $pdf->SetFillColor('200');
                    $pdf->SetFont('Arial', 'BI', 20);
                    $pdf->SetTextColor('255');
                    $pdf->Cell($LARGHEZZA_PAGINA, 10, $catmer, '0', '1', 'C', '1');
                    $pdf->SetTextColor('0');

                    if ($_doppia == "SI")
                    {
                        $_Y = $pdf->GetY();
                        $pdf->SetY($_Y - 10);
                        indice_intesta("foto");
                        $pdf->SetX($MARGINE_SINISTRO + 210);
                        $pdf->SetFillColor('200');
                        $pdf->SetFont('Arial', 'BI', 20);
                        $pdf->SetTextColor('255');
                        $pdf->Cell($LARGHEZZA_PAGINA, 10, $catmer, '0', '1', 'C', '1');
                        $pdf->SetTextColor('0');
                    }
                }
                //iniziamo con il mostrare le foto..
                //Ora cambiamo le coordinate inbase se è la prima la seconda o la terza foto.
                if ($_posizione == "1")
                {
                    $_y = $pdf->GetY();
                    $_x = $MARGINE_SINISTRO;
                    $_y = $_y + 5;
                }
                elseif ($_posizione == "2")
                {
                    $_y = $pdf->GetY();
                    $_x = $_x + "65";
                    $_y = $_y - $_imgy - 15;
                }
                else
                {
                    $_y = $pdf->GetY();
                    $_x = $_x + "65";
                    $_y = $_y - $_imgy - 15;
                    $_posizione = "0";
                }

                $pdf->SetFont('Arial', '', 10);
                $_immagine = $cazzo[$catmer][$tipart];
                $_pathimg = $_percorso . "../imm-art/$_immagine";
                $pdf->Image($_pathimg, $_x, $_y, $_imgx, $_imgy);
                $_immagine = $dati1['immagine'];
                $_newy = $_y + $_imgy;
                $pdf->SetXY($_x, $_newy);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(64, 5, $tipart, '0', '1', 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetX($_x);
                $pdf->Cell(64, 5, 'Da pagina ' . $pagina, 'B', 0, 'L');
                $pdf->SetXY($_x, $_newy + 15);
                $_posizione = $_posizione + "1";

                if ($_doppia == "SI")
                {
                    $_Y = $pdf->GetY();
                    $pdf->SetY($_Y - 10);

                    $pdf->SetFont('Arial', '', 10);
                    $_immagine = $cazzo[$catmer][$tipart];
                    $_pathimg = $_percorso . "../imm-art/$_immagine";
                    $pdf->Image($_pathimg, $_x + 210, $_y, $_imgx, $_imgy);
                    $_immagine = $dati1['immagine'];
                    $_newy = $_y + $_imgy;
                    $pdf->SetXY($_x + 210, $_newy);
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(64, 5, $tipart, '0', '1', 'L');
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->SetX($_x + 210);
                    $pdf->Cell(64, 5, 'Da pagina ' . $pagina, 'B', 0, 'L');
                    $pdf->SetXY($_x + 210, $_newy + 15);
                }




//
            }
        }
    }
}

if ($_doppia == "SI")
{
    salva_pagina("intesta");
}
#salva_pagina("intesta");
aggiungi_pagina();

//intestiamo l'indice
indice_intesta("alfa");
reset($merda);

while (@list($catmer, $valore) = each($merda))
{
    if ($catmer != "")
    {
        //costruzione del fine pagine
        //Vediamo dove siamo
        $_y = $pdf->GetY();

        if ($_y >= 280)
        {
            if ($_doppia == "SI")
            {
                salva_pagina("intesta");
            }
            //se siamo over.. chiudo ed apro una nuova pagina.
            aggiungi_pagina();
            indice_intesta("alfa");
        }

        $pdf->SetX(20);
        $pdf->SetFont('Arial', 'BI', 12);
        $pdf->Cell(110, 6, $catmer, 0, 1, 'L');
        if ($_doppia == "SI")
        {
            $_Y = $pdf->GetY();
            $pdf->SetY($_Y - 6);
            $pdf->SetX(20 + 210);
            $pdf->SetFont('Arial', 'BI', 12);
            $pdf->Cell(110, 6, $catmer, 0, 1, 'L');
        }


        while (@list($tipart, $pagina) = each($valore))
        {
            if ($tipart != "")
            {
                $_y = $pdf->GetY();

                if ($_y >= 280)
                {
                    //se siamo over.. chiudo ed apro una nuova pagina.
                    if ($_doppia == "SI")
                    {
                        salva_pagina("intesta");
                    }
                    aggiungi_pagina();
                    indice_intesta("alfa");
                    $pdf->SetX(20);
                    $pdf->SetFont('Arial', 'BI', 12);
                    $pdf->Cell(110, 6, $catmer, 0, 1, 'L');

                    if ($_doppia == "SI")
                    {
                        $_Y = $pdf->GetY();
                        $pdf->SetY($_Y - 6);

                        // scommentare per impaginare meglio
                        indice_intesta("alfa");
                        $pdf->SetX(20 + 210);
                        $pdf->SetFont('Arial', 'BI', 12);
                        $pdf->Cell(110, 6, $catmer, 0, 1, 'L');
                    }
                }
                $pdf->SetX(20);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(110, 5, $tipart, 'B', 0, 'L');
                $pdf->Cell(50, 5, $pagina, 'B', 1, 'C');

                if ($_doppia == "SI")
                {
                    $_Y = $pdf->GetY();
                    $pdf->SetY($_Y - 5);

                    // scommentare per impaginare meglio

                    $pdf->SetX(20 + 210);
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Cell(110, 5, $tipart, 'B', 0, 'L');
                    $pdf->Cell(50, 5, $pagina, 'B', 1, 'C');
                }
            }
        }
    }
}

salva_pagina("intesta");
#aggiungi_pagina();
//inizio parte visiva del programma..
//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);
echo " LISTINO GENERATO GRAZIE<br>";
echo " Per prelevare il listino, vederlo o salvarlo in locale..";
echo "<a href=\"" . $_percorso . "../spool/listino/Listino_figurato.pdf\"> clicca Visualizzare il listino</a>";
echo "</body></html>";
?>