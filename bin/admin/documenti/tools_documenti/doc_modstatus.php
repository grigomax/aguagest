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

//settiamo il tempo di sessione
session_start();
$_SESSION['keepalive'] ++;

//carichiamo le librerie base con al gestione degli errori
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['setting'] > "2")
{
    require $_percorso . "librerie/motore_doc_pdo.php";
    //cerco i documenti e li seleziono in base al database..
    $_tdoc = $_GET['tdoc'];
    $_anno = $_GET['anno'];


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
//selezioniamo il database documenti..
    $_dbdoc = archivio_tdoc($_tdoc);

    echo "<table width=\"100%\">\n";
    echo "<tr>\n";
    
    
    echo "<td align=\"center\" valign=\"top\" colspan=2>\n";
    echo "<span class=\"intestazione\"><b>Scegliere il $_tdoc da Cambiare</b><br></span><br>Programma che permette di cambiare Status al documento<br>\n";

    echo "<form action=\"doc_modstatus.php?tdoc=$_tdoc\" method=\"POST\">\n";
    echo "Cambia anno => <input type=\"number\" size=\"6\" name=\"anno\" value=\"$_anno\"> o suffisso <input type=\"text\" size=\"3\" maxlength=\"1\" name=\"suffix\" value=\"$_suffix\"><input type=\"submit\" name=\"cambia\">\n";

    echo "</form>\n";


    printf("<br><br><form action=\"ris_docstatus.php?tdoc=$_tdoc\" method=\"POST\">");
    printf("Anno Corrente<input type=\"radio\" name=\"anno\" value=\"%s\" checked>$_anno", $_anno);

    echo "<br><b>Selezionare il documento</b><br>";
    echo "<select name=\"ndoc\">\n";
    echo "<option value=\"\"></option>";

    // Stringa contenente la query di ricerca... solo dei fornitori
    $query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where anno='$_anno' AND suffix='$_suffix' order by ndoc desc, anno");
    #$query = sprintf("select ndoc, datareg, ragsoc, status, utente, codice from bv_bolle INNER JOIN clienti ON bv_bolle.utente = clienti.codice where anno=\"%s\" order by ndoc desc", $_anno);

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
    echo "<span class=\"testo_blu\">";
    foreach ($result AS $dati)
    {
        printf("<option value=\"%s\">%s - %s - %s - %s</option>\n", $dati['ndoc'], $dati['ndoc'], $dati['datareg'], $dati['ragsoc'], $dati['status']);
    }

    echo "</select>\n";


    echo "<br><br><b> Selezionale il nuovo status</b><br>";
    echo "<select name=\"status\">\n";
    echo "<option value=\"stampato\">stampato</option>";
    echo "<option value=\"ripristinato\">Ripristinato</option>";
    echo "<option value=\"inoltrato\">Inoltrato</option>";
    echo "<option value=\"inserito\">inserito</option>";
    echo "<option value=\"evaso\">evaso</option>";

    echo "</select>\n";


    echo "<center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Cambia\");>\n";
    echo "</form>\n";
    echo "</td>\n</tr>\n";


    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>