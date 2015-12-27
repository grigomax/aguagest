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
        require "../../librerie/motore_primanota.php";
    }


//controllo che i campi siano pieni..

    if (($_POST['data'] == "") OR ( $_POST['numero'] == ""))
    {
        echo "<h3> Attenzione non &egrave; stato selezionato un campo obbligatorio </h3>\n";
        exit;
    }

// Impostiamo l'anno di utilizzo
    $_annoeff = cambio_data("anno_it", $_POST['data']);
    $_data = $_POST['data'];

    $_numero = $_POST['numero'];

// echo $_ndoc;
// ok per ogni articolo mi prendo tutti i dati dell'articolo
// echo $_codice;
    foreach ($_numero as $_annondoc)
    {
// echo $_value;
// mi conviene riscriverlo..
// 	Seleziono il documento..
        //qui dividiamo il numero della fattura con anche l'anno di riferimento alla stessa.
       
        $_anno = substr($_annondoc, "0", "4");
        $_suffix = substr($_annondoc, "4", "1");
        $_ndoc = substr($_annondoc, "5", "11");
        

        //leggo la fattura singola

        $datiu = tabella_fatture("leggi_singola_testata", $_percorso, $_anno, $_suffix, $_ndoc, $_parametri);
        //mi prendo le variabili...

        $_datadoc = $datiu['datareg'];
        $_pagamento = $datiu['modpag'];
        $_totimpo = $datiu['totimpo'];
        $_totiva = $datiu['totiva'];
        $_totdoc = $datiu['totdoc'];
        $_utente = $datiu['utente'];

        #richiamo la funzione scadenze dove passandogli le date mi ritorna le scadenze..
        $data = scadenza($_datadoc, $_pagamento);



        #richiamiamo la funzione importi
        $valori = importi($_pagamento, $_totimpo, $_totiva, $_totdoc);

        // DIVIDO l'array
        $importo = $valori['valore'];
        $_rate = $data['rate'];
        $_trata = $valori['trata'];

        /*
          ora ho due arrey piu una variabile, la variabile e $_rate dove e c'e il numero delle rate che compongono il pagamento, poi ho l'array data dove sono memorizzate le date in formato americano, e poi ho l'array importi dove c'e le importi relativi per ogni rata */

// 	Ora per ogni rata genero una riga di effetto.. quindi foreach

        for ($_r = 1; $_r <= $_rate; $_r++)
        {

// 		prendiamo ci la data dell'effetto che tocca
            echo "scadenza effetto = $data[$_r]\n";
            $_scadeff = $data[$_r];
            $_impeff = $importo[$_r];

// 		cerchiamo l'ultimo numero di effetto inserito

            $_numeff = tabella_effetti("ultimo_numero", $_percorso, $_annoeff, $_numeff, $_parametri);

// 		prendiamo la data del post
            $_dataeff = cambio_data("us", $_data);
            #qui io dividerei il tipo di status..
            #se l'effetto è una riba mettiamo inserito
            #altrimenti in attesa..

            if ($_trata == "3")
            {
                $_status = "inserito";
            }
            elseif ($_trata == "2")
            {
                //visto che è un pagamento in contanti mettiamo la data pagamento come data effetto

                $_status = "saldato";
                $_datapag = $_dataeff;
            }
            else
            {
                $_status = "in attesa";
            }

            //inseriamo la riga dell'effetto completo
            //azzeriamo la variabile $_parametri
            $_parametri = "";
            //prepariamo l'inserimento
            $_parametri['trata'] = $_trata;
            $_parametri['dataeff'] = $_dataeff;
            $_parametri['scadeff'] = $_scadeff;
            $_parametri['impeff'] = $_impeff;
            $_parametri['tdoc'] = $datiu['tdoc'];
            $_parametri['anno'] = $datiu['anno'];
            $_parametri['ndoc'] = $datiu['ndoc'];
            $_parametri['suffix'] = $datiu['suffix'];
            $_parametri['datareg'] = $datiu['datareg'];
            $_parametri['totdoc'] = $datiu['totdoc'];
            $_parametri['codcli'] = $datiu['utente'];
            $_parametri['modpag'] = $datiu['modpag'];
            $_parametri['bancapp'] = addslashes($datiu['banca']);
            $_parametri['abi'] = $datiu['abi'];
            $_parametri['cab'] = $datiu['cab'];
            $_parametri['cin'] = $datiu['cin'];
            $_parametri['cc'] = $datiu['cc'];
            $_parametri['status'] = $_status;
            $_parametri['datapag'] = $_datapag;
            $_parametri['contabilita'] = "NO";

            $result = tabella_effetti("inserisci_singolo", $_percorso, $_annoeff, $_numeff, $_parametri);

            //azzeriamo la variabile parametri..
            $_parametri = "";
            //facciamo apparire il risultato..
            //verifichiamo che l'effetto sia stato inserito correttamente prima di procedere con il resto

            if ($result['errore'] == "errore")
            {
                echo $result['descrizione'] . "<br>\n";
                echo "Questo effetto non è stato generato per un errore dovuto in fattura..<br>Verrà saltato\n";
            }
            else
            {
                echo $result['descrizione'] . "<br>\n";

                // ora che abbiamo inserito l'effetto saldiamo la fattura..

                if (($CONTABILITA == "SI") AND ( $_trata == "2"))
                {
                    //azzeriamo i valori..
                    $_parametri['avere'] = "";
                    $_parametri['dare'] = "";

                    //leggiamo l'effetto desiderato per vedere se è stato modificato
                    $_eff = tabella_effetti("leggi_singolo", $_percorso, $_annoeff, $_numeff, $_parametri);


                    //ci preleviamo l'anno
                    //$_anno = cambio_data("anno_us", $_eff['datadist']);
                    //Prima di iniziare leggiamo il numero di registrazione che tocca..
                    $_nreg = tabella_primanota("ultimo_numero", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    //ci prendiamo il cliente..
                    $_utente = tabella_clienti("singola", $_eff['codcli'], "");


                    //registriamo anche incontabilità quallo che è successo..
                    // se fa l0insoluto in base alla banca presentato la riapriamo ..

                    $_testo = "Incass. Fatt.  $_eff[numdoc]  - $_utente[ragsoc]";

                    $_parametri['segno'] = "P";
                    $_parametri['ndoc'] = $_eff['numdoc'];
                    $_parametri['suffix_doc'] = $_eff['suffixdoc'];
                    $_parametri['anno_doc'] = $_eff['annodoc'];
                    $_parametri['data_doc'] = $_eff['datadoc'];
                    $_parametri['codpag'] = $_eff['modpag'];
                    $_parametri['conto'] = "$MASTRO_CLI$_eff[codcli]";
                    $_parametri['desc_conto'] = addslashes($_utente['ragsoc']);
                    $_parametri['avere'] = $_impeff;

                    //riapriamo il cliente in dare..
                    $_result = tabella_primanota("inserisci_singola", $id, $_anno, $_nreg, "IN", $_testo, $_datapag, $_datapag, $_parametri, $_percorso);

                    //non capisco a cosa servi la banca..
                    //$_banca = tabella_banche("singolo", $_eff['bancadist'], $_abi, $_cab, "");
                    //ora riapriamo la bana in avere..
                    //m prima cancelliamo il dare
                    $_parametri['avere'] = "";
                    $_parametri['conto'] = $CONTO_CASSA;
                    $_parametri['desc_conto'] = "Cassa Contanti";
                    $_parametri['dare'] = $_impeff;

                    $_result = tabella_primanota("inserisci_singola", $id, $_anno, $_nreg, "IN", $_testo, $_datapag, $_datapag, $_parametri, $_percorso);

                    echo "<h3>Operazione inserita in contabilit&agrave; con il numero $_result</h3>\n";

                    //azzeriamo tutti i parametri.
                    $_parametri = "";
                }

                //aggiorniamo le fatture toccate..
                $_parametri = null;
                $_parametri['status'] = "evaso";
                $_parametri['tdocevaso'] = "effetto";
                $_parametri['evasonum'] = $_numeff;
                $_parametri['evasoanno'] = $_annoeff;
                $_parametri['utente'] = $datiu['utente'];

                $result = tabella_fatture("aggiorna_status", $_percorso, $datiu['anno'], $datiu['suffix'], $datiu['ndoc'], $_parametri);

                if ($result == "Ok")
                {
                    echo "<h4>Aggiornamento fattura effettuato $datiu[ndoc]</h4>\n";
                }
            } // fine di for...
        } // chiudo possibili errori generazione effetti.
        // provo a svutare gli arrey.
        $data = array();
        $importo = array();
    } //chiudo foreach
// INIZIO PARTE VISIVA DELLA GENERAZIONE..

    echo "<h2> Generazione automatica effetti </h2> ";

    echo "<h3> Se non appaiono errori a video la generazione &egrave; stata eseguita con successo</h3>";

    echo "<h4> Ora si puo alla visualizzazione degli stessi presso effetti inseriti.. in alto </h4>";

    //chiudiamo le connessioni al database
    $conn->null;
    $conn = null;
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>