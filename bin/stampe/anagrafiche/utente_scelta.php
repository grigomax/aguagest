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



if ($_SESSION['user']['stampe'] > "1")
{

    $_utente = $_GET['utente'];

    echo "<table width=\"60%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr><td align=\"center\" valign=\"top\" colspan=\"2\">";
    echo "<span class=\"intestazione\"><br><b>Scegliere i $_utente da stampare</b><br></span><br></td></tr>\n";

    printf("<form action=\"clifor_selezione.php?utente=$_utente\" target=\"_blank\"method=\"POST\">");


    // CAMPO DOVE ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"right\"><span class=\"testo_blu\">Dove:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" align=\"left\">";
    echo "<select name=\"campi\">\n";
    echo "<option value=\"ragsoc\">Ragione Sociale</option>\n";
    echo "<option value=\"codice\">Codice </option>\n";
    echo "<option value=\"contatto\">contatto </option>\n";
    echo "<option value=\"telefono\">Telefono</option>\n";
    echo "<option value=\"cell\">Cellulare</option>\n";
    echo "<option value=\"fax\">Fax</option>\n";
    echo "<option value=\"iva\">Iva</option>\n";
    echo "<option value=\"dragsoc\">Destinazione</option>\n";
    echo "<option value=\"citta\">citta</option>\n";
    echo "<option value=\"prov\">Provincia</option>\n";
    echo "<option value=\"email\">e-mail</option>\n";
    echo "<option value=\"zona\">Zona</option>\n";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td align=\"right\"><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"descrizione\" size=\"60\" maxlength=\"40\"></td></tr>\n";


    echo "<tr><td align=center colspan=\"2\"><br>";
    echo "<select name=\"stampa\">\n";
    echo "<option value=\"clifor\">Completa con intestazione</option>";
    //echo "<option value=\"clifor_pdf\">Stampa comleta su file PDF</option>";
    echo "</select>\n";
    echo "</td></tr>\n";


    echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Stampa\");>\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</td>\n</tr>\n";
    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>