<?php

//inserimento funzioni oer creazione distinta
//creiamo la testata

function testata_dis($datib, $datie, $_pg, $pagina, $_azione, $_emaildestino)
{
	require "../../../../setting/vars.php";

	if ($_azione == "Inoltra")
	{
		echo "<center><a href=\"stampa_dist.php?anno=$datie[annoeff]&ndistinta=$datie[ndistinta]&azione=Invia\">\n";
		echo "Per inoltrare il documento via E-mail a " . "$_emaildestino" . " clicca QUI !</a><br>";
	}

	if ($datie['tipo_pres'] == "dic")
	{
		$_presentazione = "Dopo Incasso";
	}
	else
	{
		$_presentazione = "accredito S.B.F.";
	}


	print <<< html
<table border="0" align="center" heigth="950px" style="{page-break-inside: avoid;}">
<tr>
<td align="center" valign="top" width="100%" height="100%">


<div style="text-align: center;">Servizio di portafoglio elettronico distinta delle disposizioni presentate
<br>
<table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="1">
<tbody>
<tr>
<td><b>$azienda</b><br>$indirizzo<br>$cap $citta ($prov)<br>P.I. $piva<br><b>CONTO N. {$datib['cc']} </b></td>

<td valign="top"><b>Spettabile<br> {$datib['banca']} </b><br>{$datib['indirizzo']}<br>FAX {$datib['fax']}</td></tr>
<tr><td valign="top" >Tipo presentazione<br> <b>$_presentazione</b></td><td style="text-align: right;"><br>DISTINTA n. &nbsp;<b> {$datie['ndistinta']} &nbsp; </b> del <b>&nbsp;{$datie['datadist']} </b> &nbsp;Pag.&nbsp; $_pg / $pagina&nbsp;</td></tr></tbody></table><br>

html;
}

function corpo_dis($_pg, $pagina, $rpp, $result, $datie, $_nr, $_somma, $_corpo)
{
	require "../../../../setting/vars.php";
	$_imp_scad = $_corpo['imp_scad'];
	$_num_scad = $_corpo['num_scad'];

	// ciclo di estrazione dei dati
	for ($_nr = 1; $_nr <= $rpp; $_nr++)
	{
		$dati3 = $result->fetch(PDO::FETCH_ASSOC);

		if ($dati3['numeff'] != "")
		{

			$_mese_rif = substr($dati3['scadeff'], 3, 7);

			$_imp_scad[$_mese_rif] = $_imp_scad[$_mese_rif] + $dati3['impeff'];
			$_num_scad[$_mese_rif] = $_num_scad[$_mese_rif] + 1;


			print <<< corpo

<table style="text-align: left;" border="0" cellpadding="0" cellspacing="0" width=\"100%\"><tbody>
<tr>
<td valign="top" aling="center"><font face="arial" size="1" valign="top"><i>N. effetto</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;<b>{$dati3['numeff']} / {$dati3['annoeff']}</b></font></td>
<td valign="top" colspan="3"><font face="arial" size="1" valign="top"><i> Riferimenti</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['tipodoc']} N. {$dati3['numdoc']} / $dati3[suffixdoc] / {$dati3['annodoc']} DEL {$dati3['datadoc']}</font></td>
<td valign="top" align="center"><font face="arial" size="1" valign="top"><i><b>Scadenza</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['scadeff']}</font></b></td>
<td valign="top" align="center"><font face="arial" size="1" valign="top"><i><b>Importo</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['impeff']}</font></b></td>
</tr>
<tr>
<td valign="top" colspan="4"><font face="arial" size="1" valign="top"><i>Debitore</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['ragsoc']}</font></td>
<td valign="top" colspan="2"><font face="arial" size="1" valign="top"><i>Partita iva</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['piva']}</font></td>
</tr>
<tr>
<td valign="top" colspan="2"><font face="arial" size="1" valign="top"><i>Indirizzo</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['indirizzo']}</font></td>
<td valign="top"><font face="arial" size="1" valign="top"><i>Cap.</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['cap']}</font></td>
<td valign="top" colspan="2"><font face="arial" size="1" valign="top"><i>Localit&agrave;</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['citta']}</font></td>
<td valign="top"><font face="arial" size="1" valign="top"><i>Prov</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['prov']}</font></td>
</tr>
<tr>
<td valign="top" colspan="3" ><font face="arial" size="1" valign="top"><i>Banca Appoggio</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['bancapp']}</font></td>
<td valign="top"><font face="arial" size="1" valign="top"><i>Abi</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['abi']}</font></td>
<td valign="top"><font face="arial" size="1" valign="top"><i>Cab</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['cab']}</font></td>
<td valign="top"><font face="arial" size="1" valign="top"><i>C/C</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['cc']}</font></td>
</tr>
</tbody>
</table>
<HR>
corpo;
		}
		elseif ($_nr == $rpp)
		{
			echo "<table style=\"text-align: left; \" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody>\n";
			echo "<tr>\n";
			echo "<td valign=\"top\" aling=\"center\"><b>Sommario</b>&nbsp;<br>\n";

			while (@list($indice, $valore) = each($_imp_scad))
			{

				echo "<font face=\"$fontdocsta\" style=\"font-size: $fontdocsize" . "pt;\">Scadenze al $indice Nr. $_num_scad[$indice] euro = $valore</FONT></FONT><br>\n";
			}
			echo "</td></tr>\n";
		}
		else
		{
			print <<< corpo2
            <table style="text-align: left; width: 100%;" border="0" cellpadding="0" cellspacing="0"><tbody>
            <tr>
            <td valign="top" aling="center">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></td>
            </tr>


corpo2;
		}

		$_somma = $_somma + $dati3['impeff'];
	} //fine di for

	$_corpo['somma'] = $_somma;
	$_corpo['imp_scad'] = $_imp_scad;
	$_corpo['num_scad'] = $_num_scad;

	return $_corpo;
}

//fine funzione

function calce_dis($pagina, $_pg, $righe, $_somma)
{
    global $PRINT_WIDTH;

	if ($pagina == $_pg)
	{
		$_disposizioni = $righe - 1;
		$_importi = $_somma;
	}
	else
	{
		$_disposizioni = "Segue";
		$_importi = "Segue";
	}

	print <<< calce
<table style="text-align: left; width: $PRINT_WIDTH;" border="1" cellpadding="2" cellspacing="2"><tbody>
<tr>
<td valign="top" align="center"> <b>Totale disposizioni n. &nbsp; $_disposizioni </b></td>
<td valign="top" align="center"><b>Importo Totale &euro; $_importi </b></td>
</tr>
</tbody>
</table>
<br><br>Timbro e Firma___________________________________</div></body></html>

</td></tr>
</table>

calce;
}


//fine funzione calce
?>