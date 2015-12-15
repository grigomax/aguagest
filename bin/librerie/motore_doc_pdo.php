<?php

/*
 * Agua_gest programma gestionale by Grigolin Massimo
 * aguagest.sourceforge.net - massimo@mcetechnik.it
 * Programma distribuito secondo licenza GNU GPL
 * 
 * Nuovo Motore documenti..
 * In questo file vengono caricaricate tutte le funzioni che hanno a che fare con gli archivi delle ventite
 * come le fatture, le conferme ecc..
 * 
 * Ogni funzione si chiamera tabella_nome archivio..
 * Secondo me conviene agire e fare delle funzioni una per ogni archivio
 * quindi delete, inser, search ecc.. in modo che quando si mettono le mani le mett in un posto solo..
 * 
 */

//fine funzioni di annulla documento
#Inizio funzioni riguardanti l'invio per la stampa dei documenti'
function genera_maschera_stampe($file_stampa, $maschera, $_documento)
{
    if ($maschera == "visualizza")
    {
        echo "<center>\n";
        echo "<form action=\"$file_stampa\" target=\"sotto\" method=\"GET\">\n";
        echo "<table border=\"0\">\n";
        //facciamo apparire le variabili a video
        echo "<tr><td colspan=\"4\" align=\"center\"><span class=\"azioni\"><input type=\"radio\" name=\"tdoc\" value=\"$_documento[tdoc]\" checked >$_documento[tdoc]\n";
        echo "<input type=\"radio\" name=\"ndoc\" value=\"$_documento[ndoc]\" checked> N. $_documento[ndoc] \n";
        echo "<input type=\"radio\" name=\"anno\" value=\"$_documento[anno]\" checked>Anno = $_documento[anno] \n";
        echo "<input type=\"radio\" name=\"suffix\" value=\"$_documento[suffix]\" checked>suf = $_documento[suffix] </td><tr>\n";
    }
    elseif ($maschera == "pulsanti")
    {
        echo "<td align=\"center\"><span class=\"azioni\"><input type=\"submit\" name=\"azione\" value=\"Stampa\"> &nbsp;<input type=\"submit\" name=\"azione\" value=\"Inoltra\">";
        echo "</td></tr></form></table>";
    }
    else
    {
        echo "<tr><td>\n";
        echo "<center><span class=\"azioni\"><b>Se non appaiono errori a video.. <br></center>";
        echo "<center><span class=\"azioni\"><b>Documento Inserito perfettamente</b><br>Si vuol procedere alla stampa del documento?</center>";

        echo "<center><table align=\"center\">";
        echo "<form action=\"$file_stampa\" target=\"sotto\" method=\"GET\">\n";

        #Variamo le varibili documento
        if (($_documento['tdoc'] != "ddt") AND ( $_documento['tdoc'] != "ddt_diretto") AND ( $_documento['tdoc'] != "ordine") AND ( $_documento['tdoc'] != "preventivo") AND ( $_documento['tdoc'] != "conferma") AND ( $_documento['tdoc'] != "fornitore"))
        {
            $_tdoc = "FATTURA";
        }
        else
        {
            $_tdoc = $_documento['tdoc'];
        }

        //facciamo apparire le variabili a video
        echo "<tr><td colspan=\"4\" align=\"center\"><span class=\"azioni\"><input type=\"radio\" name=\"tdoc\" value=\"$_tdoc\" checked >$_documento[tdoc] <input type=\"radio\" name=\"ndoc\" value=\"$_documento[ndoc]\" checked> N. $_documento[ndoc] \n";
        echo "<input type=\"radio\" name=\"anno\" value=\"$_documento[anno]\" checked>Anno = $_documento[anno] / <input type=\"radio\" name=\"suffix\" value=\"$_documento[suffix]\" checked>suffix = $_documento[suffix]</td><tr>\n";
    }
}

function print_prezzi($_tdoc)
{
#Questa sezione mi permette di cercare nel file vars_aspetto quello che va stampato oppure no..
//    includo il files del documento

    global $conn;
    global $_percorso;

    $datidoc = tabella_stampe_layout("singola", $_percorso, $_tdoc);



    if ($datidoc['ST_PREZZI'] == "SI")
    {
        echo "<tr><td align=center colspan=2><span class=\"azioni\"><input type=\"radio\" name=\"prezzi\" value=\"si\" checked> Prezzi in bolla &nbsp;";
        echo "<input type=\"radio\" name=\"prezzi\" value=\"no\" >Senza Prezzi &nbsp;";
    }
    else
    {
        echo "<tr><td align=center colspan=2><span class=\"azioni\"><input type=\"radio\" name=\"prezzi\" value=\"si\"> Prezzi in bolla &nbsp;";
        echo "<input type=\"radio\" name=\"prezzi\" value=\"no\" checked>Senza Prezzi &nbsp;";
    }

    if ($datidoc['ST_DATA'] == "SI")
    {
        echo "<input type=radio name=dataora value=si checked > Con data e ora &nbsp;";
        echo "<input type=radio name=dataora value=no > Senza data ora";
    }
    else
    {
        echo "<input type=radio name=dataora value=si > Con data e ora &nbsp;";
        echo "<input type=radio name=dataora value=no checked> Senza data ora";
    }

    echo "<br>";

    if ($datidoc['ST_LOGOP'] == "NO")
    {
        echo "<input type=radio name=\"intesta\" value=si > Con Intestazione &nbsp;";
        echo "<input type=radio name=\"intesta\" value=no checked> Senza Intestazione";
    }
    else
    {
        echo "<input type=radio name=\"intesta\" value=si checked > Con Intestazione &nbsp;";
        echo "<input type=radio name=\"intesta\" value=no > Senza Intestazione";
    }

    echo "</td></tr>";
}

//fine file prezzi;

function seleziona_lingue()
{
    echo "<tr><td colspan=\"4\" align=\"center\"><span class=\"azioni\"><input type=\"radio\" name=\"lingua\" value=\"IT\" checked >Italiano <input type=\"radio\" name=\"lingua\" value=\"EN\">Inglese \n";
    echo "<input type=\"radio\" name=\"lingua\" value=\"ES\">Spagnolo </td><tr>\n";
}

//funzione annulla documento
function annulla_doc_vendite($_dove, $_tdoc, $_anno, $_ndoc)
{

    echo "<table border=\"0\" width=\"95%\" align=\"center\">\n";

    if (($_dove == "modifica") OR ( $_SESSION['calce'] == "calce2"))
    {
        printf("<form action=\"annulladoc.php?tdoc=$_tdoc&anno=$_anno&ndoc=$_ndoc\" method=\"POST\">");
    }
    elseif (($_dove == "parziale_vendita") OR ( $_dove == "parziale_acquisto") OR ( $_dove == "parziale"))
    {
        printf("<form action=\"annulladoc.php?cosa=parziale&tdoc=$_tdoc&anno=$_anno\" method=\"POST\">");
    }
    else
    {
        echo "<form action=\"annulladoc.php\" method=\"POST\">\n";
    }

    echo "<tr><td colspan=\"9\" align=\"center\" class=\"tabella_elenco\"><br><span font-size=\"0.8em\">Per annullare le operazioni in corso e tornare al men&ugrave; ==>>  <input type=\"submit\" name=\"azione\" value=\"annulla\"></form></td>\n";
    echo "</tr>\n";
    echo "</form></table>\n";
}

//funzione che mi restituisce il nome degli archivio in base al documento..
//mi passa un arrey con nome, archivioo testa, e dettaglio.
function archivio_tdoc($_tdoc)
{
    if ($_tdoc == "ddt")
    {
        $_testacalce = "bv_bolle";
        $_dettaglio = "bv_dettaglio";
        $_file = "bv_mce";
        $_utente = "clienti";
    }
    elseif ($_tdoc == "ddt_diretto")
    {
        $_testacalce = "bvfor_testacalce";
        $_dettaglio = "bvfor_dettaglio";
        $_file = "bvfor";
        $_utente = "clienti";
    }
    elseif ($_tdoc == "conferma")
    {
        $_testacalce = "co_testacalce";
        $_dettaglio = "co_dettaglio";
        $_file = "co_mce";
        $_utente = "clienti";
    }
    elseif ($_tdoc == "ordine")
    {
        $_testacalce = "oc_testacalce";
        $_dettaglio = "oc_dettaglio";
        $_file = "oc_mce";
        $_utente = "clienti";
    }
    elseif ($_tdoc == "preventivo")
    {
        $_testacalce = "pv_testacalce";
        $_dettaglio = "pv_dettaglio";
        $_file = "pv_mce";
        $_utente = "clienti";
    }
    elseif ($_tdoc == "fornitore")
    {
        $_testacalce = "of_testacalce";
        $_dettaglio = "of_dettaglio";
        $_file = "of_mce";
        $_utente = "fornitori";
    }
    elseif ($_tdoc == "ddtacq")
    {
        $_testacalce = "magazzino";
        $_dettaglio = "magazzino";
    }
    else
    {
        $_testacalce = "fv_testacalce";
        $_dettaglio = "fv_dettaglio";
        $_file = "fv_mce";
        $_utente = "clienti";
    }


    //restitusco un arrey con il nome archivioo ed il nome

    $_archivio['testacalce'] = $_testacalce;
    $_archivio['dettaglio'] = $_dettaglio;
    $_archivio['file'] = $_file;
    $_archivio['utente'] = $_utente;

    return $_archivio;
}

//------------------------------------------------------------
//funzione che controlla se l'utente è bloccato
function blocco_utente($dati)
{
    if ($dati['bloccocli'] == "SI")
    {
        // se risulta bloccato chiudo tutto
        echo "<tr>";
        echo "<td width=\"100\" height=\"1\" align=\"center\" class=\"tabella\"></td>";
        echo "<tr><td align=\"center\">Il seguente cliente Risulta Bloccato.</span></td></tr>";
        echo "<tr><td align=\"center\"><font color=RED > Si prega di aggiornare l'anagrafica cliente - IMPOSSIBILE Emettere qualsiasi Documento di vendita</font></td></tr>\n";

        echo "<form action=\"annulladoc.php\" method=\"POST\">";
        echo "<tr><td align=\"center\"><br>Per annullare l'operazione  <input type=\"submit\" name=\"azione\" value=\"annulla\"></form></td>";
        echo "</tr>";
        echo "</table>";
        exit;
    }
}

//---------------------------------------------------------
//funzione causale vendita:
function causale_trasporto($_causale)
{
    if ($_causale != "")
    {
        $_option = $_causale;
    }
    else
    {
        $_option = "VENDITA";
    }
    echo "<td align=\"left\" valign=\"top\">";
    echo "<span class=\"tabella_elenco\"><b>Causale del trasporto </b>&nbsp;<br></span>";
    echo "<select name=\"causale\">\n";
    printf("<option value=\"%s\">%s</option>", $_option, $_option);
    echo "<option value=\"CONTO DEPOSITO\">CONTO DEPOSITO</option>";
    echo "<option value=\"CONTO LAVORO\">CONTO LAVORO</option>";
    echo "<option value=\"CONTO MANUTENZ. ORD.\">CONTO MANUTENZIONE ORDINARIA</option>";
    echo "<option value=\"CONTO NOLEGGIO\">CONTO NOLEGGIO</option>";
    echo "<option value=\"CONTO VISIONE\">CONTO VISIONE</option>";
    echo "<option value=\"OMAGGIO\">OMAGGIO</option>";
    echo "<option value=\"RESO\">RESO</option>";
    echo "<option value=\"RESO SOST. GARANZIA\">RESO SOSTITUZIONE IN GARANZIA</option>";
    echo "<option value=\"RESO DA NOLEGGIO\">RESO DA NOLEGGIO</option>";
    echo "<option value=\"RESO DA C.TO LAVORO\">RESO DA CONTO LAVORO</option>";
    echo "<option value=\"RESO PER ACCREDITO\">RESO PER ACCREDITO</option>";
    echo "<option value=\"RIPARAZIONE\">RIPARAZIONE</option>";
    echo "<option value=\"SOSTIT. IN GARANZIA\">SOSTITUZIONE IN GARANZIA</option>";
    echo "<option value=\"TRASPORTO IN SEDE\">TRASPORTO IN SEDE</option>";
    echo "<option value=\"USO COMODATO\">USO COMODATO</option>";
    echo "<option value=\"USO COMOD. GRATUITO\">USO COMOD. GRATUITO</option>";
    echo "<option value=\"VENDITA\">VENDITA</option>";
    echo "</td>";
}

//------------------------------------------

/**
 * funzione che mi inserisce alla fine del corpo doc una riga con il totale della fattura.
 */
function cottimo($_programma, $id, $dati)
{
    global $conn;
    global $dec;
    global $_percorso;

    //facciamo la somma del carrello, poi eliminiamo i numeri e poi scrivimo una riga descrittiva

    $query = "SELECT anno, ndoc, SUM(quantita) AS quantita, SUM(listino) AS listino, SUM(nettovendita) AS nettovendita, SUM(totriga) AS totriga, iva, SUM(totrigaprovv) AS totrigaprovv, SUM(peso) AS peso from doc_basket where sessionid='$id'";

    //echo $query;
    // eseguiamo..
    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "motore_doc_pdo.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
        $_errori['errori'] = "NO";
    }

    foreach ($result AS $carrello)
        ;


    //eliminiamo dal carrello tutti i dati relativi al corpo..

    $query = "UPDATE doc_basket SET unita='', quantita='', listino='', sca='', scb='', scc='', nettovendita='', totriga='', iva='', totrigaprovv='', peso='' where sessionid='$id'";
    //eseguiamo..
    $result = $conn->exec($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "motore_doc_pdo.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
        $_errori['errori'] = "NO";
    }


    //ora inseriamo una riga descrittiva con la somma dei documenti..
    $query = "INSERT INTO doc_basket( sessionid, anno, ndoc, utente, articolo, descrizione, unita, quantita, listino, nettovendita, totriga, iva, totrigaprovv, peso) VALUES ('$id', '$carrello[anno]', '$carrello[ndoc]', '$dati[codice]', 'vuoto', 'TOTALE NOSTRA FORNITURA..',
     'NR', '1', '$carrello[totriga]', '$carrello[totriga]', '$carrello[totriga]', '$carrello[iva]', '$carrello[totrigaprovv]', '$carrello[peso]' )";

    $result = $conn->exec($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "motore_doc_pdo.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
        $_errori['errori'] = "NO";
    }
    #echo $query;
    //ecco se non ci sono errori... 
}

//--------------------------------------------------------
//controllo documenti inevasi:
function documenti_inevasi($_codutente, $_tdoc)
{
    global $conn;

    if ($_SESSION['programma'] == "VENDITA")
    {
        // verifico se il cliente ha conferme d'ordine inevase
        // avviso su monitor se ci sono e quali
        if ($_tdoc == "conferma")
        {
            $query = sprintf("(select * from oc_testacalce where utente=\"%s\" and status != 'evaso' ORDER BY ndoc) order by datareg DESC limit 10", $_codutente);
            $_documenti = "preventivi";
        }
        else
        {
            $query = sprintf("(select * from oc_testacalce where utente=\"%s\" and status != 'evaso' ORDER BY ndoc) UNION (select * from co_testacalce where utente=\"%s\" and status != 'evaso' ORDER BY ndoc)", $_codutente, $_codutente);
            $_documenti = "Conferme Ordine";
        }
    }
    else
    {
        $query = "select * from of_testacalce where utente='$_codutente' and status != 'evaso' ORDER BY ndoc limit 10";
    }

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "motore_doc_pdo.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
        $_errori['errori'] = "NO";
    }

    if ($result->rowCount() > 0)
    {
        $righe = "1";
        echo "<tr><td colspan=\"5\" align=\"center\" class=\"tabella\"><font size=\"3\">Il seguente cliente ha Questi $_documenti inevasi.</font></td></tr>";
        echo "<tr><td colspan=\"4\" align=\"center\"><font color=RED >Continuare ? <a href=\"annulladoc.php\"> NO </a>  - <a href=\"seleziona.php\"> SI</a></font></td></tr>";
        echo "<tr><td>anno</td><td>Data Reg.</td><td>ndoc</td><td>valore</td><td>status</td></tr>";

        foreach ($result AS $datico)
        {
            printf(" <tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $datico['anno'], $datico['datareg'], $datico['ndoc'], $datico['totdoc'], $datico['status']);
        }

        echo "<tr><td colspan=\"4\" align=\"center\"><font color=RED >Continuare ? <a href=\"annulladoc.php\"> NO </a>  - <a href=\"seleziona.php\"> SI</a></font></td></tr>";
        //echo "</table>";


        echo "</tr>";
        echo "</table>";
    }

    return $righe;
}

//--------------------------------------------------------------
/**
 * Funzione che mi chiude tutte le sessioni..
 * Elimina le sessioni ma non quelle di lavoro..
 */
function chiudi_sessioni()
{


    unset($_SESSION['utente']);
    unset($_SESSION['anno']);
    unset($_SESSION['annodoc']);
    unset($_SESSION['ndoc']);
    unset($_SESSION['tdoc']);
    unset($_SESSION['importi']);
    unset($_SESSION['totiva']);
    unset($_SESSION['rev']);
    unset($_SESSION['calce']);
    unset($_SESSION['pesotot']);
    unset($_SESSION['totprovv']);
    unset($_SESSION['programma']);
    unset($_SESSION['castiva']);
    unset($_SESSION['datiutente']);
    //elimino tutte le sessioniche mi hanno portato qui..
    unset($_SESSION['datidoc']['start']);
    unset($_SESSION['datidoc']['end']);
    unset($_SESSION['datidoc']['annodaimp']);
    unset($_SESSION['datidoc']['numero']);
    unset($_SESSION['datidoc']['ndoc']);
    unset($_SESSION['datidoc']['anno']);
    unset($_SESSION['datidoc']['codutente']);
    unset($_SESSION['datidoc']['daydoc']);
    unset($_SESSION['datidoc']['mesedoc']);
    unset($_SESSION['datidoc']['annodoc']);
    unset($_SESSION['datidoc']['speseb']);
    unset($_SESSION['programma']);
    #devo aprirmi una sessione con il nome dell'utente'#
    unset($_SESSION['utente']);
    #e mi apro una sessione anche con il numero dai documenti da evadere
    unset($_SESSION['datidoc']['numero']);
    #e mi apro una sessione con i dati del codutente
    unset($_SESSION['datiutente']);
    unset($_SESSION['status']);
    unset($_SESSION['suffix']);
}

//---------------------------------------
//funzione che mi crea l'intestazione dei documenti all'interno delle pagine html.
function intesta_html($_tdoc, $_tipo, $dati, $dati_doc)
{
    if ($_tipo == "calce")
    {
        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"><tr>";
        echo "<td width=\"35%\" align=\"left\" valign=\"top\"><span class=\"intestazione\"><b>Spettabile</b></span></td>";
        echo "<td colspan=\"2\" align=\"left\" valign=\"top\"><span class=\"intestazione\"><b>Destinazione Merce</b></span></td></tr><tr>";
        printf("<td align=\"left\"><span class=\"seconda_intestazione\"><b>%s<br>", $dati['ragsoc']);
        printf("%s<br>", $dati['ragsoc2']);
        printf("%s<br>", $dati['indirizzo']);
        printf("%s %s %s<br>", $dati['cap'], $dati['citta'], $dati['provincia']);
        echo "</b></span></td>";
        printf("<td align=left>Ragione Sociale : <input type=\"text\" name=\"dragsoc\" value=\"%s\" size=\"42\" maxlength=\"40\"><br>", $dati['dragsoc']);
        printf("Ragione sociale 2 : <input type=\"text\" name=\"dragsoc2\" value=\"%s\" size=\"41\" maxlength=\"50\"><br>", $dati['dragsoc2']);
        printf("Indirizzo : <input type=\"text\" name=\"dindirizzo\" value=\"%s\" size=\"49\" maxlength=\"50\"><br>", $dati['dindirizzo']);
        printf("Cap :<input type=\"text\" name=\"dcap\" value=\"%s\" size=\"6\" maxlength=\"5\">", $dati['dcap']);
        printf("Citt&agrave;:<input type=\"text\" name=\"dcitta\" value=\"%s\" size=\"30\" maxlength=\"50\">", $dati['dcitta']);
        printf("Prov. :<input type=\"text\" name=\"dprov\" value=\"%s\" size=\"3\" maxlength=\"2\"></td>", $dati['dprov']);
        echo "</tr><tr>";
        echo "<tr><td>&nbsp;</td><td>Destinazione -> :\n";
        
        tabella_destinazioni("elenca_calce", $dati['codice'], $_codice, $_parametri);
        
        echo "</td></tr>\n";
        echo "<td width=\"100\" align=\"center\" class=\"tabella\"></td>";
        echo "</tr></table>";
    }
    elseif ($_tipo == "calce2")
    {
        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"><tr>";
        echo "<td width=\"35%\" align=\"left\" valign=\"top\"><span class=\"intestazione\"><b>Spettabile</b></span></td>";
        echo "<td colspan=\"2\" align=\"left\" valign=\"top\"><span class=\"intestazione\"><b>Destinazione Merce</b></span></td></tr><tr>";
        printf("<td align=\"left\"><span class=\"seconda_intestazione\"><b>%s<br>", $dati['ragsoc']);
        printf("%s<br>", $dati['ragsoc2']);
        printf("%s<br>", $dati['indirizzo']);
        printf("%s %s %s<br>", $dati['cap'], $dati['citta'], $dati['provincia']);
        echo "</b></span></td>";
        printf("<td align=left>Ragione Sociale : <input type=\"text\" name=\"dragsoc\" value=\"%s\" size=\"42\" maxlength=\"40\"><br>", $dati_doc['dragsoc']);
        printf("Ragione sociale 2 : <input type=\"text\" name=\"dragsoc2\" value=\"%s\" size=\"41\" maxlength=\"50\"><br>", $dati_doc['dragsoc2']);
        printf("Indirizzo : <input type=\"text\" name=\"dindirizzo\" value=\"%s\" size=\"49\" maxlength=\"50\"><br>", $dati_doc['dindirizzo']);
        printf("Cap :<input type=\"text\" name=\"dcap\" value=\"%s\" size=\"6\" maxlength=\"5\">", $dati_doc['dcap']);
        printf("Citt&agrave;:<input type=\"text\" name=\"dcitta\" value=\"%s\" size=\"30\" maxlength=\"50\">", $dati_doc['dcitta']);
        printf("Prov. :<input type=\"text\" name=\"dprov\" value=\"%s\" size=\"3\" maxlength=\"2\"></td>", $dati_doc['dprov']);
        echo "</tr><tr>";
        echo "<tr><td>&nbsp;</td><td>Destinazione -> :\n";
        
        tabella_destinazioni("elenca_select", $dati['codice'], $_codice, "destinazione");
        
        echo "</td></tr>\n";
        echo "<td width=\"100\" align=\"center\" class=\"tabella\"></td>";
        echo "</tr></table>";
    }
    else
    {
        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">";
        echo "<tr><td align=\"left\" valign=\"top\">";
        echo "<span class=\"intestazione\">Gestione $_tdoc</span><br></td></tr>";
        echo "<tr>";
        echo "<td align=\"left\"><span class=\"seconda_intestazione\">$dati[ragsoc]<br>";
        echo "$dati[indirizzo]<br>\n";
        echo "$dati[cap] $dati[citta] ($dati[prov])<br>\n";
        echo "</span></td></tr>";
    }
}

