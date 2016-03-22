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
base_html("", $_percorso);

java_script($_cosa, $_percorso);
jquery_datapicker($_cosa, $_percorso);
java_script("no_invio", $_percorso);

//jquery_menu_cascata("base", "calce.php");

echo "</head>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{

    //verifico se è solo una modifica della spedizione

    if ($_GET['azione'] == "spedizione")
    {
        $_SESSION['calce'] = "calce2";
        $_SESSION['tdoc'] = $_GET['tdoc'];
        $_SESSION['ndoc'] = $_GET['ndoc'];
        $_SESSION['anno'] = $_GET['anno'];
        $_SESSION['suffix'] = $_GET['suffix'];
    }

    //recupero le varibili
    $_codutente = $_SESSION['utente'];
    $_tdoc = $_SESSION['tdoc'];
    $_totiva = $_SESSION['totiva'];
    $_imponibile = $_SESSION['importi'];
    $_pesotot = $_SESSION['pesotot'];
    $_calce = $_SESSION['calce'];
    $_suffix = $_SESSION['suffix'];
    //seleziono il cliente
    $dati = $_SESSION['datiutente'];


//seleziono il documento in caso di calce2

    $_archivio = archivio_tdoc($_tdoc);

    $_SESSION['archivi'] = $_archivio;

    //restituisce
    //restitusco un arrey con il nome archivioo ed il nome
    //$_archivio['testacalce'] = $_testacalce;
    //$_archivio['dettaglio'] = $_dettaglio;
    // Stringa contenente la query di ricerca...
    // cerco nel database testacalce



    if ($_calce == "calce2")
    {
        //prendo il resto delle varibili
        $_anno = $_SESSION['anno'];
        $_suffix = $_SESSION['suffix'];
        $_ndoc = $_SESSION['ndoc'];
        $_tdoc = $_SESSION['tdoc'];


        //prendiamoci il documento..

        $dati_doc = seleziona_documento("leggi_riga_testata", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);


        $_annodoc = $dati_doc['anno'];
        $_datareg = $dati_doc['datareg'];
        $_codpag = $dati_doc['modpag'];
        $_imballo = $dati_doc['aspetto'];
        $_colli = $dati_doc['colli'];
        $_vettore = $dati_doc['vettore'];
        $_porto = $dati_doc['porto'];
        $_scoinco = $dati_doc['scoinco'];
        $_trasporto = $dati_doc['trasporto'];
        $_varie = $dati_doc['spesevarie'];
        $_banca = $dati['banca'];
        $_agente = $dati_doc['agente'];
        $_spimba = $dati_doc['imballo'];
        $_sp_bancarie = $dati_doc['sp_bancarie'];

        if ($_pesotot = "0.00")
        {
            $_pesotot = $dati_doc['pesotot'];
        }


        if ($_tdoc == "preventivo")
        {
            $_notedoc = substr($dati_doc['note'], '31');
        }
        else
        {
            $_notedoc = $dati_doc['note'];
        }


        $_causale = $dati_doc['causale'];
        $_SESSION['rev'] = $dati_doc['rev'];
        $_ddtacq = $dati_doc['ddtfornitore'];
        $_fatturacq = $dati_doc['fatturacq'];
        $_datareg = $dati_doc['datareg'];
        $_protoiva = $dati_doc['protoiva'];
        $_suffix_proto = $dati_doc['suffix_proto'];
        $_anno_proto = $dati_doc['anno_proto'];
        $_id_collo = $dati_doc['id_collo'];
        $_data_scad = cambio_data("it", $dati_doc['data_scad']);

        if ($_GET['azione'] == "spedizione")
        {
            $_scrivi = "Spedizione";
            //qui mi prendo anche i dati dell'utente..
            $_SESSION['datiutente'] = tabella_clienti("singola", $dati_doc['utente'], $_parametri);
            $dati = $_SESSION['datiutente'];
        }
        else
        {
            $_scrivi = "Aggiorna";
        }
    }
    else
    {
        $_listino = $dati['listino'];
        $_scontocli = $dati['scontocli'];
        $_ivacli = $dati['iva'];
        $_codpag = $dati['codpag'];
        $_banca = $dati['banca'];
        $_agente = $dati['codagente'];
//		$_notedoc = $dati['note'];
        $_vettore = $dati['vettore'];
        $_porto = $dati['porto'];
        $_scrivi = "Inserisci";
        $_calce = "calce";

        $_data_scad = date("d-m-Y", mktime(0, 0, 0, date(m) + 1, date(d), date(Y)));
    }

    //inizio parte visiva..

    printf("<form action=\"inserisci.php\" id=\"myform\" method=\"POST\">");

