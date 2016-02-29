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

require $_percorso . "librerie/motore_doc_pdo.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{

    $_tdoc = "FATTURA";
//prendiamoci i il database..
    $_archivi = archivio_tdoc($_tdoc);
    $_numero = $_GET['ndoc'];

    
    $_anno = substr($_numero, "0", "4");
    $_suffix = substr($_numero, "4", "1");
    $_ndoc = substr($_numero, "5", "11");



    //prendiamo i dati fattura e quelli del cliente..

    $dati_start = gestisci_testata("leggi_riga_testata", $_utente, $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_archivi, $_parametri);
    //prendiamo tutti i dati dal clienti..
    $dati_utente = tabella_clienti("singola", $dati_start['utente'], "");
    //sistemo l'iva aziendale eliminando l'eventuale IT

    $iva = substr($piva, '-11', '11');


    echo "<table width=\"80%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo "<td width=\"85%\" align=\"center\" valign=\"top\" colspan=\"2\">\n";
    echo "<span class=\"intestazione\"><b>Esportazione $_tdoc nr. $_ndoc anno $_anno</b><br></span><br>\n";

    $_progressivo = str_pad($_GET['progressivo'], 5, '0', STR_PAD_LEFT);

    $file = "IT$iva" . "_$_progressivo.xml";
    $nfile = $_percorso . "../setting/fatture_PA/$file";
// creo il files e nascondo la soluzione
    $fp = fopen($nfile, "w");
//controllo l'esito
    if (!$fp)
        die("Errore.. non sono riuscito a creare il file.. Permessi ?");

