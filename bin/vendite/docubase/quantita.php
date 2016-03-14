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
require "../../librerie/motore_doc_pdo.php";



//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{

//recupero le sessioni:
//recupero le session

    $_codutente = $_SESSION['utente'];
    $dati = $_SESSION['datiutente'];
    $_tdoc = $_SESSION['tdoc'];
    $id = session_id();
    $_anno = $_SESSION['anno'];
    $_ndoc = $_SESSION['ndoc'];
    $_calce = $_SESSION['calce'];
    
    if ($_calce == "calce2")
    {
        $_suffix = $_SESSION['suffix'];
        //echo "il suffisso è $_suffix";
    }

// prendo la fiunzione della muovimentaizone
    @$_calce = $_SESSION['calce'];

// veirfico che tipo di inserimento voglio se manuale o automatico
    @$_azione = $_POST['azione'];

//vedo se c'è bisogno di una riga vuota.

    @$_cosa = $_GET['cosa'];
    if(($_POST['codice'] == "vuota") OR ($_POST['codice'] == "VUOTA"))
    {
        $_cosa = "vuota";
    }

    if(($_POST['codice'] == "ordine") OR ($_POST['codice'] == "ORDINE"))
    {
        $_cosa = "vuota";
        $_descrizione = "A seguito Vostro Ordine";
    }
    
    
    echo "<html><body>";
//VISULIZZO LA PAGINA:
// ricerco ilcliente...

    intesta_html($_tdoc, "", $dati, "");

    $_listinocli = $dati['listino'];
    @$_scontocli = $dati['scontocli'];
    @$_scontocli2 = $dati['scontocli2'];
    @$_scontocli3 = $dati['scontocli3'];
    $_ivacli = $dati['iva'];

