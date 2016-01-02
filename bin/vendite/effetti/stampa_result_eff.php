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
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);


//carichiamo la testata del programma.
#testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{
    $_parametri['descrizione'] = $_POST['descrizione'];
    $_parametri['campi'] = $_POST['campi'];
    $_parametri['sospesi'] = $_POST['sospesi'];

 
    $result = tabella_effetti("ricerca_eff", $_percorso, $_annoeff, $_numeff, $_parametri);

    echo "<table class=\"elenco\" align=\"left\" width=\"95\">";
    echo "<tr><td colspan=\"8\">\n";
    echo "<h2 align=\"center\">Stampa scadenziario effetti </h2>\n";
    echo "1 - Rimessa diretta - 2 - Contanti - 3 - Ricevuta bancaria - 4 - Tratta o cambiale - 5 - Contrassegno - 6 - Bonifico Bancario - 7 - Ricevimento Fattura </span> <br>";
    echo "<hr>\n";
    echo "</td></tr>\n";

    // Tutto procede a meraviglia...

    echo "<tr>";
    echo "<td width=\"30\" align=\"center\" class=\"tabella\">Tipo</td>";
    echo "<td width=\"100\" align=\"center\" class=\"tabella\">Data Fatt.</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"tabella\">Fattura</span></td>";
    echo "<td width=\"450\" align=\"left\" class=\"tabella\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"tabella\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"tabella\">Scadenza</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"tabella\">Status</span></td>";
    echo "<td width=\"70\" align=\"center\" class=\"tabella\">N. Eff.</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {

        echo "<tr>";
        printf("<td align=\"center\">%s</span></td>", $dati['tipoeff']);
        printf("<td align=\"center\">%s</span></td>", $dati['datadoc']);
        printf("<td align=\"center\"><b>%s</b></span></td>", $dati['numdoc']);
        printf("<td align=\"left\">%s</span></td>", $dati['ragsoc']);
        printf("<td align=\"center\">%s</span></td>", $dati['impeff']);
        printf("<td align=\"center\">%s</span></td>", $dati['scadeff']);
        printf("<td align=\"center\">%s</span></td>", $dati['status']);
        echo " <td height=\"1\" align=\"center\" >$dati[numeff]</td>\n";

        echo "</tr>";
        echo "<tr>";
        echo "<td height=\"1\" align=\"center\"></td>";
        echo "<td height=\"1\" align=\"center\"></td>";
        echo "<td height=\"1\" align=\"center\"></td>";
        echo "<td height=\"1\" align=\"center\"></td>";
        echo "<td height=\"1\" align=\"center\"></td>";
        echo "<td height=\"1\" align=\"center\"></td>";
        echo "<td height=\"1\" align=\"center\"></td>";
        echo "<td height=\"1\" align=\"center\"></td>";
        echo "</tr>";

        if ($dati['status'] != "saldato")
        {
            $_imptot = $_imptot + $dati['impeff'];
        }
    }
    echo "<tr>";
    echo "<td colspan=\"8\"><hr></td></tr>\n";
    echo "<tr>";
    echo "<td height=\"1\" align=\"center\"></td>";
    echo "<td height=\"1\" align=\"center\"></td>";
    echo "<td height=\"1\" align=\"center\"></td>";
    echo "<td height=\"1\" align=\"right\">Totale saldato escluso =></td>";
    echo "<td height=\"1\" align=\"center\">$_imptot</td>";
    echo "<td height=\"1\" align=\"center\"></td>";
    echo "<td height=\"1\" align=\"center\"></td>";
    echo "<td height=\"1\" align=\"center\"></td>";
    echo "</tr>";




    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>