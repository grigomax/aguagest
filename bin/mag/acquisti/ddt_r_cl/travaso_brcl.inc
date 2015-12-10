<?php

// files d'importazione conferme d'ordine in maniera totale....
// motore di elaborazione dati per l'importazione dei documenti
// per qualsiasi informazione... info@grigomx.it
// files d'importazione conferme d'ordine in maniera totale....
// motore di elaborazione dati per l'importazione dei documenti
// per qualsiasi informazione... massimo@mcetechnik.it
// travaso preventivo vendita in conferma d'ordine
// funzione che mi inserisce tutti i documenti nel carrello

/**
 * funzione che mi permette di sapere se il numero è ancora disponibile.. ed eventualemte cercarne uno..
 * restituisce $_ndoc con il numero del nuovo documento..
 * @global $conn $conn
 * @param <type> $_cosa
 * @param <type> $_tdoc tipo documento
 * @param <type> $_ndoc
 * @param <type> $_anno
 */
function insert_tot_doc_ddtreso($_annodaimp, $_codini, $_codfine, $_ndoc, $_annondoc, $_daydoc, $_mesedoc, $_annodoc, $_ddtacq, $_fatturacq)
{
    global $conn;

//Formole variabili
    $_codicefor = $_SESSION['fornitore'];
    $_id = session_id();
    $_SESSION['annodaimp'] = $_annodaimp;
    $_SESSION['codini'] = $_codini;
    $_SESSION['codfine'] = $_codfine;
    $_SESSION['ndoc'] = $_ndoc;
    $_SESSION['annondoc'] = $_annondoc;
    $_SESSION['daydoc'] = $_daydoc;
    $_SESSION['mesedoc'] = $_mesedoc;
    $_SESSION['annodoc'] = $_annodoc;
    $_SESSION['ddtacq'] = $_ddtacq;
    $_SESSION['fatturacq'] = $_fatturacq;

    // inseriamo i corpi documenti del corpo documenti e facciamo i calcoli dell'imponibile...
    //prelevo tutte le conferme d'ordine

    $query = sprintf("SELECT * FROM bv_bolle where anno=\"%s\" and utente=\"%s\" and ndoc >= \"%s\" and ndoc <= \"%s\" and (status = 'stampato' OR status = 'parziale') AND causale != 'VENDITA' order by ndoc", $_annodaimp, $_codicefor, $_codini, $_codfine);

    // Esegue la query...
    $res3 = mysql_query($query, $conn) or mysql_error();

    // Tutto procede a meraviglia...
    while ($dati3 = mysql_fetch_array($res3))
    {
        //creo una query annidata..
        // leggo il corpo documenti
        $query = sprintf("SELECT * from bv_dettaglio where anno=\"%s\" and ndoc=\"%s\" and utente=\"%s\" order by ndoc, rigo", $dati3['anno'], $dati3['ndoc'], $dati3['utente']);
        // Esegue la query...
        $res4 = mysql_query($query, $conn);

        // Tutto procede a meraviglia...
        while ($dati4 = mysql_fetch_array($res4))
        {
            //$_rigo = $_rigo + 1;
            //$_nettodoc = $_nettodoc + $dati4['totriga'];
            //faccio i conti dell'iva
            //$_ivariga = $_ivariga + $dati4['totrigaprovv'];
            $_qtaevasa = $dati4['qtaevasa'];

            if ($_qtaevasa == 0.00)
            {
                $_qtasaldo = $dati4['quantita'];
            }
            else
            {
                $_qtasaldo = $dati4['qtasaldo'];
            }

            //diciamo che le righe sono di default saldo no.

            if ($dati4[rsaldo] != 'SI')
            {
                $_rsaldo = "NO";
            }
            else
            {
                $_rsaldo = "SI";
            }

            $query = sprintf("insert into doc_basket( sessionid, rigo, anno, ndoc, utente, articolo, artfor, descrizione, unita, quantita,
		qtaevasa, qtaestratta, qtasaldo, rsaldo, listino, sca, scb, scc, nettovendita, totriga)
		values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",
		\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\" )", $_id, $dati4['rigo'], $dati4['anno'], $dati4['ndoc'], $_codicefor, $dati4['articolo'], $dati4['artfor'], $dati4['descrizione'], $dati4['unita'], $dati4['quantita'], $dati4['qtaevasa'], $dati4['qtaestratta'], $_qtasaldo, $_rsaldo, $dati4['listino'], $dati4['scaa'], $dati4['scab'], $dati4['scac'], $dati4['nettovendita'], $dati4['totriga']);

            // Esegue la query...
            if (mysql_query($query, $conn) != 1)
            {
                echo "Si &egrave; verificato un errore nella query basket:<br>\n\"$query\"\n";
                mysql_error();
                return -1;
            }
            // fine queri dati4
        }
        // diffrenza di query dati3
    }
}

