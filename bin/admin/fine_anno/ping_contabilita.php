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



if ($_SESSION['user']['setting'] > "3")
{

//verifichiamo subito una domanda..
    if ($_POST['conferma'] == "NO")
    {

	header("Location: " . "../../menu.php");
	exit;
    }

    $_anno = $_POST['anno'];


    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo "<td colspan=\"8\" align=\"center\" >\n";
    echo "<span class=\"intestazione\"><b>Programma per la chiusura e la eventuale <br>riapertura dell'esercizio di Contabilit&agrave;</b>
    <br>Si chiude sempre l'anno precedente, gli esercizi nuovi si aprono in automatico</span><br></td></tr>\n";

    if ($_POST['conferma'] == "SI")
    {

	echo "<tr><td align=center colspan=8><font color=\"red\">\n";
	echo "L'operazione pu&ograve; essere effettuata una volta sola, <b><blink>ed &egrave; irreversibile.</blink></b><br>\n";

	echo "<font size=\"3\"><b><blink>SI CONSIGLIA DI FARE LE COPIE DEGLI ARCHIVI PRIMA DI PROSEGUIRE...</blink></b></font></font><br><br>\n";

	echo "Verifica Quadratura registrazioni anno aperto.. $_anno <br>";



	$query = "SELECT SUM(dare) AS dare, SUM(avere) AS avere from prima_nota where anno='$_anno'";

	$result = mysql_query($query, $conn);

	$dati = mysql_fetch_array($result);

	$_sbilanciamento = $dati['dare'] - $dati['avere'];

	echo "<h4><font color=\"red\">Controllo sul totale.. $_sbilanciamento</font></h4>";


	if ($_sbilanciamento != "0")
	{
	    echo "<h4>Di seguito elencate le registrazioni fuori quadratura..</h4>\n";

	    $query = "SELECT *, (SUM(dare) - SUM(avere)) AS diff from prima_nota where anno='$_anno' GROUP BY nreg ORDER BY nreg";

	    $result = mysql_query($query, $conn);

//apriamo una tabella..

	    echo "<table border=\"1\" width=\"80%\" align=\"center\">\n";
	    echo "<td class=\"tabella\">N. Reg.</td>\n";
	    echo "<td class=\"tabella\">Data</td>\n";
	    echo "<td class=\"tabella\">Causale</td>\n";
	    echo "<td class=\"tabella\">Sbilancio</td></tr>\n";

	    while ($dati = mysql_fetch_array($result))
	    {
		if ($dati['diff'] != 0.00)
		{
		    printf("<td width=\"30\" align=\"center\" class=\"tabella_elenco\"><input type=\"submit\" name=\"nreg\" value=\"%s\"></span></td>\n", $dati['nreg']);
		    echo "<td class=\"tabella_elenco\">$dati[data_reg]</td>\n";
		    echo "<td class=\"tabella_elenco\">$dati[causale]</td>\n";
		    echo "<td class=\"tabella_elenco\">$dati[diff]</td></tr>\n";
		    echo "</form>\n";
		}
	    }

	    echo "</td>\n";
	    echo "</table>";
	}

	echo "<form action=\"pong_contabilita.php\" method=\"POST\">\n";
	echo "<tr><td colspan=\"8\" align=\"center\"><br><span class=\"intestazione\"><input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>ANNO DI ELABORAZIONE $_anno</td></tr>\n";

	echo "<tr><td colspan=\"8\" align=\"center\">Data Chiusura <input type=\"text\" name=\"data_end\" value=\"31-12-$_anno\" size=\"11\" maxlength=\"10\"></td></tr>\n";

	$_anno_nuovo = $_anno + 1;
	echo "<tr><td colspan=\"8\" align=\"center\">Data Apertura <input type=\"text\" name=\"data_start\" value=\"01-01-$_anno_nuovo\" size=\"11\" maxlength=\"10\"></td></tr>\n";

	echo "<tr><td colspan=\"8\" align=\"center\"><br>Scegliere la Prodedura..</td></tr>\n";
	echo "<tr><td colspan=\"8\" align=\"center\"><input type=\"radio\" name=\"procedura\" value=\"annulla\" checked> - Annulla operazione
        <br><input type=\"radio\" name=\"procedura\" value=\"chiudi\"> - Chiusura Esercizio
        <br><input type=\"radio\" name=\"procedura\" value=\"apri\"> - Chiusura e Riapertura
        </td></tr>\n";

	echo "<tr><td colspan=\"8\" align=\"center\"><br><input type=\"submit\" name=\"azione\" value=\"Esegui\"></form></td></tr>\n";


	echo "</table>\n";
	
    }
    else
    {
	echo "<form action=\"ping_contabilita.php\" method=\"POST\">";
	echo "<tr><td colspan=\"8\" align=\"center\"><span class=\"intestazione\"><marquee>ATTENZIONE... !! </marquee></td></tr>\n";
	echo "<tr><td colspan=\"8\" align=\"center\"><span class=\"intestazione\"><font color=\"red\">Sono state eseguite le registrazioni di rettifica inviate dal proprio commercialista ?</font></span></td></tr>\n";


	echo "<tr><td colspan=\"8\" align=\"center\"><br>Inserire anno di chiusura<input type=\"text\" name=\"anno\" size=\"5\" maxlength=\"4\"></td></tr>\n";

	echo "<tr><td colspan=\"8\" align=\"center\"><br><input type=\"submit\" name=\"conferma\" value=\"SI\"> - <input type=\"submit\" name=\"conferma\" value=\"NO\"></form></td></tr>\n";
    }




    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>