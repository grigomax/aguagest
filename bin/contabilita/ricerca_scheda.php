<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso . "../setting/vars.php";
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../librerie/motore_primanota.php";

if ($_POST['codconto'] == "")
{
	if ($_POST['tipo_cf'] != "")
	{
		echo Showcodconto();
		die;
	}
}
//carichiamo la base delle pagine:
base_html("", $_percorso);

java_script($_cosa, $_percorso);

jquery_menu_cascata("scheda", $_percorso);

jquery_datapicker($_cosa, $_percorso);
echo "</head>\n";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{



	$_day = date('d');
	$_mese = date('m');
	$_anno = date('Y');



	echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\" border=\"0\">\n";
	echo "<tr>\n";

	echo "<form action=\"result_scheda.php\" method=\"POST\">\n";
	echo "<td width=\"80%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

	echo "<table width=\"80%\" border=\"0\"\n";

	echo "<tr><td align=\"center\"><h3>Scegliere il conto da visualizzare</h3></td></tr>\n";
	echo "<form action=\"result_scheda.php\" id=\"myform\" method=\"POST\">\n";
//echo "<tr><td align=\"center\"><br>Inserisci direttamente il conto => </td></tr>\n";
	echo "<tr><td align=\"center\"><br>Diretto <input type=\"text\" autofocus name=\"diretto\" size=\"11\" maxlength=\"10\"> oppure <select id=\"tipo_conto\" name=\"tipo_cf\">" . Showtipo_conto() . "</select></td></tr>\n";
	echo "<tr><td align=\"center\"><br><select id=\"codconto\" name=\"codconto\"><option>Scegli...</option></select></tr>\n";

	echo "<tr><td align=\"center\"><br>Dalla data\n";
	echo "<input type=\"text\" class=\"data\" name=\"start\" value=\"01-01-$_anno\" size=\"11\" maxlength=\"10\">\n";

	echo "&nbsp;  Alla data\n";
	echo "<input type=\"text\" class=\"data\" name=\"end\" value=\"$_day-$_mese-$_anno\" size=\"11\" maxlength=\"10\">\n";

	echo " </td></tr>\n";


	echo "<tr><td align=\"center\"><br><input type=\"submit\" value=\"Cerca !\"></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";

	echo "</table>\n";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>