/** graffa di fine funzione
 *  funione che mi permette di eliminare un documeto ed evetualmente ripristinare quello evaso..
 *
 * @global $conn $conn
 * @global  $nomedoc
 * @param <type> $_risultato
 * @param <type> $_tdoc
 * @param <type> $_anno
 * @param <type> $_ndoc
 * @param <type> $_archivio
 * @return <type>
 */
function elimina_documento($_risultato, $_tdoc, $_anno, $_ndoc, $_archivio)
{
    global $conn;
    global $nomedoc;
    global $_percorso;


    //per poter correttamente eliminare il documento e ripristinare le conferme d'ordine in modo che esse
    //siano ancora gestibili bisogna procedere prima a ripristinare le giacenze e poi eliminare i documenti
    // in terzo ripristinare lo status..
    #echo $_tdoc;
    //controllo status documento.
    //la funzione restituisce prosegui o aspetta
    //$_risultato = status_documento("cancella", $_archivio, $_tdoc, $_anno, $_ndoc, "modificadoc.php", $_azione);


    if ($_risultato == "prosegui")
    {

        //leggiamo il corpo documenti per leggermi le righe il segreto è quello di gestire le righe del corpo e il numero
        // dei documenti sempre in ordine crescente del numero doc e rigo..


        if (($_tdoc == "ddt") OR ( $_tdoc == "ddt_diretto") OR ( $_tdoc == $nomedoc))
        {

            //ripristinare le uscite..
            //leggiamo il corpo del documento


            $query = sprintf("SELECT * from %s where anno=\"%s\" and ndoc=\"%s\" ORDER BY rigo", $_archivio['dettaglio'], $_anno, $_ndoc);

            $result = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore_doc_pdo.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_errori['errori'] = "NO";
            }
            else
            {
                $_rigostart = "0";
                foreach ($result AS $datistart)
                {
                    //all'interno del ciclo andiamo a prenderci il numero della conferma ed anche l'anno..

                    if ($datistart['articolo'] == "vuoto")
                    {
                        //verifichiamo se esiste la parola conferma

                        if (strstr($datistart['descrizione'], "NS. conferma n."))
                        {
                            //se si vuol dire che esiste un qualcosa..
                            //vedimo quanto lunga è
                            $datistart['descrizione'] = rtrim($datistart['descrizione']);
                            //se si vuol dire che esiste un qualcosa..
                            //vedimo quanto lunga è
                            $stringa = explode(' ', $datistart['descrizione']);
                            $_ndoc_start = $stringa['3'];
                            $_anno_start = substr($stringa['5'], -4, 4);
                        }
                    }
                    else
                    {

                        if ($_rigostart == "0")
                        {
                            //leggiamo e poi scriviamo..
                            $query = "SELECT * from co_dettaglio WHERE anno='$_anno_start' and ndoc='$_ndoc_start' AND articolo='$datistart[articolo]' ORDER BY rigo LIMIT 1";
                        }
                        else
                        {
                            //leggiamo e poi scriviamo..
                            $query = "SELECT * from co_dettaglio WHERE anno='$_anno_start' and ndoc='$_ndoc_start' AND articolo='$datistart[articolo]' AND rigo > '$rigo' ORDER BY rigo LIMIT 1";
                        }

                        $result2 = $conn->query($query);

                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
                            echo $_errore['2'];
                            //aggiungiamo la gestione scitta dell'errore..
                            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                            $_errori['files'] = "motore_doc_pdo.php";
                            scrittura_errori($_cosa, $_percorso, $_errori);
                            $_errori['errori'] = "NO";
                        }

                        //Facciamo i conti e poi aggiorniamo la stessa riga..

                        foreach ($result2 AS $datiriga)
                            ;

                        $rigo = $datiriga['rigo'];

                        //adesso dobbiamo andare a cerca il corpo della conferma e ripristinare le quantità:

                        $query = "UPDATE co_dettaglio SET qtaevasa = qtaevasa - $datistart[quantita], qtaestratta=$datistart[quantita], qtasaldo=qtasaldo+$datistart[quantita], totriga=totriga+$datistart[totriga], totrigaprovv=totrigaprovv+$datistart[totrigaprovv], rsaldo='NO' where anno='$_anno_start' and ndoc='$_ndoc_start' AND articolo='$datistart[articolo]' AND rigo = '$rigo'";

                        $conn->query($query);

                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
                            echo $_errore['2'];
                            //aggiungiamo la gestione scitta dell'errore..
                            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                            $_errori['files'] = "motore_doc_pdo.php";
                            scrittura_errori($_cosa, $_percorso, $_errori);
                            $_errori['errori'] = "NO";
                        }
                    }
                }
            }
        }

        //se tutto è andato bene annullo il l'array
        $rigo = "";


        // Stringa contenente la query di ricerca...
        // elimino il documento dalla tabella bolle

        $query = sprintf("DELETE from %s where anno=\"%s\" and ndoc=\"%s\" ", $_archivio['testacalce'], $_anno, $_ndoc);

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_doc_pdo.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }



        // Stringa contenente la query di ricerca...
        // elimino il documento dal corpo tabella bolle
        $query = sprintf("DELETE from %s where anno=\"%s\" and ndoc=\"%s\" ", $_archivio['dettaglio'], $_anno, $_ndoc);
        // Esegue la query...
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_doc_pdo.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }

        // ripristino i documenti che hanno generato il ddt
        if ($_tdoc == "conferma")
        {
            // ripristino i documenti che hanno generato il ddt
            $query = sprintf("UPDATE pv_testacalce SET status='ripristinato' WHERE tdocevaso=\"%s\" AND evasoanno=\"%s\" AND evasonum=\"%s\"", $_tdoc, $_anno, $_ndoc);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore_doc_pdo.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_errori['errori'] = "NO";
            }
            else
            {
                $_errori['errori'] = "OK";
            }
        }

        if (($_tdoc == "ddt") OR ( $_tdoc == "ddt_diretto"))
        {

            // ripristino i documenti che hanno generato il ddt
            $query = sprintf("UPDATE co_testacalce SET status='ripristinato' WHERE tdocevaso=\"%s\" AND evasoanno=\"%s\" AND evasonum=\"%s\" ", $_tdoc, $_anno, $_ndoc);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore_doc_pdo.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_errori['errori'] = "NO";
            }
            else
            {
                $_errori['errori'] = "OK";
            }

            // ripristino i documenti che hanno generato il ddt
            $query = sprintf("UPDATE pv_testacalce SET status='ripristinato' WHERE tdocevaso=\"%s\" AND evasoanno=\"%s\" AND evasonum=\"%s\"", $_tdoc, $_anno, $_ndoc);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore_doc_pdo.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_errori['errori'] = "NO";
            }
            else
            {
                $_errori['errori'] = "OK";
            }
        }

        // ripristino i documenti che hanno generato la fattura
        if (( $_tdoc == "FATTURA") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == $nomedoc))
        {

            if ($_tdoc == "FATTURA")
            {//ripristino il documenti di trasporto
                $query = sprintf("update bv_bolle set status='ripristina' where evasoanno=\"%s\" and evasonum=\"%s\" ", $_anno, $_ndoc);
                // Esegue la query...
                $result = $conn->exec($query);
                echo $query;

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "motore_doc_pdo.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                    $_errori['errori'] = "NO";
                }
                else
                {
                    $_errori['errori'] = "OK";
                }

                $query = sprintf("update bvfor_testacalce set status='ripristina' where evasoanno=\"%s\" and evasonum=\"%s\" ", $_anno, $_ndoc);
                // Esegue la query...
                $result = $conn->exec($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "motore_doc_pdo.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                    $_errori['errori'] = "NO";
                }
                else
                {
                    $_errori['errori'] = "OK";
                }
            }

            // ripristino i documenti che hanno generato la fattura
            if ($_tdoc == $nomedoc)
            {
                $query = sprintf("update co_testacalce set status='ripristina' where tdocevaso=\"%s\" and evasoanno=\"%s\" and evasonum=\"%s\" ", $_tdoc, $_anno, $_ndoc);
                #echo $query;

                $result = $conn->exec($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "motore_doc_pdo.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                    $_errori['errori'] = "NO";
                }
                else
                {
                    $_errori['errori'] = "OK";
                }
            }

            // Elimino le provvigioni sull'agente
            $query = sprintf("delete from provvigioni where anno=\"%s\" and ndoc=\"%s\"", $_anno, $_ndoc);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore_doc_pdo.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_errori['errori'] = "NO";
            }
            else
            {
                $_errori['errori'] = "OK";
            }
        }
        //fine funzione di eliminazione documenti inerenti la fattura
        //se il documento mi gestisce il magazzino vado ad aliminarlo
        if (( $_tdoc == "ddt") OR ( $_tdoc == "ddt_diretto") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == $nomedoc))
        {

            // Elimino i muovimenti sul magazzino
            $query = sprintf("delete from magazzino where tdoc=\"%s\" and anno=\"%s\" and ndoc=\"%s\"", $_tdoc, $_anno, $_ndoc);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore_doc_pdo.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_errori['errori'] = "NO";
            }
            else
            {
                $_errori['errori'] = "OK";
            }
        }



        echo "<center><h2>Documento eliminato correttamente</h2></center>";
        // elimino le sessioni usate
        // molto importante non eliminare le sessioni di lavoro
        unset($_SESSION['utente']);
        unset($_SESSION['anno']);
        unset($_SESSION['ndoc']);
        unset($_SESSION['tdoc']);
        unset($_SESSION['importi']);
        unset($_SESSION['totiva']);
        unset($_SESSION['rev']);
        unset($_SESSION['calce']);


        if ($_return == "")
        {
            $_return = "tuttok";
        }
    }// fine if elimina
    else
    {
        $_return = $_risultato;
    }

    return $_return;
}

/** Funzione che mi gestisce la testata del documento 
 ** Funzioni:n\
 * @$_cosa inserisci = inserisce la testata completamente..
 * @$_cosa inserisci_testata
 * @$_cosa scrivi_spedizione
 * @return $return
 */
