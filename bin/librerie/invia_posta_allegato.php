<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
//$_percorso = "../";
require_once $_percorso."../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require_once $_percorso."librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);
require_once $_percorso."librerie/motore_doc_pdo.php";

require_once $_percorso."tools/phpmailer/class.phpmailer.php";


$_azione = $_GET['azione'];

//qui creiamo una funzione per poter effettuare l'invio multiplo dei coumenti

function invio_posta($_cosa, $_percorso, $_nomefile, $_emailmittente, $_emaildestino, $_emaildestinoCC, $_emaildestinoBCC, $_oggetto, $_messaggio, $_ricevuta, $_tdoc, $_anno, $_ndoc, $_allegato, $_allegato2, $_parametri)
{
    global $conn;
    global $mailsmtp;
    global $smtpaut;
    global $smtppass;
    global $smtpuser;
    global $azienda;

    require_once $_percorso . "tools/phpmailer/PHPMailerAutoload.php";
    #require_once $_percorso . "tools/phpmailer/class.phpmailer.php";
    //require_once $_percorso . "tools/phpmailer/class.smtp.php";
    require_once $_percorso . "librerie/motore_doc_pdo.php";

    if ($_emaildestino != "")
    {

        $mail = new PHPMailer();
        $mail->SetLanguage("it", $_percorso . "tools/phpmailer/language/");
        $mail->isSMTP(); // set mailer to use SMTP

        $mail->Host = "$mailsmtp"; // specify main and backup server
        $mail->Port = "$PORTSMTP";
        $mail->SMTPAuth = "$smtpaut"; // turn on SMTP authentication
        $mail->Username = "$smtpuser"; // SMTP username
        $mail->Password = "$smtppass"; // SMTP password
        $mail->Hostname = "$HOSTNAME";

        if ($SSL != "NO")
        {
            $mail->SMTPSecure = "$SSL";                            // Enable encryption, 'ssl' also accepted
        }

        $mail->From = $_emailmittente;
        $mail->FromName = $_azienda;
        $mail->AddAddress($_emaildestino);

        if ($_emaildestinoCC != "")
        {
            $mail->AddCC($_emaildestinoCC);
        }

        if ($_emaildestinoBCC != "")
        {
            $mail->AddCC($_emaildestinoBCC);
        }

        $mail->AddReplyTo($_emailmittente);
        if ($_ricevuta == "SI")
        {
            $mail->ConfirmReadingTo = ($_emailmittente);
        }

        $mail->WordWrap = 50; // set word wrap to 50 characters
        $mail->AddAttachment($_percorso . "../spool/$_nomefile", $_nomefile); // add attachments

        if ($_allegato['verifica'] == "OK")
        {
            $mail->AddAttachment("../../spool/" . $_allegato['file']['name'] . "", $_FILES["file"]["name"]); // add attachments
        }
        
        if ($_allegato2['verifica'] == "OK")
        {
            $mail->AddAttachment("../../spool/" . $_allegato['file2']['name'] . "", $_FILES["file2"]["name"]); // add attachments
        }

        $mail->IsHTML(true); // set email format to HTML

        $mail->Subject = $_oggetto;

        $mail->Body = addslashes($_messaggio);


        if (!$mail->Send())
        {

            $_return['errore'] = "E-mail non inoltrata \n";
            $_return['result_send'] = $mail->ErrorInfo;
            $_return['risultato'] = "errore";
            //passiamo il tipo di documento alla funzione per sapere che databse cambiare..

            $db_doc = archivio_tdoc($_tdoc);

            $query = sprintf("UPDATE $db_doc[testacalce] SET invio='er' where anno=\"%s\" and ndoc=\"%s\"", $_anno, $_ndoc);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query 1 = $query - $_errore[2]";
                $_errori['files'] = "invia_posta_allegato.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_return['errore_sql'] = "E-mail non inoltrata ed inoltre Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
                $_return['risultato_sql'] = "errore";
            }
            echo "<h4>Errore Inoltro email = $_emaildestino per documento nr $_ndoc <h4>\n";
            echo "Tipo di errore: " . $mail->ErrorInfo;
        }
        else
        {
            $_return['errore'] = "E-mail Inoltrata \n";
            #$_return['result_send'] = $mail->ErrorInfo;
            $_return['risultato'] = "";

            $db_doc = archivio_tdoc($_tdoc);

            $query = sprintf("UPDATE $db_doc[testacalce] SET invio='si' where anno=\"%s\" and ndoc=\"%s\"", $_anno, $_ndoc);

            echo "<h4>Email inoltrata correttamente all'indirizzo = $_emaildestino per documento nr $_ndoc <h4>\n";

            $result = $conn->exec($query);
            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query 2 = $query - $_errore[2]";
                $_errori['files'] = "invia_posta_allegato.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $_return['errore_sql'] = "E-mail non inoltrata ed inoltre Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
                $_return['risultato_sql'] = "errore";
            }
        }// fine graffa funzione controllo invio
    }// fine graffa funzione controllo esistenza campo email

    return $_return;
}