//intesto la pagina html
    intesta_html($_tdoc, $_calce, $dati, $dati_doc);

//NOTE CONTABILI... nel caso di spedizione non facciamo apparire
    if ($_GET['azione'] != "spedizione")
    {
        echo "<hr>\n";
        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
        echo "<tr><td colspan=\"3\" align=\"left\" valign=\"top\"><span class=\"intestazione\"><b>Note Contabili</b></span><br></td></tr>\n";
        echo "<tr>";

        if ($_tdoc == "ddt")
        {
            causale_trasporto($_cosa, $_causale);
        }
        
        

//selezioniamo i pagamenti
        echo "<td align=\"left\" valign=\"top\" colspan=\"2\">";
        echo "<b>Pagamento in corso: </b>&nbsp;<br>";
        echo "<select name=\"modpag\">\n";
        tabella_pagamenti("desc_singola_select", $_codpag, $_parametri);
//INSERISCO IN PAGAMENTO VUOTO
        echo "<option value=\"\"></option>\n";
        tabella_pagamenti("elenca_select", $_codpag, $_parametri);
        echo "</select>";
        echo "</td></tr>\n";

// l'unico documento ad aver diversi campi inpiù sono solo le fatture e derivati.

        echo "<tr><td align=\"left\" valign=\"top\" colspan=\"2\"><br>Banca appoggio.:&nbsp;<br> <b>";
        if (( $_tdoc == "FATTURA") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == "$nomedoc"))
        {
            //Do la possibilita di cambiare banca
            echo "<tr><td align=\"left\" valign=\"top\" width=\"50%\"><span class=\"testo_blu\"><b>Banca : </b>&nbsp;</span>";
            echo "<select name=\"banca\">\n";
            echo "<option value=\"$_banca\">$_banca</option>";
            $result = tabella_banche("elenca", $_codice, $_abi, $_cab, $_parametri);
            foreach ($result AS $datib)
            {
                printf("<option value=\"%s\">%s</option>\n", $datib['banca'], $datib['banca']);
            }
            echo "<option value=\"\"></option>\n";
            printf("<option value=\"%s\">%s</option>\n", $dati['banca'], $dati['banca']);
            echo "</select></td>\n";

#visualizziamo anche l'agene'
// ricerca nome agente -----------------------------------------------------------------------------------------
            echo "<td>\n";
            echo "<b>Agente : </b>&nbsp;</span>";
            echo "<select name=\"agente\">\n";

            tabella_agenti("singola_select", $_agente, $_parametri);

            tabella_agenti("elenca_select", $_codage, $_parametri);

            echo "</select></td></tr>\n";
            echo "<tr>\n";
            printf("<td align=\"left\" valign=\"top\">Spese Bancarie : &nbsp; <input type=\"text\" name=\"spbanca\" value=\"%s\" size=\"10\" maxlength=\"10\"></td>", $_sp_bancarie);
            printf("<td> Spese imballo: <input type=\"text\" name=\"spimba\" value=\"%s\" size=\"10\" maxlength=\"10\"></td><tr>\n", $_spimba);
            printf("<td align=\"left\" valign=\"top\" colspan=\"1\" >Spese Varie.: <input type=\"text\" name=\"varie\" value=\"%s\" size=\"10\" maxlength=\"10\"></td>", $_varie);
            printf("<td align=\"left\" valign=\"top\">   Sconto incondizionato: <input type=\"text\" name=\"scoinco\" value=\"%s\" size=\"10\" maxlength=\"10\"></td></tr>", $_scoinco);
        }
        else
        {
            echo $_banca;
            echo "</b></td></tr>";
            echo "<tr><td align=\"left\" valign=\"top\" colspan=\"1\" >Spese Varie.: <input type=\"text\" name=\"varie\" value=\"$_varie\" size=\"10\" maxlength=\"10\"></td></tr>";
        }
        echo "</table>\n";
    }
    echo "<hr>\n";


