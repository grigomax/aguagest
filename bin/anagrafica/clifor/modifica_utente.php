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

require "../../librerie/motore_anagrafiche.php";


if ($CONTABILITA == "SI")
{
    include "../../../setting/par_conta.inc.php";
}


$_tut = $_GET['tut'];
$_codice = $_POST['codice'];
$_azione = $_GET['azione'];

if ($_tut == "c")
{
    $_tipo = "clienti";
    $tipo_cf = "C";
    if ($_azione == "nuovo")
    {
        $_codice = cerca_verifica_numero("cerca_libera", $_tipo, $_parametri);
        $_submit = "Inserisci";
        $_data_reg = date('d-m-Y');
    }
    else
    {
        $dati = tabella_clienti("singola", $_codice, "");
        $_submit = "Aggiorna";
        $_datareg = $dati['data_reg'];
    }
}
else
{

    $_tipo = "fornitori";
    $tipo_cf = "F";
    $dati['sitocli'] = $dati['sitofor'];

    if ($_azione == "nuovo")
    {
        $_codice = cerca_verifica_numero("cerca_libera", $_tipo, $_parametri);
        $_submit = "Inserisci";
        $_data_reg = date('d-m-Y');
    }
    else
    {
        $dati = tabella_fornitori("singola", $_codice, "");
        $_submit = "Aggiorna";
        $_datareg = $dati['data_reg'];
    }
}


//inizio parte visiva..

base_html($_cosa, $_percorso);
java_script($_cosa, $_percorso);
jquery_tabs($_cosa, $_percorso);
tiny_mce($_cosa, $_percorso);

echo "</head>\n";
echo "</body>\n";
testata_html($_cosa, $_percorso);
menu_tendina($_cosa, $_percorso);

// Inizio tabella pagina principale ----------------------------------------------------------
echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"0\">\n";
echo "<tr>";

echo "<td width=\"100%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

