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


    if ($_azione == "PDF")
    {

        //creaiamo il pdf..
        
        
        //qui iniziamo a costruire la pagina direttamente in pdf..
        //creaiamo il file
        crea_file_pdf($_cosa, $_orientamento, "effetto_$dati[numeff]");

        crea_pagina_pdf();
        $_parametri['email'] = "3";
        
        crea_intestazione_ditta_pdf("", "effetto_$dati[numeff]", $_anno, $_pg, $pagina, $_parametri);
        //qui mi conviene comporla a mano..
        $_parametri['paga'] = $paga[$dati['tipoeff']];
        intesta_pagina("titolo", "Risultato Scadenziario Incassi.." , $_parametri);

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
        $pdf->SetFont('Arial','B', 10);

        foreach ($result AS $datiba)
        {
            $pdf->Cell(193, 3, " ", 0, 1, 'L', 0);
            $pdf->Cell(193, 5, "$datiba[banca]", 0, 1, 'L', 0);
            $pdf->Cell(193, 5, "Iban = $datiba[iban] $datiba[cin] $datiba[abi] $datiba[cab], $datiba[cc]", 0, 1, 'L', 0);
            
        }

        corpo_pagina("calce_effetti", $dati, $_parametri);
        

        $_pdf = chiudi_files("effetto_$dati[numeff]", "../../..", "F");
       


        //prepariamo la maschera per scrivere
        maschera_invio_posta("singolo", $_percorso, $_pdf, $email3, $dati2['email2'], "Risultato Scadenziario Incassi", $_parametri);
    }
    else
    {

        $_title = "AguaGest | Portafoglio Scadenze";
        base_html_stampa("chiudi", $_parametri);


        if($EFFETTI_LOGO != "")
        {
            if($EFFETTI_LOGO == "0")
            {
                $_parametri['intestazione'] = "0";
            }
            elseif($EFFETTI_LOGO == "2")
            {
                $_parametri['intestazione'] = "2";
            }
            else
            {
                $_parametri['intestazione'] = "1";
                $_parametri[intesta_immagine] = $EFFETTI_LOGO;
            }
        }
        
        $_parametri['tabella'] = "Risultato Scadenziario Incassi..";
        $_parametri['email'] = "3";
        intestazione_html($_cosa, $_percorso, $_parametri);
        
        
        print <<<html
        <br>&nbsp;
        <table border="0" align="center" cellspacing="0" cellpadding="0">
        <tr>
    <td width="50%" bgcolor="#FFFFFF" valign="top" align="left">
    <i><b>Spettabile</b></i>&nbsp;{$dati['utente']}<br>
    {$dati2['ragsoc']}<br>
    {$dati2['indirizzo']}<br>
    {$dati2['cap']}&nbsp; {$dati2['citta']}&nbsp;({$dati2['prov']})<br>
    <?# $naz_cod = ord_field("dcodnazione"); naz_read(); naz_show("ddenominazione")}<br>
    P.I.&nbsp;{$dati2['piva']}<br>
    Tel. &nbsp;{$dati2['telefono']}<br>
    Fax &nbsp;{$dati2['fax']}<br>
    </td>
    <td width="50%"bgcolor="#ffFFFF" valign="top" align="left">
    <b><i>{$dati['tipodoc']}</i></b><br>
    Num. {$dati['numdoc']} / {$dati['annodoc']} del {$dati['datadoc']}<br>
	<b>Banca </b><br>
	{$dati['bancapp']}<br>
    ABI {$dati['abi']}  CAB {$dati['cab']}  CIN {$dati['cin']}  C/C {$dati['cc']}<br>
    <b>Pagamento </b><br> {$datip['descrizione']}<br>
	<b>Totale documento </b> {$dati['totdoc']}<br>
    </td>
  </tr>
</table>
<br>&nbsp;<br>        
<table border="1" align="center" cellspacing="0" cellpadding="0" width=\"$PRINT_WIDTH\">
  <tr>
  <td width="60%" colspan=2 bgcolor="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>Tipo pagamento </i></font><br>{$paga[$dati['tipoeff']]}
  </td>
	<td width="150" bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Data Registrazione</i></font><br><font face="arial" size="3"><b>
	{$dati['datareg']} </b></font></td>

    <td width="150" bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Pagamento numero</i></font><br><font face="arial" size="3"><b> {$dati['numeff']}/{$dati['annoeff']}</b></font></td>
</tr>
  <tr>
  	<td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Scadenza Pagamento</i></font><br><font face="arial" size="3"><b>{$dati['scadeff']}</b></font></td>
        <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Importo scadenza / ev. spese</i></font><br><font face="arial" size="3"><b>{$dati['impeff']} + {$dati['spese']}</b></font></td>
	<td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Importo da pagare</i></font><br><font face="arial" size="3"><b>{$totale}</b></font></td>

    <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Status</i></font><br><font face="arial" size="3"><b>{$dati['status']}</b></font></td>

  </tr>

</table>

<table border="0" align="center" cellspacing="0" cellpadding="0">

html;

        if ($dati['status'] == "in attesa")
        {
            echo "$_messaggio\n";

            $result = tabella_banche("elenca_radio", $_codice, $_abi, $_cab, $_parametri);
        }
        else
        {
            echo "$_messaggio_2\n";

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

        foreach ($result AS $datiba)
        {
            printf("<input type=\"checkbox\" name=\"istituto\" value=\"%s\"><b><font size=\"2\"> %s <br>&nbsp; &nbsp; &nbsp; &nbsp; iban %s  cin %s  abi %s  cab %s  c/c %s</b><br><br>\n", $datiba['codice'], $datiba['banca'], $datiba['iban'], $datiba['cin'], $datiba['abi'], $datiba['cab'], $datiba['cc']);
        }
        print <<<html2

    </td>
</tr>

<tr><td align="center" width="$PRINT_WIDTH"><br>

In attesa di un vostro riscontro <br> Porgiamo distinti saluti <br>$azienda
    
</td>
</tr>

</table>
</body>
</page>
</html>

html2;
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>