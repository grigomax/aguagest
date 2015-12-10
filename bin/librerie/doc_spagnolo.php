<?php
/* 
 * File di variabili contenenti le parole italiane per i documenti di stampa
 * agua gest aguagest.sourceforge.net
 */

// Cambio varibili..

$_porto = $dati['porto'];
if ($_porto == "ASSEGNATO")
	{
	$_porto = "ASIGNADO";
	}
	else
	{
	$_porto = "INCLUIDO";
	}

$_look = $dati['aspetto'];
if ($_look == "SCATOLA")
{
    $_look = "Caja";
}


$_causale = $dati['causale'];
if(($_causale = "VENDITA") OR ($_causale = ""))
{
    $_causale = "VENTA";
}

#IL TIPO DOCUMENTO NEL CASO DI VENDITA ESTERA DEVE ESSERE DIVERSO..
$TIPODOC = $dati['tdoc'];
if($TIPODOC != "")
{
    if($TIPODOC == "FATTURA")
    {
	$TIPODOC = "FACTURA";
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
	$TIPODOC = "Documento de Transporte";
    }
    elseif($ST_NDOC == "Conferma Ordine")
    {
	$TIPODOC = "Confirmar pedido";
    }
    elseif($ST_NDOC == "Preventivo")
    {
	$TIPODOC = "Cotizaciones";
    }
    elseif($ST_NDOC == "Ordine Agente")
    {
	$TIPODOC = "Agente de Orden";
    }
    else
    {
	$TIPODOC = "Orden de Proveedor";
    }
}
else
{
    $TIPODOC = $ST_NDOC;
}


//Files per il logo $LG000
$LG001 = "T.A.V.";
$LG002 = "Cod. Fisc.";
$LG003 = "Tel.";
$LG004 = "Fax";
$LG005 = "Sitio";
$LG006 = "E-mail";

//Files per la intestazione del documento $ID000
$ID001 = "Spett.le";
$ID002 = "T.A.V.";
$ID003 = "Tel.";
$ID004 = "destino si es diferente";
$ID005 = "Tel. Dest.";
$ID006 = "Fax	";



//Files per la testata e la calce del documento $TC000
$TC001 = "Tipo de documento";
$TC002 = "Razon de Trasporte";
$TC003 = "Pagina";
$TC004 = "Documento N.";
$TC005 = "Expedicion Purto";
$TC006 = "Nombre del Correo";
$TC007 = "Data Documento";
$TC008 = "Vuestros Contacto";
$TC009 = "Ns. Cont.";
$TC010 = "Cod. Cliente";
$TC011 = "Cod. Fisc.";
$TC012 = "TAV";
$TC013 = "Tipo de Pago";
$TC014 = "e-mail";
$TC015 = "Banco ";
$TC016 = "Swift (BIC)";
$TC017 = "Iban";
$TC018 = "Cin";
$TC019 = "Abi";
$TC020 = "Cab";
$TC021 = "c/c";
$TC022 = "Coordenadas de banco";
$TC023 = "* Precio Neto";
$TC024 = "Aspectod de Bienes";
$TC025 = "N. Paquete";
$TC026 = "Peso Kg";
$TC027 = "Costos Correo";
$TC028 = "Fecha de salida";
$TC029 = "Horas de salida";
$TC030 = "Annotaciones";
$TC031 = "Firma (Remitente o Correo)";
$TC032 = "Firma por Recibida";
$TC033 = "* CONTRIBUTO CONAI ASSOLTO OVE DOVUTO * ";
$TC034 = "Netto Merce";
$TC035 = "Esconto incondicionado";
$TC036 = "Costos Paquete";
$TC038 = "Costos vario";
$TC039 = "Costos de banco";
$TC040 = "CUENTA";
$TC041 = "Totale Imponible";
$TC043 = "Sigue pagina";
$TC050 = "Por favor, compruebe la exactitud de este documento, cualquier cambio debe ser notificada sin demora, de lo contrario se llevará a cabo válida. Las mercancías viajan a riesgo del comprador, incluso si se envían en puerto. Este documento es parte de las condiciones generales de venta, visibles en el sitio de";
$TC051 = "o solicitar por fax al";



//files per il castello iva CI000
$CI001 = "Imponibili";
$CI002 = "Tav";
$CI003 = "Impuestos";



//Files per il corpo del documento $CD000
$CD001 = "Rigo";
$CD002 = "Codigo";
$CD003 = "Art. Forn.";
$CD004 = "Descricion";
$CD005 = "UM";
$CD006 = "Quantit&agrave;";
$CD007 = "Q.ta Evasa";
$CD008 = "Q.ta Estratta";
$CD009 = "Q.ta Saldo";
$CD010 = "Precio";
$CD011 = "Esconto";
$CD012 = "Neto";
$CD013 = "Cuenta";
$CD014 = "Tav";
$CD015 = "Peso";
$CD016 = "rs";
$CD017 = "Consiña";


?>