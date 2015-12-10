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
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_anagrafiche.php";
//carichiamo la base delle pagine:
base_html($_cosa, $_percorso);
java_script($_cosa, $_percorso);
jquery_datapicker($_cosa, $_percorso);

echo "</head>";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['contabilita'] > "1")
{


	$_azione = $_GET['azione'];


	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
	echo "<tr>";

	echo "<td>";

	$_datanuova = cambio_data("listamesi", $_data);

	echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";


	if ($_azione == "paga")
	{

		echo "<h3>Liquidazione iva periodica</h3>";
		echo "<h4>Scegliere il mese che si vuol pagare</h4>";

		$result = tabella_liquid_iva_periodica("elenco_aperte", $_anno, $_periodo, $_parametri);

		$_datanuova['13'] = "Acc. Dicembre";

//Ok apriamo una tabella con le ultimi conti..

		echo "<form action=\"liquid_iva_period2.php\" method=\"POST\">\n";
		echo "<table align=\"center\">\n";
		echo "<tr><td class=\"tabella\">Scegli</td><td class=\"tabella\">Anno</td><td class=\"tabella\">Periodo</td><td class=\"tabella\">Iva erario</td><td class=\"tabella\">Versata</td><td class=\"tabella\">Banca</td><td class=\"tabella\">Data</td><td class=\"tabella\">Cod. Tributo</td></tr>";

		foreach ($result AS $dati)
		{
			echo "<tr><td class=\"tabella_elenco\"><input type=\"radio\" name=\"periodo\" value=\"$dati[anno]-$dati[periodo]\"></td><td class=\"tabella_elenco\">$dati[anno]</td><td class=\"tabella_elenco\">" . $_datanuova[$dati[periodo]] . "</td><td class=\"tabella_elenco\">$dati[val_liquid]</td><td class=\"tabella_elenco\">$dati[versato]</td><td class=\"tabella_elenco\">$dati[banca]</td><td class=\"tabella_elenco\">$dati[data_vers]</td><td class=\"tabella_elenco\">$dati[cod_tributo]</td></tr>";
		}
		echo "</table>\n";


		echo "<h5>Scegliere la banca con il quale si &egrave; effettuato il pagamento oppure se è Altro</h5>\n";

		echo "<select name=\"banca\">\n";
		echo "<option value=\"\"></option>";
		echo "<option value=\"MC\">Muovimento chiusura</option>";
		echo "<option value=\"AV\">Avviso Bonario</option>";
		
		$result = tabella_banche("elenco", $_codice, $_abi, $_cab, $_parametri);

		foreach ($result AS $banca)
		{
			echo "<option value=\"$banca[codice]\">$banca[banca]</option>";
		}
		echo "</select><br>";
		$_hoy = date('d-m-Y');
		echo "<br>Inserisci la data per l'operazione <input type=\"text\" class=\"data\" name=\"datareg\" value=\"$_hoy\" size=\"11\" maxlength=\"10\">\n";
		echo "<br><br><input type=\"submit\" name=\"azione\" value=\"liquida\">\n";
	}
	else
	{
		echo "<h3>Liquidazione iva periodica</h3>\n";

		$_anno = date('Y');
		echo "<h5>Selezionare il mese che si intende elaborare tra quelli aperti..</h5>\n";
		echo "<h5>Il programma effettua una serie di conti e poi porta i dati in una nuova tabella<br>\n
    poi una volta che l'utente visiona la liquidazione<br>pu&ograve; decidere se confermare oppure no </h5>\n";

//$query = "SELECT date_format(data_cont, '%Y-%m') AS data_cont FROM prima_nota where liquid_iva != 'SI' AND conto='$CONTO_IVA_VENDITE' group by data_cont order by data_cont limit 12";
//$result = mysql_query($query, $conn) or mysql_error();
		echo "<form action=\"liquid_iva_period2.php\" method=\"POST\">\n";
//facciamo un select..
		echo "Scegliere l'anno <input type=\"number\" name=\"anno\" size=\"6\" value=\"$_anno\">&nbsp;\n";

		echo "<select name=\"periodo\">\n";
		echo "<option value=\"\"></option>";
		echo "<option value=\"01\">Gennaio</option>";
		echo "<option value=\"02\">Febbraio</option>";
		echo "<option value=\"03\">Marzo</option>";
		echo "<option value=\"04\">Aprile</option>";
		echo "<option value=\"05\">Maggio</option>";
		echo "<option value=\"06\">Giugno</option>";
		echo "<option value=\"07\">Luglio</option>";
		echo "<option value=\"08\">Agosto</option>";
		echo "<option value=\"09\">Settembre</option>";
		echo "<option value=\"10\">Ottobre</option>";
		echo "<option value=\"11\">Novembre</option>";
		echo "<option value=\"12\">Dicembre</option>";
		echo "<option value=\"13\">Acc. Dicembre</option>";

		echo "</select>&nbsp;\n";

		#echo "<br><br>\n";
		echo "<input type=\"submit\" value=\"Vai\">\n";
		echo "</form>\n";
		echo "</br>\n";
		echo "</td></tr></table></body></html>\n";
	}
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>