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
require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['vendite'] > "2")
{

    if (!file_exists("../../../setting/fatture_PA"))
    {
        if (mkdir("../../../setting/fatture_PA", 0755))
        {
            echo("<br>Directory creata!<br>");
            $fine = "1";
        }
        else
        {
            echo("<br>Non posso creare la directory! setting/fatture_PA <br>Contattare l'amministratore");
            $fine = "0";
            exit;
        }
    }

    $array_file = glob('../../../setting/fatture_PA/*.xml');
    {
        //$ordinato = sort($array_file);

        foreach ($array_file as $key => $file)
        {
            $file = substr($file, 28);

            //echo $file . "<br/>";
            //dividiamo il file in base ai capitoli
            echo "<tr>\n";

            $progressivo = substr($file, "14", "5");
            //echo "file trovato $file numero progressivo.. $progressivo<br>";
        }
    }

    $_tdoc = "FATTURA";
//prendiamoci i il database..
    $_archivio = archivio_tdoc($_tdoc);

    //settiamo il numero che tocca
    $progressivo++;
    
    if (($_POST['anno'] == "") AND ($_POST['suffix'] == ""))
    {
        $_anno = date('Y');
        $_suffix = $SUFFIX_DDT;
    }
    else
    {
        $_anno = $_POST['anno'];
        $_suffix = strtoupper($_POST['suffix']);
    }

    echo "<table width=\"80%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo "<td width=\"85%\" align=\"center\" valign=\"top\" colspan=\"2\">\n";
    echo "<span class=\"intestazione\"><b>Scegliere $_tdoc da Esportare</b><br></span><br></td></tr>\n";


     echo "<form action=\"fattura_PA.php?tdoc=$_tdoc\" method=\"POST\">\n";
    echo "Cambia anno => <input type=\"number\" size=\"6\" name=\"anno\" value=\"$_anno\"> o suffisso <input type=\"text\" size=\"3\" maxlength=\"1\" name=\"suffix\" value=\"$_suffix\"><input type=\"submit\" name=\"cambia\">\n";

    echo "</form>\n";

// conferma d'ordine...
    printf("<br><br><form action=\"fattura_PA_2.php\" method=\"GET\">");
    echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"radio\" name=\"anno\" value=\"$_anno\" checked >Anno $_anno<input type=\"radio\" name=\"tdoc\" value=\"$_tdoc\" checked >$_tdoc</td></tr>";
    echo "<tr><td align=center colspan=\"2\"><br>";
    echo "<select name=\"ndoc\">\n";
    echo "<option value=\"\"></option>";


    $result = seleziona_documento("elenco_select", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

    // Tutto procede a meraviglia...
    echo "<span class=\"testo_blu\">";
    foreach ($result AS $dati)
    {
        printf("<option value=\"%s%s%s\">%s - %s - %s - %s %s</option>\n", $dati['anno'], $dati['suffix'], $dati['ndoc'], $dati['ndoc'], $dati['datareg'], $dati['ragsoc'], $dati['status'], $dati['invio']);
    }

    echo "</select>\n";
    echo "</td></tr>\n";
    echo "<tr><td align=center colspan=\"2\">\n";
    
    echo "Numero progressivo => <input type=\"text\" size=\"6\" maxlength=\"5\" name=\"progressivo\" value=\"$progressivo\"></td></tr>\n";
    echo "<tr><td align=center colspan=\"2\"><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Esporta\">\n";
    echo "</form>\n</td></tr>\n";
    echo "<tr><td align=center colspan=\"2\"><b>Leggiamo i file presenti per verificare il progressivo..</b><br><br>\n";
    //$ordinato = sort($array_file);

    sort($array_file);
        foreach ($array_file as $key => $file)
        {
            $file = substr($file, 28);

            //echo $file . "<br/>";
            //dividiamo il file in base ai capitoli
            

            $progressivo = substr($file, "14", "5");
            echo "file trovato $file numero progressivo.. $progressivo<br>";
        }
        echo "</td></tr>\n";
        echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>