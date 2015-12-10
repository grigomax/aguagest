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

//carico il motore anagrafiche
require_once $_percorso . "librerie/motore_anagrafiche.php";

//inizio parte visiva..

base_html($_cosa, $_percorso);


java_script($_cosa, $_percorso);

jquery_tabs($_cosa, $_percorso);

tiny_mce($_cosa, $_percorso);

echo "</head>\n";

echo "<body>\n";

testata_html($_cosa, $_percorso);

menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['anagrafiche'] > "2")
{

//selezioniamo l'articolo o lo inseriamo

    $_azione = $_POST['azione'];

    if ($_azione == "")
    {
        $_azione = $_GET['azione'];
    }


    if ($_azione == "Modifica")
    {
        $_articolo = $_POST['articolo'];

        if ($_articolo == "")
        {
            $_articolo = $_GET['articolo'];
        }

        $dati = tabella_articoli("singola", $_articolo, $_parametri);

        if ($dati['errori'] != "")
        {
            // Inizio tabella pagina principale ----------------------------------------------------------
            echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
            echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $dati[errori]</h3></span>";
            echo "<center><h2><br> Errore nessun articolo Trovato si prega di tornare indietro e riprovare</h2>\n";
            exit;
        }

        $_submit = "Aggiorna";
    }
    else
    {
        $_submit = "Inserisci";
        $dati['esco'] = "NO";
        $dati['esma'] = "NO";
        $dati['pubblica'] = "SI";
    }

// Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"95%\" cellspacing=\"0\" border=\"0\" align=\"left\" valign=\"top\" cellpadding=\"0\">\n";


    echo "<tr><td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

    if ($_SESSION['user']['anagrafiche'] > "1")
    {



        echo "<form action=\"risinseart.php\" id=\"inserisci\" method=\"POST\">";

       

        echo "<span class=\"testo_blu\"><h3><center>Modifica articolo</center></h3></span>";

//definiamo i tabs
        echo "<div id=\"tabs\">\n";
        echo "<ul>\n";
        echo "<li><a href=\"#tabs-1\">Generale</a></li>\n";
        echo "<li><a href=\"#tabs-2\">Acquisti</a></li>\n";
        echo "<li><a href=\"#tabs-3\">Vendite</a></li>\n";
        echo "<li><a href=\"#tabs-4\">Dettagli</a></li>\n";
        echo "<li><a href=\"#tabs-5\">Pubblica</a></li>\n";
        echo "</ul>\n";

        echo "<div id=\"tabs-1\">\n";

        echo "<table class=\"classic_bordo\" border=\"0\"";

        if ($_azione == "Modifica")
        {
            // CAMPO Articolo ---------------------------------------------------------------------------------------
            echo "<tr><td align=\"right\"><span class=\"testo_blu\">* Codice:&nbsp;</span></td>\n";

            printf("<td align=\"left\"><input type=\"radio\" name=\"articolo\" value=\"%s\" checked>%s</td><tr>\n", $_articolo, $_articolo);
        }
        else
        {
// CAMPO Articolo ---------------------------------------------------------------------------------------
            echo "<tr><td align=\"right\"><span class=\"testo_blu\"><b>*Codice:&nbsp;</b></span></td>\n";
            echo "<td align=\"left\"><input type=\"text\" name=\"articolo\" size=\"20\" maxlength=\"15\" autofocus></td><tr>\n";
        }
// CAMPO Descrizione ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\"><b>Descrizione:&nbsp;</b></span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"90\" maxlength=\"120\"></td></tr>\n", $dati['descrizione']);

// CAMPO DESCRIZIONE RIDOTTA ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Descr. ridotta :&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" name=\"desrid\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['desrid']);

// CAMPO Unita' di misura ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">* U.M.:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"unita\" value=\"%s\" size=\"3\" maxlength=\"2\"></td></tr>", $dati['unita']);

// CAMPO IVA -----------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">* I.V.A. :&nbsp;</span></td>\n";
        echo "<td align=\"left\">";

//gestione iva
        $iva = tabella_aliquota("singola", $dati['iva'], $_percorso);

// Tutto procede a meraviglia...
        echo "<span class=\"testo_blu\">";
        echo "<select name=\"iva\">\n";
        printf("<option value=\"%s\">%s</option>\n", $iva['codice'], $iva['descrizione']);

        $res = tabella_aliquota("elenca_codice", $_codiva, $_percorso);
        // Tutto procede a meraviglia...
        echo "<span class=\"testo_blu\">";
        foreach ($res AS $dati3)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati3['codice'], $dati3['codice'], $dati3['descrizione']);
        }

        echo "</select>\n";
        echo "</td></tr>";