function gestisci_testata($_cosa, $_utente, $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_archivi, $_parametri)
{
    #le funzioni globali
    global $conn;
    global $nomedoc;
    global $dec;
    global $ivasis;
    global $DATAIVA;

    
    if($_cosa == "inserisci")
    {
        
        //questa funzione inserisce la testata completamenta
        if (($_tdoc == "ddt") OR ( $_tdoc == "ddt_diretto"))
        {
          // inserimento testacalce viene inclusa la causale vendita
            $query = sprintf("insert into %s( anno, suffix, ndoc, datareg, utente, dragsoc, dragsoc2, dindirizzo, dcap, dcitta, dprov, modpag, vettore, spesevarie,
	 porto, aspetto, status, note, colli, trasporto, pesotot, totimpo, totiva, totdoc, causale, id_collo  )
	 values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",
	 \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\" )", $_archivi['testacalce'], $_anno, $_suffix, $_ndoc, $_datareg, $_utente,
                    $_parametri['dragsoc'], $_parametri['dragsoc2'], $_parametri['dindirizzo'], $_parametri['dcap'], $_parametri['dcitta'],
                    $_parametri['dprov'], $_parametri['modpag'], $_parametri['vettore'], $_parametri['varie'], $_parametri['porto'], $_parametri['aspetto'],
                    $_parametri['status'], $_parametri['notedoc'], $_parametri['colli'], $_parametri['trasporto'], $_parametri['peso'], $_parametri['imponibile'],
                    $_parametri['totimposta'], $_parametri['totdoc'], $_parametri['causale'], $_parametri['id_collo']);
        }
        elseif (( $_tdoc == "FATTURA") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == "$nomedoc"))
        {
            $query = sprintf("INSERT INTO $_archivi[testacalce]( tdoc, anno, suffix, ndoc, datareg, utente, dragsoc, dragsoc2, dindirizzo, dcap, dcitta, dprov, zona, agente,
             modpag, banca, abi, cab, cin, cc, iban, swift, sp_bancarie, imballo, trasporto, spesevarie, scoinco, vettore, listino, porto,
             aspetto, status, note, colli, pesotot, nettomerce, cod_iva_1, imponibile_1, imposta_1, cod_iva_2, imponibile_2, imposta_2, cod_iva_3, imponibile_3, imposta_3,
             cod_iva_4, imponibile_4, imposta_4, cod_iva_5, imponibile_5, imposta_5, totimpo, totiva, totdoc, totprovv, id_collo )
             values('$_tdoc', '$_anno', '$_suffix', $_ndoc', '$_datareg', '$_utente', '$_parametri[dragsoc]', '$_parametri[dragsoc2]', '$_parametri[dindirizzo]', '$_parametri[dcap]', '$_parametri[dcitta]', '$_parametri[dprov]', '$_parametri[zona]',
                    '$_parametri[agente]', '$_parametri[modpag]', '$_parametri[banca]', '$_parametri[abi]', '$_parametri[cab]', '$_parametri[cin]', '$_parametri[cc]', '$_parametri[iban]', '$_parametri[swift]', '$_parametri[spbanca]', '$_parametri[spimba]', '$_parametri[trasporto]',
                    '$_parametri[varie]', '$_parametri[scoinco]', '$_parametri[vettore]', '$_parametri[listinocli]', '$_parametri[porto]', '$_parametri[aspetto]', '$_parametri[status]', '$_parametri[notedoc]', '$_parametri[colli]', '$_parametri[peso]',
                    '$_parametri[nettomerce]', \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",
                    \"%s\", \"%s\", \"%s\", '$_parametri[imponibile]', '$_parametri[totimposta]', '$_parametri[totdoc]', '$_parametri[totprovv]', '$_parametri[id_collo]')", 
                    $_parametri['imponibili']['1']['aliquota'], $_parametri['imponibili']['1']['imponibile'], $_parametri['imponibili']['1']['imposta'],
                    $_parametri['imponibili']['2']['aliquota'], $_parametri['imponibili']['2']['imponibile'], $_parametri['imponibili']['2']['imposta'],
                    $_parametri['imponibili']['3']['aliquota'], $_parametri['imponibili']['3']['imponibile'], $_parametri['imponibili']['3']['imposta'],
                    $_parametri['imponibili']['4']['aliquota'], $_parametri['imponibili']['4']['imponibile'], $_parametri['imponibili']['4']['imposta'],
                    $_parametri['imponibili']['5']['aliquota'], $_parametri['imponibili']['5']['imponibile'], $_parametri['imponibili']['5']['imposta']);
        }
        elseif ($_tdoc == "fornitore")
        {
            // inserimento testacalce altri doc.
            $query = sprintf("insert into %s( anno, suffix, ndoc, datareg, utente, dragsoc, dragsoc2, dindirizzo, dcap, dcitta, dprov, modpag, vettore, listino,
	 porto, aspetto, status, note, colli, pesotot, totimpo, totiva, totdoc )
	values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",
	\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\" )", $_archivi['testacalce'], $_anno, $_suffix, $_ndoc, $_datareg, $_utente, $_parametri['dragsoc'], $_parametri['dragsoc2'], $_parametri['dindirizzo'], $_parametri['dcap'], $_parametri['dcitta'],
                    $_parametri['dprov'], $_parametri['modpag'], $_parametri['vettore'], $_listinocli, $_parametri['porto'], $_parametri['aspetto'],
                    $_parametri['status'], $_parametri['notedoc'], $_parametri['colli'], $_parametri['peso'], $_parametri['imponibile'], $_parametri['totimposta'], $_parametri['totdoc']);
        }
        elseif ($_tdoc == "preventivo")
        {
            // inserimento testacalce altri doc.
            $query = sprintf("insert into %s( anno, suffix, ndoc, datareg, utente, dragsoc, dragsoc2, dindirizzo, dcap, dcitta, dprov, modpag, vettore, spesevarie,
	 porto, aspetto, status, note, colli, trasporto, pesotot, totimpo, totiva, totdoc, data_scad )
	values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",
	\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_archivi['testacalce'], $_anno, $_suffix, $_ndoc, $_datareg, $_utente, $_parametri['dragsoc'], $_parametri['dragsoc2'], $_parametri['dindirizzo'], $_parametri['dcap'], $_parametri['dcitta'],
                    $_parametri['dprov'], $_parametri['modpag'], $_parametri['vettore'], $_parametri['varie'], $_parametri['porto'], $_parametri['aspetto'],
                    $_parametri['status'], $_parametri['notedoc'], $_parametri['colli'], $_parametri['trasporto'], $_parametri['peso'], $_parametri['imponibile'], $_parametri['totimposta'], $_parametri['totdoc'], $_parametri['data_scad']);
        }
        else
        {
            // inserimento testacalce altri doc.
            $query = sprintf("insert into %s( anno, suffix, ndoc, datareg, utente, dragsoc, dragsoc2, dindirizzo, dcap, dcitta, dprov, modpag, vettore, spesevarie,
	 porto, aspetto, status, note, colli, trasporto, pesotot, totimpo, totiva, totdoc )
	values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",
	\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\" )", $_archivi['testacalce'], $_anno, $_suffix, $_ndoc, $_datareg, $_utente, $_parametri['dragsoc'], $_parametri['dragsoc2'], $_parametri['dindirizzo'], $_parametri['dcap'], $_parametri['dcitta'],
                    $_parametri['dprov'], $_parametri['modpag'], $_parametri['vettore'], $_parametri['varie'], $_parametri['porto'], $_parametri['aspetto'],
                    $_parametri['status'], $_parametri['notedoc'], $_parametri['colli'], $_parametri['trasporto'], $_parametri['peso'], $_parametri['imponibile'], $_parametri['totimposta'], $_parametri['totdoc']);
        }
        
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "NO";
        }
        else
        {
            $return['errori'] = "OK";
        }
        
        
    }
    elseif ($_cosa == "inserisci_testata")
    {

        if (( $_tdoc == "FATTURA") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == "$nomedoc"))
        {
            $query = sprintf("insert into %s( tdoc, anno, suffix, ndoc, datareg, utente, status) values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_archivi['testacalce'], $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_utente, $_parametri['status']);
        }
        else
        {
            $query = sprintf("insert into %s( anno, suffix, ndoc, datareg, utente, status) values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_archivi['testacalce'], $_anno, $_suffix, $_ndoc, $_datareg, $_utente, $_parametri['status']);
        }


        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "NO";
        }
        else
        {
            $return['errori'] = "OK";
        }
    }
    elseif($_cosa == "aggiorna")
    {
        if (($_tdoc == "ddt") OR ( $_tdoc == "ddt_diretto"))
        {
            $query = sprintf("update %s set dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", modpag=\"%s\", vettore=\"%s\",
		 spesevarie=\"%s\", porto=\"%s\", aspetto=\"%s\", status=\"%s\", note=\"%s\", colli=\"%s\", trasporto=\"%s\", pesotot=\"%s\", totimpo=\"%s\",
		totiva=\"%s\", totdoc=\"%s\", causale=\"%s\", id_collo=\"%s\" where anno=\"%s\" AND suffix=\"%s\" and ndoc=\"%s\"", $_archivi['testacalce'], $_parametri['dragsoc'],
                    $_parametri['dragsoc2'], $_parametri['dindirizzo'], $_parametri['dcap'], $_parametri['dcitta'], $_parametri['dprov'], $_parametri['modpag'],
                    $_parametri['vettore'], $_parametri['varie'], $_parametri['porto'], $_parametri['aspetto'], $_parametri['status'], $_parametri['notedoc'],
                    $_parametri['colli'], $_parametri['trasporto'], $_parametri['peso'], $_parametri['imponibile'], $_parametri['totimposta'], $_parametri['totdoc'],
                    $_parametri['causale'], $_parametri['id_collo'], $_anno, $_suffix, $_ndoc);
        }
        elseif (( $_tdoc == "FATTURA") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == "$nomedoc"))
        {

            $query = sprintf("update %s set dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", zona=\"%s\", agente=\"%s\",
             modpag=\"%s\", banca=\"%s\", abi=\"%s\", cab=\"%s\", cin=\"%s\", cc=\"%s\", iban=\"%s\", swift=\"%s\", sp_bancarie=\"%s\",
             imballo=\"%s\", trasporto=\"%s\", spesevarie=\"%s\", scoinco=\"%s\", vettore=\"%s\", listino=\"%s\", porto=\"%s\",
             aspetto=\"%s\", status=\"%s\", note=\"%s\", colli=\"%s\", pesotot=\"%s\", nettomerce=\"%s\",
             cod_iva_1=\"%s\", imponibile_1=\"%s\", imposta_1=\"%s\", cod_iva_2=\"%s\", imponibile_2=\"%s\", imposta_2=\"%s\",
             cod_iva_3=\"%s\", imponibile_3=\"%s\", imposta_3=\"%s\",cod_iva_4=\"%s\", imponibile_4=\"%s\", imposta_4=\"%s\",
             cod_iva_5=\"%s\", imponibile_5=\"%s\", imposta_5=\"%s\",totimpo=\"%s\", totiva=\"%s\",
             totdoc=\"%s\", totprovv=\"%s\", id_collo=\"%s\" where anno=\"%s\" AND suffix=\"%s\" and ndoc=\"%s\"", $_archivi['testacalce'], $_parametri['dragsoc'], 
                    $_parametri['dragsoc2'], $_parametri['dindirizzo'], $_parametri['dcap'], $_parametri['dcitta'], $_parametri['dprov'],
                    $_parametri['zona'], $_parametri['agente'], $_parametri['modpag'], $_parametri['banca'], $_parametri['abi'], $_parametri['cab'],
                    $_parametri['cin'], $_parametri['cc'], $_parametri['iban'], $_parametri['swift'], $_parametri['spbanca'], $_parametri['spimba'],
                    $_parametri['trasporto'], $_parametri['varie'], $_parametri['scoinco'], $_parametri['vettore'], $_listinocli, $_parametri['porto'],
                    $_parametri['aspetto'], $_parametri['status'], $_parametri['notedoc'], $_parametri['colli'], $_parametri['peso'],
                    $_parametri['nettomerce'],
                    $_parametri['imponibili']['1']['aliquota'], $_parametri['imponibili']['1']['imponibile'], $_parametri['imponibili']['1']['imposta'],
                    $_parametri['imponibili']['2']['aliquota'], $_parametri['imponibili']['2']['imponibile'], $_parametri['imponibili']['2']['imposta'],
                    $_parametri['imponibili']['3']['aliquota'], $_parametri['imponibili']['3']['imponibile'], $_parametri['imponibili']['3']['imposta'],
                    $_parametri['imponibili']['4']['aliquota'], $_parametri['imponibili']['4']['imponibile'], $_parametri['imponibili']['4']['imposta'],
                    $_parametri['imponibili']['5']['aliquota'], $_parametri['imponibili']['5']['imponibile'], $_parametri['imponibili']['5']['imposta'],
                    $_parametri['imponibile'], $_parametri['totimposta'], $_parametri['totdoc'], $_parametri['totprovv'], $_parametri['id_collo'],
                    $_anno, $_suffix, $_ndoc);
        }
        elseif ($_tdoc == "fornitore")
        {
            // testacalce documento
            $query = sprintf("update %s set dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", modpag=\"%s\",
		vettore=\"%s\", porto=\"%s\", aspetto=\"%s\", status=\"%s\", note=\"%s\", colli=\"%s\", pesotot=\"%s\", totimpo=\"%s\",
		 totiva=\"%s\", totdoc=\"%s\" where anno=\"%s\" AND suffix=\"%s\", nd ndoc=\"%s\"", $_archivi['testacalce'], $_parametri['dragsoc'],
                    $_parametri['dragsoc2'], $_parametri['dindirizzo'], $_parametri['dcap'], $_parametri['dcitta'], $_parametri['dprov'],
                    $_parametri['modpag'], $_parametri['vettore'], $_parametri['porto'], $_parametri['aspetto'], $_parametri['status'],
                    $_parametri['notedoc'], $_parametri['colli'], $_parametri['peso'], $_parametri['imponibile'], $_parametri['totimposta'],
                    $_parametri['totdoc'], $_anno, $_suffix, $_ndoc);
        }
        elseif ($_tdoc == "preventivo")
        {

            $_rev1 = $_parametri['rev'] + 1;
            // testacalce documento
            $query = sprintf("update %s set dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", modpag=\"%s\", vettore=\"%s\",
		 spesevarie=\"%s\", porto=\"%s\", aspetto=\"%s\", status=\"%s\", note=\"%s\", colli=\"%s\", trasporto=\"%s\", pesotot=\"%s\", totimpo=\"%s\",
		 totiva=\"%s\", totdoc=\"%s\", rev=\"%s\", data_scad=\"%s\" where anno=\"%s\" AND suffix=\"%s\" and ndoc=\"%s\"", $_archivi['testacalce'], $_parametri['dragsoc'],
                    $_parametri['dragsoc2'], $_parametri['dindirizzo'], $_parametri['dcap'], $_parametri['dcitta'], $_parametri['dprov'],
                    $_parametri['modpag'], $_parametri['vettore'], $_parametri['varie'], $_parametri['porto'], $_parametri['aspetto'], $_parametri['status'],
                    $_parametri['notedoc'], $_parametri['colli'], $_parametri['trasporto'], $_parametri['peso'], $_parametri['imponibile'],
                    $_parametri['totimposta'], $_parametri['totdoc'], $_rev1, $_parametri['data_scad'], $_anno, $_suffix, $_ndoc);
        }
        else
        {
            $_rev1 = $_parametri['rev'] + 1;
            // testacalce documento
            $query = sprintf("update %s set dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", modpag=\"%s\", vettore=\"%s\",
		 spesevarie=\"%s\", porto=\"%s\", aspetto=\"%s\", status=\"%s\", note=\"%s\", colli=\"%s\", trasporto=\"%s\", pesotot=\"%s\", totimpo=\"%s\",
		 totiva=\"%s\", totdoc=\"%s\", rev=\"%s\" where anno=\"%s\" AND suffix=\"%s\" and ndoc=\"%s\"", $_archivi['testacalce'], $_parametri['dragsoc'],
                    $_parametri['dragsoc2'], $_parametri['dindirizzo'], $_parametri['dcap'], $_parametri['dcitta'], $_parametri['dprov'], $_parametri['modpag'],
                    $_parametri['vettore'], $_parametri['varie'], $_parametri['porto'], $_parametri['aspetto'], $_parametri['status'], $_parametri['notedoc'],
                    $_parametri['colli'], $_parametri['trasporto'], $_parametri['peso'], $_parametri['imponibile'], $_parametri['totimposta'], $_parametri['totdoc'],
                    $_rev1, $_anno, $_suffix, $_ndoc);
        }

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "NO";
        }
        else
        {
            $return['errori'] = "OK";
        }
            
            
            
            
    }
    elseif ($_cosa == "blocca_numero")
    {
        //verificiamo che il numero sia disponibile in caso positivo lo blocchiamo inserendo parte del documento..
        //verifichiamo che il numero sia disponibile..

        $errori = seleziona_documento("disponibilita_numero", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivi, $_parametri);

        if ($errori['errori'] != "NO")
        {
            //prendiamo il numero dalla funzione

            $_ndoc = $errori['ndoc'];
            //azzeriamo la variabile errori
            $errori = "";


            //passiamo alla funzione inserisci parte del documento testacalce..
            $_parametri['status'] = "inserito";
            $errori = gestisci_testata("inserisci_testata", $_utente, $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_archivi, $_parametri);

            if ($errori['errori'] != "OK")
            {
                echo "errore inserimento parte testata blocca numero<br>";
                $return['errori'] = "NO";
            }
            else
            {
                $return['errori'] = "OK";
                $return['ndoc'] = $_ndoc;
            }
        }
        else
        {
            echo "errore Generale";
            exit;
        }
    }
    elseif ($_cosa == "aggiorna_chiudi")
    {

        //qui aggiornaim oil documento e lo chiediamo..
        $query = "UPDATE $_archivi[testacalce] SET status='$_parametri[status]', tdocevaso='$_parametri[t_doc_end]', evasonum='$_parametri[ndoc_end]', evasoanno='$_parametri[anno_end]', evasosuffix='$_parametri[suffix_end]' WHERE anno='$_anno' AND suffix='$_suffix' and ndoc='$_ndoc' and utente='$_utente'";


        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "NO";
        }
        else
        {
            $return['errori'] = "OK";
        }
    }
    elseif ($_cosa == "aggiorna_travasa")
    {
        //dividiamo le variabili dell'array parametri..

        $dati3 = $_parametri['datidoc'];
        $_imponibili = $_parametri['imponibili'];
        $_datiutente = $_parametri['utente'];


        //aggiorniamo il documento finale con la somma delle cose inserendo i dati mancanti appena prelevati dalla anagrafica cliente

        if (( $_tdoc == "FATTURA") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == "$nomedoc"))
        {
            $query = sprintf("UPDATE $_archivi[testacalce] SET dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", vettore=\"%s\", porto=\"%s\", aspetto=\"%s\", zona=\"%s\", agente=\"%s\", 
				modpag=\"%s\", banca=\"%s\", abi=\"%s\", cab=\"%s\", cin=\"%s\", cc=\"%s\", iban=\"%s\", swift=\"%s\",
                       nettomerce=\"%s\", cod_iva_1=\"%s\", imponibile_1=\"%s\", imposta_1=\"%s\", cod_iva_2=\"%s\", imponibile_2=\"%s\", imposta_2=\"%s\",
             cod_iva_3=\"%s\", imponibile_3=\"%s\", imposta_3=\"%s\",cod_iva_4=\"%s\", imponibile_4=\"%s\", imposta_4=\"%s\",
             cod_iva_5=\"%s\", imponibile_5=\"%s\", imposta_5=\"%s\",colli=\"%s\", pesotot=\"%s\", trasporto=\"%s\", spesevarie=\"%s\", sp_bancarie=\"%s\", totimpo=\"%s\", totiva=\"%s\", totdoc=\"%s\", totprovv=\"%s\"
                       where anno='$_anno' AND suffix='$_suffix' and ndoc='$_ndoc' and utente='$_utente'", addslashes($dati3['dragsoc']), addslashes($dati3['dragsoc2']), addslashes($dati3['dindirizzo']), $dati3['dcap'], addslashes($dati3['dcitta']), $dati3['dprov'], $dati3['vettore'], $dati3['porto'], $dati3['aspetto'], $_datiutente['zona'], $_datiutente['codagente'], $dati3['modpag'], addslashes($_datiutente['banca']), $_datiutente['abi'], $_datiutente['cab'], $_datiutente['cin'], $_datiutente['cc'], $_datiutente['iban'], $_datiutente['swift'], $_parametri['nettodoc'], $_imponibili['1']['aliquota'], $_imponibili['1']['imponibile'], $_imponibili['1']['imposta'], $_imponibili['2']['aliquota'], $_imponibili['2']['imponibile'], $_imponibili['2']['imposta'], $_imponibili['3']['aliquota'], $_imponibili['3']['imponibile'], $_imponibili['3']['imposta'], $_imponibili['4']['aliquota'], $_imponibili['4']['imponibile'], $_imponibili['4']['imposta'], $_imponibili['5']['aliquota'], $_imponibili['5']['imponibile'], $_imponibili['5']['imposta'], $_parametri['colli'], $_parametri['peso'], $_parametri['trasporto'], $_parametri['varie'], $_parametri['speseb'], $_parametri['imponibile'], $_parametri['imposta'], $_parametri['totdoc'], $_parametri['totprovv']);
        }
        elseif (($_tdoc == "ddt") OR ( $_tdoc == "ddt_diretto"))
        {
            $query = sprintf("UPDATE $_archivi[testacalce] SET dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", banca=\"%s\", vettore=\"%s\", porto=\"%s\", aspetto=\"%s\", modpag=\"%s\",
                        colli=\"%s\", pesotot=\"%s\", trasporto=\"%s\", spesevarie=\"%s\", totimpo=\"%s\", totiva=\"%s\", totdoc=\"%s\", causale=\"%s\"
                        where anno='$_anno' AND suffix='$_suffix' and ndoc='$_ndoc' and utente='$_utente'", addslashes($dati3['dragsoc']), addslashes($dati3['dragsoc2']), addslashes($dati3['dindirizzo']), $dati3['dcap'], addslashes($dati3['dcitta']), $dati3['dprov'], $_banca, $dati3['vettore'], $dati3['porto'], $dati3['aspetto'], $dati3['modpag'], $_parametri['colli'], $_parametri['peso'], $_parametri['trasporto'], $_parametri['varie'], $_parametri['imponibile'], $_parametri['totimposta'], $_parametri['totdoc'], 'VENDITA');
        }
        else
        {
            $query = sprintf("UPDATE $_archivi[testacalce] SET dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", banca=\"%s\", vettore=\"%s\", porto=\"%s\", aspetto=\"%s\", modpag=\"%s\",
                        colli=\"%s\", pesotot=\"%s\", trasporto=\"%s\", spesevarie=\"%s\", totimpo=\"%s\", totiva=\"%s\", totdoc=\"%s\"
                        where anno='$_anno' AND suffix='$_suffix' and ndoc='$_ndoc' and utente='$_utente'", addslashes($dati3['dragsoc']), addslashes($dati3['dragsoc2']), addslashes($dati3['dindirizzo']), $dati3['dcap'], addslashes($dati3['dcitta']), $dati3['dprov'], $_banca, $dati3['vettore'], $dati3['porto'], $dati3['aspetto'], $dati3['modpag'], $_parametri['colli'], $_parametri['peso'], $_parametri['trasporto'], $_parametri['varie'], $_parametri['imponibile'], $_parametri['imposta'], $_parametri['totdoc']);
        }

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "NO";
        }
        else
        {
            $return['errori'] = "OK";
        }
    }
    elseif ($_cosa == "leggi_riga_testata")
    {

        if ($_tdoc == "ddtacq")
        {
            $query = "select * from magazzino where tdoc='ddtacq' AND anno='$_anno' and ndoc='$_ndoc'";
        }
        else
        {
            $query = sprintf("select * from %s where anno=\"%s\" and ndoc=\"%s\"", $_archivi['testacalce'], $_anno, $_ndoc);
        }


        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            foreach ($result AS $dati);

            $dati['errori'] = "OK";
        }


        $return = $dati;
    }
    elseif ($_cosa == "scrivi_spedizione")
    {
        #procedura di aggiornamento testaca e calce del documento

        $_archivi = archivio_tdoc($_tdoc);

        $_dragsoc = addslashes($_parametri['dragsoc']);
        $_dragsoc2 = addslashes($_parametri['dragsoc2']);
        $_dindirizzo = addslashes($_parametri['dindirizzo']);
        $_dcitta = addslashes($_parametri['dcitta']);
        $_note = addslashes($_parametri['memoart']);
        $id_collo = strtoupper($_parametri['id_collo']);

        $query = "UPDATE $_archivi[testacalce] SET dragsoc='$_dragsoc', dragsoc2='$_dragsoc2', dindirizzo='$_dindirizzo', dcap='$_parametri[dcap]',
            dcitta='$_dcitta', dprov='$_parametri[dprov]', vettore='$_parametri[vettore]', porto='$_parametri[porto]', aspetto='$_parametri[aspetto]', colli='$_parametri[colli]',
                pesotot='$_parametri[peso]', id_collo='$id_collo', note='$_note' WHERE anno='$_anno' and suffix='$_suffix' AND ndoc='$_ndoc'";
        //echo $query;

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = $_errori;
        }
        else
        {
            $return['errori'] = "OK";
            $return['tdoc'] = $_tdoc;
            $return['ndoc'] = $_ndoc;
            $return['anno'] = $_anno;
            $return['suffix'] = $_suffix;
        }
    }
    elseif($_cosa == "verifica_zero")
    {
        $query = "SELECT bv_bolle.anno, bv_bolle.suffix, bv_bolle.ndoc, bv_bolle.utente, status, articolo, totriga from bv_bolle INNER JOIN bv_dettaglio ON bv_bolle.anno=bv_dettaglio.anno and bv_bolle.ndoc=bv_dettaglio.ndoc WHERE bv_bolle.anno='$_anno' AND bv_bolle.suffix='$_suffix' AND bv_bolle.ndoc = '$_ndoc' AND status = 'stampato' AND causale != 'RESO' AND causale != 'SOSTIT. IN GARANZIA' AND causale != 'RIPARAZIONE' AND causale != 'CONTO DEPOSITO' AND (articolo != 'vuoto' and totriga = '0.00')";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = $_errori;
        }

        if ($result->rowCount() > 0)
        {
            echo "<center>";
            echo " <br>Controllo Generazione articoli senza prezzo";
            echo " <table border=1 align=center><tr>";
            echo " <td>anno</td><td>n. doc.</td><td>status</td><td>articolo</td><td>Valore</td></tr>";

            foreach ($result AS $dati)
            {
                //cerchiamo l'articolo risultato e vediamo se è un articolo promozionale

                $_articolo = tabella_articoli("singola", $dati['articolo'], $_parametri);

                if ($_articolo['egpz'] != "SI")
                {
                    printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $dati['anno'], $dati['ndoc'], $dati['status'], $dati['articolo'], $dati['totriga']);
                    $_errore = "SI";
                }
            }

            echo "</table>";
            if ($_errore == "SI")
            {
                echo "<br> Programma interrotto ....";
                exit;
            }
            else
            {
                echo "<h3>Trovato articolo promozionale, Nessun problema</h3>\n";
            }
        }
    }
    elseif ($_cosa == "ultimo_numero")
    {

        if ($_tdoc == "ddtacq")
        {
            //$query = sprintf("select * from %s where anno=\"%s\" ORDER BY ndoc DESC LIMIT 1", $_archivio['testacalce'], $_anno);
            $query = "select * from magazzino where tdoc='ddtacq' AND anno='$_anno' ORDER BY ndoc DESC LIMIT 1";
        }
        else
        {
            $query = "select * from $_archivi[testacalce] where anno='$_anno' AND suffix='$_suffix' ORDER BY ndoc DESC LIMIT 1";
        }

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            foreach ($result AS $dati)
                ;

            $return = $dati['ndoc'] + 1;
        }
    }
    else
    {
        //funzione che mi legge la testata del documento passato..

        $query = sprintf("SELECT * from %s where anno=\"%s\" and utente=\"%s\" and ndoc = \"%s\"", $_archivi['testacalce'], $_anno, $_utente, $_ndoc);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
           $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            foreach ($result AS $return)
                ;
        }
    }



    return $return;
}

/**Funzione che mi gestisce tutti i corpi dei documenti..
 * @cosa = inserisci_singola = mi inserisce una riga singola del corpo del documento
 * @return type
 */
function gestisci_dettaglio($_cosa, $_archivi, $_tdoc, $_anno, $_suffix, $_ndoc, $_rigo, $_utente, $_codice, $_descrizione, $_iva, $_parametri)
{
    #le funzioni globali
    global $conn;
    global $nomedoc;
    global $dec;
    global $ivasis;
    global $DATAIVA;
    global $_percorso;

    $_descrizione = addslashes($_descrizione);

    if ($_cosa == "inserisci_singola")
    {

        if (( $_tdoc == "FATTURA") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == "$nomedoc"))
        {
            $query = "insert into $_archivi[dettaglio] ( tdoc, anno, suffix, ndoc, rigo, utente, articolo, descrizione, unita, quantita, listino, scva, scvb, scvc, nettovendita, totriga, iva, totrigaprovv, peso)
                                 values('$_tdoc', '$_anno', '$_suffix', '$_ndoc', '$_rigo', '$_utente', '$_codice', '$_descrizione', '$_parametri[unita]', '$_parametri[quantita]',
								'$_parametri[listino]', '$_parametri[scva]', '$_parametri[scvb]', '$_parametri[scvc]', '$_parametri[nettovendita]', '$_parametri[totriga]', '$_iva', '$_parametri[totrigaprovv]', '$_parametri[peso]')";
        }
        elseif (($_tdoc == "ddtacq") AND ($_codice != "vuoto"))
        {
            //evitiamo di scrivere gli articoli esenti magazzino ed diversi da vuoto..
            

            if ($_parametri['agg'] == "SI")
            {
                // aggiornaiamo la anagrafica articoli, 
// 				verifichiamo se bisogna aggiornare il fornitore uno o due
// 				partiamo con l'uno passarei tutto ad una funzione

                $articoli = tabella_articoli("aggiorna_fornitore", $_codice, $_parametri);
            }

            //aggiorniamo l'ultimo acquisto..
            
                $articoli = tabella_articoli("aggiorna_ultimo_acq", $_codice, $_parametri);
                        
            //qui mettiamo il magazzino
            $query = "insert into magazzino( tdoc, anno, suffix, ndoc, datareg, tut, rigo, utente, articolo, qtacarico, valoreacq, ddtfornitore, fatturacq, protoiva, status)
				    values('$_tdoc', '$_anno', '$_suffix' ,'$_ndoc', '$_parametri[datareg]', 'f', '$_rigo', '$_utente', '$_codice', '$_parametri[quantita]', '$_parametri[totriga]', '$_parametri[ddtfornitore]', '$_parametri[fatturacq]', '$_parametri[protoiva]', '$_parametri[status]')";
            
            
        }
        elseif (($_tdoc == "ddt") OR ( $_tdoc == "ddt_diretto"))
        {
            $query = "insert into $_archivi[dettaglio] ( anno, suffix, ndoc, rigo, utente, articolo, descrizione, unita, quantita, listino, scva, scvb, scvc, nettovendita, totriga, iva, totrigaprovv, peso)
                                 values( '$_anno', '$_suffix', '$_ndoc', '$_rigo', '$_utente', '$_codice', '$_descrizione', '$_parametri[unita]', '$_parametri[quantita]',
								'$_parametri[listino]', '$_parametri[scva]', '$_parametri[scvb]', '$_parametri[scvc]', '$_parametri[nettovendita]', '$_parametri[totriga]', '$_iva', '$_parametri[totrigaprovv]', '$_parametri[peso]' )";
        }
        elseif($_tdoc == "fornitore")
        {
            $query = "insert into $_archivi[dettaglio] ( anno, suffix, ndoc, rigo, utente, articolo, artfor, descrizione, unita, quantita, qtaevasa, qtaestratta, qtasaldo, rsaldo, listino, scaa, scab, scac, nettoacq, totriga, peso)
                         values( '$_anno','$_suffix' ,'$_ndoc', '$_rigo', '$_utente', '$_codice', '$_parametri[artfor]', '$_descrizione', '$_parametri[unita]', '$_parametri[quantita]', '$_parametri[qtaevasa]', '$_parametri[qtaestratta]', '$_parametri[qtasaldo]', '$_parametri[rsaldo]',
                                                        '$_parametri[listino]', '$_parametri[scva]', '$_parametri[scvb]', '$_parametri[scvc]', '$_parametri[nettovendita]', '$_parametri[totriga]', '$_parametri[peso]' )";
        }
        else
        {
            $query = "insert into $_archivi[dettaglio] ( anno, suffix, ndoc, rigo, utente, articolo, descrizione, unita, quantita, qtaevasa, qtaestratta, qtasaldo, rsaldo, listino, scva, scvb, scvc, nettovendita, totriga, iva, totrigaprovv, peso, consegna)
                                 values( '$_anno', '$_suffix', '$_ndoc', '$_rigo', '$_utente', '$_codice', '$_descrizione', '$_parametri[unita]', '$_parametri[quantita]', '$_parametri[qtaevasa]', '$_parametri[qtaestratta]', '$_parametri[qtasaldo]', '$_parametri[rsaldo]',
								'$_parametri[listino]', '$_parametri[scva]', '$_parametri[scvb]', '$_parametri[scvc]', '$_parametri[nettovendita]', '$_parametri[totriga]', '$_iva', '$_parametri[totrigaprovv]', '$_parametri[peso]', '$_parametri[consegna]' )";
        }

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }
        $return = $_errori;
    }
    elseif ($_cosa == "leggi_corpo")
    {

        if ($_parametri == "norighe")
        {
            $query = "SELECT * from $_archivi[dettaglio] where articolo != 'vuoto' AND anno='$_anno' AND suffix='$_suffix' and ndoc='$_ndoc' and utente='$_utente' order by rigo";
        }
        else
        {
            $query = "SELECT * from $_archivi[dettaglio] where anno='$_anno' AND suffix='$_suffix' and ndoc='$_ndoc' and utente='$_utente' order by rigo";
        }

        //echo $query;

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }

        $return = $result;
    }
    elseif ($_cosa == "aggiorna_singola")
    {

        if ($_tdoc == "fornitore")
        {
            $query = "UPDATE $_archivi[dettaglio] SET qtaevasa='$_parametri[qtaevasa]', qtaestratta='$_parametri[qtaestratta]', qtasaldo='$_parametri[qtasaldo]', rsaldo='$_parametri[rsaldo]', totriga='$_parametri[totriga]' WHERE anno='$_anno' AND ndoc='$_ndoc' AND rigo='$_rigo' AND utente='$_utente'";
        }
        else
        {
            $query = "UPDATE $_archivi[dettaglio] SET qtaevasa='$_parametri[qtaevasa]', qtaestratta='$_parametri[qtaestratta]', qtasaldo='$_parametri[qtasaldo]', rsaldo='$_parametri[rsaldo]', totriga='$_parametri[totriga]', totrigaprovv='$_parametri[totrigaprovv]' , peso='$_parametri[peso]' WHERE anno='$_anno' AND ndoc='$_ndoc' AND rigo='$_rigo' AND utente='$_utente'";
        }

        //echo $query;

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
           $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }
        $return = $_errori;
    }
    elseif ($_cosa == "verifica_saldo")
    {

        $query = sprintf("SELECT * FROM %s where articolo != 'vuoto' AND rsaldo != 'SI' and anno=\"%s\" and ndoc=\"%s\" and utente=\"%s\"", $_archivi['dettaglio'], $_anno, $_ndoc, $_utente);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }

        $_righe = $result->rowCount();

        if ($_righe > "0")
        {
            $_status = "parziale";
        }
        else
        {
            $_status = "evaso";
        }

        $return = $_status;
    }
    elseif($_cosa == "elimina")
    {
        $query = "delete from $_archivi[dettaglio] where anno='$_anno' AND suffix='$_suffix' AND ndoc='$_ndoc'";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }
        $return = $_errori;
    }
    else
    {
        //funzione che mi legge tutte le righe del corpo documento richiesto..

        $query = sprintf("SELECT * from %s where anno=\"%s\" and ndoc=\"%s\" and utente=\"%s\" order by rigo", $_archivi['dettaglio'], $_anno, $_ndoc, $_utente);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";

            $return = $result;
        }
    }


    return $return;
}

