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

$_tut = $_GET['tut'];
$_codice = $_GET['codice'];

if ($_tut == "c")
{

    $_tipo = "clienti";
    $dati = tabella_clienti("singola", $_codice, "");
    $tipo_cf = "C";
    $_numero = "singolo";
    $_tdoc = "conferma";
}
else
{
    $_tipo = "fornitori";
    $dati = tabella_fornitori("singola", $_codice, "");
    $tipo_cf = "F";
    $_numero = "sing_fornitore";
    $_tdoc = "fornitore";
}

//prendiamoci i dati

base_html($_cosa, $_percorso);

//carichiamo java..
java_script($_cosa, $_percorso);

//carichiamo i tabs

jquery_tabs($_cosa, $_percorso);

echo "</head>\n";

echo "<body>\n";

testata_html($_cosa, $_percorso);

menu_tendina($_cosa, $_percorso);
// Inizio tabella pagina principale ----------------------------------------------------------

echo "<table width=\"100%\" border=0 cellspacing=\"0\" align=\"left\" cellpadding=\"0\">\n";
echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

if ($_SESSION['user']['anagrafiche'] > "1")
{

    echo "<form action=\"modifica_utente.php?tut=$_tut&azione=Modifica\" id=\"modifica\" method=\"POST\">";



    echo "<span class=\"testo_blu\"><br><b>Visualizzazione Breve $_tipo</b></span><br><br>";
//inserimento tabs

    echo "<div id=\"tabs\">\n";
    echo "<ul>\n";
    echo "<li><a href=\"#tabs-1\">Generale</a></li>\n";
    echo "<li><a href=\"#tabs-2\">Sede Amministrativa</a></li>\n";
    echo "<li><a href=\"#tabs-3\">Condizioni Vendita</a></li>\n";
    echo "<li><a href=\"#tabs-4\">Contatti</a></li>\n";
    echo "<li><a href=\"#tabs-5\">Dettagli</a></li>\n";
    echo "<li><a href=\"#tabs-6\">Ult. Muovimenti</a></li>\n";
    echo "</ul>\n";


#sezione Generale..
    echo "<div id=\"tabs-1\">\n";

    echo "<table class=\"classic_bordo\">";

// CAMPO Articolo ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" ><b>Codice:&nbsp;</b></td>\n";
    printf("<td align=\"left\"  ><b>%s</b></td><tr>\n", $dati['codice']);

    echo "<tr><td align=\"left\"><b>Registrato Dal :&nbsp;</b></td>\n";
    printf("<td align=\"left\">%s ultima modifica il %s</td><tr>\n", $dati['data_reg'], $dati['ts']);

// CAMPO Descrizione ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" ><b>Ragione soc. :&nbsp;</b></td>";
    printf("<td align=\"left\"  ><b>%s</b></td></tr>\n", $dati['ragsoc']);

// CAMPO Unita' di misura ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Ragsoc 2:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['ragsoc2']);

// CAMPO iva -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Indirizzo:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['indirizzo']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Città &nbsp;</td>";
    printf("<td align=\"left\" >%s + %s</td></tr>", $dati['citta'], $dati['prov']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >P.I. &nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['piva']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Cod. Fisc &nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['codfisc']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Telefono.:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['telefono']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Telefono 2.:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['telefono2']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Cell .:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['cell']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Fax.:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['fax']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Contatto .:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['contatto']);

    echo "</table>\n";
    echo "</div>\n";


    echo "<div id=\"tabs-2\">\n";

    echo "<table class=\"classic_bordo\">";

// DESTINAZIONE--------------------------------------------------------------
    echo "<tr><td align=\"left\" colspan=\"2\" >Destinazione diversa</td></tr>";

// CAMPO Descrizione ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" ><b>Ragione soc. :&nbsp;</b></td>";
    printf("<td align=\"left\"  ><b>%s</b></td></tr>\n", $dati['dragsoc']);

// CAMPO Unita' di misura ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Ragsoc 2:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['dragsoc2']);

// CAMPO iva -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Indirizzo:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['dindirizzo']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Città &nbsp;</td>";
    printf("<td align=\"left\" >%s + %s</td></tr>", $dati['dcitta'], $dati['dprov']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Telefono.:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['telefonodest']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"  >Fax.:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['faxdest']);

    echo "<tr><td align=\"left\" >Corriere .:&nbsp;</td>";
    echo "<td align=\"left\" >$dati[vettore]</td></tr>\n";

    echo "<tr><td align=\"left\" >Porto .:&nbsp;</td>";
    echo "<td align=\"left\" >$dati[porto]</td></tr>\n";

    echo "</table>\n";
    echo "</div>\n";


    echo "<div id=\"tabs-3\">\n";

    echo "<table class=\"classic_bordo\">";
// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Pagamento .:&nbsp;</td>";
    $_pagamento = tabella_pagamenti("singola", $dati['codpag'], '');

    echo "<td align=\"left\" >$_pagamento[descrizione]</td></tr>\n";

    echo "<tr><td align=\"left\" >Banca Appoggio:&nbsp;</td>\n";
    echo "<td align=\"left\">$dati[banca]</td></tr>\n";
    echo "<tr><td>&nbsp;</td><td align=\"left\"><b>Abi</b> $dati[abi] <b>Cab</b> $dati[cab] <b>C/C</b> $dati[cc]</td></tr>\n";
    echo "<tr><td>&nbsp;</td><td align=\"left\"><b>Swift - Bic </b>$dati[swift] <b>Iban </b>$dati[iban] <b>Cin</b> $dati[cin]</td></tr>\n";

    echo "<tr><td align=\"left\">IBAN COMPLETO</td><td align=\"left\">$dati[iban]$dati[cin]$dati[abi]$dati[cab]$dati[cc]</td></tr>\n";

    echo "<tr><td align=\"left\"><font size=\"4\">Sconto:</td><td align=\"left\"><font size=\"4\">$dati[scontocli] + $dati[scontocli2] + $dati[scontocli3]</td></tr>\n";

    echo "<tr><td align=\"left\" >Iva Riferimento .:&nbsp;</td>";
    $_tabiva = tabella_aliquota("singola", $dati['iva'], "silent");

    echo "<td align=\"left\" >$_tabiva[descrizione]</td></tr>\n";




    echo "</table>\n";
    echo "</div>\n";



    echo "<div id=\"tabs-4\">\n";

    echo "<table class=\"classic_bordo\">";
// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Sito internet .:&nbsp;</td>";
    printf("<td align=\"left\" ><a href=\"http://%s%s\" noframe>%s%s</td></tr>", $dati['sitocli'], $dati['sitofor'], $dati['sitocli'], $dati['sitofor']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Invia una email .:&nbsp;</td>";
    printf("<td align=\"left\" ><a href=\"mailto:%s\">%s</td></tr>", $dati['email'], $dati['email']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >E-mail acquisti .:&nbsp;</td>";
    printf("<td align=\"left\" ><a href=\"mailto:%s\">%s</td></tr>", $dati['email2'], $dati['email2']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >email Conabilità .:&nbsp;</td>";
    printf("<td align=\"left\" ><a href=\"mailto:%s\">%s</td></tr>", $dati['email3'], $dati['email3']);


    echo "</table>\n";
    echo "</div>\n";


    echo "<div id=\"tabs-5\">\n";

    echo "<table class=\"classic_bordo\">";


// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Legge Privacy .:&nbsp;</td>";
    printf("<td align=\"left\" >%s</td></tr>", $dati['privacy']);

// CAMPO nome utente  ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >utente Internet ?&nbsp;</td>";
    $_si = "NO";
    if ($dati['username'] != "")
    {
        $_si = "SI";
    }
    printf("<td align=\"left\"  >%s</td></tr>\n", $_si);


    echo "<tr><td align=\"left\">Dichiarazione intento:</td><td align=\"left\">$dati[nintento]</td></tr>\n";
    echo "<tr><td align=\"left\">Indice Pubblica Amministrazione:</td><td align=\"left\">$dati[indice_pa]</td></tr>\n";
    echo "<tr><td align=\"left\">Nostro Codice utente:</td><td align=\"left\">$dati[cod_ute_dest]</td></tr>\n";


// CAMPO note articolo -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\" >Note :&nbsp;</td>\n";
    echo "<td align=\"left\"  >";
    printf("%s</td></tr>", $dati['note']);

    echo "</table>\n";
    echo "</div>\n";
#fine generale
#inizio sezione acquisti..
    echo "<div id=\"tabs-6\">\n";

    echo "<table class=\"classic_bordo\">";

    if ($_tut == "c")
    {

// inizio controllo muovimenti

        $_anno = date('Y');
        echo "<table border=\"0\" width=\"90%\">";

        echo "<tr><td align=\"left\" colspan=5 ><h3><a href=\"tophit_articolo_utente.php?tut=$_tut&utente=$_codice\" target=\"_blank\">Top hit articoli &nbsp;</a></h3></td></tr>";

// inizio muovimenti uscita
        echo "<tr><td align=\"left\" colspan=5 >Ultimi Muovimenti Vendita Bolle &nbsp;</td></tr>";

        echo "<tr><td align=\"left\" >Data Reg.</td><td align=\"left\"> Numero Doc.</td><td align=\"left\">Valore</td></tr> ";

// Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("SELECT * FROM bv_bolle where anno=\"%s\" and utente=\"%s\" ORDER BY ndoc DESC LIMIT 10", $_anno, $_codice);


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
        echo "";
        foreach ($result AS $dati2)
        {
            printf("<tr><td align=\"left\" >%s</td><td align=\"left\"><a href=\"../../vendite/docubase/visualizzadoc.php?tdoc=ddt&anno=%s&suffix=%s&ndoc=%s\">%s</td><td align=\"left\">%s</td></tr>", $dati2['datareg'], $_anno, $dati2['suffix'], $dati2['ndoc'], $dati2['ndoc'], $dati2['totdoc']);
        }

        echo "</tr></table>"; // chiusura tabelle interna
        echo "<br><br>";

        echo "<table border=\"0\" width=\"90%\">";
// inizio muovimenti uscita
        echo "<tr><td align=\"left\" colspan=5 >Ultimi Muovimenti Vendita Fatture &nbsp;</td></tr>";

        echo "<tr><td align=\"left\" >Tipo doc. </td><td align=\"left\">Data Reg.</td><td align=\"left\"> Numero Doc.</td><td align=\"left\">Valore</td></tr> ";

// Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("SELECT * FROM fv_testacalce where anno=\"%s\" and utente=\"%s\" ORDER BY ndoc DESC LIMIT 10", $_anno, $_codice);

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
        echo "";
        foreach ($result AS $dati2)
        {
            printf("<tr><td align=\"left\" >%s</td><td align=\"left\">%s</td><td align=\"left\"><a href=\"../../vendite/docubase/visualizzadoc.php?tdoc=FATTURA&anno=%s&suffix=%s&ndoc=%s\">%s</td><td align=\"left\">%s</td></tr>", $dati2['tdoc'], $dati2['datareg'], $_anno, $dati2['suffix'], $dati2['ndoc'], $dati2['ndoc'], $dati2['totdoc']);
        }

        echo "</tr></table>"; // chiusura tabelle interna

        echo "<br><br>";
// inizio controllo muovimenti

        $_annov = $_anno - 1;
        echo "<table border=\"0\" width=\"90%\">";

// inizio muovimenti uscita
        echo "<tr><td align=\"left\" colspan=5 >Ultimi Muovimenti Vendita Bolle anno precedente &nbsp;</td></tr>";

        echo "<tr><td align=\"left\" >Data Reg.</td><td align=\"left\"> Numero Doc.</td><td align=\"left\">Valore</td></tr> ";

// Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("SELECT * FROM bv_bolle where anno=\"%s\" and utente=\"%s\" ORDER BY ndoc DESC LIMIT 10", $_annov, $_codice);

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
        echo "";
        foreach ($result AS $dati2)
        {
            printf("<tr><td align=\"left\" >%s</td><td align=\"left\"><a href=\"../../vendite/docubase/visualizzadoc.php?tdoc=ddt&anno=%s&suffix=%s&ndoc=%s\">%s</td><td align=\"left\">%s</td></tr>", $dati2['datareg'], $_annov, $dati2['suffix'], $dati2['ndoc'], $dati2['ndoc'], $dati2['totdoc']);
        }

        echo "</tr></table>"; // chiusura tabelle interna
        echo "<br><br>";

        echo "<table border=\"0\" width=\"90%\">";
// inizio muovimenti uscita
        echo "<tr><td align=\"left\" colspan=5 >Ultimi Muovimenti Vendita Fatture &nbsp;</td></tr>";

        echo "<tr><td align=\"left\" >Tipo doc. </td><td align=\"left\">Data Reg.</td><td align=\"left\"> Numero Doc.</td><td align=\"left\">Valore</td></tr> ";

// Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("SELECT * FROM fv_testacalce where anno=\"%s\" and utente=\"%s\" ORDER BY ndoc DESC LIMIT 10", $_annov, $_codice);

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
        echo "";
        foreach ($result AS $dati2)
        {
            printf("<tr><td align=\"left\" >%s</td><td align=\"left\">%s</td><td align=\"left\"><a href=\"../../vendite/docubase/visualizzadoc.php?tdoc=FATTURA&anno=%s&suffix=%s&ndoc=%s\">%s</td><td align=\"left\">%s</td></tr>", $dati2['tdoc'], $dati2['datareg'], $_annov, $dati2['suffix'], $dati2['ndoc'], $dati2['ndoc'], $dati2['totdoc']);
        }
    }
    else
    {
        echo "<table class=\"classic_bordo\" border=\"0\">";
        //per vedere gli ultimmi muovimenti del fornitore dovrei richiamarlo dal magazzino..
        $_anno = date('Y');
        echo "<tr><td colspan=6 align=\"left\"><span class=\"testo_blu\"><br>Ultimi Muovimenti Acquisto &nbsp;</span></td></tr>";

        echo "<tr><td align=\"left\">Data Reg.</td><td> Numero Doc.</td><td>Fornitore</td><td>Q.ta carico</td><td>Valore</td><td>Netto acq.</td></tr> ";

// Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("SELECT *,SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq FROM magazzino INNER JOIN fornitori ON magazzino.utente=fornitori.codice WHERE tut='f' AND utente=\"%s\" AND anno <=\"%s\" and tdoc='ddtacq' GROUP BY ndoc order by datareg DESC LIMIT 10", $_codice, $_anno);

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

        echo "<span class=\"testo_blu\">";
        foreach ($result AS $dati)
        {
            @$_nettoacq = $dati['valoreacq'] / $dati['qtacarico'];

            $_annodoc = substr($dati['anno'], 0, 4);

            printf("<tr><td align=\"left\">%s</td><td><a href=\"../../vendite/docubase/visualizzadoc.php?tdoc=ddtacq&anno=$_annodoc&suffix=$dati[suffix]&ndoc=%s\">%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $dati['datareg'], $dati['ndoc'], $dati['ndoc'], $dati['ragsoc'], $dati['qtacarico'], $dati['valoreacq'], number_format(($_nettoacq), 2));
        }


        echo "</tr>";

        echo "<br>";

        $_annov = $_anno - 1;


        echo "<table class=\"classic_bordo\" border=\"0\">";
        echo "<tr><td colspan=\"6\"><hr></td></tr>\n";

        echo "<tr><td colspan=6 align=\"left\"><span class=\"testo_blu\"><br>Ultimi Muovimenti Acquisto &nbsp; anno scorso</span></td></tr>";

        echo "<tr><td align=\"left\">Data Reg.</td><td> Numero Doc.</td><td>Fornitore</td><td>Q.ta carico</td><td>Valore</td><td>Netto acq.</td></tr> ";

// Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("SELECT *,SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq FROM magastorico INNER JOIN fornitori ON magastorico.utente=fornitori.codice WHERE tut='f' AND utente=\"%s\" AND anno <=\"%s\" and tdoc='ddtacq' GROUP BY ndoc order by datareg DESC LIMIT 10", $_codice, $_annov);
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

        echo "<span class=\"testo_blu\">";
        foreach ($result AS $dati)
        {
            @$_nettoacq = $dati['valoreacq'] / $dati['qtacarico'];
            printf("<tr><td align=\"left\">%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $dati['datareg'], $dati['ndoc'], $dati['ragsoc'], $dati['qtacarico'], $dati['valoreacq'], number_format(($_nettoacq), 2));
        }

        echo "</tr>";
    }

    echo "</table>\n";

    echo "</div></div>\n";


    echo "</table><center><br><b>Azioni possibili<br></b>\n";

        if ($_SESSION['user']['anagrafiche'] >= "2")
        {
            pulsanti("cerca", "submit", "", "get", "ricerca.php", "40px", "40px", "Cerca", "", "", "Cerca", $_id);
        }

        if ($_SESSION['user']['anagrafiche'] >= "3")
        {
           // pulsanti("nuovo", "submit", "nuovo_get", "get", "modifica_utente.php?tut", "40px", "40px", "Nuovo", "azione", "nuovo", "Nuovo", $_id);

            pulsanti("modifica", "submit", "modifica", $_formmethod, $_formaction, "40px", "40px", "Modifica", "codice", $_codice, "Modifica", $_id);
            pulsanti("help", "submit", "", "get", "http://aguagest.sourceforge.net/?q=node/55", "40px", "40px", "Aiuto", "", "", "Aiuto", $_id);
        }

    if ($_SESSION['user']['anagrafiche'] > "3")
    {
        //echo "<br> Visualizza tutti i dettagli e modifica $_tipo==> &nbsp;<b><input type=\"submit\" name=\"codice\" value=\"$_codice\">\n";
        echo "</form>";

        echo "<br><BR>";
        echo "<a href=\"../../vendite/docubase/stampa_inevaso.php?tdoc=$_tdoc&tut=$_numero&anno=2011&utente=$_codice\" target=\"_blank\">Stampa Inevaso di questo cliente</a>\n";

        echo "<br><BR>";
        echo "<a href=\"../../vendite/docubase/nuovodoc.php?tipo=$_tipo&codice=$_codice\">Inizia Un nuovo documento con questo cliente.. </a><br>\n";

        if ($CONTABILITA == "SI")
        {
            $_anno = date('Y');
            echo "<br><BR>Visualizza la situazione cliente contabile == ><a href=\"../../contabilita/result_scheda.php?tipo_cf=$tipo_cf&codconto=$_codice&start=$_anno\">Clicca QUI !</a>\n";
            echo "<br>\n";
        }
    }
    elseif ($_SESSION['user']['anagrafiche'] > "2")
    {
        //echo "<br> Visualizza tutti i dettagli e modifica $_tipo==> &nbsp;<b><input type=\"submit\" name=\"codice\" value=\"$_codice\">\n";
        echo "</form>";

        echo "<br><BR>";
        echo "<a href=\"../../vendite/docubase/stampa_inevaso.php?tdoc=$_tdoc&tut=$_numero&anno=2011&utente=$_codice\" target=\"_blank\">Stampa Inevaso di questo cliente/fornitore</a>\n";

        echo "<br><BR>";
        echo "<a href=\"../../vendite/docubase/nuovodoc.php?tipo=$_tipo&codice=$_codice\">Inizia Un nuovo documento con questo cliente.. </a><br>\n";

        if ($CONTABILITA == "SI")
        {
            $_anno = date('Y');
            echo "<br><BR>Visualizza la situazione cliente/fornitore contabile == ><a href=\"../../contabilita/result_scheda.php?tipo_cf=$tipo_cf&codconto=$_codice&start=$_anno\">Clicca QUI !</a>\n";
            echo "<br>\n";
        }
    }
    elseif ($_SESSION['user']['anagrafiche'] < "3")
    {
        printf("<center>Non hai i permessi per poter modificare questo cliente/fornitore</center>");

        echo "<br><BR>";
        echo "<a href=\"../../vendite/docubase/stampa_inevaso.php?tdoc=$_tdoc&tut=$_numero&anno=2011&utente=$_codice\" target=\"_blank\">Stampa Inevaso di questo cliente / fornitore</a>\n";

        echo "<br><BR>";
        echo "<a href=\"../../vendite/docubase/nuovodoc.php?tipo=$_tipo&codice=$_codice\">Inizia Un nuovo documento con questo cliente / Fornitore.. </a><br>\n";

        if ($CONTABILITA == "SI")
        {
            $_anno = date('Y');
            echo "<br><BR>Visualizza la situazione cliente/fornitore contabile == ><a href=\"../../contabilita/result_scheda.php?tipo_cf=$tipo_cf&codconto=$_codice&start=$_anno\">Clicca QUI !</a>\n";
            echo "<br>\n";
        }
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



echo "</tr></table>"; // chiusura tabelle interna



echo "</td></tr></table>"; //chiusura seconda tabella
// Fine tabella pagina principale -----------------------------------------------------------
?>
