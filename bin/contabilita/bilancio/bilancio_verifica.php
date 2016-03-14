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
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../../../setting/par_conta.inc.php";

//prendiamo i post dalla pagina precedente..

$_start = substr($_GET['azione'], "0", "10");
$_end = substr($_GET['azione'], "10", "10");

function intest($_end, $_pg, $pagina)
{
    echo "<html>";
    echo "<link rel=\"stylesheet\" href=\"../../css/globalest.css\" type=\"text/css\">";

    echo "<body>";
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
    echo "<tr>";
    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
    include "../../../setting/vars.php";
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\" style=\"page-break-inside: avoid;\">";
    echo "<tr><td colspan=\"2\">\n";
    echo "<h4>$azienda</h4>\n";
    echo "$indirizzo - $cap<br>\n";
    echo "$citta - $prov<br>\n";
    echo "Partita iva $piva e C.F. $codfisc<br>\n";
    echo "</td></tr>\n";
    echo "<h3>Bilancio di verifica al $_end Pagina $_pg di $pagina</h3>";

    echo "</td></tr></table>\n";
    echo "<table align=\"center\" width=\"80%\">\n";
    echo "<tr><td class=\"tabella\">conto</td><td class=\"tabella\">Descrizione</td><td class=\"tabella\">Saldo</td></tr> \n";
}

//echo "<h3>Stampa Bilancio <a href=\"bilancio_stampa.php?data_start=$_POST[data_start]&data_end=$_POST[data_end]\" target=\"_blanck\">Stampa Qui!</a></h3>";
#echo "<h4>La stampa in formato pdf verr&agrave; inviata in linea al Browser</h4>\n";
/* devo fare un query per i clienti
 * una per i fornitori una per il resto in ordine di categoria..
 *
 */

//Fissiamo il numero di righe per pagina...
$rpp = "45";

$query = "SELECT data_cont, desc_conto, conto, SUM( dare ) - SUM( avere ) AS saldo FROM prima_nota WHERE data_cont >= '$_start' AND data_cont <= '$_end' GROUP BY conto ORDER BY conto";

$result = domanda_db("query", $query, $_cosa, $_ritorno, $_parametri);

$righe = $result->rowCount();

//inserisco il numero di righe per pagina
$_pagine = $righe / $rpp;
//arrotondo per eccesso
$pagina = ceil($_pagine);


//creiamo una tabella dove mettere i dati dentro..

for ($_pg = 1; $_pg <= $pagina; $_pg++)
{

    intest($_end, $_pg, $pagina);


    for ($_nr = 1; $_nr <= $rpp; $_nr++)
    {
        $row = domanda_db("query", $query, $_cosa, "solo_fetch", $result);

        if ($row['saldo'] > "0.00")
        {
            $_scritta_p = "D";
            $_totale_D = $_totale_D + abs($row['saldo']);
        }
        else
        {
            $_scritta_p = "A";
            $_totale_A = $_totale_A + abs($row['saldo']);
        }

        echo "<tr><td class=\"tabella_elenco\">$row[conto]</td><td class=\"tabella_elenco\">$row[desc_conto]</td><td align=\"right\" class=\"tabella_elenco\">" . abs($row[saldo]) . " $_scritta_p</td></tr> \n";
    }


    echo "<tr><td colspan=\"3\"><hr></td></tr>\n";
    echo "</table>\n";
}


echo "<h4>Totali... </h4>";
echo "<h4>Totale Dare = ". number_format($_totale_D, $dec , '.', '') ."</h4>\n";
echo "<h4>Totale Avere = ". number_format($_totale_A, $dec , '.', '') ."</h4>\n";
$_sbilanciamento = $_totale_D - $_totale_A;


echo "<h3>Sbilanciamento = ". number_format($_sbilanciamento, $dec , '.', '') ."</h3>\n";
echo "</body></html>";
?>