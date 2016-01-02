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

//carico le librerie necessarie all'utilizzo
require "../../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("", $_percorso);
java_script($_cosa, $_percorso);
jquery_seleziona_tot($_cosa, $_percorso);
echo "</head>\n";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{


// imposto la sessione della banca e della data
//Recupero l'anno'
    $_anno = cambio_data("anno_it", $_POST['date']);

    $_SESSION['date'] = cambio_data("us", $_POST['date']);

    // prendiamoci i dati della banca e li mettiamo in sessione
    $_SESSION['banca'] = tabella_banche("singola", $_POST['banca'], $_abi, $_cab, $_parametri);


// elenca le fatture presenti in archivio non evasi solo ricevuta bancaria
    echo "<span class=\"testo_blu\"><center><br><b>Banca di presentazione " . $_SESSION['banca']['banca'] . "</b></center><br></span>";
// elenco documenti fatture vendita
    echo "<span class=\"testo_blu\"><center><br><b>Selezionare gli effetti da presentare</b></center><br></span>";
    //richiamiamo gli effetti
    $result = tabella_effetti("elenco_effetti_liberi", $_percorso, $_annoeff, $_numeff, $_parametri);

    echo "<form action=\"crea_dist.php\" method=\"POST\">\n";
    echo "<table align=\"center\" border=\"0\" width=\"90%\">";
    echo "<tr>";

    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Fatt.</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Pagamento</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Scadenza</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero eff.</span></td>";
    echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Seleziona</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {
        echo "<tr>";
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datadoc']);
        echo "<td align=\"center\"><span class=\"testo_blu\"><b>$dati[numdoc] / $dati[suffixdoc]</b></span></td>\n";
        printf("<td align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['modpag']);
        printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['impeff']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['scadeff']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['numeff']);

        printf("<td align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"%s%s\" valore=\"%s\"></td>", $dati['annoeff'], $dati['numeff'], $dati['impeff']);

        echo "</tr>";
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

    echo "</td></tr>";
    echo "<tr><td colspan=5 align=\"center\" class=\"testo_blu\">Tipo di presentazione <input type=\"radio\" name=\"tipo_pres\" value=\"sbf\" checked> S.B.F.
    <input type=\"radio\" name=\"tipo_pres\" value=\"dic\">Dopo Incasso</td>
    <td colspan=\"3\" align=\"center\">Conferma = ><input type=\"submit\" name=\"azione\" value=\"vai\" onclick=\"if(!confirm('Confermi presentazione Distinta ?')) return false;\"   ></td></tr>\n";
    echo "<tr><td align=right colspan=8><div style=\"width: 500px; background-color: #C1C1C1; margin-top: 10px\">TOTALE effetti da presentare:
    <input type=\"text\" name=\"totale\" style=\"100px; margin: 10px; text-align: right\" value=\"0\"></div>
    </td>
    </tr>";
    echo "</form></table></body></html>";

    $conn->null;
    $conn = null;
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>