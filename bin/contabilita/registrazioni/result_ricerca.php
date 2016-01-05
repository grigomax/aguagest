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



if ($_SESSION['user']['contabilita'] > "1")
{

    $_campi = $_POST['campi'];
    echo "<span class=\"testo_blu\"><center><br><b>Elenco documenti trovati per $_POST[descrizione] </b></center></span><br>";

    $_descrizione = $_POST['descrizione'];
    $_descrizione = "%$_descrizione%";

    if ($_POST['nreg'] != "")
    {
        $_campi = "nreg";
        $_descrizione = $_POST['nreg'];
        $query = "SELECT *, date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont from prima_nota where $_campi like '$_descrizione' AND rigo='1' order by anno DESC, nreg ";
    }
    else
    {
        // Stringa contenente la query di ricerca...
        $query = "SELECT *, date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont from prima_nota where $_campi like '$_descrizione' order by anno DESC, nreg ";
    }

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    // La query ?stata eseguita con successo...
    // Tutto procede a meraviglia...
    echo "<table align=\"center\" width=\"95%\">";
    echo "<tr>";
    echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Anno</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data reg.</span></td>";
    echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Giornale</span></td>";
    echo "<td width=\"300\" align=\"CENTER\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
    echo "<td width=\"100\" align=\"CENTER\" class=\"logo\"><span class=\"testo_bianco\">Desc. Conto</span></td>";
    echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Causale</span></td>";
    echo "<td width=\"35\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Protocollo</span></td>";
    echo "<td width=\"35\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
    echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {
        echo "<tr>";
        printf("<form action=\"visualizza_reg.php?causale=%s&anno=%s\" method=\"POST\">", $dati['causale'], $dati['anno']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['anno']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['data_reg']);
        printf("<td align=\"CENTER\"><span class=\"testo_blu\">%s</span></td>", $dati['data_cont']);
        printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['descrizione']);
        printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", substr($dati['desc_conto'], '0', '20'));
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['causale']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s/$dati[suffix_proto]</span></td>", $dati['nproto']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['ndoc']);
        printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['status']);
        printf("<td height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"submit\" name=\"nreg\" value=\"%s\"></td>", $dati['nreg']);
        echo "</tr></form>";
        echo "<tr>";
        // 				echo "<td width=\"200\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
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
    }


    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>