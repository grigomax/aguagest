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

//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_percorso);



if ($_SESSION['user']['vendite'] > "1")
{

    intestazione_html($_cosa, $_percorso, $_parametri);

    $query = "select * from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status = 'inserito' order by annoeff, scadeff ASC";

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    
    // Tutto procede a meraviglia...
    echo "<table align=\"center\" width=\"100%\" >";
    echo "<tr><td colspan=\"6\" align=\"center\"><b>Elenco effetti inseriti</b></td></tr>\n";
    echo "<tr>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Fatt.</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Scadenza</span></td>";
    echo "</tr>";

    foreach ($result AS
            $dati)
    {
        echo "<tr>";
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datadoc']);
        echo "<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>$dati[numdoc] / $dati[suffixdoc]</b></span></td>\n";
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


    echo "<tr><td colspan=\"6\" align=\"center\">Totale effetti disponibili $_somma</td></tr>\n";
    echo "</table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>