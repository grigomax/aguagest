<?php

//carichiamo il percorso
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
//settiamo il tempo di sessione
session_start();
$_SESSION['keepalive'] ++;
require $_percorso . "librerie/lib_html.php";
//carichiamo le sessioni correnti
$conn = permessi_sessione("verifica_PDO", $_percorso);
require "../../librerie/motore_anagrafiche.php";

//inizio parte visiva..

base_html($_cosa, $_percorso);
echo "</head>\n";
echo "<body>\n";

testata_html($_cosa, $_percorso);

menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['setting'] > "3")
{

// ora devo prendermi i post..
// poi devo creare il files contenete la esportazione
// e lo creo in questa directory plugins



    if (($_POST['host'] == "") or ( $_POST['sito'] == "") or ( $_POST['db_server'] == "") or ( $_POST['db_user'] == "") or ( $_POST['azienda'] == "") or ( $_POST['indirizzo'] == "") or ( $_POST['cap'] == "") or ( $_POST['citta'] == "") or ( $_POST['prov'] == "") or ( $_POST['codfisc'] == "") or ( $_POST['piva'] == "") or ( $_POST['telefono'] == "" ) or ( $_POST['dec'] == "") or ( $_POST['decdoc'] == "") or ( $_POST['nlv'] == "") or ( $_POST['ivasis'] == "") or ( $_POST['ivamulti'] == "") or ( $_POST['nomedoc'] == ""))
    {
        echo "<center>";
        echo "<h2>Controllo dati inseriti</h2>";
        echo "<h3>Uno di questi campi obbligatori risulta vuoto</h3>";
        echo "Nome host server locale = " . $_POST['host'] . "<br>";
        echo "Percorso assoluto = " . $_POST['sito'] . "<br>";
        echo "Nome host server Mysql = " . $_POST['db_server'] . "<br>";
        echo "Nome utente mysql = " . $_POST['db_user'] . "<br>";
        echo "Password Mysql = " . $_POST['db_password'] . "<br>";
        echo "Ragione sociale = " . $_POST['azienda'] . "<br>";
        echo "Indirizzo = " . $_POST['indirizzo'] . "<br>";
        echo "Cap = " . $_POST['cap'] . "<br>";
        echo "Citta = " . $_POST['citta'] . "<br>";
        echo "Provincia = " . $_POST['prov'] . "<br>";
        echo "Codice Fiscale = " . $_POST['codfisc'] . "<br>";
        echo "Partita iva = " . $_POST['piva'] . "<br>";
        echo "Telefono = " . $_POST['telefono'] . "<br>";
        echo "n. decimali sistema = " . $_POST['dec'] . "<br>";
        echo "n. decimali documento = " . $_POST['decdoc'] . "<br>";
        echo "N. di listini vendita = " . $_POST['nlv'] . "<br>";
        echo "Aliquita iva di sistema = " . $_POST['ivasis'] . "<br>";
        echo "Gestione aliquote multiple = " . $_POST['ivamulti'] . "<br>";
        echo "Nome documento Fatture = " . $_POST['nomedoc'] . "<br><br>";
        echo "La preghiamo di tornare indietro e verificare i campi con l'asterisco<br>";
        echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A>";

        exit;
    }

    //verifico che se il post contabilità è stato selezionato ad esercizio avviato ci siano i campi dei parametri già inserii
    if ($_POST['CONTABILITA'] != $CONTABILITA)
    {

        if ($_POST['CONTABILITA'] == "SI")
        {
            include "../../../setting/par_conta.inc.php";

            //verifico che ci siano i parametri banche, clienti, fornitori
            if (($MASTRO_CLI == "" ) OR ( $MASTRO_FOR == "") OR ( $MASTRO_BANCHE == "") OR ( $CONTO_EFFETTI_INCASSO == "")
                    OR ( $CONTO_EFFETTI_SBF == "") OR ( $CONTO_EFFETTI_INSOLUTI == "" ) OR ( $CONTO_CLIENTI == "") OR ( $CONTO_FORNITORI == ""))
            {
                echo "<center>";
                echo "<h2>Controllo dati inseriti</h2>";
                echo "<h3>Impossibile Abilitare la contabilit&agrave;</h3>";
                echo "Non sono stati impostati i parametri clienti e fornitori";
                echo "La preghiamo di tornare indietro e di inserire i parametri base di contabilit&agrave; prima di abilitarla<br>";
                echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A>";
                exit;
            }
            else
            {
                // se i parametri sono impostati, selezioniamo un cliente a caso e verifichiamo se ha associato il codice conto mastro piano..
                // così capiamo se è una nuova aperura conti oppure solo un normale salvataggio..

                $query = "SELECT cod_conto FROM clienti where cod_conto = '$CONTO_CLIENTI'";

                $result = $conn->query($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }
//	echo $query;
                if ($result->rowCount() < 1)
                {
                    //Se è inferiore a 1 vuol dire che non ci sono dati inseriti e quindi va settato tutti i parametri..

                    $query = "UPDATE clienti SET cod_conto = '$CONTO_CLIENTI'";
                    // Esegue la query...
                    $result = $conn->exec($query);

                    if ($conn->errorCode() != "00000")
                    {
                        $_errore = $conn->errorInfo();
                        echo $_errore['2'];
                        //aggiungiamo la gestione scitta dell'errore..
                        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                        scrittura_errori($_cosa, $_percorso, $_errori);
                    }

                    $query = "UPDATE fornitori SET cod_conto = '$CONTO_FORNITORI'";
                    $result = $conn->exec($query);

                    if ($conn->errorCode() != "00000")
                    {
                        $_errore = $conn->errorInfo();
                        echo $_errore['2'];
                        //aggiungiamo la gestione scitta dell'errore..
                        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                        scrittura_errori($_cosa, $_percorso, $_errori);
                    }

                    //ed ora tocca le banche.. da inserire nel piano dei conti..
                    //quindi, leggiamo le banche e poi con un ciclo di while le inseriamo all'inserno del piano dei conti..
                    // e per ogni banca generiamo i sotto conti di livello 3

                    $query = "SELECT * FROM banche ORDER BY codice";

                    $result = $conn->query($query);

                    if ($conn->errorCode() != "00000")
                    {
                        $_errore = $conn->errorInfo();
                        echo $_errore['2'];
                        //aggiungiamo la gestione scitta dell'errore..
                        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                        scrittura_errori($_cosa, $_percorso, $_errori);
                    }

                    foreach ($result AS $dati)
                    {
                        //inseriamo il tutto nel piano dei conti..
                        $queryp = sprintf("INSERT INTO piano_conti (codconto, descrizione, natcon, livello, tipo_cf) values (\"%s%s\", \"%s\", \"A\", \"2\", \"B\")", $MASTRO_BANCHE, $dati['codice'], $dati['banca']);
                        // Esegue la query...
                        if (mysql_query($queryp, $conn) != 1)
                        {
                            echo "Si &egrave; verificato un errore nella query:<br>\n\"$queryp\"\n";
                            return -1;
                        }

                        $_descrizione = "Effetti S.B.F. $dati[banca]";
                        //inseriamo il tutto nel piano dei conti..
                        $queryp2 = sprintf("INSERT INTO piano_conti (codconto, descrizione, natcon, livello, tipo_cf) values (\"%s%s\", \"%s\", \"A\", \"3\", \"B\")", $CONTO_EFFETTI_SBF, $dati['codice'], $_descrizione);
                        $result2 = $conn->exec($query2);

                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
                            echo $_errore['2'];
                            //aggiungiamo la gestione scitta dell'errore..
                            $_errori['descrizione'] = "Errore Query = $query2 - $_errore[2]";
                            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                            scrittura_errori($_cosa, $_percorso, $_errori);
                        }

                        $_descrizione = "Effetti all incasso $dati[banca]";
                        //inseriamo il tutto nel piano dei conti..
                        $queryp2 = sprintf("INSERT INTO piano_conti (codconto, descrizione, natcon, livello, tipo_cf) values (\"%s%s\", \"%s\", \"A\", \"3\", \"B\")", $CONTO_EFFETTI_INCASSO, $dati['codice'], $_descrizione);
                        $resultp2 = $conn->exec($queryp2);

                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
                            echo $_errore['2'];
                            //aggiungiamo la gestione scitta dell'errore..
                            $_errori['descrizione'] = "Errore Query = $queryp2 - $_errore[2]";
                            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                            scrittura_errori($_cosa, $_percorso, $_errori);
                        }

                        $_descrizione = "Effetti insoluti $dati[banca]";
                        //inseriamo il tutto nel piano dei conti..
                        $queryp2 = sprintf("INSERT INTO piano_conti (codconto, descrizione, natcon, livello, tipo_cf) values (\"%s%s\", \"%s\", \"A\", \"3\", \"B\")", $CONTO_EFFETTI_INSOLUTI, $dati['codice'], $_descrizione);
                        $resultp2 = $conn->exec($queryp2);

                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
                            echo $_errore['2'];
                            //aggiungiamo la gestione scitta dell'errore..
                            $_errori['descrizione'] = "Errore Query = $queryp2 - $_errore[2]";
                            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                            scrittura_errori($_cosa, $_percorso, $_errori);
                        }
                    }
                }
            }
        }
    }