// CAMPO selezione categoria merceologica -----------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Categoria Merceologica:&nbsp;</span></td>\n";
        echo "<td align=\"left\">";

        tabella_catmer("elenca_select_2", $dati[catmer], "catmer");

        echo "</td></tr>\n";

// CAMPO tipo articolo -----------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Tipo articolo:&nbsp;</span></td>\n";
        echo "<td align=\"left\">";

        tabella_tipart("elenca_select_2", $dati[tipart], "tipart");
        echo "</td></tr>\n";


// Campo esenzione gestione magazzino articolo se si non scarica il magazzino
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Esente muovimento magazzino :&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" value=\"%s\" name=\"esma\" size=\"3\" maxlength=\"2\"> Se <b> SI </b> esclude l' articolo da tutti i muovimenti di carico e scarico del magazzino</td><tr>", $dati['esma']);

        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Escludi Ricerca veloce :&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" value=\"%s\" name=\"es_selezione\" size=\"3\" maxlength=\"2\"> Se <b> SI </b> esclude l' articolo dalla selezione a tendina</td><tr>", $dati['es_selezione']);

        
        
        echo "</table>\n";
        echo "</div>\n";
#fine generale
#inizio sezione acquisti..
        echo "<div id=\"tabs-2\">\n";

        echo "<table class=\"classic_bordo\">";

        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Ultimo acquisto</span></td>";
        printf("<td align=\"left\">Ultimo acquisto = %s</td></tr>", $dati['ultacq']);



// CAMPO selezione fornitore -----------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">* Fornitore:&nbsp;</span></td>\n";
        echo "<td align=\"left\">";

        $datif = tabella_fornitori("singola", $dati['fornitore'], $_parametri);
        echo "<span class=\"testo_blu\">";

        echo "<select name=\"fornitore\">\n";
        printf("<option value=\"%s\">%s</option>\n", $dati['fornitore'], $datif['ragsoc']);
        echo "<option value=\"\"></option>\n";

        $res = tabella_fornitori("elenca", $_utente, $_parametri);
        echo "<span class=\"testo_blu\">";
        foreach ($res AS $dati3)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati3['codice'], $dati3['ragsoc']);
        }
        echo "</select>\n";

        echo "</td></tr>";

        // CAMPO codice a fornitore ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Codice fornitore:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"artfor\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['artfor']);
        ?>
        <SCRIPT LANGUAGE="JavaScript">

            function sconto(form) {
                prelisacq = eval(form.prelisacq.value)
                scaa = eval(form.scaa.value)
                scab = eval(form.scab.value)
                scac = eval(form.scac.value)

                nettoacq = prelisacq

                if (scaa >= 0)
                    nettoacq = (prelisacq - ((prelisacq * scaa) / 100));
                if (scab >= 0)
                    nettoacq = (nettoacq - ((nettoacq * scab) / 100));
                if (scac >= 0)
                    nettoacq = (nettoacq - ((nettoacq * scac) / 100));

                form.preacqnetto.value = nettoacq
            }

            function sconto_2(form) {
                prelisacq_2 = eval(form.prelisacq_2.value)
                scaa_2 = eval(form.scaa_2.value)
                scab_2 = eval(form.scab_2.value)
                scac_2 = eval(form.scac_2.value)

                nettoacq_2 = prelisacq_2

                if (scaa_2 >= 0)
                    nettoacq_2 = (prelisacq_2 - ((prelisacq_2 * scaa_2) / 100));
                if (scab_2 >= 0)
                    nettoacq_2 = (nettoacq_2 - ((nettoacq_2 * scab_2) / 100));
                if (scac_2 >= 0)
                    nettoacq_2 = (nettoacq_2 - ((nettoacq_2 * scac_2) / 100));

                form.preacqnetto2.value = nettoacq_2
            }

            function sconto_3(form) {
                prelisacq_3 = eval(form.prelisacq_3.value)
                scaa_3 = eval(form.scaa_3.value)
                scab_3 = eval(form.scab_3.value)
                scac_3 = eval(form.scac_3.value)

                nettoacq_3 = prelisacq_3

                if (scaa_3 >= 0)
                    nettoacq_3 = (prelisacq_3 - ((prelisacq_3 * scaa_3) / 100));
                if (scab_3 >= 0)
                    nettoacq_3 = (nettoacq_3 - ((nettoacq_3 * scab_3) / 100));
                if (scac_3 >= 0)
                    nettoacq_3 = (nettoacq_3 - ((nettoacq_3 * scac_3) / 100));

                form.preacqnetto_3.value = nettoacq_3
            }

            // End -->
        </SCRIPT>
        <?php
        // CAMPO prezzo listino acquisto ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Listino acquisto:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"prelisacq\" onChange=\"sconto(this.form)\" value=\"%s\" size=\"10\" maxlength=\"10\"></td></tr>", $dati['prelisacq']);