#echo $_ivacli;

    if ($_azione == "vai")
    {

        $_codice = $_POST['articolo'];

        // ok per ogni articolo mi prendo tutti i dati dell'articolo
        // echo $_codice;
        foreach ($_codice as $_value)
        {

            $dati2 = tabella_articoli("singola", $_value, $_parametri);

// mik prendo i dati dell'articolo
            $_esco = $dati2['esco'];
            $_articolo = $dati2['articolo'];
            $_descrizione = $dati2['descrizione'];
            $_unita = $dati2['unita'];
            $_ivart = $dati2['iva'];
            $_catmer = $dati2['catmer'];
            $_img = $dati2['immagine'];
            $_pesoart = $dati2['pesoart'];
            $_provvart = $dati2['provvart'];
            $_fornitore = $dati2['fornitore'];
            $_fornitore2 = $dati2['fornitore2'];
            $_fornitore_3 = $dati2['fornitore_3'];


            #qui divido qui acquisti dalle vendite..

            if (($_tdoc == "ddtacq") OR ( $_tdoc == "fornitore"))
            {
                if ($_codutente == $_fornitore)
                {
                    $_artfor = $dati2['artfor'];
                    $_listino = $dati2['prelisacq'];
                    $_nettoa = $dati2['preacqnetto'];
                    $_sca = $dati2['scaa'];
                    $_scb = $dati2['scab'];
                    $_scc = $dati2['scac'];
                    $_netto = $dati2['preacqnetto'];
                    $_qtaminord = $dati2['qtaminord'];
                }

                if ($_codutente == $_fornitore2)
                {
                    $_artfor = $dati2['artfor2'];
                    $_listino = $dati2['prelisacq_2'];
                    $_nettoa = $dati2['preacqnetto2'];
                    $_sca = $dati2['scaa_2'];
                    $_scb = $dati2['scab_2'];
                    $_scc = $dati2['scac_2'];
                    $_netto = $dati2['preacqnetto2'];
                    $_qtaminord = $dati2['qtaminord_2'];
                }

                if ($_codutente == $_fornitore_3)
                {
                    $_artfor = $dati2['artfor_3'];
                    $_listino = $dati2['prelisacq_3'];
                    $_nettoa = $dati2['preacqnetto_3'];
                    $_sca = $dati2['scaa_3'];
                    $_scb = $dati2['scab_3'];
                    $_scc = $dati2['scac_3'];
                    $_netto = $dati2['preacqnetto_3'];
                    $_qtaminord = $dati2['qtaminord_3'];
                }


                $_nettoacq = $_listino;

                if ($_sca != 0)
                {
                    $_nettoacq = ($_listino - number_format(((($_listino * $_sca) / 100)), $dec, '.', ''));
                }
                if ($_scb != 0)
                {
                    $_nettoacq = ($_nettoacq - number_format(((($_nettoacq * $_scb) / 100)), $dec, '.', ''));
                }
                if ($_scc != 0)
                {
                    $_nettoacq = ($_nettoacq - number_format(((($_nettoacq * $_scc) / 100)), $dec, '.', ''));
                }

                if ($_tdoc == "fornitore")
                {
                    $_qta = $_qtaminord;
                }

                $_totriga = $_nettoacq * $_qta;
            }
            else
            {
                if ($_ivacli != "")
                {

                    // CERCO L'ALIQUOTA IVA CORRISPONDENTE AL CODICE CLIENTE
                    $dativa = tabella_aliquota("singola", $_ivacli, $_percorso);
                    $_ivart = $_ivacli;

                    echo "<CENTER><font color=RED>Attenzione IVA diversa dal sistema.<br> Sul documento verr&agrave; sostituito il codice I.V.A. degli articoli <br> con quello abbinato al cliente ( Codice I.V.A. : $dativa[descrizione] ) </font></center>";
                }

                //includo la funzione che mi preleva i prezzi di vendita
                // Da qui prelevo i vari plugins personalizzadi dei clienti..
                #echo $_codutente;
                //includo la funzione che mi preleva i prezzi di vendita
                // Da qui prelevo i vari plugins personalizzadi dei clienti..
                //aggiungiamo il discorso esma;
                $dati['esco'] = $_esco;

                $_prezzi = prezzi_cliente($_cosa, $dati['codice'], $_articolo, $dati['listino'], $dati);

                if ($_prezzi['descrizione'] != "")
                {
                    $_descrizione = $_prezzi['descrizione'];
                }


                $_listino = $_prezzi['listino'];
                $_messaggio = $_prezzi['messaggio'];
                $_sca = $_prezzi['sca'];
                $_scb = $_prezzi['scb'];
                $_scc = $_prezzi['scc'];

                if (file_exists("../../../plugins/altri_campi_clienti.dir/altricampi.inc"))
                {
                    $_cosa_vari = "singola";
                    include("../../../plugins/altri_campi_clienti.dir/altricampi.inc");
                }
                // chiudo else
            }//fine divisione vendite
            //inserisco nel carrello

            $_parametri = "";
            $_parametri['programma'] = $_SESSION['programma'];
            $_parametri['artfor'] = $_artfor;
            $_parametri['descrizione'] = $_descrizione;
            $_parametri['unita'] = $_unita;
            $_parametri['qta'] = $_qta;
            $_parametri['listino'] = $_listino;
            $_parametri['sca'] = $_sca;
            $_parametri['scb'] = $_scb;
            $_parametri['scc'] = $_scc;
            $_parametri['iva'] = $_ivart;


            tabella_doc_basket("inserisci", $id, $_rigo, $_anno, $_suffix, $_ndoc, $_codutente, $_articolo, $_parametri);

            $_parametri = "";
        }// fine for
        //
    
    
    //elenco il carrello:
        mostra_carrello($_SESSION['programma'], $id, $_tdoc, $_calce, $IVAMULTI, $ivasis);

        //annulla doc
        annulla_doc_vendite($_dove, $_tdoc, $_anno, $_suffix, $_ndoc);
    }// fine azione
    elseif ($_cosa == "vuota")
    {
        echo "<table align=\"center\" border=\"0\">";

        if ($_ivacli != "")
        {

            // CERCO L'ALIQUOTA IVA CORRISPONDENTE AL CODICE CLIENTE
            $dativa = tabella_aliquota("singola", $_ivacli, $_percorso);
            $_ivart = $_ivacli;

            echo "<CENTER><font color=RED>Attenzione IVA diversa dal sistema.<br> Sul documento verr&agrave; sostituito il codice I.V.A. degli articoli <br> con quello abbinato al cliente ( Codice I.V.A. : $dativa[descrizione] ) </font></center>";
        }
        else
        {
            //se non c'è una iva diversa l'iva è quella del sistema
            $_ivart = $ivasis;
        }// fine gestione aliquote iva
        //devo specificare dova va.. !
        printf("<form action=\"corpo.php\" method=\"POST\">");

        //faccio apparire la schermata della quantità
        schermata_quantita($_tdoc, $_cosa, $_messaggio, $_rigo, "vuoto", $_artfor, $_descrizione, $_unita, $_qta, $_listino, $_sca, $_scb, $_scc, $_netto, $_pesoart, $_ivart, $_consegna, $_qtaevasa, $_qtaestratta, $_qtasaldo, $_rsaldo, $_agg, $_anno, $_suffix, $_ndoc);

        //inserisco il pulsante..
        //echo "<tr><td colspan=\"9\" align=center>Inserisci >> <input type=\"submit\" name=\"azione\" value=\"inserisci\"></form></td></tr>";
    }
    else
    {
//INIZIO SELEZIONE QUANTITA ARTICOLO.. PARTE NORMALE...
//
//
        // Stringa contenente la query di ricerca...
        if ($_SESSION['programma'] == "VENDITA")
        {
            if (($_POST['codice'] == "") and ( $_POST['codbar'] == ""))
            {
                echo "<center><h2> Nessun Carattere immesso nel campo ricerca </h2>";
                echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A></center>";
                exit;
            }
        }
        else
        {
            if (($_POST['codice'] == "") and ( $_POST['artfor'] == ""))
            {
                echo "<center><h2> Nessun Carattere immesso nel campo ricerca </h2>";
                echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A></center>";
                exit;
            }
        }

        //troviamo il codice articolo..

        if ($_POST['codbar'] != null)
        {
            $_articolo = tabella_barcode("singola", $_POST['codbar'], $_articolo, $_rigo);

            $dati2 = tabella_articoli("singola", $_articolo, $_parametri);
        }
        elseif ($_POST['artfor'] != null)
        {
            $dati2 = tabella_articoli("fornitori", $_POST['artfor'], $_parametri);
        }
        else
        {
            $dati2 = tabella_articoli("singola", $_POST['codice'], $_parametri);
        }


        if ($dati2['risultato'] != "SI")
        {
            echo "<center><tr><td colspan=6 align=center><h2>Nessun articolo Trovato</h2><br>
		<A HREF=\"#\" onClick=\"history.back()\">Riprova</A></td></tr></center>";
            return;
        }

        //dividerei le le schermate per poterle recuperare anche in corpo ecc.
        //devo trovare cosa devo passare..
        //
    // prova incollaggio tutto il programma quantità

        echo "<table align=\"center\" border=\"0\">";

        //echo "<tr>";

        $_esco = $dati2['esco'];
        $_articolo = $dati2['articolo'];
        $_descrizione = $dati2['descrizione'];
        $_unita = $dati2['unita'];
        $_ivart = $dati2['iva'];
        $_catmer = $dati2['catmer'];
        $_img = $dati2['immagine'];
        $_pesoart = $dati2['pesoart'];

        if ($_SESSION['programma'] == "VENDITA")
        {

            if ($_ivacli != "")
            {

                // CERCO L'ALIQUOTA IVA CORRISPONDENTE AL CODICE CLIENTE
                $dativa = tabella_aliquota("singola", $_ivacli, $_percorso);
                $_ivart = $_ivacli;

                echo "<CENTER><font color=RED>Attenzione IVA diversa dal sistema.<br> Sul documento verr&agrave; sostituito il codice I.V.A. degli articoli <br> con quello abbinato al cliente ( Codice I.V.A. : $dativa[descrizione] ) </font></center>";
            }


            //includo la funzione che mi preleva i prezzi di vendita
            // Da qui prelevo i vari plugins personalizzadi dei clienti..
            //aggiungiamo il discorso esma;
            $dati['esma'] = $_esco;
            $_prezzi = prezzi_cliente($_cosa, $dati['codice'], $_articolo, $dati['listino'], $dati);

            if ($_prezzi['descrizione'] != "")
            {
                $_descrizione = $_prezzi['descrizione'];
            }


            $_listino = $_prezzi['listino'];
            $_messaggio = $_prezzi['messaggio'];
            $_sca = $_prezzi['sca'];
            $_scb = $_prezzi['scb'];
            $_scc = $_prezzi['scc'];

            if (file_exists("../../../plugins/altri_campi_clienti.dir/altricampi.inc"))
            {
                $_cosa_vari = "singola";
                include("../../../plugins/altri_campi_clienti.dir/altricampi.inc");
            }
        }
        else
        {

            $_fornitore = $dati2['fornitore'];
            $_fornitore2 = $dati2['fornitore2'];
            $_fornitore_3 = $dati2['fornitore_3'];

            if ($_codutente == $_fornitore)
            {
                $_artfor = $dati2['artfor'];
                $_listino = $dati2['prelisacq'];
                $_sca = $dati2['scaa'];
                $_scb = $dati2['scab'];
                $_scc = $dati2['scac'];
                $_netto = $dati2['preacqnetto'];
            }

            if ($_codutente == $_fornitore2)
            {
                $_artfor = $dati2['artfor2'];
                $_listino = $dati2['prelisacq_2'];
                $_nettoa = $dati2['preacqnetto2'];
                $_sca = $dati2['scaa_2'];
                $_scb = $dati2['scab_2'];
                $_scc = $dati2['scac_2'];
                $_netto = $dati2['preacqnetto2'];
            }

            if ($_codutente == $_fornitore_3)
            {
                $_artfor = $dati2['artfor_3'];
                $_listino = $dati2['prelisacq_3'];
                $_nettoa = $dati2['preacqnetto_3'];
                $_sca = $dati2['scaa_3'];
                $_scb = $dati2['scab_3'];
                $_scc = $dati2['scac_3'];
                $_netto = $dati2['preacqnetto_3'];
            }

            $_qta = $dati2['qtaminord'];

            $_descrizione = $dati2['descrizione'];
            $_unita = $dati2['unita'];
        }

        //inizio parte visiva..
        //devo specificare dova va.. !
        //printf("<form action=\"corpo.php\" method=\"POST\">");
        //faccio apparire la schermata della quantità

        schermata_quantita($_tdoc, $_cosa, $_messaggio, $_rigo, $_articolo, $_artfor, $_descrizione, $_unita, $_qta, $_listino, $_sca, $_scb, $_scc, $_netto, $_pesoart, $_ivart, $_consegna, $_qtaevasa, $_qtaestratta, $_qtasaldo, $_rsaldo, $_agg, $_anno, $_suffix, $_ndoc);



        //verifico un eventuale articolo correlato
        // se c'e appare subito

        if ($_tdoc == "fornitore")
        {
            impegno_articolo($_cosa, $_articolo);
        }
        else
        {
            if ((($dati2['artcorr'] != "") OR ($dati2['artcorr_2'] != "") OR ($dati2['artcorr_3'] != "")) AND ( $_tdoc != "ddtacq"))
            {
                if($dati2[artcorr] != "")
                {
                    $dati_corr = tabella_articoli("singola", $dati2['artcorr'], $_parametri);
                    echo "<tr><td colspan=\"7\" align=\"left\">Trovato Articolo correlato:<br>";
                    echo "<font size=\"2\" face=\"arial\">$dati_corr[articolo] $dati_corr[descrizione]</font></td>\n";
                    echo "<td align=\"CENTER\" colspan=\"2\"><font size=\"2\" face=\"arial\">Aggancia =></font> <input type=\"checkbox\" name=\"aggancia\" value=\"$dati_corr[articolo]\"></td></tr>";
                }
                
                if($dati2[artcorr_2] != "")
                {
                    $dati_corr = tabella_articoli("singola", $dati2['artcorr_2'], $_parametri);
                    echo "<tr><td colspan=\"7\" align=\"left\">Trovato Articolo correlato:<br>";
                    echo "<font size=\"2\" face=\"arial\">$dati_corr[articolo] $dati_corr[descrizione]</font></td>\n";
                    echo "<td align=\"CENTER\" colspan=\"2\"><font size=\"2\" face=\"arial\">Aggancia => </font><input type=\"checkbox\" name=\"aggancia_2\" value=\"$dati_corr[articolo]\"></td></tr>";
                }
                
                if($dati2[artcorr_3] != "")
                {
                    $dati_corr = tabella_articoli("singola", $dati2['artcorr_3'], $_parametri);
                    echo "<tr><td colspan=\"7\" align=\"left\">Trovato Articolo correlato:<br>";
                    echo "<font size=\"2\" face=\"arial\">$dati_corr[articolo] $dati_corr[descrizione]</font></td>\n";
                    echo "<td align=\"CENTER\" colspan=\"2\"><font size=\"2\" face=\"arial\">Aggancia => </font><input type=\"checkbox\" name=\"aggancia_3\" value=\"$dati_corr[articolo]\"></td></tr>";
                }

                
                
                if ($_cosa == "modifica")
                {
                    echo "<tr><td align=center colspan=\"8\"><br><input type=\"submit\" name=\"azione\" value=\"aggiorna\"></td></tr>";
                }
                else
                {
                    //indichiamo l'azione..
                    echo "<tr><td align=center colspan=\"8\"><br><input type=\"submit\" name=\"azione\" value=\"inserisci\"></td></tr>";
                }
            }
        }

        echo "</form></td></tr>\n";

        // inizio variabili ambiantali..
        $_anno = date('Y');

        //$query = "select sum(qtacarico) AS qtacarico, sum(qtascarico) AS qtascarico from magazzino where articolo='$_articolo'";
        $query = "select sum(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq, sum(qtascarico) AS qtascarico from magazzino where articolo='$dati2[articolo]'";

        $dati_mag = domanda_db("query", $query, $_cosa, "fetch", $_parametri);
        
        $_qtacarico = $dati_mag['qtacarico'];
        $_qtascarico = $dati_mag['qtascarico'];
        $_valoreacq = $dati_mag['valoreacq'];
        $_giacenza = ($_qtacarico - $_qtascarico);
        
        if(($_valoreacq != "") AND ($_qtacarico != ""))
        {
            @$_mediaacq = $_valoreacq / $_qtacarico;
        }
        else
        {
            $_mediaacq = "0.00";
        }
        


        $_ultimavend = tabella_magazzino("ultima_vendita", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_tut, $_rigo, $_utente, $_articolo, $dati['codice']);



        // fine calcolo giacenza
        echo "<tr><td colspan=\"10\"><hr></td></tr>\n";
        echo "<tr><td colspan=\"2\" align=\"center\"><span class=\"testo_blu\">Giacenza articolo in magazzino ==>&nbsp;</span> <b>$_giacenza</b></td>";
        echo "<td colspan=\"3\" align=\"CENTER\"><span class=\"testo_blu\">  Valore medio acquisto ==>&nbsp;</span> <b>$_mediaacq</b></td>";
        echo "<td colspan=\"3\" align=\"center\"><span class=\"testo_blu\">  Ultimo Vendita articolo ==>&nbsp;</span><b>$_ultimavend</b></td></tr>";


        annulla_doc_vendite($_dove, $_tdoc, $_anno, $_suffix, $_ndoc);
    }
    echo "</table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>