// graffa fine funzione..


/* * *
 * Funzione di aggiornamento carrello dandogli i dati lui me li modifica nel carrello
 * attenzione cambiamo la funzione della variabile peso..
 * La $_peso divenerà il valore aggiunto dell'articolo
 * ed la variabile artfor diventerà l'articolo che diventerà nel carico
 */

function update_basket_vecchio($_rigo, $_anno, $_ndoc, $_articolo, $_codfor, $_descrizione, $_unita, $_qta, $_qtaevasa, $_qtaestratta, $_qtasaldo, $_rsaldo, $_listino, $_sca, $_scb, $_scc, $_agg, $_peso)
{
    global $conn;
    $id = session_id();

    // Stringa contenente la query di ricerca...
//  calcolo la quantit�
    $_qtasaldo = $_qta;
    $_qtasaldo = $_qta - $_qtaevasa - $_qtaestratta;

    if ($_rsaldo == "SI")
    {
        $_qtasaldo = "0.00";
    }
    else
    {
        $_rsaldo = "NO";
    }

    if ($_qtasaldo <= 0.00)
    {
        $_rsaldo = "SI";
        $_qtasaldo = "0.00";
    }

    $_listino = $_listino + $_peso;
    //chiedo il prezzo netto
    $_nettoacq = sconti_vecchio($_listino, $_scaa, $_scab, $_scac);

    $_totriga = $_nettoacq * $_qtasaldo;
// 	$_totrigaprovv = number_format(((($_totriga * $_ivart)/100)),$dec,'.','');



    $query = sprintf("update doc_basket SET articolo=\"%s\", artfor=\"%s\", descrizione=\"%s\", unita=\"%s\", quantita=\"%s\",
	qtaestratta=\"%s\", qtasaldo=\"%s\", rsaldo=\"%s\", listino=\"%s\", sca=\"%s\", scb=\"%s\", scc=\"%s\", nettovendita=\"%s\",
	totriga=\"%s\", agg=\"%s\", peso=\"%s\" WHERE sessionid=\"%s\" AND rigo=\"%s\" AND anno=\"%s\" AND ndoc=\"%s\"", $_articolo, $_codfor, $_descrizione, $_unita, $_qta, $_qtaestratta, $_qtasaldo, $_rsaldo, $_listino, $_sca, $_scb, $_scc, $_nettoacq, $_totriga, $_agg, $_peso, $id, $_rigo, $_anno, $_ndoc);


    // Esegue la query...
    if (mysql_query($query, $conn) != 1)
    {
        echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
        return -1;
    }
    return 0;
}

/* * *
 * Funzione per inserire parzialmente il ducmento all'interno del magazzino
 *
 */