if ($_SESSION['user']['anagrafiche'] > "2")
{


    echo "<span class=\"testo_blu\"><br><b>Modifica Anagrafica $_tipo</b></span><br><br>";

    echo "<form action=\"risinse_utente.php?tut=$_tut\" method=\"POST\">";

    echo "<div id=\"tabs\">\n";
    echo "<ul>\n";
    echo "<li><a href=\"#tabs-1\">Generale</a></li>\n";
    echo "<li><a href=\"#tabs-2\">Amministrativa</a></li>\n";
    echo "<li><a href=\"#tabs-3\">Cond. Vendite</a></li>\n";

    if ($CONTABILITA == "SI")
    {
        echo "<li><a href=\"#tabs-4\">Contabilità</a></li>\n";
    }
    echo "<li><a href=\"#tabs-5\">Mail</a></li>\n";
    echo "<li><a href=\"#tabs-6\">Dettagli</a></li>\n";
    echo "</ul>\n";


#sezione Generale..
    echo "<div id=\"tabs-1\">\n";

    echo "<table class=\"classic_bordo\">";

// CAMPO Codice ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><b>Codice:&nbsp;</b></td>\n";
    printf("<td align=\"left\"><input type=\"radio\" name=\"codcli\" value=\"%s\" checked>%s</td><tr>\n", $_codice, $_codice);

// CAMPO DATA INS ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  ><b>Registrati Dal :&nbsp;</b></td>\n";
    printf("<td>%s</td><tr>\n", $dati['data_reg']);


// CAMPO ragione sociale 1 ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><b>Ragione Sociale:&nbsp;</b></td>";
    printf("<td align=\"left\"    ><input type=\"text\" autofocus name=\"ragsoc\" required value=\"%s\" size=\"75\" maxlength=\"100\"></td></tr>\n", $dati['ragsoc']);

// CAMPO Ragione sociale 2 ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  ><b>Ragione Sociale 2:&nbsp;</b></td>";
    printf("<td align=\"left\"    ><input type=\"text\" name=\"ragsoc2\" value=\"%s\" size=\"75\" maxlength=\"100\"></td></tr>\n", $dati['ragsoc2']);


// CAMPO Indirizzo ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Indirizzo:&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"indirizzo\" value=\"%s\" size=\"60\" maxlength=\"60\"></td>\n", $dati['indirizzo']);

// CAMPO Cap ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Cap :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"cap\" value=\"%s\" size=\"6\" maxlength=\"5\"></td></tr>", $dati['cap']);

// CAMPO citt�---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Citt&agrave;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"citta\" value=\"%s\" size=\"60\" maxlength=\"60\"></td></tr>", $dati['citta']);


// CAMPO Provincia ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Prov. :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"prov\" value=\"%s\" size=\"3\" maxlength=\"2\"></td></tr>", $dati['prov']);

// CAMPO Nazione -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Nazione :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"codnazione\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['codnazione']);

// CAMPO codfiscale -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Codice Fiscale :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"codfisc\" value=\"%s\" size=\"20\" maxlength=\"16\"></td></tr>", $dati['codfisc']);

// CAMPO Partita iva -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Partita Iva :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"piva\" value=\"%s\" size=\"20\" maxlength=\"14\"> Inserire ZZ per bypassare il controllo</td></tr>", $dati['piva']);

// CAMPO Contatto -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Contatto riportato sui documenti :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"contatto\" value=\"%s\" size=\"60\" maxlength=\"60\"></td></tr>", $dati['contatto']);


// CAMPO Telefono -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Telefono :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"telefono\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['telefono']);

// CAMPO Telefono 2 -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Telefono 2 :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"telefono2\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['telefono2']);

// CAMPO Cell -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Cellulare :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"cell\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['cell']);

// CAMPO Fax -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Fax :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"fax\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['fax']);
    echo "</table>\n";
    echo "</div>\n";




//--------------------------------------------------------------SECONDA TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-2\">\n";
    echo "<table class=\"classic_bordo\">";


// inizio destinazione diversa dalla ragione sociale
// CAMPO ragione sociale  ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  ><b>Ragione Sociale Amministrativa:&nbsp;</b></td>";
    printf("<td align=\"left\"    ><input type=\"text\" name=\"dragsoc\" value=\"%s\" size=\"75\" maxlength=\"100\"></td></tr>\n", $dati['dragsoc']);

// CAMPO Ragione sociale 2 ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  ><b>Ragione Sociale 2:&nbsp;</b></td>";
    printf("<td align=\"left\"    ><input type=\"text\" name=\"dragsoc2\" value=\"%s\" size=\"75\" maxlength=\"100\"></td></tr>\n", $dati['dragsoc2']);

// CAMPO Indirizzo ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Indirizzo :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"dindirizzo\" value=\"%s\" size=\"60\" maxlength=\"60\"></td>\n", $dati['dindirizzo']);

// CAMPO Cap ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Cap :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"dcap\" value=\"%s\" size=\"6\" maxlength=\"6\"></td></tr>", $dati['dcap']);

// CAMPO citt�---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Citt&agrave; :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"dcitta\" value=\"%s\" size=\"60\" maxlength=\"60\"></td></tr>", $dati['dcitta']);

// CAMPO Provincia ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Prov. :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"dprov\" value=\"%s\" size=\"3\" maxlength=\"2\"></td></tr>", $dati['dprov']);

// CAMPO Nazione -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Nazione :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"dcodnazione\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['dcodnazione']);

// CAMPO Telefono -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Telefono :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"telefonodest\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['telefonodest']);

// CAMPO Fax -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Fax :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"faxdest\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['faxdest']);


    echo "</table>\n";
    echo "</div>\n";

//--------------------------------------------------------------TERZA TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-3\">\n";
    echo "<table class=\"classic_bordo\">";
// CAMPO IVA -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >* I.V.A. Associata:&nbsp;</td>\n";
    echo "<td align=\"left\"  >";

    tabella_aliquota("elenca_select_2", $dati['iva'], "iva");

    echo "Solo se diversa dal sistema\n";
    echo "</td></tr>";



// CAMPO Codice Pagamento -------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Pagamento :&nbsp;</td>\n";
    echo "<td align=\"left\" >";

    tabella_pagamenti("elenca_select_2", $dati['codpag'], "codpag");

    echo "</td></tr>";

// CAMPO Banca -----------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Banca :&nbsp;</td>\n";
    printf("<td align=\"left\" ><input type=\"text\" name=\"banca\" value=\"%s\" size=\"50\" maxlength=\"50\"></td></tr>\n", $dati['banca']);

// CAMPO ABI ------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Abi :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"abi\" value=\"%s\" size=\"6\" maxlength=\"5\"></td><tr>", $dati['abi']);

// CAMPO CAB ------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Cab :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"cab\" value=\"%s\" size=\"6\" maxlength=\"5\"></td><tr>", $dati['cab']);

// CAMPO Cin ------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Cin :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"cin\" value=\"%s\" size=\"2\" maxlength=\"1\"></td><tr>", $dati['cin']);
// CAMPO cc ------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >C/C :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"cc\" value=\"%s\" size=\"13\" maxlength=\"12\"></td><tr>", $dati['cc']);

// CAMPO iban ------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Iban :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"iban\" value=\"%s\" size=\"5\" maxlength=\"4\"></td><tr>", $dati['iban']);
// CAMPO cc ------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Swift (BIC) :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"swift\" value=\"%s\" size=\"12\" maxlength=\"11\"></td><tr>", $dati['swift']);


    echo "<tr><td align=\"left\"  ></td>";
    echo "<td align=\"left\" >Oppure Seleziona una delle nostre banche <br>";

    $_banche = tabella_banche("elenca_radio", $_codice, $_abi, $_cab, $_parametri);
    echo "";
    foreach ($_banche AS $datiba)
    {
        printf("<input type=\"radio\" name=\"istituto\" value=\"%s\"> %s<br>\n", $datiba['codice'], $datiba['banca']);
    }
    echo "</td><tr>";



// CAMPO Listino associato ------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Listino Associato :&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"number\" name=\"listino\" value=\"%s\" size=\"4\" maxlength=\"3\"></td><tr>", $dati['listino']);

// CAMPO sconto ------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Sconto Cliente listino:&nbsp;</td>";
    printf("<td align=\"left\" ><input type=\"text\" name=\"scontocli\" value=\"%s\" size=\"6\" maxlength=\"6\"> + <input type=\"text\" name=\"scontocli2\" value=\"%s\" size=\"6\" maxlength=\"6\"> + <input type=\"text\" name=\"scontocli3\" value=\"%s\" size=\"6\" maxlength=\"6\"></td><tr>", $dati['scontocli'], $dati['scontocli2'], $dati['scontocli3']);


// CAMPO Codice Agente -------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Agente :&nbsp;</td>\n";
    echo "<td align=\"left\"  >";

    tabella_agenti("elenca_select_2", $dati['codagente'], "codagente");

    echo "</td></tr>";


    echo "<tr><td align=\"left\" >Zona Appartenenza:&nbsp;</td>\n";
    echo "<td align=\"left\" >";

    tabella_zone("elenca_select_2", $dati['zona'], "zona");

    echo "</td></tr>";

    echo "</table>\n";
    echo "</div>\n";

    echo "<div id=\"tabs-4\">\n";
    echo "<table class=\"classic_bordo\">";

    //--------------------------------------------------------------QUARTA TABS-------------------------------------------------------------------------

    if ($CONTABILITA == "SI")
    {
        // CAMPO IVA -----------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\" >Conto associato Contabilit&agrave;</td>\n";
        echo "<td align=\"left\" >";

        if ($_azione == "nuovo")
        {
            if ($_tipo == "clienti")
            {
                // Stringa contenente la query di ricerca... solo dei fornitori
                $query = sprintf("select codconto, descrizione from piano_conti where codconto=\"%s\"", $CONTO_CLIENTI);
            }
            else
            {
                // Stringa contenente la query di ricerca... solo dei fornitori
                $query = sprintf("select codconto, descrizione from piano_conti where codconto=\"%s\"", $CONTO_FORNITORI);
            }
        }
        else
        {
            // Stringa contenente la query di ricerca... solo dei fornitori
            $query = sprintf("select codconto, descrizione from piano_conti where codconto=\"%s\"", $dati['cod_conto']);
        }
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        // Tutto procede a meraviglia...
        echo "";
        foreach ($result AS $datip)
            ;

        echo "<select name=\"cod_conto\">\n";
        printf("<option value=\"%s\">%s - %s</option>\n", $datip['codconto'], $datip['codconto'], $datip['descrizione']);

        echo "<option value=\"\"></option>\n";
        $datip = "";
        // Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("select codconto, descrizione from piano_conti where livello = '2' order by codconto");

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result AS $datip)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $datip['codconto'], $datip['codconto'], $datip['descrizione']);
        }

        echo "</select>\n";
        echo "</td></tr>";

        // CAMPO Lettera intento ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  >N. lettera intento:&nbsp;</td>";
        printf("<td align=\"left\"  ><input type=\"text\" name=\"nintento\"  value=\"%s\" size=\"16\" maxlength=\"15\"></td></tr>\n", $dati['nintento']);

