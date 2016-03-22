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

    //prendiamo i valori..
    $_azione = substr($_GET['azione'], 0, 3);
    $_anno = substr($_GET['azione'], 3, 4);
    $_nscad = substr($_GET['azione'], 7, 10);


    echo "<table align=\"left\" width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\">\n";
    echo "<form action=\"\" id=\"pulsanti\" method=\"GET\">";

    echo "<center>\n";
    pulsanti("home", "submit", "", "get", "../index.php", "40px", "40px", "Indice", "", "", "Cerca", $_id);
    pulsanti("cerca", "submit", "", "get", "cerca_scad.php", "40px", "40px", "Cerca", "", "", "Cerca", $_id);
    pulsanti("nuovo", "submit", "", "get", "scadenza.php", "40px", "40px", "Nuova", "azione", "nuo", "Nuova", $_id);
    pulsanti("modifica", "submit", "", "get", "scadenza.php", "40px", "40px", "Modifica", "azione", "mod$_anno$_nscad", "Modifica", $_id);
    pulsanti("calendario", "submit", "", "get", "index.php", "40px", "40px", "Calendario", "azione", "", "Calendario", $_id);
    pulsanti("aiuto", "submit", "_blank", "get", "../manuale/visualizza_guida.php?file=M0701.html", "40px", "40px", "Aiuto", "file", "M0701.html", "Aiuto", $_id);
    echo "</form>\n";

    echo "<h3 align=\"center\">Scadenze ed Appuntamenti in agenda</h3>";

    echo "<table align=\"left\" class=\"classic\" width=\"80%\">\n";
    //$_azione = $_GET['azione'];

    if (($_GET['azione'] == "nuo") OR ( $_GET['giorno'] != ""))
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
        $_parametri['nscad'] = $_nscad;
        $_parametri['anno'] = $_anno;

        $_scadenza = tabella_scadenziario("singola", $_percorso, $_parametri);

        $_riga = "<tr><td width=\"250\">N. scadenza</td><td align=\"left\"><input type=\"radio\" name=\"anno\" value=\"$_scadenza[anno]\" checked>$_scadenza[anno] / <input type=\"radio\" name=\"nscad\" value=\"$_scadenza[nscad]\" checked>$_scadenza[nscad]</td></tr>\n";
        $_pulsante_1 = "Elimina";
        $_pulsante_2 = "Aggiorna";
    }

    if ($_scadenza['status'] != "saldato")
    {
        echo "<form action=\"result_scad.php\" enctype=\"multipart/form-data\" method=\"post\">\n";
    }


    if ($_azione == "vis")
    {
        
        echo $_riga;
        echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
        
        echo "<tr><td>Data Scadenza</td><td align=\"left\">" . cambio_data("it", $_scadenza[data_scad]) . "</td></tr>\n";
        echo "<tr><td>Descrizione</td><td align=\"left\">$_scadenza[descrizione]</td></tr>\n";
        echo "<tr><td>Importo</td><td align=\"left\">$_scadenza[importo]</td></tr>\n";
        echo "<tr><td>Fornitore</td><td align=\"left\">\n";
        if($_scadenza['utente'] != "")
        {
           $utente = tabella_fornitori("singola", $_scadenza['utente'], "utente"); 
        }
        
        echo $utente['ragsoc'] . "</td></tr>\n";

        echo "<tr><td>Documento fornitore Nr. </td><td align=\"left\">$_scadenza[ndoc] / $_scadenza[anno_doc]  Del " . cambio_data("it", $_scadenza[data_doc]) . "</td></tr>\n";
        echo "<tr><td>Protocollo</td><td align=\"left\">$_scadenza[nproto] / $_scadenza[suffix_proto] anno $_scadenza[anno_proto]\n";
        
        if(($_scadenza[nproto] != "") AND ($_scadenza[suffix_proto] != "") AND ($_scadenza[anno_proto] != ""))
        {
            //verifichiamo se abbiamo un file con lo stesso nome..
            if (is_file($_percorso."../setting/fatture_acq/FA_".$_scadenza['anno_proto'].$_scadenza['suffix_proto'].$_scadenza['nproto'].".pdf" ))
            {
                //vuo dire che c'è..
                echo "<font color=\"green\"><a href=\"".$_percorso."../setting/fatture_acq/FA_".$_scadenza[anno_proto].$_scadenza[suffix_proto].$_scadenza[nproto].".pdf\" target=\"_blank\"> Trovata Fattura <img src=\"../images/pdf.png\" width=\"35px\"> </a></font>\n";
                
            }
            else
            {
                echo "<font color=\"white\">Nessuna Fattura in pdf trovata</font>\n";
            }
            
        }
        
        
        echo "</td></tr>\n";
        echo "<tr><td>Pagamento</td><td align=\"left\">\n";
        $_pagamento = tabella_pagamenti("singola", $_scadenza['codpag'], "codpag");
        echo $_pagamento['descrizione'];

        echo "</td></tr>\n";

        echo "<tr><td>Banca</td><td align=\"left\">\n";
//prendiamoci la banca..
        $appoggio = tabella_banche("singola", $_scadenza['banca'], $_abi, $_cab, "banca");
        echo $appoggio['banca'];
        echo "</td></tr>\n";
        echo "<tr><td>Importo Scadenza</td><td align=\"left\">$_scadenza[impeff]</td></tr>\n";
        echo "<tr><td>Data Pagamento/insoluto</td><td align=\"left\">" . cambio_data("it", $_scadenza[data_pag]) . "</td></tr>\n";
        echo "<tr><td>Status &nbsp;</td><td align=\"left\"> $_scadenza[status]</td></tr>\n";
        echo "<tr><td>Note:</td><td align=\"left\">$_scadenza[note]</td></tr>\n";
        echo "</table>\n";
    }
    else
    {
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


        echo "<tr><td>Data Pagamento/insoluto</td><td align=\"left\"><input type=\"text\" class=\"data\" name=\"data_pag\" value=\"" . cambio_data("it", $_scadenza[data_pag]) . "\" size=\"11\" maxlength=\"10\"> gg-mm-aaaa <br>&nbsp;</td></tr>\n";
        if ($CONTABILITA == "SI")
        {

            echo "<tr><td>Collegare contabilità</td><td align=\"left\"><span class=\"testo_blu\"><font color=\"red\">Registrare prima nota ? <input type=\"checkbox\" name=\"primanota\" value=\"SI\">\n";
            echo "</td></tr>\n";
        }
        else
        {
            echo "<tr><td><font color=\"red\">Allegare file in pdf fornitore ?</font></td><td align=\"left\">\n";
            echo "<input name=\"MAX_FILE_SIZE\" type=\"hidden\" value=\"16777216\" />\n";
        #<!--campo per la scelta del file-->
            echo "<input size=\"50\" id=\"file\" name=\"file\" type=\"file\"  /><br>&nbsp;\n";
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
    }


    echo "</form>\n";
    echo "</td></tr></table></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>