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
require $_percorso . "librerie/motore_anagrafiche.php";



//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("", $_percorso);

java_script($_cosa, $_percorso);
jquery_tabs($_cosa, $_percorso);
tiny_mce($_cosa, $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


$_cosa = null;

//creiamo una funzione che ci richiami tutti gli elenchi e menù disponibili
function layout_menu_tendina($_cosa, $_campo_sx, $_campo_ALL, $_campo_CT, $_campo_LC, $_nomecampo, $maxct, $maxlc)
{
    global $dati;

    $_nome = "ST_" . $_nomecampo;
    $_nome_ALL = "ST_" . $_nomecampo . "_ALL";
    $_nome_CT = "ST_" . $_nomecampo . "_CT";
    $_nome_LC = "ST_" . $_nomecampo . "_LC";


    echo "<td colspan=\"5\"><hr></td></tr>\n";
    echo "<tr>\n";
    
    echo "<td style=\"width: 20%;\" align=\"left\" valign=\"top\"><B>$_campo_sx</B></td>\n";
    echo "<td style=\"text-align: left;\" valign=\"top\"><select name=\"$_nome\">\n";
    if($dati[$_nome] == "")
    {
        $dati[$_nome] = "NO";
    }
    echo "<option value=\"$dati[$_nome]\">$dati[$_nome]</option>\n";
    echo "<option value=\"SI\">SI</option>\n";
    echo "<option value=\"NO\">NO</option>\n";
    echo "</select></td>\n";
    //echo "</tr>\n";

    if ($_cosa[(string) 'ALL'] != "NO")
    {
        
        //ripristianiamo la dicitura..
        if($dati[$_nome_ALL] == "L")
        {
            $visivo = "Sinistra";
        }
        elseif($dati[$_nome_ALL] == "C")
        {
            $visivo = "Centrale";
        }
        elseif($dati[$_nome_ALL] == "R")
        {
            $visivo = "Destra";
        }
        else
        {
            $visivo = $dati[$_nome_ALL];
            $dati[$_nome_ALL] = substr($dati[$_nome_ALL], "0", "1");
        }
        
        
        echo "<td style=\"text-align: left;\" valign=\"top\"><select name=\"$_nome_ALL\">\n";
        echo "<option value=\"$dati[$_nome_ALL]\">$visivo</option>\n";
        echo "<option value=\"L\">Sinistra</option>\n";
        echo "<option value=\"C\">Centrale</option>\n";
        echo "<option value=\"R\">Destra</option>\n";
        echo "</select></td>\n";
        //echo "</tr>\n";
    }
    else
    {
        echo "<td>&nbsp;</td>\n";
    }

    if ($_cosa[CT] != "NO")
    {
        //echo "<tr>\n";
        //echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\">$_campo_CT</td>\n";
        echo "<td style=\"text-align: left;\" valign=\"top\"><input type=\"text\" size=\"5\" name=\"$_nome_CT\" value=\"$dati[$_nome_CT]\"> max $maxct</td>\n";
        //echo "</tr>\n";
    }
    else
    {
        echo "<td>&nbsp;</td>\n";
    }

    if ($_cosa['LC'] != "NO")
    {
        if ($_nomecampo == "AVVISO")
        {
            //echo "<tr>\n";
            //echo "<td colspan=\"2\" align=\"left\" valign=\"top\">Testo<br>\n";
            echo "<input type=\"text\" size=\"65\" maxlenght = \"60\" name=\"$_nome_LC\" value=\"$dati[$_nome_LC]\"></td>\n";
            //echo "</tr><tr>\n";
        }
        else
        {
            //echo "<tr>\n";
            //echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\">$_campo_LC</td>\n";
            echo "<td style=\"text-align: left;\" valign=\"top\"><input type=\"text\" size=\"5\" name=\"$_nome_LC\" value=\"$dati[$_nome_LC]\">max $maxlc </td>\n";
            //echo "</tr><tr>\n";
        }
    }
    else
    {
        echo "<td>&nbsp;</td>\n";
    }
    
    
    echo "</tr>\n";
}

if ($_SESSION['user']['setting'] > "3")
{
// mi prendo i paametri passati
//ed il nome del documento da prendere..

    $_azione = $_GET['azione'];

    if (($_azione == "Nuovo") AND ( $_GET['eti'] == "SI"))
    {

        $_eti = "SI";
        //adatto il file di lingua a riconoscere la maschera
    }
    elseif (($_azione == "Nuovo") AND ( $_GET['lis'] == "SI"))
    {

        $_lis = "SI";
        //adatto il file di lingua a riconoscere la maschera
    }
    else
    {

        $_tdoc = $_GET['tdoc'];

        $dati = tabella_stampe_layout("singola", $_percorso, $_tdoc);

        //Verifichiamo se è una etichetta:

        $_tipo = substr($_GET['tdoc'], "0", "3");

        if (($_tipo == "eti") OR ( $_GET[eti] == "SI"))
        {
            //confermiamo che la etichetta è una etichetta

            $_eti = "SI";
        }
        
        if (($_tipo == "lis") OR ( $_GET[lis] == "SI"))
        {
            //confermiamo che la etichetta è una etichetta

            $_lis = "SI";
        }

        //adatto il file di lingua a riconoscere la maschera
        $datidoc['tdoc'] = $_GET['tdoc'];
    }



//includiamo il file di lingua in italiano per prenderci le descrizioni dei campi
    include "../../librerie/doc_italiano.php";

    echo "</head><body>\n";

    echo "<form action=\"salva_layout.php?tdoc=$_tdoc\" method=\"POST\">\n";

    echo "<table align=\"center\" valign=\"top\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
    echo "<tr><td colspan=\"2\" valign=\"TOP\" align=\"center\"> \n";
    echo "<h2>Configurazione aspetto</h2><h3><font color=\"RED\"> $dati[ST_NDOC] </h3></font>\n";
    echo "Attenzione non si possono inserire nei campi di scrittura<br>NE parole accentate NE apostrofi e TANTOMENO virgolette.<br><font color=\"red\">La non osservanza potrebbe compromettere l'uso del programma.</font>\n";
    echo "<br>&nbsp;</td></tr>\n";
    echo "</table>\n";



    echo "<div id=\"tabs\">\n";
    echo "<ul>\n";
    echo "<li><a href=\"#tabs-1\">Nome e Logo Azienda</a></li>\n";
    echo "<li><a href=\"#tabs-2\">Intestazione</a></li>\n";
    echo "<li><a href=\"#tabs-3\">Corpo</a></li>\n";
    echo "<li><a href=\"#tabs-4\">Calce</a></li>\n";
    echo "<li><a href=\"#tabs-5\">Automatismi</a></li>\n";
    echo "</ul>\n";

    echo "<div id=\"tabs-1\">\n";
    echo "<table class=\"classic_bordo\" border=\"0\" align=\"center\" width=\"80%\">\n";

    if (($_eti == "SI") AND ( $_azione == "Nuovo"))
    {
        echo "<tr><td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Codice Etichetta <input type=\"text\" size=\"23\" maxlength=\"20\" name=\"tdoc\" value=\"eti_\">Obbligo inizio per eti_</span></td>\n";
        echo "</tr><tr>\n";
    }

    if (($_lis == "SI") AND ( $_azione == "Nuovo"))
    {
        echo "<tr><td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Codice Listino <input type=\"text\" size=\"23\" maxlength=\"20\" name=\"tdoc\" value=\"lis_\">Obbligo inizio per lis_</span></td>\n";
        echo "</tr><tr>\n";
    }
    
    if ($_eti == "SI")
    {
        echo "<tr><td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Nome Etichetta <input type=\"text\" size=\"23\" maxlength=\"20\" name=\"ST_NDOC\" value=\"$dati[ST_NDOC]\"></span></td>\n";
        echo "</tr><tr>\n";
    }
    
    if ($_lis == "SI")
    {
        echo "<tr><td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Nome Listino <input type=\"text\" size=\"23\" maxlength=\"20\" name=\"ST_NDOC\" value=\"$dati[ST_NDOC]\"></span></td>\n";
        echo "</tr><tr>\n";
    }

    echo "<tr><td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Immagini per il logo aziendale</span></td>\n";
    echo "</tr><tr>\n";
    echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\">Selezionare logo azienda </td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"ST_LOGOG\">\n";
    echo "<option value=\"$dati[ST_LOGOG]\">$dati[ST_LOGOG]</option>";
    echo "<option value=\"\"></option>";
    exec("ls ../../../setting/loghiazienda/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {

        echo "<option value=\"$val\">$val\n";
    }
    echo "</select> Se esteso dimensioni 193mm x 30mm\n";
    
    
    echo "</tr><tr>\n";
    echo "<td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\"><br>Tipo di logo aziendale</span></td>\n";
    echo "</tr><tr>\n";

    echo "<td colspan=\"2\" align=\"left\" valign=\"top\"><b>Tipo logo Zero</b> = ovvero nessun logo, lo spazio rimarr&agrave; libero per una eventuale carta stanpata <br>\n";
    echo "<b>Tipo di logo Uno </b>= ovvero logo completo della ditta riferito al logo grande 193X30mm<br>\n";
    echo "<b>Tipo di logo Due </b> = ovvero nessun logo tutta l'intestazione della ditta viene scritta con i caratteri<br>\n";
    echo "<b>Tipo di logo Tre </b>= ovvero logo medio mis. 150x33 della ditta a sinistra ed tutta la descrizione della ragione sociale a dx<br>\n";
    echo "<b>Tipo di logo Quattro </b>= ovvero logo medio mis. 150x33 della ditta a destra ed tutta la descrizione della ragione sociale a sx<br>\n";
    echo "<center><select name=\"ST_TLOGO\">\n";
    echo "<option value=\"$dati[ST_TLOGO]\">$dati[ST_TLOGO]</option>\n";
    echo "<option value=\"0\">0 Nessun logo spazio libero</option>\n";
    echo "<option value=\"1\">1 Logo immagine grande</option>\n";
    echo "<option value=\"2\">2 Logo Scritto con caratteri</option>\n";
    echo "<option value=\"3\">3 Logo Medio ovvero a sinistra il logo ed a destra scritto con caratteri</option>\n";
    echo "<option value=\"4\">4 Logo Medio ovvero a destra il logo ed a sinistra scritto con caratteri</option>\n";
    echo "<option value=\"5\">Logo per inventario/rimanenze</option>\n";
    echo "</select>\n";

    echo "</td>\n";
    echo "</td></tr>\n";
    echo "<tr>\n";
    echo "<td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\"><br>Font di stampa propria azienda</span></td>\n";
    echo "</tr><tr>\n";
    echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\">Font Intestazione ditta</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\">\n";
    echo "<select name=\"ST_FONTLOGO\">\n";
    echo "<option value=\"$dati[ST_FONTLOGO]\">$dati[ST_FONTLOGO]</option>\n";
    echo "<option value=\"Arial\">Arial, Helvetica sans serif</option>\n";
    echo "<option value=\"Times\">Times serif</option>\n";
    echo "<option value=\"Courier\">Courier spazi larghi</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";
    echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\">Grandezza font</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"ST_FONTLOGOSIZE\">\n";
    echo "<option value=\"$dati[ST_FONTLOGOSIZE]\">$dati[ST_FONTLOGOSIZE]</option>\n";
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

    echo "</table>\n";
    echo "</div>\n";

//--------------------------------------------------------------TESTATA-------------------------------------------------------------------------
    echo "<div id=\"tabs-2\">\n";
    echo "<table class=\"tabs\">";


    if ($_eti != SI)
    {
        echo "<tr><td colspan=\"2\"><br><hr><br></td></tr>\n";
// scelta del tipo di intestazione
        echo "<td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Tipo di intestazione</span></td>\n";
        echo "</tr><tr>\n";
        echo "<td colspan=\"2\" align=\"left\" valign=\"top\"><b>Tipo uno </b>= Semplice classico per ddt e fatture immediate, ovvero sede legale sulla sinistra
e distinazione sulla destra. necessita di sottotestata. <br>\n";
        echo "<b>Tipo Due </b>= tipo di testata complessa e completa.. ottima per tipo conferme preventivi ecc.. non necessita di sotto testata.<br>\n";
        echo "<b>Tipo Tre </b>= Semplice con solo la sede legale a destra tipico delle fatture differite. Necessita di sottotestata.<br>\n";
        echo "<center><select name=\"ST_TIPOTESTATA\">\n";
        echo "<option value=\"$dati[ST_TIPOTESTATA]\">$dati[ST_TIPOTESTATA]</option>\n";
        echo "<option value=\"1\">1 Cassica per ddt</option>\n";
        echo "<option value=\"2\">2 Completa e complessa</option>\n";
        echo "<option value=\"3\">3 Tipica per fatture</option>\n";
        echo "<option value=\"4\">4 Sspeciale per fatture</option>\n";
        echo "<option value=\"5\">5 Logo in caratteri classico per stampe di magazzino</option>\n";
        echo "</select>\n";
        echo "</tr><tr>\n";

// scelta del tipo di intestazione
        echo "<td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\"><br>Scelta della sotto testata. per testate dipo 1-3-4</span></td>\n";
        echo "</tr><tr>\n";
        echo "<td colspan=\"2\" align=\"left\" valign=\"top\"><b>Tipo 1 </b>= Semplice classico per ddt con il numero doc. <br>\n";
        echo "<b>Tipo Due </b>= Complessa e completa.. con banca ecc. per tutti i tipi di fatture.<br>\n";
        echo "<center><select name=\"ST_SOTTOTESTATA\">\n";
        echo "<option value=\"$dati[ST_SOTTOTESTATA]\">$dati[ST_SOTTOTESTATA]</option>\n";
        echo "<option value=\"0\">0 Nessuna sotto testata</option>\n";
        echo "<option value=\"1\">1 Cassica per ddt</option>\n";
        echo "<option value=\"2\">2 Completa e complessa per fatture</option>\n";
        echo "</select>\n";
        echo "</tr><tr>\n";
    }

    echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\"><br>Font Intestazione Cliente</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><br><select name=\"ST_FONTINTEST\">\n";
    echo "<option value=\"$dati[ST_FONTINTEST]\">$dati[ST_FONTINTEST]</option>\n";
    echo "<option value=\"Arial\">Arial, Helvetica sans serif</option>\n";
    echo "<option value=\"Times\">Times serif</option>\n";
    echo "<option value=\"Courier\">Courier spazi larghi</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";
    echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\">Grandezza font</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\">\n";
    echo "<select name=\"ST_FONTINTESTSIZE\">\n";
    echo "<option value=\"$dati[ST_FONTINTESTSIZE]\">$dati[ST_FONTINTESTSIZE]</option>\n";
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

    echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\"><br>Carattere stampa Testa e Calce documenti</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><br><select name=\"ST_FONTESTACALCE\">\n";
    echo "<option value=\"$dati[ST_FONTESTACALCE]\">$dati[ST_FONTESTACALCE]</option>\n";
    echo "<option value=\"Arial\">Arial, Helvetica sans serif</option>\n";
    echo "<option value=\"Times\">Times serif</option>\n";
    echo "<option value=\"Courier\">Courier spazi larghi</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";
    echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\">Grandezza font</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"ST_FONTESTASIZE\">\n";
    echo "<option value=\"$dati[ST_FONTESTASIZE]\">$dati[ST_FONTESTASIZE]</option>\n";
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

    echo "</table>\n";
    echo "</div>\n";

//--------------------------------------------------------------CORPO-------------------------------------------------------------------------
    echo "<div id=\"tabs-3\">\n";
    echo "<table class=\"tabs\" border=\"0\">";

    echo "<tr><td colspan=\"5\"><br><hr><br></td></tr>\n";

    echo "<tr>\n";
    echo "<td colspan=\"5\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Corpo documento</span></td>\n";
    echo "</tr><tr>\n";
    echo "<td colspan=\"2\" align=\"left\" valign=\"top\">Carattere corpo documento</td>\n";
    echo "<td colspan=\"3\" style=\"text-align: left;\" valign=\"top\"><select name=\"ST_FONTCORPO\">\n";
    echo "<option value=\"$dati[ST_FONTCORPO]\">$dati[ST_FONTCORPO]</option>\n";
    echo "<option value=\"Arial\">Arial, Helvetica sans serif</option>\n";
    echo "<option value=\"Times\">Times serif</option>\n";
    echo "<option value=\"Courier\">Courier spazi larghi</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";
    echo "<td colspan=\"2\" align=\"left\" valign=\"top\">Grandezza font</td>\n";
    echo "<td colspan=\"3\" style=\"text-align: left;\" valign=\"top\"><select name=\"ST_FONTCORPOSIZE\">\n";
    echo "<option value=\"$dati[ST_FONTCORPOSIZE]\">$dati[ST_FONTCORPOSIZE]</option>\n";
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


    //fino a qui il sistema di gestione è diverso, da qui inizia la stessa routine..
    //passiamo il ciclo funzioni e dividiamo in caso sia una etichetta


    echo "<td colspan=\"2\" align=\"left\" valign=\"top\">Numeri di righe corpo documento</td>\n";
    echo "<td colspan=\"3\" style=\"text-align: left;\" valign=\"top\"><input type=\"text\" size=\"5\" name=\"ST_RPP\" value=\"$dati[ST_RPP]\"> In genere dalle 15 alle 60</td>\n";
    echo "</tr><tr>\n";
    echo "<td colspan=\"2\"style=\"width: 40%;\" align=\"left\" valign=\"top\">Interlinea Righe corpo</td>\n";
    echo "<td colspan=\"3\"style=\"text-align: left;\" valign=\"top\"><select name=\"ST_INTERLINEA\">\n";
    echo "<option value=\"$dati[ST_INTERLINEA]\">$dati[ST_INTERLINEA]</option>\n";
    echo "<option value=\"0\">0 punti</option>\n";
    echo "<option value=\"1\">1 punti</option>\n";
    echo "<option value=\"2\">2 punti</option>\n";
    echo "<option value=\"3\">3 punti</option>\n";
    echo "<option value=\"4\">4 punti</option>\n";
    echo "<option value=\"5\">5 punti</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";



    echo "<td colspan=\"5\" rowspan=\"1\" style=\"width: 100%;\" align=\"center\" valign=\"top\"><br>Selezionare le colonne da far apparire nel corpo<br>
<b><font color=\"RED\"> ATTENZIONE per tutte le colonne che si seleziona SI <br>la somma dei millimetri deve essere max 200 altrimenti il programma non passa</b></font></td>\n";
    echo "</tr>\n";

    echo "<tr><td colspan=\"5\">&nbsp;</td></tr>\n";
    echo "<td rowspan=\"1\" width=\"40%\" align=\"left\" valign=\"top\"><b><i>Tipo Campo</b></i></td>\n";
    echo "<td rowspan=\"1\" align=\"left\" valign=\"top\"><b><i>Apparire ?</b></i></td>\n";
    echo "<td rowspan=\"1\" align=\"left\" valign=\"top\"><b><i>Allineamento</b></i></td>\n";
    echo "<td rowspan=\"1\" align=\"left\" valign=\"top\"><b><i>Nr. Caratteri</b></i></td>\n";
    echo "<td rowspan=\"1\" align=\"left\" valign=\"top\"><b><i>Larghezza mm <br>(pixel se etichetta)</b></i></td>\n";
    
    if ($_eti == "SI")
    {

        layout_menu_tendina(($_cosa = ['CT' => 'NO', 'ALL' => 'NO']), "Bordo tabella", $_campo_ALL, $_campo_CT, "Larghezza Etichetta", "RIGA", "10", "10");
        //azzeriamo l'arrey
        $_cosa = null;

        layout_menu_tendina($_cosa, "Campo $CD002", "Allineamento", "Numero, Caratteri stampabili", "Spessore Riga", "ARTICOLO", "15", "10");

        layout_menu_tendina($_cosa, "Campo $CD003", "Allineamento colonna", "Dimensione testo sotto il codice 0 per vuoto", "Spessore Codice a Barre", "ARTFOR", "20", "15");

        layout_menu_tendina($_cosa, $CD004, "Allienamento", "Numero Caratteri stampabili", "Spessore Della Riga", "DESCRIZIONE", "80", "70");

        layout_menu_tendina(($_cosa = ['CT' => 'NO']), "Immagine Articolo", "Allineamento", $_campo_CT, "Larghezza foto", "QUANTITA", "20", "20");

        layout_menu_tendina(($_cosa = ['CT' => 'NO']), "Intestazione ditta", "Allineamento colonna", $_campo_CT, "Spessore riga", "QTAEVASA", "20", "20");

        layout_menu_tendina(($_cosa = ['CT' => 'NO', 'ALL' => 'NO']), "Riga inferiore testo a piacimento", $_campo_ALL, $_campo_CT, "Spessore Riga", "QTAESTRATTA", "20", "20");

        //azzeriamo l'arrey
        $_cosa = null;

        layout_menu_tendina($_cosa, "Campo $CD009", $_campo_ALL, "Spessore Margine inferiore", "Spessore Margine SX", "QTASALDO", "20", "20");

        layout_menu_tendina(($_cosa = ['CT' => 'NO', 'ALL' => 'NO']), $CD020, $_campo_ALL, $_campo_CT, $_campo_LC, "AVVISO", "50", "100");

        //azzeriamo l'arrey
        $_cosa = null;
    }
    else
    {

        layout_menu_tendina(($_cosa = ['CT' => 'NO', 'ALL' => 'NO']), "Campo $CD001", $_campo_ALL, $_campo_CT, $_campo_LC, "RIGA", "10", "10");
        //azzeriamo l'arrey
        $_cosa = null;

        layout_menu_tendina($_cosa, "Campo $CD002", $_campo_ALL, $_campo_CT, $_campo_LC, "ARTICOLO", "15", "10");

        layout_menu_tendina($_cosa, "Campo $CD003", $_campo_ALL, $_campo_CT, $_campo_LC, "ARTFOR", "20", "15");

        layout_menu_tendina($_cosa, $CD004, $_campo_ALL, $_campo_CT, $_campo_LC, "DESCRIZIONE", "80", "70");

        layout_menu_tendina(($_cosa = ['CT' => 'NO']), "Colonna $CD005", $_campo_ALL, $_campo_CT, $_campo_LC, "UNITA", "10", "");
        //azzeriamo l'arrey
        $_cosa = null;


        layout_menu_tendina($_cosa, "Campo $CD006", $_campo_ALL, $_campo_CT, $_campo_LC, "QUANTITA", "20", "20");

        layout_menu_tendina($_cosa, "Campo $CD007", $_campo_ALL, $_campo_CT, $_campo_LC, "QTAEVASA", "20", "20");

        layout_menu_tendina($_cosa, "Campo $CD008", $_campo_ALL, $_campo_CT, $_campo_LC, "QTAESTRATTA", "20", "20");


        //azzeriamo l'arrey
        $_cosa = null;



        layout_menu_tendina($_cosa, "Campo $CD009", $_campo_ALL, $_campo_CT, $_campo_LC, "QTASALDO", "20", "20");

        layout_menu_tendina($_cosa, "Campo $CD010", $_campo_ALL, $_campo_CT, $_campo_LC, "LISTINO", "20", "20");

        layout_menu_tendina(($_cosa = ['CT' => 'NO', 'ALL' => 'NO', 'LC' => 'NO']), "Avviso Prezzi Netti", $_campo_ALL, $_campo_CT, $_campo_LC, "AVV_PN", "20", "20");
        //azzeriamo l'arrey
        $_cosa = null;

        layout_menu_tendina(($_cosa = ['CT' => 'NO']), "Campo $CD011", $_campo_ALL, $_campo_CT, $_campo_LC, "SCONTI", "20", "20");
        //azzeriamo l'arrey
        $_cosa = null;

        layout_menu_tendina($_cosa, "Campo $CD012", $_campo_ALL, $_campo_CT, $_campo_LC, "NETTO", "20", "20");

        layout_menu_tendina($_cosa, "Campo $CD013", $_campo_ALL, $_campo_CT, $_campo_LC, "TOTRIGA", "20", "20");

        layout_menu_tendina(($_cosa = ['CT' => 'NO']), "Campo $CD014", $_campo_CT, $_campo_LC, "Campo $CD014", "CODIVA", "20", "20");
        //azzeriamo l'arrey
        $_cosa = null;

        layout_menu_tendina(($_cosa = ['CT' => 'NO']), "Campo $CD016", $_campo_CT, $_campo_LC, "Campo $CD016", "RSALDO", "10", "10");
        //azzeriamo l'arrey
        $_cosa = null;

        layout_menu_tendina(($_cosa = ['CT' => 'NO']), "Campo $CD015", $_campo_CT, $_campo_LC, "Campo $CD015", "PESO", "10", "10");
        //azzeriamo l'arrey
        $_cosa = null;

        layout_menu_tendina("", "Campo $CD017", $_campo_ALL, $_campo_CT, $_campo_LC, "CONSEGNA", "20", "15");



        //azzeriamo l'arrey
        $_cosa = null;
    }


    echo "</table>\n";
    echo "</div>\n";

//--------------------------------------------------------------CALCE-------------------------------------------------------------------------
    echo "<div id=\"tabs-4\">\n";
    echo "<table class=\"tabs\">";

    if ($_eti != "SI")
    {
        
//scelta della calce
        echo "<td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\"><br>Tipo di calce documento</span></td>\n";
        echo "</tr><tr>\n";
        echo "<td colspan=\"2\" align=\"left\" valign=\"top\">Tipo uno = Classico per i d.d.t. con colli, peso e lo spazio per firmare<br>\n";
        echo "Tipo Due = Semplice con i totali a destra ed un ampio spazio per le annotazioni ottimo per conferme, ordini ecc.<br>\n";
        echo "Tipo Tre = Semplice e completo classico delle fatture normali<br>\n";
        echo "Tipo Quattro = Completo di tutto ottimo per le fatture immediate, ci sono colli peso e lo spazio per firmare<br>\n";
        echo "<select name=\"ST_TIPOCALCE\">\n";
        echo "<option value=\"$dati[ST_TIPOCALCE]\">$dati[ST_TIPOCALCE]</option>\n";
        echo "<option value=\"1\">1 Classico DDT</option>\n";
        echo "<option value=\"2\">2 Ottimo per conferme ordini</option>\n";
        echo "<option value=\"3\">3 Classico per fatture</option>\n";
        echo "<option value=\"4\">4 Perfetto per le fatture immediate</option>\n";
        echo "<option value=\"5\">5 Tipo calce per inventario Magazzino</option>\n";
        echo "<option value=\"6\">6 Somma della pagina quantità e valore</option>\n";
        echo "</select>\n";
        echo "</tr><tr>\n";
    }

    layout_menu_tendina(($_cosa = ['CT' => 'NO']), $CD020, $_campo_ALL, $_campo_CT, $_campo_LC, "AVVISO", "50", "100");
    echo "</table></div>\n";

//--------------------------------------------------------------AUTOMATISMI-------------------------------------------------------------------------
    echo "<div id=\"tabs-5\">\n";
    echo "<table class=\"tabs\">";


    if ($_eti != SI)
    {

        echo "<tr><td colspan=\"2\" rowspan=\"1\" style=\"width: 100%;\" align=\"center\" valign=\"top\"><h3>Impostazioni predefinite per la stampa</h3></td>\n";
        echo "</tr><tr>\n";

        echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\"><B>Stampa Logo nel documento ?</B></td>\n";
        echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\">\n";
        echo "<select name=\"ST_LOGOP\">\n";
        if($dati[ST_LOGOP] == "")
        {
            $dati[ST_LOGOP] = "NO";
        }
        echo "<option value=\"$dati[ST_LOGOP]\">$dati[ST_LOGOP]</option>\n";
        echo "<option value=\"SI\">SI</option>\n";
        echo "<option value=\"NO\">NO</option>\n";
        echo "</select></td>\n";
        echo "</tr><tr>\n";
        
        echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\"><B>Stampa prezzi nel documento ?</B></td>\n";
        echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\">\n";
        echo "<select name=\"ST_PREZZI\">\n";
        if($dati[ST_PREZZI] == "")
        {
            $dati[ST_PREZZI] = "NO";
        }
        echo "<option value=\"$dati[ST_PREZZI]\">$dati[ST_PREZZI]</option>\n";
        echo "<option value=\"SI\">SI</option>\n";
        echo "<option value=\"NO\">NO</option>\n";
        echo "</select></td>\n";
        echo "</tr><tr>\n";

        
        echo "<td style=\"width: 60%;\" align=\"left\" valign=\"top\"><B>Stampa data e ora consegna ?</B></td>\n";
        echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"ST_DATA\">\n";
        if($dati[ST_DATA] == "")
        {
            $dati[ST_DATA] = "NO";
        }
        echo "<option value=\"$dati[ST_DATA]\">$dati[ST_DATA]</option>\n";
        echo "<option value=\"SI\">SI</option>\n";
        echo "<option value=\"NO\">NO</option>\n";
        echo "</select></td>\n";
        echo "</tr><tr>\n";

        echo "<td colspan=\"2\"><br><hr></td><br></tr><tr>\n";
        echo "<td colspan=\"2\" rowspan=\"1\" style=\"width: 100%;\" align=\"center\" valign=\"top\">Messaggio predefinito per invio documenti</td>\n";
        echo "</tr><tr>\n";

        echo "<td colspan =\"2\" align=\"center\" valign=\"top\"><B>Messaggio da includere</B><br>\n";
        echo "<textarea name=\"BODY\" rows=\"30\" columns=\"60\">$dati[BODY]</textarea></td>\n";
        echo "</tr>\n";
    }

    echo "</table></div>\n";

    if (($_azione == "Nuovo") AND (( $_eti == "SI") OR ( $_lis == "SI")))
    {
        echo "<td colspan=2 align=center><input type=\"submit\" name=\"azione\" value=\"Inserisci\"></td></tr>\n";
    }
    elseif (($_azione == "Modifica") AND (( $_eti == "SI") OR ( $_lis == "SI")))
    {
        echo "<td colspan=2 align=center><input type=\"submit\" name=\"azione\" value=\"Aggiorna\"> - <input type=\"submit\" name=\"azione\" value=\"Elimina\"></td></tr>\n";
    }
    else
    {
        echo "<td colspan=2 align=center><input type=\"submit\" name=\"azione\" value=\"Aggiorna\"></td></tr>\n";
    }

    echo "</tr></table></div>\n";

    echo "<br>&nbsp;<br>&nbsp;<br>&nbsp;\n";
    echo "</body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>