//qui dividiamo i ddtacq dal resto..

    if ($_SESSION['programma'] == "DDT_ACQ")
    {
        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
        printf("<tr><td align=\"left\" valign=\"top\"><br>N. ddt Fornitore: <input type=\"text\" name=\"ddtfornitore\" value=\"%s\" size=\"20\" maxlength=\"20\"></td>", $_ddtacq);
        printf("<td align=\"left\" valign=\"top\"><br>Fattura fornitore: <input type=\"text\" name=\"fatturacq\" value=\"%s\" size=\"20\" maxlength=\"20\"></td>", $_fatturacq);
        echo "<td align=\"left\" valign=\"top\"><br>N. protocollo iva: <input type=\"text\" name=\"protoiva\" value=\"$_protoiva\" size=\"5\" maxlength=\"4\">\n";
        echo "Suffix :<input type=\"text\" name=\"suffix_proto\" value=\"$_suffix_proto\" size=\"2\" maxlength=\"1\">\n";
        echo "Anno: <input type=\"text\" name=\"anno_proto\" value=\"$_anno_proto\" size=\"5\" maxlength=\"4\"></td></tr>\n";

        echo "</table>\n";
        echo "<hr><br>\n";
    }
    else
    {

        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"><tr>";
        echo "<td colspan=\"3\" align=\"left\" valign=\"top\"><span class=\"intestazione\"><b>Aspetto Esteriore dei Beni</b></span><br></td></tr>";
        echo "<tr><td width=\"34%\" align=\"left\" valign=\"top\">";

// CAMPO selezione Aspetto beni -----------------------------------------------------------------------------------------
        echo "Aspetto:&nbsp;</span>";
        echo "<select name=\"aspetto\">\n";
        echo "<option value=\"$_imballo\">$_imballo</option>";
        tabella_imballi("elenca_select", $_imballo, $_parametri);
        echo "</select>\n";
        echo "</td>";
        printf("<td align=\"left\" valign=\"top\">Numero Colli: <input type=\"text\" name=\"colli\" value=\"%s\" size=\"10\" maxlength=\"10\"></td>", $_colli);
        printf("<td align=\"left\" valign=\"top\">Peso Lordo: <input type=\"text\" name=\"peso\" value=\"%s\" size=\"10\" maxlength=\"10\"></td></tr>", $_pesotot);
        echo "</table>\n";
        echo "<hr>\n";

        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
        echo "<tr><td colspan=\"4\" align=\"left\" valign=\"center\"><span class=\"intestazione\"><b>Spedizione :</b></span><br>\n";
        echo "</td></tr>\n";
        echo "<tr>\n";

// CAMPO selezione Corriere -----------------------------------------------------------------------------------------

        echo "<td align=\"left\" valign=\"middle\">Vettore :&nbsp;</br>";
        echo "<select name=\"vettore\">\n";
        printf("<option value=\"%s\">%s</option>\n", $_vettore, $_vettore);
        echo "<option value=\"\"></option>";
        tabella_vettori("elenca_select", $_percorso, $_codice, $_parametri);
        echo "</select>\n";
        echo "</td>";

        echo "<td align=\"left\" valign=\"middle\">Porto :&nbsp;</br>";
        echo "<select name=\"porto\">";
        echo "<option value=\"$_porto\">$_porto</option>\n";
        echo "<option value=\"FRANCO\">FRANCO</option>\n";
        echo "<option value=\"ASSEGNATO\">ASSEGNATO</option>\n";
        echo "</select>\n";
        echo "</td>";

        echo "<td align=\"left\" valign=\"top\">\n";
        if ($_GET['azione'] != "spedizione")
        {
            echo "Spese Trasporto.:<br> <input type=\"text\" name=\"trasporto\" value=\"$_trasporto\" size=\"10\" maxlength=\"10\"><br>\n";
        }
        echo "ID Collo.:<br> <input type=\"text\" name=\"id_collo\" value=\"$_id_collo\" size=\"21\" maxlength=\"20\"></td>\n";

        echo "<td align=\"center\" valign=\"middle\">Note Documento :&nbsp;<br>";
        echo "<textarea cols=\"40\" rows=\"6\" name=\"memoart\" value=\"$_notedoc\">$_notedoc</textarea></td></tr>\n";

        echo "</table>\n";
    }
    echo "<hr>\n";
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr><td colspan=\"3\" align=\"left\" valign=\"center\"><span class=\"intestazione\"><b>Dati documento  - $_tdoc / $_suffix</b></span><br></td></tr>\n";
    echo "<tr><td width=\"34%\" align=\"left\" valign=\"center\">\n";


    if ($_calce != "calce2")
    {


        $_anno = date('Y');

        echo "N. Documento : \n";
        //echo "<select id=\"tipo_conto\" name=\"tipo_cf\">" . Show_suffix() . "</select>\n";
        //echo "<select id=\"codconto\" name=\"codconto\"><option>Scegli...</option>\n";
        //passiamo il suffisso;
        $_ndoc = seleziona_documento("ultimo_numero", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

        $_datareg = date("d-m-Y");


        echo "<input type=\"number\" name=\"ndoc\" value=\"$_ndoc\" required></td>\n";
        printf("<td align=\"left\" valign=\"center\">Anno doc. </span><input type=\"number\" name=\"annodoc\" value=\"%s\" required ></td>", $_anno);
        printf("<td align=\"left\" valign=\"center\">Data reg. </span><input type=\"text\" class=\"data\"  size=\"12\" name=\"datareg\" value=\"%s\" required></td></tr>", $_datareg);
        if ($_tdoc == "preventivo")
        {
            printf("<tr><td>&nbsp;</td><td>&nbsp;</td><td align=\"left\" ><span class=\"testo_blu\">Data Scad</span><input type=\"text\" class=\"data\"  size=\"12\" name=\"data_scad\" value=\"%s\" ></td></tr>", $_data_scad);
        }
    }
    else
    {
        //invertiamo la data..
        printf("N. documento : <input type=\"radio\" name=\"ndoc\" value=\"%s\" checked>%s / $_suffix</td>", $_ndoc, $_ndoc);
        printf("<td align=\"left\" valign=\"center\">Anno doc. <input type=\"radio\" name=\"annodoc\" value=\"%s\" checked>%s</td>", $_anno, $_anno);
        printf("<td align=\"left\" valign=\"center\">Data reg. <input type=\"radio\" name=\"datareg\" value=\"%s\" checked>%s</td></tr>", $_datareg, $_datareg);
        if ($_tdoc == "preventivo")
        {
            echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td align=\"left\" >Data Scad<input type=\"text\" class=\"data\"  size=\"12\" name=\"data_scad\" value=\"$_data_scad\" ></td></tr>\n";
        }
    }

    $_imponibile = $_SESSION['importi'];

    if ($_GET['azione'] != "spedizione")
    {
        if ($_datareg < $DATAIVA)
        {
            $ivasis = $ivasis - 1;
        }

        $_spese = ($_spbanca + $_spimba + $_trasporto) - $_scoinco;
        $_impostaspese = number_format((($_spese * $ivasis) / 100), $dec, '.', '');
        $_imponibile = $_imponibile + $_spese;
        $_totimposta = $_totiva + $_impostaspese;
        $_totdoc = $_imponibile + $_totimposta;
        echo "<tr>\n";
        printf("<td align=\"left\" valign=\"center\">Imponibile doc.. %s</td>", number_format(($_imponibile), $dec, '.', ''));
        printf("<td align=\"left\" valign=\"center\">Imposta doc..%s</td>", number_format(($_totimposta), $dec, '.', ''));
        printf("<td align=\"left\" valign=\"center\"><b>Totale doc.. %s</b></td></tr>", number_format(($_totdoc), $dec, '.', ''));
    }

    echo "<tr><td colspan=\"3\" align=right><br><b> Per Inserire il documento  ==> </b><input type=\"submit\" name=\"scrivi\" value=\"$_scrivi\"></td></tr></form></table>";
    echo "<hr>\n";
    annulla_doc_vendite($_dove, $_tdoc, $_anno, $_suffix, $_ndoc);

    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>