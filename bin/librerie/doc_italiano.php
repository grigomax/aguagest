<?php

/*
 * File di variabili contenenti le parole italiane per i documenti di stampa
 * agua gest aguagest.sourceforge.net
 */


// Cambio varibili..

$_porto = $dati['porto'];
$_look = $dati['aspetto'];

$_causale = $dati['causale'];
if ($_causale == "")
{
    $_causale = "VENDITA";
}

#IL TIPO DOCUMENTO NEL CASO DI VENDITA ESTERA DEVE ESSERE DIVERSO..
$TIPODOC = $dati['tdoc'];
if ($TIPODOC != "")
{
    $TIPODOC = $dati['tdoc'];
}
else
{
    $TIPODOC = $ST_NDOC;
}


//Files per il logo $LG000
$LG001 = "P.I.";
$LG002 = "Cod. Fisc.";
$LG003 = "Tel.";
$LG004 = "Fax";
$LG005 = "Sito";
$LG006 = "E-mail";

//Files per la intestazione del documento $ID000
$ID001 = "Spett.le";
$ID002 = "P.I.";
$ID003 = "Tel.";
$ID004 = "Destinazione se diversa";
$ID005 = "Tel. Dest.";
$ID006 = "Fax	";



//Files per la testata e la calce del documento $TC000
$TC001 = "Tipo documento";
$TC002 = "Causale del Trasporto";
$TC003 = "Pagina";
$TC004 = "Documento N.";
$TC005 = "Spedizione in Porto";
$TC006 = "Vettore trasporto";
$TC007 = "Data Documento";
$TC008 = "Vostri Riferimenti";
$TC009 = "Ns. Rif.";
$TC010 = "Cod. Cliente";
$TC011 = "Cod. Fiscale";
$TC012 = "Partita iva";
$TC013 = "Pagamento";
$TC014 = "e-mail";
$TC015 = "Banca ";
$TC016 = "Swift (BIC)";
$TC017 = "Iban";
$TC018 = "Cin";
$TC019 = "Abi";
$TC020 = "Cab";
$TC021 = "c/c";
$TC022 = "Indirizzo Bancario completo";
$TC023 = "* Prezzo netto";
$TC024 = "Aspetto dei Beni";
$TC025 = "N. Colli";
$TC026 = "Peso Kg";
$TC027 = "Spese Trasporto";
$TC028 = "Data partenza:";
$TC029 = "Ora Partenza:";
$TC030 = "Annotazioni";
$TC031 = "Firma (Mittente o Vettore)";
$TC032 = "Firma per Ricevuta";
$TC033 = "* CONTRIBUTO CONAI ASSOLTO OVE DOVUTO * ";
$TC034 = "Netto Merce";
$TC035 = "Sconto incondizionato";
$TC036 = "Spese imballo";
$TC038 = "Spese varie";
$TC039 = "Spese Bancarie";
$TC040 = "TOTALE";
$TC041 = "Totale Imponibile";
$TC042 = "VENDITA";
$TC043 = "Segua pagina";
$TC050 = "Si prega di controllare l'esattezza di questo documento, ogni variazione deve essere tempestivamente comunicata, in caso contrario la si terra valida. La merce viaggia a rischio e pericolo dell'acquirente anche se spedita in porto franco - Questo documento e parte integrante delle condizioni generali di vendita, visibili presso il sito";
$TC051 = "oppure richiedibili per fax allo";



//files per il castello iva CI000
$CI001 = "Imponibili";
$CI002 = "Iva";
$CI003 = "Imposte";

if($_eti == "SI")
{
	
	//Maschera per le etichette
    $CD001 = "Larghetta Etichetta";
    $CD002 = "Codice";
    $CD003 = "Bar Code";
    $CD004 = "Descrizione";
    $CD005 = "UM";
    $CD006 = "Foto Articolo";
    $CD007 = "Riga intestazione";
    $CD008 = "Riga Inferiore";
    $CD009 = "Bordo Inf / bordo SX";
    $CD010 = "Prezzo";
    $CD011 = "Sconto";
    $CD012 = "Netto";
    $CD013 = "Importo";
    $CD014 = "Iva";
    $CD015 = "Peso";
    $CD016 = "rs";
    $CD017 = "Consegna";
	$CD020 = "Riga Campo scrittura riga inferiore";
	
}
elseif ($datidoc['tdoc'] == "inventario")
{
    //Files per il corpo del documento $CD000
    $CD001 = "Riga";
    $CD002 = "Codice";
    $CD003 = "Art. Forn.";
    $CD004 = "Descrizione";
    $CD005 = "UM";
    $CD006 = "Iniziale";
    $CD007 = "Acquisto";
    $CD008 = "Venduta";
    $CD009 = "Finale";
    $CD010 = "Prezzo";
    $CD011 = "Sconto";
    $CD012 = "Netto";
    $CD013 = "Valore";
    $CD014 = "Iva";
    $CD015 = "Peso";
    $CD016 = "rs";
    $CD017 = "Consegna";
	$CD020 = "Eventuale avviso da inserire in calce al corpo";
	$_campo_ALL = "Allineamento colonna";
	$_campo_CT = "Numero di caratteri stampabili";
	$_campo_LC = "Larghezza campo in percentuale";
}
elseif($datidoc['tdoc'] == "rimanenze")
{
    //Files per il corpo del documento $CD000
    $CD001 = "Riga";
    $CD002 = "Codice";
    $CD003 = "Art. Forn.";
    $CD004 = "Categoria";
    $CD005 = "UM";
    $CD006 = "Quantità";
    $CD007 = "Acquisto";
    $CD008 = "Venduta";
    $CD009 = "Finale";
    $CD010 = "Prezzo";
    $CD011 = "Sconto";
    $CD012 = "Netto";
    $CD013 = "Valore";
    $CD014 = "Iva";
    $CD015 = "Peso";
    $CD016 = "rs";
    $CD017 = "Consegna";
		$CD020 = "Eventuale avviso da inserire in calce al corpo";
		$_campo_ALL = "Allineamento colonna";
	$_campo_CT = "Numero di caratteri stampabili";
	$_campo_LC = "Larghezza campo in percentuale";
}
else
{
    //Files per il corpo del documento $CD000
    $CD001 = "Riga";
    $CD002 = "Codice";
    $CD003 = "Art. Forn.";
    $CD004 = "Descrizione";
    $CD005 = "UM";
    $CD006 = "Q.ta";
    $CD007 = "Q.ta Evasa";
    $CD008 = "Q.ta Estratta";
    $CD009 = "Q.ta Saldo";
    $CD010 = "Prezzo";
    $CD011 = "Sconto";
    $CD012 = "Netto";
    $CD013 = "Importo";
    $CD014 = "Iva";
    $CD015 = "Peso";
    $CD016 = "rs";
    $CD017 = "Consegna";
	$CD020 = "Eventuale avviso da inserire in calce al corpo";
	
	$_campo_ALL = "Allineamento colonna";
	$_campo_CT = "Numero di caratteri stampabili";
	$_campo_LC = "Larghezza campo in percentuale";
	
	
}
?>