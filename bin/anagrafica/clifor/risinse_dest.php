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
    echo "<span class=\"testo_blu\"><h2>Verifica inserimento Destinazioni</h2><br></span></font>";

    echo "<table border=\"0\" align=\"center\" width=\"80%\">";

// inserisci
    $_azione = $_POST['azione'];
    $_tut = $_GET['tut'];
    $_codice = $_POST['codice'];
    $_utente = $_POST['utente'];


    if ($_azione == "Inserisci")
    {
// verifica inserimento cliente


        if ($_tut == "c")
        {
            $query = sprintf("select codice, utente from destinazioni where codice=\"%s\" AND utente=\"%s\"", $_POST['codice'], $_POST['utente']);
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

            echo "<tr><td>Fai indietro con il browser per non perdere i dati inseriti.<br> Poi cambia codice utente</td></tr>\n";

            exit;
        }
        else
        {


            // inserimento cliente
            // chiamo la funzione che mi scrive i dati nella tabella magazzino e
            // mi scrive i dati nel magazzino...
            $_data_reg = date('Y-m-d');
            if ($_tut == "c")
            {
                $query = sprintf("INSERT INTO destinazioni ( utente, codice, datareg, dragsoc, dragsoc2, dindirizzo, dcap, dcitta, dprov, dcodnazione, telefonodest, faxdest, demail, dcontatto, predefinito ) VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_POST['utente'], $_POST['codice'], $_data_reg, addslashes($_POST['dragsoc']), addslashes($_POST['dragsoc2']), addslashes($_POST['dindirizzo']), $_POST['dcap'], addslashes($_POST['dcitta']), $_POST['dprov'], $_POST['dcodnazione'], $_POST['telefonodest'], $_POST['faxdest'], $_POST['demail'], addslashes($_POST['dcontatto']), $_POST['predefinito']);
            }
            else
            {
                $query = sprintf("INSERT INTO fornitori ( codice, data_reg, ragsoc, ragsoc2, indirizzo, cap, citta, prov, codnazione, codfisc, piva, contatto, telefono, telefono2, cell, fax, iva, codpag, banca, abi, cab, cin, cc, iban, swift, spesometro, zona, dragsoc, dragsoc2, dindirizzo, dcap, dcitta, dprov, dcodnazione, telefonodest, faxdest, email, email2, email3, sitofor, privacy, note, vettore, porto, cod_conto, indice_pa, cod_ute_dest, es_selezione ) VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")", $_POST['codcli'], $_data_reg, addslashes($_POST['ragsoc']), addslashes($_POST['ragsoc2']), addslashes($_POST['indirizzo']), $_POST['cap'], addslashes($_POST['citta']), $_POST['prov'], $_POST['codnazione'], $_POST['codfisc'], $_POST['piva'], $_POST['contatto'], $_POST['telefono'], $_POST['telefono2'], $_POST['cell'], $_POST['fax'], $_POST['iva'], $_POST['codpag'], $_banca, $_abi, $_cab, $_cin, $_cc, $_iban, $_swift, $_POST['spesometro'], $_POST['zona'], $_POST['dragsoc'], $_POST['dragsoc2'], $_POST['dindirizzo'], $_POST['dcap'], $_POST['dcitta'], $_POST['dprov'], $_POST['dcodnazione'], $_POST['telefonodest'], $_POST['faxdest'], $_POST['email'], $_POST['email2'], $_POST['email3'], $_POST['sitocli'], $_POST['privacy'], $_note, $_POST['vettore'], $_POST['porto'], $_POST['cod_conto'], $_POST['indice_pa'], $_POST['cod_ute_dest'], $_POST['es_selezione']);
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


            echo "<tr><td> Utente $_POST[utente]  - codice $_POST[codice] inserito correttamente</td></tr>\n";
        }// fine graffa else
    }// fine graffa funzione


    if ($_azione == "Aggiorna")
    {

//	Query modifica includo files variabili

        $_data_reg = date('Y-m-d');

        //connessione
        // Stringa contenente aggiornamento
        // eccola

        if ($_tut == "c")
        {
            $query = sprintf("UPDATE destinazioni SET dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", dcodnazione=\"%s\", telefonodest=\"%s\", faxdest=\"%s\", demail=\"%s\", dcontatto=\"%s\", predefinito=\"%s\" WHERE utente=\"%s\" AND codice=\"%s\"", addslashes($_POST['dragsoc']), addslashes($_POST['dragsoc2']), addslashes($_POST['dindirizzo']), $_POST['dcap'], addslashes($_POST['dcitta']), $_POST['dprov'], $_POST['dcodnazione'], $_POST['telefonodest'], $_POST['faxdest'], $_POST['demail'], addslashes($_POST['dcontatto']), $_POST['predefinito'], $_utente, $_codice);
        }
        else
        {
            $query = sprintf("UPDATE fornitori SET ragsoc=\"%s\" , ragsoc2=\"%s\", indirizzo=\"%s\", cap=\"%s\", citta=\"%s\", prov=\"%s\", codnazione=\"%s\", codfisc=\"%s\", piva=\"%s\", contatto=\"%s\", telefono=\"%s\", telefono2=\"%s\", cell=\"%s\",fax=\"%s\",iva=\"%s\", codpag=\"%s\", banca=\"%s\", abi=\"%s\", cab=\"%s\", cin=\"%s\", cc=\"%s\", iban=\"%s\", swift=\"%s\", spesometro=\"%s\", zona=\"%s\", dragsoc=\"%s\", dragsoc2=\"%s\", dindirizzo=\"%s\", dcap=\"%s\", dcitta=\"%s\", dprov=\"%s\", dcodnazione=\"%s\", telefonodest=\"%s\", faxdest=\"%s\", email=\"%s\", email2=\"%s\", email3=\"%s\", sitofor=\"%s\", privacy=\"%s\", note=\"%s\", vettore=\"%s\", porto=\"%s\" , cod_conto=\"%s\", indice_pa='$_POST[indice_pa]', cod_ute_dest='$_POST[cod_ute_dest]', es_selezione='$_POST[es_selezione]' WHERE codice=\"%s\"", $_POST['ragsoc'], $_POST['ragsoc2'], $_POST['indirizzo'], $_POST['cap'], $_POST['citta'], $_POST['prov'], $_POST['codnazione'], $_POST['codfisc'], $_POST['piva'], $_POST['contatto'], $_POST['telefono'], $_POST['telefono2'], $_POST['cell'], $_POST['fax'], $_POST['iva'], $_POST['codpag'], $_banca, $_abi, $_cab, $_cin, $_cc, $_iban, $_swift, $_POST['spesometro'], $_POST['zona'], $_POST['dragsoc'], $_POST['dragsoc2'], $_POST['dindirizzo'], $_POST['dcap'], $_POST['dcitta'], $_POST['dprov'], $_POST['dcodnazione'], $_POST['telefonodest'], $_POST['faxdest'], $_POST['email'], $_POST['email2'], $_POST['email3'], $_POST['sitocli'], $_POST['privacy'], $_note, $_POST['vettore'], $_POST['porto'], $_POST['cod_conto'], $_POST['codcli']);
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

        if ($_tut == "c")
        {
            $query = "DELETE FROM destinazioni WHERE codice='$_codice' AND utente='$_utente' limit 1";
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
            echo "Eliminazione Utente $_utente non riuscita";
        }
        else
        {
            echo "Eliminazione Utente $_utente  codice $_codice Riuscita";
        }
    }


    echo "</td></tr></table>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>