// CAMPO Lettera intento ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  >N. nostro protocollo:&nbsp;</td>";
        printf("<td align=\"left\"  ><input type=\"text\" name=\"nproto\" value=\"%s\" size=\"16\" maxlength=\"15\"></td></tr>\n", $dati['nproto']);



        // CAMPO Blocco Cliente -------------------------------------------------------------------------------
        echo "<tr><td align=\"left\" >Esenzione Spesometro :&nbsp;</td>\n";
        echo "<td align=\"left\"  >";
        echo "<select name=\"spesometro\">\n";
        printf("<option value=\"%s\">%s</option>\n", $dati['spesometro'], $dati['spesometro']);
        echo "<option value=\"NO\">NO</option>";
        echo "<option value=\"SI\">SI</option>";
        echo "</select>\n";
        echo "Se <b>SI</b> Esonera il soggetto dal conto dello spesometro";
        echo "</td></tr>";
    }




    echo "</table>\n";
    echo "</div>\n";

//--------------------------------------------------------------QUINTA TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-5\">\n";
    echo "<table class=\"classic_bordo\">";


// CAMPO email  ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >E-mail generale:&nbsp;</td>";
    printf("<td align=\"left\"  ><input type=\"text\" name=\"email\" value=\"%s\" size=\"75\" maxlength=\"80\"></td></tr>\n", $dati['email']);

