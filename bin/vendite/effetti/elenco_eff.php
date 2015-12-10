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
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);



//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

    echo "<span class=\"testo_blu\"><center><br><b>Elenco effetti inseriti</b></center><br>";
    echo "<center><a href=\"elenco_eff_st.php\" target=\"_blank\">Per stampare questa pagina premi qui.. !</a></center>\n";
    echo "1 - Rimessa diretta - 2 - Contanti - 3 - Ricevuta bancaria - 4 - Tratta o cambiale - 5 - Contrassegno - 6 - Bonifico Bancario - 7 - Ricevimento Fattura </span> <br>";


// Stringa contenente la query di ricerca...

    $query = "select * from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where tipoeff='3' AND status !='saldato' AND presenta = 'NO' order by annoeff, scadeff ASC";

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
    echo "<table align=\"center\">";
    echo "<tr>";

    echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Tipo</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Fatt.</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Pagamento</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Scadenza</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {
        echo "<tr>";
        printf("<form action=\"visualizzadoc.php?anno=$dati[annoeff]\" method=\"POST\">");
        printf("<td width=\"30\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['tipoeff']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datadoc']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['numdoc']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['modpag']);
        printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
        printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['impeff']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['scadeff']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['status']);
        printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"submit\" name=\"ndoc\" value=\"%s\"></td>", $dati['numeff']);

        echo "</form></tr>";
        echo "<tr>";
        echo "<td width=\"30\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "</tr>";
        $_ndocp = $dati['ndoc'] + 1;
        $_somma = $_somma + $dati['impeff'];
    }

    echo "<tr><td colspan=\"9\" align=\"center\">Totale effetti disponibili $_somma</td></tr>\n";
    echo "</table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>