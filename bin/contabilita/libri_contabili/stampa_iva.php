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

require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_primanota.php";
require $_percorso . "librerie/motore_anagrafiche.php";
#libreria funzioni che generano il pdf ..
//qui parte l'avventura del sig. buonaventura...
//includo file per generazione pdf
define('FPDF_FONTPATH', '../../tools/fpdf/font/');
require('../../tools/fpdf/fpdf.php');

require "libri_contabili_pdf.php";

if ($_SESSION['user']['contabilita'] > "1")
{


    $_periodo = $_GET['anno'] . "-" . $_GET['periodo'];
//mi prendo l'elenco dei mesi..
    $_datanuova = cambio_data("listamesi", $_data);
    $_azione = $_GET['azione'];
    $_datareg = cambio_data("us", $_GET['datareg']);
    $_anno_reg = cambio_data("anno_it", $_GET['datareg']);


//due domande o ona.. ?
//megio due..
//acquisti
    $query = "select *, (SUM(dare) - SUM(avere)) AS acquisti from prima_nota where liquid_iva != 'SI' AND conto='$CONTO_IVA_ACQUISTI' and data_cont like '$_periodo%'";

//eseguiamo la query
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

    $dati_acq = $result->fetch(PDO::FETCH_ASSOC);
   

//vendite
    $query = "select *, (SUM(avere) - SUM(dare)) AS vendite from prima_nota where liquid_iva != 'SI' AND conto='$CONTO_IVA_VENDITE' and data_cont like '$_periodo%'";
//echo "<br> $query";
//eseguiamo la query

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
    $dati_ven = $result->fetch(PDO::FETCH_ASSOC);
   

//selezioniamo i mesi..
    if ($_GET['periodo'] == "01")
    {
        $_per_vc = "12";
        $query = "SELECT * FROM liquid_iva_periodica where periodo = '12' and anno = '$_GET[anno]'-1 limit 1";
    }
    else
    {
        $_per_vc = $_GET['periodo'] - 1;
        $query = "SELECT * FROM liquid_iva_periodica where periodo = '$_GET[periodo]' -1 and anno = '$_GET[anno]' limit 1";
    }

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

    $dati_liquid = $result->fetch(PDO::FETCH_ASSOC);

    if ($_azione == "conto")
    {
        //carichiamo la base delle pagine:
        base_html_stampa("chiudi", $_parametri);
        //inizio parte visiva...
        echo "<CENTER>";


        echo "<h4> Valori iva Riportati..</h4>\n";
        echo "<h4> <input type=\"radio\" name=\"anno\" value=\"$_GET[anno]\" checked>anno = $_GET[anno] mese = <input type=\"radio\" name=\"periodo\" value=\"$_GET[periodo]\" checked>$_GET[periodo]</h4>\n";
        echo "Iva Credito mesi precedenti = $dati_liquid[cred_residuo]<br>";
        echo "Iva vendite per il mese di " . $_datanuova[$_GET[periodo]] . "= $dati_ven[vendite]<br>";
        echo "Iva acquisti per il mese di " . $_datanuova[$_GET[periodo]] . " = $dati_acq[acquisti]<br>";
        if ($dati_liquid['diff_periodo'] < "0.00")
        {
            echo "Eventuale Iva a credito $_datanuova[$_per_vc] = $dati_liquid[diff_periodo]<br>";
        }

        if ($dati_liquid['diff_periodo'] < "0.00")
        {
            $_differenza = ($dati_ven['vendite'] - $dati_acq['acquisti'] - abs($dati_liquid['cred_residuo']));
        }
        else
        {
            $_differenza = ($dati_ven['vendite'] - $dati_acq['acquisti'] - abs($dati_liquid['cred_residuo']));
        }

        if ($_POST['periodo'] == "12")
        {
            //andiamoci a prendere l'acconto relativo all'anno già versato.

            $query = "SELECT * FROM liquid_iva_periodica where periodo = '13' and anno = '$_POST[anno]' AND versato = 'SI' limit 1";

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

            echo "Acconto iva versato =  $dati_acc[val_liquid]<br>\n";
        }
        #		$_differenza = ($dati_ven['vendite'] - $dati_acq['acquisti'] - abs($dati_liquid['cred_residuo']));

        $_differenza = $_differenza - $dati_acc['val_liquid'];


        $_per_vc = $_per_vc + 2;

        if ($_differenza < "0.00")
        {
            $_differenza = "0.00";
        }
        echo "<b><font color=\"red\">Iva da versare entro il 16 $_datanuova[$_per_vc] = $_differenza</font></b>\n";
        echo "<br><br><b><font color=\"red\">Codice tributo erario 60$_GET[periodo]</font></b>\n";

        if ($_GET['periodo'] == "12")
        {
            //andiamoci a prendere l'acconto relativo all'anno già versato.

            $query = "SELECT * FROM liquid_iva_periodica where periodo = '13' and anno = '$_GET[anno]' AND versato = 'SI' limit 1";

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
            
            echo "<br><br>Acconto iva versato =  $dati_acc[val_liquid]<br>\n";
            $_differenza = $_differenza - $dati_acc['val_liquid'];
            if ($_differenza < "0.00")
            {
                $_differenza = "0.00";
            }
            echo "<b>Iva da versare entro il 16 $_datanuova[$_per_vc] = $_differenza</b></br>\n";
        }
    }
    else // camnhio funzione..
    {
        //echo "<center>\n";
        //echo "<h3> Preparazione file... periodo di $_periodo </h3>\n";

        $_title = "Liquidazione_iva_periodo_$_periodo";

        //creiamo un nuovo pdf..
        new_pdf("", $_title);

        // per poter procedere a stampare l'iva.. dobbiamo selezionare tutte le vendite e gli acquisti..
        //Prtiamo con gli acquisti..
        //selezioniamo dal database i muovimeni inerenti..
        $query = "select *, date_format(data_cont,'%d-%m-%Y') AS data_cont_2 from prima_nota where causale = 'FA' AND data_cont like '$_periodo%' order by suffix_proto, nproto, data_cont, rigo";

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

        //$acquisti = mysql_fetch_array($result);

        intesta_colonna("inizio_acquisti");

        foreach ($result AS $dati)
        {
            $_y = $pdf->GetY();

            if ($_y >= "280")
            {
                intesta_colonna("inizio_acquisti");
            }


            if ($dati['nreg'] != $_nreg)
            {
                $pdf->SetX(5);
                $pdf->Cell(191, 1, '', 'T', 1, '');
            }

            elenco_liquid($_cosa, $dati, "1");

            $_nreg = $dati['nreg'];
        }

        $_y = $_y + 5;
        $pdf->SetXY(5, $_y);

        $_y = $pdf->GetY();

        if ($_y >= "250")
        {
            sbarra();
            $pdf->AddPage();
        }

        chiusura("liquid", $dare, $avere, $_anno);

        if ($_y >= "280")
        {
            intesta_colonna("");
        }
        sbarra();

        //iniziamo con il registro vendite...
        //passiamo la variabile castiva ad altro nome per poterla recuperare dopo.. !
        $_iva_acquisti = $castiva;
        //azzeriamo l'iva
        $castiva = "";

        // per poter procedere a stampare l'iva.. dobbiamo selezionare tutte le vendite e gli acquisti..
        //Prtiamo con gli acquisti..
        //selezioniamo dal database i muovimeni inerenti..
        $query = "select *, date_format(data_cont,'%d-%m-%Y') AS data_cont_2 from prima_nota where causale = 'FV' AND data_cont like '$_periodo%' order by suffix_doc, data_doc, CAST( ndoc AS SIGNED ) ASC";

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

        //$acquisti = mysql_fetch_array($result);

        intesta_colonna("inizio_vendite");

        foreach ($result AS $dati)
        {
            $_y = $pdf->GetY();

            if ($_y >= "280")
            {
                intesta_colonna("inizio_vendite");
            }


            if ($dati['nreg'] != $_nreg)
            {
                $pdf->SetX(5);
                $pdf->Cell(191, 1, '', 'T', 1, '');
            }

            elenco_liquid("vendite", $dati, "1");

            $_nreg = $dati['nreg'];
        }

        $_y = $_y + 5;
        $pdf->SetXY(5, $_y);

        $_y = $pdf->GetY();

        if ($_y >= "280")
        {
            intesta_colonna("inizio_vendite");
        }

        chiusura("liquid", $dare, $avere, $_anno);

        if ($_y >= "280")
        {
            intesta_colonna("");
        }
        sbarra();

        // Ora che abbiamo tutto.. facciamo il riassunto con la liquidazione..

        liquidazione($_datanuova, $_GET['anno'], $_GET['periodo'], $dati_acq, $dati_ven, $dati_liquid, $_per_vc);


        //generazione del files..
        $_pdf = "$_title.pdf";
        $pdf->Output("../../../spool/$_pdf", 'I');

        //echo "<br>\n";
        //echo "<h4 align=\"center\"><a href=\"../../../spool/$_pdf\">Clicca qui per prelevare il file in pdf con il libro IVA</a></h4>";
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>