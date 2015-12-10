<?php

/*
  Programma con funzioni per la creazione dei documenti.
 * Agua gest file di libreria per la preparazione alla stampa..
 * Grigolin Massimo massimo@mcetechnik.it
 * 
 * 
 * Programma generale stempe di tutti i documenti, sia di magazzino che altro..
 * Cercherò di creare un unico programma dove appoggiare tutte le stampe..
 */

// file di funzioni

/////// COMPLETATA LA MIGRAZIONE ALLA LIBRERIE PDO

/* * *
 * funzione che mi recupera le caratteristiche dei documenti..
 */
function layout_doc($_cosa, $_tdoc, $conn)
{
    global $_percorso;
	
    if($_cosa == "singola")
    {
        $query = "SELECT * FROM stampe_layout WHERE tdoc = '$_tdoc' limit 1";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore layot_doc Query = $query - $_errore[2]";
            $_errori['files'] = "stampe.inc.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        foreach ($result AS $datidoc);
    }
    else
    {
        //funzione che mi seleziona dal database il documento..
	//ed il nome del documento da prendere..
	$query = "SELECT * FROM stampe_layout WHERE tdoc = '$_tdoc' limit 1";

	#echo $query;
	
	$result = mysql_query($query, $conn) or mysql_error();

	$datidoc = mysql_fetch_array($result);
    }

	return $datidoc;
}

//funzione che mi genera il castelletto d'iva
function castello_iva($_ivadiversa, $_castiva, $pagina, $_pg, $datidoc, $LINGUA, $desciva, $dati)
{
//per prima cosa passiamo l'inclusione dei files vars.php
	require "../../setting/vars.php";
	// includiamo il file delle lingue
	include "../librerie/$LINGUA";
	require_once "../librerie/lib_html.php";
	require_once "../librerie/motore_anagrafiche.php";
	//dobbiamo inserire le spese di trasporto nel castelletto iva
	//considerando le spese sono con iva 20%
	if ($dati['datareg'] < $DATAIVA)
	{
		$ivasis = $ivasis - 1;
	}

	if ($_ivadiversa != "2")
	{
		$_castiva[$ivasis] = ($_castiva[$ivasis] + (($dati['imballo'] + $dati['trasporto'] + $dati['spesevarie'] + $dati['sp_bancarie']) - $dati['scoinco']));
	}
	//riordino l castello iva
	arsort($_castiva);

	echo "<TD WIDTH=134 colspan=\"2\">\n";
	echo "<table align=\"center\" width=\"100%\" border=\"0\">\n";
	echo "<tr><td align=right><FONT FACE=$datidoc[ST_FONTESTACALCE]><font size=1><I>$CI001</I></FONT></FONT></td>";
	echo "<td align=right><FONT FACE=$datidoc[ST_FONTESTACALCE]><font size=1><I>$CI002</I></FONT></FONT></td>";
	echo "<td align=right><FONT FACE=$datidoc[ST_FONTESTACALCE]><font size=1><I>$CI003</I></FONT></FONT></td>";
	echo "</tr>";

	if ($pagina == $_pg)
	{
		// se il cliente e esente lo faccio apparire
		if ($_ivadiversa == "2")
		{
			echo "<tr><td align=center colspan=3><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$desciva</font></td></tr>\n";
		}
		else
		{
			// Visualizzo tatali iva diverse
			while (@list($indice, $valore) = each($_castiva))
			{
				$_aliquota = tabella_aliquota("singola_aliquota", $indice, $_percorso);
				$_ivasep = number_format((($valore * $_aliquota) / 100), $dec, '.', '');

				if ($indice != "")
				{
					echo "<tr>";
					echo "<td ALIGN=RIGHT><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">" . number_format(($valore), $decdoc) . "</FONT></FONT></td>\n";
					echo "<td ALIGN=right><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$indice</FONT></FONT></td>\n";
					echo "<td ALIGN=RIGHT><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">" . number_format(($_ivasep), $decdoc) . "</FONT></FONT></td>\n";
					echo "</tr>\n";
				}
			}
		}
	}

	echo "</table></TD>";
}

//inizio gestione documenti..

/* Funzioni di creazione documenti l'estenzione pdf sarà per gli stessi.
 */


/* * *
 * Funzione che mi crea il logo del   in formato html..
 * quattro tipi di logo
 * 1=con logo grande
 * 2= con mezzo logo
 * 3=con intestazione tutta scritta da variabili
 * 4=con spazio relativo ad un logo pre esistente in carta.
 *
 */
function intestazione_doc($datidoc, $LINGUA, $_percorso)
{//inizio funzioni testata documenti
//per prima cosa passiamo l'inclusione dei files vars.php
	require "$_percorso" . "../setting/vars.php";
	// includiamo il file delle lingue
	include "$_percorso" . "librerie/$LINGUA";

	if ($_GET['intesta'] == "no")
	{
            echo "<table border=\"0\" height=\"135px\" align=\"left\" width=\"750px\">\n";
            echo "<tr>\n";
            echo "<td width=\"100%\" align=\"center\">\n";
            echo "&nbsp;\n";
            echo "</td>\n";
            echo "</tr></table>\n";
	}
	else
	{
		if ($datidoc[ST_TLOGO] == "1")
		{
                        echo "<table border=\"0\" height=\"135px\" align=\"left\" width=\"750px\">\n";
                        echo "<tr>\n";
                        echo "<td align=\"center\">\n";
                        echo "<img src=\"../../setting/loghiazienda/$datidoc[ST_LOGOG]\" width=\"100%\">\n";
                        echo "</td>\n";
                        echo "</tr></table>\n";
			echo "<br>\n";
		}

		if ($datidoc[ST_TLOGO] == "2")
		{//tipo di logo molto semplice con carattere a ed allineamento d sinistra
			#echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">\n";
			echo "<table class=\"document_logo\">\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\"><h3>$azienda</h3></font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$azienda2</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$indirizzo - $cap $citta $prov</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$LG001 $piva $LG002 $codfisc</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$LG003 $telefono $LG004 $fax</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$LG005 $sitointernet $LG006 $email1</font></td></tr>\n";
			echo "</table><br>\n";
		}//fine secondo logo.

		if ($datidoc[ST_TLOGO] == "3")
		{//tipo di logo molto semplice con carattere a ed allineamento d sinistra
			#echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">\n";
			echo "<table class=\"document_logo\">\n";
			echo "<td align=\"left\" width=\"50%\" rowspan=\"6\"><img src=\"../../setting/loghiazienda/$datidoc[ST_LOGOM]\" width=\"250\" height=\"100\"></img></td>\n";
			echo "<tr><td align=\"left\" width=\"50%\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\"><h3>$azienda</h3></font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$azienda2</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$indirizzo - $cap $citta $prov</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$LG001 $piva $LG002 $codfisc</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$LG003 $telefono $LG004 $fax></font></td></tr>\n";
			echo "</table><br>\n";
		}//fine secondo logo.

		if ($datidoc[ST_TLOGO] == "4")
		{//tipo di logo molto semplice con carattere a ed allineamento d sinistra
			#echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">\n";
			echo "<table class=\"document_logo\">\n";
			echo "<tr><td align=\"left\" width=\"50%\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\"><h3>$azienda</h3></font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$azienda2</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$indirizzo - $cap $citta $prov</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$LG001 $piva $LG002 $codfisc</font></td></tr>\n";
			echo "<tr><td align=\"left\"><font face=\"$datidoc[ST_FONTLOGO]\" style=\"font-size: $datidoc[ST_FONTLOGOSIZE]" . "pt;\">$LG003 $telefono $LG004 $fax></font></td></tr>\n";
			echo "<td align=\"left\" width=\"50%\" rowspan=\"6\"><img src=\"../../setting/loghiazienda/$datidoc[ST_LOGOM]\" width=\"250\" height=\"100\"></img></td>\n";
			echo "</table><br>\n";
		}//fine secondo logo.
	}


	if ($datidoc['ST_TLOGO'] == "5")
	{
		//piccola tabellina per stampe stupine

		echo "<table align=\"left\" width=\"$datidoc[width]\" border=\"0\" class=\"table\" style=\"page-break-inside: avoid;\">\n";
		echo "<tr><td align=\"left\"><h3>$azienda</h3></td></tr>\n";
		echo "<tr><td align=\"left\">$indirizzo</td></tr>\n";
		echo "<tr><td align=\"left\">$cap $citta $prov</td></tr>\n";
		echo "<tr><td align=\"left\">P.I. $piva / C.F. $codfisc</td></tr>\n";
		echo "</table>\n";
	}
}