/** Funzione che mi permette di gestire il magazzino in corso durante i documenti
 * $cosa = Evadi
 * //la differenza tra aggiornare ed inserire è solamente il fatto di cancellare prima..
    //quindi .. verifichiamo se è aggiorna cancelliamo altrimenti lasciamo stare..
 * 
 * @return type
 */
function gestisci_magazzino($_cosa, $id, $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_utente, $_tut, $_archivi, $_parametri)
{
    #le funzioni globali
    global $conn;
    global $nomedoc;
    global $dec;
    global $ivasis;
    global $DATAIVA;
    global $_percorso;
    $_causale = $_parametri['causale'];

    //la differenza tra aggiornare ed inserire è solamente il fatto di cancellare prima..
    //quindi .. verifichiamo se è aggiorna cancelliamo altrimenti lasciamo stare..
    if ($_cosa == "Evadi")
    {
        //devo solo cambiare il tipo di query per tutto il resto è uguale
        //prendiamoci il documento relativo e finito
        $_result = gestisci_dettaglio("seleziona_corpo", $_archivi, $_tdoc, $_anno, $_suffix, $_ndoc, $_rigo, $_utente, $_codice, $_descrizione, $_iva, $_parametri);
    }
    else
    {
        //eliminiamo eventuale prova..

        $_errori = tabella_magazzino("elimina_documento", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_tut, $_rigo, $_utente, $_codice, $_parametri);

        if ($_errori['errori'] != "OK")
        {
            echo "errore eliminazione documento di magazzino $_ndoc";
        }

        // ora procediamo a inserire gli articoli che sono legati al magazzino
        // dopo averlo cancellato lo reinserisco

        $_result = tabella_doc_basket("leggi_sessione", $id, $_rigo, $_anno, $_suffix, $_ndoc, $_utente, $_articolo, $_parametri);
    }

    //estrappoliamo i dati..
    foreach ($_result AS $dati)
    {//4
        //cerco le le esenzioni articolo per non inserirle nel magazzino
        $_esma = tabella_articoli("esma", $dati['articolo'], $_parametri);

        //se esma = esenzione magazzino � uguale a si escudo la muovimentazione
        if ($_esma == "NO")
        { // parentesi d'escusione
            // Stringa contenente la query di inserimento
            // la riga qui sotto � una riga che serve per non inserire i muovimenti magazzino le rige descrittive.
            if ($dati['articolo'] !== "vuoto")
            {//5
                if (($_causale == "RESO") OR ( $_causale == "CONTO LAVORO") OR ( $_causale == "RESO SOST. GARANZIA"))
                {
                    // devo scaricare i carichi in negativo
                    //qui scarico imposto l'utente a cliente per l'inserimento nel database
                    //eliminiamo la componente parametri..
                    $_parametri = "";
                    $_parametri['qtacarico'] = -$dati['quantita'];
                    $_parametri['valoreacq'] = -$dati['totriga'];


                    $_errori = tabella_magazzino("inserisci_singola", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, "c", $_rigo, $dati['utente'], $dati['articolo'], $_parametri);
                }// chiuso
                elseif ($_tdoc == "NOTA CREDITO")
                {
                    // qui carico se �una nota credito
                    #qui carico il magazzino in positivo come se fosse un ddt di acquisto
                    //eliminiamo la componente parametri..
                    $_parametri = "";
                    $_parametri['qtascarico'] = -$dati['quantita'];
                    $_parametri['valorevend'] = -$dati['totriga'];

                    $_errori = tabella_magazzino("inserisci_singola", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, "c", $_rigo, $dati['utente'], $dati['articolo'], $_parametri);
                }
                else
                {
                    //qui scarico imposto l'utente a cliente per l'inserimento nel database
                    #qui vuol dire che è una vendita normale
                    //eliminiamo la componente parametri..
                    $_parametri = "";
                    $_parametri['qtascarico'] = $dati['quantita'];
                    $_parametri['valorevend'] = $dati['totriga'];


                    $_errori = tabella_magazzino("inserisci_singola", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, "c", $_rigo, $dati['utente'], $dati['articolo'], $_parametri);
                }
            }//5
        }// fine esclusione magazzino
    }


    $_return = $_errori;

    return $_return;
}

//-------------------------------------------------------------------------------------------------------------
#ora Dobbiamo creare una funzione che mi gestisca le provvigioni.

function gestione_provvigioni($_funzione, $_tdoc, $_anno, $_suffix, $_ndoc, $_agente, $_datareg, $_codutente, $_totdoc, $_totprovv)
{
    #parametri globali
    global $conn;
    global $nomedoc;
    global $_percorso;


    #dobbiamo distinguere se la funzione è di inserimento o di aggiornamento

    if ($_funzione == "aggiorna")
    {
        // inizio gestione provvigioni Agente
        if ($_tdoc != "NOTA CREDITO")
        {//6
            // Inseriamo nell'anagrafica provvigioni la fattura appena generata se �fattura va in positivo
            $query = "update provvigioni set codage='$_agente', totdoc='$_totdoc', provvigioni='$_totprovv' where ndoc='$_ndoc' and anno='$_anno' and suffix='$_suffix' and tdoc='$_tdoc'";
        }//6
        else
        {//7
            // qui carico se una nota credito
            // Inseriamo nell'anagrafica provvigioni la fattura appena generata in negativo perche �un reso
            $query = "update provvigioni set codage='$_agente', totdoc='-$_totdoc', provvigioni='-$_totprovv' where ndoc='$_ndoc' and anno='$_anno' AND suffix='$_suffix', and tdoc='$_tdoc'";
        }//7	fin
    }
    else
    {
        if ($_tdoc != "NOTA CREDITO")
        {
            
            // Inseriamo nell'anagrafica provvigioni la fattura appena generata se e fattura va in positivo
            $query = "insert into provvigioni( codage, ndoc, anno, suffix, tdoc, datareg, utente, totdoc, provvigioni )
             values('$_agente', '$_ndoc', '$_anno', '$_suffix', '$_tdoc', '$_datareg', '$_codutente', '$_totdoc', '$_totprovv')";
            #echo $query;
        }
        else
        {
            // qui carico se �una nota credito
            // Inseriamo nell'anagrafica provvigioni la fattura appena generata in negativo perche �un reso
            $query = "insert into provvigioni( codage, ndoc, anno, suffix, tdoc, datareg, utente, totdoc, provvigioni )
             values('$_agente', '$_ndoc', '$_anno', '$_suffix', '$_tdoc', '$_datareg', '$_codutente', '-$_totdoc', '-$_totprovv')";
        }
    }

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        $_errori['errori'] = "NO";
    }
    else
    {
        $_errori['errori'] = "OK";
    }


    return $_errori;
}

//----------------------------------------------------------------------------------------------------------

/**
 * funzione che mi mostra il carrello in forma tabulare:
 *
 * @global  $conn
 * @global  $dec
 * @param <type> tipo di maschera, PARZIALE
 * @param <type> $id
 * @param <type> $_tdoc
 * @param <type> $IVAMULTI
 * @param <type> $ivasis
 */
function mostra_carrello($_dove, $id, $_tdoc, $IVAMULTI, $ivasis)
{
    global $conn;
    global $dec;
    global $_percorso;
    global $_messaggio;
    include_once "lib_html.php";

    $_colspan = "0";

    //leggiamo il carrello..
    $result = tabella_doc_basket("elenco", $id, $_rigo, $_anno, $_suffix, $_ndoc, $_utente, $_articolo, $_parametri);

    // Tutto procede a meraviglia...
    //printf("<td align=left>%s</td>", $dati['utente']);

    echo "<table class=\"classic_bordo\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"><tr>";
    // Richiamo le variabili dalla sessione
    // corpo documento...
    echo "<tr><td colspan=\"2\" align=\"center\"><h2>Corpo Documento <font color=\"blue\">$_messaggio</font></h2></td></tr>\n";

    echo "<tr><td style=\"font-size: 1.3em;\" align=center><a href=\"seleziona.php\">Aggiungi riga</a></td>";
    echo "<td style=\"font-size: 1.3em;\" align=center><a href=\"calce.php\">Prosegui</a></td></tr>\n";

    //la mia idea è quella di fare una scermata unica per tutto il carrello e faccio apparire le ricghe mi mi interessano in base al tipo documento

    echo "</table>\n";
    
    echo "<table class=\"classic_bordo\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"><tr>";
    $_colspan ++;
    echo "<td width=\"30\" align=\"center\" class=\"tabella\">Riga</span></td>";
    $_colspan ++;
    echo "<td width=\"70\" align=\"center\" class=\"tabella\">Codice</span></td>";
    if (($_tdoc == "ddtacq") OR ( $_tdoc == "fornitore"))
    {
        echo "<td width=\"70\" align=\"center\" class=\"tabella\">Art For</span></td>";
        $_colspan ++;
    }
    $_colspan ++;
    echo "<td width=\"400\" align=\"center\" class=\"tabella\">Descrizione</span></td>";
    $_colspan ++;
    echo "<td width=\"30\" align=\"center\" class=\"tabella\">Um</span></td>";
    $_colspan ++;
    echo "<td width=\"70\" align=\"center\" class=\"tabella\">Q.t&agrave;</span></td>";

    if (($_tdoc == "conferma") OR ( $_tdoc == "fornitore"))
    {
        $_colspan ++;
        echo "<td width=\"70\" align=\"center\" class=\"tabella\">Q.ta cons.</span></td>";
        $_colspan ++;
        echo "<td width=\"70\" align=\"center\" class=\"tabella\">Q.ta Prep.</span></td>";
        $_colspan ++;
        echo "<td width=\"20\" align=\"center\" class=\"tabella\">Re</span></td>";
    }


    $_colspan ++;
    echo "<td width=\"70\" align=\"center\" class=\"tabella\">Listino</span></td>";
    $_colspan++;
    echo "<td width=\"50\" align=\"center\" class=\"tabella\">Sconti</span></td>";

    if (($_tdoc == "ddtacq") OR ( $_tdoc == "fornitore"))
    {
        $_colspan ++;
        echo "<td width=\"50\" align=\"center\" class=\"tabella\">Tot Riga</span></td>";
        $_colspan ++;
        echo "<td width=\"50\" align=\"center\" class=\"tabella\">Agg.</span></td>";
    }
    elseif ($_tdoc == "conferma")
    {
        $_colspan ++;
        echo "<td width=\"50\" align=\"center\" class=\"tabella\">Consegna</span></td>";
    }


    $_colspan ++;
    echo "<td width=\"70\" align=\"center\"a class=\"tabella\">Azione</span></td>";
    echo "</tr>";

    $_colore = "nero";

    foreach ($result AS $dati_carr)
    {
        printf("<tr><form action=\"corpo.php\" method=\"POST\">");
        //in caso ci siano quantita uguali a zero le tingo di rosso.
        if (($dati_carr['quantita'] == 0) OR ( $dati_carr['quantita'] == 0.00))
        {
            $_color = "red";
        }
        else
        {
            $_color = "";
        }

        if ($_colore == "bianco")
        {
            $_colorbg = "#FFFFFF";
            $_colore = "nero";
        }
        else
        {
            $_colorbg = "#EEEEEE";
            $_colore = "bianco";
        }

        echo "<td width=\"30\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><a name=\"$dati_carr[rigo]\"><input type=\"radio\" name=\"rigo\" value=\"$dati_carr[rigo]\"checked>$dati_carr[rigo]</a></td>\n";
        printf("<td width=\"70\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>\n", $dati_carr['articolo']);
        if (($_tdoc == "ddtacq") OR ( $_tdoc == "fornitore"))
        {
            printf("<td width=\"70\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>\n", $dati_carr['artfor']);
        }

        printf("<td width=\"400\" bgcolor=\"$_colorbg\" height=\"1\" align=\"left\" class=\"tabella_elenco\"><font color=$_color>%s</td>\n", $dati_carr['descrizione']);
        printf("<td width=\"30\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=$_color>%s</td>\n", $dati_carr['unita']);
        printf("<td width=\"70\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=$_color>%s</td>\n", $dati_carr['quantita']);

        if (($_tdoc == "conferma") OR ( $_tdoc == "fornitore"))
        {
            echo "<td width=\"50\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=green>\n";
            if ($dati_carr['qtaevasa'] != "0.00")
            {
                echo $dati_carr['qtaevasa'];
            }

            echo " </td>\n";
            echo "<td width=\"50\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=blue>\n";
            if ($dati_carr['qtaestratta'] != "0.00")
            {
                echo $dati_carr['qtaestratta'];
            }

            echo " </td>\n";
            echo "<td width=\"20\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=blue>\n";
            if ($dati_carr['rsaldo'] != "NO")
            {
                echo $dati_carr['rsaldo'];
            }

            echo " </td>\n";
        }
        printf("<td width=\"70\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=$_color>%s</td>\n", $dati_carr['listino']);
        printf("<td width=\"70\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=$_color>%s+%s+%s</td>\n", $dati_carr['sca'], $dati_carr['scb'], $dati_carr['scc']);

        if (($_tdoc == "ddtacq") OR ( $_tdoc == "fornitore"))
        {
            printf("<td width=\"50\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=$_color>%s</td>\n", $dati_carr['totriga']);
            printf("<td width=\"50\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=$_color>%s</td>\n", $dati_carr['agg']);
        }
        elseif ($_tdoc == "conferma")
        {
            printf("<td width=\"50\" bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=$_color>%s</td>\n", $dati_carr['consegna']);
        }

        // Calcolo L'iva
        $_ivariga = $dati_carr['iva'];
        $_castiva[$_ivariga] = ($_castiva[$_ivariga] + $dati_carr['totriga']);

        $_articolo = $dati_carr['articolo'];
        if (($_articolo >= "000800") AND ( $_articolo <= "001255"))
        {
            $_pesorame = $_pesorame + $dati_carr['quantita'];
        }

        $_pesotot = $_pesotot + $dati_carr['peso'];
        $_totprovv = $_totprovv + $dati_carr['totrigaprovv'];


        if (($_tdoc == "FATTURA") AND ( $dati_carr['articolo'] != "vuoto"))
        {
            //chiediamo agli articoli se l'articolo e esente magazzino
            $queryes = "SELECT esma FROM articoli WHERE articolo = '$dati_carr[articolo]'";
            //eseguiamo la query
            $result2 = $conn->query($queryes);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore $_cosa Query = $queryes - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_errori['errori'] = "NO";
            }
            foreach ($result2 AS $_datiesma);

            if ($_datiesma['esma'] == "SI")
            {
                printf("<td class=\"tabella_elenco\"><input type=\"submit\" name=\"azione\" value=\"modifica\">");
                printf("<input type=\"submit\" name=\"azione\" value=\"cancella\" onclick=\"if(!confirm('Sicuro di voler eliminare la riga ?')) return false;\" ></form></td>");
                printf("</tr>");
            }
            else
            {
                echo "<td colspan\"2\" class=\"tabella_elenco\">Riga non modificabile</td></tr></form>";
            }
        }
        else
        {

            printf("<td><input type=\"submit\" name=\"azione\" value=\"modifica\">");
            printf("<input type=\"submit\" name=\"azione\" value=\"cancella\" onclick=\"if(!confirm('Sicuro di voler eliminare la riga ?')) return false;\" ></form></td>");
            printf("</tr>");
        }



        // calcolo castello iva
        $_imponibile = $_imponibile + $dati_carr['totriga'];
    }

    echo "</table>\n";
    echo "<table class=\"classic_bordo\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"><tr>";
    //la somma del colspan la facciamo in base alle colonne inserire..

    echo "<tr><td colspan=\"2\"><hr></td></tr>";

    printf("<tr><td colspan=\"2\" align=right><b> Imponibile = %s</b></tr>", $_imponibile);
    echo "<tr><td style=\"font-size: 1.2em;\" align=center><a href=\"seleziona.php\">Aggiungi riga</a></font></td>\n";
    echo "<td style=\"font-size: 1.2em;\" align=center><a href=\"calce.php\">Prosegui</a></font></td>\n";

    echo "</tr>";

    echo " <tr><td>";

    $_totiva = gestione_iva("iva_corpo", $_castiva, $_totiva, $_imponibile, $_spese, $_impostaspese, $_datareg);

    echo "Per un totale di Iva = $_totiva </td>";

    echo "<td >";

    // visualizzo eventuale peso
    echo "Peso filo rame = $_pesorame <br> Peso totale apparente = $_pesotot <br> Totale provvigioni = $_totprovv</td></tr>";


    if ($_tdoc == "FATTURA")
    {
        echo "<form action=\"corpo.php\" method=\"POST\">\n";
        echo "<tr><td colspan=\"2\" align=\"center\" class=\"tabella_elenco\"><br>Per inserire una descrizione con un solo prezzo a cottimo <input type=\"submit\" name=\"azione\" value=\"cottimo\" onclick=\"if(!confirm('Confermi la fatturazione a cottimo ?')) return false;\"><br></form></td>\n";
        echo "</tr>\n";
    }



    // passo le sessioni
    $_SESSION['importi'] = $_imponibile;
    $_SESSION['castiva'] = $_castiva;
    $_SESSION['totiva'] = $_totiva;
    $_SESSION['pesotot'] = $_pesotot;
    $_SESSION['provvigioni'] = $_totprovv;


    echo "</table></table>\n";

    return;
    //annulla_doc($_dove, $_tdoc, $_anno, $_ndoc);
}

//-------------------------------------------------------------------------------------------
//

