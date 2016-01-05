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
base_html("", $_percorso);
java_script($_cosa, $_percorso);
jquery_seleziona_tot($_cosa, $_percorso);
echo "</head>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

    echo "<table align=\"center\" width=\"80%\" border=\"0\">";
    echo "<tr><td align=\"center\" valign=\"center\">";
    echo "<span class=\"testo_blu\"><center><h3><font color=\"red\"><b>Importa fatture e note credito / debito in contabilit&agrave;</font></h3></b></span>";
    echo "</td>";
    echo "<tr><td align=\"center\" valign=\"center\"></span>";
    echo "<span class=\"testo_blu\"><center><h3>Seleziona i documenti, una volta importati non sar&agrave; pi&ugrave; possibile modificarli nel reparto vendite</h3></span>";
    echo "<span class=\"testo_blu\"><center><h3><font color=\"green\">I documenti qui presenti sono già definitivi</font></h3></span>";
    echo "</td></tr>\n";
    echo "</table>";
// Stringa contenente la query di ricerca...
// 	prendo l'anno in corso

    $query = "select * from fv_testacalce INNER JOIN clienti ON fv_testacalce.utente = clienti.codice where contabilita != 'SI' and status = 'evaso' order by anno, ndoc";

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
    echo "<form action=\"importa_fatt2.php\" method=\"POST\">\n";
    echo "<td width=\"100\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
    echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Seleziona</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {


            echo "<tr>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";

            echo "</tr>";
            //azzero le variabili
            //      $_valoretot = 0;
            echo "<tr>";
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
            printf("<td align=\"center\"><span class=\"testo_blu\"><b>%s/$dati[suffix]</b></span></td>", $dati['ndoc']);
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['codice']);
            printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
            printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['totdoc']);
            printf("<td align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"%s%s%s\" valore=\"%s\"></td>\n", $dati['anno'],$dati['suffix'], $dati['ndoc'], $dati['totdoc']);
            echo "</tr>";

    }


    echo "</td></tr>\n";
    echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
    echo "<tr><td align=right colspan=6><div style=\"width: 500px; background-color: #C1C1C1; margin-top: 10px\">TOTALE fatture selezionate:
    <input type=\"text\" name=\"totale\" style=\"100px; margin: 10px; text-align: right\" value=\"0\"></div>
    </td>\n";
    echo "<tr><td colspan=\"6\" align=\"right\" class=\"testo_blu\"><input type=\"submit\" name=\"azione\" value=\"vai\"></td>";
    echo "</FORM></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>