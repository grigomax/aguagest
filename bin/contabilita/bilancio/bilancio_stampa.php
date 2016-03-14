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
require $_percorso . "librerie/stampe_pdf.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


require "../../../setting/par_conta.inc.php";

//prendiamo i GET dalla pagina precedente..
if ($_SESSION['user']['contabilita'] > "1")
{
    $_start = substr($_GET['azione'], "0", "10");
    $_end = substr($_GET['azione'], "10", "10");

    crea_file_pdf($_cosa, $_orientamento, "bilancio");

    function testata($_titolo, $_prima, $_seconda)
    {
        global $pdf;
        $pdf->SetFillColor(0, 0, 255);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(10);
        $pdf->Cell(190, 8, $_titolo, 1, 1, 'C', true);
        $pdf->Cell(95, 7, mb_convert_encoding($_prima, "windows-1252", "UTF-8"), 1, 0, 'C', true);
        $pdf->Cell(95, 7, mb_convert_encoding($_seconda, "windows-1252", "UTF-8"), 1, 1, 'C', true);
        $pdf->SetX(10);
        //tablella dati
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 6, "Conto", 1, 0, 'C');
        $pdf->Cell(63, 6, "Descrizione", 1, 0, 'C');
        $pdf->Cell(15, 6, "Valore", 1, 0, 'C');
        $pdf->Cell(4, 6, "", 0, 0, 'C');
        $pdf->Cell(15, 6, "Conto", 1, 0, 'C');
        $pdf->Cell(63, 6, "Descrizione", 1, 0, 'C');
        $pdf->Cell(15, 6, "Valore", 1, 1, 'C');

    }

    function corpo($_cosa, $row, $row2, $_totale, $_primo, $_secondo)
    {
        global $pdf;
        global $MASTRO_CLI;
        global $MASTRO_FOR;
        global $clienti;
        global $fornitori;
        global $H_celle;

        $_Y = $pdf->GetY();

        if ($_cosa == "fornitori")
        {
            if ($fornitori['saldo'] > "0.00")
            {
                $_scritta_p = "D";
                $_totale[$_secondo] = $_totale[$_secondo] - abs($fornitori[saldo]);
            }
            else
            {
                $_scritta_p = "A";
                $_totale[$_secondo] = $_totale[$_secondo] + abs($fornitori[saldo]);
            }
            $pdf->SetXY(97 + 10, $_Y);
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(15, $H_celle, $MASTRO_FOR, 1, 0, 'L');
            $pdf->Cell(63, $H_celle, "Fornitori", 1, 0, 'L');
            $pdf->Cell(15, $H_celle, number_format(abs($fornitori[saldo]), '2', ',', '.') . " $_scritta_p", 1, 1, 'R');
            
        }
        elseif ($_cosa == "clienti")
        {
            if ($clienti['saldo'] > "0.00")
            {
                $_scritta_p = "D";
                $_totale[$_primo] = $_totale[$_primo] + abs($clienti[saldo]);
            }
            else
            {
                $_scritta_p = "A";
                $_totale[$_primo] = $_totale[$_primo] - abs($clienti[saldo]);
            }
            $pdf->SetXY(10, $_Y);
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(15, $H_celle, $MASTRO_CLI, 1, 0, 'L');
            $pdf->Cell(63, $H_celle, "Clienti", 1, 0, 'L');
            $pdf->Cell(15, $H_celle, number_format(abs($clienti[saldo]), '2', ',', '.') . " $_scritta_p", 1, 0, 'R');

        }
        else
        {
            $_Y = $pdf->GetY();
            if ($row)
            {
                $_scritta_p = "D";
                $pdf->SetFont('Arial', '', 6);
                $pdf->Cell(15, $H_celle, $row[conto], 1, 0, 'L');
                $pdf->Cell(63, $H_celle, mb_convert_encoding($row[desc_conto], "windows-1252", "UTF-8"), 1, 0, 'L');
                $pdf->Cell(15, $H_celle, number_format(abs($row[saldo]), '2', ',', '.') . " $_scritta_p", 1, 1, 'R');

                $_totale[$_primo] = $_totale[$_primo] + $row['saldo'];
            }
            else
            {
                $pdf->Cell(15, $H_celle, "", 1, 0, 'L');
                $pdf->Cell(63, $H_celle, "", 1, 0, 'L');
                $pdf->Cell(15, $H_celle, "", 1, 1, 'L');
                
            }

            $pdf->SetXY(97 + 10, $_Y);
            

            if ($row2)
            {
                $_scritta_p = "A";
                $pdf->SetFont('Arial', '', 6);
                $pdf->Cell(15, $H_celle, $row2[conto], 1, 0, 'L');
                $pdf->Cell(63, $H_celle, $row2[desc_conto], 1, 0, 'L');
                $pdf->Cell(15, $H_celle, number_format(abs($row2[saldo]), '2', ',', '.') . " $_scritta_p", 1, 1, 'R');
                $_totale[$_secondo] = $_totale[$_secondo] + abs($row2['saldo']);
            }
            else
            {
                $pdf->Cell(15, $H_celle, "", 1, 0, 'L');
                $pdf->Cell(63, $H_celle, "", 1, 0, 'L');
                $pdf->Cell(15, $H_celle, "", 1, 1, 'L');
            }
        }


        return $_totale;
    }

    function calce($_totale, $_primo, $_secondo)
    {
        global $pdf;
        $pdf->SetFillColor(0, 0, 255);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(10);
        $pdf->Cell(95, 7, "Totale $_primo = " . number_format(abs($_totale[$_primo]), '2', ',', '.'), 1, 0, 'C', true);
        $pdf->Cell(95, 7, "Totale $_secondo = " . number_format(abs($_totale[$_secondo]), '2', ',', '.'), 1, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 8);

    }

    $_righe = "55";
    $H_celle = "4";

    
