<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso ."../setting/vars.php";
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['magazzino'] > "1")
{
// questa funzione mi permette di non generare una e-mail vuota
    if ($_POST['AddAddress'] != "")
    {

	require("../../tools/phpmailer/class.phpmailer.php");
//$mail->SetLanguage("it", "../tools/phpmailer/language/directory/phpmailer.lang-it.php");
	$mail = new PHPMailer();

	$mail->IsSMTP();				      // set mailer to use SMTP
	$mail->Host = "$mailsmtp";  // specify main and backup server
	$mail->SMTPAuth = "$smtpaut";     // turn on SMTP authentication
	$mail->Username = "$smtpuser";  // SMTP username
	$mail->Password = "$smtppass"; // SMTP password

	$mail->From = $_POST['From'];
	$mail->FromName = $_POST['FromName'];
	$mail->AddAddress($_POST['AddAddress']);
	$mail->AddReplyTo($_POST['AddReplyTo']);

	$mail->WordWrap = 50;				 // set word wrap to 50 characters
	$mail->AddAttachment("../../../spool/$_POST[AddAttachment]", $_POST['AddAttachment']);	 // add attachments
	$mail->IsHTML(false);				  // set email format to HTML

	$mail->Subject = $_POST['Subject'];
	$mail->Body = $_POST['Body'];

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
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>