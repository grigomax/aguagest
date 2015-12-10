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

require $_percorso . "librerie/motore_anagrafiche.php";
require $_percorso . "librerie/motore_doc_pdo.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{


#recupero i post
    $_azione = $_POST['azione'];
    $_codutente = $_POST['codutente'];
    $_anno = $_POST['anno'];
    $_ndoc = $_POST['ndoc'];
    $_tdoc_start = $_POST['start'];
    $_tdoc_end = $_POST['end'];
    $_speseb = $_POST['speseb'];

    $_utente = $_SESSION['datiutente'];
    //giriamo gia la data in americano

    $_datareg = cambio_data("us", $_POST['datareg']);
    $_numero = $_POST['numero'];

    
    //facciamo una piccola verifica se i campi sono pieni o vuoti e se sono corretti..
    
    if(($_ndoc != "") AND ($_anno != "") AND ($_datareg != ""))
    {
        //controlliamo se il numero documento è un intero come anche l'anno
        
        if($_ndoc < "1")
        {
            echo "<br>";
            echo "<center><h3><font color=\"red\">ATTENZIONE.. !</h3>";
            echo "<center><h3><font color=\"red\">Numero documento non valido....</h3>";
            echo "<center><h3>Tornare indietro con il browser e controllare..</h3>";
            exit;
        }
        
        
    }
    else
    {
        
        echo "<br>";
        echo "<center><h3><font color=\"red\">ATTENZIONE.. !</h3>";
        echo "<center><h3><font color=\"red\">Si Dati Documento non validi....</h3>";
        echo "<center><h3>Tornare indietro con il browser e controllare..</h3>";
        exit;
    }

    //prendiamo gli archivi del documento che tocca..

    $_archivi_end = archivio_tdoc($_tdoc_end);
    $_archivi_start = archivio_tdoc($_tdoc_start);

    //carichiamo i dati della angrafica clienti fonritori

    if ($_tdoc_start == "fornitore")
    {
        $_utente = tabella_fornitori("singola", $_codutente, $_parametri);
    }
    else
    {
        $_utente = tabella_clienti("singola", $_codutente, $_parametri);
    }

    /* Verifichiamo che esistino le sessioni prima di inserire un documento..
     * Così si evita che vada dentro un documento sbagliato o che si inserisca un documento vuoto..
     * 
     */

    if ($_azione == "Annulla")
    {
        annulla_doc_vendite($_dove, $_tdoc, $_anno, $_ndoc);

        chiudi_sessioni();
    }
    elseif (!empty($_utente))
    {

        intesta_html($_tdoc_end, "normale", $_utente, "");
        //verifichiamo di avere tutti i dati..

        if (($_POST['numero'] == "") OR ( $_POST['ndoc'] == "") OR ( $_POST['anno'] == "") OR ( $_POST['datareg'] == ""))
        {
            echo "<h2>ATTENZIONE TROVATO CAMPO MANCANTE</h2>";
            echo "Documenti selezionati = " . $_POST['numero'];
            echo "<br>Numero documento da generare = $_POST[ndoc]\n";
            echo "<br>Anno documento da generare = $_POST[anno]\n";
            echo "<br>Data documento da generare giorno = $_POST[datareg]\n";
            exit;
        }





        //bene.. ora passiamo tutto il discorso alla funzione estrai documento..
        //facciamo tutto dentro un unico if in modo da che se una cosa non va non va tutto..
        //farei una funzione che mi blocca il numero di documento che blocca inserendone la testa..
        //prima parte
        $errori = gestisci_testata("blocca_numero", $_codutente, $_tdoc_end, $_anno, $_suffix, $_ndoc, $_datareg, $_archivi_end, $_parametri);

        //$errori['result'] = "OK";

        if ($errori['errori'] != "OK")
        {
            echo "errore bloccaggio numero<br>Il numero selezionato risulta già occupato..";
        }
        else
        {
            //poi passerei ad una funzione che mi inseriesce tutti i corpi del documenti all'interno di quello che devo generare
            //però la stessa funzione mi deve aprire e chiudere il documento start una volta aggiornato il documento end.
            //seconda parte
            //prendiamo il numero corretto..
            $_ndoc = $errori['ndoc'];

            //settiamo il discorso iva..

            if ($_utente['iva'] == "")
            {
                $_iva = $ivasis;
            }
            else
            {
                $_iva = $_utente['iva'];
            }

            //ora dobbiamo inseriere i documento..
            //leggiamo i corpi idei documenti selezionati..

            foreach ($_numero as $_annondoc)
            {
                //dobbiamo dividere i vari campi in quanto abbiamo anche l'anno..

                $_anno_start = substr($_annondoc, '0', '4');
                $_ndoc_start = substr($_annondoc, '4', '6');

                //leggiamo la testata del documento..
                //dinnanzi dobbiamo leggere la testata del documemento per sapere la data di appartenenza di esso..

                $datidoc = gestisci_testata("leggi_singola", $_codutente, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $_datareg, $_archivi_start, $_parametri);

                //scriviamo la prima riga del nuovo documento intestandola con il numero di riferimento del documento prelevato..
                #cambio la data
                $_datait = cambio_data("it", $datidoc[datareg]);

                //inserisco la prima riga del corpo documenti destinatario.. es. d.d.t.  n. del cc..
                $_rigo = $_rigo + 1;

                if ($_tdoc_end != "ddtacq")
                {
                    $_codice = "vuoto";
                    $_descrizione = "NS. $_tdoc_start n. $_ndoc_start del $_datait";
                    //echo "$_descrizione<br>\n";
                    //qui scriviamo la riga nel database..
                    $resut_ins = gestisci_dettaglio("inserisci_singola", $_archivi_end, $_tdoc_end, $_anno, $_suffix, $_ndoc, $_rigo, $_codutente, $_codice, $_descrizione, $_iva, $_parametri);

                    if ($resut_ins['errori'] != "OK")
                    {
                        echo "errore numero 1 inserimento riga descrittiva<br>";
                        exit;
                    }
                }

                //leggiamo il corpo del documento.. dividiamo le vogliamo le righe vuote oppure no.. e comunque togliamo quelle eventuali saldate..

                if ($_POST['righe' . $_annondoc] == "")
                {
                    $daticorpo = gestisci_dettaglio("leggi_corpo", $_archivi_start, $_tdoc_start, $_anno_start, $_suffix_start ,$_ndoc_start, $_rigo, $_codutente, $_codice, $_descrizione, $_iva, "norighe");
                }
                else
                {
                    $daticorpo = gestisci_dettaglio("leggi_corpo", $_archivi_start, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $_rigo, $_codutente, $_codice, $_descrizione, $_iva, $_parametri);
                }

                //elenchiamo le righe..
                //e con un ciclo le inseriamo dentro..
                foreach ($daticorpo AS $datidettaglio)
                {

                    $_rigo = $_rigo + 1;
                    //facciamo apparire anche le righe descrittive..
                    if (($datidettaglio['articolo'] == "vuoto") AND ( $datidettaglio['rsaldo'] != "SI"))
                    {

                        //verifichiamo che non ci siano prezzi a zero..
                        if ($_tdoc_start == "ddt")
                        {
                            if (($datidettaglio['listino'] == "0.00") AND ($datidettaglio['articolo'] != "vuoto"))
                            {
                                //verifichiamo se l'articolo può essere fatturato a zero..
                                $_articolo = tabella_articoli("singola", $datidettaglio['articolo'], $_parametri);

                                if ($_articolo['egpz'] != "SI")
                                {
                                    printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $datidettaglio['anno'], $datidettaglio['ndoc'], $datidettaglio['status'], $datidettaglio['articolo'], $datidettaglio['totriga']);
                                    $_errore = "SI";
                                }

                                echo "</table>";
                                if ($_errore == "SI")
                                {
                                    echo "<br> Programma interrotto ....";
                                    echo "<br> <a href=\"generafatt.php\">Torna all'elenco generazione</a></center> <a href=\"../../index.php\">Oppure torna all'indice fatture</a></center>";
                                    exit;
                                }
                                else
                                {
                                    $_errore = "";
                                    echo "<h3>Trovato articolo promozionale, Nessun problema</h3>\n";
                                }
                            }
                        }


                        //escludiamo la gestione del magazzino per il ddtacq
                        if ($_tdoc_end != "ddtacq")
                        {
                            $_parametri = $datidettaglio;
                            //questa è la riga che va scritta nel nuovo documento..
                            //echo "|$datidettaglio[articolo], $datidettaglio[descrizione]|<br><hr><br>";
                            //qui scriviamo il corpo nuovo..
                            $resut_ins = gestisci_dettaglio("inserisci_singola", $_archivi_end, $_tdoc_end, $_anno, $_suffix, $_ndoc, $_rigo, $_codutente, $datidettaglio[articolo], $datidettaglio[descrizione], $datidettaglio[iva], $_parametri);

                            if ($resut_ins['errori'] != "OK")
                            {
                                echo "errore numero due inserimento corpo dettaglio <br>";
                                exit;
                            }
                        }
                    }
                    elseif (($_POST['tipologia' . $_annondoc] == "parz") AND ( $datidettaglio['qtaestratta'] == "0.00"))
                    {
                        //potrebbe essere che la riga letta do debba essere inserita nel nuovo documento..
                        //verifichiamo che non sia da chiudere.. altrimenti la saltiamo..
                        //verifichiamo che non ci sia rsaldo=SI

                        if ($datidettaglio['rsaldo'] == "SI")
                        {

                            //azzieriamo i parametri..
                            $_parametri = "";
                            //aggiorniamo i prametri correnti del documento di partenza..
                            $_parametri['qtasaldo'] = "0.00";
                            $_parametri['rsaldo'] = "SI";

                            //qui togliamo la funzione di aggirnamento del documento di origine in caso che lo start sia un ddt

                            if (($_tdoc_start != "ddt") AND ( $_tdoc_start != "ddt_diretto"))
                            {
                                $resut_agg = gestisci_dettaglio("aggiorna_singola", $_archivi_start, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $datidettaglio['rigo'], $_codutente, $datidettaglio[articolo], $datidettaglio[descrizione], $_iva, $_parametri);

                                if ($resut_agg['errori'] != "OK")
                                {
                                    echo "errore numero tre aggiornamento corpo dettaglio <br>";
                                    exit;
                                }
                            }
                        }
                    }
                    else
                    {

                        //bisogna modificare a monte il programma dichiarando che se il documento che si và a generare è una fattura semplice
                        //la gestione dei conti deve essere disabilitata..
                        //
			
                        if ($_tdoc_end == "FATTURA")
                        {
                            $_qtaend = $datidettaglio['quantita'];
                        }
                        else
                        {
                            //
                            //ora dobbiamo dividere i conti se vogliamo evadere tutto il documento oppure solo in parte..
                            //evadiamo il preparato dal magazziniere
                            //echo "|$datidettaglio[articolo]| $datidettaglio[descrizione]| qta $datidettaglio[quantita]| evasa $datidettaglio[qtaevasa]| estr $datidettaglio[qtaestratta]|saldo  $datidettaglio[qtasaldo]| $datidettaglio[rsaldo]| $datidettaglio[totriga]| $datidettaglio[totrigaprovv]<br>\n";

                            if (($_POST['tipologia' . $_annondoc] == "parz") AND ( $datidettaglio['qtaestratta'] != "0.00"))
                            {

                                //facciamo i conti..
                                $_qtasaldo = $datidettaglio['qtasaldo'] - $datidettaglio['qtaestratta'];
                                $_qtaevasa = $datidettaglio['qtaevasa'] + $datidettaglio['qtaestratta'];
                                $_qtaend = $datidettaglio['qtaestratta'];
                            }
                            else
                            {

                                //facciamo i conti..
                                //verifichiamo se sul documento esiste un saldo..
                                if (($datidettaglio['qtasaldo'] != "") AND ( $datidettaglio['qtasaldo'] != "0.00"))
                                {
                                    $_qtasaldo = "0.00";
                                    $_qtaevasa = $datidettaglio['qtasaldo'];
                                }
                                else
                                {
                                    $_qtasaldo = "0.00";
                                    $_qtaevasa = $datidettaglio['quantita'];
                                }

                                $_qtaend = $_qtaevasa;
                            }

                            //bisogna dichiara una cosa semplice..

                            if ($_qtasaldo <= "0.00")
                            {
                                $_qtasaldo = "0.00";
                                $_rsaldo = "SI";
                            }
                            else
                            {
                                $_rsaldo = "NO";
                            }
                        }//fine separazione fattura
                        // $_qtaend è la quantità che va a formare il nuovo documento
                        //definirei dei parametri globali per unificare tutti i documenti...
                        //la diffferenza sta solo nel fatto che le quantita per fare i conti sono diverse..
                        //calcoliamo le nuove provvigioni.. per calcolare le provvigioni dobbiamo richiamare l'articolo e vedere quanto sono assegnate

                        if ($_tdoc_start == "fornitore")
                        {
                            $_totriga_start = $datidettaglio['nettoacq'] * $_qtasaldo;
                            $_provvart = tabella_articoli("provvart", $datidettaglio['articolo'], $_parametri);
                            $_provvnetto = number_format((($datidettaglio['nettoacq'] * $_provvart) / 100), $dec, '.', '');
                            $_totrigaprovv_start = $_provvnetto * $_qtasaldo;
                        }
                        else
                        {
                            $_totriga_start = $datidettaglio['nettovendita'] * $_qtasaldo;
                            $_provvart = tabella_articoli("provvart", $datidettaglio['articolo'], $_parametri);
                            $_provvnetto = number_format((($datidettaglio['nettovendita'] * $_provvart) / 100), $dec, '.', '');
                            $_totrigaprovv_start = $_provvnetto * $_qtasaldo;
                        }



                        //questa è na nuova riga aggiornata..
                        //echo "nuova riga aggiornata del documento start saldo  $_qtasaldo, evasa = $_qtaevasa valore totale riga = $_totriga_start, nuove provvigioni $_totrigaprovv_start<br>";
                        $_totimpo_start = $_totimpo_start + $_totriga_start;

                        //azzieriamo i parametri..
                        $_parametri = "";
                        //aggiorniamo i prametri correnti del documento di partenza..
                        $_parametri['qtaevasa'] = $_qtaevasa;
                        $_parametri['qtaestratta'] = "0.00";
                        $_parametri['qtasaldo'] = $_qtasaldo;
                        $_parametri['rsaldo'] = $_rsaldo;
                        $_parametri['totriga'] = $_totriga_start;
                        $_parametri['totrigaprovv'] = $_totrigaprovv_start;
                        $_parametri['peso'] = $_peso;

                        //qui togliamo la funzione di aggirnamento del documento di origine in caso che lo start sia un ddt

                        if (($_tdoc_start != "ddt") AND ( $_tdoc_start != "ddt_diretto"))
                        {
                            $resut_agg = gestisci_dettaglio("aggiorna_singola", $_archivi_start, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $datidettaglio['rigo'], $_codutente, $datidettaglio[articolo], $datidettaglio[descrizione], $_iva, $_parametri);

                            if ($resut_agg['errori'] != "OK")
                            {
                                echo "errore numero tre aggiornamento corpo dettaglio <br>";
                                exit;
                            }
                        }
                        //andiamo ora a scrivere la nuova riga per il documento creato nuovo..
                        //il documento creato deve essere completo di tutto..

                        if ($_tdoc_start == "fornitore")
                        {
                            $_totriga_end = $datidettaglio['nettoacq'] * $_qtaend;
                            $_provvnetto = number_format((($datidettaglio['nettoacq'] * $_provvart) / 100), $dec, '.', '');
                            $_totrigaprovv_end = $_provvnetto * $_qtaend;
                        }
                        else
                        {
                            $_totriga_end = $datidettaglio['nettovendita'] * $_qtaend;
                            $_provvnetto = number_format((($datidettaglio['nettovendita'] * $_provvart) / 100), $dec, '.', '');
                            $_totrigaprovv_end = $_provvnetto * $_qtaend;
                        }


                        //-------------------------------------------------------------------------
                        //il documento creato deve essere completo di tutto..



                        /* ora i conti dell'iva vanno fatti sia per il documento che va chiuso ed aggiornato si aper quello che si va a generare..
                         * quindi per avere un calcolo dell'iva corretto, mi converrebbe inserire tutti gli imponibili relativi alla proprio codice all'interno
                         * di un array per poi elaborarlo tutto su un colpo alla fine della storia.., ne faccio due uno per start ed uno per end..
                         * passiamo  alla funzione gestione ia..
                         */

                        if ($_tdoc_start == "fornitore")
                        {
                            $_ivariga = $ivasis;
                        }
                        else
                        {
                            $_ivariga = $datidettaglio['iva'];
                        }

                        //Calcolo L'iva per il documento start
                        $_castiva_start[$_ivariga] = ($_castiva_start[$_ivariga] + $_totriga_start);

                        //Calcolo L'iva per il documento end
                        $_castiva_end[$_ivariga] = ($_castiva_end[$_ivariga] + $_totriga_end);

                        $_totprovv = $_totprovv + $_totrigaprovv_end;


                        //echo "riga ddt = $datidettaglio[articolo], $datidettaglio[descrizione], $_qtaend, $_totriga_end, $_totrigaprovv_end<br><hr>\n";
                        $_totimpo_end = $_totimpo_end + $_totriga_end;

                        //qui andiamo a creare la riga nuova..
                        //azzieriamo i parametri..
                        $_parametri = "";
                        //aggiorniamo i prametri correnti del documento di partenza..
                        //mi conviene assegnare alla variabile $_parametri tutto e poi cambiare quello che mi serve..

                        $_parametri = $datidettaglio;

                        $_parametri['quantita'] = $_qtaend;
                        $_parametri['totriga'] = $_totriga_end;
                        $_parametri['totrigaprovv'] = $_totrigaprovv_end;
                        $_parametri['peso'] = $_peso_end;
                        $_parametri['ddtfornitore'] = $_POST['ddtfornitore'];
                        $_parametri['fatturacq'] = $_POST['fatturacq'];
                        $_parametri['datareg'] = $_datareg;
                        $_descrizione = addslashes($datidettaglio[descrizione]);

                        $resut_ins = gestisci_dettaglio("inserisci_singola", $_archivi_end, $_tdoc_end, $_anno, $_suffix, $_ndoc, $_rigo, $_codutente, $datidettaglio[articolo], $_descrizione, $datidettaglio[iva], $_parametri);

                        if ($resut_ins['errori'] != "OK")
                        {
                            echo "errore numero quattro inserimento corpo nuovo dettaglio <br>";
                            exit;
                        }

                        //se tutto ok aggiorniamo l'anagrafica articoli sull'ultimo acquisto..

                        if ($_tdoc_end == "ddtacq")
                        {
                            #echo "<br>documento ddt acquisto nr $_ndoc eseguito inserimento";
//calcoliamo il prezzo netto
// aggiornamo l'ultimo prezzo acquisto
                            @$_ultacq = $_parametri['totriga'] / $_parametri['quantita'];
                            $query = sprintf("UPDATE articoli SET ultacq=\"%s\" where articolo=\"%s\"", $_ultacq, $datidettaglio['articolo']);
// Esegue la query...
                            $result = $conn->exec($query);

                            if ($conn->errorCode() != "00000")
                            {
                                $_errore = $conn->errorInfo();
                                echo $_errore['2'];
                                //aggiungiamo la gestione scitta dell'errore..
                                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                                $_errori['files'] = "esegui impo doc.php";
                                scrittura_errori($_cosa, $_percorso, $_errori);
                                $_errori['errori'] = "NO";
                            }
                        }//fine ddtacq
                    }//fine di divisione descrittiva da codici
                }//fine dettaglio..
                //qui mettidmo i saldi nuovi dei documenti..
                //per quanto riguarda i saldi abbiamo il totale del documento
                //sistemiamo anche l'iva del documento aggiornato..


                $_imponibili_start = gestione_iva("iva_documenti", $_castiva_start, $_totiva, $_imponibile, $_spese, $_utente, $_datareg);

                //in teoria qui abbiamo le ive già calcolate e giuste.

                $_imponibile_start = $_imponibili_start['totimpo'];
                $_totimposta_start = $_imponibili_start['totiva'];

                #echo $_imponibile;
                #echo $_totimposta;

                $_totdoc_start = $_imponibile_start + $_totimposta_start;


                //ora aggiorniamo il documento start,  riportando i saldi corretti ed il numero del documenbto che la chiouso..
                // la funzione aggiorna e chiudi..
                //echo "<br>Totali documento chiuso $_totdoc_start imponibile $_totimpo_start per un totale di iva $_totimposta_start<br>";
                //verifichiamo lo status del documento per vedere se è chiuso..

                if ($_tdoc_end != "FATTURA")
                {
                    $_status = gestisci_dettaglio("verifica_saldo", $_archivi_start, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $_rigo, $_codutente, $_codice, $_descrizione, $_iva, $_parametri);
                }
                else
                {
                    $_status = "evaso";
                }
                //resettiamo le variabili
                $_parametri = "";
                $_parametri['status'] = $_status;
                $_parametri['t_doc_end'] = $_tdoc_end;
                $_parametri['ndoc_end'] = $_ndoc;
                $_parametri['anno_end'] = $_anno;



                $result_agg = gestisci_testata("aggiorna_chiudi", $_codutente, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $_datareg, $_archivi_start, $_parametri);

                if ($result_agg['errori'] != "OK")
                {
                    echo "errore numero cinque aggiorna e chiudi <br>";
                    exit;
                }

                // faccio la somma delle spese di trasporto
                $_trasporto = $_trasporto + $datidoc['trasporto'];
                $_colli = $_colli + $datidoc['colli'];
                $_pesotot = $_pesotot + $datidoc['pesotot'];
                $_speseb = $_speseb + $datidoc['sp_bancarie'];
                $_spimba = $_spimba + $datidoc['imballo'];
                $_varie = $_varie + $datidoc['spesevarie'];
                $_scoinco = $_scoinco + $datidoc['scoinco'];
            }//chiudo forearch selezione documento
            //qui calcoliamo tutte le ive..
            //dobbiamo aggiornare anche il pagamento che segue il documento insieme al corriere ecc..
            //qui dobbiamo fare il conto delle spese, poi passarlo direttamente allafunzione che
            //che mi calcola l'iva differente..
            //calcolo l'imponibile delle spese..
            $_spese = ($_speseb + $_spimba + $_trasporto + $_varie) - $_scoinco;

            // passo alla funzione il calcolo dell'iva per il documento..

            $_imponibili = gestione_iva("iva_documenti", $_castiva_end, $_totiva, $_imponibile, $_spese, $_utente, $_datareg);

            //in teoria qui abbiamo le ive già calcolate e giuste.

            $_imponibile = $_imponibili['totimpo'];
            $_totimposta = $_imponibili['totiva'];

            #echo $_imponibile;
            #echo $_totimposta;

            $_totdoc = $_imponibile + $_totimposta;


            //qui aggiorniamo la testata del documento generato..

            echo "<br>Totali documento generato netto doc $_totimpo_end";
            echo "<br>Nuovi importi Totali documento generato $_totdoc .. imponibile $_imponibile per un totale di iva $_totimposta tot provv doc. $_totprovv";

            //manca la fine.. che viene fatta solo per documenti generati diversi dal ddtaccq
            //qui aggiorniamo il documento..
            //azzeriamo la variabile $_parametri e prepariamo quelli nuovi..
            if ($_tdoc_end != "ddtacq")
            {
                $_parametri = "";
                $_parametri['imponibili'] = $_imponibili;
                $_parametri['datidoc'] = $datidoc;
                $_parametri['utente'] = $_utente;
                //ora passiamo le somme dei documenti

                $_parametri['trasporto'] = $_trasporto;
                $_parametri['colli'] = $_colli;
                $_parametri['peso'] = $_pesotot;
                $_parametri['speseb'] = $_speseb;
                $_parametri['spimba'] = $_spimba;
                $_parametri['varie'] = $_varie;
                $_parametri['scoinco'] = $_scoinco;
                $_parametri['totdoc'] = $_totdoc;
                $_parametri['nettodoc'] = $_totimpo_end;
                $_parametri['imponibile'] = $_imponibile;
                $_parametri['imposta'] = $_totimposta;
                $_parametri['totprovv'] = $_totprovv;



                $result_agg = gestisci_testata("aggiorna_travasa", $_codutente, $_tdoc_end, $_anno, $_suffix, $_ndoc, $_datareg, $_archivi_end, $_parametri);

                if ($result_agg['errori'] != "OK")
                {
                    echo "errore numero sei aggiorna finale <br>";
                    exit;
                }
            }


            //qui passiamo alla gestione del magazzino e delle provvigioni..
            //gestione del magazzino.. tabella_magazzino..
            //ora non ci resta che inserire il tutto nel magazzino e poi aggiornare le provvigioni degli agenti.
            #la funzione magazzino andrà attivata solo per i docuemnti che sono collegati al magazzino
            #quindi

            if (( $_tdoc_end == "ddt") OR ( $_tdoc_end == "ddt_diretto") OR ( $_tdoc_end == "NOTA DEBITO") OR ( $_tdoc_end == "NOTA CREDITO") OR ( $_tdoc_end == "$nomedoc"))
            {

                //ora aggiorniamo il magazzino
                #la veriabile cosa è collegata con l'inizio quindi si arrangia per aggiornare o inserire.

                $_magazzino = gestisci_magazzino("Evadi", $id, $_tdoc_end, $_anno, $_suffix, $_ndoc, $_datareg, $_codutente, $_tut, $_archivi_end, $_parametri);

                if ($_magazzino['errori'] != "OK")
                {
                    $_return['descrizione'] = "Si &egrave; verificato un errore nella query inserimento in magazzino:<br>\n\"$query\"\n";
                    $_return['errore'] = "errore";
                }
            }


            //Gestione delle provvigioni in caso di fattura..
            //ora bisogna gestire le provvigioni, che riguardano solo le fatture ed i suoi documenti
            if (( $_tdoc_end == "FATTURA") OR ( $_tdoc_end == "NOTA DEBITO") OR ( $_tdoc_end == "NOTA CREDITO") OR ( $_tdoc_end == "$nomedoc"))
            {

                #la variabile cosa è legata alle funzioni secondarie
                $_provvigioni = gestione_provvigioni("inserisci", $_tdoc_end, $_anno, $_suffix, $_ndoc, $_utente['codagente'], $_datareg, $_codutente, $_totdoc, $_totprovv);


                if ($_provvigioni['errore'] != "OK")
                {
                    $_return['descrizione'] = "Si &egrave; verificato un errore nella query inserimento in provvigioni:<br>\n\"$query\"\n";
                    $_return['errore'] = "errore";
                }
            }
        }//chiudo else blocca numero

        echo "<h3>Documento inserito..</h3>\n";

        $_documento['ndoc'] = $_ndoc;
        $_documento['tdoc'] = $_tdoc_end;
        $_documento['anno'] = $_anno;