//effettuiamo il cambio dell'iva su tutti gli articoli di magazzino che corrispondono all'iva di sistema..

    if ($_POST['change'] == "si")
    {
        //effettuamo il cambio

        $query = "UPDATE articoli SET iva = '$_POST[ivasis]' where iva='$ivasis'";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = " $_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
    }

//inizio parte produttiva
// il nome del files
// nome con full percorso
//$nfile="/sito/fatture/vars2.php";
    $nfile = "../../../setting/vars.php";

// creo il files e nascondo la soluzione
    $fp = fopen($nfile, "w");
//controllo l'esito
    if (!$fp)
        die("Errore.. non sono riuscito a creare il file.. Permessi ?");

#scriviamo una riga di commento per chiarire le posizioni
// scriviamo le righe e le verifico
    //scriviamo che un file php
    $_commento = "<?php\n";
    fwrite($fp, $_commento);
    if (!$fp)
        die("Errore.. Riga non inserita ?");


    $_commento = "// file di configurazione del programma\n";
    fwrite($fp, $_commento);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_commento = 'date_default_timezone_set(\'Europe/Rome\');' . "\n";

    fwrite($fp, $_commento);
    if (!$fp)
        die("Errore.. Riga non inserita ?");
    $_commento = 'include "vars_aspetto.php";' . "\n";
    fwrite($fp, $_commento);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$host = "%s";' .
            "\n", $_POST['host']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$sito = "%s";' .
            "\n", $_POST['sito']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$title = "Agua Gest | %s";' . "\n", $_POST['azienda']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$db_server = "%s";' . "\n", $_POST['db_server']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$db_user = "%s";' . "\n", $_POST['db_user']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$db_password = "%s";' . "\n", $_POST['db_password']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$db_nomedb = "%s";' . "\n", $_POST['db_nomedb']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$azienda = "%s";' . "\n", $_POST['azienda']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$azienda2 = "%s";' . "\n", $_POST['azienda2']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$indirizzo = "%s";' . "\n", $_POST['indirizzo']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$cap = "%s";' .
            "\n", $_POST['cap']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$citta = "%s";' .
            "\n", $_POST['citta']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$prov = "%s";' .
            "\n", $_POST['prov']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$nazione = "%s";' . "\n", $_POST['nazione']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$piva = "%s";' .
            "\n", $_POST['piva']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$codfisc = "%s";' . "\n", $_POST['codfisc']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$telefono = "%s";' . "\n", $_POST['telefono']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$telefono2 = "%s";' . "\n", $_POST['telefono2']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$cell = "%s";' .
            "\n", $_POST['cell']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$fax = "%s";' .
            "\n", $_POST['fax']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

