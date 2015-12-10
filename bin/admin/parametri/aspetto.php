<?php

//carichiamo il percorso
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
//settiamo il tempo di sessione
session_start();
$_SESSION['keepalive'] ++;
require $_percorso . "librerie/lib_html.php";
//carichiamo le sessioni correnti
$conn = permessi_sessione("verifica_PDO", $_percorso);


//inizio parte visiva..

base_html($_cosa, $_percorso);
java_script($_cosa, $_percorso);
jquery_tabs($_cosa, $_percorso);

echo "</head>\n";
echo "<body>\n";

testata_html($_cosa, $_percorso);

menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['setting'] > "3")
{
    
    //facciamo le moltiplicazioni..
    
    $SCREEN_FONT_SIZE = $SCREEN_FONT_SIZE * 10;
#    $SCREEN_INPUT_SIZE = $SCREEN_INPUT_SIZE * 10;
#    $SCREEN_SELECT_SIZE = $SCREEN_SELECT_SIZE * 10;
    
    
    
    echo "<h3 align=\"center\">Aspetto Estitico programma e stampe</h2>\n";

    echo "<form action=\"salvavars_aspetto.php\" method=\"POST\">";

    echo "<div id=\"tabs\">\n";
    echo "<ul>\n";
    echo "<li><a href=\"#tabs-1\">Css - Programma</a></li>\n";
    echo "<li><a href=\"#tabs-2\">Css - Stampe</a></li>\n";
    echo "<li><a href=\"#tabs-3\">Loghi - Stampe</a></li>\n";
    echo "</ul>\n";

    echo "<div id=\"tabs-1\">\n";

    echo "<table class=\"classic_bordo\" border=\"0\" align=\"center\" width=\"80%\">\n";
    echo "<tr><td colspan=\"2\" rowspan=\"1\" style=\"width: 350px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Campo Body (corpo)</span></td>
         </tr>\n";

    echo "<tr>\n";
    echo "<td>Larghezza</td>\n";
    echo "<td><input type=\"range\" min=\"70\" max=\"100\" name=\"SCREEN_WIDTH\" value=\"$SCREEN_WIDTH\"> Min. 70 max 100%</td>\n";
    echo "</tr><tr>\n";
    echo "<td>Grandezza Carattere Base</td>\n";
    echo "<td><input type=\"range\" min=\"5\" max=\"15\" name=\"SCREEN_FONT_SIZE\" value=\"$SCREEN_FONT_SIZE\"> Min. 0.5 max 1.5 em</td></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Carattere Base Applicazione</td>\n";
    echo "<td><select name=\"SCREEN_FONT_TYPE\">\n";
    echo "<option value=\"$SCREEN_FONT_TYPE\">$SCREEN_FONT_TYPE</option>\n";
    echo "<option value=\"Arial\">Arial</option>\n";
    echo "<option value=\"serif\">Serif</option>\n";
    echo "<option value=\"sans-serif\">Sans serif</option>\n";
    echo "<option value=\"helvetica\">Helvetica</option>\n";
    echo "<option value=\"times\">Times serif</option>\n";
    echo "<option value=\"courier\">Courier spazi larghi</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Colore Sfondo</td>\n";
    echo "<td><input type=\"color\" name=\"SCREEN_COLOR_BACKGROUND\" value=\"$SCREEN_COLOR_BACKGROUND\"></td>\n";
    echo "</tr>\n";

    echo "</table>\n";
    echo "</div>\n";

//--------------------------------------------------------------SECONDA TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-2\">\n";
    echo "<table class=\"classic_bordo\" border=\"0\" align=\"center\" width=\"80%\">\n";
    echo "<tr>\n";
    echo "<td>Larghezza in pixel della pagina </td>\n";
    echo "<td><input type=\"number\" size=\"4\" name=\"PRINT_WIDTH\" value=\"$PRINT_WIDTH\"> classico da 700 a 750px</td>\n";
    echo "</tr><tr>\n";
    echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\">Grandezza font base documenti</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"PRINT_FONT_SIZE\">\n";
    echo "<option value=\"$PRINT_FONT_SIZE\">$PRINT_FONT_SIZE</option>\n";
    echo "<option value=\"4\">4 punti</option>\n";
    echo "<option value=\"5\">5 punti</option>\n";
    echo "<option value=\"6\">6 punti</option>\n";
    echo "<option value=\"7\">7 punti</option>\n";
    echo "<option value=\"8\">8 punti</option>\n";
    echo "<option value=\"9\">9 punti</option>\n";
    echo "<option value=\"10\">10 punti</option>\n";
    echo "<option value=\"11\">11 punti</option>\n";
    echo "<option value=\"12\">12 punti</option>\n";
    echo "<option value=\"13\">13 punti</option>\n";
    echo "<option value=\"14\">14 punti</option>\n";
    echo "<option value=\"16\">16 punti</option>\n";
    echo "<option value=\"18\">18 punti</option>\n";
    echo "<option value=\"20\">20 punti</option>\n";
    echo "<option value=\"22\">22 punti</option>\n";
    echo "<option value=\"24\">24 punti</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Carattere Base Stampa</td>\n";
    echo "<td><select name=\"PRINT_FONT_TYPE\">\n";
    echo "<option value=\"$PRINT_FONT_TYPE\">$PRINT_FONT_TYPE</option>\n";
    echo "<option value=\"Arial\">Arial</option>\n";
    echo "<option value=\"serif\">Serif</option>\n";
    echo "<option value=\"sans-serif\">Sans serif</option>\n";
    echo "<option value=\"helvetica\">Helvetica</option>\n";
    echo "<option value=\"times\">Times serif</option>\n";
    echo "<option value=\"courier\">Courier spazi larghi</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";
    echo "</tr>\n";

    
    
    
    echo "</table>\n";
    echo "</div>\n";


    //--------------------------------------------------------------Terza TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-3\">\n";
    echo "<h4 align=\"center\">Selezionare i loghi relativi alle stampe statiche dei seguenti documenti</h4>\n";
    echo "<b>Legenda Numeri:</b><br><b>0 </b> Il logo è dato da una immagine <br> <b>1 </b> Nessun Logo, spazio Vuoto <br> <b>2 </b> Tutto il logo è scritto a caratteri <br>\n";
    echo "<table class=\"classic_bordo\" border=\"0\" align=\"center\" width=\"80%\">\n";

    echo "<tr>\n";
    echo "<td style=\"width: 60%;\" align=\"center\" valign=\"top\">Scheda Articolo</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"SCHEDA_LOGO\">\n";
    echo "<option value=\"$SCHEDA_LOGO\">$SCHEDA_LOGO</option>";
    echo "<option value=\"0\">Nessun logo, Spazio risevato alla carta intestata (1)</option>";
    echo "<option value=\"2\">Logo Aziendale Scritto a caratteri (2)</option>";
    echo "<option value=\"\"></option>";
    exec("ls ../../../setting/loghiazienda/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {

        echo "<option value=\"$val\">$val\n";
    }

    echo "</select>\n";
    echo "</tr>\n";
    
     echo "<tr>\n";
    echo "<td style=\"width: 60%;\" align=\"center\" valign=\"top\">Avviso Effetti</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"EFFETTI_LOGO\">\n";
    echo "<option value=\"$EFFETTI_LOGO\">$EFFETTI_LOGO</option>";
    echo "<option value=\"0\">Nessun logo, Spazio risevato alla carta intestata (1)</option>";
    echo "<option value=\"2\">Logo Aziendale Scritto a caratteri (2)</option>";
    echo "<option value=\"\"></option>";
    exec("ls ../../../setting/loghiazienda/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {

        echo "<option value=\"$val\">$val\n";
    }

    echo "</select>\n";
    echo "</tr>\n";
    
    echo "<tr>\n";
    echo "<td style=\"width: 60%;\" align=\"center\" valign=\"top\">Privacy Clienti</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"PRIVACY_LOGO\">\n";
    echo "<option value=\"$PRIVACY_LOGO\">$PRIVACY_LOGO</option>";
    echo "<option value=\"0\">Nessun logo, Spazio risevato alla carta intestata (1)</option>";
    echo "<option value=\"2\">Logo Aziendale Scritto a caratteri (2)</option>";
    echo "<option value=\"\"></option>";
    exec("ls ../../../setting/loghiazienda/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {

        echo "<option value=\"$val\">$val\n";
    }

    echo "</select>\n";
    echo "</tr>\n";


    echo "</table>\n";
    echo "</div>\n";



    echo "<right><br><input type=\"submit\" name=\"azione\" value=\"Modifica\"></td></tr>\n";

    echo "</form></body></html>\n";
}
else
{
    echo "<h2>Non hai i permessi per poter visualizzare cliente/fornitore</h2>\n";
}
?>