/* * *
 * Funzione che mi crea la testata del documento  in formato html..
 * qui ci dovrebbero essere quattro tipo di testate
 * una per le fatture
 * una per le fatture immediate
 * una per i DDT
 * una per le conferme ordieni preventivi ecc.
 */

function testata_doc($datidoc, $dati, $dati2, $_datait, $_pg, $pagina, $_pagamento, $LINGUA, $_percorso)
{//inizio funzioni testata documenti
//per prima cosa passiamo l'inclusione dei files vars.php
	require "$_percorso" . "../setting/vars.php";
	// includiamo il file delle lingue
	include "$_percorso" . "librerie/$LINGUA";

	if ($datidoc[ST_TIPOTESTATA] == "1")
	{// tipo di testata semplice per ddt o fatture immediate con la visualizzazione sulla destra della destinazione diversa
		//echo "<table border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" valign=\"top\" width=\"100%\">\n";
		echo "<table class=\"document_testa\">\n";
		echo "<tr>\n";
		echo "<td width=\"50%\" valign=\"top\" align=\"left\">\n";
		echo "<font face=\"$datidoc[ST_FONTINTEST]\" style=\"font-size: $datidoc[ST_FONTINTESTSIZE]" . "pt;\">\n";
		echo "<i>$ID001</i>&nbsp;";
		echo "<br>\n";
		echo "<B> $dati2[ragsoc]<br>\n";
		echo "$dati2[ragsoc2]<br></B>\n";
		echo "$dati2[indirizzo]<br>\n";
		echo "$dati2[cap] &nbsp; $dati2[citta] &nbsp; ($dati2[prov])<br>\n";
		echo "$dati2[codnazione] &nbsp;<br>\n";
		echo "$ID002&nbsp; $dati2[piva]<br>\n";
		echo "$ID003 $dati2[telefono] &nbsp;<br></font></font></td>\n";

		echo "<td width=\"50%\" bgcolor=\"#ffFFFF\" valign=\"top\" align=\"left\">\n";
		echo "<font face=\"$datidoc[ST_FONTINTEST]\" style=\"font-size: $datidoc[ST_FONTINTESTSIZE]" . "pt;\">\n";
		if ($dati['dragsoc'] != "")
		{
			echo "<i><b>$ID004</i><br>\n";
			echo "$dati[dragsoc]</b><br>\n";
			echo "$dati[dragsoc2]<br>\n";
			echo "$dati[dindirizzo]<br>\n";
			echo "$dati[dcap]&nbsp; $dati[dcitta] &nbsp; ($dati[dprov])<br>\n";
			echo "$dati[dcodnazione]&nbsp;<br>\n";
			echo "$ID005 $dati2[telefonodest] &nbsp;<br>\n";
		}

		echo "</font></td></tr></table>\n";
	}//fine testata n. 1

	if ($datidoc[ST_TIPOTESTATA] == "2")
	{
		// tipo di testata complessa e completa.. tipo conferme preventivi ecc..
		#echo "<TABLE WIDTH=100% BORDER=1 BORDERCOLOR=\"#000000\" CELLPADDING=0 CELLSPACING=0>\n";
		echo "<table class=\"document_testa\" border=\"1\" >\n";
		echo "<COL WIDTH=135*><COL WIDTH=121*>\n";
		echo "<TR>\n";
		echo "<TD align=\"left\" valign=\"top\" WIDTH=53%>\n";
		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		echo "<b><i>$datidoc[ST_NDOC] n.: &nbsp;</i>$dati[ndoc] / $dati[anno] </b>&nbsp; rev. $dati[rev]<BR>\n";
		echo "<B><i>$TC007 &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;:&nbsp;</i> $_datait </b><BR>\n";
		echo "<i>$TC003&nbsp;: &nbsp; </i><B>$_pg / $pagina</b><BR>\n";
		echo "<i>$ID006 :&nbsp;</i>$dati2[fax]<BR>\n";
		echo "<i>$TC006 :&nbsp;</i>$dati[vettore]<BR>\n";
		echo "<i>$TC005 :&nbsp;</i>$_porto</FONT></FONT>\n";
		echo "</TD>";

		echo "<TD valign=\"top\" align=\"left\" WIDTH=47%>\n";
		echo "<font face=\"$datidoc[ST_FONTINTEST]\" style=\"font-size: $datidoc[ST_FONTINTESTSIZE]" . "pt;\">\n";
		echo "<B><I>$ID001</I></B><br>\n";
		echo "<b>$dati2[ragsoc]</b> <br>\n";
		echo "$dati2[ragsoc2]<br>\n";
		echo "$dati2[indirizzo]<br>\n";
		echo "$dati2[cap] &nbsp; $dati2[citta] &nbsp; ($dati2[prov])<br>\n";
		echo "$dati2[nazione]</FONT></FONT></P>\n";
		echo "</TD></TR><TR><TD WIDTH=53% align=\"left\">\n";

		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><I>$TC013 </i>:&nbsp; $_pagamento <BR>\n";
		echo "<i>$TC015 :</i> $dati2[banca]<BR>\n";
		echo "<i>$TC016 :</i>&nbsp; $dati2[swift]<BR>\n";
		echo "<i>$TC017 :</i> &nbsp; $dati2[iban]&nbsp;&nbsp;|&nbsp;&nbsp; <i>$TC018 :</i> &nbsp; $dati2[cin]  <BR>\n";
		echo "<i>$TC019 :</i> &nbsp; $dati2[abi] &nbsp;&nbsp;|&nbsp;&nbsp;<i>$TC020 :</i>&nbsp; $dati2[cab]<BR>\n";
		echo "<i>$TC021 :</i>&nbsp; $dati2[cc]</font></font>\n";

		echo "</TD><TD align=\"left\" valign=\"top\" WIDTH=47%>\n";
		echo "<font face=\"$datidoc[ST_FONTINTEST]\" style=\"font-size: $datidoc[ST_FONTINTESTSIZE]" . "pt;\"></I>\n";
                echo "<B><I>$ID001</I></B><br>\n";
		echo "$dati[dragsoc]<br>\n";
                echo "$dati[dragsoc2]<br>\n";
		echo "$dati[dindirizzo]<br>\n";
		echo "$dati[dcap] &nbsp; $dati[dcitta] &nbsp; ($dati[dprov])<br>\n";
		echo "$dati[dnazione]<br>\n";
		//echo "$ID006 :&nbsp; $dati2[faxdest]
                echo "</FONT></FONT>\n";
		echo "</TD></TR>";
                
		echo "<tr><td align=\"left\" colspan=\"2\">\n";
		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		echo "$TC022 = $dati2[swift]$dati2[iban]$dati2[cin]$dati2[abi]$dati2[cab]$dati2[cc]</FONT></FONT>\n";
		echo "</td></tr></table>\n";
	}//fine testata tipo 2

	if ($datidoc[ST_TIPOTESTATA] == "3")
	{// tipo di testata a mono esposizione a dx tipica fatture
		#echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\">\n";
		echo "<table class=\"document_testa\">\n";
		echo "<TR><TD widht=\"65%\" align=\"left\"></TD>\n";
		echo "<TD WIDTH=\"45%\" align=\"left\" VALIGN=TOP>\n";
		echo "<font face=\"$datidoc[ST_FONTINTEST]\" style=\"font-size: $datidoc[ST_FONTINTESTSIZE]" . "pt;\">\n";
		echo "<i><BR>$ID001</i>&nbsp;";
		echo "<br>\n";
		echo "<B> $dati2[ragsoc]<br>\n";
		echo "$dati2[ragsoc2]<br></B>\n";
		echo "$dati2[indirizzo]<br>\n";
		echo "$dati2[cap] &nbsp; $dati2[citta] &nbsp; ($dati2[prov])<br>\n";
		echo "$dati2[codnazione] &nbsp;<br>\n";
		echo "</font></font></td>\n";
		echo "</TD></TR></TABLE>\n";
	}// fine testata n. 3

	if ($datidoc[ST_TIPOTESTATA] == "4")
	{// tipo di testata semplice per ddt o fatture immediate con la visualizzazione sulla destra della destinazione diversa
		//senza telefono
		#echo "<table border=\"0\" align=\"center\" valign=\"top\" width=\"100%\">\n";
		echo "<table class=\"document_testa\">\n";
		echo "<tr>\n";
		echo "<td width=\"50%\" bgcolor=\"#FFFFFF\" valign=\"top\" align=\"left\">\n";
		echo "<font face=\"$datidoc[ST_FONTINTEST]\" style=\"font-size: $datidoc[ST_FONTINTESTSIZE]" . "pt;\">\n";
		echo "<i>$ID001</i>&nbsp;";
		echo "<br>\n";
		echo "<B> $dati2[ragsoc]<br>\n";
		echo "$dati2[ragsoc2]<br></B>\n";
		echo "$dati2[indirizzo]<br>\n";
		echo "$dati2[cap] &nbsp; $dati2[citta] &nbsp; ($dati2[prov])<br>\n";
		echo "$dati2[codnazione] &nbsp;<br>\n";
		echo "$ID002&nbsp; $dati2[piva]<br>\n";
		echo "&nbsp;<br></font></font></td>\n";

		echo "<td width=\"50%\" bgcolor=\"#ffFFFF\" valign=\"top\" align=\"left\">\n";
		echo "<font face=\"$datidoc[ST_FONTINTEST]\" style=\"font-size: $datidoc[ST_FONTINTESTSIZE]" . "pt;\">\n";
		if ($dati['dragsoc'] != "")
		{
			echo "<i><b>$ID004</i><br>\n";
			echo "$dati[dragsoc]</b><br>\n";
			echo "$dati[dragsoc2]<br>\n";
			echo "$dati[dindirizzo]<br>\n";
			echo "$dati[dcap]&nbsp; $dati[dcitta] &nbsp; ($dati[dprov])<br>\n";
			echo "$dati[dcodnazione]&nbsp;<br>\n";
			echo "&nbsp;<br>\n";
		}

		echo "</font></td></tr></table>\n";
	}//fine testata n. 4
	//
//-------------------------------------------------------------   

	if ($datidoc[ST_TIPOTESTATA] == "5")
	{
		echo "<table class=\"document_testata\" width=\"100%\">\n";
		echo "<tr><td width=\"50%\" bgcolor=\"#FFFFFF\" valign=\"top\" align=\"left\">$azienda<br>$sitointernet<br>Telefono : $telefono Fax : $fax<br>e-mail :$email1 </td>\n";
		echo "<td width=\"50%\" bgcolor=\"#FFFFFF\" valign=\"top\" align=\"right\">Data $_datait <br>\n";
		echo "<b>Anno di riferimento = $_POST[anno] $_POST[mese]</b><br>\n";
		echo "<b>Tipo = $datidoc[tipo]</b><br>\n";
		echo "Pagina $_pg di $pagina</td></tr>\n";
		echo "<tr><td colspan=\"2\" align=\"center\"><h3>$datidoc[ST_NDOC] al $_POST[data]</h3></td></tr>\n";
		echo "</table><br>\n";
	}
	//--------------------------------------------------------------------------



	if ($datidoc[ST_SOTTOTESTATA] == "1")
	{// semplice tipo bolle
		echo "<br>\n";
		#echo "<table border=\"1\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
		echo "<table border=\"1\" class=\"document_sottotesta\">\n";
		echo "<tr>";
		echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC001</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>&nbsp; $datidoc[ST_NDOC] </font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC002</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br><center><center> $_causale</center></font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC003</i><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>$_pg / $pagina</font></font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"2\" valign=\"top\"><i>$TC004</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br><b><BIG>$dati[ndoc]/$dati[anno]</BIG></b></font></font></td>\n";
		echo "</tr><tr>\n";
		echo "<td bg color=\"#FFFFFF\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC005</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br><b>&nbsp; $_porto</b></font></font></td>\n";
		echo "<td colspan=\"2\" bg color=\"#FFFFFF\" valign=\"top\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\"><i>$TC006</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"> $dati[vettore]<br>$dativ[indirizzo]</font></font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"2\" valign=\"top\"><i>$TC007</i></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><b><BIG>$_datait</BIG></b></font></font></td>\n";
		echo "</tr><tr>";
		echo "<td colspan=\"2\" bgcolor=\"#FFFFFF\"align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC008</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br><font face=\"arial\" size=\"2\">&nbsp; $dati2[contatto]</font></font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC009</i><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>$dati2[codagente]</font></font></td>\n";
		echo "<td align=\"center\" width=\"250\"><img src=$_percorso/tools/barcode/barcode.php?barcode=$dati[anno]$dati[ndoc]&width=200&height=40&text=0></td>\n";
		echo "</tr></table>\n";
	}//fine sotto testata

	if ($datidoc[ST_SOTTOTESTATA] == "2")
	{// completa per fatture ecc..
		echo "<br>\n";
		#echo "<TABLE WIDTH=\"100%\" align=\"center\" BORDER=1 CELLPADDING=1 CELLSPACING=0>\n";
		echo "<table class=\"document_sottotesta\" BORDER=1>\n";
		echo "<TR VALIGN=TOP>\n";
		echo "<TD colspan=\"2\" WIDTH=\"70\" VALIGN=\"TOP\" ALIGN=\"CENTER\">\n";
		echo "<FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC001</I></FONT></FONT>\n";
		echo "<br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><b>$TIPODOC</b></FONT></FONT>\n";
		echo "</TD>";
		echo "<TD WIDTH=\"5%\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=1 STYLE=\"font-size: 6pt;\"><I>$TC009</I></FONT></FONT>\n";
		echo "<br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati[agente]</FONT></font></TD>\n";
		echo "<TD WIDTH=\"10%\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC010</I></FONT></FONT>\n";
		echo "<br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati[utente]</FONT></FONT></TD>\n";
		echo "<TD WIDTH=\"14%\" ALIGN=\"CENTER\"><font face=\"$datidoc[ST_FONTESTACALCE]\"><font size=\"1\" style=\"font-size: 6pt;\"><i>$TC011</i></font></font>\n";
		echo "<br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati2[codfisc]</font></font></TD>\n";
		echo "<TD COLSPAN=\"2\" WIDTH=\"14%\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC012</I></FONT></FONT>\n";
		echo "<br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati2[piva]</FONT></FONT></TD>\n";
		echo "<TD WIDTH=\"8%\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC003</I></FONT></FONT>\n";
		echo "<br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$_pg / $pagina</FONT></FONT></TD>\n";
		echo "<TD COLSPAN=\"2\" WIDTH=\"17%\" ALIGN=\"CENTER\" BGCOLOR=\"#00ffff\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 8pt;\"><I>$TC004</I></FONT></FONT>\n";
		echo "<br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><big><b>$dati[ndoc] / $dati[anno]</b></big></FONT></FONT></TD>\n";
		echo "</TR>\n";
		echo "<TR VALIGN=TOP><TD COLSPAN=\"5\" WIDTH=\"60%\" align=\"left\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC013</I></FONT></FONT>\n";
		echo "<br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$_pagamento</FONT></FONT></TD>\n";
		echo "<TD COLSPAN=\"3\" WIDTH=\"13%\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC014</I></FONT></FONT><br>\n";
		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"> $dati2[email3]</FONT></FONT></TD>\n";
		echo "<TD COLSPAN=\"2\" WIDTH=\"25%\" align=\"center\" BGCOLOR=\"#00ffff\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 8pt;\"><I>$TC007</I></FONT></FONT><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><big><B>$_datait</B></big></FONT></FONT></TD>\n";
		echo "</TR><TR VALIGN=TOP>\n";
		echo "<TD COLSPAN=\"4\" WIDTH=\"40%\" align=left >\n";
		echo "<FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC015</I></FONT></FONT><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">&nbsp; $dati[banca]</FONT></FONT></TD>\n";
		echo "<TD WIDTH=\"10%\"><font face=\"$datidoc[ST_FONTESTACALCE]\"><font size=\"1\" style=\"font-size: 6pt;\"><i>$TC016</i></font></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati[swift]</font></font></TD>\n";
		echo "<TD WIDTH=\"6%\"><font face=\"$datidoc[ST_FONTESTACALCE]\"><font size=\"1\" style=\"font-size: 6pt;\"><i>$TC017</i></font></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati[iban]</font></font></TD>\n";
		echo "<TD WIDTH=\"8%\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC018</I></FONT></FONT><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati[cin]</FONT></FONT></TD>\n";
		echo "<TD WIDTH=\"7%\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC019</I></FONT></FONT><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati[abi]</FONT></FONT></TD>\n";
		echo "<TD WIDTH=\"7%\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC020</I></FONT></FONT><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati[cab]</FONT></FONT></TD>\n";
		echo "<TD WIDTH=\"12%\"><font face=\"$datidoc[ST_FONTESTACALCE]\"><font size=\"1\" style=\"font-size: 6pt;\"><i>$TC021</i></font></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$dati[cc]</font></font></TD>\n";
		echo "</TR></TABLE>\n";
	}// fine seconda sottotestata.
}

