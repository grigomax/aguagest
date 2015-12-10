<?php
/* 
 * File di variabili contenenti le parole italiane per i documenti di stampa
 * agua gest aguagest.sourceforge.net
 */

// Cambio varibili..

$_porto = $dati['porto'];
if ($_porto == "ASSEGNATO")
	{
	$_porto = "EXWORKS";
	}
	else
	{
	$_porto = "FREE PORT";
	}

$_look = $dati['aspetto'];
if ($_look == "SCATOLA")
{
    $_look = "CARTOON";
}


$_causale = $dati['causale'];
if(($_causale = "VENDITA") OR ($_causale = ""))
{
    $_causale = "SALES";
}

#IL TIPO DOCUMENTO NEL CASO DI VENDITA ESTERA DEVE ESSERE DIVERSO..
$TIPODOC = $dati['tdoc'];
if($TIPODOC != "")
{
    if($TIPODOC == "FATTURA")
    {
	$TIPODOC = "INVOICE";
    }
    elseif($TIPODOC == "FATTURA IMMEDIATA")
    {
	$TIPODOC = "INVOICE";
    }
    else
    {
	$TIPODOC = $dati['tdoc'];
    }
}
elseif($ST_NDOC != "")
{
    #mttiamo le treduzioni in varie ringue
    if($ST_NDOC == "D.D.T. DPR 476/96")
    {
	$TIPODOC = "Document of transport";
    }
    elseif($ST_NDOC == "Conferma Ordine")
    {
	$TIPODOC = "Confirm order";
    }
    elseif($ST_NDOC == "Preventivo")
    {
	$TIPODOC = "Offert";
    }
    elseif($ST_NDOC == "Ordine Agente")
    {
	$TIPODOC = "Agency Order";
    }
    else
    {
	$TIPODOC = "Provvider Order";
    }
}
else
{
    $TIPODOC = $ST_NDOC;
}




//Files per il logo $LG000
$LG001 = "VAT";
$LG002 = "Fisc. Cod.";
$LG003 = "Tel.";
$LG004 = "Fax";
$LG005 = "Site";
$LG006 = "E-mail";

//Files per la intestazione del documento $ID000
$ID001 = "Messers";
$ID002 = "VAT";
$ID003 = "Tel.";
$ID004 = "Delivery Address";
$ID005 = "Tel. Del.";
$ID006 = "Fax	";



//Files per la testata e la calce del documento $TC000
$TC001 = "Document Type";
$TC002 = "Motive of Transport";
$TC003 = "Page";
$TC004 = "Document N.";
$TC005 = "Parity";
$TC006 = "Dispatch";
$TC007 = "Document Date";
$TC008 = "Your Ref.";
$TC009 = "Our Ref.";
$TC010 = "Client Code";
$TC011 = "Fiscal Code";
$TC012 = "V.A.T. Number";
$TC013 = "Payment Terms";
$TC014 = "e-mail";
$TC015 = "Bank ";
$TC016 = "Swift (BIC)";
$TC017 = "Iban";
$TC018 = "Cin";
$TC019 = "Abi";
$TC020 = "Cab";
$TC021 = "c/c";
$TC022 = "Complete bank Address";
$TC023 = "* Net Price";
$TC024 = "Despatch";
$TC025 = "N. of Pack";
$TC026 = "Weight Kg";
$TC027 = "Transport Charghes";
$TC028 = "Start Date";
$TC029 = "Time Start";
$TC030 = "Note:";
$TC031 = "Signature (For internal use only)";
$TC032 = "Signature for receipt";
$TC033 = "**";
$TC034 = "Tot. Goods net";
$TC035 = "Another Discount";
$TC036 = "Packing Cost";
$TC038 = "Extra Charges";
$TC039 = "Bank Charges";
$TC040 = "TOTAL";
$TC041 = "Total Ammount";
$TC043 = "Go in next";
$TC050 = "Please, look this documet, and verify is right, if not contact us immediately, else for us is good. - The goods travel at risk and danger of the messers even if sold frank of destiny. - This document is an integral part of general sales condition, visible at the site";
$TC051 = "or by fax";



//files per il castello iva CI000
$CI001 = "Amount by tax";
$CI002 = "Tax";
$CI003 = "Tax amount";



//Files per il corpo del documento $CD000
$CD001 = "Item";
$CD002 = "Id code";
$CD003 = "Code Provv.";
$CD004 = "Description";
$CD005 = "Unit.";
$CD006 = "Quantity";
$CD007 = "Q.ta Evasa";
$CD008 = "Q.ta Estratta";
$CD009 = "Q.ta Saldo";
$CD010 = "Price";
$CD011 = "Discount";
$CD012 = "Net.";
$CD013 = "Amount";
$CD014 = "Vat";
$CD015 = "Weight";
$CD016 = "is";
$CD017 = "Delivery";


?>
