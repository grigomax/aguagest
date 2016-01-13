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
require_once $_percorso . "librerie/stampe_pdf.php";
//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


if ($_SESSION['user']['vendite'] > "1")
{
// vars ok
// Programma che perepara il documento all astampa
// horus grigomax@mcetechnik.it
// vado a prendermi il numero della di bolla inserita


    $_ndoc = $_GET['ndoc'];
    $_anno = $_GET['anno'];
    $_azione = $_GET['azione'];



// settingo di stampa avviso effetto bancario
//genero il cambio documento
    $paga['1'] = "1 - Rimessa diretta";
    $paga['2'] = "2 - Contanti";
    $paga['3'] = "3 - Ricevuta bancaria";
    $paga['4'] = "4 - Tratta o cambiale";
    $paga['5'] = "5 - Contrassegno";
    $paga['6'] = "6 - Bonifico Bancario";
    $paga['7'] = "7 - Ricevimento Fattura";


    $_messaggio = "<tr><td align=\"left\" width=\"700\"><br><br>Gentile cliente, da un nostro controllo contabile, non ci risulta ancora pervenuto questo pagamento. <br>
	Vi preghiamo di verificare la vostra situazione e nel caso aveste gi&agrave; provveduto al pagamento,<br> vi chiediamo di inoltrarci copia,
	della contabile bancaria e di non considerare questo sollecito .<br>
	Altrimenti vogliate saldare il seguente pagamento al pi&ugrave; presto. <br>
	<br><br>Di seguito rimettiamo le nostre coordinate bancarie. <br><br>";

    $_messaggio_2 = "<tr><td align=\"left\" width=\"700\"><br><br>Gentile cliente, la nostra banca ci comunica l'insoluto relativo alla fattura sopra citata. <br>
	Vi preghiamo di controllare la vostra situazione e nel caso aveste gi&agrave; provveduto al pagamento,
	<br> vi chiediamo di inoltrarci copia della contabile bancaria.<br><br>
	Altrimenti vogliate saldare al pi&ugrave; presto il vostro debito con relative spese, sulle seguenti coordinate bancarie..<br><br>";

    //selezioniamo l'effetto..
    $dati = tabella_effetti("singola", $_percorso, $_anno, $_ndoc, $_parametri);

    // prendo lo status del documento per eliminare i pulsanti sotto
    $_status = $dati['status'];

    //prendo il tipo di documento
    $_tdoc = $dati['tdoc'];
    //selezioniamo il cliente dall'anagrafica
    $dati2 = tabella_clienti("singola", $dati['codcli'], $_parametri);

    //selezioniamo il pagamento
    $datip = tabella_pagamenti("singola", $dati['modpag'], $_parametri);

    //selezioniamo la banca di appartenenza
    $datib = tabella_banche("singola", $dati['bancadist'], $_abi, $_cab, $_parametri);


    $totale = $dati['impeff'] + $dati['spese'];


    //creaiamo il pdf..
    //qui iniziamo a costruire la pagina direttamente in pdf..
    //creaiamo il file
    crea_file_pdf($_cosa, $_orientamento, "effetto_$dati[numeff]");

    crea_pagina_pdf();
    $_parametri['email'] = "3";
    
    $link = substr("http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 0,(strpos("http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], '?')));
    $_parametri['link'] = $link."?ndoc=$dati[numeff]&anno=$_anno&azione=Inoltra";

    crea_intestazione_ditta_pdf("", "effetto_$dati[numeff]", $_anno, $_pg, $pagina, $_parametri);
    //qui mi conviene comporla a mano..
    $_parametri['paga'] = $paga[$dati['tipoeff']];
    intesta_pagina("titolo", "Risultato Scadenziario Incassi..", $_parametri);

    $dati['pagamento'] = $datip['descrizione'];
    intesta_pagina("effetti_tabella", "Scheda Articolo", $dati2);

    corpo_pagina("effetti_tabella", $dati, $_parametri);

    //inseriamo il messaggio

    if ($dati['status'] == "in attesa")
    {
        $pdf->MultiCell(193, 5, strip_tags($_messaggio), 0, 'L', 0);

        $result = tabella_banche("elenca_radio", $_codice, $_abi, $_cab, $_parametri);
    }
    else
    {
        $pdf->MultiCell(193, 5, strip_tags($_messaggio2), 0, 'L', 0);

        if ($dati[bancadist] == "")
        {
            $result = tabella_banche("singola_abi", $_codice, $dati[abi], $dati[cab], $_parametri);
        }
        else
        {
            $_parametri['campi'] = "codice";
            $_parametri['descrizione'] = $dati[bancadist];
            $result = tabella_banche("ricerca", $_codice, $dati[abi], $dati[cab], $_parametri);
        }
    }
    $pdf->SetFont('Arial', 'B', 10);

    foreach ($result AS $datiba)
    {
        $pdf->Cell(193, 3, " ", 0, 1, 'L', 0);
        $pdf->Cell(193, 5, "$datiba[banca]", 0, 1, 'L', 0);
        $pdf->Cell(193, 5, "Iban = $datiba[iban] $datiba[cin] $datiba[abi] $datiba[cab], $datiba[cc]", 0, 1, 'L', 0);
    }

    corpo_pagina("calce_effetti", $dati, $_parametri);


    if ($_azione == "Inoltra")
    {
        $_pdf = chiudi_files("effetto_$dati[numeff]", "../../..", "F");
        //prepariamo la maschera per scrivere
        maschera_invio_posta("singolo", $_percorso, $_pdf, $email3, $dati2['email2'], "Risultato Scadenziario Incassi", $_parametri);
    }
    else
    {
        $_pdf = chiudi_files("effetto_$dati[numeff]", "../../..", "I");
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>