// CAMPO email  ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >E-mail 2 invio acquisti:&nbsp;</td>";
    printf("<td align=\"left\"  ><input type=\"text\" name=\"email2\" value=\"%s\" size=\"75\" maxlength=\"80\"></td></tr>\n", $dati['email2']);


// CAMPO email  ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >E-mail 3 contabilita:&nbsp;</td>";
    printf("<td align=\"left\"  ><input type=\"text\" name=\"email3\" value=\"%s\" size=\"75\" maxlength=\"80\"></td></tr>\n", $dati['email3']);


// CAMPO sito internet ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Sito internet:&nbsp;</td>";
    printf("<td align=\"left\"  ><input type=\"text\" name=\"sitocli\" value=\"%s%s\" size=\"75\" maxlength=\"80\"></td></tr>\n", $dati['sitocli'], $dati['sitofor']);



    echo "</table>\n";
    echo "</div>\n";

//--------------------------------------------------------------SESTA TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-6\">\n";
    echo "<table class=\"classic_bordo\">";





// CAMPO Blocco Cliente -------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Blocco Cliente :&nbsp;</td>\n";
    echo "<td align=\"left\"  >";
    echo "<select name=\"bloccocli\">\n";
    printf("<option value=\"%s\">%s</option>\n", $dati['bloccocli'], $dati['bloccocli']);
    echo "<option value=\"NO\">NO</option>";
    echo "<option value=\"SI\">SI</option>";
    echo "</select>\n";
    echo "Se <b>SI</b> blocca tutto il reparto vendita al cliente";
    echo "</td></tr>";
    
    // CAMPO Blocco Cliente -------------------------------------------------------------------------------
    echo "<tr><td align=\"left\">Escludi utente selezione :&nbsp;</td>\n";
    echo "<td align=\"left\"  >";
    echo "<select name=\"es_selezione\">\n";
    printf("<option value=\"%s\">%s</option>\n", $dati['es_selezione'], $dati['es_selezione']);
    echo "<option value=\"NO\">NO</option>";
    echo "<option value=\"SI\">SI</option>";
    echo "</select>\n";
    echo "Se <b>SI</b> blocca tutto il reparto vendita al cliente";
    echo "</td></tr>";
    
    

