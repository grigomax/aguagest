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

//richiemiamo il motore anagrafica
require_once $_percorso . "librerie/motore_anagrafiche.php";


//carichiamo la base delle pagine:
base_html($_cosa, $_percorso);

//carichiamo interfaccia grafica


java_script($_cosa, $_percorso);

jquery_tabs($_cosa, $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);





if ($_SESSION['user']['anagrafiche'] > "1")
{

    echo "</head>\n";
    echo "<body>\n";

    echo "<center>\n";

    echo "<form action=\"modificacod.php\" id=\"modifica\" method=\"POST\">";

    if ($_SESSION['user']['anagrafiche'] >= "2")
    {
        pulsanti("ritorna", "submit", "", "get", "../../index.php", "40px", "40px", "Indietro", "", "", "Indietro", $_id);
        pulsanti("cerca", "submit", "", "get", "ricerca.php", "40px", "40px", "Cerca", "", "", "Cerca", $_id);
    }

    if ($_SESSION['user']['anagrafiche'] >= "3")
    {
        pulsanti("nuovo", "submit", "nuovo_get", "get", "modificacod.php?azione=inserisci.php", "40px", "40px", "Nuovo", $_name, $_value, $_alt, $_id);

        pulsanti("modifica", "submit", "modifica", $_formmethod, $_formaction, "40px", "40px", "Modifica", "azione", "Modifica", "Modifica", $_id);
        pulsanti("duplica", "submit", "", "get", "duplica_cod.php", "40px", "40px", "Duplica", "", "", "Duplica", $_id);
    }

    pulsanti("stampa", "submit", "_blank", "get", "scheda_art.php", "40px", "40px", "Stampa", "", "", "Stampa", $_id);
    pulsanti("aiuto", "submit", "_blank", "get", "$_percorso/manuale/visualizza_guida.php", "40px", "40px", "Aiuto", "file", "M0402.html", "Aiuto", $_id);




    echo "</center>\n";
    
// Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"100%\" border=0 cellspacing=\"0\" align=\"left\" cellpadding=\"0\">\n";

    echo "<td width=\"90%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

// mi prendo il GET appena passato

    $_articolo = trim($_GET['codice']);

    $dati = tabella_articoli("singola", $_articolo, $_parametri);

    if ($dati['errori'] != "")
    {
        echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $dati[errori]</h3></span>";
        exit;
    }

    echo "<span class=\"testo_blu\"><h3>Visualizzazione ARTICOLO</h3></span>";

#echo "<h2 class=\"demoHeaders\">Tabs</h2>\n";
    echo "<div id=\"tabs\" style=\"z-index: 1;\">\n";
    echo "<ul>\n";
    echo "<li><a href=\"#tabs-1\">Generale</a></li>\n";
    echo "<li><a href=\"#tabs-2\">Acquisti</a></li>\n";
    echo "<li><a href=\"#tabs-3\">Dettagli articolo</a></li>\n";
    echo "<li><a href=\"#tabs-4\">Ultimi Muovimenti</a></li>\n";
    echo "</ul>\n";


#sezione Generale..
    echo "<div id=\"tabs-1\">\n";

    echo "<table class=\"classic_bordo\">";

// CAMPO Articolo ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
    printf("<td align=\"left\"><input type=\"radio\" name=\"articolo\" value=\"%s\" checked><b>%s</b></td><tr>\n", $dati['articolo'], $dati['articolo']);

// CAMPO Articolo ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Data Reg.:&nbsp;</span></td>\n";
    printf("<td align=\"left\">%s ultima modifica = %s</td><tr>\n", $dati['data_reg'], $dati['ts']);

// CAMPO Descrizione ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Descrizione:&nbsp;</b></span></td>";
    printf("<td align=\"left\"><b>%s</b></td></tr>\n", $dati['descrizione']);

    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Articolo Alternativo : &nbsp;</b></span></td>\n";
    printf("<td align=\"left\"><a href=\"visualizzacod.php?codice=%s\"><b>%s</b></td><tr>\n", $dati['art_alternativo'], $dati['art_alternativo']);
    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

    for ($_nlv = 1;
            $_nlv <= $nlv;
            $_nlv++)
    {
        // CAMPO pezzo vendita 1 ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Prezzo Vendita $_nlv:</b>&nbsp;</span></td>";
        echo "<td align=\"left\">";

        $dati3 = tabella_listini("singola", $_articolo, $_nlv, $_parametri);

        printf("<b>%s</b>", $dati3['listino']);

        echo "</td></tr>";
    }
    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";


    //calcoliamo l'impegno dell articolo
    $_impegno = impegni_articolo($_cosa, $_articolo, $_anno);

// fine calcolo giacenza

    echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Giacenza articolo==>&nbsp;</span></td>";
    echo "<td  align=\"left\"><b>$_impegno[giacenza]</b></td></tr>";

    echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Impegnati in conferme ==>&nbsp;</span></td>";
    echo "<td  align=\"left\"><b>$_impegno[impegnato]</b></td></tr>";
    echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Inseriti in ordine fornitore ==>&nbsp;</span></td>";
    echo "<td  align=\"left\"><b>$_impegno[ordinato]</b></td></tr>";

    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
// immagine articolo
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Immagine :&nbsp;</span></td>\n";
    echo "<td align=\"left\ width=\"600\">";
    printf("<img src=\"../../../imm-art/%s\" height=\"250\" width=\"250\">", $dati['immagine']);
    if ($dati[immagine2] != "")
    {
        echo "<img src=\"../../../imm-art/disegni/$dati[immagine2]\" height=\"250\" width=\"250\"></td></tr>\n";
    }


    // descrizione articolo estesa
// CAMPO note articolo -----------------------------------------------------------------------------------------
    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Desc. estesa:&nbsp;</span></td>\n";
    echo "<td align=\"left\" width=\"70%\"> $dati[descsito]</td></tr>\n";

    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";


    echo "</table>\n";
    echo "</div>\n";
#fine generale
#inizio sezione acquisti..
    echo "<div id=\"tabs-2\">\n";

    echo "<table class=\"classic_bordo\">";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Ultimo acquisto:&nbsp;</span></td>";
    printf("<td> %s</td></tr>", $dati['ultacq']);
    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

// CAMPO selezione fornitore -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">* Fornitore Principale:&nbsp;</span></td>\n";
    echo "<td align=\"left\">";

    //prendiamo il fornitore 1

    $dati2 = tabella_fornitori("singola", $dati['fornitore'], "silent");

    echo "<span class=\"testo_blu\">";

    printf("%s", $dati2['ragsoc']);
    printf("%s</td></tr>\n", $dati2['fornitore']);


// CAMPO prezzo listino acquisto ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Codice fornitore:&nbsp;</span></td>";
    printf("<td>%s</td></tr>", $dati['artfor']);


// CAMPO prezzo listino acquisto ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Listino acquisto:&nbsp;</span></td>";
    printf("<td>%s &nbsp;", $dati['prelisacq']);

// CAMPI sconti ---------------------------------------------------------------------------------------
#echo "<tr><td colspan=\"1\" align=\"left\"><span class=\"testo_blu\">Sconti.:&nbsp;</span></td>";
    printf("<span class=\"testo_blu\">Sconto :&nbsp;</span> %s+%s+%s &nbsp;", $dati['scaa'], $dati['scab'], $dati['scac']);

// CAMPO prezzo netto acquisto -----------------------------------------------------------------------------------------
    echo "<span class=\"testo_blu\">Netto:&nbsp;</span> $dati[preacqnetto]</td>";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">min. ordinabile | per cartone | multipla per ord</span></td>";
    echo "<td>$dati[qtaminord] | $dati[qta_cartone] | $dati[qta_multi_ord]</td></tr>\n";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Prodotto composto | Stato prodotto | Tempo medio consegna</span></td>";
    echo "<td>$dati[prod_composto] | $dati[stato_prod] | $dati[lead_time]</td></tr>\n";


    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Ultima data variazione prezzo:&nbsp;</span></td>";
    echo "<td>$dati[data_var] </td></tr>\n";



    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">* Fornitore Secondario:&nbsp;</span></td>\n";
    echo "<td align=\"left\">";

    $dati2 = tabella_fornitori("singola", $dati['fornitore2'], "silent");

    echo "<span class=\"testo_blu\">";

    printf("%s", $dati2['ragsoc']);
    printf("%s</td></tr>\n", $dati2['fornitore']);

    $_fornitorep = $_dati2['fornitore'];

// CAMPO prezzo listino acquisto ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Codice fornitore secondario:&nbsp;</span></td>";
    printf("<td>%s</td></tr>", $dati['artfor2']);


// CAMPO prezzo listino acquisto ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Listino acquisto:&nbsp;</span></td>";
    printf("<td>%s &nbsp;", $dati['prelisacq_2']);

// CAMPI sconti ---------------------------------------------------------------------------------------
#echo "<tr><td colspan=\"1\" align=\"left\"><span class=\"testo_blu\">Sconti.:&nbsp;</span></td>";
    printf("<span class=\"testo_blu\">Sconto :&nbsp;</span> %s+%s+%s &nbsp;", $dati['scaa_2'], $dati['scab_2'], $dati['scac_2']);

// CAMPO prezzo netto acquisto -----------------------------------------------------------------------------------------
    echo "<span class=\"testo_blu\">Netto:&nbsp;</span> $dati[preacqnetto2]</td></tr>";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">min. ordinabile | per cartone | multipla per ord</span></td>";
    echo "<td>$dati[qtaminord_2] | $dati[qta_cartone_2] | $dati[qta_multi_ord_2]</td></tr>\n";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Prodotto composto | Stato prodotto | Tempo medio consegna</span></td>";
    echo "<td>$dati[prod_composto_2] | $dati[stato_prod_2] | $dati[lead_time_2]</td></tr>\n";


    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Ultima data variazione prezzo:&nbsp;</span></td>";
    echo "<td>$dati[data_var_2] </td></tr>\n";

    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">* Fornitore Terziario:&nbsp;</span></td>\n";
    echo "<td align=\"left\">";

    $dati2 = tabella_fornitori("singola", $dati['fornitore_3'], "silent");

    echo "<span class=\"testo_blu\">";
    printf("%s", $dati2['ragsoc']);


// CAMPO prezzo listino acquisto ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Codice terzo fornitore:&nbsp;</span></td>";
    printf("<td>%s</td></tr>", $dati['artfor_3']);


// CAMPO prezzo listino acquisto ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Listino acquisto:&nbsp;</span></td>";
    printf("<td>%s &nbsp;", $dati['prelisacq_3']);

// CAMPI sconti ---------------------------------------------------------------------------------------
#echo "<tr><td colspan=\"1\" align=\"left\"><span class=\"testo_blu\">Sconti.:&nbsp;</span></td>";
    printf("<span class=\"testo_blu\">Sconto :&nbsp;</span> %s+%s+%s &nbsp;", $dati['scaa_3'], $dati['scab_3'], $dati['scac_3']);

// CAMPO prezzo netto acquisto -----------------------------------------------------------------------------------------
    echo "<span class=\"testo_blu\">Netto:&nbsp;</span> $dati[preacqnetto_3]</td></tr>";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">min. ordinabile | per cartone | multipla per ord</span></td>";
    echo "<td>$dati[qtaminord_3] | $dati[qta_cartone_3] | $dati[qta_multi_ord_3]</td></tr>\n";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Prodotto composto | Stato prodotto | Tempo medio consegna</span></td>";
    echo "<td>$dati[prod_composto_3] | $dati[stato_prod_3] | $dati[lead_time_3]</td></tr>\n";


    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Ultima data variazione prezzo:&nbsp;</span></td>";
    echo "<td>$dati[data_var_3] </td></tr>\n";

    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
// CAMPO note articolo -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Note articolo:&nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    printf("%s</td></tr>", $dati['memoart']);

    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

    echo "<tr><td colspan=\"2\"><h3>LEGENDA</h3></td></tr>\n";
    echo "<tr><td ><h3>Tempi di consegna</h3></td><td> numeri 1 a 9 giorni di lavoro <br> A = 2sett, B = 3 sett. ecc.. J = 11, R=19, Z=27  </td></tr>\n";

    echo "<tr><td ><h3>Prodotto composto</h3></td><td> 1 = SI è un Kit, 0 NO non lo è</td></tr>\n";
    echo "<tr><td ><h3>Stato prodotto</h3></td><td> 1 = Nuovo Prodotto <br> 2 = prodotto in esaurimento <br>3 Prodotto Gestito a magazzino"
    . "<br> 4 Nuovo servizio <br> 5 Servizio annullato <br> 6 = Prodotto a commessa, producibile su ordine <br>"
    . "7 = Articolo a commessa in via di esaurimento - annullamento <br> 8 = servizio (non merce fisica) <br>9 = prodotto annullato</td></tr>\n";
    echo "</table>\n";
    echo "</div>\n";


#inizio sezione dettagli
    echo "<div id=\"tabs-3\">\n";
    echo "<table class=\"classic_bordo\">";
// CAMPO selezione categoria merceologica -------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Categoria Merceologica:&nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    $catmer = tabella_catmer("singola_codice", $dati['catmer'], $_parametri);
    echo "$catmer[codice] - $catmer[catmer]</td></tr>\n";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Tipologia Articolo:&nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    $tipart = tabella_tipart("singola_codice", $dati['tipart'], $_parametri);
    echo "$tipart[codice] - $tipart[tipoart]</td></tr>\n";


// CAMPO pAGINA CATALOGO -------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Peso articolo:&nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    printf("%s in Kg</td></tr>", $dati['pesoart']);

// CAMPO Unita' di misura ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Articolo correlato 1 | 2 | 3&nbsp;</span></td>";
    echo "<td align=\"left\">$dati[artcorr] | $dati[artcorr_2] | $dati[artcorr_3] </td></tr>\n";

// CAMPO iva -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Posizione:&nbsp;</span></td>";
    printf("<td align=\"left\">Settore = %s, Scaffale = %s, Ripiano = %s, Cassetto = %s</td></tr>", $dati['a_settore'], $dati['a_scaffale'], $dati['a_ripiano'], $dati['a_cassetto']);

// ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Sito internet .:&nbsp;</span></td>";
    printf("<td align=\"left\"><a href=\"http://%s\" target=\"_blank\">%s</td></tr>", $dati['sitoart'], $dati['sitoart']);

    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

// CAMPO selezione categoria merceologica -------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Pagina Catalogo:&nbsp;</span></td>\n";
    echo "<td align=\"left\">";
    printf("%s</td></tr>", $dati['pagcat']);

    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Codici a barre associati.</span></td>\n";
    echo "<td align=\"left\">";

    $result = tabella_barcode("elenco_codice", "", $_articolo, $_rigo);

    #echo "Rigo 1 codice $_articolo <br>\n";
    foreach ($result AS
            $dati)
    {

        echo "Rigo $dati[rigo] codice $dati[codbar] <br>\n";
    }

    echo "</td></tr>\n";

    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Nuovo codice</span></td>";
    echo "<td align=\"left\"><a href=\"barcode/maschera_codbar.php?azione=nuovo&articolo=$_articolo\">Inserisci nuovo codice a barre</td></tr>\n";

    echo "</table>";

    echo "</div>\n";

    //----------------------------------------------------------------tabella 4

    echo "<div id=\"tabs-4\">\n";
    echo "<table class=\"classic_bordo\">";

    //prendiamo l'anno aperto negli archivi
    $_anno = tabella_magazzino("prendi_anno", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_tut, $_rigo, $_utente, $_codice, $_parametri);

    echo "<tr><td colspan=\"7\" align=\"left\"><span class=\"testo_blu\"><br>Ultimi Muovimenti Acquisto &nbsp;</span></td></tr>";

    echo "<tr><td align=\"left\">Data Reg.</td><td> Numero Doc.</td><td colspan=\"2\" align=\"left\">Fornitore</td><td>Q.ta carico</td><td>Valore</td><td>Netto acq.</td></tr> ";

    $res = tabella_magazzino("muov_acquisto", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_tut, $_rigo, $_utente, $_articolo, "10");


    // Tutto procede a meraviglia...
    echo "<span class=\"testo_blu\">";

    foreach ($res AS
            $dati)
    {
        @$_nettoacq = $dati['valoreacq'] / $dati['qtacarico'];

        $_annodoc = substr($dati['anno'], 0, 4);

        echo "<tr><td align=\"left\">$dati[datareg]</td><td><a href=\"../../vendite/docubase/visualizzadoc.php?tdoc=ddtacq&anno=$_annodoc&suffix=$dati[suffix]&ndoc=$dati[ndoc]\">$dati[ndoc]/$dati[suffix]</a></td><td colspan=\"2\" align=\"left\">$dati[ragsoc]</td><td align=\"center\">$dati[qtacarico]</td><td>$dati[valoreacq]</td><td>".number_format(($_nettoacq), 2)."</td></tr>\n";
    }

    echo "</tr>";

// inizio muovimenti uscita
    echo "<tr><td colspan=6 align=\"left\"><span class=\"testo_blu\">Ultimi Muovimenti Vendita &nbsp;</span></td></tr>";

    echo "<tr><td align=\"left\">T. Doc.</td><td align=\"left\">Data Reg.</td><td> Numero Doc.</td><td align=\"left\">Cliente</td><td>Q.ta scarico</td><td>Valore</td><td>Netto Vendita</td></tr> ";

    $res = tabella_magazzino("muov_vendita", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_tut, $_rigo, $_utente, $_articolo, "10");
    echo "<span class=\"testo_blu\">";
    foreach ($res AS
            $dati)
    {
        @$_nettovend = $dati['valorevend'] / $dati['qtascarico'];
        echo "<tr><td align=\"left\">$dati[tdoc]</td><td align=\"left\">$dati[datareg]</td><td><a href=\"../../vendite/docubase/visualizzadoc.php?tdoc=$dati[tdoc]&anno=$dati[anno]&suffix=$dati[suffix]&ndoc=$dati[ndoc]\">$dati[ndoc]/$dati[suffix]</a></td><td align=\"left\">$dati[ragsoc]</td><td>$dati[qtascarico]</td><td>$dati[valorevend]</td><td>".number_format(($_nettovend), 2)."</td></tr>\n";
    }

    echo "</tr></table>"; // chiusura tabelle interna
    echo "<br>";



    echo "<table class=\"classic_bordo\" border=\"0\">";
    echo "<tr><td colspan=\"6\"><hr></td></tr>\n";

    echo "<tr><td colspan=6 align=\"left\"><span class=\"testo_blu\"><br><a href=\"../../mag/gestmag/muovimenti/ricercadoc.php?descrizione=$_articolo\">Clicca Qui per cercare tutti gli altri muovimenti</a></span></td></tr>";

    echo "</tr></table>"; // chiusura tabelle interna
    echo "</div>\n";

    echo "</div>\n";


    echo "<br>";

    echo "</form>";
    echo "</td></tr></table>"; //chiusura seconda tabella
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>