if ($_GET['azione'] == "singolo")
{

    base_html("chiudi", $_percorso);


    echo "<body>\n";

    if ($_POST['AddAddress'] != "")
    {
        //facciamo la sezione dell'allegato..

        if ($_FILES['file'] != "")
        {
            //controlliamo che il file rispetti le dimensioni impostate
            if ($_FILES["file"]["size"] < 10240000)
            {
                //controlliamo se ci sono stati errori durante l'upload
                if ($_FILES["file"]["error"] > 0)
                {
                    echo "Codice Errore: " . $_FILES["file"]["error"] . "";
                }
                else
                {
                    //stampo alcune informazioni sul file
                    //il nome originale
                    echo "Nome File: " . $_FILES["file"]["name"] . "<br>";
                    //il mime-type
                    //	echo "Tipo File: " . $_FILES["file"]["type"] . "<br>";
                    //la dimensione in byte
                    echo "Dimensione [byte]: " . $_FILES["file"]["size"] . "<br>";
                    //il nome del file temporaneo
                    //	echo "Nome Temporaneo: " . $_FILES["file"]["tmp_name"] . "<br>";
                    //controllo se il file esiste già sul server
                    //sposto il file caricato dalla cartella temporanea alla destinazione finale
                    move_uploaded_file($_FILES["file"]["tmp_name"], "../../spool/" . $_FILES["file"]["name"]);
                    //	echo "File caricato in: " . "../../../upload/" . $_FILES["file"]["name"];
                    $_allegato = "OK";
                }
            }
            else
            {
                echo "File numero uno allegato è troppo grande!!";
                exit;
            }

            //se invece va bene lo alleghiamo alla e-mail..
        }


        if ($_FILES['file2'] != "")
        {
            
            //controlliamo che il file rispetti le dimensioni impostate
            if ($_FILES["file2"]["size"] < 10240000)
            {
                //controlliamo se ci sono stati errori durante l'upload
                if ($_FILES["file2"]["error"] > 0)
                {
                    echo "Codice Errore: " . $_FILES["file2"]["error"] . "";
                }
                else
                {
                    //stampo alcune informazioni sul file
                    //il nome originale
                    echo "Nome File: " . $_FILES["file2"]["name"] . "<br>";
                    //il mime-type
                    //	echo "Tipo File: " . $_FILES["file"]["type"] . "<br>";
                    //la dimensione in byte
                    echo "Dimensione [byte]: " . $_FILES["file2"]["size"] . "<br>";
                    //il nome del file temporaneo
                    //	echo "Nome Temporaneo: " . $_FILES["file"]["tmp_name"] . "<br>";
                    //controllo se il file esiste già sul server
                    //sposto il file caricato dalla cartella temporanea alla destinazione finale
                    move_uploaded_file($_FILES["file2"]["tmp_name"], "../../spool/" . $_FILES["file2"]["name"]);
                    //	echo "File caricato in: " . "../../../upload/" . $_FILES["file"]["name"];
                    $_allegato2 = "OK";
                }
            }
            else
            {
                echo "File numero due allegato è troppo grande!!";
                exit;
            }

            //se invece va bene lo alleghiamo alla e-mail..
        }


        $mail = new PHPMailer();

        //$mail->SMTPDebug = 3;
        $mail->isSMTP(); // set mailer to use SMTP
        $mail->SetLanguage("it", $_percorso . "tools/phpmailer/language/");
        $mail->Host = "$mailsmtp"; // specify main and backup server
        $mail->SMTPAuth = "$smtpaut"; // turn on SMTP authentication
        $mail->Port = "$PORTSMTP";
        $mail->Username = "$smtpuser"; // SMTP username
        $mail->Password = "$smtppass"; // SMTP password

        if ($SSL != "NO")
        {
            $mail->SMTPSecure = "$SSL";                            // Enable encryption, 'ssl' also accepted
        }

        $mail->From = $_POST['From'];
        $mail->FromName = $_POST['FromName'];
        $mail->AddAddress($_POST['AddAddress']);

        if ($_POST['AddAddressCC'] != "")
        {
            $mail->AddCC($_POST['AddAddressCC']);
        }

        if ($_POST['AddAddressBCC'] != "")
        {
            $mail->AddCC($_POST['AddAddressBCC']);
        }

        $mail->AddReplyTo($_POST['AddReplyTo']);

        $mail->WordWrap = 50; // set word wrap to 50 characters
        $mail->AddAttachment("../../spool/$_POST[AddAttachment]", $_POST['AddAttachment']); // add attachments

        if ($_allegato == "OK")
        {
            $mail->AddAttachment("../../spool/" . $_FILES['file']['name'] . "", $_FILES["file"]["name"]); // add attachments
        }
        
        if ($_allegato2 == "OK")
        {
            $mail->AddAttachment("../../spool/" . $_FILES['file2']['name'] . "", $_FILES["file2"]["name"]); // add attachments
        }

        $mail->IsHTML(true); // set email format to HTML

        $mail->Subject = $_POST['Subject'];

        $mail->Body = addslashes($_POST['Body']);

//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
        if (!$mail->Send())
        {
            echo "<span class=\"testo_blu\"><center><br><b>Risulato Invio e-mail</b></center></span><br>";

            echo "<H2>Invio della email fallito.</H2>";
            echo "C'e stato un errore nella presa in consegna del messaggio.. indirizzo errato ? controllare e riprovare";

            echo "<br>Tipo di errore: " . $mail->ErrorInfo;
            exit;
        }
        else
        {

            echo "<span class=\"testo_blu\"><center><br><b>Risulato Invio e-mail</b></center></span><br>";
            print "<H2>Invio della email effettuato.</H2>";
            echo " Email inviata correttamente all'indirizzo $_POST[AddAddress]";
            if ($_POST['AddAddressCC'] != "")
            {
                echo "<h4>Email inviata correttamente all'indirizzo <font color=\"GREEN\">$_POST[AddAddressCC]</font></h4>\n";
            }
            if ($_POST['AddAddressBCC'] != "")
            {
                echo "<h4>Email inviata correttamente all'indirizzo <font color=\"GREEN\">$_POST[AddAddressBCC]</font></h4>\n";
            }
            echo "<br> Questo non vuol dire che il destinatario ricever&agrave; l'e-mail, ma che &egrave; stata presa in consegna dal provider";
            echo "<br>ORA SI PUO' CHIUDERE LA FINESTRA";
        }// fine graffa funzione controllo invio
    }// fine graffa funzione controllo esistenza campo email
    else
    {

        echo "<span class=\"testo_blu\"><center><br><b>Risulato Invio e-mail</b></center></span><br>";
        print "<H2>IMPOSSIBILE EFFETTUARE L'INVIO -  CAMPO E-mail ERRATO o VUOTO</H2>";
        echo "<br> Controllare e riprovare";
    }//fine graffa funzione else
}