function schermata_quantita($_tdoc, $_cosa, $_messaggio, $_rigo, $_articolo, $_artfor, $_descrizione, $_unita, $_qta, $_listino, $_sca, $_scb, $_scc, $_netto, $_peso, $_iva, $_consegna, $_qtaevasa, $_qtaestratta, $_qtasaldo, $_rsaldo, $_agg, $_anno, $_suffix, $_ndoc)
{
    //inquesta funzione dobbiamo caricare il tipo del documento per poter prelevare la lunghezza del campo descrizione..
    global $conn;
    global $_percorso;
    global $dec;


    //un'altro parametro che mi serve è sapere se siamo in modifica oppure in inserimento..
    global $_calce;


    //selezioniamo il tipo di documento
    $datidoc = tabella_stampe_layout("singola", $_percorso, $_SESSION[tdoc]);

    if (($_tdoc == "conferma") OR ( $_tdoc == "fornitore"))
    {
        // la conferma ordine ed l'ordine fornitore sono la stessa cosa..
        //disabilito le colonne che non servono durante un inserimento.
        printf("<form action=\"corpo.php#%s\" method=\"POST\">", $_rigo);
        echo "<table border=\"0\" align=\"center\" cellspacing=\"1\" width=\"90%\">";
        echo "<tr><td colspan=\"11\" align=center><font color=\"red\" size=\"2\">$_messaggio</font> <input type=\"radio\" name=\"articolo\" value=\"$_articolo\" checked>$_articolo</td></tr>";

        echo "<tr><td colspan=\"11\"> Inserire la quantit&agrave; gli sconti ecc., si puo anche modificare la descrizione dell'articolo</td></tr>";

        if ($_tdoc == "fornitore")
        {
            echo "<td align=\"left\" class=\"tabella\">Art. forn</span></td>";
        }


        echo "<td align=\"left\" colspan=\"6\" class=\"tabella\">Descrizione</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Um</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Q.t&agrave;</span></td>";
        if ($_calce == "calce2")
        {
            echo "<td align=\"center\" class=\"tabella\">Q.tà Evasa</span></td>";
            echo "<td align=\"center\" class=\"tabella\">Q.tà Prep.</span></td>";
            echo "<td align=\"center\" class=\"tabella\">Riga chiusa</span></td>";
        }
        echo "</tr>";
        echo "<tr>";

        if ($_tdoc == "fornitore")
        {
            echo "<td align=center><input type=\"text\" name=\"artfor\" value=\"$_artfor\" size=\"15\" maxlength=\"25\"></td>\n";
        }

        if ($_cosa == "vuota")
        {
            echo "<td align=left colspan=\"6\"><input type=\"text\" autofocus name=\"descrizione\" value=\"$_descrizione\" size=\"60\" maxlength=\"$datidoc[ST_DESCRIZIONE_LC]\"></td>\n";
            echo "<td align=center><input type=\"text\" name=\"unita\" value=\"$_unita\" size=\"2\" maxlength=\"2\"></td>\n";
            echo "<td align=center><input type=\"text\" name=\"qta\" value=\"$_qta\" size=\"6\" maxlength=\"18\"></td>\n";
        }
        else
        {
            echo "<td align=left colspan=\"6\"><input type=\"text\" name=\"descrizione\" value=\"$_descrizione\" size=\"60\" maxlength=\"$datidoc[ST_DESCRIZIONE_LC]\"></td>\n";
            echo "<td align=center><input type=\"text\" name=\"unita\" value=\"$_unita\" size=\"2\" maxlength=\"2\"></td>\n";
            echo "<td align=center><input type=\"text\" autofocus name=\"qta\" value=\"$_qta\" size=\"6\" maxlength=\"18\"></td>\n";
        }

        if ($_calce == "calce2")
        {
            echo "<td align=center><input type=\"radio\" name=\"qtaevasa\" value=\"$_qtaevasa\" checked>$_qtaevasa</td>\n";
            echo "<td align=center><input type=\"text\" name=\"qtaestratta\" value=\"$_qtaestratta\" size=\"6\" maxlength=\"18\"></td>\n";

            if ($_rsaldo == "SI")
            {
                echo "<td align=center><input type=\"checkbox\" name=\"rsaldo\" value=\"SI\" checked></td>\n";
            }
            else
            {
                echo "<td align=center><input type=\"checkbox\" name=\"rsaldo\" value=\"SI\"></td>\n";
            }
        }

        echo "</tr>\n";
        echo "<tr><td colspan=\"11\">&nbsp;</td></tr>\n";
        echo "<tr>\n";
        echo "<td align=\"center\" colspan=\"1\" class=\"tabella\">Listino</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Sc A</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Sc B</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Sc C</span></td>";

        echo "<td align=\"center\" class=\"tabella\">Peso uni</span></td>";
        
        echo "<td align=\"center\" class=\"tabella\">IVA</span></td>";
        
        
        echo "<td align=\"center\" colspan=\"2\" class=\"tabella\">Consegna</span></td>";
        echo "<td align=\"center\" colspan=\"3\" class=\"tabella\">Pos.</span></td>";

        echo "</tr><tr>\n";
        echo "<td align=\"center\" colspan=\"1\"><input type=\"text\" name=\"listino\" value=\"$_listino\" size=\"10\" maxlength=\"18\"></td>\n";
        echo "<td align=center><input type=\"text\" name=\"sca\" value=\"$_sca\" size=\"4\" maxlength=\"6\"></td>\n";
        echo "<td align=center><input type=\"text\" name=\"scb\" value=\"$_scb\" size=\"4\" maxlength=\"6\"></td>\n";
        echo "<td align=center><input type=\"text\" name=\"scc\" value=\"$_scc\" size=\"4\" maxlength=\"6\"></td>\n";
        echo "<td align=center><input type=\"text\" name=\"peso\" value=\"$_peso\" size=\"4\" maxlength=\"6\"></td>\n";
        echo "<td align=center>\n";
        
        tabella_aliquota("elenca_select_numeri", $_iva, "iva");
        
        echo "</td>\n";
        echo "<td align=center colspan=\"2\" ><input type=\"text\" name=\"consegna\" value=\"$_consegna\" size=\"10\" maxlength=\"10\"></td>\n";
        // apriamo una varibile, nel caso sia una riga vuota oppure una modifica che si voglia cambiare riga..
        if ($_cosa == "modifica")
        {
            echo "<td align=\"center\" colspan=\"3\">Da: <input type=\"radio\" name=\"rigo\" value=\"$_rigo\" checked>$_rigo - A: <input type=\"text\" name=\"rigo_dest\" size=\"4\" maxlenght=\"6\" value=\"$_rigo\"></td>";
        }
        elseif ($_cosa == "vuota")
        {
            echo "<td align=\"center\" colspan=\"4\"><input type=\"text\" name=\"rigo\" size=\"4\" maxlenght=\"6\" value=\"$_rigo\"></td>";
        }
        else
        {
            echo "<td align=\"center\" colspan=\"4\"><input type=\"text\" name=\"rigo\" size=\"4\" maxlenght=\"6\" value=\"$_rigo\"></td>";
        }

        echo "</tr>\n";
        if ($_cosa == "modifica")
        {
            echo "<td align=center colspan=\"11\"><br><input type=\"submit\" name=\"azione\" value=\"aggiorna\"></td></tr>";
        }
        else
        {
            //indichiamo l'azione..
            echo "<td align=center colspan=\"11\"><br><input type=\"submit\" name=\"azione\" value=\"inserisci\"></td></tr>";
        }
    }
    elseif ($_tdoc == "ddtacq")
    {
        printf("<form action=\"corpo.php#%s\" method=\"POST\">", $_rigo);
        echo "<br><table border=\"0\" align=\"center\" cellspacing=\"5\" width=\"90%\"><tr>";

        echo "<td align=\"center\"class=\"tabella\">Codice Fornitore</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Codice interno</span></td>";
        echo "<td align=\"left\" colspan=\"4\" class=\"tabella\">Descrizione</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Um</span></td></tr>";

        printf("<td align=center><input type=\"text\" name=\"artfor\" value=\"%s\" size=\"15\" maxlength=\"25\"></td>", $_artfor);
        echo "<td align=center><input type=\"radio\" name=\"articolo\" value=\"$_articolo\" checked>$_articolo</td>\n";
        printf("<td colspan=\"4\" align=left><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"50\" maxlength=\"80\"></td>", $_descrizione);
        printf("<td align=center><input type=\"text\" name=\"unita\" value=\"%s\" size=\"2\" maxlength=\"2\"></td></tr>", $_unita);

        echo "<tr><td align=\"center\" >Q.t&agrave;</span></td>";
        echo "<td align=\"center\" >Listino</span></td>";
        echo "<td align=\"center\" >Sc A</span></td>";
        echo "<td align=\"center\" >Sc B</span></td>";
        echo "<td align=\"center\" >Sc C</span></td>";
        echo "<td align=\"center\" >Netto acq.</span></td>";
        echo "<td align=\"center\" >Azione</span></td>";
        echo "</tr>";
        echo "<tr>";
        printf("<td align=center><input type=\"text\" autofocus name=\"qta\" value=\"%s\" size=\"8\" maxlength=\"18\"></td>", $_qta);
        printf("<td align=\"center\"><input type=\"text\" name=\"listino\" value=\"%s\" size=\"8\" maxlength=\"18\"></td>", $_listino);
        printf("<td align=center><input type=\"text\" name=\"sca\" value=\"%s\" size=\"4\" maxlength=\"6\"></td>", $_sca);
        printf("<td align=center><input type=\"text\" name=\"scb\" value=\"%s\" size=\"4\" maxlength=\"6\"></td>", $_scb);
        printf("<td align=center><input type=\"text\" name=\"scc\" value=\"%s\" size=\"4\" maxlength=\"6\"></td>", $_scc);
        printf("<td align=center><input type=\"text\" name=\"netto\" value=\"%s\" size=\"8\" maxleght=\"10\"></td>", $_netto);
        if ($_cosa == "modifica")
        {
            echo "<td align=center colspan=\"8\"><input type=\"submit\" name=\"azione\" value=\"aggiorna\"></td></tr>";
        }
        else
        {
            //indichiamo l'azione..
            echo "<td align=center ><input type=\"submit\" name=\"azione\" value=\"inserisci\"></td></tr>";
        }


        // apriamo una varibile, nel caso sia una riga vuota oppure una modifica che si voglia cambiare riga..
        if ($_cosa == "modifica")
        {
            echo "<td align=\"center\" colspan=\"7\"><span class=\"tabella_elenco\">Sposta rigo Da: <input type=\"radio\" name=\"rigo\" value=\"$_rigo\" checked>$_rigo - A: <input type=\"text\" name=\"rigo_dest\" size=\"4\" maxlenght=\"6\" value=\"$_rigo\"></td>";
        }
        else
        {
            echo "<td align=\"center\" colspan=\"7\"><span class=\"tabella_elenco\">Inserisci eventuale numero rigo <input type=\"text\" name=\"rigo\" size=\"4\" maxlenght=\"6\" value=\"$_rigo\"></td>";
        }

        echo "</tr>\n";

        echo "<tr><td colspan=4 align=\"right\"><span class=\"tabella_elenco\">Si desidera aggiornare anche l'anagrafica articoli ?&nbsp;</span></td>";
        echo "<td colspan=3 align=\"left\">\n";

        if ($_agg == "SI")
        {
            print("<input type=\"checkbox\" name=\"agg\" value=\"SI\" CHECKED></td></tr>");
        }
        else
        {
            print( "<input type=\"checkbox\" name=\"agg\" value=\"SI\"></td></tr>");
        }
    }
    else
    {
        //creiamo un form
        printf("<form action=\"corpo.php#%s\" method=\"POST\">", $_rigo);
        echo "<tr><td colspan=\"11\" align=center><font color=\"red\" size=\"2\">$_messaggio</font></td></tr>";

        echo "<tr><td colspan=\"11\"> Inserire la quantit&agrave; gli sconti ecc., si puo anche modificare la descrizione dell'articolo</td></tr>";

        echo "<td align=\"left\" colspan=\"4\" class=\"tabella\">Descrizione</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Um</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Q.t&agrave;</span></td>";
        echo "<td align=\"center\" colspan=\"1\" class=\"tabella\">Listino</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Sc A</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Sc B</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Sc C</span></td>";
        echo "</tr>";
        echo "<tr>";
        if ($_cosa == "vuota")
        {
            echo "<td align=left colspan=\"4\"><input type=\"text\" autofocus name=\"descrizione\" value=\"$_descrizione\" size=\"60\" maxlength=\"$datidoc[ST_DESCRIZIONE_LC]\"></td>\n";
            echo "<td align=center><input type=\"text\" name=\"unita\" value=\"$_unita\" size=\"2\" maxlength=\"2\"></td>\n";
            echo "<td align=center><input type=\"text\" name=\"qta\" value=\"$_qta\" size=\"6\" maxlength=\"18\"></td>\n";
        }
        else
        {
            echo "<td align=left colspan=\"4\"><input type=\"text\" name=\"descrizione\" value=\"$_descrizione\" size=\"60\" maxlength=\"$datidoc[ST_DESCRIZIONE_LC]\"></td>\n";
            echo "<td align=center><input type=\"text\" name=\"unita\" value=\"$_unita\" size=\"2\" maxlength=\"2\"></td>\n";
            echo "<td align=center><input type=\"text\" autofocus name=\"qta\" value=\"$_qta\" size=\"6\" maxlength=\"18\"></td>\n";
        }

        echo "<td align=\"center\" colspan=\"1\"><input type=\"text\" name=\"listino\" value=\"$_listino\" size=\"10\" maxlength=\"18\"></td>\n";
        echo "<td align=center><input type=\"text\" name=\"sca\" value=\"$_sca\" size=\"4\" maxlength=\"6\"></td>\n";
        echo "<td align=center><input type=\"text\" name=\"scb\" value=\"$_scb\" size=\"4\" maxlength=\"6\"></td>\n";
        echo "<td align=center><input type=\"text\" name=\"scc\" value=\"$_scc\" size=\"4\" maxlength=\"6\"></td>\n";

        echo "</tr>\n";
        echo "<tr><td colspan=\"11\">&nbsp;</td></tr>\n";
        echo "<tr>\n";

        echo "<td align=\"center\" >Peso uni</span></td>";
        echo "<td align=\"center\" >IVA</span></td>";
        echo "<td align=\"center\" >Consegna</span></td>";
        echo "<td align=\"center\" colspan=\"4\">Pos.</span></td>";
        echo "<td align=\"center\" colspan=\"3\">Codice</span></td>";
        echo "</tr><tr>\n";
        echo "<td align=center><input type=\"text\" name=\"peso\" value=\"$_peso\" size=\"4\" maxlength=\"6\"></td>\n";
        echo "<td align=center>\n";
        
        tabella_aliquota("elenca_select_numeri", $_iva, "iva");
        
        echo "</td>\n";
        echo "<td align=center><input type=\"text\" name=\"consegna\" value=\"$_consegna\" size=\"10\" maxlength=\"10\"></td>\n";
        // apriamo una varibile, nel caso sia una riga vuota oppure una modifica che si voglia cambiare riga..
        if ($_cosa == "modifica")
        {
            echo "<td align=\"center\" colspan=\"4\">Da: <input type=\"radio\" name=\"rigo\" value=\"$_rigo\" checked>$_rigo - A: <input type=\"text\" name=\"rigo_dest\" size=\"4\" maxlenght=\"6\" value=\"$_rigo\"></td>";
            echo "<td align=center colspan=\"3\"><input type=\"radio\" name=\"articolo\" value=\"$_articolo\" checked>$_articolo</td>\n";
        }
        elseif ($_cosa == "vuota")
        {
            echo "<td align=\"center\" colspan=\"4\"><input type=\"text\" name=\"rigo\" size=\"4\" maxlenght=\"6\" value=\"$_rigo\"></td>";
            echo "<td align=\"center\" colspan=\"3\"><input type=\"radio\" name=\"articolo\" value=\"vuoto\" checked>Vuoto</td>\n";
        }
        else
        {
            echo "<td align=\"center\" colspan=\"4\"><input type=\"text\" name=\"rigo\" size=\"4\" maxlenght=\"6\" value=\"$_rigo\"></td>";
            echo "<td align=center colspan=\"3\"><input type=\"radio\" name=\"articolo\" value=\"$_articolo\" checked>$_articolo</td>\n";
        }

        echo "</tr>\n";
        if ($_cosa == "modifica")
        {
            echo "<td align=center colspan=\"8\"><br><input type=\"submit\" name=\"azione\" value=\"aggiorna\"></td></tr>";
        }
        else
        {
            //indichiamo l'azione..
            echo "<td align=center colspan=\"8\"><br><input type=\"submit\" name=\"azione\" value=\"inserisci\"></td></tr>";
        }
    }
}

//-----------------------------------------------

/**
 *  Questa funzione serve per vedere se l'articolo che si va ad ordinare è già stato ordinato..
 * il programma crea una piccola tabellina esplicando i muovienti
 * @global $conn parametri connessione
 * @param type $_cosa non si sa mai
 * @param type $_articolo codice articolo
 */
function impegno_articolo($_cosa, $_articolo)
{
    global $conn;
    global $_percorso;

    // proviamo a vedere se l'articolo cercato e gia stato ordinato presso un'altro fornitore oppure non e ancora stato conseganto.
    // verifico se il cliente ha conferme d'ordine inevase
    // avviso su monitor se ci sono e quali
    $query = sprintf("select * from of_dettaglio INNER JOIN of_testacalce ON of_dettaglio.ndoc=of_testacalce.ndoc where articolo=\"%s\" and status != 'evaso' AND rsaldo != 'SI' ORDER BY of_dettaglio.ndoc", $_articolo);

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "motore_doc_pdo.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    if ($result->rowCount() > 0)
    {

        echo "<table width=\"60%\" cellspacing=\"0\" cellpadding=\"0\" border=\"1\" align=\"center\">";

        echo "<tr><td colspan=\"6\" align=\"center\" class=\"tabella\">Il seguente Articolo risulta gi&agrave; in ordine con i seguenti muovimenti.</span></td></tr>";
        echo "<tr><td>anno</td><td>ndoc</td><td>Fornitore</td><td>ordinati</td><td>saldati</td><td>Status</td></tr>";

        foreach ($result AS $datico)
        {
            echo "<tr><td>$datico[anno]</td><td>$datico[ndoc]</td><td>$datico[utente]</td><td>$datico[quantita]</td><td>$datico[qtasaldo]</td><td>$datico[status]</tr>\n";
        }

        echo "</table>";
    }
}

//---------------------------------------

/** Funzione che mi gestische il carrello dei documenti
 * @cosa = azzera_sessione elimina la sessione dal carrello.. restiruisce OK o NO
 * @cosa = leggi_id = legge tutto il carrello per id ordine per rigo
 * 
 * @return arrey
 */
