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
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require $_percorso . "librerie/motore_anagrafiche.php";
require $_percorso . "librerie/motore_primanota.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

//funzione elenco mastri..
//funzione elenco conti

if ($_SESSION['user']['setting'] > "3")
{

// mi prendo i paametri passati
	@include "../../../setting/par_conta.inc.php";

//passiamo tutto la query all'array per poi poterla riselezionare..

	$query1 = "select * from piano_conti WHERE livello = '1' order by descrizione";
	$res_1 = mysql_query($query1, $conn);

	$query2 = "select * from piano_conti WHERE livello = '2' order by descrizione";
	$res_2 = mysql_query($query2, $conn);


	echo "<div style=\"text-align: center;\"><span style=\"font-weight: bold;\">Configurazione parametri base Contabilit&agrave;</span><br><br>\n";
	echo "Inserire Il conto dal piano dei conti relativo al paramentro richiesto <br>La non osservanza potrebbe compromettere l'uso del programma.<br><br>\n";
	echo "<form action=\"salva_para.php\" method=\"POST\">\n";
	echo "<table class=\"classic_bordo\">\n";
	echo "<tbody><tr>\n";
	echo "<td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Automatismi contabili</span></td>\n";
	echo "</tr><tr>\n";
//-------------------------------
//--------------------------------
	if ($MASTRO_CLI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $MASTRO_CLI);
	}

	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Mastro associato clienti</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\">\n";
	
	$_parametri['name']= "MASTRO_CLI";
	$_parametri['conto'] = $MASTRO_CLI;
	$_parametri['descrizione'] = $DESCRIZIONE_CAMPO;
	tabella_piano_conti("elenco_select_mastro", $_codconto, $_parametri);

	echo "</tr><tr>\n";

//--------------------------------
	if ($CONTO_CLIENTI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_CLIENTI, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto standard associato clienti</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\">\n";
	
	$_parametri['name']= "CONTO_CLIENTI";
	$_parametri['conto'] = $CONTO_CLIENTI;
	$_parametri['descrizione'] = $DESCRIZIONE_CAMPO;
	tabella_piano_conti("elenco_select_conto", $_codconto, $_parametri);
	$DESCRIZIONE_CAMPO = "";
	echo "</tr><tr>\n";
	

//--------------------------------
	if ($MASTRO_FOR != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $MASTRO_FOR, "descsingola");
	}
//-------------------------------

	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Mastro associato fornitori</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"MASTRO_FOR\">\n";
	echo "<option value=\"$MASTRO_FOR\">$DESCRIZIONE_CAMPO - $MASTRO_FOR</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_1 = mysql_query($query1, $conn);
	while ($dati = mysql_fetch_array($res_1))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

//--------------------------------
	if ($CONTO_FORNITORI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_FORNITORI, "descsingola");
	}
//----------------------------
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto standard associato fornitori</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_FORNITORI\">\n";
	echo "<option value=\"$CONTO_FORNITORI\">$DESCRIZIONE_CAMPO - $CONTO_FORNITORI</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

//--------------------------------
	if ($MASTRO_BANCHE != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $MASTRO_BANCHE, "descsingola");
	}
