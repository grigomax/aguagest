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

//DICHIARO CHE MI SERVE UNA VAIABILE PER MANTENERMI Ò'IVA

$IVA_CORSO = $ivasis;

// Prendiamo i vari post dalla pagina precedente...
// eseguiamo la connessione con il database
//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

echo "<h2>Generazione automatica fatture </h2> ";

// Impostiamo l'anno di utilizzo
$_anno_end = cambio_data("anno_it", $_POST['data']);
$_data = $_POST['data'];

$_numero = $_POST['numero'];

// Stringa contenente la query di ricerca...
if ($_SESSION['user']['vendite'] > "2")
{

    //settiamo le variabili globali..
    $_tdoc_end = "FATTURA";
    $_tdoc_start = "ddt";
    $_archivi_end = archivio_tdoc("FATTURA");
    $_archivi_start = archivio_tdoc("ddt");

    //controllo che i campi siano pieni..
    if (($_POST['data'] == "") OR ( $_POST['numero'] == ""))
    {
        echo "<h3> Attenzione non &egrave; stato selezionato un campo obbligatorio </h3>\n";
        exit;
    }

    foreach ($_numero as $_annondoc)
    {
        //dobbiamo dividere i vari campi in quanto abbiamo anche l'anno..
        $_anno_start = substr($_annondoc, '0', '4');
        $_ndoc_start = substr($_annondoc, '4', '6');

        // bisogna fare una sicurezza sull'elenco altrimenti si fatturano bolle eraatew.
        //	Funzione di controllo verifica prezzi.. controllo che non ci siano righe senza prezzo o a valore 0
        // PRIMA DI PARTIRE VERIFICO SE CI SONO ARTICOLI SENZA PREZZO.. CHE NON SI SA MAI

        gestisci_testata("verifica_zero", $_utente, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $_datareg, $_archivi_start, $_parametri);

        // 	se non si blocca il programma vuol dire che il documento è valido
        // 	lo riselezioniamo.. in quanto valido
        //leggiamo il documento..

        $dati_start = gestisci_testata("leggi_riga_testata", $_utente, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $_datareg, $_archivi_start, $_parametri);

        $_utente = $dati_start['utente'];

        //prendiamo tutti i dati dal clienti..
        $dati_utente = tabella_clienti("singola", $_utente, "");

        if ($dati_utente['iva'] == "")
        {
            if ($dati_start['datareg'] < $DATAIVA)
            {
                $ivasis = $ivasis - 1;
            }

            $_ivadesc = $ivasis;
        }
        else
        {
            $_ivadesc = $dati_utente['iva'];
        }

        if ($_utente == "")
        {
            echo "Errore nella selezione dei documenti.. <br>";
            echo "Programma interrotto";
            $_errori['descrizione'] = "Errore nella selezione del documento in generafatt2.php";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            exit;
        }

        if ($_utente != $_utente_pro)
        {
            //Vuol dire che è un documento nuovo..
            //azzeriamo le variabili e poi inseriamo il nuovo documento anche nelle provvigioni
            // 		questo vale anche nel caso di apertura di tutto nuovo
            // 		Bisogna azzerare tutte le variabili...
            // 		Prima di iniziare un documento nuvo
            $_data_us = "";
            $_trasporto = "";
            $_rigo = "";
            $_nettomerce = "";
            $_nettofattura = "";
            $_provvart = "";
            $_provvnetto = "";
            $_rigaprovv = "";
            $_ivariga = "";
            $_ivacli = "";
            $_esenzione = "";
            $_impostaspese = "";
            $_imponibile = "";
            $_speseb = "";
            $_totprovv = "";
            $_totimposta = "";
            $_totdoc = "";
            $_castiva = array();
            $_totiva = "";
            $_ivasep = "";
            $valore = "";
            $indice = "";
            $nr = "";
            $_imponibili = "";
            $_varie = "";
            $_parametri="";

            $_datareg = cambio_data("data_us", $_data);

            $_status = "inserito";

            //prendiamo l'ultimo numero che tocca..
            $_ndoc_end = gestisci_testata("ultimo_numero", $_utente, $_tdoc_end, $_anno_end, $_suffix_end, $_ndoc, $_datareg, $_archivi_end, $_parametri);

            //Query che inserisce solo parte dell'intestazione della fattura
            //blocchiamo il numero..
            $blocca = gestisci_testata("blocca_numero", $_utente, $_tdoc_end, $_anno_end, $_suffix_end, $_ndoc_end, $_datareg, $_archivi_end, $_parametri);
            // Esegue la query...
            if ($blocca['errori'] != "OK")
            {
               echo "Errore Blocco fattura numero $_ndoc_end <br>\n";
            }
            else
            {
                echo "|Blocco fattura numero $_ndoc_end riuscito |\n";
            }

            //inseriamo anche un blocco nelle provvigioni..
            // Inseriamo nell'anagrafica provvigioni la fattura appena generata
            $_provvigioni = gestione_provvigioni("inserisci", $_tdoc_end, $_anno_end, $_suffix_end, $_ndoc_end, $dati_utente['codagente'], $_datareg, $_utente, $_totdoc, $_totprovv);

            if ($_provvigioni['errori'] != "OK")
            {
                echo "Si &egrave; verificato un errore nell'inserimento provvigiorni";
            }
            else
            {
                echo " Blocco provvigioni per fattura numero $_ndoc_end| <br>\n";
            }
            //ora per tutto il resto il programma è identico..
        }

        //Ora NON faccio altro che inserire i documenti dentro la fattura ed aggiornarla in continuazione
        
        // faccio le somme eventualeme delle spese..
        $_varie = $_varie + $dati_start['spesevarie'];
        $_trasporto = $_trasporto + $dati_start['trasporto'];
        $_datait = cambio_data("it", $dati_start['datareg']);

        //inserisco la prima riga del corpo documenti destinatario.. es. d.d.t.  n. del cc..
        // e setto i parametri per non incorrere in errori.
        $_rigo = $_rigo + 1;
        $ddt = $dati_start['ndoc'];
        $_codice = "vuoto";
        $_descrizione = "D.D.T. n. $ddt del $_datait";
        $_parametri['unita'] = "";
        $_parametri['quantita'] = "";
        $_parametri['listino'] = "";
        $_parametri['scva'] = "";
        $_parametri['scvb'] = "";
        $_parametri['scvc'] = "";
        $_parametri['nettovendita'] = "";
        $_parametri['totriga'] = "";
        $_parametri['totrigaprovv'] = "";
        $_parametri['peso'] = "";
        
        $corpo = gestisci_dettaglio("inserisci_singola", $_archivi_end, $_tdoc_end, $_anno_end, $_suffix_end, $_ndoc_end, $_rigo, $_utente, $_codice, $_descrizione, $_ivadesc, $_parametri);
        // Esegue la query...
        if ($corpo['errori'] != "OK")
        {
            echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
            return -1;
        }

        // bisogna modificare lo stato documento
        // setto a modo evaso il documento in eleborazione..
        // ed inserisco il numero dosumento fattura
        $status = gestisci_testata("aggiorna_chiudi", $_utente, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $_datareg, $_archivi_start, array('status' => 'evaso', 't_doc_end' => $_tdoc_end, 'ndoc_end' => $_ndoc_end, 'anno_end' => $_anno_end));

        // Esegue la query...
        if ($status['errori'] != "OK")
        {
            echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
            return -1;
        }
        else
        {
            echo "Aggiornamento ddt nr $dati_start[ndoc] <br>\n";
        }

        // leggo il corpo documenti
        // procedo a travasare le righe del corpo nella fattura
        $dettaglio = gestisci_dettaglio("leggi_corpo", $_archivi_start, $_tdoc_start, $_anno_start, $_suffix_start, $_ndoc_start, $_rigo, $_utente, $_codice, $_descrizione, $_iva, $_parametri);

        //ora inseriamo il tutto nella fattura..
        foreach ($dettaglio AS $dati_corpo)
        {
            // funzione che mi permette di inserire tutte le riche
            // una di seguito all'altra senza saltare numeri
            $_rigo = $_rigo + 1;
            // ora faccio i conti con il totale delle riche in modo da avere il l'imponibile senza spese
            $_nettofattura = $_nettofattura + $dati_corpo['totriga'];
            $_totprovv = $_totprovv + $dati_corpo['totrigaprovv'];
            //Calcolo L'iva
            $_ivariga = $dati_corpo['iva'];
            $_castiva[$_ivariga] = ($_castiva[$_ivariga] + $dati_corpo['totriga']);

            $_descrizione = addslashes($dati_corpo[descrizione]);
            // inserisco i dati allinterno del corpo fattura

            $result = gestisci_dettaglio("inserisci_singola", $_archivi_end, $_tdoc_end, $_anno_end, $_suffix_end, $_ndoc_end, $_rigo, $_utente, $dati_corpo['articolo'], $_descrizione, $_ivariga, $dati_corpo);

            // Esegue la query...
            if ($result['errori'] != "OK")
            {
                echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
                return -1;
            }
        }

        //azzero le variabili dell'indice del castello iva..
        $indice = "";
        $valore = "";
        $_totiva = "";
        
        //resetto il puntatore dall'array riportandolo all'inizio per poterlo ricontare..
        reset($_castiva);

        //calcolo l'imponibile delle spese..
        $_spese = ($_speseb + $_spimba + $_trasporto + $_varie) - $_scoinco;

        // passo alla funzione il calcolo dell'iva per il documento..
        $_imponibili = gestione_iva("fattura", $_castiva, $_totiva, $_imponibile, $_spese, $dati_utente, $dati_start['datareg']);

        //in teoria qui abbiamo le ive già calcolate e giuste.
        $_imponibile = $_imponibili['totimpo'];
        $_totimposta = $_imponibili['totiva'];

        $_totdoc = $_imponibile + $_totimposta;

        $_parametri = "";
        $_parametri['imponibili'] = $_imponibili;
        $_parametri['datidoc'] = $dati_start;
        $_parametri['utente'] = $dati_utente;
        //ora passiamo le somme dei documenti
        $_parametri['trasporto'] = $_trasporto;
        $_parametri['colli'] = $_colli;
        $_parametri['peso'] = $_pesotot;
        $_parametri['speseb'] = $_speseb;
        $_parametri['spimba'] = $_spimba;
        $_parametri['varie'] = $_varie;
        $_parametri['scoinco'] = $_scoinco;
        $_parametri['totdoc'] = $_totdoc;
        $_parametri['nettodoc'] = $_nettofattura;
        $_parametri['imponibile'] = $_imponibile;
        $_parametri['imposta'] = $_totimposta;
        $_parametri['totprovv'] = $_totprovv;

        //aggiorniamo la fattura inserendo i dati mancanti appena prelevati dalla anagrafica cliente
        $aggiorna = gestisci_testata("aggiorna_travasa", $_utente, $_tdoc_end, $_anno_end, $_suffix_end, $_ndoc_end, $_datareg, $_archivi_end, $_parametri);
        // Esegue la query...
        if ($aggiorna['errori'] != "OK")
        {
            echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
            return -1;
        }
        else
        {
            echo "<b>Aggiornamento fattura numero $_ndoc_end Completato </b><br>\n";
        }

        // Inseriamo nell'anagrafica provvigioni la fattura appena generata
        $_provvigioni = gestione_provvigioni("aggiorna", $_tdoc_end, $_anno_end, $_suffix_end, $_ndoc_end, $dati_utente['codagente'], $_datareg, $_utente, $_totdoc, $_totprovv);
        if ($_provvigioni['errori'] != "OK")
        {
            echo "Errore Aggiornamento provvigioni fattura $_ndoc_end\n";
        }

        // setto l'utente nel caso il documento che segua sia dello stesso cliente..
        
        $_utente_pro = $_utente;
    }

    echo "<h3>Se non appaiono errori a video la generazione &egrave; stata eseguita con successo</h3>";
    echo "<h4> Ora si puo prodere alla stampa cliccando ristampa fattura, oppure elenco documenti in alto </h4>";
}
else
{
    echo "<h2>Non hai i permessi per poter visualizzare il documento</h2>\n";
}

echo "</body></html>\n";
?>