#generiamo la maschera delle stampe
        if ($_tdoc_end == "ddtacq")
        {
            echo "<tr><td align=\"center\">\n";
#Richiamo le funzioni si di stampa;
            genera_maschera_stampe("../stampa_doc.php", "visualizza", $_documento);
        }
        else
        {
#Richiamo le funzioni si di stampa;
            genera_maschera_stampe("../stampa_doc.php", "inserisci", $_documento);
#genero la selezione dei prezzi
            print_prezzi($_tdoc_end);
#faccio apparire i pulsanti per selezionare le lingue..
            seleziona_lingue();
            echo "<br><td align=\"center\"><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Stampa\"> &nbsp;<input type=\"submit\" name=\"azione\" value=\"Inoltra\">";
        }

        echo "</td></tr></form></table>";

        echo "<br>";
        echo "<center><h3> Vuoi rimodificare subito questo documento ?</h3><br>";
        printf("<a href=\"visualizzadoc.php?tdoc=%s&ndoc=%s&anno=%s\">Clikka qui e vai subito!</a>", $_tdoc_end, $_ndoc, $_anno);
        echo "</center>";
        echo "<br>";

        if ($_documento['tdoc'] == "FATTURA")
        {
            echo "<br><center><a href=\"importa_doc.php?start=$_POST[start]&end=FATTURA\">Importa un altro DDT</a></center>\n";
        }




        echo "</body></html>";


        // elimino le sessioni usate
        chiudi_sessioni();
    }
    else
    {
        echo "<br>";
        echo "<center><h3><font color=\"red\">ATTENZIONE.. !</h3>";
        echo "<center><h3><font color=\"red\">Si st&agrave; cercando di modificare un documento gi&agrave; modificato od inserito..</h3>";
        echo "<center><h3>Non si pu&ograve; tornare indietro con il browser..</h3>";
        #echo "<center><h3>Non si pu&ograve; tornare indietro con il browser..</h3>";
        exit;
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>