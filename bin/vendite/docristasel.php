<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
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



if ($_SESSION['user']['vendite'] > "1")
{


    $_tdoc = $_GET['tdoc'];
//prendiamoci i il database..
    $_archivio = archivio_tdoc($_tdoc);

    if (($_POST['anno'] == "") AND ($_POST['suffix'] == ""))
    {
        $_anno = date('Y');
        $_suffix = $SUFFIX_DDT;
    }
    else
    {
        $_anno = $_POST['anno'];
        $_suffix = $_POST['suffix'];
    }

    echo "<table width=\"80%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo "<td width=\"85%\" align=\"center\" valign=\"top\" colspan=\"2\">\n";
    echo "<span class=\"intestazione\"><b>Scegliere $_tdoc da ristampare</b><br></span><br></td></tr>\n";


    echo "<form action=\"docristasel.php?tdoc=$_tdoc\" method=\"POST\">\n";
    echo "Cambia anno => <input type=\"number\" size=\"6\" name=\"anno\" value=\"$_anno\"> o suffisso <input type=\"text\" size=\"3\" maxlength=\"1\" name=\"suffix\" value=\"$_suffix\"><input type=\"submit\" name=\"cambia\">\n";

    echo "</form>\n";

// conferma d'ordine...
    printf("<br><br><form action=\"stampa_doc.php\" target=\"sotto\" method=\"GET\">");

    echo "<td align=\"center\"><a href=\"docristasel.php\">Aggiorna ==><img src=\"../images/reload.png\"></img></a></td></tr>";

    echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"radio\" name=\"anno\" value=\"$_anno\" checked >Anno $_anno<input type=\"radio\" name=\"tdoc\" value=\"$_tdoc\" checked >$_tdoc / suffisso<input type=\"radio\" name=\"suffix\" value=\"$_suffix\" checked >$_suffix</td></tr>";

    echo "<tr><td align=center colspan=\"2\"><br>";
    echo "<select name=\"ndoc\">\n";
    echo "<option value=\"\"></option>";


    $result = seleziona_documento("elenco_select", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

    // Tutto procede a meraviglia...
    echo "<span class=\"testo_blu\">";
    foreach ($result AS $dati)
    {
        printf("<option value=\"%s\">%s - %s - %s - %s %s</option>\n", $dati['ndoc'], $dati['ndoc'], $dati['datareg'], $dati['ragsoc'], $dati['status'], $dati['invio']);
    }

    echo "</select>\n";
    echo "</td></tr>\n";
    echo "<tr><td colspan=\"2\" align=\"center\"><br>Per stampare o inviare o salvare documento/i <input type=\"checkbox\" name=\"multi\" value=\"SI\"></td></tr>\n";
    echo "<tr><td colspan=\"2\" align=\"center\"><br>Per salvare il documento pdf in locale barra qui<input type=\"checkbox\" name=\"salva\" value=\"SI\"></td></tr>\n";



    echo "<tr><td align=center colspan=\"2\"><br>";
    echo "<select name=\"docfine\">\n";
    echo "<option value=\"\"></option>";

    $result = seleziona_documento("elenco_select", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

    // Tutto procede a meraviglia...
    echo "<span class=\"testo_blu\">";

    foreach ($result AS $dati)
    {
        printf("<option value=\"%s\">%s - %s - %s - %s %s</option>\n", $dati['ndoc'], $dati['ndoc'], $dati['datareg'], $dati['ragsoc'], $dati['status'], $dati['invio']);
    }

    echo "</select>\n";
    echo "</td></tr>\n";



    print_prezzi($_tdoc);

    seleziona_lingue();

    echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Stampa\">&nbsp; <input type=\"submit\" name=\"azione\" value=\"Inoltra\">\n";
    echo "</form>\n</td>\n";
    echo "</td></tr></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>