function tabella_doc_basket($_cosa, $id, $_rigo, $_anno, $_suffix, $_ndoc, $_utente, $_articolo, $_parametri)
{
    global $conn;
    global $_percorso;
    global $dec;

    //require_once $_percorso."librerie/motore_anagrafiche.php";

    if ($_cosa == "azzera_sessione")
    {
        $query = sprintf("delete from doc_basket where sessionid = \"%s\"", $id);

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_doc_pdo.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }

        $return = $_errori;
    }
    elseif ($_cosa == "delete_rigo")
    {
        //funzione mi elimina la riga richiesta

        $query = sprintf("DELETE from doc_basket where sessionid=\"%s\" and rigo=\"%s\"", $id, $_rigo);

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_doc_pdo.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }

        $return = $_errori;
    }
    elseif ($_cosa == "inserisci")
    {
        //la funzione mi inserisce gli articoli nel carrello..

        $_rigo_dest = $_parametri['rigo_dest'];
        $_qta = $_parametri['qta'];
        $_qtaestratta = $_parametri['qtaestratta'];
        $_qtaevasa = $_parametri['qtaevasa'];
        $_rsaldo = $_parametri['rsaldo'];

        //dobbiamo anche fare i conti
        //ora Partiamo con la gestione dei prezzi e delle quantità
        //facciamo prima la quantità e poi i prezzi..
        #Qui iniziamo a evadere parzialmente l'ordine..
        //  calcolo la quantit�


        if (($_rigo < "1.0") AND ( $_rigo != ""))
        {
            //impossibile cambiare rigo la lasciamo normale
            echo "<center><font color=\"RED\">Impossibile Inserire il rigo in quanto è inferiore a 1.0 <BR> si prega ditornare indiero e modificare il numero</font>\n";
            exit;
        }
        
        if($_rigo != "")
        {
            //se la riga è diversa dobbiamo cambiare la riga ma prima bisogna verificare che la stessa sia disponibile
            $query = "SELECT * FROM doc_basket where sessionid=\"$id\" AND rigo=\"$_rigo\"";

            $result = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore_doc_pdo.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_errori['errori'] = "NO";
            }

            if ($result->rowCount() > 0)
            {
                //impossibile cambiare rigo la lasciamo normale
                
                echo "<center><font color=\"RED\">Impossibile cambiare rigo in quanto lo stesso risulta occupato</font>\n";
                exit;
            }
        }
  
               $_qtasaldo = $_qta - $_qtaevasa;


                if ($_rsaldo == "SI")
                {
                    $_qtasaldo = "0.00";
                }
                elseif (($_qtasaldo - $_qtaestratta) <= "0.00")
                {
                    $_rsaldo = "SI";
                    #$_qtasaldo = "0.00";
                }
                else
                {
                    $_rsaldo = "NO";
                }

                if ($_qta = "0.00")
                {
                    $_rsaldo = "NO";
                }


                $_peso = $_parametri['peso'] * $_qtasaldo;


                if ($_articolo != "vuoto")
                {
                    //calcolo le provvigioni
                    //vado a prendermele
                    //nel caso la provvigione non venga passata la cerco
                    $_provvart = tabella_articoli("provvart", $_articolo, $_parametri);
                }

                if ($_articolo == "vuoto")
                {
                    $_rsaldo = "NO";
                }

                //RICHIEDO GLI SCONTI ED IL PREZZO NETTO
                $_nettovendita = sconti($_parametri['listino'], $_parametri['sca'], $_parametri['scb'], $_parametri['scc']);

                $_totriga = round(($_nettovendita * $_qtasaldo), $dec);
                $_provvnetto = number_format((($_nettovendita * $_provvart) / 100), $dec, '.', '');
                $_totrigaprovv = $_provvnetto * $_qtasaldo;

                $query = sprintf("insert into doc_basket( sessionid, rigo, anno, suffix, ndoc, utente, articolo, artfor, descrizione, unita, quantita,
	    qtaevasa, qtaestratta, qtasaldo, rsaldo, listino, sca, scb, scc, nettovendita, totriga, iva, totrigaprovv, peso, consegna, agg )
	     values( \"%s\", \"%s\",\"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",
	     \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $id, $_rigo, $_anno, $_suffix, $_ndoc, $_utente, $_articolo, $_parametri['artfor'], $_parametri['descrizione'], $_parametri['unita'], $_parametri['qta'], $_qtaevasa, $_qtaestratta, $_qtasaldo, $_rsaldo, $_parametri['listino'], $_parametri['sca'], $_parametri['scb'], $_parametri['scc'], $_nettovendita, $_totriga, $_parametri['iva'], $_totrigaprovv, $_peso, $_parametri['consegna'], $_parametri['agg']);

                $result = $conn->exec($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "motore_doc_pdo.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                    $_errori['errori'] = "NO";
                }
                else
                {
                    $_errori['errori'] = "OK";
                }

        $return = $_errori;
    }
    elseif ($_cosa == "leggi_singola")
    {

        $query = sprintf("select * from doc_basket where sessionid=\"%s\" and rigo=\"%s\"", $id, $_rigo);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_doc_pdo.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }

        //restituisco l'array completo..
        //
			//'

        $return = $result->fetch(PDO::FETCH_ASSOC);
        
    }
    elseif ($_cosa == "travasa")
    {
        //la funzione mi inserisce gli articoli nel carrello..

        $query = sprintf("insert into doc_basket( sessionid, rigo, anno, suffix, ndoc, utente, articolo, artfor, descrizione, unita, quantita,
	    qtaevasa, qtaestratta, qtasaldo, rsaldo, listino, sca, scb, scc, nettovendita, totriga, iva, totrigaprovv, peso, consegna )
	     values( \"%s\", \"%s\",\"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",
	     \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\")", $id, $_rigo, $_anno, $_suffix, $_ndoc, $_utente, $_articolo, $_parametri['artfor'], $_parametri['descrizione'], $_parametri['unita'], $_parametri['quantita'], $_parametri['qtaevasa'], $_parametri['qtaestratta'], $_parametri['qtasaldo'], $_parametri['rsaldo'], $_parametri['listino'], $_parametri['scva'], $_parametri['scvb'], $_parametri['scvc'], $_parametri['nettovendita'], $_parametri['totriga'], $_parametri['iva'], $_parametri['totrigaprovv'], $_parametri['peso'], $_parametri['consegna']);

        //echo $query;

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa= $query - $_errore[2]";
            $_errori['files'] = "motore_doc_pdo.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }

        $return = $_errori;
    }
    elseif ($_cosa == "update")
    {

        //commutiamo le richieste;
        $_rigo_dest = $_parametri['rigo_dest'];
        $_qta = $_parametri['qta'];
        $_qtaestratta = $_parametri['qtaestratta'];
        $_qtaevasa = $_parametri['qtaevasa'];
        $_rsaldo = $_parametri['rsaldo'];
        $_tdoc = $_SESSION['tdoc'];

        if ($_qtaestratta != "0.00")
        {

            if (($_tdoc == "conferma") OR ( $_tdoc == "fornitore"))
            {
                $_ndoc = $_SESSION['ndoc'];
                $_SESSION['status'][$_ndoc] = "parziale";
            }
        }

        //per prima cosa verifichiamo il cambio della riga articolo
        //	verifichiamo se chiediamo il cambio della riga
        if ($_rigo != $_rigo_dest)
        {

            if ($_rigo_dest < "1.0")
            {
                //impossibile cambiare rigo la lasciamo normale
                echo "<center><font color=\"RED\">Impossibile Inserire il rigo in quanto è inferiore a 1.0 <BR> si prega ditornare indiero e modificare il numero</font>\n";
                exit;
            }
            else
            {
                //se la riga è diversa dobbiamo cambiare la riga ma prima bisogna verificare che la stessa sia disponibile
                $query = "SELECT * FROM doc_basket where sessionid=\"$id\" AND rigo=\"$_rigo_dest\"";

                $result = $conn->query($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "motore_doc_pdo.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                    $_errori['errori'] = "NO";
                }



                if ($result->rowCount() > 0)
                {
                    //impossibile cambiare rigo la lasciamo normale
                    $_rigo_dest = $_rigo;
                    echo "<center><font color=\"RED\">Impossibile cambiare rigo in quanto lo stesso risulta occupato</font>\n";
                    exit;
                }
                else
                {
                    //aggiorno il rigo di destinazione poi aggioniamo la riga..
                    //se la riga è diversa dobbiamo cambiare la riga ma prima bisogna verificare che la stessa sia disponibile
                    $query = "UPDATE doc_basket SET rigo='$_rigo_dest'  where sessionid='$id' AND rigo='$_rigo'";

                    $result = $conn->query($query);

                    if ($conn->errorCode() != "00000")
                    {
                        $_errore = $conn->errorInfo();
                        echo $_errore['2'];
                        //aggiungiamo la gestione scitta dell'errore..
                        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                        $_errori['files'] = "motore_doc_pdo.php";
                        scrittura_errori($_cosa, $_percorso, $_errori);
                        $_errori['errori'] = "NO";
                    }
                    else
                    {
                        //cambiamo il numero del rigo

                        $_rigo = $_rigo_dest;
                    }
                }
            }
        }


        //ora Partiamo con la gestione dei prezzi e delle quantità
        //facciamo prima la quantità e poi i prezzi..
        #Qui iniziamo a evadere parzialmente l'ordine..
        //  calcolo la quantit�
        $_qtasaldo = $_qta - $_qtaevasa;


        if ($_rsaldo == "SI")
        {
            $_qtasaldo = "0.00";
        }
        elseif (($_qtasaldo - $_qtaestratta) <= "0.00")
        {
            $_rsaldo = "SI";
            #$_qtasaldo = "0.00";
        }
        else
        {
            $_rsaldo = "NO";
        }

        if ($_articolo != "vuoto")
        {
            //calcolo le provvigioni
            //vado a prendermele
            //nel caso la provvigione non venga passata la cerco
            $_provvart = tabella_articoli("provvart", $_articolo, $_parametri);
        }

        if ($_articolo == "vuoto")
        {
            $_rsaldo = "NO";
        }

        //RICHIEDO GLI SCONTI ED IL PREZZO NETTO
        $_nettovendita = sconti($_parametri['listino'], $_parametri['sca'], $_parametri['scb'], $_parametri['scc']);

        $_totriga = round(($_nettovendita * $_qtasaldo), $dec);
        $_provvnetto = number_format((($_nettovendita * $_provvart) / 100), $dec, '.', '');

        if ($_qtasaldo != "0.00")
        {
            $_peso = $_parametri['peso'] * $_qtasaldo;
            $_totrigaprovv = $_provvnetto * $_qtasaldo;
        }
        else
        {
            $_peso = $_parametri['peso'] * $_qta;
            $_totrigaprovv = $_provvnetto * $_qta;
        }

        $query = sprintf("update doc_basket SET descrizione=\"%s\", unita=\"%s\", quantita=\"%s\", qtaevasa = \"%s\", qtaestratta=\"%s\", qtasaldo=\"%s\", rsaldo=\"%s\",
				listino=\"%s\", sca=\"%s\", scb=\"%s\", scc=\"%s\", nettovendita=\"%s\", totriga=\"%s\", iva=\"%s\", totrigaprovv=\"%s\",
				peso=\"%s\", agg=\"%s\", consegna=\"%s\", rsaldo=\"%s\" WHERE sessionid=\"%s\" AND rigo=\"%s\" AND anno=\"%s\" AND ndoc=\"%s\"", $_parametri['descrizione'], $_parametri['unita'], $_parametri['qta'], $_qtaevasa, $_qtaestratta, $_qtasaldo, $_rsaldo, $_parametri['listino'], $_parametri['sca'], $_parametri['scb'], $_parametri['scc'], $_nettovendita, $_totriga, $_parametri['iva'], $_totrigaprovv, $_peso, $_parametri['agg'], $_parametri['consegna'], $_rsaldo, $id, $_rigo, $_anno, $_ndoc);

        //echo $query;
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore update doc_basket funz. update Query = $query - $_errore[2]";
            $_errori['files'] = "motore_doc_pdo.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = 'OK';
        }

        $return = $_errori;
    }//chiusura funzioni..
    else
    {
        //else è sempre una lista per sicurezza..

        $query = sprintf("select * from doc_basket where sessionid=\"%s\" order by rigo", $id);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_doc_pdo.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori = $result;
        }

        $return = $_errori;
    }



    return $return;
}

/**
 * funzione che mi permette di modificare il documento e mi inserisce il tutto nel carrello
 *
 */
