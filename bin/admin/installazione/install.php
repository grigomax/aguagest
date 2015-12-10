<?php
// varianti php
include("../../include/version.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
        <LINK REL="shortcut icon" HREF="favicon.ico">
        <title>Agua Gest - Installazione</title>
    </head>
    <BODY background="../../images/aguaback.jpg">
    <center>
        <img src="../../images/aguagest.png">
        <font color="yellow">
            <h1>Benvenuto nel programma di installazione</h1>
            <h2>Agua Gest<br>Gestionale Aziendale PHP</h2>
            <h3>Versione = <?php echo $_PROGRAM_VERSION; ?></h3>
            <h4>Controllo file parametri base</h4>
        </font>
        <center>
            <?php
            if (file_exists("../../../setting/vars.php"))
            {

                echo "File trovato = <font color=\"green\"> [ OK ] </font>";
                include("../../../setting/vars.php");

                if (($host != "") or ( $db_server != "") or ( $db_user != "") or ( $db_nomedb != ""))
                {
                    echo "<style=\"font-weight: bold;\"><font color=\"RED\">";
                    echo "<br><br>Errore 2 = File variante base non vuoto</font></font></span>";
                    echo "<br><br>Il file vars.php contenuto all'interno della cartella agua/setting contiene le variabili come localhost e nome database e password non vuote..";
                    echo "<br>Questo &egrave; un programma di installazione e non di aggiornamento, per eseguire tale vi preghiamo di puntare nella barra degli indirizzi il file update.php</font>";
                    echo "<br>";
                    exit;
                }
                else
                {
                    echo "<br>File vuoto = <font color=\"green\"> [ OK ] </font>";

                    $file = fopen("../../../setting/vars.php", "r+");
                    if ($file)
                    {
                        echo "<br>Permessi di scrittura = <font color=\"green\"> [ OK ] </font><br>";
                        
                        echo "<br>Il programma settera Agua Gest nel vostro computer impostando tutti i parametri base, <br>che voi inserirete nelle prossime schermate\n";
                        echo "<br><br>\n";
                        echo "<font color=\"RED\">Mi Raccomando di prestare molta attenzione alle schermate successive,<br> per una buona riuscita dell'operazione</font>\n";
                        echo "<br><br>\n";
                        echo "<h2><a href=\"parametri_inst.php?azione=install\">Premere qui per iniziare l'installazione</a></h2>\n";

                    }
                    else
                    {
                        echo "<span style=\"font-weight: bold;\">Errore 3 = File variante base non scrivibile.</span>
			<br>Per
			poter installare il programma i files vars.php vars-aspetto.php devono
			avere i permessi lettura e scrittura. si prega si provvedere e poi
			riprovare l'installazione.
			<br><br>
			</div></div>";
                        exit;
                    }
                    fclose($file);
                }
            }
            else
            {
                echo "<br> File dati mancante.. proviamo a crearlo..\n";
                $fp = fopen("../../../setting/vars.php", "w+");
                if ($fp)
                {
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
// chiudiamo il file php
                    $_commento = "?>";
                    fwrite($fp, $_commento);
                    if (!$fp)
                        die("Errore.. Riga non inserita ?");
// chiudiamo il files
                    fclose($fp);
                    
                    echo "<br>Permessi di scrittura = <font color=\"green\"> [ OK ] </font>";
                    echo "<br>Creazione file parametri eseguita..";
                    echo "<br><a href=\"install.php\"><b>Clicca Qui per proseguire.. </b></a>\n";
                    
                }
                else
                {
                    echo "<span style=\"font-weight: bold;\">Errore 4 = File variante base non scrivibile.</span>
			<br>Per
			poter installare il programma i files vars.php vars-aspetto.php devono
			avere i permessi lettura e scrittura. si prega si provvedere e poi
			riprovare l'installazione.
			<br><br>
			</div></div>";

                    echo "<br><br>In caso non esistesse provare a ricopiarlo all'interno del server, altrimenti<br>contattare il sito ufficiale aguagest.sourceforge.net";
                    exit;
                }
            }
            ?>

 
        </center>
    </body>
</html>