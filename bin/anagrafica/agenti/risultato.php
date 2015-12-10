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

    $_campi = $_POST['campi'];
    $_descrizione = $_POST['descrizione'];
    echo "<table width=\"100%\">\n";
    echo "<tr><td align=\"center\" width=\"80%\" valign=\"top\">\n";

    echo "<span class=\"testo_blu\"><b>Risulati ricerca</b></span>";

    // Stringa contenente la query di ricerca...

    $_parametri['descrizione'] = $_POST['descrizione'];
    $_parametri['campi'] = $_POST['campi'];

    $result = tabella_agenti("ricerca", $_codage, $_parametri);

    // Tutto procede a meraviglia...
    echo "<table width=\"700\">";
    echo "<tr>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
    echo "<td width=\"280\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragiose sociale</span></td>";
    echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Citta</span></td>";
    echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Telefono</span></td>";
    echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Cellulare</span></td>";
    echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fax</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {
        echo "<tr>";
        printf("<td width=\"70\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['codice']);
        printf("<td width=\"280\" align=\"left\"><span class=\"testo_blu\"><a href=\"visualizzaage.php?codice=%s\">%s</span></td>", $dati['codice'], $dati['ragsoc']);
        printf("<td width=\"200\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['indirizzo']);
        printf("<td width=\"150\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['telefono']);
        printf("<td width=\"150\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['cell']);
        printf("<td width=\"150\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['fax']);
        echo "</tr>";
        echo "<tr>";
        echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"280\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"200\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "</tr>";
    }

    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>