function modifica_documento($_cosa, $id, $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc)
{
//passo la variabile globale
    global $conn;
    global $_percorso;
    global $dec;

    if ($_cosa != "Parziale")
    {

        //azzeriamo il bascket

        $errori = tabella_doc_basket("azzera_sessione", $id, $_rigo, $_anno, $_suffix, $_ndoc, $_utente, $_articolo, $_parametri);
    }

    //setto lo status del documento primario prima della modifica in modo che in caso di annullo del parziale sappiamo come reimpostarlo
    //imposto la sessione con i documenti
    $_SESSION['status'][$_ndoc] = status_documento("leggi_status", $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc, $_form_action, $_azione, $_status);

    //echo $dati_status['status'];
    //echo $_SESSION['status'][$_ndoc];
    // Cambio il modo d'uso del documento e lo setto a in'uso..

    $_errori = status_documento("cambia", $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc, $_form_action, $_azione, "in-uso");


    //qua divido le due strade..

    if ($_tdoc == "ddtacq")
    {
        //selezioniamo il documento dal magazzino..

        $result = seleziona_documento("leggi_tutto", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

        foreach ($result AS $dati2)
        {

            if ($a == "")
            {
                $dati_doc['utente'] = $dati2['utente'];
            }

            //per caricare l'articolo del fornitore in magazzino
            //dobbiamo mettere quello inerente al fornitore caricato.

            if ($dati2['utente'] == $dati2['fornitore'])
            {
                $_artfor = $dati2['artfor'];
            }
            elseif ($dati2['utente'] == $dati2['fornitore2'])
            {
                $_artfor = $dati2['artfor2'];
            }
            else
            {
                $_artfor = "";
            }


            // Stringa contenente la query di inserimento dati 14 variabili
            // inserisco i dati nella tabella del dettaglio bolle
            @$_nettoelistino = $dati2['valoreacq'] / $dati2['qtacarico'];

            //carichiamo tutto nel bascket..

            $dati2['listino'] = $_nettoelistino;
            $dati2['nettovendita'] = $_nettoelistino;

            $dati2['quantita'] = $dati2['qtacarico'];
            $dati2['totriga'] = $dati2['valoreacq'];

            $errori = tabella_doc_basket("travasa", $id, $dati2['rigo'], $_anno, $_suffix, $_ndoc, $dati2['utente'], $dati2['articolo'], $dati2);

            // Esegue la query...
            if ($errori['errori'] != "OK")
            {
                echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
                $_return = "errore inserimento bascket";
            }
            else
            {
                $_return = "1";
            }

            $_a = 1;
        }
    }
    else
    {

        //facciamo l'arta parte della medaglia..
        //
		//
		//leggo la testata del documento

        $dati_doc = seleziona_documento("leggi_riga_testata", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

        // ora proseguo...
        #leggo e riporto tutto il documento nel carrello
        // prendo tutta dal dettaglio documenti
        //leggiamo il corpo..

        $result = seleziona_documento("leggi_corpo", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);


        foreach ($result AS $dati2)
        {

            //mettiamo una sicurezza per il recupero dei documenti vecchi..
            if (($dati2['articolo'] != "vuoto") AND ( $dati2['rsaldo'] != "SI") AND ( $dati2['qtasaldo'] == "0.00") AND ( $dati2['qtaevasa'] == "0.00"))
            {
                //Vuoldire che il documento è vecchio o che ci solo degli errori..

                $dati2['qtasaldo'] = $dati2['quantita'];
                //echo "ciao gente $dati2[qtasaldo]<br>";
            }

            if ($_tdoc == "fornitore")
            {
                $dati2['scva'] = $dati2['scaa'];
                $dati2['scvb'] = $dati2['scab'];
                $dati2['scvc'] = $dati2['scac'];
                $dati2['nettovendita'] = $dati2['nettoacq'];
            }
            // Stringa contenente la query di inserimento dati 14 variabili
            // inserisco i dati nella tabella del dettaglio
            //Inseriamo il tutto nel bascket

            $errori = tabella_doc_basket("travasa", $id, $dati2['rigo'], $dati2['anno'], $dati2['suffix'], $dati2['ndoc'], $dati2['utente'], $dati2['articolo'], $dati2);

            if ($errori['errori'] != "OK")
            {
                echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
                $_return = "errore inserimento bascket";
            }
            else
            {
                $_return = "1";
            }
        }
    }

    //	se invece va tutto bene
    if ($_return == "1")
    {
        $_risultato['verifica'] = "tutto_ok";
        $_risultato['utente'] = $dati_doc['utente'];
    }

    $_risultato['tdoc'] = $dati_doc['tdoc'];

    //}
    return $_risultato;
}

//-----------------------------------------------
//verifica privacy;
function privacy($dati)
{
    if ($dati['privacy'] == "NO" OR $dati['codfisc'] == "")
    {
        echo "<tr>";
        echo "<td width=\"100\" height=\"1\" align=\"center\" class=\"tabella\"></td>";

        echo "<tr><td align=\"center\"><font size=\"2\">Secondo il decreto Bersani e il ministro Rodota'</font></span></td></tr>";
        echo "<tr><td align=\"center\"><font size=\"4\">Il seguente cliente a uno dei due campi vuoti<br> campo privacy = $dati[privacy] codice fiscale = $dati[codfisc]</font></span></td></tr>";
        echo "<tr><td align=\"center\"><font color=RED >Continuare ? <a href=\"annulladoc.php\"> NO </a>  - <a href=\"privacydoc.php?forza=SI\">SI</a></font></td></tr>";
        //echo "</table>";
        echo "<form action=\"annulladoc.php\" method=\"POST\">";
        echo "<tr><td colspan=\"10 \" align=\"center\" class=\"tabella_elenco\"><br>Per annullare l'operazione  <input type=\"submit\" name=\"azione\" value=\"annulla\"></form></td>";
        echo "</tr>";
        echo "</table>";
        exit;
    }
}

//-----------------------------------------------
//sicuramente le prima che facciamo è quella delle conferme ordine..


function tabella_co_dettaglio($_cosa, $_anno, $_ndoc, $_rigo, $_status, $_articolo, $_parametri)
{
    global $conn;
    global $_percorso;
    global $dec;


    if ($_cosa == "aggiorna_estratta")
    {
        $query = "UPDATE co_dettaglio SET qtaestratta=qtaestratta + $_parametri  where anno='$_anno' AND ndoc='$_ndoc' AND articolo='$_articolo' ORDER BY rigo ASC limit 1";
        echo $query . "<br>\n";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }

        $return = $errori;
    }
    else
    {
        //classico elenco..
    }






    return $return;
}

//funzion che mi crea il prezzo netto partendo dal lordo
function sconti($_listino, $_sca, $_scb, $_scc)
{
    global $dec;
//funzione di generazione prezzo netto
    $_nettovendita = $_listino;

    if ($_sca != 0)
        $_nettovendita = ($_listino - number_format(((($_listino * $_sca) / 100)), $dec, '.', ''));
    if ($_scb != 0)
        $_nettovendita = ($_nettovendita - number_format(((($_nettovendita * $_scb) / 100)), $dec, '.', ''));
    if ($_scc != 0)
        $_nettovendita = ($_nettovendita - number_format(((($_nettovendita * $_scc) / 100)), $dec, '.', ''));

    return $_nettovendita;
}

//------------------------------------------------------------------------------------------------------------------------------------------
//funzione che mi genera le schermte html..
function schermata_seleziona($_tipo, $_tdoc)
{
    global $conn;
    global $TERMINAL_CODE;

    //schermata seleziona
    if ($_tipo == "ord_for")
    {
        //schermata acquisto
        echo "<table cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=\"center\">\n";
        echo "<tr><td colspan=\"7\" align=\"center\"> Cerca Codice da Ordinare </td></tr><tr>";
        echo "<td align=\"left\" class=\"tabella\">Codice Interno</span></td>";
        echo "<td width=\"100\" align=\"left\" class=\"tabella\">Art. Fornitore</span></td>";
        echo "<td align=\"left\" class=\"tabella\">Cerca Sicuro</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Cerca Testo</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Vai</span></td>";
        echo "<td align=\"center\" class=\"tabella\">&nbsp;Ricerca un &nbsp;</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Inserisci una</span></td>";
        echo "</tr>";
        echo "<tr><br><form action=\"quantita.php\" method=\"POST\">\n";
        echo "<td align=left><input type=\"text\" autofocus name=\"codice\" size=\"14\" maxlength=\"14\"></td>\n";
        echo "<td align=left><input type=\"text\" name=\"artfor\" size=\"15\" maxlength=\"15\"></td>\n";
        echo "<td align=left><input type=\"submit\" value=\"Cerca\"></td></form>";
        echo "<br><form action=\"risultatoart.php\" method=\"POST\">\n";
        echo "<td align=left><input type=\"text\" name=\"descrizione\" size=\"30\" maxlength=\"30\"></td>";
        echo "<td align=left><input type=\"submit\" value=\"Cerca\"></form>";

        echo "<td align=center><a href=\"ricercart.php\">&nbsp;Articolo&nbsp;</a></td>";
        echo "<td align=center><a href=\"quantita.php?cosa=vuota\">riga vuota</a></td></tr></table>\n";

        if ($TERMINAL_CODE == "SI")
        {

            terminale_barre("form", "quantita_barre.php", "7");
        }
    }
    elseif ($_tipo == "ddt_acq")
    {
        //schermata acquisto
        echo "<table cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=\"center\">\n";
        echo "<tr><td colspan=\"7\" align=\"center\"> Cerca Codice Caricare </td></tr><tr>";
        echo "<td width=\"100\" align=\"left\" class=\"tabella\">Art. Fornitore</span></td>";
        echo "<td align=\"left\" class=\"tabella\">Codice Interno</span></td>";
        echo "<td align=\"left\" class=\"tabella\">Cerca Sicuro</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Cerca Testo</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Vai</span></td>";
        echo "<td align=\"center\" class=\"tabella\">&nbsp;Ricerca un &nbsp;</span></td>";
        echo "<td align=\"center\" class=\"tabella\">Inserisci una</span></td>";
        echo "</tr>";
        echo "<tr><br><form action=\"quantita.php\" method=\"POST\">\n";
        echo "<td align=left><input type=\"text\" autofocus name=\"artfor\" size=\"15\" maxlength=\"15\"></td>\n";
        echo "<td align=left><input type=\"text\" name=\"codice\" size=\"15\" maxlength=\"20\"></td>\n";
        echo "<td align=left><input type=\"submit\" value=\"Cerca\"></td></form>";
        echo "<br><form action=\"risultatoart.php\" method=\"POST\">\n";
        echo "<td align=left><input type=\"text\" name=\"descrizione\" size=\"30\" maxlength=\"30\"></td>";
        echo "<td align=left><input type=\"submit\" value=\"Cerca\"></form>";

        echo "<td align=center><a href=\"ricercart.php\">&nbsp;Articolo&nbsp;</a></td>";
        echo "<td align=center><a href=\"quantita.php?cosa=vuota\">riga vuota</a></td></tr></table>\n";

        if ($TERMINAL_CODE == "SI")
        {

            terminale_barre("form", "quantita_barre.php", "7");
        }
    }
    else
    {
        //else
        if (( $_tdoc == "FATTURA") or ( $_tdoc == "NOTA DEBITO"))
        {
            // seleziono solo il metodo spesa e basta
            echo "<table cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=\"center\">\n";
            echo "<tr><td align=\"center\" class=\"tabella\">Inserisci una</span></td>";
            echo "<td align=\"center\" class=\"tabella\">Ricerca Articolo</span></td>";
            echo "</tr>";
            echo "<td align=center><a href=\"quantita.php?cosa=vuota\">Nuova riga</a></td>";
            echo "<td align=center><a href=\"ricercart.php\">&nbsp;Articolo&nbsp;</a></td>";
            echo "</tr></table>";
        }
        else
        {
            // seleziona.....
            echo "<table cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=\"center\">\n";
            echo "<tr><td colspan=\"7\">Selezionare un articolo da inserire nel corpo documento</td></tr>\n";
            echo "<td width=\"100\" align=\"left\" class=\"tabella\">Codice</span></td>\n";
            echo "<td align=\"left\" class=\"tabella\">Cod. Barre</span></td>\n";
            echo "<td align=\"left\" class=\"tabella\">Cerca Sicuro</span></td>\n";
            echo "<td align=\"center\" class=\"tabella\">Cerca Testo</span></td>\n";
            echo "<td align=\"center\" class=\"tabella\">Vai</span></td>\n";
            echo "<td align=\"center\" class=\"tabella\">&nbsp;Ricerca un &nbsp;</span></td>\n";
            echo "<td align=\"center\" class=\"tabella\">Inserisci una</span></td>\n";
            echo "</tr><tr>\n";
            echo "<form action=\"quantita.php\" method=\"POST\">\n";
            echo "<td align=left><input type=\"text\" autofocus name=\"codice\" size=\"15\" maxlength=\"15\"></td>\n";
            echo "<td align=left><input type=\"text\" name=\"codbar\" size=\"14\" maxlength=\"14\"></td>\n";
            echo "<td align=left><input type=\"submit\" value=\"Cerca\"></td></form>\n";
            echo "<br><form action=\"risultatoart.php\" method=\"POST\">\n";
            echo "<td align=left><input type=\"text\" name=\"descrizione\" size=\"30\" maxlength=\"30\"></td>\n";
            echo "<td align=left><input type=\"submit\" value=\"Cerca\"></form>\n";
            echo "<td align=center><a href=\"ricercart.php\">&nbsp;Articolo&nbsp;</a></td>\n";
            echo "<td align=center><a href=\"quantita.php?cosa=vuota\">riga vuota</a></td></tr></table>\n";

            if ($TERMINAL_CODE == "SI")
            {
                terminale_barre("form", "quantita_barre.php", "7");
            }
        }
    }
}

//--------------------------------------------------------------------------------------------------------------------------
//schermata visualizza documento
function schermata_visualizza($_cosa, $dati_ute, $dati_doc, $_archivio, $_anno, $_suffix, $_ndoc, $_imponibile)
{
//funzione che mi visualizza il documento a video
    global $conn;
    global $_percorso;
    global $dec;

    if ($_cosa == "intestazione")
    {
        echo "<table class=\"classic\" border=\"1\" align=\"center\" width=\"100%\">\n";
        echo "<tr>\n";
        echo "<td width=\"50%\" bgcolor=\"#FFFFFF\" valign=\"top\" align=\"left\">\n";
        echo "<i>Spett.le</i>&nbsp; $dati_doc[utente]<br>\n";
        echo "$dati_ute[ragsoc]<br>\n";
        echo "$dati_ute[indirizzo]<br>\n";
        echo "$dati_ute[cap]&nbsp; $dati_ute[citta]&nbsp;($dati_ute[prov])<br>\n";
        echo "P.I.&nbsp;$dati_ute[piva]\n";
        echo "</td>\n";
        echo "<td width=\"50%\" bgcolor=\"#ffFFFF\" valign=\"top\" align=\"left\">\n";
        echo "<i><b>Destinazione</i><br>\n";
        echo "$dati_doc[dragsoc]</b><br>\n";
        echo "$dati_doc[dragsoc2]</b><br>\n";
        echo "$dati_doc[dindirizzo]<br>\n";
        echo "$dati_doc[dcap]&nbsp; $dati_doc[dcitta]&nbsp;( $dati_doc[dprov])\n";
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        if ($dati_doc['tdoc'] == "ddtacq")
        {

            echo "<table class=\"classic\" border=\"1\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
            echo "<tr>\n";
            echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Tipo documento</i></font><br><input type=\"radio\" value=\"$dati_doc[tdoc]\" name=\"tdoc\" checked> $dati_doc[tdoc] </td>\n";
            echo "<td bg color=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Numero fattura fornitore</i></font><br>$dati_doc[fatturacq]</td>\n";
            echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"arial\" size=\"2\" valign=\"top\"><i>Pagina</i><br></font></td>\n";
            echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"arial\" size=\"2\" valign=\"top\"><i>Documento N.</i></font><br><b><font face=\"arial\" size=\"3\">\n";
            echo "<input type=\"radio\" name=\"suffix\" value=\"$dati_doc[suffix]\" checked >$dati_doc[suffix] - <input type=\"radio\" name=\"ndoc\" value=\"$dati_doc[ndoc]\" checked >$dati_doc[ndoc]/<input type=\"radio\" name=\"anno\" value=\"$dati_doc[anno]\" checked>$dati_doc[anno]</b><br>Rev. n. $dati_doc[rev]</font></td>\n";
            echo "</tr><tr>\n";
            echo "<td bg color=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Protocollo iva </i></font><br><b>$dati_doc[protoiva]</b></font></td>\n";
            echo "<td bg color=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Numero ddt fornitore</i></font><br>$dati_doc[ddtfornitore]</font></td>\n";
            echo "<td bgcolor=\"#FFFFFF\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Status Documento</i></font><br>$dati_doc[status]</font></td>\n";
            echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"arial\" size=\"2\" valign=\"top\"><i>Data Documento</i></font><br><font face=\"arial\" size=\"3\"><b>$dati_doc[datareg]</b></font></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
        }
        else
        {
            echo "<br>\n";
            echo "<table class=\"classic\" border=\"1\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
            echo "<tr>\n";
            echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Tipo documento</i></font><br><input type=\"radio\" value=\"$dati_doc[tdoc]\" name=\"tdoc\" checked> $dati_doc[tdoc] </td>\n";
            echo "<td bg color=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Causale del Trasporto</i></font><br>$dati_doc[causale]</td>\n";
            echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"arial\" size=\"2\" valign=\"top\"><i>Pagina</i><br></font></td>\n";
            echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"arial\" size=\"2\" valign=\"top\"><i>Documento N.</i></font><br><b><font face=\"arial\" size=\"3\">\n";
            echo "<input type=\"radio\" name=\"suffix\" value=\"$dati_doc[suffix]\" checked >$dati_doc[suffix] - <input type=\"radio\" name=\"ndoc\" value=\"$dati_doc[ndoc]\" checked >$dati_doc[ndoc]/<input type=\"radio\" name=\"anno\" value=\"$dati_doc[anno]\" checked>$dati_doc[anno]</b><br>Rev. n. $dati_doc[rev]</font></td>\n";
            echo "</tr><tr>\n";
            echo "<td bg color=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Spedizione in</i></font><br><b>$dati_doc[porto]</b></font></td>\n";
            echo "<td bg color=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Trasporto a cura</i></font><br>$dati_doc[vettore]</font></td>\n";
            if ($dati_doc['id_collo'] != "")
            {
                $_traking = tabella_vettori("id_collo", $_percorso, $dati_doc['vettore'], $_parametri);

                echo "<td bg color=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>ID Collo</i></font><br><a href=\"$_traking$dati_doc[id_collo]\" target=\"_blank\">$dati_doc[id_collo]</font></td>\n";
            }
            else
            {
                echo "<td bg color=\"#FFFFFF\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>ID Collo</i></font><br>$dati_doc[id_collo]</font></td>\n";
            }
            echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"arial\" size=\"2\" valign=\"top\"><i>Data Documento</i></font><br><font face=\"arial\" size=\"3\"><b>$dati_doc[datareg] / $dati_doc[data_scad]</b></font></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
        }
    }

    if ($_cosa == "corpo")
    {


        if ($dati_doc['tdoc'] == "ddtacq")
        {

            echo "<br>\n";
            echo "<table <table class=\"classic\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" width=\"100%\">\n";
            echo "<tr>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"70\">Codice</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"450\" align=\"left\">Descrizione</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" >U.M.</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" >Quantita</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" >Netto acquisto</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" >Tot. Riga</th>\n";
            echo "</tr>\n";
            $query = sprintf("select * from magazzino INNER JOIN articoli ON magazzino.articolo=articoli.articolo where tdoc='ddtacq' and anno=\"%s\" and ndoc=\"%s\" order by rigo", $_anno, $_ndoc);
        }
        else
        {
            echo "<table <table class=\"classic\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
            echo "<tr>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"70\">Codice</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"400\" align=\"left\">Descrizione</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"30\">U.M.</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"70\">Quantit&agrave;</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"70\">Conseg.</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"30\">Riga Chiusa</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"70\">Qta. Preparata</th>\n";

            echo "<th bgcolor=\"#FFFFFF\" width=\"70\">Listino</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"50\" >Sconti</th>\n";
            echo "<th bgcolor=\"#FFFFFF\" width=\"70\">Netto</th>\n";
            if ($dati_doc['tdoc'] == "fornitore")
            {
                echo "<th bgcolor=\"#FFFFFF\" width=\"70\">Tot. Riga</th>\n";
            }
            echo "<th bgcolor=\"#FFFFFF\" width=\"50\">Consegna</th>\n";
            echo "</tr>\n";
            echo "<tr><td bgcolor=\"#FFFFFF\" colspan=\"11\" align=\"left\"><hr></td>\n";
            echo "</tr><tr>\n";



            $query = sprintf("select * from %s where anno=\"%s\" AND suffix=\"%s\" and ndoc=\"%s\" order by rigo", $_archivio[dettaglio], $_anno, $_suffix, $_ndoc);
        }
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }

        foreach ($result AS $dati3)
        {
            if ($_colore == "bianco")
        {
            $_colorbg = "#FFFFFF";
            $_colore = "nero";
        }
        else
        {
            $_colorbg = "#DDDDDD";
            $_colore = "bianco";
        }
        
            $_articolo = $dati3['articolo'];
            if ($_articolo == "vuoto")
            {
                $_articolo = "&nbsp;";
            }

            if ($dati_doc['tdoc'] == "ddtacq")
            {

                $_nettoacq = $dati3['valoreacq'] / $dati3['qtacarico'];
                printf("<tr><td bgcolor=\"$_colorbg\" width=\"70\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><a href=\"../../anagrafica/articoli/visualizzacod.php?codice=%s\">%s&nbsp;</a></td>", $_articolo, $_articolo);
                printf("<td bgcolor=\"$_colorbg\" width=\"450\" height=\"1\" align=\"left\" class=\"tabella_elenco\">%s</td>", stripslashes($dati3['descrizione']));
                printf("<td bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>", $dati3['unita']);
                printf("<td bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>", $dati3['qtacarico']);
                printf("<td bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>", $_nettoacq);
                printf("<td bgcolor=\"$_colorbg\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>", $dati3['valoreacq']);
                printf("</tr>");
                $_imponibile = $_imponibile + $dati3['valoreacq'];
            }
            else
            {
                printf("<tr><td bgcolor=\"$_colorbg\" width=\"70\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><a href=\"../../anagrafica/articoli/visualizzacod.php?codice=%s\">%s&nbsp;</a></td>", $_articolo, $_articolo);
                printf("<td style=\"font-size: 0.8em;\" bgcolor=\"$_colorbg\" width=\"450\" height=\"1\" align=\"left\" class=\"tabella_elenco\">%s</td>", stripslashes($dati3['descrizione']));
                printf("<td bgcolor=\"$_colorbg\" width=\"30\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>", $dati3['unita']);

                if ($dati3['quantita'] != "0.00")
                {
                    $_quantita = $dati3['quantita'];
                }
                else
                {
                    $_quantita = "";
                }

                printf("<td bgcolor=\"$_colorbg\" width=\"70\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>", $_quantita);

                if ($dati3['qtaevasa'] != "0.00")
                {
                    $_qtaevasa = $dati3['qtaevasa'];
                }
                else
                {
                    $_qtaevasa = "";
                }

                printf("<td bgcolor=\"$_colorbg\" width=\"70\" height=\"1\" align=\"center\" class=\"tabella_elenco\"><font color=\"blue\"><b>%s</b></font></td>", $_qtaevasa);

                if ($dati3['rsaldo'] == "SI")
                {
                    $_rsaldo = "SI";
                }
                else
                {
                    $_rsaldo = "";
                }
                printf("<td bgcolor=\"$_colorbg\" width=\"30\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>", $_rsaldo);

                if ($dati3['qtaestratta'] != "0.00")
                {

                    $_qtaestratta = $dati3['qtaestratta'];
                }
                else
                {
                    $_qtaestratta = "";
                }

                printf("<td bgcolor=\"$_colorbg\" width=\"70\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>", $_qtaestratta);

                if ($dati3['listino'] == "0.00")
                {
                    $dati3['listino'] = "";
                }

                printf("<td bgcolor=\"$_colorbg\" width=\"40\" height=\"1\" align=\"right\" class=\"tabella_elenco\">%s</td>", $dati3['listino']);

                if ($dati_doc['tdoc'] == "fornitore")
                {
                    if (($dati3['scaa'] == "0") AND ( $dati3['scac'] == "0") AND ( $dati3['scac'] == "0"))
                    {
                        echo "<td bgcolor=\"$_colorbg\" width=\"50\" height=\"1\" align=\"center\" class=\"tabella_elenco\">&nbsp</td>";
                    }
                    else
                    {
                        printf("<td bgcolor=\"$_colorbg\" width=\"50\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s+%s+%s</td>", $dati3['scaa'], $dati3['scab'], $dati3['scac']);
                    }


                    if ($dati3['nettoacq'] == "0.00")
                    {
                        $dati3['nettoacq'] = "";
                    }

                    printf("<td bgcolor=\"$_colorbg\" width=\"70\" height=\"1\" align=\"right\" class=\"tabella_elenco\">%s</td>", $dati3['nettoacq']);
                }
                else
                {
                    if (($dati3['scva'] == "0") AND ( $dati3['scvc'] == "0") AND ( $dati3['scvc'] == "0"))
                    {
                        echo "<td bgcolor=\"$_colorbg\" width=\"50\" height=\"1\" align=\"center\" class=\"tabella_elenco\">&nbsp</td>";
                    }
                    else
                    {
                        printf("<td bgcolor=\"$_colorbg\" width=\"50\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s+%s+%s</td>", $dati3['scva'], $dati3['scvb'], $dati3['scvc']);
                    }


                    if ($dati3['nettovendita'] == "0.00")
                    {
                        $dati3['nettovendita'] = "";
                    }

                    printf("<td bgcolor=\"$_colorbg\" width=\"70\" height=\"1\" align=\"right\" class=\"tabella_elenco\">%s</td>", $dati3['nettovendita']);
                }



                if ($dati_doc['tdoc'] == "fornitore")
                {
                    printf("<td bgcolor=\"$_colorbg\" width=\"70\" height=\"1\" align=\"right\" class=\"tabella_elenco\">%s</td>", $dati3['totriga']);
                }

                printf("<td bgcolor=\"$_colorbg\" width=\"50\" height=\"1\" align=\"center\" class=\"tabella_elenco\">%s</td>", $dati3['consegna']);
                printf("</tr>");
            }
        }
        echo "<tr><td bgcolor=\"#FFFFFF\" colspan=\"11\" align=\"left\">&nbsp;</td>\n";
        echo "</tr><tr>\n";
        echo "</table>\n";
    }

    if ($_cosa == "calce")
    {
        echo "<table class=\"classic\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
        echo "<tr>\n";
        echo "<td  width=\"40%\" align=\"left\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Aspetto dei Beni</i><br>$dati_doc[aspetto]</font></td>\n";
        echo "<td  width=\"15%\" align=\"center\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Colli n.</i><br>$dati_doc[colli]</td>\n";
        echo "<td  width=\"15%\" align=\"center\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Peso</i><br>$dati_doc[pesotot]</td>\n";
        echo "<td  width=\"15%\" align=\"center\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Spese Varie</i><br>$dati_doc[spesevarie]</td>\n";
        echo "<td  width=\"15%\" align=\"center\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>trasporto</i><br>$dati_doc[trasporto]</td>\n";
        echo "<td width=\"15%\" align=\"center\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Data partenza:</i></td>\n";
        echo "<td  width=\"15%\" align=\"center\"><font face=\"arial\" size=\"1\" valign=\"top\"><i>Ora Partenza:</i><br></td>\n";
        echo "</tr><tr>\n";
        echo "<th colspan=\"7\" width=\"100%\" align=\"left\"><font face=\"arial\" size=\"1\"><i>Annotazioni</i></font><br>$dati_doc[note]</th>\n";
        echo "</tr><tr>\n";
        echo "<th colspan=\"2\" width=\"50%\" align=\"center\">Imponibile euro <i><br>\n";
        if ($dati_doc['tdoc'] == "ddtacq")
        {
            echo $_imponibile;
        }
        else
        {
            echo $dati_doc[totimpo];
        }
        echo "</i></th>\n";
        echo "<th colspan=\"2\" width=\"50%\" align=\"center\">Imposta  <i><br>$dati_doc[totiva]</i></th>\n";
        echo "<th colspan=\"3\" width=\"50%\" align=\"center\">Tot. Documento  <i><br>$dati_doc[totdoc]</i></th>\n";
        echo "</tr></table>\n";
    }

    return $_imponibile;
}

function scrivi_doc($_cosa, $id, $_tdoc, $dati, $_ndoc, $_anno, $_suffix, $_datareg, $_parametri)
{// graffa inizio funzione
// recupero le variabili
    global $conn;
    global $dec;
    global $nomedoc;
    global $IVAMULTI;
    global $ivasis;
    global $DATAIVA;
    $_castiva = $_SESSION['castiva'];
    require_once "lib_html.php";

    //sistemiamo le variabili
    $_azione = $_parametri['scrivi'];
    $_nettomerce = $_SESSION['importi'];
    $_imponibile = $_SESSION['importi'];
    $_totiva = $_SESSION['totiva'];
    $_parametri['rev'] = $_SESSION['rev'];
    
    $_parametri['totprovv'] = $_SESSION['provvigioni'];
    $_parametri['rev'] = $_SESSION['rev'];
   
    
    //selezioniamo il cliente
    $_codutente = $dati['codice'];
    $_parametri['zona'] = $dati['zona'];
    
    $_parametri['datareg'] = $_datareg;
    
    $_parametri['dragsoc'] = addslashes($_parametri['dragsoc']);
    $_parametri['dragsoc2'] = addslashes($_parametri['dragsoc2']);
    $_parametri['dindirizzo'] = addslashes($_parametri['dindirizzo']);
    $_parametri['dcitta'] = addslashes($_parametri['dcitta']);
    $_parametri['id_collo'] = strtoupper($_parametri['id_collo']);
    $_banca = addslashes($_parametri['banca']);
    
    $_spbanca = $_parametri['spbanca'];
    $_spimba = $_parametri['spimba'];
    $_trasporto = $_parametri['trasporto'];
    $_varie = $_parametri['varie'];
    $_scoinco = $_parametri['scoinco'];
    
    
    $_parametri['vettore'] = addslashes($_parametri['vettore']);
 
    
    $_data_scad = cambio_data("us", $_parametri['data_scad']);

    if ($_tdoc == "preventivo")
    {
        $_parametri['notedoc'] = "Scadenza Offerta $_parametri[data_scad]<br>" . addslashes($_parametri['memoart']);
    }
    else
    {
        $_parametri['notedoc'] = addslashes($_parametri['memoart']);
    }

    $_parametri['data_scad'] = cambio_data("us", $_parametri['data_scad']);

    if ($_datareg < $DATAIVA)
    {
        $ivasis = $ivasis - 1;
    }

    //seleziono il documemnto
    //convertiamo il nome documento
    $_archivio = archivio_tdoc($_tdoc);
    //restitusco un arrey con il nome archivioo ed il nome
    #$_archivioo['testacalce'] = $_testacalce;
    #$_archivioo['dettaglio'] = $_dettaglio;
    //gestiamo subito la situazione banca.. se è differente la banca verifichiamo se il post è diverso dalla sessione;
    #selezioniamo dall'anagrafica clienti la banca..
    $_bancavecchia = $dati['banca'];
    #poi confronto con il post appena passato
    #pero il tutto bisogna vedere che io non voglia togliere la banca
    if ($_banca != "")
    {
        if ($_bancavecchia != $_banca)
        {
            //se diversa devo cambiarla
            $datib = tabella_banche("singola_descrizione", $_banca, $_abi, $_cab, $_parametri);

            $_parametri = array_merge($_parametri, $datib);
            $_parametri['banca'] = addslashes($datib['banca']);
        }
        else
        {
            #inserisco i dati del cliente
            $_parametri['banca'] = addslashes($dati['banca']);
            $_parametri['abi'] = $dati['abi'];
            $_parametri['cab'] = $dati['cab'];
            $_parametri['cin'] = $dati['cin'];
            $_parametri['cc'] = $dati['cc'];
            $_parametri['iban'] = $dati['iban'];
            $_parametri['swift'] = $dati['swift'];
            
        }
    }
    else
    {
        #elimino i dati bancari dalla fattura
        $_parametri['banca'] = "";
        $_parametri['abi'] = "";
        $_parametri['cab'] = "";
        $_parametri['cin'] = "";
        $_parametri['cc'] = "";
        $_parametri['iban'] = "";
        $_parametri['swift'] = "";
    }

    //qui dobbiamo fare il conto delle spese, poi passarlo direttamente allafunzione che
    //che mi calcola l'iva differente..
    //calcolo l'imponibile delle spese..
    $_spese = ($_spbanca + $_spimba + $_trasporto + $_varie) - $_scoinco;

    if ($_tdoc == "fornitore")
    {
        $_parametri['imponibile'] = $_nettomerce + $_spese;
        $_parametri['totimposta'] = $_totiva;
    }
    else
    {
        // passo alla funzione il calcolo dell'iva per il documento..

        $_imponibili = gestione_iva("fattura", $_castiva, $_totiva, $_imponibile, $_spese, $dati, $_datareg);

        //in teoria qui abbiamo le ive già calcolate e giuste.
        $_parametri['nettomerce'] = $_nettomerce;
        $_parametri['imponibile'] = $_imponibili['totimpo'];
        $_parametri['totimposta'] = $_imponibili['totiva'];
        
        $_parametri['imponibili'] = $_imponibili;

        #echo $_imponibile;
        #echo $_totimposta;
    }

    $_parametri['totdoc'] = $_parametri['imponibile'] + $_parametri['totimposta'];


    #inizio a vedere la differenza tra inserire ed aggiornare

    if ($_cosa == "inserisci")
    {
        #procedura di inserimento documento.
        $_parametri['status'] = "inserito";

        // verifico la presenza del documento appena generato..
        // onde evitare la simultaneit� del numero e quindi l'errore
        //cerco la riga se c'� l'aggiorno, se non c e la inserisco
        $_disponibilita = seleziona_documento("disponibilita_numero", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

        if ($_disponibilita['errori'] != "OK")
        {
            echo "Nonostante i tentativi il numero risulta occupato.. <br>errore bloccaggio numero<br>Il numero selezionato risulta già occupato..";
            exit;
        }
        

            //dentro qui ci inseriamo il documento..
        //il numero del documento che tocca è:
        $_ndoc = $_disponibilita['ndoc'];

        
        //qui prepariamo tutti i dati dell'array per poterli passare alla funzione di inserimento testata..
        // operiamo solo se il documento non è un ddtacq
        
        if($_tdoc != "ddtacq")
        {
            $testata = gestisci_testata("inserisci", $_codutente, $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_archivio, $_parametri);
        
            if($testata['errori'] != "OK")
            {
                echo "Inserimento documento Fallito..";
                exit;
            }  
        }
        
        
    }
    else
    {
        if ($_SESSION['status'][$_ndoc] != "parziale")
        {
            #procedura di aggiornamento testaca e calce del documento
            $_parametri['status'] = "modificato";
        }
        else
        {
            $_parametri['status'] = $_SESSION['status'][$_ndoc];
        }

        //passiamo alla funzione aggiorna tesrata
        //saltiamo in caso di ddtacq
        if ($_tdoc != "ddtacq")
        {
            $testata = gestisci_testata("aggiorna", $_codutente, $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_archivio, $_parametri);

            if ($testata['errori'] != "OK")
            {
                echo "Aggiornamento documento Fallito..";
                exit;
            }
        }
    }

    //parte dettaglio
    #Verifichiami se è un aggiornamento elimino il bascket altrimenti lo aggiorno

    if ($_cosa == "aggiorna")
    {
        //da corpo eliminiamo il documento esistente per reinserirlo..
        
        $dettaglio = gestisci_dettaglio("elimina", $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc, $_rigo, $_utente, $_codice, $_descrizione, $_iva, $_parametri);
        
        if($dettaglio['errori'] != "OK")
        {
            echo "Eliminazione corpo Fallito..";
            exit;
        }
        
        //eliminiamo anche il magazino e le provvigioni..
        
        
        
        
    }


    $basket = tabella_doc_basket("leggi_id", $id, $_rigo, $_anno, $_suffix, $_ndoc, $_codutente, $_articolo, $_parametri);

    foreach ($basket AS $dati2)
    {
        // per ogni rigo inserisco la cosa..
        //Cambio la variabile per il discorso del basket..
        $dati2['scva'] = $dati2['sca'];
        $dati2['scvb'] = $dati2['scb'];
        $dati2['scvc'] = $dati2['scc'];
        //aggiungo in caso di ddtacq i dati mancanti per le righe:
        if($_tdoc == "ddtacq")
        {
            $dati2['datareg'] = $_datareg;
            $dati2['ddtfornitore'] = $_parametri['ddtfornitore'];
            $dati2['fatturacq'] = $_parametri['fatturacq'];
            $dati2['protoiva'] = $_parametri['protoiva'];
            $dati2['status'] = $_parametri['status'];
        }
        
        $dettaglio = gestisci_dettaglio("inserisci_singola", $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc, $dati2['rigo'], $_codutente, $dati2['articolo'], $dati2['descrizione'], $dati2['iva'], $dati2);
        
        if($dettaglio['errori'] != "OK")
        {
            echo "Inserimento riga corpo Fallito..";
            exit;
        }
        
        
    }
    
    //qui passiamo alla gestione del magazzino e delle provvigioni..
            //gestione del magazzino.. tabella_magazzino..
            //ora non ci resta che inserire il tutto nel magazzino e poi aggiornare le provvigioni degli agenti.
            #la funzione magazzino andrà attivata solo per i docuemnti che sono collegati al magazzino
            #quindi

            if (( $_tdoc == "ddt") OR ( $_tdoc == "ddt_diretto") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == "$nomedoc"))
            {
                //ora aggiorniamo il magazzino
                #la veriabile cosa è collegata con l'inizio quindi si arrangia per aggiornare o inserire.

                $_magazzino = gestisci_magazzino("automatico", $id, $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_codutente, $_tut, $_archivio, $_parametri);

                if ($_magazzino['errori'] != "OK")
                {
                    $_return['descrizione'] = "Si &egrave; verificato un errore nella query inserimento in magazzino:<br>\n\"$query\"\n";
                    $_return['errore'] = "errore";
                }
            }

            //Gestione delle provvigioni in caso di fattura..
            //ora bisogna gestire le provvigioni, che riguardano solo le fatture ed i suoi documenti
            if (( $_tdoc == "FATTURA") OR ( $_tdoc == "NOTA DEBITO") OR ( $_tdoc == "NOTA CREDITO") OR ( $_tdoc == "$nomedoc"))
            {

                if ($_cosa == "aggiorna")
                {
                    #la variabile cosa è legata alle funzioni secondarie
                       $_provvigioni = gestione_provvigioni("aggiorna", $_tdoc, $_anno, $_suffix, $_ndoc, $_parametri['agente'], $_datareg, $_codutente, $_parametri['totdoc'], $_parametri['totprovv']);

                }
                else
                {
                    #la variabile cosa è legata alle funzioni secondarie
                    $_provvigioni = gestione_provvigioni("inserisci", $_tdoc, $_anno, $_suffix, $_ndoc, $_parametri['agente'], $_datareg, $_codutente, $_parametri['totdoc'], $_parametri['totprovv']);

                }
                

                if ($_provvigioni['errori'] != "OK")
                {
                    $_return['descrizione'] = "Si &egrave; verificato un errore nella query inserimento in provvigioni:<br>\n\"$query\"\n";
                    $_return['errore'] = "errore";
                }
            }

    // finisco la funzione di magazzino
    //rilascio le funzioni necessarie
    //ora che è andato tutto a buon fine.. setto le variabili per la stampa..
    $_documento['tdoc'] = "$_tdoc";
    $_documento['ndoc'] = "$_ndoc";
    $_documento['anno'] = "$_anno";
    $_documento['errori'] = $_return;
    $_documento['suffix'] = $_suffix;

    return $_documento;
}