//-----------------------------
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Mastro associato alle banche</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"MASTRO_BANCHE\">\n";
	echo "<option value=\"$MASTRO_BANCHE\">$DESCRIZIONE_CAMPO - $MASTRO_BANCHE</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_1 = mysql_query($query1, $conn);
	while ($dati = mysql_fetch_array($res_1))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------

	//--------------------------------
	if ($CONTO_SPESE_BANCARIE != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_SPESE_BANCARIE, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Spese Bancarie</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_SPESE_BANCARIE\">\n";
	echo "<option value=\"$CONTO_SPESE_BANCARIE\">$DESCRIZIONE_CAMPO - $CONTO_SPESE_BANCARIE</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------
	
//--------------------------------
	if ($CONTO_CASSA != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_CASSA, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Cassa Contanti</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_CASSA\">\n";
	echo "<option value=\"$CONTO_CASSA\">$DESCRIZIONE_CAMPO - $CONTO_CASSA</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------
//--------------------------------
	if ($CONTO_ASSEGNI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_ASSEGNI, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Cassa Assegni</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_ASSEGNI\">\n";
	echo "<option value=\"$CONTO_ASSEGNI\">$DESCRIZIONE_CAMPO - $CONTO_ASSEGNI</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

	
	// conto compensazioni..
	
	//--------------------------------
	if ($CONTO_COMPENSAZIONI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_COMPENSAZIONI, "");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto compensazioni clienti fornitori</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\">\n";
	
	$_parametri['name']= "CONTO_COMPENSAZIONI";
	$_parametri['conto'] = $CONTO_COMPENSAZIONI;
	$_parametri['descrizione'] = $DESCRIZIONE_CAMPO;
	tabella_piano_conti("elenco_select_conto", "", $_parametri);
	$DESCRIZIONE_CAMPO = "";
	echo "</tr><tr>\n";
	
	
	
	
//--------------------------------
	if ($REC_TRASPORTO != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $REC_TRASPORTO, "descsingola");
	}

	echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
//*************************************************
	echo "<td colspan=\"2\" rowspan=\"1\" style=\"width: 241px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Recupero spese Di vendita</span></td>\n";
	echo "</tr><tr>\n";
//----------------------------
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Recupero spese di trasporto</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"REC_TRASPORTO\">\n";
	echo "<option value=\"$REC_TRASPORTO\">$DESCRIZIONE_CAMPO - $REC_TRASPORTO</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------
//--------------------------------
	if ($REC_IMBALLI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $REC_IMBALLI, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Recupero spese Imballi</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"REC_IMBALLI\">\n";
	echo "<option value=\"$REC_IMBALLI\">$DESCRIZIONE_CAMPO - $REC_IMBALLI</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

//--------------------------------
	if ($REC_SPESE_VARIE != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $REC_SPESE_VARIE, "descsingola");
	}
//----------------------------
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Recupero spese spese varie</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"REC_SPESE_VARIE\">\n";
	echo "<option value=\"$REC_SPESE_VARIE\">$DESCRIZIONE_CAMPO - $REC_SPESE_VARIE</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------
//--------------------------------
	if ($REC_SPESE_BANCARIE != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $REC_SPESE_BANCARIE, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Recupero spese bancarie</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"REC_SPESE_BANCARIE\">\n";
	echo "<option value=\"$REC_SPESE_BANCARIE\">$DESCRIZIONE_CAMPO - $REC_SPESE_BANCARIE</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------
//--------------------------------
	if ($CONTO_SCONTI_FINALI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_SCONTI_FINALI, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Sconti ed abbuoni in fattura</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_SCONTI_FINALI\">\n";
	echo "<option value=\"$CONTO_SCONTI_FINALI\">$DESCRIZIONE_CAMPO - $CONTO_SCONTI_FINALI</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";

// scelta del tipo di intestazione
	
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------
//
	echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
	echo "<td colspan=\"2\" rowspan=\"1\" style=\"width: 241px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Conti Effetti</span></td></tr>\n";
//--------------------------------
	if ($MASTRO_EFFETTI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $MASTRO_EFFETTI, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Mastro Effetti</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"MASTRO_EFFETTI\">\n";
	echo "<option value=\"$MASTRO_EFFETTI\">$DESCRIZIONE_CAMPO - $MASTRO_EFFETTI</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_1 = mysql_query($query1, $conn);
	while ($dati = mysql_fetch_array($res_1))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------------
//--------------------------------
	if ($CONTO_EFFETTI_ATTIVI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_EFFETTI_ATTIVI, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Effetti Attivi</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_EFFETTI_ATTIVI\">\n";
	echo "<option value=\"$CONTO_EFFETTI_ATTIVI\">$DESCRIZIONE_CAMPO - $CONTO_EFFETTI_ATTIVI</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------
//
//--------------------------------
	if ($CONTO_EFFETTI_SBF != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_EFFETTI_SBF, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Effetti SBF (saldo buon fine)</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_EFFETTI_SBF\">\n";
	echo "<option value=\"$CONTO_EFFETTI_SBF\">$DESCRIZIONE_CAMPO -$CONTO_EFFETTI_SBF</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

//----------------------------
//--------------------------------
	if ($CONTO_EFFETTI_INCASSO != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_EFFETTI_INCASSO, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Effetti all'incasso (dopo maturazione) </td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_EFFETTI_INCASSO\">\n";
	echo "<option value=\"$CONTO_EFFETTI_INCASSO\">$DESCRIZIONE_CAMPO - $CONTO_EFFETTI_INCASSO</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";

	$DESCRIZIONE_CAMPO = "";
//----------------------------
	if ($CONTO_EFFETTI_INSOLUTI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_EFFETTI_INSOLUTI, "descsingola");
	}

//----------------------------
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Effetti Insoluti</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_EFFETTI_INSOLUTI\">\n";
	echo "<option value=\"$CONTO_EFFETTI_INSOLUTI\">$DESCRIZIONE_CAMPO - $CONTO_EFFETTI_INSOLUTI</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";

	$DESCRIZIONE_CAMPO = "";
//----------------------------
	if ($CONTO_SPESE_INSOLUTI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_SPESE_INSOLUTI, "descsingola");
	}
//----------------------------
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto recupero Spese Insoluti</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_SPESE_INSOLUTI\">\n";
	echo "<option value=\"$CONTO_SPESE_INSOLUTI\">$DESCRIZIONE_CAMPO - $CONTO_SPESE_INSOLUTI</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";


// scelta del tipo di intestazione
	echo "<td colspan=\"2\" rowspan=\"1\" style=\"width: 241px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Conti IVA</span></td>\n";
	echo "</tr><tr>\n";
	echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
//----------------------------
	if ($CONTO_IVA_ACQUISTI != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_IVA_ACQUISTI, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Iva Acquisti</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_IVA_ACQUISTI\">\n";
	echo "<option value=\"$CONTO_IVA_ACQUISTI\">$DESCRIZIONE_CAMPO - $CONTO_IVA_ACQUISTI</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";
//----------------------------
	if ($CONTO_IVA_VENDITE != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_IVA_VENDITE, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Iva Vendite</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_IVA_VENDITE\">\n";
	echo "<option value=\"$CONTO_IVA_VENDITE\">$DESCRIZIONE_CAMPO - $CONTO_IVA_VENDITE</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

//----------------------------
	if ($CONTO_IVA_ERARIO != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $CONTO_IVA_ERARIO, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Conto Iva Erario per liquidazioni</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"CONTO_IVA_ERARIO\">\n";
	echo "<option value=\"$CONTO_IVA_ERARIO\">$DESCRIZIONE_CAMPO - $CONTO_IVA_ERARIO</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr>\n";
	$DESCRIZIONE_CAMPO = "";

	echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

// scelta del tipo di intestazione
	echo "<tr><td colspan=\"2\" rowspan=\"1\" style=\"width: 241px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Automatismi contabili fine anno</span></td>\n";
	echo "</tr><tr>\n";

//----------------------------
	if ($PROFITTI_PERDITE != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $PROFITTI_PERDITE, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Profitti e Perdite</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"PROFITTI_PERDITE\">\n";
	echo "<option value=\"$PROFITTI_PERDITE\">$DESCRIZIONE_CAMPO - $PROFITTI_PERDITE</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

//----------------------------
	if ($UTILE_ESERCIZIO != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $UTILE_ESERCIZIO, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Utile d'Esercizio</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"UTILE_ESERCIZIO\">\n";
	echo "<option value=\"$UTILE_ESERCIZIO\">$DESCRIZIONE_CAMPO - $UTILE_ESERCIZIO</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

//----------------------------
	if ($PERDITA_ESERCIZIO != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $PERDITA_ESERCIZIO, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Perdita d'Esercizio</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"PERDITA_ESERCIZIO\">\n";
	echo "<option value=\"$PERDITA_ESERCIZIO\">$DESCRIZIONE_CAMPO - $PERDITA_ESERCIZIO</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

//----------------------------
	if ($BILANCIO_CHIUSURA != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $BILANCIO_CHIUSURA, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Bilancio di Chiusura</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"BILANCIO_CHIUSURA\">\n";
	echo "<option value=\"$BILANCIO_CHIUSURA\">$DESCRIZIONE_CAMPO - $BILANCIO_CHIUSURA</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

//----------------------------
	if ($BILANCIO_APERTURA != "")
	{
		$DESCRIZIONE_CAMPO = tabella_piano_conti("descsingola", $BILANCIO_APERTURA, "descsingola");
	}
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Bilancio d'Apertura</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><select name=\"BILANCIO_APERTURA\">\n";
	echo "<option value=\"$BILANCIO_APERTURA\">$DESCRIZIONE_CAMPO - $BILANCIO_APERTURA</option>";
	echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	$res_2 = mysql_query($query2, $conn);
	while ($dati = mysql_fetch_array($res_2))
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
	}
	echo "</select>\n";
	echo "</tr><tr>\n";
	$DESCRIZIONE_CAMPO = "";

	echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

	echo "<tr><td colspan=\"2\" rowspan=\"1\" style=\"width: 241px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Varie ed eventuali</span></td>\n";
	echo "</tr><tr>\n";

	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Codice Attività</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\"><input type=\"text\" name=\"CODATTIVITA\" value=\"$CODATTIVITA\" size=\"7\"  maxlength=\"6\">\n";
	echo "</td></tr>\n";
	echo "<tr>\n";
	echo "<td style=\"width: 40%;\" align=\"center\" valign=\"top\">Abilitare la Gestione dello spesometro ?</td>\n";
	echo "<td style=\"width: 60%; text-align: left;\" valign=\"top\">\n";

	if ($SPESOMETRO == "SI")
	{
		echo "SI - <input type=\"radio\" name=\"SPESOMETRO\" value=\"SI\" checked> - - - NO <input type=\"radio\" name=\"SPESOMETRO\" value=\"NO\">\n";
	}
	else
	{
		echo "SI - <input type=\"radio\" name=\"SPESOMETRO\" value=\"SI\"> - - - NO <input type=\"radio\" name=\"SPESOMETRO\" value=\"NO\" checked>\n";
	}
	echo "</td></tr>\n";
	echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
	echo "<tr><td colspan=2 align=center><input type=\"submit\" name=\"azione\" value=\"Salva\"></td></tr>\n";
	echo "</tr></tbody></table></div>\n";

	echo "</body></html>\n";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>