// CAMPI sconti ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Sconti.:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"scaa\" onChange=\"sconto(this.form)\" value=\"%s\" size=\"4\" maxlength=\"4\">+
	<input type=\"text\" name=\"scab\" onChange=\"sconto(this.form)\" value=\"%s\" size=\"4\" maxlength=\"4\">
	+<input type=\"text\" name=\"scac\" value=\"%s\" size=\"4\" maxlength=\"4\" onChange=\"sconto(this.form)\"></td></tr>", $dati['scaa'], $dati['scab'], $dati['scac']);


// CAMPO prezzo netto acquisto -----------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Prezzo netto:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"preacqnetto\" value=\"%s\" size=\"10\" maxlength=\"10\"></td></tr>", $dati['preacqnetto']);

        echo "<tr><td align=\"right\"><span class=\"testo_blu\">min. ordinabile | per cartone | multipla per ord</span></td>";
        echo "<td align=\"left\"><input type=\"text\" name=\"qtaminord\" value=\"$dati[qtaminord]\" size=\"6\" maxlength=\"6\"> | <input type=\"text\" name=\"qta_cartone\" value=\"$dati[qta_cartone]\" size=\"6\" maxlength=\"6\"> | <input type=\"text\" name=\"qta_multi_ord\" value=\"$dati[qta_multi_ord]\" size=\"6\" maxlength=\"6\"></td></tr>\n";
        
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Prodotto composto | Stato prodotto | Tempo medio consegna</span></td>";
        echo "<td align=\"left\"><input type=\"text\" name=\"prod_composto\" value=\"$dati[prod_composto]\" size=\"3\" maxlength=\"2\"> | <input type=\"text\" name=\"stato_prod\" value=\"$dati[stato_prod]\" size=\"3\" maxlength=\"2\"> | <input type=\"text\" name=\"lead_time\" value=\"$dati[lead_time]\" size=\"3\" maxlength=\"2\"></td></tr>\n";
        

        
        
        echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
