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

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

$_user = $_SESSION['user']['user'];
$_azione = $_GET['azione'];
// prendiamoci il codice articolo da modificare

echo "<table width=\"80%\"><tr><td>";
printf("<form action=\"mod-user.php?azione=$_GET[azione]\" method=\"POST\">\n");
echo "<table width=\"80%\" border=\"0\" align=center>\n";

if($_azione == "pwd")
{
    echo "<tr><td colspan=2 align=\"center\"><font size=3><span class=\"testo_blu\"><b>Modifica password utente</b><br></font></span></td>\n";

    $dati = tabella_utenti("singolo", $_id, $_user, $_password, $_blocca, $_parametri);

    printf("<tr><td align=right>Nome :</td>");
    printf("<td><input type=\"radio\" name=\"user\" value=\"%s\" checked>$dati[user]</td></tr>", $dati['user']);
    echo "<tr><td align=right>Utente Attivo dal</td><td>$dati[datareg]</td></tr>\n";
    echo "<tr><td align=right>Inserisci vecchia password</td><td><input type=\"password\" name=\"vecchia\" size=\"40\" maxleght=\"40\" required></td></tr>\n";
    echo "<tr><td align=right>Inserisci Nuova password</td><td><input type=\"password\" name=\"nuova\" size=\"40\" maxleght=\"40\" required></td></tr>\n";
    echo "<tr><td align=right>Inserisci Conferma password</td><td><input type=\"password\" name=\"conferma\" size=\"40\" maxleght=\"40\" required></td></tr>\n";
    #printf("<tr><td align=right>Data mod. o reg. : </td><td><input type=\"text\" name=\"datareg\" value=\"%s\" size=\"11\" maxleght=\"10\"> Data all'americana ovvero rovescia</td></tr>", date('Y-m-d'));
    echo "<tr><td>&nbsp;</td><td align=RIGHT><input type=\"submit\" name=\"azione\" value=\"Modifica\"></td></tr>";
}
else
{
    
    echo "<tr><td colspan=2 align=\"center\"><h2><span class=\"testo_blu\"><b>Modifica Estetica programma</b><br></font></span></h2></td>\n";

    $dati = tabella_utenti("singolo", $_id, $_user, $_password, $_blocca, $_parametri);

    $USER_SCREEN_FONT_SIZE = $dati['USER_SCREEN_FONT_SIZE'] * 10;
    
    echo "<tr><td colspan=\"2\" rowspan=\"1\" style=\"width: 350px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Campo Body (corpo)</span></td>
         </tr>\n";

    echo "<tr>\n";
    echo "<td>Larghezza</td>\n";
    echo "<td><input type=\"range\" min=\"70\" max=\"100\" name=\"USER_SCREEN_WIDTH\" value=\"$dati[USER_SCREEN_WIDTH]\"> Min. 70 max 100%</td>\n";
    echo "</tr><tr>\n";
    echo "<td>Grandezza Carattere Base</td>\n";
    echo "<td><input type=\"range\" min=\"5\" max=\"15\" name=\"USER_SCREEN_FONT_SIZE\" value=\"$USER_SCREEN_FONT_SIZE\"> Min. 0.5 max 1.5 em</td></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Carattere Base Applicazione</td>\n";
    echo "<td><select name=\"USER_SCREEN_FONT_TYPE\">\n";
    echo "<option value=\"$dati[USER_SCREEN_FONT_TYPE]\">$dati[USER_SCREEN_FONT_TYPE]</option>\n";
    echo "<option value=\"Arial\">Arial</option>\n";
    echo "<option value=\"serif\">Serif</option>\n";
    echo "<option value=\"sans-serif\">Sans serif</option>\n";
    echo "<option value=\"helvetica\">Helvetica</option>\n";
    echo "<option value=\"times\">Times serif</option>\n";
    echo "<option value=\"courier\">Courier spazi larghi</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Colore Sfondo</td>\n";
    echo "<td><input type=\"color\" name=\"USER_SCREEN_COLOR_BACKGROUND\" value=\"$dati[USER_SCREEN_COLOR_BACKGROUND]\"> Classico #d6e4f9</td>\n";
    echo "</tr>\n";
    
    
    echo "<tr><td>&nbsp;</td><td align=RIGHT><input type=\"submit\" name=\"azione\" value=\"Modifica\"></td></tr>";
    
}

echo "</form>\n</td>\n";
echo "</td>\n</tr>\n";
echo "</table>";
// ************************************************************************************** -->
echo "</td></tr></table>\n";
?>