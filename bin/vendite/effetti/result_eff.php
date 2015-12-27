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

require "../../librerie/motore_primanota.php";
require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{


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

    if ($_azione != "Annulla")
    {

        $_codutente = $_POST['codutente'];
        $_tipoeff = $_POST['tipoeff'];
        $_numeff = $_POST['numeff'];
        $_annoeff = $_POST['annoeff'];
        $_impeff = $_POST['impeff'];
        $_tipodoc = $_POST['tipodoc'];
        $_annodoc = $_POST['annodoc'];
        $_suffixdoc = $_POST['suffixdoc'];
        $_numdoc = $_POST['numdoc'];
        $_totdoc = $_POST['totdoc'];
        $_status = $_POST['status'];
        $_primanota = $_POST['primanota'];
        $_spese_insoluto = $_POST['spese_insoluto'];


//verifichiamo tutte le date...

        $_checkdate = verifica_data($cosa, $_POST['dataeff']);
        if (($_checkdate['errore'] == "error") OR ( $_POST['dataeff'] == '00-00-0000'))
        {
            echo "<h2>Errore campodata</h2>\n";
            echo "$_checkdate[descrizione] - data effetto $_POST[dataeff]";

            exit;
        }
        $_checkdate = "";


        $_checkdate = verifica_data("scadenza", $_POST['scadeff']);
        if ($_checkdate['errore'] == "error")
        {
            echo "<h2>Errore campodata</h2>\n";
            echo "$_checkdate[descrizione] - scadenza effetto $_POST[scadeff]";

            exit;
        }
        $_checkdate = "";

        if (($_azione == "insoluto") OR ( $_azione == "parziale") OR ( $_azione == "saldato") OR ( $_azione == "richiamato") OR ( $_azione == "riemesso"))
        {
            $_checkdate = verifica_data($cosa, $_POST['datapag']);
            if (($_checkdate['errore'] == "error") OR ( $_POST['datapag'] == '00-00-0000'))
            {
                echo "<h2>Errore campodata</h2>\n";
                echo "$_checkdate[descrizione] - data pagamento $_POST[datapag]";

                exit;
            }
            $_checkdate = "";
        }

        if ($_azione != "Salda")
        {
            $_checkdate = verifica_data($cosa, $_POST['datadoc']);
            if ($_checkdate['errore'] == "error")
            {
                echo "$_checkdate[descrizione] - data documento $_POST[datadoc]";

                exit;
            }
            $_checkdate = "";
        }




        $_dataeff = cambio_data("us", $_POST['dataeff']);
        $_scadeff = cambio_data("us", $_POST['scadeff']);
        $_datadoc = cambio_data("us", $_POST['datadoc']);
        $_datapag = cambio_data("us", $_POST['datapag']);
        $_annopag = cambio_data("anno_it", $_POST['datapag']);

//intestiamo la pagina..
        $dati = tabella_clienti("singola", $_codutente, "");
    }

    //echo $dati['ragsoc'];

    if ($_azione == "Salda")
    {
        if (($CONTABILITA == "SI") AND ( $_primanota == "SI"))
        {
            //leggiamo l'effeto desiderato per vedere se è stato modificato

            $dati_eff = tabella_effetti("leggi_singolo", $_percorso, $_annoeff, $_numeff, $_parametri);
            //ci preleviamo l'anno
            $_anno = cambio_data("anno_us", $dati_eff['datadist']);

            //Prima di iniziare leggiamo il numero di registrazione che tocca..
            $_nreg = tabella_primanota("ultimo_numero", $id, $_annopag, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);


            if (($_status == "insoluto") AND ( $_tipoeff == "3") AND ( $dati_eff['status'] != "insoluto") OR ( $_status == "richiamato"))
            {

                $_impeff = $dati_eff['impeff'];
                //verifichiamo subito se ci sono spese...

                if ($_status != "richiamato")
                {
                    if (($_spese_insoluto != "0.00") AND ( $_spese_insoluto != ""))
                    {
                        $_dare_cli = $_impeff + $_spese_insoluto;
                        $_avere_banca = $_impeff;
                    }
                    else
                    {
                        $_dare_cli = $_impeff;
                        $_avere_banca = $_impeff;
                    }
                    $_conto = $REC_SPESE_BANCARIE;
                    $tconto = "avere";
                    //registriamo anche incontabilità quallo che è successo..
                    // se fa l0insoluto in base alla banca presentato la riapriamo ..

                    $_testo = "Add. Insoluto Fatt. $dati_eff[numdoc] scad. $_scadeff - $dati[ragsoc]";
                }
                else
                {
                    if (($_spese_insoluto != "0.00") AND ( $_spese_insoluto != ""))
                    {
                        $_avere_banca = $_impeff + $_spese_insoluto;
                        $_dare_cli = $_impeff;
                    }
                    else
                    {
                        $_avere_banca = $_impeff;
                        $_dare_cli = $_impeff;
                    }
                    $_conto = $REC_SPESE_BANCARIE;
                    $tconto = "dare";
                    //registriamo anche incontabilità quallo che è successo..
                    // se fa l0insoluto in base alla banca presentato la riapriamo ..

                    $_testo = "Add. Rich. Eff. $dati_eff[numdoc] scad. $_scadeff - $dati[ragsoc]";
                }

                $_parametri['segno'] = "P";
                $_parametri['ndoc'] = $dati_eff['numdoc'];
                $_parametri['anno_doc'] = $dati_eff['annodoc'];
                $_parametri['suffix_doc'] = $dati_eff['suffixdoc'];
                $_parametri['data_doc'] = $dati_eff['datadoc'];
                $_parametri['codpag'] = $dati_eff['modpag'];
                $_parametri['conto'] = "$MASTRO_CLI$dati_eff[codcli]";
                $_parametri['desc_conto'] = $dati['ragsoc'];
                $_parametri['dare'] = $_dare_cli;

                //riapriamo il cliente in dare..
                $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "IN", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);

                $_banca = tabella_banche("singola", $dati_eff['bancadist'], $_abi, $_cab, "");

                //ora riapriamo la bana in avere..
                //m prima cancelliamo il dare
                $_parametri['dare'] = "";
                $_parametri['conto'] = "$MASTRO_BANCHE$dati_eff[bancadist]";
                $_parametri['desc_conto'] = $_banca['banca'];
                $_parametri['avere'] = $_avere_banca;

                $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "IN", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);

                if (($_spese_insoluto != "0.00") AND ( $_spese_insoluto != ""))
                {
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    $_parametri['conto'] = $_conto;
                    $_parametri['desc_conto'] = piano_conti($_conto, "desc_singola");
                    $_parametri[$tconto] = $_spese_insoluto;

                    $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "IN", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);
                }
            }//FINE IF
            
            
            //qui invece se l'effetto è stato presentato in sbf lo spostiamo
            
            if (($_status == "saldato") AND ( $_tipoeff == "3") AND ( $dati_eff['presenta'] == "SI"))
            {
                //eseguiamo la registrazione tra la banca sbf ed il conto
                //il dare va alla banca c/c ed in avere il conto sbf
                //come causale ST, come titolo registrazione Accredito SBF + nome banca
                //prepariamo i dati da inserire..



                $_banca = tabella_banche("singola", $dati_eff['bancadist'], $_abi, $_cab, "");

                $_testo = "Accredito Effetti SBF $_banca[banca]";

                $_parametri['avere'] = "";
                $_parametri['segno'] = "P";
                $_parametri['ndoc'] = $dati_eff['numdoc'];
                $_parametri['anno_doc'] = $dati_eff['annodoc'];
                $_parametri['suffix_doc'] = $dati_eff['suffixdoc'];
                $_parametri['data_doc'] = $dati_eff['datadoc'];
                $_parametri['codpag'] = $dati_eff['modpag'];
                $_parametri['conto'] = "$MASTRO_BANCHE$dati_eff[bancadist]";
                $_parametri['desc_conto'] = $_banca['banca'];
                $_parametri['dare'] = $dati_eff['impeff'];

                //riapriamo il cliente in dare..
                $_result = tabella_primanota("inserisci_singola", $id, $_anno, $_nreg, "ST", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);

                $_piano = tabella_piano_conti("singola", $CONTO_EFFETTI_SBF . $dati_eff['bancadist'], $_parametri);

                //ora riapriamo la bana in avere..
                //m prima cancelliamo il dare
                $_parametri['dare'] = "";
                $_parametri['conto'] = $CONTO_EFFETTI_SBF . $dati_eff['bancadist'];
                $_parametri['desc_conto'] = $_piano['descrizione'];
                $_parametri['avere'] = $dati_eff['impeff'];

                $_result = tabella_primanota("inserisci_singola", $id, $_anno, $_nreg, "ST", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);
        }
            
            
            
            //annullo tutti i parametri..
            $_parametri = "";
        }

        // ora aggiorniamo l'effetto
        //qui bisogna richiamare l'effetto per inserire lo status di saldato ovvero quando è stato pagato..

        //$query = sprintf("UPDATE effetti SET status=\"%s\", datapag=\"%s\", spese=\"%s\" WHERE numeff=\"%s\" and annoeff=\"%s\"", $_status, $_datapag, $_spese_insoluto, $_numeff, $_annoeff);
        $query = sprintf("UPDATE effetti SET status=\"%s\", datapag=\"%s\", spese=\"%s\", conta_anno='$_anno', conta_nreg='$_nreg' WHERE numeff=\"%s\" and annoeff=\"%s\"", $_status, $_datapag, $_spese_insoluto, $_numeff, $_annoeff);
        
        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $_messaggio = "Errore... n. $_numeff non modificato\n";
        }
        else
        {
            $_messaggio = "Ok.. Effetto  n. $_numeff modificato con successo\n";
            
        }
        
        
    }
    elseif ($_azione == "Inserisci")
    {
//    echo $_numeff."\n";
//    echo $_annoeff ."\n";
//    echo $_status . "\n";
//    echo $_datapag . "\n";
//    Prima di inserire l'effetto controlliamo che non esista già'
        //leggiamo l'effeto desiderato per vedere se è stato modificato
        $dati_eff = tabella_effetti("checki_singolo", $_percorso, $_annoeff, $_numeff, $_parametri);

        if ($dati_eff > 0)
        {
            $_messaggio = "<h3>Attenzione l'effetto selezionato &egrave; gi&agrave; esistente.. Tornare indietro per verificare..</h3>\n";
        }
        else
        {
            //altrimenti lo inseriamo..
            $query = sprintf("INSERT INTO effetti (tipoeff,	annoeff, numeff, dataeff, scadeff, impeff, tipodoc, annodoc, numdoc, datadoc, totdoc, codcli,
            modpag, bancapp, abi, cab, cin, cc, status, datapag, contabilita) VALUES
            (\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",
            \"%s\",\"%s\", 'NO')", $_tipoeff, $_annoeff, $_numeff, $_dataeff, $_scadeff, $_impeff, $_tipodoc, $_annodoc, $_numdoc, $_datadoc, $_totdoc, $_codutente, $dati['codpag'], $dati['banca'], $dati['abi'], $dati['cab'], $dati['cin'], $dati['cc'], $_status, $_datapag);

            $result = $conn->query($query);
            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "result_eff.php";
                scrittura_errori($_cosa, $_percorso, $_errori);

                $_messaggio = "Errore... Effetto Non Inserito\n";
            }
            else
            {
                $_messaggio = "Ok.. Effetto Inserito con successo\n";
            }
        }//graffa di fine funzione inserimento..
    }
    elseif ($_azione == "Modifica")
    {
//    echo $_numeff."\n";
//    echo $_annoeff ."\n";
//    echo $_status . "\n";
//    echo $_datapag . "\n";


        if (($CONTABILITA == "SI") AND ( $_primanota == "SI"))
        {
            //leggiamo l'effeto desiderato per vedere se è stato modificato
            $dati_eff = tabella_effetti("leggi_singolo", $_percorso, $_annoeff, $_numeff, $_parametri);


            //ci preleviamo l'anno
            $_anno = $_annoeff;

            //Prima di iniziare leggiamo il numero di registrazione che tocca..
            $_nreg = tabella_primanota("ultimo_numero", $id, $_annopag, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

            if (($_status == "saldato") AND ( $dati_eff['status'] != "saldato"))
            {
                //registriamo anche incontabilità quallo che è successo..
                // se fa l0insoluto in base alla banca presentato la riapriamo ..

                $_testo = "Incasso Fatt. $dati_eff[numdoc] del $_POST[annodoc] - $dati[ragsoc]";

                //verifichiamo subito se ci sono spese...

                if (($_spese_insoluto != "0.00") AND ( $_spese_insoluto != ""))
                {
                    $_avere_cli = $_impeff + $_spese_insoluto;
                }
                else
                {
                    $_avere_cli = $_impeff;
                }

                $_parametri['segno'] = "P";
                $_parametri['ndoc'] = $dati_eff['numdoc'];
                $_parametri['anno_doc'] = $dati_eff['annodoc'];
                $_parametri['data_doc'] = $dati_eff['datadoc'];
                $_parametri['codpag'] = $dati_eff['modpag'];
                $_parametri['conto'] = "$MASTRO_CLI$dati_eff[codcli]";
                $_parametri['desc_conto'] = $dati['ragsoc'];
                $_parametri['avere'] = $_avere_cli;


                //riapriamo il cliente in dare..
                $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "IN", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);



                if ($_POST['banca'] == $CONTO_CASSA)
                {
                    $_parametri['conto'] = $CONTO_CASSA;
                    $_parametri['desc_conto'] = piano_conti($CONTO_CASSA, "desc_singola");
                }
                elseif ($_POST['banca'] == $CONTO_ASSEGNI)
                {
                    $_parametri['conto'] = $CONTO_ASSEGNI;
                    $_parametri['desc_conto'] = piano_conti($CONTO_ASSEGNI, "desc_singola");
                }
                elseif ($_POST['banca'] == $CONTO_COMPENSAZIONI)
                {
                    $_parametri['conto'] = $CONTO_COMPENSAZIONI;
                    $_parametri['desc_conto'] = piano_conti($CONTO_COMPENSAZIONI, "desc_singola");
                }
                else
                {
                    $_banca = tabella_banche("singola", $_POST['banca'], $_abi, $_cab, "");
                    $_parametri['conto'] = "$MASTRO_BANCHE$_POST[banca]";
                    $_parametri['desc_conto'] = $_banca['banca'];
                }

                //ora riapriamo la bana in avere..
                //m prima cancelliamo il dare
                $_parametri['avere'] = "";
                $_parametri['dare'] = $_avere_cli;

                $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "IN", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);
            }

            //azzero i parametri..
            $_parametri = "";
        }



        if ($_status == "parziale")
        {
            //registriamo anche incontabilità quallo che è successo..
            // se fa l0insoluto in base alla banca presentato la riapriamo ..

            $_testo = "Incasso parz. Fatt. $dati_eff[numdoc] del $_POST[annodoc] - $dati[ragsoc]";

            //verifichiamo subito se ci sono spese...
            //Ora decidiamo che l'importo pagato è la differenza tra l'importo originale e quello rimasto.

            if ($dati_eff['impeff'] != $_impeff)
            {
                $_avere_cli = $dati_eff['impeff'] - $_impeff;
            }
            else
            {
                $_avere_cli = $_impeff;
            }

            //verifichiamo che non ci siano altre spese..
            if (($_spese_insoluto != "0.00") AND ( $_spese_insoluto != ""))
            {
                $_avere_cli = $_avere_cli + $_spese_insoluto;
            }


            $_parametri['segno'] = "P";
            $_parametri['ndoc'] = $dati_eff['numdoc'];
            $_parametri['anno_doc'] = $dati_eff['annodoc'];
            $_parametri['data_doc'] = $dati_eff['datadoc'];
            $_parametri['codpag'] = $dati_eff['modpag'];
            $_parametri['conto'] = "$MASTRO_CLI$dati_eff[codcli]";
            $_parametri['desc_conto'] = $dati['ragsoc'];
            $_parametri['avere'] = $_avere_cli;

            //riapriamo il cliente in dare..
            $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "IN", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);


            if ($_POST['banca'] == $CONTO_CASSA)
            {
                $_parametri['conto'] = $CONTO_CASSA;
                $_parametri['desc_conto'] = piano_conti($CONTO_CASSA, "desc_singola");
            }
            elseif ($_POST['banca'] == $CONTO_ASSEGNI)
            {
                $_parametri['conto'] = $CONTO_ASSEGNI;
                $_parametri['desc_conto'] = piano_conti($CONTO_ASSEGNI, "desc_singola");
            }
            elseif ($_POST['banca'] == $CONTO_COMPENSAZIONI)
            {
                $_parametri['conto'] = $CONTO_COMPENSAZIONI;
                $_parametri['desc_conto'] = piano_conti($CONTO_COMPENSAZIONI, "desc_singola");
            }
            else
            {
                $_banca = tabella_banche("singola", $_POST['banca'], $_abi, $_cab, "");
                $_parametri['conto'] = "$MASTRO_BANCHE$_POST[banca]";
                $_parametri['desc_conto'] = $_banca['banca'];
            }

            //ora riapriamo la banca in avere..
            //m prima cancelliamo il dare
            $_parametri['avere'] = "";
            $_parametri['dare'] = $_avere_cli;

            $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "IN", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);

            //azzero i parametri..
            $_parametri = "";
        }





        if ($_status == "riemesso")
        {
            $query = "UPDATE effetti SET tipoeff=\"$_tipoeff\", scadeff=\"$_scadeff\", impeff=\"$_impeff\", tipodoc=\"$_tipodoc\", annodoc=\"$_annodoc\",
                 numdoc=\"$_numdoc\", datadoc=\"$_datadoc\", totdoc=\"$_totdoc\", status=\"$_status\", datapag=\"$_datapag\", spese=\"$_spese_insoluto\" , ndistinta='', bancadist='', datadist='', presenta='NO', contabilita='NO'
			WHERE annoeff=\"$_annoeff\" and numeff=\"$_numeff\"";
        }
        else
        {
            $query = "UPDATE effetti SET tipoeff=\"$_tipoeff\", scadeff=\"$_scadeff\", impeff=\"$_impeff\", tipodoc=\"$_tipodoc\", annodoc=\"$_annodoc\",
                 numdoc=\"$_numdoc\", datadoc=\"$_datadoc\", totdoc=\"$_totdoc\", status=\"$_status\", datapag=\"$_datapag\", spese=\"$_spese_insoluto\" WHERE annoeff=\"$_annoeff\" and numeff=\"$_numeff\"";
        }

        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "result_eff.php";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $_messaggio = "Errore... Effetto non modificato\n";
        }
        else
        {
            $_messaggio = "Ok.. Effetto Modificato con successo.\n";
        }
    }
    else
    {
        //in caso di annulla...
        $_messaggio = "Operazione Annullata con Successo..\n";
    }

    echo "<center>\n";
    echo "<h2>Gestione Effetti</h2>\n";
    echo "<h3>$_messaggio</h3>\n";
    if (($CONTABILITA == "SI") AND ( $_azione != "Annulla"))
    {
        echo "<h3>Operazione inserita in contabilit&agrave; con il numero $_nreg</h3>";
    }

    echo "</center>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>