#echo "<h4>La stampa in formato pdf verr&agrave; inviata in linea al Browser</h4>\n";
    /* devo fare un query per i clienti
     * una per i fornitori una per il resto in ordine di categoria..
     *
     */
//query per i clienti
    $query = "SELECT data_cont, desc_conto, conto, (SUM(dare) - SUM(avere)) AS saldo from prima_nota where data_cont >= '$_start' AND data_cont <= '$_end' AND conto LIKE '$MASTRO_CLI%' HAVING saldo != '0.00'";
    $clienti = domanda_db("query", $query, $_cosa, "fetch", $_parametri);

    $query = "SELECT data_cont, desc_conto, conto, (SUM(dare) - SUM(avere)) AS saldo from prima_nota where data_cont >= '$_start' AND data_cont <= '$_end' AND conto LIKE '$MASTRO_FOR%' HAVING saldo != '0.00'";
    $fornitori = domanda_db("query", $query, $_cosa, "fetch", $_parametri);



//$query = "SELECT data_cont, desc_conto, conto, (SUM(dare) - SUM(avere)) AS saldo from prima_nota where data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto ORDER BY conto";
//prima domanda solo per il conto patrimoniale..
    $query = "SELECT data_cont, desc_conto, conto, codconto, natcon, tipo_cf, (SUM( dare ) - SUM( avere ) ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto = piano_conti.codconto WHERE (natcon='A' OR natcon='P') AND data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto HAVING saldo != '0.00' AND saldo > '0.00'";
    $result = domanda_db("query", $query, $_cosa, "", $_parametri);

    $query = "SELECT data_cont, desc_conto, conto, codconto, natcon, tipo_cf, (SUM( dare ) - SUM( avere ) ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto = piano_conti.codconto WHERE (natcon='A' OR natcon='P') AND data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto HAVING saldo != '0.00' AND saldo < '0.00'";
    $result2 = domanda_db("query", $query, $_cosa, "", $_parametri);

    $quante = "1";

    for ($pagina = "1"; $pagina <= $quante; $pagina++)
    {
        crea_pagina_pdf();

        crea_intestazione_ditta_pdf("schede_contabili", "Bilancio al ".cambio_data("it", $_end), $_end, $pdf->PageNo(), cambio_data("anno_us", $_end), $_parametri);
        $pdf->SetXY(10, 30);

        testata("STATO PATRIMONIALE", "Attività", "Passività");

        if ($_apparizione != "SI")
        {
            //facciamo apparire i clienti
            $_totale = corpo("clienti", $row, $row2, $_totale, "attivita", "passivita");
            $_totale = corpo("fornitori", $row, $row2, $_totale, "attivita", "passivita");
            $_righe = $_righe - 1;
            $_apparizione = "SI";
        }


        for ($a = "0"; $a <= $_righe; $a++)
        {
            //foreach ($costi AS $row);

            $row = $result->fetch(PDO::FETCH_ASSOC);
            $row2 = $result2->fetch(PDO::FETCH_ASSOC);
            $_totale = corpo($_cosa, $row, $row2, $_totale, "attivita", "passivita");
        }

        if (($row) OR ( $row2))
        {
            $quante = "2";
        }


        calce($_totale, "attivita", "passivita");
    }


