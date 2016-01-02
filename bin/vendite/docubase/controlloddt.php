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
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['vendite'] > "1")
{
    echo "<span class=\"testo_blu\"><center><br><b>Controllo numeri mancanti d.d.t.</b></center></span><br>";


// Stringa contenente la query di ricerca...
    $_anno = date('Y');

    $query = sprintf("select * from bv_bolle INNER JOIN clienti ON bv_bolle.utente = clienti.codice where anno=\"%s\" order by suffix, ndoc", $_anno);

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
//aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
        $_errori['errori'] = "NO";
    }

// Tutto procede a meraviglia...
    echo "<table align=\"center\" width=\"90%\">";
    echo "<tr>";

    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
    //echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {
        if ($_ndocv != $dati['ndoc'])
        {
            echo "<tr>";
            echo "<td colspan=7>Manca un numero prima di questo</td></tr><tr>";
            //printf("<form action=\"visualizzadoc.php?tdoc=ddt&anno=%s\" method=\"POST\">", $dati['anno']);
            printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
            printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s / $dati[suffix]</b></span></td>", $dati['ndoc']);
            printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
            printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['totdoc']);
            printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['status']);
            //printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"submit\" name=\"ndoc\" value=\"%s\"></td>", $dati['ndoc']);
            echo "</tr>";
            echo "<tr>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            //echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "</tr>";
        }
        $_ndocv = $dati['ndoc'] + 1;
    }


    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>