// CAMPO selezione fornitore secondario-----------------------------------------------------------------------------------------

        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Secondo Fornitore:&nbsp;</span></td>\n";
        echo "<td align=\"left\">";

        $datif = tabella_fornitori("singola", $dati['fornitore2'], $_parametri);
        echo "<span class=\"testo_blu\">";

        echo "<select name=\"fornitore2\">\n";
        printf("<option value=\"%s\">%s</option>\n", $dati['fornitore2'], $datif['ragsoc']);
        echo "<option value=\"\"></option>\n";

        $res = tabella_fornitori("elenca", $_utente, $_parametri);
        echo "<span class=\"testo_blu\">";
        foreach ($res AS $dati3)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati3['codice'], $dati3['ragsoc']);
        }
        echo "</select>\n";

        echo "</td></tr>";

// CAMPO codice a fornitore secondario ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Codice secondo fornitore:&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" name=\"artfor2\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>\n", $dati['artfor2']);

        // CAMPO prezzo listino acquisto ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Listino acquisto:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"prelisacq_2\" onChange=\"sconto_2(this.form)\" value=\"%s\" size=\"10\" maxlength=\"10\"></td></tr>", $dati['prelisacq_2']);

// CAMPI sconti ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Sconti.:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"scaa_2\" onChange=\"sconto_2(this.form)\" value=\"%s\" size=\"4\" maxlength=\"4\">+
	<input type=\"text\" name=\"scab_2\" onChange=\"sconto_2(this.form)\" value=\"%s\" size=\"4\" maxlength=\"4\">+
	<input type=\"text\" name=\"scac_2\" value=\"%s\" size=\"4\" maxlength=\"4\" onChange=\"sconto_2(this.form)\" >
	</td></tr>", $dati['scaa_2'], $dati['scab_2'], $dati['scac_2']);

// CAMPO prezzo netto acquisto------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Prezzo netto acquisto:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"preacqnetto2\" value=\"%s\" size=\"10\" maxlength=\"10\"></td><tr>", $dati['preacqnetto2']);

        echo "<tr><td align=\"right\"><span class=\"testo_blu\">min. ordinabile | per cartone | multipla per ord</span></td>";
        echo "<td align=\"left\"><input type=\"text\" name=\"qtaminord_2\" value=\"$dati[qtaminord_2]\" size=\"6\" maxlength=\"6\"> | <input type=\"text\" name=\"qta_cartone_2\" value=\"$dati[qta_cartone_2]\" size=\"6\" maxlength=\"6\"> | <input type=\"text\" name=\"qta_multi_ord_2\" value=\"$dati[qta_multi_ord_2]\" size=\"6\" maxlength=\"6\"></td></tr>\n";
        
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Prodotto composto | Stato prodotto | Tempo medio consegna</span></td>";
        echo "<td align=\"left\"><input type=\"text\" name=\"prod_composto_2\" value=\"$dati[prod_composto_2]\" size=\"3\" maxlength=\"2\"> | <input type=\"text\" name=\"stato_prod_2\" value=\"$dati[stato_prod_2]\" size=\"3\" maxlength=\"2\"> | <input type=\"text\" name=\"lead_time_2\" value=\"$dati[lead_time_2]\" size=\"3\" maxlength=\"2\"></td></tr>\n";
        
        
        echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
// CAMPO selezione fornitore secondario-----------------------------------------------------------------------------------------

        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Terzo Fornitore:&nbsp;</span></td>\n";
        echo "<td align=\"left\">";

        $datif = tabella_fornitori("singola", $dati['fornitore_3'], $_parametri);
        echo "<span class=\"testo_blu\">";

        echo "<select name=\"fornitore_3\">\n";
        printf("<option value=\"%s\">%s</option>\n", $dati['fornitore_3'], $datif['ragsoc']);
        echo "<option value=\"\"></option>\n";

        $res = tabella_fornitori("elenca", $_utente, $_parametri);
        echo "<span class=\"testo_blu\">";
        foreach ($res AS $dati3)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati3['codice'], $dati3['ragsoc']);
        }
        echo "</select>\n";

        echo "</td></tr>";

        
