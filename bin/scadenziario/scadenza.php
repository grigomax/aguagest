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

require_once $_percorso . "librerie/motore_anagrafiche.php";



if ($_SESSION['user']['scadenziario'] > "2")
{


    base_html("", $_percorso);
    java_script($_cosa, $_percorso);
    jquery_datapicker($_cosa, $_percorso);
    tiny_mce($_cosa, $_percorso);
    echo "</head>";

    testata_html($_cosa, $_percorso);
    menu_tendina($_cosa, $_percorso);


    echo "<table align=\"left\" width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\">\n";
    echo "<h3 align=\"center\">Scadenze ed Appuntamenti in agenda</h3>";

    //$_azione = $_GET['azione'];

    if (($_GET['azione'] == "nuova") OR ( $_GET['giorno'] != ""))
    {
        echo "<h3 align=\"center\">Inserisci scadenza</h3>";
        //creiamo una tabella con l'inserimento deidati..
        $_pulsante_1 = "Annulla";
        $_pulsante_2 = "Inserisci";

        $_scadenza['status'] = "in attesa";
        $_scadenza['data_scad'] = $_GET['giorno'];
        //echo cambio_data("it", $_scadenza[data_scad]);
    }
    else
    {
        //andiamo a vidualizzarla...
        //prendiamoci il GET
        $_parametri['nscad'] = $_GET['nscad'];
        $_parametri['anno'] = $_GET['anno'];

        $_scadenza = tabella_scadenziario("singola", $_percorso, $_parametri);

        $_riga = "<tr><td>N. scadenza</td><td align=\"left\"><input type=\"radio\" name=\"anno\" value=\"$_scadenza[anno]\" checked>$_scadenza[anno] / <input type=\"radio\" name=\"nscad\" value=\"$_scadenza[nscad]\" checked>$_scadenza[nscad]</td></tr>\n";
        $_pulsante_1 = "Elimina";
        $_pulsante_2 = "Aggiorna";
    }

    if ($_scadenza['status'] != "saldato")
    {
        echo "<form action=\"result_scad.php\" method=\"post\">\n";
    }


    echo "<table align=\"left\" class=\"classic\">\n";
    echo $_riga;
    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
    echo "<tr><td>Data Scadenza</td><td align=\"left\"><input type=\"text\" class=\"data\" name=\"data_scad\" value=\"" . cambio_data("it", $_scadenza[data_scad]) . "\" size=\"11\" maxlength=\"10\" required> gg-mm-aaaa</td></tr>\n";
    echo "<tr><td>Descrizione</td><td align=\"left\"><input type=\"text\" name=\"descrizione\" value=\"$_scadenza[descrizione]\" size=\"60\" maxlength=\"80\" required></td></tr>\n";
    echo "<tr><td>Importo</td><td align=\"left\"><input type=\"text\" name=\"importo\" value=\"$_scadenza[importo]\" size=\"12\" maxlength=\"19\" required> xxx.xx</td></tr>\n";
    echo "<tr><td>Fornitore</td><td align=\"left\">\n";

//non se dito che gavemo un fornitore..
//
    #$_fornitore = tabella_fornitori("singola", $_scadenza[utente], "");
//prendiamoci tutti i fornitori
    tabella_fornitori("elenca_select_2", $_scadenza['utente'], "utente");

//azzeriamo l'array
    $dati = "";
    echo "</select>\n";
    echo "</td></tr>";
    echo "<tr><td>Anno_doc</td><td align=\"left\"><input type=\"text\" name=\"anno_doc\" value=\"$_scadenza[anno_doc]\" size=\"5\" maxlength=\"5\"> aaaa</td></tr>\n";
    echo "<tr><td>Numero Documento</td><td align=\"left\"><input type=\"text\" name=\"ndoc\" value=\"$_scadenza[ndoc]\" size=\"15\" maxlength=\"20\"></td></tr>\n";
    echo "<tr><td>Data Documento</td><td align=\"left\"><input type=\"text\" class=\"data\" name=\"data_doc\" value=\"" . cambio_data("it", $_scadenza[data_doc]) . "\" size=\"11\" maxlength=\"10\"> gg-mm-aaaa</td></tr>\n";
    echo "<tr><td>Anno Protocollo</td><td align=\"left\"><input type=\"text\" name=\"anno_proto\" value=\"$_scadenza[anno_proto]\" size=\"5\" maxlength=\"4\"> aaaa</td></tr>\n";
    echo "<tr><td>Suffisso Protocollo</td><td align=\"left\"><input type=\"text\" name=\"suffix_proto\" value=\"$_scadenza[suffix_proto]\" size=\"1\" maxlength=\"1\"> A => Z</td></tr>\n";
    echo "<tr><td>Numero Protocollo</td><td align=\"left\"><input type=\"text\" name=\"nproto\" value=\"$_scadenza[nproto]\" size=\"10\" maxlength=\"10\">  In caso di pagamento delega inserire il numero del conto del piano dei conti</td></tr>\n";
    echo "<tr><td>Pagamento</td><td align=\"left\">\n";



    tabella_pagamenti("elenca_select_2", $_scadenza['codpag'], "codpag");

    echo "</td></tr>";

    echo "<tr><td>Banca</td><td align=\"left\">\n";
//prendiamoci la banca..
    $appoggio = tabella_banche("singola", $_scadenza['banca'], $_abi, $_cab, "banca");
    echo "<select name=\"banca\">\n";

    echo "<option value=\"$appoggio[codice]\">$appoggio[banca]</option>\n";
    echo "<option value=\"\"></option>\n";
    echo "<option value=\"$CONTO_CASSA\">Cassa Contanti</option>\n";
    echo "<option value=\"$CONTO_ASSEGNI\">Cassa Assegni</option>\n";
    echo "<option value=\"$CONTO_COMPENSAZIONI\">Conto Compensazioni Cli. / For.</option>\n";

    $res = tabella_banche("elenca", "", $_abi, $_cab, "");
    foreach ($res AS $banca)
    {
        echo "<option value=\"$banca[codice]\">$banca[banca]</option>\n";
    }
    echo "</select></td></tr>\n";


    echo "</td></tr>";

    echo "<tr><td>Importo Scadenza</td><td align=\"left\"><input type=\"text\" name=\"impeff\" value=\"$_scadenza[impeff]\" size=\"15\" maxlength=\"19\"> xxx.xx</td></tr>\n";

    echo "<tr><td>Status &nbsp;</td>\n";
    echo "<td align=\"left\"><select name=\"status\">\n";
    echo "<option value=\"$_scadenza[status]\">$_scadenza[status]</option>\n";
    echo "<option value=\"\"></option>\n";
    echo "<option value=\"in attesa\">in attesa</option>\n";
    echo "<option value=\"insoluto\">insoluto</option>\n";
    echo "<option value=\"richiamato\">Richiamato</option>\n";
    echo "<option value=\"riemesso\">Riemesso</option>\n";
    echo "<option value=\"parziale\">Parziale</option>\n";
    echo "<option value=\"saldato\">saldato</option>\n";
    echo " </select>";


    echo "<tr><td>Data Pagamento/insoluto</td><td align=\"left\"><input type=\"text\" class=\"data\" name=\"data_pag\" value=\"" . cambio_data("it", $_scadenza[data_pag]) . "\" size=\"11\" maxlength=\"10\"> gg-mm-aaaa</td></tr>\n";

    if ($CONTABILITA == "SI")
    {

        echo "<tr><td>Collegare contabilità</td><td align=\"left\"><span class=\"testo_blu\"><font color=\"red\">Registrare prima nota ? <input type=\"checkbox\" name=\"primanota\" value=\"SI\">\n";
        echo "</td></tr>\n";
    }



    echo "<tr><td>Note:</td><td align=\"left\"><textarea name=\"note\" style=\"width:100%; height:250px;\">$_scadenza[note]</textarea></td></tr>\n";


    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

    echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"azione\" value=\"$_pulsante_2\">  oppure  <input type=\"submit\" name=\"azione\" value=\"$_pulsante_1\" onclick=\"if(!confirm('Sicuro di $_pulsante_1 la registrazione ?')) return false;\"></td></tr>\n";

    if ($_pulsante_1 == "Elimina")
    {
        echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"azione\" value=\"Annulla\"></td></tr>\n";
    }
    echo "</table>\n";
    echo "</form>\n";
    echo "</td></tr></table></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>