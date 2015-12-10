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

require $_percorso . "librerie/lib_html.php";
require "../../librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

    $_annorif = $_POST['annorif'];
    $_codiceage = $_POST['agente'];
    $_mese = $_POST['mese'];
    echo "<table width=\"100%\">\n";
    echo "<tr><td align=\"center\" width=\"80%\" valign=\"top\">\n";

    echo "<span class=\"testo_blu\"><h3>Risultato provvigioni agente $_codiceage Anno in corso</b><br><a href=\"risprovsta.php?annorif=$_annorif-$_mese%&codiceage=$_codiceage\" target=\"_blank\">Stampa questa pagina</a></h3></span>";

    // Stringa contenente la query di ricerca...

    $query = "select * from provvigioni INNER JOIN clienti ON provvigioni.utente=clienti.codice where codage='$_codiceage' and datareg LIKE '$_annorif-$_mese%' order by ndoc";



    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "motore_anagrafiche.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    // Tutto procede a meraviglia...
    echo "<table width=\"700\">";
    echo "<tr>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">N. Doc.</span></td>";
    echo "<td width=\"280\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Tipo Documento</span></td>";
    echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Data reg.</span></td>";
    echo "<td width=\"250\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Utente</span></td>";
    echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Valore Doc.</span></td>";
    echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Val. Riscosso</span></td>";
    echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Provv. netta</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {
        echo "<tr>";
        printf("<td width=\"70\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['ndoc']);
        printf("<td width=\"280\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['tdoc']);
        printf("<td width=\"200\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
        printf("<td width=\"250\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
        printf("<td width=\"150\" align=\"right\"><span class=\"testo_blu\">%s</span></td>", $dati['totdoc']);
        printf("<td width=\"150\" align=\"right\"><span class=\"testo_blu\">%s</span></td>", $dati['riscosso']);
        printf("<td width=\"150\" align=\"right\"><span class=\"testo_blu\">%s</span></td>", $dati['provvigioni']);
        echo "</tr>";
        $_totdoc = $_totdoc + $dati['totdoc'];
        $_totprovv = $_totprovv + $dati['provvigioni'];
        echo "<tr>";
        echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"280\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"200\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"250\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "</tr>";
    }

    echo "<tr>";
    echo "<td width=\"70\" align=\"center\" ></td>";
    echo "<td width=\"280\" align=\"center\" ></td>";
    echo "<td width=\"200\" align=\"center\" ></td>";
    echo "<td width=\"250\" align=\"center\" ></td>";
    echo "<td width=\"150\" align=\"right\" >$_totdoc</td>";
    echo "<td width=\"150\" align=\"center\" ></td>";
    echo "<td width=\"150\" align=\"right\" >$_totprovv</td>";
    echo "</tr>";
    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>