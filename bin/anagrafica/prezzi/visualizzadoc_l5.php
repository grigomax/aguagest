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
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

printf("<form action=\"modificadoc.php\" method=\"POST\">");

//recupero le variabili
$_tdoc = $_GET['tdoc'];
$_anno = $_GET['anno'];
if ($_GET['ndoc'] != "")
{
	$_ndoc = $_GET['ndoc'];
}
else
{
	$_ndoc = $_POST['ndoc'];
}



if ($_tdoc == "ddt")
{
	$_documento = "bv_bolle";
}
elseif ($_tdoc == "conferma")
{
	$_documento = "co_testacalce";
}
elseif ($_tdoc == "ordine")
{
	$_documento = "or_testacalce";
}
elseif ($_tdoc == $nomedoc)
{
	$_documento = "fv_testacalce";
}
else
{
	$_documento = "pv_testacalce";
}



// Stringa contenente la query di ricerca... solo dei fornitori
$query = sprintf("SELECT * FROM %s where ndoc=\"%s\" and anno=\"%s\"", $_documento, $_ndoc, $_anno);

// Esegue la query...
if ($res = mysql_query($query, $conn))
{
	$dati = mysql_fetch_array($res);
	$_utente = $dati['utente'];

	// prendo lo status del documento per eliminare i pulsanti sotto
	$_status = $dati['status'];
	$_evasonum = $dati['evasonum'];
	$_evasoanno = $dati['evasoanno'];
	$_tdocevaso = $dati['tdocevaso'];
}

$query = sprintf("select * from clienti where codice=\"%s\"", $_utente);

// Esegue la query...
if ($res = mysql_query($query, $conn))
{
	$dati2 = mysql_fetch_array($res);
}
?>
<table border="1" align="center" width="100%">

	<tr>
    <td width="50%" bgcolor="#FFFFFF" valign="top" align="left">
			<i>Spett.le</i>&nbsp;<? echo $dati['utente']; ?><br>
			<? echo $dati2['ragsoc']; ?><br>
			<? echo $dati2['indirizzo']; ?><br>
			<? echo $dati2['cap']; ?>&nbsp; <? echo $dati2['citta']; ?>&nbsp;(<? echo $dati2['prov']; ?>)<br>
			<? # $naz_cod = ord_field("dcodnazione"); naz_read(); naz_show("ddenominazione"); ?><br>
			P.I.&nbsp;<? echo $dati2['piva']; ?>
    </td>
    <td width="50%" bgcolor="#ffFFFF" valign="top" align="left">
			<i><b>Destinazione</i><br>
<? echo $dati['dragsoc']; ?></b><br>
<? echo $dati['dindirizzo']; ?><br>
<? echo $dati['dcap']; ?>&nbsp;<? echo $dati['dcitta']; ?>&nbsp;(<? echo $dati['dprov']; ?>)<br>
<? # $naz_cod = ord_field("dcodnazione"); naz_read(); naz_show("denominazione");  ?><br>
    </td>
  </tr>
</table>
<br>
<table border="1" align="center" cellspacing="0" cellpadding="0" width="100%">
  <tr>
		<td bgcolor="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>Tipo documento</i></font><br><? echo $_tdoc; ?>
		</td>
		<td bg color="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>Causale del Trasporto</i></font><br><? echo $dati['causale']; ?>
		</td>
    <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Pagina</i><br><? $_pag = 1;
				echo $_pag; ?></font></td>
    <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Documento N.</i></font><br><b><font face="arial" size="3"><? echo
				$dati['ndoc']; ?>/<? echo $dati['anno']; ?></b></font></td>  </tr>
  <tr>
		<td bg color="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>Spedizione in</i></font><br><b><? echo $dati['porto']; ?></b></font></td>  <td bg color="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>Trasporto a cura</i></font><br><? echo
				$dati['vettore']; ?></font></td>    <td bgcolor="#FFFFFF"></td>

    <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Data Documento</i></font><br><font face="arial" size="3"><b><? echo $dati['datareg']; ?></b></font></td>  </tr>
</table>

<br>