function insert_parz_doc_ddtreso($_annodaimp, $_codini, $_codfine, $_ndoc, $_annondoc, $_daydoc, $_mesedoc, $_annodoc, $_ddtacq, $_fatturacq, $_chiudi)
{
    global $conn;
// finisco di prendermi le sessioni
    $_codicefor = $_SESSION['fornitore']; //contiene il codice cliente
    $_codicecli = $_SESSION['cliente']; // contiene il codice fornitore
    //	abbiamo bisogno di entrambi i codici utente sia quelli del cliente che quelli del fornitore e li riprendiamo dalle sessioni
    //
    //$_ivacli = $_SESSION['ivacli'];
    $id = session_id();

    //Setto le variabili

    $_data_us = sprintf("%s-%s-%s", $_annodoc, $_mesedoc, $_daydoc);
    $_tut = "f";
    $_tdoc = "ddtacq";
    $_rigo = "0";


    // prendiamo l'ultima documento per inserire la testata documento


    $query = sprintf("SELECT * from bv_bolle where anno=\"%s\" and utente=\"%s\" and ndoc >= \"%s\" and ndoc <= \"%s\" AND ( status='stampato' OR status='parziale') AND causale != 'VENDITA' order by ndoc", $_annodaimp, $_codicefor, $_codini, $_codfine);

    if ($res2 = mysql_query($query, $conn))
    {
        // La query ?stata eseguita con successo...
        // MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
        if (mysql_num_rows($res2))
        {
            // Tutto procede a meraviglia...
            while ($dati2 = mysql_fetch_array($res2))
            {
                $_data_us = sprintf("%s-%s-%s", $_annodoc, $_mesedoc, $_daydoc);

                //setto a modo parziale il documento in eleborazione..
                // e inserisco sul database conferma il numero documento bolla ed anno che è stato evaso
                // esempio conferma d'ordine evasa con d.d.t. n. del ecc....

                $query = sprintf("UPDATE bv_bolle SET status='parziale', tdocevaso='ddtacq', evasonum=\"%s\", evasoanno=\"%s\" where anno=\"%s\" and ndoc=\"%s\" and utente=\"%s\"", $_ndoc, $_annondoc, $_annodaimp, $dati2['ndoc'], $_codicefor);

                // Esegue la query...
                if (mysql_query($query, $conn) != 1)
                {
                    echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
                    return -1;
                }

                //prenderi da qui
                // leggo il corpo documenti
                $query = sprintf("SELECT * from doc_basket where sessionid = \"%s\" AND anno=\"%s\" and ndoc=\"%s\" AND utente=\"%s\"
		    AND qtaestratta != '0.00' ORDER BY ndoc, rigo", $id, $dati2['anno'], $dati2['ndoc'], $dati2['utente']);

                // Esegue la query...
                if ($res4 = mysql_query($query, $conn) < 1)
                {
                    echo "Si &egrave; verificato un errore nella query: perl basket<br>\n\"$query\"\n";
                    return -1;
                }

                $res4 = mysql_query($query, $conn);

                if (mysql_num_rows($res4))
                {
                    // Tutto procede a meraviglia...
                    while ($dati4 = mysql_fetch_array($res4))
                    {
                        $_rigo = $_rigo + 1;

                        //Calcolo quantita e valori riga
                        //Devo riportarmi i campi in variabili

                        $_qtaestratta = $dati4['qtaestratta'];
                        $_nettoacq = $dati4['nettovendita'];

                        //  calcolo la quantita
                        $_qta = $_qtaestratta;
                        $_totriga = $_nettoacq * $_qta;

                        //Dobbiamo vedere se bisogna cambiare codice articolo.
                        if ($dati4[artfor] != "")
                        {
                            $_codice = $dati4['artfor'];
                        }
                        else
                        {
                            $_codice = $dati4['articolo'];
                        }

                        // inserisco i dati nella tabella magazzino
                        $_tut = "f";
                        $_tdoc = "ddtacq";

                        $query = sprintf("insert into magazzino( tdoc, anno, ndoc, datareg, tut, rigo, utente, articolo, qtacarico,
			    valoreacq, ddtfornitore, fatturacq, protoiva ) values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",
			    \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_tdoc, $_annondoc, $_ndoc, $_data_us, $_tut, $_rigo, $_codicecli, $_codice, $_qta, $_totriga, $_ddtacq, $_fatturacq, $_protoiva);

                        // Esegue la query...

                        if (mysql_query($query, $conn) != 1)
                        {
                            echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
                            return -1;
                        }

                        if ($dati4['agg'] == "SI")
                        {
                            // aggiornaiamo la anagrafica articoli,
                            // 				verifichiamo se bisogna aggiornare il fornitore uno o due
                            // 				partiamo con l'uno
                            $_ultacq = $_totriga / $_qta;

                            $query = sprintf("UPDATE articoli SET preacqnetto=\"%s\", prelisacq=\"%s\", scaa=\"%s\", scab=\"%s\",
				scac=\"%s\", ultacq=\"%s\" where articolo=\"%s\" and fornitore=\"%s\"", $dati4['nettoacq'], $dati4['listino'], $dati4['sca'], $dati4['scb'], $dati4['scc'], $_ultacq, $dati4['articolo'], $dati4['utente']);

                            $resagg = mysql_query($query, $conn);
                            // se le righe prese sono uguali a zero vuol dire che è il secondo
                            if (mysql_affected_rows($conn) < 1)
                            {
                                $query = sprintf("UPDATE articoli SET preacqnetto2=\"%s\", ultacq=\"%s\" where articolo=\"%s\" and fornitore2=\"%s\"", $dati4['nettoacq'], $_ultacq, $dati4['articolo'], $dati4['utente']);

                                //eseguiamo
                                // Esegue la query...
                                if (mysql_query($query, $conn) != 1)
                                {
                                    echo "Si &egrave; verificato un errore nella query: n 1<br>\n\"$query\"\n";
                                    return -1;
                                }
                            }
                        }
                        else
                        {

                            //calcoliamo il prezzo netto
                            // aggiornamo l'ultimo prezzo acquisto
                            $_ultacq = $_totriga / $_qta;
                            $query = sprintf("UPDATE articoli SET ultacq=\"%s\" where articolo=\"%s\"", $_ultacq, $_codice);

                            // Esegue la query...
                            if (mysql_query($query, $conn) != 1)
                            {
                                echo "Si &egrave; verificato un errore nella query: ultimo acquisto<br>\n\"$query\"\n";
                                return -1;
                            }
                        }
                        // fine queri dati4
                    }
                }

                if ($_chiudi == "SI")
                {

                    // vuol dire che si può evadere il documento
                    $query = sprintf("UPDATE bv_bolle SET status='evaso', tdocevaso='ddtacq', evasonum=\"%s\", evasoanno=\"%s\"
			where anno=\"%s\" and ndoc=\"%s\" and utente=\"%s\"", $_ndoc, $_annondoc, $_annodaimp, $dati2['ndoc'], $_codicefor);

                    // Esegue la query...
                    if (mysql_query($query, $conn) != 1)
                    {
                        echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
                        return -1;
                    }
                }
                // diffrenza di query dati3
                //Se non ci sono errori abbiamo finito.
            }
        }
    }



    // A questo punto se tutto è ok procedo a svuotare il carrello..

    $query = sprintf("DELETE from doc_basket where sessionid = \"%s\"", $id);

    // Esegue la query...
    if (mysql_query($query, $conn) != 1)
    {
        echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
        return -1;
    }

//Se non ci sono errori abbiamo finito.
}