//---------------------------------------------------------------------------

    $_scrivi = sprintf('$DAZIENDA = "%s";' . "\n", $_POST['DAZIENDA']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DAZIENDA2 = "%s";' . "\n", $_POST['DAZIENDA2']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DINDIRIZZO = "%s";' . "\n", $_POST['DINDIRIZZO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DCAP = "%s";' .
            "\n", $_POST['DCAP']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DCITTA = "%s";' . "\n"
            , $_POST['DCITTA']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DPROV = "%s";' .
            "\n", $_POST['DPROV']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DNAZIONE = "%s";' . "\n", $_POST['DNAZIONE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DTELEFONO = "%s";' . "\n", $_POST['DTELEFONO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DTELEFONO2 = "%s";' . "\n", $_POST['DTELEFONO2']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DCELL = "%s";' .
            "\n", $_POST['DCELL']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DFAX = "%s";' .
            "\n", $_POST['DFAX']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");



//-----------------------------------------------------------------------------
    $_scrivi = sprintf('$nomedoc = "%s";' . "\n", $_POST['nomedoc']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    
        $_scrivi = sprintf('$SUFFIX_DDT = "%s";' . "\n", $_POST['SUFFIX_DDT']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");
    
    
    
    $_scrivi = sprintf('$SIA = "%s";'."\n", $_POST['SIA']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");


    $_scrivi = sprintf('$TIPOSOC = "%s";' . "\n", $_POST['TIPOSOC']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$REAUFFICIO = "%s";' . "\n", $_POST['REAUFFICIO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$REANUMERO = "%s";' . "\n", $_POST['REANUMERO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CAPSOCIALE = "%s";' . "\n", $_POST['CAPSOCIALE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");


    $_scrivi = sprintf('$SOCIOUNICO = "%s";' . "\n", $_POST['SOCIOUNICO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$LIQUIDAZIONE = "%s";' . "\n", $_POST['LIQUIDAZIONE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");


    $_scrivi = sprintf('$sitointernet = "%s";' . "\n", $_POST['sitointernet']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$email1 = "%s";' . "\n"
            , $_POST['email1']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$email2 = "%s";' . "\n"
            , $_POST['email2']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$email3 = "%s";' . "\n"
            , $_POST['email3']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$email4 = "%s";' . "\n"
            , $_POST['email4']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$HOSTNAME = "%s";' . "\n", $_POST['HOSTNAME']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$mailsmtp = "%s";' . "\n", $_POST['mailsmtp']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$PORTSMTP = "%s";' . "\n", $_POST['PORTSMTP']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$SSL = "%s";' .
            "\n", $_POST['SSL']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$smtpout = "%s";' . "\n", $_POST['smtpout']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$smtpuser = "%s";' . "\n", $_POST['smtpuser']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$smtppass = "%s";' . "\n", $_POST['smtppass']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$SESSIONTIME = "%s";' . "\n", $_POST['SESSIONTIME']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$dec = "%s";' .
            "\n", $_POST['dec']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$decdoc = "%s";' . "\n"
            , $_POST['decdoc']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$nlv = "%s";' .
            "\n", $_POST['nlv']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$ivasis = "%s";' . "\n"
            , $_POST['ivasis']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DATAIVA = "%s";' . "\n", $_POST['DATAIVA']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");


    $_scrivi = sprintf('$IVAMULTI = "%s";' . "\n", $_POST['ivamulti']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");


    $_scrivi = sprintf('$CONTABILITA = "%s";' . "\n", $_POST['CONTABILITA']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");



    $_scrivi = sprintf('$TERMINAL_CODE = "%s";' . "\n", $_POST['TERMINAL_CODE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$NOME_FILECODBAR = "%s";' . "\n", $_POST[
            'NOME_FILECODBAR']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$RIGHE_FILECODBAR = "%s";' . "\n", $_POST[
            'RIGHE_FILECODBAR']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");


    $_scrivi = sprintf('$CGV = "%s";' .
            "\n", $_POST['CGV']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$DEBUG = "%s";' .
            "\n", $_POST['DEBUG']);
    fwrite($fp, $_scrivi);
    if (!$fp)
        die("Errore.. Riga non inserita ?");


// chiudiamo il file php
    $_commento = "?>";
    fwrite($fp, $_commento);
    if (!$fp)
        die("Errore.. Riga non inserita ?");


// chiudiamo il files
    fclose($fp);



    echo "</body></html>";

    echo "<center>";
    echo "<h2>Se non appaiono errori a video<br> il file &egrave; stato <br>modificato con successo</h2>";
       echo "</body></html>";
}
else
{
    echo "<h2>Non hai i permessi per poter visualizzare cliente/fornitore</h2>\n";
}
?>