// fine funzione testata documenti


/* * *
 * Funzione di creazione corpo documenti in formato html
 *  *
 */

function corpo_doc($datidoc, $result, $LINGUA, $corpo_doc, $_percorso)
{//qui passeremo tutti gli arrre per la gestione..
	//per prima cosa i file essenziali
	require "$_percorso" . "../setting/vars.php";
	// includiamo il file delle lingue
	include "$_percorso" . "librerie/$LINGUA";
        
        global $conn;


	#echo "<link rel=\"stylesheet\" href=\"../css/globalest.css\" type=\"text/css\">";
	#Splitto gli importi per poter continuare a fare le somme incorporando la pagina precedente
        if($corpo_doc!= "")
        {
            $_nettovendita = $corpo_doc['netto'];
            $_castiva = $corpo_doc['iva'];

            $_nettovendita = $corpo_doc['netto'];
            $_castiva = $corpo_doc['iva'];
            $_totini = $corpo_doc['iniziale'];
            $_totacq = $corpo_doc['acquisto'];
            $_totvend = $corpo_doc['venduta'];
        }

	//creiamo la tabella..
	//chreiamo una variabile per il messaggio finale..
	$_colspan = "0";

	#echo "<TABLE WIDTH=100% BORDER=1 CELLPADDING=0 CELLSPACING=\"1\" align=\"center\">\n";
	echo "<table class=\"document_corpo\" border=\"0\" CELLPADDING=\"$datidoc[ST_INTERLINEA]\" CELLSPACING=\"0\">\n";
	//creiamo l'intestazione
	echo "<tr>";

	//verifichiamo se vogliono la colonna righe
	if ($datidoc[ST_RIGA] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_RIGA_LC]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD001</b></FONT></FONT></TD>\n";
	}
	//verifichiamo se vogliono la colonna articolo
	if ($datidoc[ST_ARTICOLO] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" align=\"center\" WIDTH=\"$datidoc[ST_ARTICOLO_LC]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD002</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna ARTICOLO FORNITORE
	if ($datidoc[ST_ARTFOR] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_ARTFOR_LC]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD003</b></FONT></FONT></TD>\n";
	}
	//verifichiamo se vogliono la colonna articolo
	if ($datidoc[ST_DESCRIZIONE] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_DESCRIZIONE_LC]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD004</b></FONT></FONT></TD>\n";
	}
	//verifichiamo se vogliono la colonna articolo
	if ($datidoc[ST_UNITA] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" align=\"center\" WIDTH=\"$datidoc[ST_UNITA]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD005</b></FONT></FONT></TD>\n";
	}
	//verifichiamo se vogliono la colonna articolo
	if ($datidoc[ST_QUANTITA] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" align=\"center\" WIDTH=\"$datidoc[ST_QUANTITA_LC]%\" align=\"$datidoc[ST_QUANTITA_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD006</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna articolo
	if ($datidoc[ST_QTAEVASA] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_QTAEVASA_LC]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD007</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna articolo
	if ($datidoc[ST_QTAESTRATTA] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_QTAESTRATTA_LC]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD008</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna articolo
	if ($datidoc[ST_QTASALDO] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" align=\"center\" WIDTH=\"$datidoc[ST_QTASALDO_LC]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD009</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna articolo
	if ($datidoc[ST_LISTINO] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" align=\"center\" WIDTH=\"$datidoc[ST_LISTINO_LC]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD010</b></FONT></FONT></TD>\n";
	}
	//verifichiamo se vogliono la colonna sconti
	if ($datidoc[ST_SCONTI] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_SCONTI_LC]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD011</b></FONT></FONT></TD>\n";
	}

	//COLONNA NETTO VENDITA
	if ($datidoc[ST_NETTO] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_NETTO]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD012</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna sconto
	if ($datidoc[ST_TOTRIGA] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" align=\"center\" WIDTH=\"$datidoc[ST_TOTRIGA]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD013</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna articolo
	if ($datidoc[ST_CODIVA] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_CODIVA]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD014</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna consegna
	if ($datidoc[ST_PESO] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_PESO]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD015</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna consegna
	if ($datidoc[ST_RSALDO] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_RSALDO]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD016</b></FONT></FONT></TD>\n";
	}

	//verifichiamo se vogliono la colonna consegna
	if ($datidoc[ST_CONSEGNA] == "SI")
	{
		$_colspan = $_colspan + 1;
		echo "<TD class=\"intesta_corpo\" WIDTH=\"$datidoc[ST_CONSEGNA]%\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"><b>$CD017</b></FONT></FONT></TD>\n";
	}


	//INIZIO CORPO VARIABILE
	// ciclo di estrazione dei dati
	for ($_nr = 1; $_nr <= $datidoc['ST_RPP']; $_nr++)
	{

		if (($datidoc[ST_AVVISO] == "SI") AND ($_nr == $datidoc['ST_RPP']))
		{
			echo "<tr>\n";
			echo "<td class=\"intesta_corpo\" width=\"100%\" align=\"center\" colspan=\"$_colspan\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"> &nbsp;$datidoc[ST_AVVISO_LC]&nbsp;</font></font></td>";
		}
		else
		{

                        $dati3 = $result->fetch(PDO::FETCH_ASSOC);

			#azzero la variabile
			$_pne = "";

			echo "<tr>\n";
			//verifichiamo se vogliono la colonna righe
			if ($datidoc[ST_RIGA] == "SI")
			{
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_RIGA_LC]%\" align=\"center\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\"> &nbsp;$dati3[rigo]&nbsp;</font></font></td>";
			}

			//verifichiamo se vogliono la colonna articoli
			if ($datidoc[ST_ARTICOLO] == "SI")
			{

				$_articolo = $dati3['articolo'];
				// eliminazione della scritta vuoto dalla stampa
				if ($_articolo == "vuoto")
				{
					$_articolo = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_ARTICOLO_LC]%\" align=\"$datidoc[ST_ARTICOLO_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">&nbsp; $_articolo &nbsp;</font></font></td>\n";
			}

			//verifichiamo se vogliono la colonna articoli
			if ($datidoc[ST_ARTFOR] == "SI")
			{

				$_artfor = $dati3['artfor'];
				// eliminazione della scritta vuoto dalla stampa
				if ($_artfor == "vuoto")
				{
					$_artfor = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_ARTFOR_LC]%\" align=\"$datidoc[ST_ARTFOR_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">&nbsp; $_artfor &nbsp;</font></font></td>\n";
			}

			//verifichiamo se vogliono la colonna descrizione
			if ($datidoc[ST_DESCRIZIONE] == "SI")
			{
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_DESCRIZIONE_LC]%\" align=\"left\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$dati3[descrizione]&nbsp;</td>\n";
			}

			//verifichiamo se vogliono la colonna unita
			if ($datidoc[ST_UNITA] == "SI")
			{
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_UNITA_LC]%\" align=\"center\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$dati3[unita]&nbsp;</td>\n";
			}

			//verifichiamo se vogliono la colonna descrizione
			if ($datidoc[ST_QUANTITA] == "SI")
			{
				$_quantita = $dati3['quantita'];
				if ($_quantita == 0)
				{
					$_quantita = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_QUANTITA_LC]%\" align=\"$datidoc[ST_QUANTITA_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_quantita</td>\n";
			}

			//verifichiamo se vogliono la colonna descrizione
			if ($datidoc[ST_QTAEVASA] == "SI")
			{
				$_qtaevasa = $dati3['qtaevasa'];
				if ($_qtaevasa == 0)
				{
					$_qtaevasa = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" bgcolor=\"#FFFFFF\" width=\"$datidoc[ST_QTAEVASA_LC]%\" align=\"$datidoc[ST_QTAEVASA_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_qtaevasa</td>\n";
			}

			//verifichiamo se vogliono la colonna descrizione
			if ($datidoc[ST_QTAESTRATTA] == "SI")
			{
				$_qtaestratta = $dati3['qtaestratta'];
				if ($_qtaestratta == 0)
				{
					$_qtaestratta = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" bgcolor=\"#FFFFFF\" width=\"$datidoc[ST_QTAESTRATTA_LC]%\" align=\"$datidoc[ST_QTAESTRATTA_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_qtaestratta</td>\n";
			}

			//verifichiamo se vogliono la colonna descrizione
			if ($datidoc[ST_QTASALDO] == "SI")
			{
				$_qtasaldo = $dati3['qtasaldo'];
				if ($_qtasaldo == 0)
				{
					$_qtasaldo = "&nbsp;";
				}
				$_color = "black";
				if ($_qtasaldo < 0)
				{
					$_color = "red";
				}
				echo "<td class=\"document_corpo\" bgcolor=\"#FFFFFF\" width=\"$datidoc[ST_QTASALDO_LC]%\" align=\"$datidoc[ST_QTASALDO_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\" color=\"$_color\">$_qtasaldo</td>\n";
			}


			//verifichiamo se vogliono la colonna descrizione
			if ($datidoc[ST_LISTINO] == "SI")
			{
				if (($datidoc['tdoc'] == "ddt" ) AND ($_GET['prezzi'] == "no"))
				{
					$dati3['listino'] = "";
				}

				$_listino = $dati3['listino'];
				if ($_listino == 0)
				{
					$_listino = "&nbsp;";
				}

				if ($datidoc[ST_AVV_PN] == "SI")
				{
					if (($_articolo != "&nbsp;") AND ($_articolo != "") AND ($_articolo != "vuoto"))
					{
						if (($dati3['scva'] + $dati3['scvb'] + $dati3['scvc']) == "0.00")
						{
							$_pne = '*';
						}
					}
				}

				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_LISTINO_LC]%\" align=\"$datidoc[ST_LISTINO_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_listino&nbsp;$_pne</td>\n";
			}

			//verifichiamo se vogliono la colonna sconti
			if ($datidoc[ST_SCONTI] == "SI")
			{
				$_scva = $dati3['scva'];
				if ($_scva == 0)
				{
					$_scva = "";
				}

				$_scvb = $dati3['scvb'];
				if ($_scvb == 0)
				{
					$_scvb = "";
				}

				$_scvc = $dati3['scvc'];
				if ($_scvc == 0)
				{
					$_scvc = "";
				}

				$_percentuale = "";

				if ($_scva + $_scvb + $_scvc != "")
				{
					$_percentuale = "%";
				}

				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_SCONTI_LC]%\" align=\"$datidoc[ST_SCONTI_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_scva&nbsp;$_scvb&nbsp;$_scvc$_percentuale</td>\n";
			}

			if ($datidoc[ST_NETTO] == "SI")
			{
				$_netto = $dati3['netto'];
				if ($_netto == 0)
				{
					$_netto = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_NETTO_LC]%\" align=\"$datidoc[ST_NETTO_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_netto</td>\n";
			}


			if ($datidoc[ST_TOTRIGA] == "SI")
			{
				$_totriga = $dati3['totriga'];
				if ($_totriga == 0)
				{
					$_totriga = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_TOTRIGA_LC]%\" align=\"$datidoc[ST_TOTRIGA_ALL]\" ><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_totriga</td>\n";
			}

			//verifichiamo se vogliono la colonna unita
			if ($datidoc[ST_CODIVA] == "SI")
			{
				$_iva = $dati3['iva'];
				if ($_totriga == 0)
				{
					$_iva = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_CODIVA_LC]%\" align=\"$datidoc[ST_CODIVA_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_iva</td>\n";
			}


			if ($datidoc[ST_PESO] == "SI")
			{
				$_peso = $dati3['peso'];
				if ($_peso == "")
				{
					$_peso = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_PESO_LC]%\" align=\"$datidoc[ST_PESO_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_peso</td>\n";
			}

			if ($datidoc[ST_RSALDO] == "SI")
			{
				$_rsaldo = $dati3['rsaldo'];
				if ($_rsaldo == "")
				{
					$_rsaldo = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_RSALDO_LC]%\" align=\"$datidoc[ST_RSALDO_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_rsaldo</td>\n";
			}

			if ($datidoc[ST_CONSEGNA] == "SI")
			{
				$_consegna = $dati3['consegna'];
				if ($_consegna == "")
				{
					$_consegna = "&nbsp;";
				}
				echo "<td class=\"document_corpo\" width=\"$datidoc[ST_CONSEGNA_LC]%\" align=\"$datidoc[ST_CONSEGNA_ALL]\"><font face=\"$datidoc[ST_FONTCORPO]\" style=\"font-size: $datidoc[ST_FONTCORPOSIZE]" . "pt;\">$_consegna</td>\n";
			}


			// cALCOLO DEL CASTELLETTO IVA:
			$_ivariga = $dati3['iva'];
			$_castiva[$_ivariga] = ($_castiva[$_ivariga] + $dati3['totriga']);
			$_nettovendita = $_nettovendita + $dati3['totriga'];

			$_totini = $dati3['quantita'] + $_totini;
			$_totacq = $dati3['qtaevasa'] + $_totacq;
			$_totvend = $dati3['qtaestratta'] + $_totvend;
			#$_totval = $datia['netto'] + $_totval;
                        
                        //azzero l'aary
                        $dati3 = "";
		}

		printf("</tr>");
	}

	echo "</TABLE></CENTER>";
	//return $_nettovendita;
	//impostiamo i ritorni..
	//ritorno il netto vendita ed l'iva..'
	return array("netto" => $_nettovendita, "iva" => $_castiva, "iniziale" => $_totini, "acquisto" => $_totacq, "venduta" => $_totvend);
}

