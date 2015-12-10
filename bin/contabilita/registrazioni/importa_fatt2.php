<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * http://aguagest.sourceforge.net/
 * massimo@mcetechnik.it
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);
require "../../librerie/motore_anagrafiche.php";
require "../../librerie/motore_primanota.php";
require "../../../setting/par_conta.inc.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

//Passo l'va sistema a nuova iva..'
    $IVANUOVA = $ivasis;
//questo programma importa le varie fatture / note credito in contabilità quindi
// andranno registrate in contabilità con la stessa data di emissione



    $_check = $_POST['check'];

// echo $_ndoc;
// ok per ogni articolo mi prendo tutti i dati dell'articolo
// echo $_codice;
    foreach ($_check as $_annondoc)
    {

        $_anno = substr($_annondoc, "0", "4");
        $_ndoc = substr($_annondoc, "4", "10");

//leggiamo le fatture e ma mano che le leggiamo le passiamo in contabilità..
        //Poi leggiamo anche tutte le varie aggiute come trasporto ecc..
        //leggo il database delle fatture..
        $query = "SELECT * FROM fv_testacalce INNER JOIN clienti ON fv_testacalce.utente=clienti.codice where anno='$_anno' AND ndoc='$_ndoc' LIMIT 1";

        //ora esequiamo la query..
        $res = mysql_query($query, $conn) or mysql_error();

        //ci prendiamo i dati..

        $dati = mysql_fetch_array($res);

        //sistemiamo la questione iva.. 
        //verifichiamo la data del documento.. e aggiorniamo l'iva
        if ($dati['datareg'] < $DATAIVA)
        {
            $ivasis = $ivasis - 1;
        }
        else
        {
            //altrimenti vuol dire che l0iva è apposto
            $ivasis = $IVANUOVA;
        }


        //prima di tutto verifichiamo che la registrazione non esisti già, altrimenti avvisiamo e saltiamo..
        $_return = tabella_primanota("verifica_FV", $id, $_anno, $_ndoc, "FV", $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

        if ($_return == "true")
        {
            echo "<h3>Errore non bloccante, Documento nr. $_ndoc e anno $_anno </h3>\n";
            echo "<h3>Risulta Gi&agrave; presente in prima nota </h3>\n";
        }
        else
        {
            //metto un blocco sulla importazione della prima nota..
            //verifico che al cliente sia associato un contro conto..

            if ($dati['cod_conto'] == "")
            {
                echo "<h3>Errore non bloccante, Documento nr. $_ndoc e anno $_anno </h3>\n";
                echo "<h3>Il cliente risulta senza codice del piano dei conti..</h3>\n";
                echo "<h3>Il documento verr&agrave; saltato</h3>\n";
            }
            else
            {

                //ora che abbiamo i dati effettuiamo le scritture in contabilità..
                //ci prendiamo il numero..

                $_nreg = tabella_primanota("ultimo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);
                //ottimo ora inseriamo l'intero importo della registrazione in dare al cliente a meno che non sia una nota credito mche va al contrario
                //
            //prepariamo i dati standard..
                $_data_gior = $dati['datareg'];
                $_data_reg = $dati['datareg'];
                $_causale = "FV";
                $_parametri['ndoc'] = $dati['ndoc'];
                $_parametri['anno_doc'] = $dati['anno'];
                $_parametri['data_doc'] = $dati['datareg'];
                $_parametri['codpag'] = $dati['modpag'];

                /*
                 * Prima di proseguire bisogna controllare che tipo di codice iva ha il cliente se ha esenzioni ecc..
                 * Poi direi di assegnare una variabile all'imponibile della fattura a cui io devo togliere le spese..
                 * 
                 */
                //verifichiamo se il cliente è esente..
                //se è vuoto vuol dire che non è esente e quindi li pioppiamo l'iva di sistema.
                if ($dati['iva'] != "")
                {
                    //altrimenti verifichiamo il tipo di esenzione che lui ha..
                    #echo $dati['iva'];
                    // vuo dire che il cliente ha una forma di esenzione..
                    // vediamo qual'è
                    //prendiamo l'arrey dell'iva
                    $dati_iva = tabella_aliquota("singolo", $dati['iva'], $_percorso);

                    //verifichiamo se è esente livello 2

                    if ($dati_iva['eseniva'] == "3")
                    {
                        //vuol dire che il cliente e un esportatore abituale e quindi 
                        //gli assegnamo una variabile di riconoscimento
                        $_ivaspese = $ivasis;
                    }
                    else
                    {
                        //altriementi gli 
                        $_ivaspese = $dati['iva'];
                    }
                }
                else
                {
                    $_ivaspese = $ivasis;
                }



                if ($dati['tdoc'] == "NOTA CREDITO")
                {
                    // essendo nota credito va al contrario..
                    $_parametri['segno'] = "N";
                    //giriamo la data..
                    $_data_testo = cambio_data("it", $dati['datareg']);
                    $_testo = "Emessa nota credito " . $dati['ndoc'] . " / " . $dati['anno'] . " del " . $_data_testo;
                }
                else
                {
                    $_parametri['segno'] = "P";
                    //giriamo la data..
                    $_data_testo = cambio_data("it", $dati['datareg']);
                    $_testo = "Emessa Fattura " . $dati['ndoc'] . " / " . $dati['anno'] . " del " . $_data_testo;
                    //inseriamo i dati..
                    //inseriamo tutto l'importo della fattura in dare..
                }

                //Prepariamo i dati..
                $_parametri['conto'] = $MASTRO_CLI . $dati[utente];
                $_parametri['desc_conto'] = $dati['ragsoc'];

                //controllo se è una nota credito
                if ($_parametri['segno'] == "N")
                {
                    $_parametri['avere'] = $dati['totdoc'];
                }
                else
                {
                    $_parametri['dare'] = $dati['totdoc'];
                }

                $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                if ($_result['errori']['errore'] == "errore")
                {
                    echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                    exit;
                }

                //se buon fine azzeriamo le variabili
                $_parametri['conto'] = "";
                $_parametri['desc_conto'] = "";
                $_parametri['dare'] = "";
                $_parametri['avere'] = "";

//-----------------------------------------------------------------------------------
                //prima di tutto dobbiamo inserire le spese perche dobbiamo fare i conti..
                //-------------------
                // e fin qui.. tutte le registrazione sono uguali...
                //ora vediamo se abbiamo qualcosa di più

                if ($dati['scoinco'] != "0.00")
                {
                    //insertiamo i parametri
                    //se buon fine azzeriamo le variabili
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    $_parametri['iva'] = "";

                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente

                    $_parametri['conto'] = $CONTO_SCONTI_FINALI;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($CONTO_SCONTI_FINALI, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['avere'] = $dati['scoinco'];
                        $_totspese = $_totspese + $dati['scoinco'];
                    }
                    else
                    {
                        $_parametri['dare'] = $dati['scoinco'];
                        $_totspese = $_totspese - $dati['scoinco'];
                    }

                    //qui dobbiamo inserire dove è stato messo l'iva..
                    //io ci associerei il codice del cliente

                    $_parametri['iva'] = $_ivaspese;

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

                if ($dati['trasporto'] != "0.00")
                {
                    //insertiamo i parametri
                    //se buon fine azzeriamo le variabili
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    $_parametri['iva'] = "";

                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente

                    $_parametri['conto'] = $REC_TRASPORTO;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($REC_TRASPORTO, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['trasporto'];
                        $_totspese = $_totspese - $dati['trasporto'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['trasporto'];
                        $_totspese = $_totspese + $dati['trasporto'];
                    }

                    //qui dobbiamo inserire dove è stato messo l'iva..
                    //io ci associerei il codice del cliente

                    $_parametri['iva'] = $_ivaspese;

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

                if ($dati['spesevarie'] != "0.00")
                {
                    //insertiamo i parametri
                    //se buon fine azzeriamo le variabili
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    $_parametri['iva'] = "";

                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente

                    $_parametri['conto'] = $REC_SPESE_VARIE;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($REC_SPESE_VARIE, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['spesevarie'];
                        $_totspese = $_totspese - $dati['spesevarie'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['spesevarie'];
                        $_totspese = $_totspese + $dati['spesevarie'];
                    }


                    //qui dobbiamo inserire dove è stato messo l'iva..
                    //io ci associerei il codice del cliente

                    $_parametri['iva'] = $_ivaspese;

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

                if ($dati['imballo'] != "0.00")
                {
                    //insertiamo i parametri
                    //se buon fine azzeriamo le variabili
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    $_parametri['iva'] = "";

                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente

                    $_parametri['conto'] = $REC_IMBALLI;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($REC_IMBALLI, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imballo'];
                        $_totspese = $_totspese - $dati['imballo'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imballo'];
                        $_totspese = $_totspese + $dati['imballo'];
                    }

                    //qui dobbiamo inserire dove è stato messo l'iva..
                    //io ci associerei il codice del cliente

                    $_parametri['iva'] = $_ivaspese;

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

                if ($dati['sp_bancarie'] != "0.00")
                {
                    //insertiamo i parametri
                    //se buon fine azzeriamo le variabili
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    $_parametri['iva'] = "";

                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente

                    $_parametri['conto'] = $REC_SPESE_BANCARIE;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($REC_SPESE_BANCARIE, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['sp_bancarie'];
                        $_totspese = $_totspese - $dati['sp_bancarie'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['sp_bancarie'];
                        $_totspese = $_totspese + $dati['sp_bancarie'];
                    }

                    //qui dobbiamo inserire dove è stato messo l'iva..
                    //io ci associerei il codice del cliente

                    $_parametri['iva'] = $_ivaspese;

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

                //fine spese.....
                //
	    //
	    //
//-----------------------------------
                //tutte le variabili qui a sequire non è detto che esistano..
                //dobbiamo inserirne una per  ogni iva
                if (($dati['imponibile_1'] != "0.00") OR ( $dati['nettomerce']) != "0.00")
                {
                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";


                    //aggiungiamo una funzione per far entrare le fatture vecchie
                    if ($dati['imponibile_1'] == "0.00")
                    {
                        $dati['imponibile_1'] = $dati['nettomerce'];
                        $_parametri['iva'] = $ivasis;
                    }
                    else
                    {
                        $_parametri['iva'] = $dati['cod_iva_1'];
                    }


                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente
                    $_parametri['conto'] = $dati['cod_conto'];
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($dati['cod_conto'], "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];

                    //dobbiamo verificicare sempre se l'iva è associaa al cliente e se ci sono spese..
                    if ($dati['cod_iva_1'] == $_ivaspese)
                    {
                        $dati['imponibile_1'] = $dati['imponibile_1'] - $_totspese;
                    }

                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imponibile_1'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imponibile_1'];
                    }



                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

//-----------------------------------------
                //ora inseriamo l'iva..

                if (($dati['imposta_1'] != "0.00") OR ( $dati['totiva'] != "0.00"))
                {
                    //ora registriamo l'iva..
                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    //aggiungiamo una funzione per far entrare le fatture vecchie
                    if ($dati['imposta_1'] == "0.00")
                    {
                        $dati['imposta_1'] = $dati['totiva'];
                        $_parametri['iva'] = $ivasis;
                    }
                    else
                    {
                        $_parametri['iva'] = $dati['cod_iva_1'];
                    }


                    $_parametri['conto'] = $CONTO_IVA_VENDITE;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($CONTO_IVA_VENDITE, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imposta_1'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imposta_1'];
                    }


                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

//dobbiamo inserirne una per  ogni iva
                if ($dati['imponibile_2'] != "0.00")
                {

                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente
                    $_parametri['conto'] = $dati['cod_conto'];
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($dati['cod_conto'], "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];

                    //dobbiamo verificicare sempre se l'iva è associaa al cliente e se ci sono spese..
                    if ($dati['cod_iva_2'] == $_ivaspese)
                    {
                        $dati['imponibile_2'] = $dati['imponibile_2'] - $_totspese;
                    }
                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imponibile_2'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imponibile_2'];
                    }

                    $_parametri['iva'] = $dati['cod_iva_2'];

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

//-----------------------------------------
                //ora inseriamo l'iva..

                if ($dati['imposta_2'] != "0.00")
                {
                    //ora registriamo l'iva..
                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";

                    $_parametri['conto'] = $CONTO_IVA_VENDITE;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($CONTO_IVA_VENDITE, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imposta_2'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imposta_2'];
                    }

                    $_parametri['iva'] = $dati['cod_iva_2'];

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }
//--------------------
                //dobbiamo inserirne una per  ogni iva
                if ($dati['imponibile_3'] != "0.00")
                {

                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente
                    $_parametri['conto'] = $dati['cod_conto'];
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($dati['cod_conto'], "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];

                    //dobbiamo verificicare sempre se l'iva è associaa al cliente e se ci sono spese..
                    if ($dati['cod_iva_3'] == $_ivaspese)
                    {
                        $dati['imponibile_3'] = $dati['imponibile_3'] - $_totspese;
                    }
                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imponibile_3'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imponibile_3'];
                    }

                    $_parametri['iva'] = $dati['cod_iva_3'];

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

//-----------------------------------------
                //ora inseriamo l'iva..

                if ($dati['imposta_3'] != "0.00")
                {
                    //ora registriamo l'iva..
                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";

                    $_parametri['conto'] = $CONTO_IVA_VENDITE;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($CONTO_IVA_VENDITE, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imposta_3'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imposta_3'];
                    }

                    $_parametri['iva'] = $dati['cod_iva_3'];

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }
//-----------------------------
                //dobbiamo inserirne una per  ogni iva
                if ($dati['imponibile_4'] != "0.00")
                {

                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente
                    $_parametri['conto'] = $dati['cod_conto'];
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($dati['cod_conto'], "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    //dobbiamo verificicare sempre se l'iva è associaa al cliente e se ci sono spese..
                    if ($dati['cod_iva_4'] == $_ivaspese)
                    {
                        $dati['imponibile_4'] = $dati['imponibile_4'] - $_totspese;
                    }

                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imponibile_4'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imponibile_4'];
                    }

                    $_parametri['iva'] = $dati['cod_iva_4'];

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

//-----------------------------------------
                //ora inseriamo l'iva..

                if ($dati['imposta_4'] != "0.00")
                {
                    //ora registriamo l'iva..
                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";

                    $_parametri['conto'] = $CONTO_IVA_VENDITE;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($CONTO_IVA_VENDITE, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];


                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imposta_4'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imposta_4'];
                    }

                    $_parametri['iva'] = $dati['cod_iva_4'];

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }
//---------------------
                //dobbiamo inserirne una per  ogni iva
                if ($dati['imponibile_5'] != "0.00")
                {

                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";
                    //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente
                    $_parametri['conto'] = $dati['cod_conto'];
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($dati['cod_conto'], "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];

                    //dobbiamo verificicare sempre se l'iva è associaa al cliente e se ci sono spese..
                    if ($dati['cod_iva_5'] == $_ivaspese)
                    {
                        $dati['imponibile_5'] = $dati['imponibile_5'] - $_totspese;
                    }

                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imponibile_5'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imponibile_5'];
                    }

                    $_parametri['iva'] = $dati['cod_iva_5'];

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }

//-----------------------------------------
                //ora inseriamo l'iva..

                if ($dati['imposta_5'] != "0.00")
                {
                    //ora registriamo l'iva..
                    //se buon fine azzeriamo le variabili
                    $_parametri['iva'] = "";
                    $_parametri['conto'] = "";
                    $_parametri['desc_conto'] = "";
                    $_parametri['dare'] = "";
                    $_parametri['avere'] = "";

                    $_parametri['conto'] = $CONTO_IVA_VENDITE;
                    //richiediamo la descrizione di questo conto...
                    $_desc_conto = piano_conti($CONTO_IVA_VENDITE, "singolo");
                    $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                    //controllo se è una nota credito
                    if ($_parametri['segno'] == "N")
                    {
                        $_parametri['dare'] = $dati['imposta_5'];
                    }
                    else
                    {
                        $_parametri['avere'] = $dati['imposta_5'];
                    }

                    $_parametri['iva'] = $dati['cod_iva_5'];

                    //inseriamo i dati
                    $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    if ($_result['errori']['errore'] == "errore")
                    {
                        echo "<h2>errore nell'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                        exit;
                    }
                }



                //visto che è tutto ok..
                //setto la fatura vendita a portata in contabilità..

                $query = "UPDATE fv_testacalce set status='saldato', contabilita = 'SI' WHERE anno='$_anno' AND ndoc='$_ndoc' LIMIT 1";

                mysql_query($query, $conn) or mysql_error();

                //Ora azzero le variabili usate..
                $_parametri = "";
                $_data_gior = "";
                $_data_reg = "";
                $_causale = "";
                $_testo = "";
                $_data_testo = "";
                $ivasis = "";
                $_ivaspese = "";
                $_totspese = "";

                echo "<h3>Immesso numero in contabilita $_nreg per documento nr $_ndoc</h3>\n";
            }
        }
    } // fine della selezione documenti. foreach
// INIZIO PARTE VISIVA DELLA GENERAZIONE..

    echo "<h2> Importazione Fatture in contabilit&agrave; </h2> ";

    echo "<h3> Se non appaiono errori a video la importazione &egrave; stata eseguita con successo</h3>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>