// CAMPO codice a fornitore secondario ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Codice terzo fornitore:&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" name=\"artfor_3\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>\n", $dati['artfor_3']);

        // CAMPO prezzo listino acquisto ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Listino acquisto:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"prelisacq_3\" onChange=\"sconto_3(this.form)\" value=\"%s\" size=\"10\" maxlength=\"10\"></td></tr>", $dati['prelisacq_3']);

// CAMPI sconti ---------------------------------------------------------------------------------------
        echo "<tr><td colspan=\"1\" align=\"right\"><span class=\"testo_blu\">Sconti.:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"scaa_3\" onChange=\"sconto_3(this.form)\" value=\"%s\" size=\"4\" maxlength=\"4\">+
	<input type=\"text\" name=\"scab_3\" onChange=\"sconto_3(this.form)\" value=\"%s\" size=\"4\" maxlength=\"4\">
	+<input type=\"text\" name=\"scac_3\" value=\"%s\" onChange=\"sconto_3(this.form)\" size=\"4\" maxlength=\"4\">
	</td></tr>", $dati['scaa_3'], $dati['scab_3'], $dati['scac_3']);

// CAMPO prezzo netto acquisto------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Prezzo netto acquisto:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"preacqnetto_3\" value=\"%s\" size=\"10\" maxlength=\"10\"></td><tr>", $dati['preacqnetto_3']);

        echo "<tr><td align=\"right\"><span class=\"testo_blu\">min. ordinabile | per cartone | multipla per ord</span></td>";
        echo "<td align=\"left\"><input type=\"text\" name=\"qtaminord_3\" value=\"$dati[qtaminord_3]\" size=\"6\" maxlength=\"6\"> | <input type=\"text\" name=\"qta_cartone_3\" value=\"$dati[qta_cartone_3]\" size=\"6\" maxlength=\"6\"> | <input type=\"text\" name=\"qta_multi_ord_3\" value=\"$dati[qta_multi_ord_3]\" size=\"6\" maxlength=\"6\"></td></tr>\n";
        
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Prodotto composto | Stato prodotto | Tempo medio consegna</span></td>";
        echo "<td align=\"left\"><input type=\"text\" name=\"prod_composto_3\" value=\"$dati[prod_composto_3]\" size=\"3\" maxlength=\"2\"> | <input type=\"text\" name=\"stato_prod_3\" value=\"$dati[stato_prod_3]\" size=\"3\" maxlength=\"2\"> | <input type=\"text\" name=\"lead_time_3\" value=\"$dati[lead_time_3]\" size=\"3\" maxlength=\"2\"></td></tr>\n";
        
        
        
        echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
// CAMPO memo ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Note articolo :&nbsp;</span></td>";
        echo "<td align=\"left\"><textarea id=\"elm1\" name=\"memoart\" style=\"width:100%; height:300px;\" >$dati[memoart]</textarea></td></tr>\n";
        #echo "<textarea id=\"elm1\" name=\"elm1\" rows=\"15\" cols=\"80\" style=\"width: 80%\"> </textarea>\n";
        echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

        echo "<tr><td colspan=\"2\"><h3>LEGENDA</h3></td></tr>\n";
        echo "<tr><td ><h3>Tempi di consegna</h3></td><td> numeri 1 a 9 giorni di lavoro <br> A = 2sett, B = 3 sett. ecc.. J = 11, R=19, Z=27  </td></tr>\n";

        echo "<tr><td ><h3>Prodotto composto</h3></td><td> 1 = SI è un Kit, 0 NO non lo è</td></tr>\n";
        echo "<tr><td ><h3>Stato prodotto</h3></td><td> 1 = Nuovo Prodotto <br> 2 = prodotto in esaurimento <br>3 Prodotto Gestito a magazzino"
        . "<br> 4 Nuovo servizio <br> 5 Servizio annullato <br> 6 = Prodotto a commessa, producibile su ordine <br>"
                . "7 = Articolo a commessa in via di esaurimento - annullamento <br> 8 = servizio (non merce fisica) <br>9 = prodotto annullato</td></tr>\n";
	
        
        
        echo "</table>\n";
        echo "</div>\n";