//fine funzione costruzione corpo documenti html

/* * *
 * Funzione che mi crea la calce del documento  in formato html..
 * qui ci dovrebbero essere quattro tipo di calce
 * una per le fatture
 * una per le fatture immediate
 * una per i DDT
 * una per le conferme ordieni preventivi ecc.
 */

function calce_doc($datidoc, $pagina, $_pg, $_nettovendita, $_castiva, $dati, $LINGUA, $_ivadiversa, $desciva, $_pagamento, $_percorso)
{//qui passeremo tutti gli arrre per la gestione..
//per prima cosa i file essenziali
	require "$_percorso" . "../setting/vars.php";
	// includiamo il file delle lingue
	include "$_percorso" . "librerie/$LINGUA";

	//specifico la codifica locale
	setlocale(LC_MONETARY, 'it_IT');

	//    Vedo le varianti per la data e l'ora
	if ($_GET['dataora'] == "no")
	{
		$ST_DATA = "";
		$ST_TIME = "";
	}
	elseif ($_ALLT['dataora'] == "no")
	{
		$ST_DATA = "";
		$ST_TIME = "";
	}
	else
	{
		$ST_DATA = date("d/n/Y");
		$ST_TIME = date("H:i");
	}

	// devo variare la esposizione dei dati in base alla lingua..


	if ($datidoc[ST_TIPOCALCE] == "1")
	{
		echo "<i> $TC023 </i>";
		echo "<table border=\"1\" class=\"document_calce\">\n";
		echo "<tr>";
		echo "<td bgcolor=\"#FFFFFF\" width=\"30%\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC024</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>&nbsp;$_look</font></font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"10%\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC025</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>&nbsp; $dati[colli]&nbsp;</font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"10%\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC026</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>&nbsp;$dati[pesotot]&nbsp;</font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"10%\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC027</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>&nbsp; $dati[trasporto]&nbsp;</font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"10%\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC038</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>&nbsp; $dati[spesevarie]&nbsp;</font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"10%\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC028</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>$ST_DATA &nbsp; </font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"10%\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC029</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>$ST_TIME &nbsp;</font></td>\n";
		echo "</tr><tr>";
		echo "<th colspan=\"7\" bgcolor=\"#FFFFFF\" width=\"100%\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\"><i>$TC030</i></font><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><br>&nbsp;$dati[note]</th>\n";
		echo "</tr><tr>\n";
		echo "<th colspan=\"3\" bgcolor=\"#FFFFFF\" width=\"50%\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"1\"><i>$TC031</i><br><br><br></th>\n";
		echo "<th colspan=\"4\" bgcolor=\"#FFFFFF\" width=\"50%\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"2\"><i><b>$TC032</b></i><br><br><br></th>\n";

		echo "</tr></table>\n";
	}//fine calce tipo uno
	//inizio calce tipo conferma
	if ($datidoc[ST_TIPOCALCE] == "2")
	{

		echo "<br>";
		echo "<CENTER>";

		echo "<table border=\"1\" class=\"document_calce\">\n";

		echo "<COL WIDTH=504><COL WIDTH=168><COL WIDTH=167>";

		echo "	<TR>";
		echo "	<TD align=\"left\" ROWSPAN=\"5\" WIDTH=504 VALIGN=TOP>";
		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size:$datidoc[ST_FONTESTASIZE]" . "pt;\"><I>$TC030</I><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">";
		echo $dati['note'];
		echo "	</FONT></FONT></TD>";

		echo "	<TD WIDTH=168>";
		echo "	<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size:$datidoc[ST_FONTESTASIZE]" . "pt;\"><I>$TC034</I>";
		echo "	</FONT></FONT></TD>";

		echo "	<TD WIDTH=167 align=right>";
		echo "		<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">";
		echo number_format($_nettovendita, $dec, '.', '');
		echo "</font></TD></TR>";


		echo "	<TD WIDTH=168>";
		echo "	<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size:$datidoc[ST_FONTESTASIZE]" . "pt;\"><I>$TC006</I>";
		echo "	</FONT></FONT></TD>";

		echo "	<TD WIDTH=167 align=right>";
		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size:$datidoc[ST_FONTESTASIZE]" . "pt;\">";
		echo $dati['trasporto'];
		echo "</font></TD></TR>";
		echo "<TR>";

		echo "	<TD WIDTH=168>";
		echo "	<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size:$datidoc[ST_FONTESTASIZE]" . "pt;\"><I>$TC038</I>";
		echo "	</FONT></FONT></TD>";

		echo "<TD WIDTH=167 align=right>";
		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size:$datidoc[ST_FONTESTASIZE]" . "pt;\">";
		echo $dati['spesevarie'];
		echo "</font></TD></TR>";
		echo "<TR>";

		//provo a richiamare la funzione del castello iva..
		castello_iva($_ivadiversa, $_castiva, $pagina, $_pg, $datidoc, $LINGUA, $desciva, $dati);

		echo "</TR><TR>";
		echo "<TD WIDTH=168>";
		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size:$datidoc[ST_FONTESTASIZE]" . "pt;\"><I><B>$TC040</B></I></FONT></FONT></TD>";
		echo "<TD WIDTH=167 align=right>";
		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size:$datidoc[ST_FONTESTASIZE]" . "pt;\"><b>";
		if ($pagina == $_pg)
		{
			echo "&euro; &nbsp;" .
			number_format(($dati['totdoc']), $decdoc, ',', '.');
                        #echo "<br><big>&euro;  " . number_format(($dati['totdoc']), $decdoc, ',', '.') . "</big>";
		}
		else
		{
			echo $TC043;
		}

		echo "</b></font></TD>";
		echo "</TR>";
		echo "</TABLE></CENTER>";
	}//fine calce tipo 2
	//inizio calce tipo classica fattura..
	if ($datidoc[ST_TIPOCALCE] == "3")
	{
		echo "<font face=\"arial\" size=\"1\">$TC033</font>\n";
		#echo "<TABLE WIDTH=\"100%\" BORDER=\"1\" CELLPADDING=\"1\" CELLSPACING=\"0\">\n";
		echo "<TABLE class=\"document_calce\" WIDTH=\"100%\" BORDER=\"1\" CELLPADDING=\"1\" CELLSPACING=\"0\">\n";
		echo "<TR VALIGN=TOP>\n";
		echo "<TD WIDTH=\"117\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC034</I></FONT></FONT><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['nettomerce'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"117\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC035</I></FONT></FONT><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['scoinco'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"117\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC036</I></FONT></FONT><BR><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['imballo'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"117\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC027</I></FONT></FONT><BR><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['trasporto'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"100\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC038</I></FONT></FONT><BR><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['spesevarie'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"134\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC039</I></FONT></FONT><BR><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['sp_bancarie'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "</TR><TR VALIGN=TOP>\n";
		echo "<TD COLSPAN=\"3\" WIDTH=\"350\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC030</I></FONT></FONT><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['note'];
		}
		echo "&nbsp;</font></FONT></TD>\n";

		//provo a richiamare la funzione del castello iva..
		castello_iva($_ivadiversa, $_castiva, $pagina, $_pg, $datidoc, $LINGUA, $desciva, $dati);

		echo "<TD align=\"center\" WIDTH=\"117\" valign=\"middle\" ><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><B>$TC040 $_tdoc</B></FONT></FONT>\n";
		echo "<font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><B>\n";
		if ($pagina == $_pg)
		{
			echo "<br><big>&euro;  " . number_format(($dati['totdoc']), $decdoc, ',', '.') . "</big>";
		}
		else
		{
			echo $TC043;
		}
		echo "</B></font></FONT></TD>\n";

		echo "</TR></TABLE>\n";
	}//fine calce tipo 3
	//inizio calce tipo classica fattura IMMEDIATA..
	if ($datidoc[ST_TIPOCALCE] == "4")
	{
		echo "<font face=\"arial\" size=\"1\">$TC033 </font>\n";
		echo "<table border=\"1\" class=\"document_calce\" cellspacing=\"0\" cellpadding=\"0\" >\n";
		echo "<TR VALIGN=TOP>\n";
		echo "<TD WIDTH=\"150\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC034</I></FONT></FONT><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['nettomerce'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"117\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC035</I></FONT></FONT><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['scoinco'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"100\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC036</I></FONT></FONT><BR><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['imballo'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"110\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC027</I></FONT></FONT><BR><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['trasporto'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"100\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC038</I></FONT></FONT><BR><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['spesevarie'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "<TD WIDTH=\"134\" ALIGN=\"CENTER\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\"><FONT SIZE=\"1\" STYLE=\"font-size: 6pt;\"><I>$TC039</I></FONT></FONT><BR><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['sp_bancarie'];
		}
		echo "&nbsp;</font></FONT></TD>\n";
		echo "</TR><TR VALIGN=TOP>\n";
		echo "<TR><TD colspan=\"4\" ROWSPAN=\"2\" WIDTH=\"504\" VALIGN=\"TOP\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\" size=\"2\"><I>$TC030</I><br>\n";
		if ($pagina == $_pg)
		{
			echo $dati['note'];
		}
		echo "&nbsp;</FONT></FONT></TD>\n";
		echo "<TD WIDTH=\"168\" align=\"center\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\" SIZE=\"2\"><I>$TC041</I></FONT></TD><TD WIDTH=167 align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $dati['totimpo'];
		}
		echo "&nbsp;</font></TD>\n";
		echo "</TR><TR>\n";

		//provo a richiamare la funzione del castello iva..
		castello_iva($_ivadiversa, $_castiva, $pagina, $_pg, $datidoc, $LINGUA, $desciva, $dati);

		echo "</TR>\n";
		echo "<tr><td bgcolor=\"#FFFFFF\" width=\"100\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC002</i></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">$_causale</font></td>\n";
		echo "<td colspan=\"2\" bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC006</i></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">&nbsp;\n";
		if ($pagina == $_pg)
		{
			echo $dati['vettore'];
		}
		echo "&nbsp;</td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"40\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC005</i></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $_porto;
		}
		echo "&nbsp;</td>\n";
		echo "<TD WIDTH=\"168\" align=\"center\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\" SIZE=\"3\"><I><B>$TC040</B></I></FONT></TD><TD WIDTH=\"167\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><b>\n";
		if ($pagina == $_pg)
		{
			echo "<big>&euro;  " . number_format(($dati['totdoc']), $decdoc, ',','.') . "</big>";
                        #number_format(($dati['totdoc']), $decdoc, ',', '.')
		}
		else
		{
			echo $TC043;
		}
		echo "</b></font></TD></TR>\n";
		echo "<td colspan=\"2\" bgcolor=\"#FFFFFF\" width=\"100\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC024</i></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">&nbsp;\n";
		if ($pagina == $_pg)
		{
			echo $_look;
		}
		echo "&nbsp;</font></td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"10%\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC025</i></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">&nbsp;\n";
		if ($pagina == $_pg)
		{
			echo $dati['colli'];
		}
		echo "&nbsp;</td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"40\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC026</i></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">&nbsp;\n";
		if ($pagina == $_pg)
		{
			echo $dati['pesotot'];
		}
		echo "&nbsp;</td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"18%\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC028</i></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $ST_DATA;
		}
		echo "&nbsp;</td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"16%\" align=\"center\"> <font face=\"$datidoc[ST_FONTESTACALCE]\" size=\"1\" valign=\"top\"><i>$TC029</i></font><br><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\">\n";
		if ($pagina == $_pg)
		{
			echo $ST_TIME;
		}
		echo "&nbsp;</td>\n";
		echo "</tr><tr>\n";
		echo "<th colspan=\"3\" bgcolor=\"#FFFFFF\" width=\"50%\" align=\"left\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\" ><i>$TC031</i></FONT><br><br><br></th>\n";
		echo "<th colspan=\"3\" bgcolor=\"#FFFFFF\" width=\"50%\" align=\"left\"><FONT FACE=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\" ><i><b>$TC032</b></i></FONT><br><br><br></th>\n";
		echo "</tr></table>\n";
	}//fine calce tipo 4

	if ($datidoc[ST_TIPOCALCE] == "5")
	{
		echo "<table class=\"document_calce\" align=\"center\" border=\"0\">\n";
		echo "<tr><td width=\"100%\"><hr></td></tr>\n";
		echo "<tr>\n";
		echo "<td width=\"100%\" colspan=\"7\" bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><b>Q.ta Iniziale = $_nettovendita[iniziale] / acquistata = $_nettovendita[acquisto] / Venduta = $_nettovendita[venduta] / Valore tot. = " . number_format(($_nettovendita['netto']), 2, '.', '') . "</B> </font></td>\n";
		echo "</tr>\n";
		echo "<tr><td width=\"100%\"><hr></td></tr>\n";
		echo "</table>\n";
		echo "<br><br>\n";
	}
	elseif ($datidoc[ST_TIPOCALCE] == "6")
	{
		echo "<table class=\"document_calce\" border=\"1\" align=\"center\" >\n";
		echo "<tr>\n";
		echo "<td width=\"10%\" bgcolor=\"#FFFFFF\" align=\"left\"><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><i>Pagina </i>$_pg di $pagina</font></td>\n";
		echo "<td width=\"80%\" bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><b>Q.ta Finale = $_nettovendita[iniziale]  /  Valore tot. = " . number_format(($_nettovendita['netto']), 2, '.', '') . "</B> </font></td>\n";
		echo "<td width=\"10%\" bgcolor=\"#FFFFFF\" align=\"right\"><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: $datidoc[ST_FONTESTASIZE]" . "pt;\"><i>Pagina </i>$_pg di $pagina</font></td>\n";
		echo "</tr></table><br><br>\n";
	}
	else
	{

		echo "<CENTER>";
		if ($_pagamento == "OMAGGIO CON RIVALSA IVA")
		{
			echo "<table class=\"document_calce\" align=\"center\" border=\"0\"><tr><td align=\"right\">OMAGGIO CON RIVALSA IVA <b>TOTALE DA PAGARE &euro; 0.00</b></td></tr>";
			echo "</table>";
		}
		else
		{
			if ($CGV == "SI")
			{
				echo "<TABLE class=\"document_cgv\" BORDER=\"0\" CELLPADDING=0 CELLSPACING=0>";
				echo "<TR><TD align=\"left\" WIDTH=\"100%\" VALIGN=\"TOP\">";
				echo "<FONT FACE=\"Avantgarde, sans, serif\"><FONT STYLE=\"font-size: 5pt;\">
                                        $TC050 $sitointernet $TC051 $fax.</FONT></FONT>";
				echo "</TD></TR>\n";
			}
			else
			{
				echo "<TABLE class=\"document_cgv\" BORDER=1 CELLPADDING=0 CELLSPACING=0>";
				echo "<TR><TD WIDTH=100% VALIGN=TOP>";
				echo "<FONT FACE=\"Avantgarde, sans, serif\"><FONT STYLE=\"font-size: 8pt;\"><br>&nbsp;<br>&nbsp;</FONT></FONT>";
				echo "</TD></TR>\n";
			}
			echo "<tr><td align=\"right\"><font face=\"$datidoc[ST_FONTESTACALCE]\" style=\"font-size: 4pt;\">Powered by AguaGest - http://aguagest.sourceforge.net/</FONT></FONT></td></tr>\n";
			echo "</TABLE></CENTER></font>";
		}
	}
}

//fine funzione calce doc html
?>