// CAMPO Legge privacy --------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Legge privacy :&nbsp;</td>\n";
    echo "<td align=\"left\"  >";
    echo "<select name=\"privacy\">\n";
    printf("<option value=\"%s\">%s</option>\n", $dati['privacy'], $dati['privacy']);
    echo "<option value=\"NO\">NO</option>";
    echo "<option value=\"SI\">SI</option>";
    echo "</select>\n";
    echo "Se <b>NO</b> Se No durante le operazioni di ventita si viene avvisati ";
    echo "</td></tr>";



// CAMPO nome utente  ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >utente Internet ?&nbsp;</td>";
    $_si = "NO";
    if ($dati['username'] != "")
    {
        $_si = "SI";
    }
    printf("<td align=\"left\"    >%s</td></tr>\n", $_si);

    echo "<tr><td align=\"left\">Indice Pubblica Amministrazione:</td>\n";
    echo "<td align=\"left\"><input type=\"text\" name=\"indice_pa\" value=\"$dati[indice_pa]\" size=\"7\" maxlength=\"6\"></td></tr>\n";
    echo "<tr><td align=\"left\">Nostro Codice utente:</td>\n";
    echo "<td align=\"left\"><input type=\"text\" name=\"cod_ute_dest\" value=\"$dati[cod_ute_dest]\" size=\"16\" maxlength=\"15\"></td></tr>\n";
    
    
    
    // CAMPO vettore -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Corriere Abituale :&nbsp;</td>";
    echo "<td align=\"left\" >\n";
    echo "<select name=\"vettore\">\n";
    printf("<option value=\"%s\">%s</option>\n", $dati['vettore'], $dati['vettore']);
    echo "<option value=\"\"></option>\n";

    tabella_vettori("elenca_select", $_percorso, $_codice, $_parametri);

    echo "</select><br>\n";
    //echo "</td></tr>";
//-------PORTO-------------------------------------------------------
    //echo "<tr><td align=\"left\"  >";
    echo "      Servizio Porto :&nbsp;";
    echo "<select name=\"porto\">";
    printf("<option value=\"%s\">%s</option>\n", $dati['porto'], $dati['porto']);
    printf("<option value=\"FRANCO\">FRANCO</option>");
    printf("<option value=\"ASSEGNATO\">ASSEGNATO</option>");
    echo "</select>\n";
    echo "</td></tr>";
    
    
    
// CAMPO note ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\">Note :&nbsp;</td>";
    printf("<td align=\"left\"><textarea id=\"elm1\" name=\"note\" cols=\"40\" rows=\"40\" WRAP=\"physical\">%s</textarea></td></tr>\n", $dati['note']);


    echo "</table>\n";
    echo "</div>\n";
    echo "</div>\n";


    echo "</table><center><br><b>Azioni possibili</b></center>\n";
    if ($_SESSION['user']['anagrafiche'] == "4")
    {
        printf("<center><input type=\"submit\" name=\"azione\" value=\"$_submit\"> - <input type=\"submit\" name=\"azione\" value=\"Elimina\"></center>");
    }
    elseif ($_SESSION['user']['anagrafiche'] == "3")
    {
        printf("<center><input type=\"submit\" name=\"azione\" value=\"$_submit\"></center>");
    }
    elseif ($_SESSION['user']['anagrafiche'] == "2")
    {
        printf("<center>Non hai i permessi per poter modificare questo cliente/fornitore</center>");
    }
    else
    {
        printf("<center>Non hai i permessi per poter vedere questo cliente/fornitore</center>");
    }
}
else
{
    echo "<h2>Non hai i permessi per poter visualizzare cliente/fornitore</h2>\n";
}


echo "</form>\n";
//// Fine tabella pagina princ
// Fine tabella pagina principale -----------------------------------------------------------
?>
