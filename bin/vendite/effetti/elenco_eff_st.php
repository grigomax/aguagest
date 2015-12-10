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

echo "<link rel=\"stylesheet\" href=\"../../css/globalest.css\" type=\"text/css\">";

$query = "select * from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status = 'inserito' order by annoeff, scadeff ASC";

// Esegue la query...
if ($res = mysql_query($query, $conn))
{
    // La query ?stata eseguita con successo...
    // MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
    if (mysql_num_rows($res))
    {
        // Tutto procede a meraviglia...
        echo "<table align=\"center\">";
        echo "<tr><td colspan=\"6\" align=\"center\"><b>Elenco effetti inseriti</b></td></tr>\n";
        echo "<tr>";
        echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Fatt.</span></td>";
        echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
        echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
        echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
        echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Scadenza</span></td>";
        echo "</tr>";

        while ($dati = mysql_fetch_array($res))
        {
            echo "<tr>";
            printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datadoc']);
            printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['numdoc']);
            printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
            printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['impeff']);
            printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['scadeff']);

            echo "</tr>";
            echo "<tr>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "</tr>";
            $_ndocp = $dati['ndoc'] + 1;
            $_somma = $_somma + $dati['impeff'];
        }
    }
}

echo "<tr><td colspan=\"6\" align=\"center\">Totale effetti disponibili $_somma</td></tr>\n";
echo "</table></body></html>";
?>