// scriviamo le righe basi all'inizio del file

    $_commento = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '<p:FatturaElettronica versione="1.1"' . "\n";
    $_commento.= 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:p="http://www.fatturapa.gov.it/sdi/fatturapa/v1.1" ' . "\n";
    $_commento.= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . "\n";
    fwrite($fp, $_commento);

    $_commento = '  <FatturaElettronicaHeader>' . "\n";

    $_commento.= '      <DatiTrasmissione>' . "\n";

    $_commento.= '          <IdTrasmittente>' . "\n";


    $_commento.= '              <IdPaese>IT</IdPaese>' . "\n";

    $_commento.= '              <IdCodice>' . $iva . '</IdCodice>' . "\n";

    $_commento.= '          </IdTrasmittente>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '          <ProgressivoInvio>' . $_progressivo . '</ProgressivoInvio>' . "\n";
    $_commento.= '          <FormatoTrasmissione>SDI11</FormatoTrasmissione>' . "\n";
    $_commento.= '          <CodiceDestinatario>' . $dati_utente['indice_ipa'] . '</CodiceDestinatario>' . "\n";
    #$_commento.= '          <CodiceDestinatario>999999</CodiceDestinatario>' . "\n";
    $_commento.= '          <ContattiTrasmittente>' . "\n";
    $_commento.= '              <Telefono>' . $telefono . '</Telefono>' . "\n";
    $_commento.= '              <Email>' . $email3 . '</Email>' . "\n";
    $_commento.= '          </ContattiTrasmittente>' . "\n";
    $_commento.= '      </DatiTrasmissione>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '      <CedentePrestatore>' . "\n";
    $_commento.= '          <DatiAnagrafici>' . "\n";
    $_commento.= '              <IdFiscaleIVA>' . "\n";
    $_commento.= '                  <IdPaese>IT</IdPaese>' . "\n";
    $_commento.= '                  <IdCodice>' . $iva . '</IdCodice>' . "\n";
    $_commento.= '              </IdFiscaleIVA>' . "\n";
    $_commento.= '              <CodiceFiscale>' . $codfisc . '</CodiceFiscale>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '              <Anagrafica>' . "\n";
    $_commento.= '                  <Denominazione>' . $azienda . ' </Denominazione>' . "\n";
    $_commento.= '              </Anagrafica>' . "\n";
    $_commento.= '              <RegimeFiscale>RF01</RegimeFiscale>' . "\n";
    $_commento.= '          </DatiAnagrafici>' . "\n";
    $_commento.= '          <Sede>' . "\n";
    $_commento.= '              <Indirizzo>' . $indirizzo . '</Indirizzo>' . "\n";
    $_commento.= '              <CAP>' . $cap . '</CAP>' . "\n";
    $_commento.= '              <Comune>' . $citta . '</Comune>' . "\n";
    $_commento.= '              <Provincia>' . $prov . '</Provincia>' . "\n";
    $_commento.= '              <Nazione>IT</Nazione>' . "\n";
    $_commento.= '          </Sede>' . "\n";
    /*
      $_commento.= '<StabileOrganizzazione>' . "\n";
      /*
      <Indirizzo>Piazza Garibaldi</Indirizzo>
      <CAP>00100</CAP>
      <Comune>Roma</Comune>
      <Provincia>RM</Provincia>
      <Nazione>IT</Nazione>
      </StabileOrganizzazione>
     */

    fwrite($fp, $_commento);

    $_commento = '      <IscrizioneREA>' . "\n";
    $_commento.= '          <Ufficio>' . $REAUFFICIO . '</Ufficio>' . "\n";
    $_commento.= '          <NumeroREA>' . $REANUMERO . '</NumeroREA>' . "\n";
    $_commento.= '          <CapitaleSociale>' . $CAPSOCIALE . '</CapitaleSociale>' . "\n";
    $_commento.= '          <SocioUnico>' . $SOCIOUNICO . '</SocioUnico>' . "\n";
    $_commento.= '          <StatoLiquidazione>' . $LIQUIDAZIONE . '</StatoLiquidazione>' . "\n";
    $_commento.= '      </IscrizioneREA>' . "\n";
    
    $_commento = '      <Contatti>' . "\n";
    $_commento.= '          <Telefono>' . $telefono . '</Telefono>' . "\n";
    $_commento.= '          <Fax>' . $fax . '</Fax>' . "\n";
    $_commento.= '          <Email>' . $email3 . '</Email>' . "\n";
    $_commento.= '      </Contatti>' . "\n";

    if ($dati_utente['cod_ute_dest'] != "")
    {
        $_commento.= '      <RiferimentoAmministrazione>' . $dati_utente['cod_ute_dest'] . '</RiferimentoAmministrazione>' . "\n";
    }

    $_commento.= '      </CedentePrestatore>' . "\n";
    fwrite($fp, $_commento);



    $_commento = '      <CessionarioCommittente>' . "\n";
    $_commento.= '          <DatiAnagrafici>' . "\n";
    $_commento.= '              <IdFiscaleIVA>' . "\n";
    $_commento.= '                  <IdPaese>IT</IdPaese>' . "\n";
    $_commento.= '                  <IdCodice>' . substr($dati_utente['piva'], '-11', '11') . '</IdCodice>' . "\n";
    $_commento.= '              </IdFiscaleIVA>' . "\n";
    $_commento.= '          <CodiceFiscale>' . $dati_utente['codfisc'] . '</CodiceFiscale>' . "\n";
    fwrite($fp, $_commento);


    $_commento = '          <Anagrafica>' . "\n";
    $_commento.= '              <Denominazione>' . $dati_utente['ragsoc'] . ' </Denominazione>' . "\n";
    $_commento.= '          </Anagrafica>' . "\n";
    $_commento.= '      </DatiAnagrafici>' . "\n";
    $_commento.= '      <Sede>' . "\n";
    $_commento.= '          <Indirizzo>' . $dati_utente['indirizzo'] . '</Indirizzo>' . "\n";
    $_commento.= '          <CAP>' . $dati_utente['cap'] . '</CAP>' . "\n";
    $_commento.= '          <Comune>' . $dati_utente['citta'] . '</Comune>' . "\n";
    $_commento.= '          <Provincia>' . $dati_utente['prov'] . '</Provincia>' . "\n";
    $_commento.= '          <Nazione>IT</Nazione>' . "\n";
    $_commento.= '      </Sede>' . "\n";
    $_commento.= '      </CessionarioCommittente>' . "\n";
    $_commento.= '      <SoggettoEmittente>CC</SoggettoEmittente>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '  </FatturaElettronicaHeader>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '  <FatturaElettronicaBody>' . "\n";
    $_commento.= '      <DatiGenerali>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '           <DatiGeneraliDocumento>' . "\n";

    if ($_tdoc == "NOTA CREDITO")
    {
        $TD = "TD04";
    }
    elseif ($_tdoc == "NOTA DEBITO")
    {
        $TD = "TD05";
    }
    else
    {
        $TD = "TD01";
    }

    $_commento.= '              <TipoDocumento>' . $TD . '</TipoDocumento>' . "\n";
    $_commento.= '              <Divisa>EUR</Divisa>' . "\n";
    $_commento.= '              <Data>' . $dati_start['datareg'] . '</Data>' . "\n";
    $_commento.= '              <Numero>' . $dati_start['ndoc'].'/'.$dati_start[suffix] . '</Numero>' . "\n";
    $_commento.= '              <Art73>SI</Art73>' . "\n";
    $_commento.= '          </DatiGeneraliDocumento>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '          <DatiOrdineAcquisto>' . "\n";
    $_commento.= '              <RiferimentoNumeroLinea>1</RiferimentoNumeroLinea>' . "\n";
    $_commento.= '              <IdDocumento>123</IdDocumento>' . "\n";
    $_commento.= '              <CodiceCUP>123abc</CodiceCUP>' . "\n";
    $_commento.= '              <CodiceCIG>456def</CodiceCIG>' . "\n";
    $_commento.= '          </DatiOrdineAcquisto>' . "\n";
    fwrite($fp, $_commento);

    /*

      <DatiContratto>
      <RiferimentoNumeroLinea>1</RiferimentoNumeroLinea>
      <IdDocumento>123</IdDocumento>
      <Data>2012-09-01</Data>
      <NumItem>5</NumItem>
      <CodiceCUP>123abc</CodiceCUP>
      <CodiceCIG>456def</CodiceCIG>
      </DatiContratto>
     */
    $_commento = '      </DatiGenerali>' . "\n";
    fwrite($fp, $_commento);
    $_commento = '      <DatiBeniServizi>' . "\n";
    fwrite($fp, $_commento);


    //leggiamo il corpo del documento
    $dettaglio = gestisci_dettaglio("leggi_corpo", $_archivi, $_tdoc, $_anno, $_suffix, $_ndoc, $_rigo, $dati_start['utente'], $_codice, $_descrizione, $_iva, $_parametri);

    //ora inseriamo il tutto nella fattura..
    foreach ($dettaglio AS $dati_corpo)
    {
        if ($dati_corpo['articolo'] != "vuoto")
        {
            $_rigo++;

            $_commento = '          <DettaglioLinee>' . "\n";
            $_commento.= '              <NumeroLinea>' . $_rigo . '</NumeroLinea>' . "\n";
            $_commento.= '              <CodiceArticolo>' . "\n";
            $_commento.= '                  <CodiceTipo>Codice Art. fornitore</CodiceTipo>' . "\n";
            $_commento.= '                  <CodiceValore>' . $dati_corpo['articolo'] . '</CodiceValore>' . "\n";
            $_commento.= '              </CodiceArticolo>' . "\n";
            $_commento.= '              <Descrizione>' . $dati_corpo['descrizione'] . '</Descrizione>' . "\n";
            $_commento.= '              <Quantita>' . $dati_corpo['quantita'] . '</Quantita>' . "\n";
            $_commento.= '              <UnitaMisura>' . $dati_corpo['unita'] . '</UnitaMisura>' . "\n";
            $_commento.= '              <PrezzoUnitario>' . $dati_corpo['listino'] . '</PrezzoUnitario>' . "\n";
            $_commento.= '              <ScontoMaggiorazione>' . "\n";
            $_commento.= '                  <Tipo>SC</Tipo>' . "\n";
            $_commento.= '                  <Percentuale>' . $dati_corpo['scva'] . '.00</Percentuale>' . "\n";
            $_commento.= '              </ScontoMaggiorazione>' . "\n";
            $_commento.= '              <PrezzoTotale>' . $dati_corpo['totriga'] . '</PrezzoTotale>' . "\n";
            $_commento.= '              <AliquotaIVA>' . $dati_corpo['iva'] . '.00</AliquotaIVA>' . "\n";
            $_commento.= '          </DettaglioLinee>' . "\n";
            fwrite($fp, $_commento);
        }
    }

    $_commento = '          <DatiRiepilogo>' . "\n";
    if ($dati_start['cod_iva_1'] != "")
    {
        $_commento.= '              <AliquotaIVA>' . $dati_start['cod_iva_1'] . '.00</AliquotaIVA>' . "\n";
        $_commento.= '              <ImponibileImporto>' . $dati_start['imponibile_1'] . '</ImponibileImporto>' . "\n";
        $_commento.= '              <Imposta>' . $dati_start['imposta_1'] . '</Imposta>' . "\n";
    }

    if ($dati_start['cod_iva_2'] != "")
    {
        $_commento.= '              <AliquotaIVA>' . $dati_start['cod_iva_2'] . '.00</AliquotaIVA>' . "\n";
        $_commento.= '              <ImponibileImporto>' . $dati_start['imponibile_2'] . '</ImponibileImporto>' . "\n";
        $_commento.= '              <Imposta>' . $dati_start['imposta_2'] . '</Imposta>' . "\n";
    }

    if ($dati_start['cod_iva_3'] != "")
    {
        $_commento.= '              <AliquotaIVA>' . $dati_start['cod_iva_3'] . '.00</AliquotaIVA>' . "\n";
        $_commento.= '              <ImponibileImporto>' . $dati_start['imponibile_3'] . '</ImponibileImporto>' . "\n";
        $_commento.= '              <Imposta>' . $dati_start['imposta_3'] . '</Imposta>' . "\n";
    }

    if ($dati_start['cod_iva_4'] != "")
    {
        $_commento.= '              <AliquotaIVA>' . $dati_start['cod_iva_4'] . '.00</AliquotaIVA>' . "\n";
        $_commento.= '              <ImponibileImporto>' . $dati_start['imponibile_4'] . '</ImponibileImporto>' . "\n";
        $_commento.= '              <Imposta>' . $dati_start['imposta_4'] . '</Imposta>' . "\n";
    }

    if ($dati_start['cod_iva_5'] != "")
    {
        $_commento.= '              <AliquotaIVA>' . $dati_start['cod_iva_5'] . '.00</AliquotaIVA>' . "\n";
        $_commento.= '              <ImponibileImporto>' . $dati_start['imponibile_5'] . '</ImponibileImporto>' . "\n";
        $_commento.= '              <Imposta>' . $dati_start['imposta_5'] . '</Imposta>' . "\n";
    }
    $_commento.= '          </DatiRiepilogo>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '      </DatiBeniServizi>' . "\n";
    fwrite($fp, $_commento);


    $_commento = '      <DatiPagamento>' . "\n";
    $_commento.= '          <CondizioniPagamento>TP01</CondizioniPagamento>' . "\n";
    $_commento.= '          <DettaglioPagamento>' . "\n";
    $_commento.= '              <ModalitaPagamento>MP01</ModalitaPagamento>' . "\n";
    $_commento.= '              <DataScadenzaPagamento>2012-12-31</DataScadenzaPagamento>' . "\n";
    $_commento.= '              <ImportoPagamento>' . $dati_start['totdoc'] . '</ImportoPagamento>' . "\n";
    $_commento.= '              <IstitutoFinanziario>' . $dati_start['banca'] . '</IstitutoFinanziario>' . "\n";
    if ($dati_start['iban'] != "")
    {
        $_commento.= '              <IBAN>' . $dati_start['iban'] . $dati_start['cin'] . $dati_start['abi'] . $dati_start['cab'] . $dati_start['cc'] . '</IBAN>' . "\n";
    }

    $_commento.= '              <ABI>' . $dati_start['abi'] . '</ABI>' . "\n";
    $_commento.= '              <CAB>' . $dati_start['cab'] . '</CAB>' . "\n";
    if ($dati_start['swift'] != "")
    {
        $_commento.= '              <BIC>' . $dati_start['swift'] . '</BIC>' . "\n";
    }
    $_commento.= '          </DettaglioPagamento>' . "\n";
    $_commento.= '      </DatiPagamento>' . "\n";

    fwrite($fp, $_commento);

    $_commento = '  </FatturaElettronicaBody>' . "\n";
    fwrite($fp, $_commento);

    $_commento = '</p:FatturaElettronica>' . "\n";
    fwrite($fp, $_commento);
// chiudiamo il files
    fclose($fp);

    echo "<h4>Generazione Effettuata..</h4>";
    echo "<h4>cliccando il tasto destro del mouse e faccendo salva con nome</h4>";
    echo "<h4><a href=\"$nfile\">$file </a></h4>";


    echo "</td></tr></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>