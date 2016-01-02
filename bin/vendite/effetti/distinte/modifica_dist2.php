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

//carichiamo le librerie necessarie..
require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);
jquery_seleziona_tot($_cosa, $_percorso);

echo "</head>\n";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

// imposto la sessione della banca

    $_ndistinta = $_POST['ndistinta'];
    $_azione = $_POST['azione'];
    $_anno = $_POST['anno'];

    echo "<form action=\"modifica_dist3.php\" method=\"POST\">";

    if ($_azione == "modifica")
    {

        //l'azione si svolge in due volte la prima elenchiamo gli effetti della distinta.. e la seconda gli effetti disponibili
        // in modo che recuperiamo parte del codice già scritto.
        // elenca le fatture presenti in archivio non evasi solo ricevuta bancaria
        echo "<span class=\"testo_blu\"><center><br><b><input type=\"radio\" name=\"anno\" value=\"$_anno\" checked >$_anno  <input type=\"radio\" name=\"ndistinta\" value=\"$_POST[ndistinta]\" checked >Numero Distinta $_POST[ndistinta] </b></center>";
        // elenco documenti fatture vendita
        echo "<span class=\"testo_blu\"><center><br><b>Selezionare gli effetti da includere nella distinta</b></center><br></span></span>";

        // Tutto procede a meraviglia...
        echo "<table align=\"center\" width=\"90%\">";
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

        $_parametri['anno'] = $_anno;
        $_parametri['ndistinta'] = $_ndistinta;

        $result = tabella_effetti("leggi_distinta", $_percorso, $_annoeff, $_numeff, $_parametri);

        foreach ($result AS
                $dati)
        {
            echo "<tr>";
            printf("<td align=\"center\"><span class=\"testo_blu\"><font color=\"red\"><b>%s</b></font></span></td>", $dati['datadoc']);
            echo "<td align=\"center\"><span class=\"testo_blu\"><font color=\"red\"><b>$dati[numdoc] / $dati[suffixdoc]</b></font></span></td>\n";
            printf("<td align=\"center\"><span class=\"testo_blu\"><font color=\"red\"><b>%s</b></font></span></td>", $dati['modpag']);
            printf("<td align=\"left\"><span class=\"testo_blu\"><font color=\"red\"><b>%s</b></font></span></td>", $dati['ragsoc']);
            printf("<td align=\"center\"><span class=\"testo_blu\"><font color=\"red\"><b>%s</b></font></span></td>", $dati['impeff']);
            printf("<td align=\"center\"><span class=\"testo_blu\"><font color=\"red\"><b>%s</b></font></span></td>", $dati['scadeff']);
            printf("<td align=\"center\"><span class=\"testo_blu\"><font color=\"red\"><b>%s</b></font></span></td>", $dati['numeff']);

            if ($dati['ndistinta'] == $_ndistinta)
            {
                printf("<td align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"%s%s\" valore=\"%s\" checked></td>", $dati['annoeff'], $dati['numeff'], $dati['impeff']);
            }
            else
            {
                printf("<td align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"%s%s\" valore=\"%s\"></td>", $dati['annoeff'], $dati['numeff'], $dati['impeff']);
            }
            echo "</tr>";
            echo "<tr>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "</tr>";
            $_totale = $_totale + $dati['impeff'];
            $_bancadist = $dati['bancadist'];
            $_datadist = $dati['datadist'];

            if ($dati['tipo_pres'] == "dic")
            {
                $_dic = "checked";
            }
            else
            {
                $_sbf = "checked";
            }
        }

        //ora facciamo vedere gli effetti disponibili..

        $result = tabella_effetti("elenco_effetti_liberi", $_percorso, $_annoeff, $_numeff, $_parametri);

        foreach ($result AS
                $dati)
        {
            echo "<tr>";
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datadoc']);
            echo "<td align=\"center\"><span class=\"testo_blu\">$dati[numdoc] / $dati[suffixdoc]</span></td>\n";
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['modpag']);
            printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['impeff']);
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['scadeff']);
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['numeff']);

            if ($dati['ndistinta'] == $_ndistinta)
            {
                printf("<td align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"%s%s\" valore=\"%s\" checked></td>", $dati['annoeff'], $dati['numeff'], $dati['impeff']);
            }
            else
            {
                printf("<td align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"%s%s\" valore=\"%s\"></td>", $dati['annoeff'], $dati['numeff'], $dati['impeff']);
            }
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
            $_totale = $_totale + $dati['impeff'];

            if ($dati['tipo_pres'] == "dic")
            {
                $_dic = "checked";
            }
            else
            {
                $_sbf = "checked";
            }
        }


        echo "</td></tr>";
        echo "<tr><td colspan=5 align=\"center\" class=\"testo_blu\">Tipo di presentazione <input type=\"radio\" name=\"tipo_pres\" value=\"sbf\" $_sbf> SBF
    <input type=\"radio\" name=\"tipo_pres\" value=\"dic\" $_dic>Dopo Incasso</td>
    <td colspan=\"3\" align=\"center\">Conferma = ><input type=\"submit\" name=\"azione\" value=\"vai\" onclick=\"if(!confirm('Confermi presentazione Distinta ?')) return false;\"   ></td></tr>\n";
        echo "<tr><td align=right colspan=8><div style=\"width: 500px; background-color: #C1C1C1; margin-top: 10px\">TOTALE effetti presentati: <input type=\"text\" name=\"totale\" style=\"100px; margin: 10px; text-align: right\" value=\"0\"></div></td></tr>";
        echo "</form></table>";

        $_SESSION['ndistinta'] = $_ndistinta;
        $_SESSION['bancadist'] = $_bancadist;
        $_SESSION['datadist'] = $_datadist;
    }
    echo "</body></html>";

    if ($_azione == "elimina")
    {
        echo "<form action=\"modifica_dist3.php\" method=\"POST\">";
        // elenca le fatture presenti in archivio non evasi solo ricevuta bancaria
        echo "<span class=\"testo_blu\"><center><br><b>Numero Distinta <input type=\"radio\" name=\"ndistinta\" value=\"$_POST[ndistinta]\" checked>$_POST[ndistinta] / Anno <input type=\"radio\" name=\"anno\" value=\"$_POST[anno]\" checked> $_POST[anno]</b></center><br>";
        // elenco documenti fatture vendita
        echo "<span class=\"testo_blu\"><center><br><b>Si Desidera Eliminare gli effetti oppure liberarli ?</b></center><br>";

        echo "<center><input type=\"submit\" name=\"azione\" value=\"Elimina\" onclick=\"if(!confirm('Confermi elimina Distinta ed effetti ?')) return false;\" >  <input type=\"submit\" name=\"azione\" value=\"Libera\" onclick=\"if(!confirm('Confermi eliminare la distinta e liberare gli effetti ?')) return false;\"> - <input type=\"submit\" name=\"azione\" value=\"Annulla\" onclick=\"if(!confirm('Confermi Annullamento azione ?')) return false;\" >";

        $_SESSION['ndistinta'] = $_POST['ndistinta'];
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>