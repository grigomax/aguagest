<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso ."../setting/vars.php";
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



if ($_SESSION['user']['contabilita'] > "1")
{
// Creiamo due arre per le tabelle

    $natcon['A'] = "A - Attivit&agrave;";
    $natcon['P'] = "P - Passivit&agrave;";
    $natcon['C'] = "C - Costo";
    $natcon['R'] = "R - Ricavo";
    $natcon['O'] = "O - Conto ordine";

    $tipo_cf['C'] = "C - Cliente";
    $tipo_cf['F'] = "F - Fornitore";
    $tipo_cf['B'] = "B - Banche";
    $tipo_cf['A'] = "A - Altro";


    echo "<table width=\"100%\" align=\"center\" border=\"0\" valign=\"TOP\">";
    echo "<tr>\n";

    $_azione = $_GET['azione'];
    $_conto = $_GET['conto'];

    if ($_azione == "Modifica")
    {
	$query = "SELECT * FROM piano_conti WHERE codconto=$_conto";
	$res = mysql_query($query, $conn);
	$dati = mysql_fetch_array($res);
	$_submit = "Aggiorna";
    }
    else
    {
	$_submit = "Inserisci";
    }

//dobbiamo dividere il mastro dal conto

    if ($dati[livello] > "1")
    {
	$_livello = $dati['livello'];
	if ($_livello == "3")
	{
	    $_livello = "2";
	}
	//leggiamo dall'archivio il tipo di livello.
	$_mastro = substr($dati['codconto'], '0', $_livello);
	$_conto = substr($dati['codconto'], '2', '4');
    }
    else
    {
	$_mastro = $dati['codconto'];
	$_conto = "";
    }
    echo "<td align=\"center\" width=\"100%\">";

// Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"100%\" cellspacing=\"0\" align=\"center\" border=\"0\" cellpadding=\"4\">\n";
    echo "<tr>";
    echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";


    echo "<span class=\"testo_blu\"><br><b>Gestione Piano dei conti</b></span><br>";

    echo "<form action=\"risinse_pconti.php\" method=\"POST\">";
    echo "<table width=\"80%\" border=\"1\" align=\"center\">";

    if ($_azione == "Modifica")
    {
//mastro
	echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Mastro:&nbsp;</b></span></td>\n";
	echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"mastro\" value=\"$_mastro\" checked>$_mastro</td><tr>\n";

//conto
	echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Conto:&nbsp;</b></span></td>\n";
	echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"conto\" value=\"$_conto\" checked>$_conto</td><tr>\n";
    }
    else
    {
//mastro
	echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Mastro:&nbsp;</b></span></td>\n";
	echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" size=\"3\" maxlength=\"2\" name=\"mastro\" value=\"\" ><br><font size=\"2\">Codice mastro massimo due caratteri</td><tr>\n";

//conto
	echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Conto:&nbsp;</b></span></td>\n";
	echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" size=\"7\" maxlength=\"6\" name=\"conto\" value=\"\" ><br><font size=\"2\">Codice conto massimo 6 caratteri<BR>Lasciare in bianco per creare un mastro</td><tr>\n";
    }

// CAMPO descrizione---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Descrizione:&nbsp;</b></span></td>";
    echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"descrizione\" size=\"51\" maxlength=\"50\" value=\"$dati[descrizione]\"></td></tr>\n";


//campo natura conto..
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Natura Conto : &nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    echo "<select name=\"natcon\">\n";
    printf("<option value=\"%s\"> %s </option>", $dati['natcon'], $natcon[$dati['natcon']]);
    echo "<option value=\"A\"> A - Attivit&agrave;</option>\n";
    echo "<option value=\"P\"> P - Passivit&agrave; </option>\n";
    echo "<option value=\"C\"> C - Costo</option>\n";
    echo "<option value=\"R\"> R - Ricavo </option>\n";
    echo "<option value=\"O\"> O - Conto d'ordine </option>\n";
    echo " </select>";

    //campo natura conto..
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Tipo Mastro : &nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    echo "<select name=\"tipo_cf\">\n";
    printf("<option value=\"%s\"> %s </option>", $dati['tipo_cf'], $tipo_cf[$dati['tipo_cf']]);
    echo "<option value=\"A\"> A - Altro</option>\n";
    echo "<option value=\"B\"> B - Banche</option>\n";
    echo "<option value=\"C\"> C - Cliente</option>\n";
    echo "<option value=\"F\"> F - Fornitore </option>\n";
    echo " </select>";

// CAMPO esclusione primo mese ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Codice Conto CEE&nbsp;</span></td>";
    echo "<td><input type=\"text\" name=\"cod_cee\"  size=\"11\" maxlength=\"11\" value=\"$dati[cod_cee]\"><br><font size=\"2\">Codice Conto piano CEE</td></tr>";



// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "</table>\n<br><input type=\"submit\" name=\"azione\" value=\"$_submit\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Elimina\" onclick=\"if(!confirm('Sicuro di voler eliminare la il conto ?')) return false;\" >\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
// ************************************************************************************** -->
    echo "</table>\n";
// Fine tabella pagina princ
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>