#fine generale
//
#inizio sezione acquisti..
        echo "<div id=\"tabs-3\">\n";

        echo "<table class=\"classic_bordo\" >";

// Campo esenzione sconti articolo se si elimina tutti gli sconti
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Esente sconti :&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" value=\"%s\" name=\"esco\" size=\"3\" maxlength=\"2\"> Se <b> SI </b> esclude l' articolo da tutti gli sconti possibili</td><tr>\n", $dati['esco']);

        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Esente da Gestione prezzo zero :&nbsp;</span></td>\n";
        echo "<td align=\"left\"><input type=\"text\" value=\"$dati[egpz]\" name=\"egpz\" size=\"3\" maxlength=\"2\"> Se <b> SI </b> Non blocca la fatturazione durante la vendita a prezzo zero</td><tr>\n";

        for ($_nlv = 1; $_nlv <= $nlv; $_nlv++)
        {

            // CAMPO pezzo vendita 1 ---------------------------------------------------------------------------------------
            echo "<tr><td align=\"right\"><span class=\"testo_blu\"><b>Prezzo Vendita $_nlv:</b>&nbsp;</span></td>";
            // Stringa contenente la query di ricerca...
            $dati3 = tabella_listini("singola", $dati[articolo], $_nlv, $_parametri);
            // Tutto procede a meraviglia...
            echo "<span class=\"testo_blu\">";

            printf("<td align=\"left\"><b><input type=\"text\" name=\"listino$_nlv\" value=\"%s\" size=\"10\" maxlength=\"10\"></b></td></tr>", $dati3['listino']);
        }



        echo "</table>\n";
        echo "</div>\n";
#fine generale
#inizio sezione acquisti..
        echo "<div id=\"tabs-4\">\n";

        echo "<table class=\"classic_bordo\" >";


// CAMPO Provvigioni articolo-----------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Provvigioni articolo:&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" name=\"provvart\" value=\"%s\" size=\"5\" maxlength=\"5\"></td></tr>\n", $dati['provvart']);

// CAMPO scorta minima------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Scorta Minina :&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"scorta\" value=\"%s\" size=\"20\" maxlength=\"10\"></td><tr>", $dati['scorta']);

// CAMPO peso articolo--------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Peso articolo:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"pesoart\" value=\"%s\" size=\"10\" maxlength=\"10\"></td><tr>", $dati['pesoart']);

// CAMPO peso articolo------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Codice articolo correlato&nbsp;</span></td>";
        echo "<td align=\"left\"><input type=\"text\" name=\"artcorr\" value=\"$dati[artcorr]\" size=\"16\" maxlength=\"15\"> | <input type=\"text\" name=\"artcorr_2\" value=\"$dati[artcorr_2]\" size=\"16\" maxlength=\"15\">
             | <input type=\"text\" name=\"artcorr_3\" value=\"$dati[artcorr_3]\" size=\"16\" maxlength=\"15\"><br>
    In Questo campo e possibile inserire un codice articolo correlato che apparirà nella maschera quantità</td><tr>";

// CAMPO peso articolo------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Codice articolo Alternativo&nbsp;</span></td>";
        echo "<td align=\"left\"><input type=\"text\" name=\"art_alternativo\" value=\"$dati[art_alternativo]\" size=\"16\" maxlength=\"15\">
    In Questo campo e possibile inserire un codice articolo alternativo</td><tr>";

// CAMPO peso articolo------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Posizione articolo&nbsp;</span></td>";
        echo "<td align=\"left\">Settore = <input type=\"text\" value=\"$dati[a_settore]\" name=\"a_settore\"  size=\"11\" maxlength=\"10\">
		Scaffale = <input type=\"text\" name=\"a_scaffale\" value=\"$dati[a_scaffale]\" size=\"6\" maxlength=\"5\">
		Ripiano = <input type=\"text\" name=\"a_ripiano\" value=\"$dati[a_ripiano]\" size=\"6\" maxlength=\"5\">
		Cassetto = <input type=\"text\" name=\"a_cassetto\" value=\"$dati[a_cassetto]\" size=\"6\" maxlength=\"5\">
		</td><tr>";