<table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">

	<tr>
    <th bgcolor="#FFFFFF" width="70">Codice</th>
    <th bgcolor="#FFFFFF" width="400" align="left">Descrizione</th>
    <th bgcolor="#FFFFFF" width="30">U.M.</th>
    <th bgcolor="#FFFFFF" width="70">Quantit&agrave;</th>
    <th bgcolor="#FFFFFF" width="70">Listino</th>
    <th bgcolor="#FFFFFF" width="40">SC.A</th>
    <th bgcolor="#FFFFFF" width="40">SC.B</th>
    <th bgcolor="#FFFFFF" width="40">SC.C</th>
    <th bgcolor="#FFFFFF" width="70">Netto</th>
    <th bgcolor="#FFFFFF" width="70">Tot. Riga</th>
	</tr>
</table>
<table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">

	<?
	if ($_tdoc == "ddt")
	{
		$_docdetta = "bv_dettaglio";
	}
	elseif ($_tdoc == "conferma")
	{
		$_docdetta = "co_dettaglio";
	}
	elseif ($_tdoc == "ordine")
	{
		$_docdetta = "or_dettaglio";
	}
	elseif ($_tdoc == $nomedoc)
	{
		$_docdetta = "fv_dettaglio";
	}
	else
	{
		$_docdetta = "pv_dettaglio";
	}


	$query = sprintf("select * from %s where anno=\"%s\" and ndoc=\"%s\" order by rigo", $_docdetta, $_anno, $_ndoc);

	if ($res = mysql_query($query, $conn))
	{
		if (mysql_num_rows($res))
		{
			while ($dati3 = mysql_fetch_array($res))
			{
				$_articolo = $dati3['articolo'];
				{
					if ($_articolo == "vuoto")
						$_articolo = "&nbsp;";
				}
				printf("<tr><td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s&nbsp;</td>", $_articolo);
				printf("<td width=\"400\" height=\"1\" align=\"left\" class=\"testo_blu\">%s</td>", $dati3['descrizione']);
				printf("<td width=\"30\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati3['unita']);
				printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati3['quantita']);
				printf("<td width=\"40\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati3['listino']);
				printf("<td width=\"40\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati3['scva']);
				printf("<td width=\"40\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati3['scvb']);
				printf("<td width=\"40\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati3['scvc']);
				printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati3['nettovendita']);
				printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati3['totriga']);
				printf("</tr>");
			}
		}
	}
	?>

	<table border="1" width="100%">

		<br><br><tr>
			<td bgcolor="#FFFFFF" width="40%" align="left"><font face="arial" size="1" valign="top"><i>Aspetto dei Beni</i><br><? echo
	$dati['aspetto']; ?></font></td><td bgcolor="#FFFFFF" width="15%" align="center"><font face="arial" size="1" valign="top"><i>Colli n.</i><br><? echo
	$dati['colli']; ?></td><td bgcolor="#FFFFFF" width="15%" align="center"><font face="arial" size="1" valign="top"><i>Peso</i><br><? echo
	$dati['pesotot']; ?></td><td bgcolor="#FFFFFF" width="15%" align="center"><font face="arial" size="1" valign="top"><i>Data partenza:</i><br><script
				language="php">echo date("d/n/Y");</script></td><td bgcolor="#FFFFFF" width="15%" align="center"><font face="arial" size="1" valign="top"><i>Ora
					Partenza:</i><br><script language="php">echo date("H:i");</script></td></tr>
		<tr>
			<th colspan="5" bgcolor="#FFFFFF" width="100%" align="left"><font face="arial" size="1"><i>Annotazioni</i></font><br><? echo $dati['note']; ?></th>
		</tr>
		<tr>
			<th colspan="2" bgcolor="#FFFFFF" width="50%" align="center">Imponibile  <i><? echo $dati['totimpo']; ?></i></th>
			<th colspan="1" bgcolor="#FFFFFF" width="50%" align="center">Imposta  <i><? echo $dati['totiva']; ?></i></th>
			<th colspan="2" bgcolor="#FFFFFF" width="50%" align="center">Tot. Documento  <i><? echo $dati['totdoc']; ?></i></th>
	</table>

</body>

</html>
<?php
echo "<center><A HREF=\"#\" onClick=\"history.back()\">Torna indietro</A>";
?>
</table>
</html>