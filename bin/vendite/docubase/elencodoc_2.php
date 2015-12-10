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

require $_percorso . "librerie/motore_doc_pdo.php";


//carichiamo la base delle pagine:
base_html("", $_percorso);

if ($_POST['campi'] == "barcode")
{

    $_tdoc = $_GET['tdoc'];
    $_anno = substr($_POST['descrizione'], "0", "4");
    $_ndoc = substr($_POST['descrizione'], "4", "10");

    echo "<meta http-equiv=refresh content=\"1; url=../docubase/visualizzadoc.php?tdoc=$_tdoc&anno=$_anno&ndoc=$_ndoc\"></meta>\n";
    echo "</head>\n";
    echo "<h2>Cerco il documento</h2>\n";
    exit;
}

if ($_POST['barcode'] != "")
{

    $_tdoc = $_GET['tdoc'];
    $_anno = substr($_POST['barcode'], "0", "4");
    $_ndoc = substr($_POST['barcode'], "4", "10");

    echo "<meta http-equiv=refresh content=\"1; url=../docubase/visualizzadoc.php?tdoc=$_tdoc&anno=$_anno&ndoc=$_ndoc\"></meta>\n";
    echo "</head>\n";
    echo "<h2>Cerco il documento</h2>\n";
    exit;
}



echo "</head>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['vendite'] > "1")
{


    $_tdoc = $_GET['tdoc'];
    $_campi = $_POST['campi'];


    //selezioniamo il database
    $_dbdoc = archivio_tdoc($_tdoc);

    echo "<span class=\"testo_blu\"><center><br><b>Elenco $_tdoc trovati</b></center></span><br>";

    $_descrizione = $_POST['descrizione'];
    $_descrizione = "%$_descrizione%";



    if ($_tdoc == "fornitore")
    {
        $query = "select * from $_dbdoc[testacalce] INNER JOIN fornitori ON $_dbdoc[testacalce].utente = fornitori.codice where $_campi like '$_descrizione' order by anno DESC, ndoc";
    }
    else
    {
        $query = "select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where $_campi like '$_descrizione' order by anno DESC, ndoc";
    }


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
    echo "<table align=\"center\" width=\"95%\">";
    echo "<tr>";

    echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Campo = $_campi</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
    echo "</tr>";

    echo "<form action=\"../docubase/visualizzadoc.php?tdoc=$_tdoc\" method=\"POST\">\n";
    foreach ($result AS $dati)
    {
        if ($_tdoc == "FATTURA")
        {
            $_tdoc = $dati['tdoc'];
        }
        echo "<tr>";
        
        printf("<td width=\"150\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati[$_campi]);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['ndoc']);
        printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
        printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['totdoc']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['status']);
        printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><button type=\"submit\" name=\"annondoc\" value=\"%s%s\">$dati[ndoc]</button>\n", $dati['anno'], $dati['ndoc']);
        echo "</tr>";
        echo "<tr>";
        echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";

        echo "</tr>";
    }



    echo "</form></td></tr></table></body></html>";

    //chiudu la connessione

    $conn->null;
    $conn = null;
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>