if ($_GET['azione'] == "documento")
{

    base_html("chiudi", $_percorso);


    if ($_POST['AddAddress'] != "")
    {

        if ($_FILES['file'] != "")
        {
            //controlliamo che il file rispetti le dimensioni impostate
            if ($_FILES["file"]["size"] < 10240000)
            {
                //controlliamo se ci sono stati errori durante l'upload
                if ($_FILES["file"]["error"] > 0)
                {
                    echo "Codice Errore: " . $_FILES["file"]["error"] . "";
                }
                else
                {
                    //stampo alcune informazioni sul file
                    //il nome originale
                    echo "Nome File: " . $_FILES["file"]["name"] . "<br>";
                    //il mime-type
                    //	echo "Tipo File: " . $_FILES["file"]["type"] . "<br>";
                    //la dimensione in byte
                    echo "Dimensione [byte]: " . $_FILES["file"]["size"] . "<br>";
                    //il nome del file temporaneo
                    //	echo "Nome Temporaneo: " . $_FILES["file"]["tmp_name"] . "<br>";
                    //controllo se il file esiste già sul server
                    //sposto il file caricato dalla cartella temporanea alla destinazione finale
                    move_uploaded_file($_FILES["file"]["tmp_name"], "../../spool/" . $_FILES["file"]["name"]);
                    //	echo "File caricato in: " . "../../../upload/" . $_FILES["file"]["name"];
                    $_allegato = $_FILES;
                    $_allegato['verifica'] = "OK";
                }
            }
            else
            {
                echo "File numero uno allegato è troppo grande!!";
                exit;
            }
            

            //se invece va bene lo alleghiamo alla e-mail..
        }
        
        if ($_FILES['file2'] != "")
        {
            //controlliamo che il file rispetti le dimensioni impostate
            if ($_FILES["file2"]["size"] < 10240000)
            {
                //controlliamo se ci sono stati errori durante l'upload
                if ($_FILES["file2"]["error"] > 0)
                {
                    echo "Codice Errore: " . $_FILES["file2"]["error"] . "";
                }
                else
                {
                    //stampo alcune informazioni sul file
                    //il nome originale
                    echo "Nome File: " . $_FILES["file2"]["name"] . "<br>";
                    //il mime-type
                    //	echo "Tipo File: " . $_FILES["file"]["type"] . "<br>";
                    //la dimensione in byte
                    echo "Dimensione [byte]: " . $_FILES["file2"]["size"] . "<br>";
                    //il nome del file temporaneo
                    //	echo "Nome Temporaneo: " . $_FILES["file"]["tmp_name"] . "<br>";
                    //controllo se il file esiste già sul server
                    //sposto il file caricato dalla cartella temporanea alla destinazione finale
                    move_uploaded_file($_FILES["file2"]["tmp_name"], "../../spool/" . $_FILES["file2"]["name"]);
                    //	echo "File caricato in: " . "../../../upload/" . $_FILES["file"]["name"];
                    $_allegato2 = $_FILES;
                    $_allegato2['verifica'] = "OK";
                }
            }
            else
            {
                echo "File numero due allegato è troppo grande!!";
                exit;
            }
            

            //se invece va bene lo alleghiamo alla e-mail..
        }
        
        

        $_risultato = invio_posta($_cosa, $_percorso, $_POST['AddAttachment'], $_POST['From'], $_POST['AddAddress'], $_POST['AddAddressCC'], $_POST['AddAddressBCC'], $_POST['Subject'], $_POST['Body'], $_POST['ricevuta'], $_POST['tdoc'], $_POST['anno'], $_POST['ndoc'], $_allegato, $_allegato2, $_parametri);

        echo "<span class=\"testo_blu\"><h2>Risulato Invio e-mail</h2></span>\n";


        if ($_risultato['risultato'] == "errore")
        {
            echo "<H2>Invio della email fallito.</H2>\n";
            echo "<h4>C'e stato un errore nella presa in consegna del messaggio.. indirizzo errato ? controllare e riprovare</h4>\n";
            echo "<h4>Email inviata all'indirizzo <font color=\"RED\">$_POST[AddAddress]</font></h4>\n";
            if ($_POST['AddAddressCC'] != "")
            {
                echo "<h4>Email inviata all'indirizzo <font color=\"RED\">$_POST[AddAddressCC]</font></h4>\n";
            }
            if ($_POST['AddAddressBCC'] != "")
            {
                echo "<h4>Email inviata all'indirizzo <font color=\"RED\">$_POST[AddAddressBCC]</font></h4>\n";
            }
            echo "<h4>Tipo di errore = $_risultato[result_send]</h4>\n";

            echo "<h4>Aggiornamento stato documento</h4>\n";
            if ($_risultato['risultato_sql'] == "errore")
            {
                echo "<h4>$_risultato[errore_sql]</h4>\n";
            }
            else
            {
                echo "<h4>Documento Aggiornato</h4>\n";
            }
        }
        else
        {
            print "<H2>Invio della email effettuato.</H2>";
            echo "<h4>Email inviata correttemente all'indirizzo <font color=\"GREEN\">$_POST[AddAddress]</font></h4>\n";
            if ($_POST['AddAddressCC'] != "")
            {
                echo "<h4>Email inviata correttamente all'indirizzo <font color=\"GREEN\">$_POST[AddAddressCC]</font></h4>\n";
            }
            if ($_POST['AddAddressBCC'] != "")
            {
                echo "<h4>Email inviata correttamente all'indirizzo <font color=\"GREEN\">$_POST[AddAddressBCC]</font></h4>\n";
            }
            echo "<h4>Questo non vuol dire che il destinatario ricever&agrave; l'e-mail, ma che &egrave; stata presa in consegna dal provider</h4>\n";

            echo "<h4>Aggiornamento stato documento</h4>\n";
            if ($_risultato['risultato_sql'] == "errore")
            {
                echo "<h4>$_risultato[errore_sql]</h4>\n";
            }
            else
            {
                echo "<h4>Documento Aggiornato</h4>\n";
            }
        }

        echo "<br>ORA SI PUO' CHIUDERE LA FINESTRA";
    }// fine graffa funzione controllo esistenza campo email
    else
    {

        echo "<span class=\"testo_blu\"><center><br><b>Risulato Invio e-mail</b></center></span><br>";
        print "<H2>IMPOSSIBILE EFFETTUARE L'INVIO -  CAMPO E-mail ERRATO o VUOTO</H2>";
        echo "<br> Controllare e riprovare";
    }//fine graffa funzione else
}
?>