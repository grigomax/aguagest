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
base_html("", $_percorso);
java_script($_cosa, $_percorso);

//jquery_datapicker($_cosa, $_percorso);
jquery_seleziona_tot($_cosa, $_percorso);
echo "</head>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{


// elenca le fatture presenti in archivio non evase FATTURE VENDITA
// elenco documenti fatture vendita
    echo "<span class=\"testo_blu\"><center><br><b>Salda effetti presentati</b></center><br></span>";


    $result = tabella_effetti("elenco_presentati", $_percorso, $_annoeff, $_numeff, $_parametri);
    
    echo "<form action=\"salda_eff2.php\" method=\"POST\">";


    // Tutto procede a meraviglia...
    echo "<table align=\"center\" width=\"95%\">";
    echo "<tr>";
    echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">N. Eff</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Fatt.</span></td>";
    echo "<td width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
    echo "<td width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Banca</span></td>";
    echo "<td width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">N. dist.</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Scadenza</span></td>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Seleziona</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {
        $_mese_rif = substr($dati['scadeff'], 0, -3);

        if ($_mese_rif != $_mese_rif2)
        {
            echo "<tr><td align =\"center\" colspan=\"9\"><br><font face=\"arial\">Scadenze al $_mese_rif</font></td></tr>\n";
        }
        echo "<tr>";

        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['numeff']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datadoc']);
        printf("<td align=\"center\"><span class=\"testo_blu\"><b>%s/$dati[suffixdoc]</b></span></td>", $dati['numdoc']);
        printf("<td align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['bancadist']);
        printf("<td align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['ndistinta']);
        printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['impeff']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['scadeff']);
        printf("<td align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"%s%s\" valore=\"%s\"></td>\n", $dati['annoeff'], $dati['numeff'], $dati['impeff']);

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
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "</tr>";
        $_mese_rif2 = $_mese_rif;
    }

    echo "</td></tr>\n";
    $_data = date('d-m-Y');
    echo "<tr><td colspan=\"9\"><hr></td></tr>\n";
    echo "<tr><td align=right colspan=9><div style=\"width: 500px; background-color: #C1C1C1; margin-top: 10px\">TOTALE Effetti selezionati:
    <input type=\"text\" name=\"totale\" style=\"100px; margin: 10px; text-align: right\" value=\"0\"></div>
    </td>\n";
    
    echo "<tr><td colspan=\"9\" align=\"right\" class=\"testo_blu\">Salda Effetti con data = <input type=\"text\" class=\"data\" size=\"11\" maxlength=\"10\" name=\"data\" value=\"$_data\"><input type=\"submit\" name=\"azione\" value=\"vai\"></td>";

    echo "</form></td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>