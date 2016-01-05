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
require "../../librerie/motore_doc_pdo.php";
require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{


    java_script($_cosa, $_percorso);

    jquery_datapicker($_cosa, $_percorso);

    echo "</head>\n";
    echo "<body>\n";

    if ($CONTABILITA == "SI")
    {
        require "../../../setting/par_conta.inc.php";
    }

#prendo il post dalla pagina precedente..

    if ($_GET['azione'] != "")
    {
        $_azione = $_GET['azione'];
    }
    else
    {
        $_azione = $_POST['azione'];
    }

    $_codutente = $_POST['utente'];
    $_annoeff = $_GET['annoeff'];
    $_numeff = $_GET['numeff'];

//selezioniamo tutti i dati del cliente
    $dati = tabella_clienti("singola", $_codutente, "");


//visto che divido le azioni divido le query...

    if ($_POST['azione'] == "Inserisci")
    {

        //controllo che il campo codutente sia pieno
        if ($_codutente == "")
        {
            echo "<h2>ATTENZIONE NESSUN UTENTE SELEZIONATO<h2>\n";
            echo "<h3>Si prega di tornare indietro e verificare</h3>\n";
            exit;
        }


        //    Mi prendo il codice cliente e poi leggo il pagamento associato al cliente e
        //    dopo leggo il database effetti e fornisco l'ultimo effetto inserito'

        $dati_pag = tabella_pagamenti("singola", $dati['codpag'], $_parametri);

        $_annoeff = date("Y");
        $_dataeff = date("d-m-Y");
        $_datapag = '00-00-0000';

        //Ora ci prendiamo l'ultimo numero effetto.
        $_numeff = tabella_effetti("ultimo_numero", $_percorso, $_annoeff, $_numeff, $_parametri);

        $_submit = "Inserisci";
    }
    else
    {

        $dati_eff = tabella_effetti("singola", $_percorso, $_annoeff, $_numeff, $_parametri);

        #aggiungiamo un numero
        $_codutente = $dati_eff['codcli'];
        $_tipoeff = $dati_eff['tipoeff'];
        $_dataeff = $dati_eff['dataeff'];
        $_scadeff = $dati_eff['scadeff'];
        $_impeff = $dati_eff['impeff'];
        $_tipodoc = $dati_eff['tipodoc'];
        $_annodoc = $dati_eff['annodoc'];
        $_suffixdoc = $dati_eff['suffixdoc'];
        $_numdoc = $dati_eff['numdoc'];
        $_datadoc = $dati_eff['datadoc'];
        $_totdoc = $dati_eff['totdoc'];
        $_status = $dati_eff['status'];
        $_datapag = $dati_eff['datapag'];
        $_spese_insoluto = $dati_eff['spese'];

        $dataiva['1'] = "1 - Iva divisa sulle varie rate";
        $dataiva['2'] = "2 - Iva applicata totalmente sulla prima rata";
        $dataiva['3'] = "3 - Iva applicata totalmente sull' ultima rata";
        $dataiva['4'] = "4 - La prima rata &egrave; solo l'iva";

        // e per la tipologia pagamento

        $paga['1'] = "1 - Rimessa diretta";
        $paga['2'] = "2 - Contanti";
        $paga['3'] = "3 - Ricevuta bancaria";
        $paga['4'] = "4 - Tratta o cambiale";
        $paga['5'] = "5 - Contrassegno";
        $paga['6'] = "6 - Bonifico Bancario";
        $paga['7'] = "7 - Ricevimento Fattura";

        $dati = tabella_clienti("singola", $_codutente, "");

        $_submit = "Modifica";
    }

//Inizio parte Visisva...



    intesta_html($_tdoc, "normale", $dati, "");

// Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\" border=\"0\">\n";
    echo "<tr><td align=\"center\">$_azione Effetto</td></tr>\n";
    echo "<tr><td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";


    echo "<form action=\"result_eff.php\" method=\"POST\">";
    echo "<table width=\"80%\" border=\"0\"";

//qui dividiamo la parte dell'effetto da saldare da il resto della pagina modifica..
    if ($_azione == "Salda")
    {

        echo "<td colspan=\"6\" align=\"center\"><span class=\"testo_blu\"><hr><b>DATI EFFETTO<br>&nbsp;</b></span></td></tr>\n";
        echo "<tr><td colspan=\"1\" align=\"left\"><span class=\"testo_blu\"><b>Codice cliente:&nbsp;</b></span></td>\n";
        echo "<td align=\"left\"colspan=\"5\" ><input type=\"radio\" name=\"codutente\" value=\"$dati[codice]\" checked>$dati[codice]</span></td>\n";
        echo "<tr><td colspan=\"1\" align=\"left\"><span class=\"testo_blu\"><b>Tipo effetto:&nbsp;</b></span></td>\n";
        echo "<td align=\"left\"colspan=\"5\" ><input type=\"radio\" name=\"tipoeff\" value=\"$_tipoeff\" checked> $paga[$_tipoeff]";
        echo "</td></tr>";
        echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>N. eff.:&nbsp;</b></span></td>\n";
        echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"numeff\" value=\"$_numeff\" checked>$_numeff</td>\n";
        echo "<td align=\"left\"><span class=\"testo_blu\"><b>anno. effetto:&nbsp;</b></span></td>\n";
        echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"annoeff\" value=\"$_annoeff\" checked>$_annoeff</td>\n";
        echo "<td align=\"left\"><span class=\"testo_blu\"><b>data eff. :&nbsp;</b></span></td>\n";
        echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"dataeff\" value=\"$_dataeff\" checked>$_dataeff</td>\n";

        echo "<tr><td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Importo eff.:&nbsp;</b></span></td>\n";
        echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"impeff\" value=\"$_impeff\" checked>$_impeff</td><td>&nbsp;</td>\n";
        echo "<td>&nbsp;</td><td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>scad. effetto:&nbsp;</b></span></td>\n";
        echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" class=\"data\" name=\"scadeff\" value=\"$_scadeff\" checked>$_scadeff</td>\n";

        echo "<tr><td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Status &nbsp;</b></span></td>\n";
        echo "<td colspan=\"1\"><select name=\"status\">\n";
        printf("<option value=\"%s\"> %s </option>", $_status, $_status);
//    echo "<option value=\"in attesa\">In attesa</option>\n";
//    echo "<option value=\"inserito\">Inserito</option>\n";
        echo "<option value=\"insoluto\">insoluto</option>\n";
        echo "<option value=\"richiamato\">Richiamato</option>\n";
//    echo "<option value=\"riemesso\">Riemesso</option>\n";
        echo "<option value=\"saldato\">saldato</option>\n";
        echo " </select>";
        echo "<td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Data pagamento / Insoluto&nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" colspan=\"1\" align=\"left\"><input type=\"text\" class=\"data\" size=\"10\" maxlenght=\"10\" name=\"datapag\" value=\"%s\"></td>", $_datapag);
        echo "<td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Eventuali spese&nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" colspan=\"1\" align=\"left\"><input type=\"text\" size=\"10\" maxlenght=\"10\" name=\"spese_insoluto\" value=\"%s\"></span></td></tr>", $_spese_insoluto);
        echo "<tr><td colspan=\"6\" align=\"right\"><span class=\"testo_blu\"><font color=\"red\">Registrare prima nota ? </span><input type=\"checkbox\" name=\"primanota\" value=\"SI\">\n";
        echo "</td></tr>\n";

        $_submit = "Salda";
    }
    elseif ($_azione == "Elimina")
    {
        if ($CONTABILITA == "SI")
        {
            #Per eliminare un effetto, deve presentare certe condizioni, come non essere presentato o saldato..
            //altrimenti lo inseriamo..
            $query = "DELETE FROM effetti WHERE contabilita != 'SI' AND annoeff=\"$_annoeff\" AND numeff=\"$_numeff\"";
        }
        else
        {
            #Per eliminare un effetto, deve presentare certe condizioni, come non essere presentato o saldato..
            //altrimenti lo inseriamo..
            $query = "DELETE FROM effetti WHERE annoeff=\"$_annoeff\" AND numeff=\"$_numeff\"";
        }

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $_messaggio = "Errore... Effetto non Eliminato contabilità ?\n";
        }
        else
        {

            $_messaggio = "Ok.. Effetto Eliminato con successo\n";

            //ripristiniamo anche il documento che l'ha generato..
            $query = "UPDATE fv_testacalce SET status='ripristina' WHERE tdocevaso='effetto' AND evasonum='$_numeff' AND evasoanno='$_annoeff'";

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);

                $_messaggio1 = "Errore.... Documento non ripristinato\n";
            }
            else
            {
                $_messaggio1 = "Ok.. Documento ripristinato con successo\n";
            }
        }


        echo "<center>\n";
        echo "<h2>Gestione Effetti</h2>\n";
        echo "<h3>$_messaggio</h3>\n";
        echo "<h3>$_messaggio1</h3>\n";
        echo "</center>\n";
        exit;
    }
    else
    {


// CAMPO Codice ---------------------------------------------------------------------------------------
        echo "<tr><td colspan=\"6\" align=\"center\"><span class=\"testo_blu\"><hr><b>DATI CLIENTE<br>&nbsp;</b></span></td></tr>\n";
        echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice cliente:&nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" align=\"left\" colspan=\"1\"><input type=\"radio\" name=\"codutente\" value=\"%s\" checked>%s</td>", $dati['codice'], $dati['codice']);
        echo "<td align=\"left\"><span class=\"testo_blu\">Pagamento solito cliente </td>\n";
        echo "<td align=\"left\" colspan=\"3\"><font face=\"arial\" size=\"2\">$dati_pag[descrizione]</font></td></tr>\n";
        echo "<td align=\"left\"><span class=\"testo_blu\">Banca appoggio </td>\n";
        echo "<td align=\"left\" colspan=\"2\"><font face=\"arial\" size=\"2\">$dati[banca]</font></td>\n";
        echo "<td align=\"left\" colspan=\"3\"><font face=\"arial\" size=\"2\">ABI $dati[abi] CAB $dati[cab]</font></td></tr>\n";



        echo "<tr><td colspan=\"6\" align=\"center\"><span class=\"testo_blu\"><hr><b>DATI EFFETTO<br>&nbsp;</b></span></td></tr>\n";

        echo "<tr><td colspan=\"1\" align=\"left\"><span class=\"testo_blu\"><b>Tipo effetto:&nbsp;</b></span></td>\n";
        echo "<td align=\"left\"colspan=\"5\" >";
        echo "<select name=\"tipoeff\">\n";
        printf("<option value=\"%s\"> %s </option>", $_tipoeff, $paga[$_tipoeff]);
        echo "<option value=\"1\"> 1 - Rimessa diretta</option>\n";
        echo "<option value=\"2\"> 2 - Contanti</option>\n";
        echo "<option value=\"3\"> 3 - Ricevuta bancaria </option>\n";
        echo "<option value=\"4\"> 4 - Tratta o cambiale </option>\n";
        echo "<option value=\"5\"> 5 - Contrassegno </option>\n";
        echo "<option value=\"6\"> 6 - Bonifico Bancario</option>\n";
        echo "<option value=\"7\"> 7 - Ricevimento Fattura</option>\n";
        echo " </select>";
        echo "</td></tr>";
        if ($_azione == "Modifica")
        {
            echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>N. eff.:&nbsp;</b></span></td>\n";
            echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"numeff\" value=\"$_numeff\" checked>$_numeff</td>";
            echo "<td align=\"left\"><span class=\"testo_blu\"><b>anno. effetto:&nbsp;</b></span></td>\n";
            echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"annoeff\" value=\"$_annoeff\" checked>$_annoeff</td>";
            echo "<td align=\"left\"><span class=\"testo_blu\"><b>data eff. :&nbsp;</b></span></td>\n";
            echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"dataeff\" value=\"$_dataeff\" checked>$_dataeff</td>";
        }
        else
        {
            echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>N. eff.:&nbsp;</b></span></td>\n";
            printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" size=\"6\" maxlenght=\"6\" name=\"numeff\" value=\"%s\"></td>", $_numeff);
            echo "<td align=\"left\"><span class=\"testo_blu\"><b>anno. effetto:&nbsp;</b></span></td>\n";
            printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" size=\"4\" maxlenght=\"4\" name=\"annoeff\" value=\"%s\"></td>", $_annoeff);
            echo "<td align=\"left\"><span class=\"testo_blu\"><b>data eff. :&nbsp;</b></span></td>\n";
            printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" size=\"10\" class=\"data\" maxlenght=\"10\" name=\"dataeff\" value=\"%s\"></td></tr>", $_dataeff);
        }
        echo "<tr><td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Importo eff.:&nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" colspan=\"1\" align=\"left\"><input type=\"text\" size=\"10\" maxlenght=\"16\" name=\"impeff\" value=\"%s\"></td>", $_impeff);
        echo "<td colspan=2>&nbsp;</td><td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>scad. effetto:&nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" colspan=\"1\" align=\"left\"><input type=\"text\" class=\"data\" size=\"10\" maxlenght=\"10\" name=\"scadeff\" value=\"%s\"></td></tr>", $_scadeff);

        echo "<tr><td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Status &nbsp;</b></span></td>\n";
        echo "<td colspan=\"1\"><select name=\"status\">\n";
        printf("<option value=\"%s\"> %s </option>", $_status, $_status);
        echo "<option value=\"in attesa\">in attesa</option>\n";
        echo "<option value=\"inserito\">inserito</option>\n";
        echo "<option value=\"insoluto\">insoluto</option>\n";
        echo "<option value=\"presentato\">presentato</option>\n";
        echo "<option value=\"parziale\">Parziale</option>\n";
        echo "<option value=\"richiamato\">richiamato</option>\n";
        echo "<option value=\"riemesso\">riemesso</option>\n";
        echo "<option value=\"saldato\">saldato</option>\n";
        echo " </select>";
        echo "<td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Data pagamento / Insoluto&nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" colspan=\"1\" align=\"left\"><input type=\"text\" size=\"10\" class=\"data\" maxlenght=\"10\" name=\"datapag\" value=\"%s\"></td>", $_datapag);
        echo "<td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Eventuali spese&nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" colspan=\"1\" align=\"left\"><input type=\"text\" size=\"10\" maxlenght=\"10\" name=\"spese_insoluto\" value=\"%s\"></td></tr>", $_spese_insoluto);

        echo "</td></tr>\n";
        if ($CONTABILITA == "SI")
        {
            //qui selezioniamo la banca di corrtispondenza per l'eventuale collegamento con la contabilita
            echo "<tr><td colspan=\"6\"><span class=\"testo_blu\">Nel caso di un pagamento vogliate segliere abbinamento conto ";
            echo "<select name=\"banca\"><option value=\"\"></option>\n";
            echo "<option value=\"$CONTO_CASSA\">Cassa Contanti</option>\n";
            echo "<option value=\"$CONTO_ASSEGNI\">Cassa Assegni</option>\n";
            echo "<option value=\"$CONTO_COMPENSAZIONI\">Conto Compensazioni Cli. / For.</option>\n";
            $res = tabella_banche("elenca", $_codice, $_abi, $_cab, "");
            foreach ($res AS $banca)
            {
                echo "<option value=\"$banca[codice]\">$banca[banca]</option>\n";
            }
            echo "</select></td></tr>\n";
            echo "<tr><td colspan=\"6\" align=\"right\"><span class=\"testo_blu\"><font color=\"red\">Registrare prima nota ? <input type=\"checkbox\" name=\"primanota\" value=\"SI\">\n";
            echo "</td></tr>\n";
        }



        echo "<tr><td colspan=\"6\" align=\"center\"><span class=\"testo_blu\"><hr><b>DATI DOCUMENTO<br>&nbsp;</b></span></td></tr>\n";

        echo "<td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Tipo Doc. &nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" colspan=\"2\" align=\"left\"><input type=\"text\" size=\"30\" maxlenght=\"30\" name=\"tipodoc\" value=\"%s\"></td>", $_tipodoc);
        echo "<td align=\"left\" colspan=\"1\"><span class=\"testo_blu\"><b>Anno doc. &nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" colspan=\"2\" align=\"left\"><input type=\"text\" size=\"4\" maxlenght=\"4\" name=\"annodoc\" value=\"%s\"></td></tr>", $_annodoc);
        echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Num. Doc. &nbsp;</b></span></td>\n";
        echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" size=\"6\" maxlenght=\"6\" name=\"numdoc\" value=\"$_numdoc\"> / \n";
        echo "<span class=\"testo_blu\">Suff. <input type=\"text\" size=\"2\" maxlenght=\"1\" name=\"suffixdoc\" value=\"$_suffixdoc\"</td>\n";
        echo "<td align=\"left\"><span class=\"testo_blu\"><b>data Doc. :&nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" size=\"10\" maxlenght=\"10\" class=\"data\" name=\"datadoc\" value=\"%s\"></td>", $_datadoc);
        echo "<td align=\"left\"><span class=\"testo_blu\"><b>Imp. Doc. :&nbsp;</b></span></td>\n";
        printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" size=\"10\" maxlenght=\"16\" name=\"totdoc\" value=\"%s\"></td></tr>", $_totdoc);
    }

    echo "<tr><td colspan=\"6\" align=\"center\"><span class=\"testo_blu\"><hr></span></td></tr>\n";

// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "</table>\n<br><input type=\"submit\" name=\"azione\" value=\"$_submit\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Annulla\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
// ************************************************************************************** -->
    echo "</table>\n";
// Fine tabella pagina princ
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>