#funzione che mi visualizza dullo schermo il tipo di documento che vado ad importare

function selezione_documento($_start, $_end)
{
    global $nomedoc;

    if (($_start == "ordine") and ( $_end == "conferma"))
    {
        echo "<tr>\n";
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><b>Scegliere il cliente per l'importazione<br><font color=RED>
        <input type=\"radio\" name=\"start\" value=\"$_start\" checked>dall'ordine agente</b>
        <br><input type=\"radio\" name=\"end\" value=\"$_end\" checked>In conferma ordine</span><br>\n";
        echo "</td></tr>\n";
    }
    elseif (($_start == "ordine") and ( $_end == "ddt"))
    {
        echo "<tr>\n";
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><b>Scegliere il cliente per l'importazione<br><font color=RED>
        <input type=\"radio\" name=\"start\" value=\"$_start\" checked>dall'ordine agente</b>
        <br><input type=\"radio\" name=\"end\" value=\"$_end\" checked>In DDT vendita</span><br>\n";
        echo "</td></tr>\n";
    }
    elseif (($_start == "preventivo") and ( $_end == "conferma"))
    {
        echo "<tr>\n";
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><b>Scegliere il cliente per l'importazione<br><font color=RED>
        <input type=\"radio\" name=\"start\" value=\"$_start\" checked>dal Preventivo</b>
        <br><input type=\"radio\" name=\"end\" value=\"$_end\" checked>Alla conferma ordine</span><br>\n";
        echo "</td></tr>\n";
    }
    elseif (($_start == "preventivo") and ( $_end == "ddt"))
    {
        echo "<tr>\n";
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><b>Scegliere il cliente per l'importazione<br><font color=RED>
        <input type=\"radio\" name=\"start\" value=\"$_start\" checked>dal preventivo</b>
        <br><input type=\"radio\" name=\"end\" value=\"$_end\" checked>In DDT vendita</span><br>\n";
        echo "</td></tr>\n";
    }
    elseif (($_start == "conferma") and ( $_end == "ddt"))
    {
        echo "<tr>\n";
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><b>Scegliere il cliente per l'importazione<br><font color=RED>
        <input type=\"radio\" name=\"start\" value=\"$_start\" checked>dalla conferma ordine</b>
        <br><input type=\"radio\" name=\"end\" value=\"$_end\" checked>In DDT vendita</span><br>\n";
        echo "</td></tr>\n";
    }
    elseif (($_start == "conferma") and ( $_end == $nomedoc))
    {
        echo "<tr>\n";
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><b>Scegliere il cliente per l'importazione<br><font color=RED>
        <input type=\"radio\" name=\"start\" value=\"$_start\" checked>della conferma d'ordine</b>
        <br><input type=\"radio\" name=\"end\" value=\"$_end\" checked> In Fattura Immediata</span><br>\n";
        echo "</td></tr>\n";
    }
    elseif (($_start == "fornitore") and ( $_end == "ddtacq"))
    {
        echo "<tr>\n";
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><b>Scegliere il fornitore per l'importazione<br><font color=RED>
        <input type=\"radio\" name=\"start\" value=\"$_start\" checked>dell'ordine fornitore</b>
        <br><input type=\"radio\" name=\"end\" value=\"$_end\" checked> In ddt acquisto</span><br>\n";
        echo "</td></tr>\n";
    }
    elseif (($_start == "ddt_diretto") and ( $_end == "FATTURA"))
    {
        echo "<tr>\n";
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><b>Scegliere il cliente per l'importazione<br><font color=RED>
        <input type=\"radio\" name=\"start\" value=\"$_start\" checked>ddt diretto al cliente</b>
        <br><input type=\"radio\" name=\"end\" value=\"$_end\" checked>A fattura differita</span><br>\n";
        echo "</td></tr>\n";
    }
    else
    {
        echo "<tr>\n";
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><b>Scegliere il cliente per l'importazione<br><font color=RED>
        <input type=\"radio\" name=\"start\" value=\"$_start\" checked>dal d.d.t. di vendita</b>
        <br><input type=\"radio\" name=\"end\" value=\"$_end\" checked>A fattura differita</span><br>\n";
        echo "</td></tr>\n";
    }
}


function Show_ultimo_numero()
{
    global $conn;
    global $_percorso;
    global $_dec;
    global $_tdoc;
    $_archivio = $_SESSION['archivi'];
    $_anno = date('Y');
   
    
      //$query = sprintf("SELECT * FROM %s WHERE anno=\"%s\" AND suffix=\"%s\" order by ndoc DESC LIMIT 1", $_archivio['testacalce'], $_anno, $_POST['tipo_cf']);
      
      if($_POST['tipo_cf'] == "A")
      {
          $query = "SELECT * FROM $_archivio[testacalce] WHERE anno='$_anno' AND suffix='A' order by ndoc DESC limit 1";
      }
      else
      {
          $query = "SELECT * FROM fv_testacalce WHERE anno='2015' AND suffix='$_POST[tipo_cf]' order by ndoc DESC limit 1";
      }
      

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query disponibilita_numero = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        
        //$_codconto .= '<option value="0">Scegli...</option>';

        $dati = $result->fetch(PDO::FETCH_ASSOC);
        
        $dati['ndoc']++;
        
        $_codconto .= '<option value="' . $dati['ndoc'] . '">' . $dati['ndoc'] . '</option>';
        

    
    return $_codconto;
}



//---------------------------------------------------------------------------------------------------------------------------------------------
/** Funzione che mi gestisce il documento velocemente
 * Come cerca numero verifica ecc..
 * @cosa disponibilita_numero funzione che mi da la possibilità di inserire il documento restituisce arrey
 * @cosa seleziona_singolo
 * 
 *  
 * @return arrey['errori'] e ['ndoc']
 * 
 */
function seleziona_documento($_cosa, $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri)
{
    global $conn;
    global $_percorso;
    global $dec;

    if ($_cosa == "disponibilita_numero")
    {
        //bisogna fare tre divisioni..
        //quando si inserisce quando si aggiorna e quando si travasa..
        //cerchiamo se il documento è libero..
        if ($_tdoc == "ddtacq")
        {
            $query = "select * from magazzino where tdoc='ddtacq' AND anno='$_anno' AND ndoc='$_ndoc' ORDER BY ndoc DESC LIMIT 1";
        }
        else
        {
            $query = "SELECT * FROM $_archivio[testacalce] WHERE anno='$_anno' AND suffix='$_suffix' AND ndoc='$_ndoc' LIMIT 1";
            
        }


        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }

        if ($result->rowCount() > 0)
        {
            //se è accupato.. aggiungo un numero e riprovo...
            $_ndoc_n = $_ndoc + 1;
            //cerchiamo se il documento è libero..
            if ($_tdoc == "ddtacq")
            {
                $query = "select * from magazzino where tdoc='ddtacq' AND anno='$_anno' AND ndoc='$_ndoc' ORDER BY ndoc DESC LIMIT 1";
            }
            else
            {
                $query = "SELECT * FROM $_archivio[testacalce] WHERE anno='$_anno' AND suffix='$_suffix' AND ndoc='$_ndoc_n'";
                
            }


            $result = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_errori['errori'] = "NO";
            }

            if ($result->rowCount() > 0)
            {
                //se anche questa volta è accupato.. vuol dire che si è proprio sbagliato.

                echo "<tr><td align=\"center\">Il numero ducumento selezionato risulta gi&agrave; occupato.<br>
                     Per non perdere i dati si consiglia di cliccare qui o tornare indietro e di verificare..</td></tr>\n";
                //   echo "<tr><td align=\"center\">Oppure cercare il primo numero disponibile ? </td></tr> \n";
                exit;
            }
            else
            {
                //camio il numero e lo restituisco.
                $return['ndoc'] = $_ndoc_n;
                $return['errori'] = "OK";
            }
        }
        else
        {
            //vuol dire che il numero è libero.. 
            $return['errori'] = "OK";
            $return['ndoc'] = $_ndoc;
        }
    }
    elseif ($_cosa == "seleziona_singolo")
    {

        if ($_tdoc == "ddtacq")
        {
            $query = sprintf("SELECT * FROM magazzino where tdoc='ddtacq' and ndoc=\"%s\" and anno=\"%s\" ORDER BY rigo", $_ndoc, $_anno);
        }
        else
        {
            $query = "SELECT *, date_format(datareg,'%d-%m-%Y') as datareg, utente FROM $_archivio[testacalce] where ndoc='$_ndoc' and anno='$_anno' AND suffix='$_suffix'";
        }

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query seleziona_singolo= $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            foreach ($result AS $dati)
                ;

            $dati['errori'] = "OK";
        }


        $return = $dati;
    }
    elseif ($_cosa == "leggi_riga_testata")
    {

        if ($_tdoc == "ddtacq")
        {
            $query = "select * from magazzino where tdoc='ddtacq' AND anno='$_anno' and ndoc='$_ndoc'";
        }
        else
        {
            $query = "SELECT * from $_archivio[testacalce] WHERE anno='$_anno' AND suffix='$_suffix' AND ndoc='$_ndoc'";
        }


        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            foreach ($result AS $dati)
                ;

            $dati['errori'] = "OK";
        }


        $return = $dati;
    }
    elseif ($_cosa == "ultimo_numero")
    {

        if ($_tdoc == "ddtacq")
        {
            //$query = sprintf("select * from %s where anno=\"%s\" ORDER BY ndoc DESC LIMIT 1", $_archivio['testacalce'], $_anno);
            $query = "select * from magazzino where tdoc='ddtacq' AND anno='$_anno' ORDER BY ndoc DESC LIMIT 1";
        }
        else
        {
            $query = "SELECT * FROM $_archivio[testacalce] WHERE anno='$_anno' AND suffix='$_suffix' ORDER BY ndoc DESC LIMIT 1";
        }

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            foreach ($result AS $dati)
                ;

            $return = $dati['ndoc'] + 1;
        }
    }
    elseif ($_cosa == "elenco_select")
    {

        if ($_tdoc == "fornitore")
        {
            $query = sprintf("select * from $_archivio[testacalce] INNER JOIN fornitori ON $_archivio[testacalce].utente = fornitori.codice where anno=\"%s\"order by ndoc desc", $_anno);
        }
        else
        {
            // Stringa contenente la query di ricerca... solo dei fornitori
            $query = sprintf("select * from $_archivio[testacalce] INNER JOIN clienti ON $_archivio[testacalce].utente = clienti.codice where anno=\"%s\"order by ndoc desc", $_anno);
        }

        $result = $conn->query($query);
        
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $return = $result;
        }
    }
    else
    {
        //seleziona l'elenco..
        if ($_tdoc == "ddtacq")
        {
            $query = sprintf("select * from magazzino INNER JOIN articoli ON magazzino.articolo=articoli.articolo where tdoc='ddtacq' and anno=\"%s\" and ndoc=\"%s\" order by rigo", $_anno, $_ndoc);
        }
        else
        {
            $query = "select * from $_archivio[dettaglio] WHERE anno='$_anno' AND suffix='$_suffix' AND ndoc='$_ndoc'";
        }

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }

        //restituisco la hiamata per 
        $return = $result;
    }


    return $return;
}

/* funzione che mi permette di sapere lo status del documento
 * ed fi interagire con esso come il forzare oppure altro.
 * la funzione restituisce prosegui o aspetta
 */

function status_documento($_cosa, $_archivio, $_tdoc, $_anno, $_suffix, $_ndoc, $_form_action, $_azione, $_status)
{
//passo la variabile globale
    global $conn;
    global $_percorso;
    global $dec;

    //setto le variabili di scambio

    if ($_azione == "Sicuro")
    {
        $_return = "prosegui";
    }
    elseif ($_azione == "SI")
    {
        $_return = "modifica";
    }
    elseif ($_cosa == "leggi_status")
    {

        //setto lo status del documento primario prima della modifica in modo che in caso di annullo del parziale sappiamo come reimpostarlo

        $query = "SELECT status from $_archivio[testacalce] where anno='$_anno' AND suffix='$_suffix' AND ndoc='$_ndoc'";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }

        foreach ($result AS $dativer)
            ;

        $_return = $dativer['status'];
    }
    elseif ($_cosa == "cambia")
    {
        if ($_tdoc == "ddtacq")
        {
            $query = sprintf("update %s set status='$_status' WHERE status != \"evaso\" AND tdoc='ddtacq' and anno=\"%s\" AND suffix=\"%s\" and ndoc=\"%s\"", $_archivio['testacalce'], $_anno, $_suffix, $_ndoc);
        }
        else
        {
            $query = sprintf("update %s set status='$_status' WHERE status != \"evaso\" AND anno=\"%s\" AND suffix=\"%s\" and ndoc=\"%s\"", $_archivio['testacalce'], $_anno, $_suffix, $_ndoc);
        }

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        else
        {
            $_errori['errori'] = "OK";
        }

        $_return = $_errori;
    }
    else
    {


        //VERIFICO LO STATUS
        // verifica su il documento selezionato � in uso..
        //vediamo che non sia un ddt di carico..
        if ($_tdoc == "ddtacq")
        {
            $query = sprintf("select status from %s where tdoc='ddtacq' and anno=\"%s\" and ndoc=\"%s\"", $_archivio['testacalce'], $_anno, $_ndoc);
        }
        else
        {
            $query = "select status from $_archivio[testacalce] where anno='$_anno' AND suffix='$_suffix' and ndoc='$_ndoc'";
        }

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }

        foreach ($result AS $dativer)
            ;

        $_status = $dativer['status'];

        //ok ora se il documento � in uso chiedo forzatura altrimenti rimando a casa.

        if ($_status == "in-uso")
        {

            printf("<form action=\"$_form_action\" method=\"POST\">");
            echo "<p align=\"center\">Documento <input type=\"radio\" name=\"tdoc\" value=\"$_tdoc\" checked>$_tdoc
	    Numero <input type=\"radio\" name=\"ndoc\" value=\"$_ndoc\" checked>$_ndoc
	     anno <input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>$_anno"
                    . " suff <input type=\"radio\" name=\"suffix\" value=\"$_suffix\" checked>$_suffix</p>\n";
            echo "<p align=\"center\" class=\"tabella_elenco\">ATTENZIONE il documento &egrave; gi&agrave; in uso da un altro utente <br>\n";

            if ($_azione == "Elimina")
            {
                printf("<p align=\"center\" class=\"tabella_elenco\">Sei sicuro di eliminare il documento ?<br><input type=\"submit\" name=\"azione\" value=\"Sicuro\"> - <input type=\"submit\" name=\"azione\" value=\"Annulla\"> </form>");
            }
            else
            {
                printf("<p align=\"center\" class=\"tabella_elenco\">Sei sicuro di Modificare il documento ?<br><input type=\"submit\" name=\"azione\" value=\"SI\"> - <input type=\"submit\" name=\"azione\" value=\"Annulla\"> </form>");
            }
            return;
        }// chiudo blocco..
        elseif ($_status == "evaso")
        {
            echo "<center><h2>IMPOSSIBILE TOCCARE IL DOCUMENTO, PERCHE RISULTA EVASO !!</h2></center>";
            // elimino le sessioni usate
            // molto importante non eliminare le sessioni di lavoro

            
            return;
        }
        else
        {
            // chiedo se sono sicuri di procedere

            printf("<form action=\"$_form_action\" method=\"POST\">");
            echo "<p align=\"center\">Documento <input type=\"radio\" name=\"tdoc\" value=\"$_tdoc\" checked>$_tdoc
	    Numero <input type=\"radio\" name=\"ndoc\" value=\"$_ndoc\" checked>$_ndoc
	     anno <input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>$_anno"
                    . "suff <input type=\"radio\" name=\"suffix\" value=\"$_suffix\" checked>$_suffix</p>\n";

            if ($_azione == "Elimina")
            {
                printf("<p align=\"center\" class=\"tabella_elenco\">Sei sicuro di eliminare il documento ?<br><input type=\"submit\" name=\"azione\" value=\"Sicuro\"> - <input type=\"submit\" name=\"azione\" value=\"Annulla\"> </form>");
            }
            else
            {
                printf("<p align=\"center\" class=\"tabella_elenco\">Sei sicuro di <b>Modificare il documento ?</b><br><input type=\"submit\" name=\"azione\" value=\"SI\"> - <input type=\"submit\" name=\"azione\" value=\"Annulla\"> </form>");
            }
        }

        $_return = "aspetta";
    }// fine else

    return $_return;
}

//-----------------------------------------------------------------------
//funzione che mi permette di caricare un file e mi mostra la maschera di carica
//per tutti le funzioni del terminale a barre
function terminale_barre($_funzione, $_action, $_posizione)
{
//variabile globale
    global $conn;
    global $NOME_FILECODBAR;
    global $_percorso;
    global $dec;

    //    funzione di mostra form
    if ($_funzione == "form")
    {
        echo "<table cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=\"center\">\n";
        echo "<tr><td colspan=\"$_posizione\"><BR><br><center><font size=\"2\">Oppure carica un Ordine da terminale</td></tr>";
        echo "<tr><td colspan=\"$_posizione\"><font size=\"2\">Nome Del file da cercare.. $NOME_FILECODBAR\n";
        #<!--apriamo il form e specifichiamo il tipo di dati e il metodo di invio-->
        echo "<form action=\"$_action\" enctype=\"multipart/form-data\" method=\"post\">\n";
        #<!--settiamo la dimensione massima dei file in byte, nel nostro caso 1MB=1024000byte-->
        echo "<input name=\"MAX_FILE_SIZE\" type=\"hidden\" value=\"1024000\" />\n";
        #echo "File da caricare:\n";
        #<!--campo per la scelta del file-->
        echo "<input id=\"file\" name=\"file\" type=\"file\" />\n";
        #<!--bottone di invio-->
        echo "<input name=\"submit\" type=\"submit\" value=\"Carica\" />\n";
        echo "</form>\n";
        echo "</center>";
        echo "</td></tr></table>\n";
    }
}

?>