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
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "2")
{
// file unico di inserimento e aggiornamento clienti
// Inizio tabella pagina principale ----------------------------------------------------------

    echo "<table align=\"center\" width=\"80%\">\n";
    echo "<tr><td align=\"center\" width=\"80%\">";


// **********************************************************************
    echo "<span class=\"testo_blu\"><h2>Verifica inserimento Clienti/Fornitori</h2><br></span></font>";

    echo "<table border=\"0\" align=\"center\" width=\"80%\">";

// inserisci
    $_azione = $_POST['azione'];
    $_tut = $_GET['tut'];

    $_note = addslashes($_POST['note']);


    //Verifichiamo se i campi sono pieni piva e codfisc

    if ($_POST['piva'] == '')
    {
        echo "<h3>Impossibile continuare campo partita iva vuota</h3>\n";
        exit;
    }


    //disabilitiamo il controllo iva per i ristoranti e schede carburanti
    
    if (($_POST['piva'] != "CARBU") AND ( $_POST['piva'] != "RISTO"))
    {
        //inseriamo qui il controllo partita iva..
        $_check = controllaPIVA($_POST['piva']);

        if ($_check['result'] == "NO")
        {
            echo "<br>$_check[result] - $_check[errore] <br>\n";
            echo "<h3>Impossibile continuare campo partita Errati</h3>\n";
            exit;
        }
        else
        {
            if ($_check['result'] == "CE")
            {
                $_nazione = $_check['nazione'];


                if ($_check['errore'] != "")
                {

                    echo "<br><h3><font color=\"red\">" . $_check['errore'] . "</font>\n";
                    //qui creiamo un array con le partite ive..
                    echo "<br> partita iva trovata..<br>" . $_POST['piva'] . " Di caratteri $_check[numero]";
                    echo "<br>" . $_check[$_nazione]['nome'];
                    //echo "<br>".$_check[$_nazione]['numero'];
                    echo "<br>" . $_check[$_nazione]['tipologia'];
                }
            }

            if ($_check['result'] == "ext")
            {
                //qui creiamo un array con le partite ive..
                echo $_check['nuova'];
                if ($_check['nuova'] != "")
                {
                    $_POST['piva'] = $_check['nuova'];
                }
            }
        }
    }
    //controlliamo il codice fiscale

    if ($_POST['codfisc'] != '')
    {
        $_codfisc = codiceFiscale($_POST['codfisc']);

        if ($_codfisc['result'] == "NO")
        {
            echo "<br>$_codfisc[result] - $_codfisc[errore] <br>\n";
            echo "<h3>Impossibile continuare campo Codice Fiscale</h3>\n";
            exit;
        }
    }


    if ($_azione == "Inserisci")
    {
// verifica inserimento cliente


        if ($_tut == "c")
        {
            $query = sprintf("select codice, piva from clienti where codice=\"%s\" OR piva=\"%s\"", $_POST['codcli'], $_POST['piva']);
        }
        else
        {
            $query = sprintf("select codice, piva from fornitori where codice=\"%s\" OR piva=\"%s\"", $_POST['codcli'], $_POST['piva']);
        }
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        if ($result->rowCount() > 0)
        {

            foreach ($result AS $dati)
                ;

            echo "<tr><td><b>Il utente inserito &egrave; gi&agrave; esistente nell'archivio.</td></tr>\n";

            echo "<tr><td>Controllare i seguenti campi nell'archivio per verificare la presenza</td></tr>\n";

            printf("<tr><td>Codice utente immesso = \"%s\", codice utente trovato nell'archivio \"%s\"</td></tr>\n", $_POST['codcli'], $dati['codice']);

            printf("<tr><td>Numero Partita Iva = \"%s\", Partita iva trovata nell'archivio \"%s\"</td></tr>\n", $_POST['piva'], $dati['piva']);

            echo "<tr><td>Fai indietro con il browser per non perdere i dati inseriti.<br> Poi cambia codice utente</td></tr>\n";

            exit;
        }
        else
        {

            if ($_POST['istituto'] != "")
            {
                // mi prelevo la banca prima di inserire il cliente
// 		se è la mi la inserisco altrimenti iserisco quella immessa
                // Stringa contenente la query di ricerca...

                $datiba = tabella_banche("singola", $_POST['istituto'], $_abi, $_cab, $_parametri);

                $_banca = addslashes($datiba['banca']);
                $_abi = $datiba['abi'];
                $_cab = $datiba['cab'];
                $_cc = $datiba['cc'];
                $_cin = $datiba['cin'];
                $_iban = $datiba['iban'];
                $_swift = $datiba['swift'];
            }
            else
            {
                $_banca = addslashes($_POST['banca']);
                $_abi = $_POST['abi'];
                $_cab = $_POST['cab'];
                $_cc = $_POST['cc'];
                $_cin = $_POST['cin'];
                $_iban = $_POST['iban'];
                $_swift = $_POST['swift'];
            }




            // inserimento cliente
            // chiamo la funzione che mi scrive i dati nella tabella magazzino e
            // mi scrive i dati nel magazzino...
            $_data_reg = date('Y-m-d');
            if ($_tut == "c")
            {
                $query = sprintf("INSERT INTO clienti ( codice, data_reg, ragsoc, ragsoc2, indirizzo, cap, citta, prov, codnazione, codfisc, piva, contatto, telefono, telefono2, cell, fax, iva, codpag, banca, abi, cab, cin, cc, iban, swift, scontocli, scontocli2, scontocli3, listino, codagente, zona, dragsoc, dragsoc2, dindirizzo, dcap, dcitta, dprov, dcodnazione, telefonodest, faxdest, email, email2, email3, sitocli, bloccocli, privacy, note, vettore, porto, nintento, nproto, cod_conto, indice_pa, cod_ute_dest, es_selezione ) VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_POST['codcli'], $_data_reg, addslashes($_POST['ragsoc']), addslashes($_POST['ragsoc2']), addslashes($_POST['indirizzo']), $_POST['cap'], addslashes($_POST['citta']), $_POST['prov'], $_POST['codnazione'], $_POST['codfisc'], $_POST['piva'], addslashes($_POST['contatto']), $_POST['telefono'], $_POST['telefono2'], $_POST['cell'], $_POST['fax'], $_POST['iva'], $_POST['codpag'], $_banca, $_abi, $_cab, $_cin, $_cc, $_iban, $_swift, $_POST['scontocli'], $_POST['scontocli2'], $_POST['scontocli3'], $_POST['listino'], $_POST['codagente'], $_POST['zona'], addslashes($_POST['dragsoc']), addslashes($_POST['dragsoc2']), addslashes($_POST['dindirizzo']), $_POST['dcap'], addslashes($_POST['dcitta']), $_POST['dprov'], $_POST['dcodnazione'], $_POST['telefonodest'], $_POST['faxdest'], $_POST['email'], $_POST['email2'], $_POST['email3'], $_POST['sitocli'], $_POST['bloccocli'], $_POST['privacy'], $_note, $_POST['vettore'], $_POST['porto'], $_POST['nintento'], $_POST['nproto'], $_POST['cod_conto'], $_POST['indice_pa'], $_POST['cod_ute_dest'], $_POST['es_selezione']);
            }
            else
            {
                $query = sprintf("INSERT INTO fornitori ( codice, data_reg, ragsoc, ragsoc2, indirizzo, cap, citta, prov, codnazione, codfisc, piva, contatto, telefono, telefono2, cell, fax, iva, codpag, banca, abi, cab, cin, cc, iban, swift, spesometro, zona, dragsoc, dragsoc2, dindirizzo, dcap, dcitta, dprov, dcodnazione, telefonodest, faxdest, email, email2, email3, sitofor, privacy, note, vettore, porto, cod_conto, indice_pa, cod_ute_dest, es_selezione ) VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_POST['codcli'], $_data_reg, addslashes($_POST['ragsoc']), addslashes($_POST['ragsoc2']), addslashes($_POST['indirizzo']), $_POST['cap'], addslashes($_POST['citta']), $_POST['prov'], $_POST['codnazione'], $_POST['codfisc'], $_POST['piva'], addslashes($_POST['contatto']), $_POST['telefono'], $_POST['telefono2'], $_POST['cell'], $_POST['fax'], $_POST['iva'], $_POST['codpag'], $_banca, $_abi, $_cab, $_cin, $_cc, $_iban, $_swift, $_POST['spesometro'], $_POST['zona'], addslashes($_POST['dragsoc']), addslashes($_POST['dragsoc2']), addslashes($_POST['dindirizzo']), $_POST['dcap'], addslashes($_POST['dcitta']), $_POST['dprov'], $_POST['dcodnazione'], $_POST['telefonodest'], $_POST['faxdest'], $_POST['email'], $_POST['email2'], $_POST['email3'], $_POST['sitocli'], $_POST['privacy'], $_note, $_POST['vettore'], $_POST['porto'], $_POST['cod_conto'], $_POST['indice_pa'], $_POST['cod_ute_dest'], $_POST['es_selezione']);
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

                echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
            }

            //mysql_query( $query, $conn );
            //}


            echo "<tr><td> Utente $_POST[codcli] inserito correttamente</td></tr>\n";
        }// fine graffa else
    }// fine graffa funzione


    if ($_azione == "Aggiorna")
    {

//	Query modifica includo files variabili

        $_data_reg = date('Y-m-d');


        if ($_POST['istituto'] != "")
        {
            // mi prelevo la banca prima di inserire il cliente
// 		se è la mi la inserisco altrimenti iserisco quella immessa
            // Stringa contenente la query di ricerca...
            $datiba = tabella_banche("singola", $_POST['istituto'], $_abi, $_cab, $_parametri);

            $_banca = addslashes($datiba['banca']);
            $_abi = $datiba['abi'];
            $_cab = $datiba['cab'];
            $_cc = $datiba['cc'];
            $_cin = $datiba['cin'];
            $_iban = $datiba['iban'];
            $_swift = $datiba['swift'];
        }
        else
        {
            $_banca = addslashes($_POST['banca']);
            $_abi = $_POST['abi'];
            $_cab = $_POST['cab'];
            $_cc = $_POST['cc'];
            $_cin = $_POST['cin'];
            $_iban = $_POST['iban'];
            $_swift = $_POST['swift'];
        }


//connessione
        // Stringa contenente aggiornamento
        // eccola

        if ($_tut == "c")
        {
            
            $query = sprintf("UPDATE clienti SET ragsoc=\"%s\" , ragsoc2=\"%s\", indirizzo=\"%s\", cap=\"%s\", citta=\"%s\", prov=\"%s\", codnazione=\"%s\", codfisc=\"%s\", piva=\"%s\", contatto=\"%s\", telefono=\"%s\", telefono2=\"%s\",cell=\"%s\", fax=\"%s\", iva=\"%s\", codpag=\"%s\", banca=\"%s\", abi=\"%s\", cab=\"%s\", cin=\"%s\", cc=\"%s\", iban=\"%s\", swift=\"%s\", scontocli=\"%s\", scontocli2=\"%s\", scontocli3=\"%s\", listino=\"%s\", codagente=\"%s\", zona=\"%s\", dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", dcodnazione=\"%s\", telefonodest=\"%s\", faxdest=\"%s\", email=\"%s\", email2=\"%s\", email3=\"%s\", sitocli=\"%s\", bloccocli=\"%s\", privacy=\"%s\", note=\"%s\", vettore=\"%s\", porto=\"%s\", nintento=\"%s\", nproto=\"%s\", cod_conto=\"%s\", indice_pa='$_POST[indice_pa]', cod_ute_dest='$_POST[cod_ute_dest]', es_selezione='$_POST[es_selezione]' WHERE codice=\"%s\"", addslashes($_POST['ragsoc']), addslashes($_POST['ragsoc2']), addslashes($_POST['indirizzo']), $_POST['cap'], addslashes($_POST['citta']), $_POST['prov'], $_POST['codnazione'], $_POST['codfisc'], $_POST['piva'], addslashes($_POST['contatto']), $_POST['telefono'], $_POST['telefono2'], $_POST['cell'], $_POST['fax'], $_POST['iva'], $_POST['codpag'], $_banca, $_abi, $_cab, $_cin, $_cc, $_iban, $_swift, $_POST['scontocli'], $_POST['scontocli2'], $_POST['scontocli3'], $_POST['listino'], $_POST['codagente'], $_POST['zona'], addslashes($_POST['dragsoc']), addslashes($_POST['dragsoc2']), addslashes($_POST['dindirizzo']), $_POST['dcap'], addslashes($_POST['dcitta']), $_POST['dprov'], $_POST['dcodnazione'], $_POST['telefonodest'], $_POST['faxdest'], $_POST['email'], $_POST['email2'], $_POST['email3'], $_POST['sitocli'], $_POST['bloccocli'], $_POST['privacy'], $_note, $_POST['vettore'], $_POST['porto'], $_POST['nintento'], $_POST['nproto'], $_POST['cod_conto'], $_POST['codcli']);
        }
        else
        {
            $query = sprintf("UPDATE fornitori SET ragsoc=\"%s\" , ragsoc2=\"%s\", indirizzo=\"%s\", cap=\"%s\", citta=\"%s\", prov=\"%s\", codnazione=\"%s\", codfisc=\"%s\", piva=\"%s\", contatto=\"%s\", telefono=\"%s\", telefono2=\"%s\", cell=\"%s\",fax=\"%s\",iva=\"%s\", codpag=\"%s\", banca=\"%s\", abi=\"%s\", cab=\"%s\", cin=\"%s\", cc=\"%s\", iban=\"%s\", swift=\"%s\", spesometro=\"%s\", zona=\"%s\", dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", dcodnazione=\"%s\", telefonodest=\"%s\", faxdest=\"%s\", email=\"%s\", email2=\"%s\", email3=\"%s\", sitofor=\"%s\", privacy=\"%s\", note=\"%s\", vettore=\"%s\", porto=\"%s\" , cod_conto=\"%s\", indice_pa='$_POST[indice_pa]', cod_ute_dest='$_POST[cod_ute_dest]', es_selezione='$_POST[es_selezione]' WHERE codice=\"%s\"", addslashes($_POST['ragsoc']), addslashes($_POST['ragsoc2']), addslashes($_POST['indirizzo']), $_POST['cap'], addslashes($_POST['citta']), $_POST['prov'], $_POST['codnazione'], $_POST['codfisc'], $_POST['piva'], addslashes($_POST['contatto']), $_POST['telefono'], $_POST['telefono2'], $_POST['cell'], $_POST['fax'], $_POST['iva'], $_POST['codpag'], $_banca, $_abi, $_cab, $_cin, $_cc, $_iban, $_swift, $_POST['spesometro'], $_POST['zona'], addslashes($_POST['dragsoc']), addslashes($_POST['dragsoc2']), addslashes($_POST['dindirizzo']), $_POST['dcap'], addslashes($_POST['dcitta']), $_POST['dprov'], $_POST['dcodnazione'], $_POST['telefonodest'], $_POST['faxdest'], $_POST['email'], $_POST['email2'], $_POST['email3'], $_POST['sitocli'], $_POST['privacy'], $_note, $_POST['vettore'], $_POST['porto'], $_POST['cod_conto'], $_POST['codcli']);
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

            echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
        }





        echo "<tr><td> Utente $_POST[codcli] modificato con successo</td></tr></table>\n";

// graffa di fine funzione aggiornamento
    }

    if ($_azione == "Elimina")
    {

        $_utente = $_POST['codcli'];

        if ($_tut == "c")
        {
            $query = sprintf("(select articolo, anno, ndoc, utente from magazzino where utente=\"%s\" limit 10) UNION (select articolo, anno, ndoc, utente from of_dettaglio where utente=\"%s\" limit 10) UNION (select articolo, anno, ndoc, utente from pv_dettaglio where utente=\"%s\" limit 10) UNION (select articolo, anno, ndoc, utente from co_dettaglio where utente=\"%s\" limit 10) UNION (select articolo, anno, ndoc, utente from bv_dettaglio where utente=\"%s\" limit 10) UNION (select articolo, anno, ndoc, utente from fv_dettaglio where utente=\"%s\" limit 10)", $_utente, $_utente, $_utente, $_utente, $_utente, $_utente);
            $_tipologia = "VENDITA";
        }
        else
        {
            $query = sprintf("(select articolo, anno, ndoc, utente from magazzino where utente=\"%s\" limit 10) UNION (select articolo, anno, ndoc, utente from of_dettaglio where utente=\"%s\" limit 10) UNION (select articolo, anno, ndoc, utente from magastorico where utente=\"%s\" limit 10) ", $_utente, $_utente, $_utente);
            $_tipologia = "ACQUISTO";
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
        }

        //echo $res;
        // La query e' stata eseguita con successo...
        // Non so' se ci sono articoli con quel codice...
        if ($result->rowCount() > 0)
        {
            echo "Impossibile eliminare l'utente perch&eacute; trovato nei seguenti muovimenti<BR>. $_tipologia";
            echo " <table border=1 width=\"60%\" align=\"center\"><tr>";
            echo " <td>articolo</td><td>anno</td><td>n. doc.</td><td>utente</td></tr>";
            foreach ($result AS $dati)
            {

                printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $dati['articolo'], $dati['anno'], $dati['ndoc'], $dati['utente']);
            }
            echo "</table>";
        }
        else
        {
            if ($_tut == "c")
            {
                $query = sprintf("DELETE FROM clienti WHERE codice=\"%s\" limit 1", $_utente);
            }
            else
            {
                $query = sprintf("DELETE FROM fornitori WHERE codice=\"%s\" limit 1", $_utente);
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
                echo "Eliminazione Utente $_utente riuscita";
            }
            else
            {
                echo "Eliminazione Utente $_utente Non riuscita";
            }
        }
    }

    echo "</td></tr></table>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>