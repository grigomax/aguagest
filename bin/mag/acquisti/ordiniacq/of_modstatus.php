<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
require $_percorso ."../setting/vars.php";
session_start(); $_SESSION['keepalive']++;
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



if ($_SESSION['user']['magazzino'] > "1")
{
        ?>
    <tr>
        <td align="center" valign="top" colspan="2">
    	<span class="intestazione"><b>Scegliere l'ordine fornitore da Cambiare</b><br></span><br>
    	Programma che permette di cambiare Status al documento
        </td></tr>
    <?php

    if ($_POST['anno'] == "")
    {
	$_anno = date('Y');
    }
    else
    {
	$_anno = $_POST['anno'];
    }



    echo "<form action=\"of_modstatus.php\" method=\"POST\">\n";
    echo "Cambia anno => <input type=\"number\" size=\"6\" maxlength=\"4\" name=\"anno\" value=\"$_anno\"><input type=\"submit\" name=\"cambia\">\n";

    echo "</form>\n";


    printf("<form action=\"ris_ofstatus.php\" method=\"POST\">");


    echo "<br><tr><td align=center colspan=\"2\"><b>Selezionare il documento</b><br>";

    echo "<input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>Anno selezionato = $_anno <br>\n";

    
    
    
    echo "<select name=\"ndoc\">\n";
    echo "<option value=\"\"></option>";

// Stringa contenente la query di ricerca... solo dei fornitori
    $query = sprintf("select ndoc, datareg, ragsoc, status, utente, codice from of_testacalce INNER JOIN fornitori ON of_testacalce.utente = fornitori.codice where anno=\"%s\" order by ndoc desc", $_anno);

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
	foreach ($result AS $dati)
	{
	    printf("<option value=\"%s\">%s - %s - %s - %s</option>\n", $dati['ndoc'], $dati['ndoc'], $dati['datareg'], $dati['ragsoc'], $dati['status']);
	}
    

    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td align=center><br><br><b> Selezionale il nuovo status</b><br>";
    echo "<select name=\"status\">\n";
    echo "<option value=\"stampato\">stampato</option>";
    echo "<option value=\"ripristinato\">Ripristinato</option>";
    echo "<option value=\"inoltrato\">Inoltrato</option>";
    echo "<option value=\"inserito\">inserito</option>";
    echo "<option value=\"evaso\">evaso</option>";

    echo "</select>\n";
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