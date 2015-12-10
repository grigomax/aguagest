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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);
require "../../librerie/motore_anagrafiche.php";
require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_primanota.php";

if ($_POST['utente'] == "")
{
	if ($_POST['causale'] != "")
	{
		echo Showutente();
		die;
	}
}

if ($_POST['causale'] == "")
{
	if ($_POST['utente'] != "")
	{
		echo Showndoc($_POST['causale']);
		die;
	}
}
//carichiamo la base delle pagine:
base_html("", $_percorso);

java_script($_cosa, "../../");

jquery_datapicker($_cosa, "../../");

jquery_menu_cascata("ST", $_percorso);

echo "</head>\n";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

//PRENDIAMOCI I GET
	$_azione = $_GET['azione'];


//inizio parte html

	if ($_azione == "FA")
	{

		$_titolo = "<input type=\"radio\" name=\"causale\" value=\"$_azione\" checked>Registrazione Fatture Acquisto / Nota Credito";
	}
	else
	{
		$_titolo = "Registrazione Prima Nota";
	}
	echo "<form id=\"myform\" action=\"corpo_nota.php\" method=\"POST\">\n";
	echo "<center>";
	echo "<h2>$_titolo</h2>\n";

	echo "<table width=\"90%\" align=\"center\" border=\"0\">\n";

	$_data = date('d-m-Y');

	echo "<tr><td align=\"center\">Data Registrazione  <input type=\"radio\" name=\"datareg\" value=\"$_data\" checked>$_data</td>\n";
	echo "<td align=\"\" >Data Contabile  <input type=\"text\" name=\"datagior\" class=\"data\" value=\"$_data\" size=\"10\" maxlength=\"10\" required></td></tr>\n";

	if ($_azione == "ST")
	{

		$_causale = $_POST['causale'];
		$_utente = $_POST['utente'];

		echo "<tr><td align=center colspan=\"2\"><br>Tipo di registrazione <br>Seleziona una causale contabile<br>&nbsp;";


		echo "<select id=\"causale\" name=\"causale\">" . Showcausale() . "</select><br>\n";
		echo "<select id=\"utente\" name=\"utente\"><option>Scegli...</option></select><br>\n";
		echo "<select id=\"ndoc\" name=\"ndoc\"><option>Scegli...</option></select>\n";
	}
	else
	{
		echo "<tr><td align=\"center\" colspan=\"1\"><br>Tipo documento<br>&nbsp;";
		echo "<select name=\"segno\">\n";
		echo "<option value=\"P\">P - Fattura Acquisto</option>\n";
		echo "<option value=\"N\">N - Nota Credito</option>\n";
		echo "</select></td>\n";

		echo "<td align=\"left\" colspan=\"1\"><br>Seleziona il fornitore<br>&nbsp;";
		
                tabella_fornitori("elenca_select", "utente", $_parametri);
                echo "<tr><td>&nbsp;</td><td align=\"left\">Oppure partita iva <input type=\"text\" name=\"piva\" size=\"14\" maxlength=\"11\"></td></tr>\n";

		echo "<tr><td colspan=\"2\" align=\"center\"><hr></td></tr>\n";
		echo "<tr><td align=\"center\"><br>Importo Documento comprensivo di iva</td><td align=\"center\"><br>Aliquota Iva di riferimento</td></tr>\n";
		echo "<tr><td valign=\"bottom\" align=\"center\">Euro = <input type=\"text\" name=\"totdoc\" size=\"10\" maxlength=\"10\" required></td>\n";
		echo "<td align=\"center\">Viene Proposta L'iva del sistema <br>se indetraibile scegliere l'aliquota di riferimento<br>\n";
                
                tabella_aliquota("elenca_select_2",$ivasis, "iva");

		echo "Di cui detraibile <input type=\"text\" name=\"iva_ded\" size=\"3\" maxlength=\"2\">%\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "<tr><td align=center colspan=2><br><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Nuova\">";
	echo "</td></tr>";
	echo "</form></table>";

	echo "</body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>