//chiudiamo la tabella e apriamo un'altra pagina'
//ora iniziamo con il conto economico...
//prima domanda solo per il conto economico
    $query = "SELECT data_cont, desc_conto, conto, codconto, natcon, tipo_cf, (SUM( dare ) - SUM( avere ) ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto = piano_conti.codconto WHERE (natcon='C' OR natcon='R') AND data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto HAVING saldo > '0.00'";
    $result = domanda_db("query", $query, $_cosa, "", $_parametri);

    $query = "SELECT data_cont, desc_conto, conto, codconto, natcon, tipo_cf, (SUM( dare ) - SUM( avere ) ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto = piano_conti.codconto WHERE (natcon='C' OR natcon='R') AND data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto HAVING saldo < '0.00'";
    $result2 = domanda_db("query", $query, $_cosa, "", $_parametri);


    $quante = "1";
    
    //Parte Patrimoniale

    for ($pagina = "1"; $pagina <= $quante; $pagina++)
    {

        crea_pagina_pdf();

        crea_intestazione_ditta_pdf("schede_contabili", "Bilancio al ".cambio_data("it", $_end), $_end, $pdf->PageNo(), cambio_data("anno_us", $_end), $_parametri);
        $pdf->SetXY(10, 30);



        testata("CONTO ECONOMICO", "Costi", "Ricavi");

        for ($a = "0"; $a <= $_righe; $a++)
        {
            //foreach ($costi AS $row);

            $row = $result->fetch(PDO::FETCH_ASSOC);
            $row2 = $result2->fetch(PDO::FETCH_ASSOC);
            $_totale = corpo($_cosa, $row, $row2, $_totale, "costi", "ricavi");
        }

        if (($row) OR ( $row2))
        {
            $quante = "2";
        }


        calce($_totale, "costi", "ricavi");


    }


//Lato Economico

    crea_pagina_pdf();

    crea_intestazione_ditta_pdf("schede_contabili", "Bilancio al ".cambio_data("it", $_end), $_end, $pdf->PageNo(), cambio_data("anno_us", $_end), $_parametri);
    $pdf->SetXY(10, 30);

    $_sbilanciamento_patrim = $_totale['attivita'] - $_totale['passivita'];


    $_sbilanciamento_econ = $_totale['costi'] - $_totale['ricavi'];


    $_differenza = abs($_sbilanciamento_patrim) - abs($_sbilanciamento_econ);


    $pdf->SetFillColor(0, 0, 255);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetX(10);
    $pdf->Cell(190, 8, "Riepilogo", 1, 1, 'C', true);
    
    $pdf->SetXY(10,45); 
    
    $pdf->Cell(95, 7, "Stato Patrimoniale", 1, 1, 'C', true);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(95, 6, mb_convert_encoding("Totale Attività = ", "windows-1252", "UTF-8") . number_format($_totale[attivita], $dec, ',', '.') , 1, 1, 'L');
    $pdf->Cell(95, 6, mb_convert_encoding("Totale Passività = ", "windows-1252", "UTF-8") . number_format($_totale[passivita], $dec, ',', '.') , 1, 1, 'L');
    $pdf->Cell(95, 6, mb_convert_encoding("Sbilanciamento Patrimoniale = ", "windows-1252", "UTF-8") . number_format($_sbilanciamento_patrim, $dec, ',', '.') , 1, 1, 'L');

    $pdf->SetXY(10,80); 
    
    $pdf->SetFillColor(0, 0, 255);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetX(10);
    $pdf->Cell(95, 7, "Conto Economico", 1, 1, 'C', true);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(95, 6, mb_convert_encoding("Totale Costi = ", "windows-1252", "UTF-8") . number_format($_totale[costi], $dec, ',', '.') , 1, 1, 'L');
    $pdf->Cell(95, 6, mb_convert_encoding("Totale Ricavi = ", "windows-1252", "UTF-8") . number_format($_totale[ricavi], $dec, ',', '.') , 1, 1, 'L');
    $pdf->Cell(95, 6, mb_convert_encoding("Sbilanciamento Economico = ", "windows-1252", "UTF-8") . number_format($_sbilanciamento_econ, $dec, ',', '.') , 1, 1, 'L');


    chiudi_files("bilancio", "../../../", "I");
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