// CAMPO sito internet ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Sito internet:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"sitoart\" value=\"%s\" size=\"80\" maxlength=\"80\"></td></tr>\n", $dati['sitoart']);



        echo "</table>\n";
        echo "</div>\n";
#fine generale
#inizio sezione acquisti..
        echo "<div id=\"tabs-5\">\n";

        echo "<table class=\"classic_bordo\">";


// CAMPO pagina catalogo
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">N. Pagina Catalogo:&nbsp;</span></td>";
        printf("<td align=\"left\"><input type=\"text\" name=\"pagcat\" value=\"%s\" size=\"10\" maxlength=\"10\">N. pagina catalogo per stampa listino prezzi</td><tr>", $dati['pagcat']);

// CAMPO seleziona immagine
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Immagine articolo:&nbsp;</span></td>";
        echo "<td align=\"left\"><select name=\"immagine\">";

        printf("<option value\"%s\">%s</option>", $dati['immagine'], $dati['immagine']);


        exec("ls ../../../imm-art/ ", $resrAr);
        while (list($key, $val) = each($resrAr))
        {
            echo "<option value=\"$val\">$val\n";
        }
        echo "</select></td></tr>";


        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Disegno articolo:&nbsp;</span></td>";
        echo "<td align=\"left\"><select name=\"immagine2\">";

        printf("<option value\"%s\">%s</option>", $dati['immagine2'], $dati['immagine2']);


        exec("ls ../../../imm-art/disegni/ ", $resrAr);
        while (list($key, $val) = each($resrAr))
        {
            echo "<option value=\"$val\">$val\n";
        }
        echo "</select></td></tr>";



// Campo esenzione pubblicazione articolo
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Pubblica articolo :&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" value=\"%s\" name=\"pubblica\" size=\"3\" maxlength=\"2\"> Se <b> NO </b> esclude l' articolo da tutti i listini e da internet</td><tr>", $dati['pubblica']);

        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Tipo di Ordine:&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" value=\"%s\" name=\"ordine_cat\" size=\"31\" maxlength=\"30\"> Campo libero per la libero ordine in stampa</td><tr>", $dati['ordine_cat']);



// CAMPO memo ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"right\"><span class=\"testo_blu\">Descrizione estesa :&nbsp;</span></td>";
        echo "<td align=\"left\"><textarea name=\"descsito\" style=\"width:100%; height:400px;\" >$dati[descsito]</textarea></td></tr>\n";


        echo "</table>\n";
        echo "</div></div>\n";
        
         //inseriamo la barra di navigazione

        pulsanti("annulla", "submit", "annulla_get", "get", "../../index.php", "40px", "40px", "Annulla", $_name, $_value, $_alt, $_id);


        if ($_SESSION['user']['anagrafiche'] == "4")
        {
            pulsanti("conferma", "submit", "inserisci", $_formmethod, $_formaction, "40px", "40px", $_submit, "azione", $_submit, $_submit, $_id);
            pulsanti("elimina", "submit", "inserisci", $_formmethod, $_formaction, "40px", "40px", "Elimina", "azione", "Elimina", "Elimina", $_id);
        }
        
        if ($_SESSION['user']['anagrafiche'] == "3")
        {
            pulsanti("conferma", "submit", "inserisci", $_formmethod, $_formaction, "40px", "40px", $_submit, "azione", $_submit, $_submit, $_id);
        }
    }
    else
    {
        echo "<h2>Non hai i permessi per poter visualizzare cliente/fornitore</h2>\n";
    }

    echo "</form>\n";
    echo "</td>\n</tr>\n";
// ************************************************************************************** -->
    echo "</table>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>