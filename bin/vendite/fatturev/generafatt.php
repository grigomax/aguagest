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


base_html($_cosa, $_percorso);
java_script($_cosa, $_percorso);
jquery_datapicker($_cosa, $_percorso);

echo "</head>\n";
echo "<body>\n";

testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

echo "<table align=\"center\" width=\"100%\" border=\"0\">";
echo "<tr><td align=\"center\" valign=\"center\">";
echo "<span class=\"testo_blu\"><center><h3><b>Genera fattura differita da d.d.t.</h3></b>";
echo "</td>";
echo "<tr><td align=\"center\" valign=\"center\">";
echo "<span class=\"testo_blu\"><center><h3>Seleziona i d.d.t. di VENDITA da esportare</h3>";
echo "</td>";
echo "</table>";
// Stringa contenente la query di ricerca...
if ($_SESSION['user']['vendite'] > "2")
{

    $query = "select * from bv_bolle INNER JOIN clienti ON bv_bolle.utente = clienti.codice where causale = 'VENDITA' AND status = 'stampato' order by anno, ragsoc, ndoc";

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
    echo "<table width=\"80%\" align=\"center\" border=\"0\">";
    echo "<tr>";
    echo "<form action=\"generafatt2.php\" method=\"POST\">\n";
    echo "<td width=\"100\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
    echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Seleziona</span></td>";
    echo "</tr>";

// azzero la variabile
    $_valoretot = 0;

    foreach ($result AS $dati)
    {

        $_codcli = $dati['codice'];

        if ($_prima != "NO")
        {
            $_codcli1 = $_codcli;
        }

        if ($_codcli1 != $_codcli)
        {
            echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align=\"right\"><font size=\"2\">Valore ivato == </font>  </td><td align=\"right\"><font size=\"2\">$_valoretot</font> </td><td>&nbsp;</td></tr>";
            echo "<tr>";
            echo "<td width=\"100\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"40\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";

            echo "</tr>";
            //azzero le variabili
            $_valoretot = 0;
            echo "<tr>";
            printf("<td width=\"100\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
            echo "<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>$dati[ndoc] / $dati[suffix]</b></span></td>\n";
            printf("<td width=\"40\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['codice']);
            printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
            printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['totimpo']);
            printf("<td width=\"30\" align=\"center\"><input type=checkbox name=\"numero[]\" value=\"%s%s%s\"></td>\n", $dati['anno'], $dati['suffix'], $dati['ndoc']);
            echo "</tr>";
            $_valoretot = $_valoretot + $dati['totdoc'];
            $_codcli1 = $_codcli;
        }
        else
        {
            echo "<tr>";
            //printf("<form action=\"generafatt2.php\" method=\"POST\">", $dati['anno']);
            printf("<td width=\"100\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
            echo "<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>$dati[ndoc] / $dati[suffix]</b></span></td>\n";
            printf("<td width=\"40\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['codice']);
            printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
            printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['totimpo']);
            printf("<td width=\"30\" align=\"center\"><input type=checkbox name=\"numero[]\" value=\"%s%s%s\"></td>\n", $dati['anno'], $dati['suffix'], $dati['ndoc']);
            echo "</tr>";
            $_valoretot = $_valoretot + $dati['totdoc'];
            $_codcli1 = $_codcli;
            $_prima = "NO";
        }
    }


    echo "</td></tr>\n";
    $_data = date('d-m-Y');
    echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
    echo "<tr><td colspan=\"6\" align=\"right\" class=\"testo_blu\">Genera Fatture con data = <input type=\"text\" class=\"data\" size=\"11\" maxlength=\"10\" name=\"data\" value=\"$_data\">\n";
    //inseriamo la selezione del suffisso
    
    suffisso("select", "suffix", $_parametri);
    
    echo "- <input type=\"submit\" name=\"azione\" value=\"vai\"></td>";
    echo "</FORM></table>\n";

    
}
else
{
    echo "<h2>Non hai i permessi per poter visualizzare il documento</h2>\n";
}

echo "</body></html>\n";
?>