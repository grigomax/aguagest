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
require $_percorso . "librerie/motore_doc_pdo.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);



//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['vendite'] > "1")
{
    ?>
    <tr>
        <td align="center" valign="top" colspan="2">
            <span class="intestazione"><b>Scegliere documento da Cambiare</b><br></span><br>
            Programma che permette di cambiare il cliente su una bolla vedita
        </td></tr>
    <?php
    
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
        $_suffix = strtoupper($_POST['suffix']);
    }
    
    echo "<form action=\"doc_modint.php?tdoc=$_tdoc\" method=\"POST\">\n";
    echo "Cambia anno => <input type=\"number\" size=\"6\" name=\"anno\" value=\"$_anno\"> o suffisso <input type=\"text\" size=\"3\" maxlength=\"1\" name=\"suffix\" value=\"$_suffix\"><input type=\"submit\" name=\"cambia\">\n";

    echo "</form>\n";

    printf("<br><br><form action=\"doc_bvint.php?tdoc=$_tdoc\" method=\"POST\">");

    echo "<tr><td align=center colspan=\"2\">anno selezionato <input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>$_anno documento = $_tdoc<br><b>Selezionare il documento</b><br>";

    $result = seleziona_documento("elenco_select", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

    
    echo "<select name=\"ndoc\">\n";
    echo "<option value=\"\"></option>";

    foreach ($result AS $dati)
    {
        printf("<option value=\"%s\">%s - %s - %s - %s</option>\n", $dati['ndoc'], $dati['ndoc'], $dati['datareg'], $dati['ragsoc'], $dati['status']);
    }

    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td align=center><br><b> Selezionale il nuovo Intestatario</b><br>";
    
    tabella_clienti("elenca_select", "newcli", $_parametri);
 
    echo "</td></tr>\n";

    echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Cambia\");>\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";


    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>