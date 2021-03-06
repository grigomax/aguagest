<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
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



if ($_SESSION['user']['magazzino'] > "1")
{
    $_anno = ('Y');
    echo "<span class=\"testo_blu\"><center><br><b>Elenco d.d.t. sospesi</b></center></span><br>";


   $query = "select anno, suffix, ndoc, ragsoc, utente, ddtfornitore, fatturacq, datareg, SUM(valoreacq) AS valoreacq, status from magazzino INNER JOIN fornitori ON magazzino.utente=fornitori.codice where tdoc='ddtacq' and (fatturacq = '' OR valoreacq = '' OR protoiva = '') GROUP BY ndoc, anno ORDER BY ragsoc, ndoc";
// Esegue la query...

    $result = domanda_db("query", $query, $_cosa, $_ritorno, $_parametri);

    // Tutto procede a meraviglia...
    echo "<table align=\"center\">";
    echo "<tr>";

    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Anno</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Bolla</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">P. Iva</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {
        // tolgo i numeri di documento doppi
        if ($dati['ndoc'] != $_ndoc)
        {
            $_ndoc = $dati['ndoc'];
            echo "<tr>";
            echo "<form action=\"../../../vendite/docubase/visualizzadoc.php?tdoc=ddtacq\" method=\"POST\">";
            echo "<td align=\"left\"><span class=\"testo_blu\">$dati[anno]</span></td>\n";
            echo "<td align=\"left\"><span class=\"testo_blu\">$dati[ragsoc]</span></td>\n";
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['ddtfornitore']);
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['fatturacq']);
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['valoreacq']);
            echo "<td align=\"left\"><span class=\"testo_blu\">$dati[protoiva]</span></td>\n";
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['status']);
            printf("<td height=\"1\" align=\"center\" class=\"testo_blu\"><button type=\"submit\" name=\"annondoc\" value=\"%s%s%s\">$dati[ndoc]</button>/$dati[suffix]\n", $dati['anno'], $dati['suffix'], $dati['ndoc']);
            echo "</tr></form>";
            echo "<tr>";

            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";

            echo "</tr>";
        }
    }


    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>