//funzion che mi crea il prezzo netto partendo dal lordo
function sconti_vecchio($_listino, $_sca, $_scb, $_scc)
{
//funzione di generazione prezzo netto
    $_nettovendita = $_listino;

    if ($_sca != 0)
        $_nettovendita = ($_listino - number_format(((($_listino * $_sca) / 100)), '6', '.', ''));
    if ($_scb != 0)
        $_nettovendita = ($_nettovendita - number_format(((($_nettovendita * $_scb) / 100)), '6', '.', ''));
    if ($_scc != 0)
        $_nettovendita = ($_nettovendita - number_format(((($_nettovendita * $_scc) / 100)), '6', '.', ''));

    return $_nettovendita;
}

/**
 * funzione che mi permette di sapere se il numero è ancora disponibile.. ed eventualemte cercarne uno..
 * restituisce $_ndoc con il numero del nuovo documento..
 * @global $conn $conn
 * @param <type> $_cosa
 * @param <type> $_tdoc tipo documento
 * @param <type> $_ndoc
 * @param <type> $_anno
 */
function disponibilita_ndoc($_cosa, $_tdoc, $_ndoc, $_anno)
{

    global $conn;
    //convertiamo il nome documento
    if ($_tdoc == "ddtacq")
    {
        $_tut = "f";

        // verifico la presenza del documento appena generato..
        // onde evitare la simultaneit� del numero e quindi l'errore
        //cerco la riga se c'� l'aggiorno, se non c'� la inserisco
        $query = sprintf("SELECT * FROM magazzino WHERE tdoc=\"%s\" AND anno=\"%s\" AND ndoc=\"%s\"", $_tdoc, $_anno, $_ndoc) or die("Errore!");
        //esegue la query
        $res = mysql_query($query, $conn);

        if (mysql_num_rows($res) > 0)
        {
            //se è accupato.. aggiungo un numero e riprovo...
            $_ndoc_n = $_ndoc + 1;
            //cerchiamo se il documento è libero..
            $query = sprintf("SELECT * FROM magazzino WHERE tdoc=\"%s\" AND anno=\"%s\" AND ndoc=\"%s\"", $_tdoc, $_anno, $_ndoc_n) or die("Errore!");
            //esegue la query
            $res = mysql_query($query, $conn);

            if (mysql_num_rows($res) > 0)
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
                $_ndoc = $_ndoc_n;
            }
        }
    }
    else
    {
        $_archivio = archivio_tdoc_vecchio($_tdoc);

        //bisogna fare tre divisioni..
        //quando si inserisce quando si aggiorna e quando si travasa..
        //cerchiamo se il documento è libero..
        $query = sprintf("SELECT * FROM %s WHERE anno=\"%s\" AND ndoc=\"%s\"", $_archivio['testacalce'], $_anno, $_ndoc) or die("Errore!");
        //esegue la query
        $res = mysql_query($query, $conn);

        if (mysql_num_rows($res) > 0)
        {
            //se è accupato.. aggiungo un numero e riprovo...
            $_ndoc_n = $_ndoc + 1;
            //cerchiamo se il documento è libero..
            $query = sprintf("SELECT * FROM %s WHERE anno=\"%s\" AND ndoc=\"%s\"", $_archivio['testacalce'], $_anno, $_ndoc_n) or die("Errore!");
            //esegue la query
            $res = mysql_query($query, $conn);

            if (mysql_num_rows($res) > 0)
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
                $_ndoc = $_ndoc_n;
            }
        }
    }


    return $_ndoc;
}

//funzione che mi restituisce il nome degli archivio in base al documento..
//mi passa un arrey con nome, archivioo testa, e dettaglio.
function archivio_tdoc_vecchio($_tdoc)
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

// graffa fine funzione..
?>