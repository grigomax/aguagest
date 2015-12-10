<?php
//carichiamo il percorso
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
//settiamo il tempo di sessione
session_start();
$_SESSION['keepalive'] ++;


    echo "<!DOCTYPE html>\n";
        echo "<html lang=\"it\">\n";
        echo "<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
        #echo "<meta http-equiv=\"Expires\" content=\"-1\" />\n";
        #echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
        echo "<LINK REL=\"shortcut icon\" HREF=\"" . $_percorso . "images/favicon.ico\">\n";
        echo "<title>$title</title>\n";
        echo "<link rel=\"stylesheet\" href=\"" . $_percorso . "css/globale.css\" type=\"text/css\">\n";
        
        echo "<BODY background=\"" . $_percorso . "images/aguaback.jpg\">\n";


    $_submit = "continua";

?>


        <div style="text-align: center;"><big style="font-weight: bold;">PARAMETRI BASE - AZIENDALI</big><br>
            <br>
            Attenzione non si possono inserire nei campi di scrittura<br>
            NE parole accentate NE apostrofi e TANTOMENO virgolette.<br>
            <br>
            La non osservanza potrebbe compromettere l'uso del programma.<br><font color="RED"> Sono proposte le variabili per l'installazione classica</font><br>
            <br>
            <form action="salvavars_inst.php" method="POST">

                <table class="classic" border="0" cellpadding="1" cellspacing="1">
                    <tbody>
                        <tr>
                            <td colspan="2" rowspan="1" align="center" valign="top"><span style="font-weight: bold;">Parametri configurazione server locale apache e directory agua</span></td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">Nome Host<br>
                                nome del server locale</td>
                            <td style="width: 350px; text-align: left;" valign="top"><input type="text" size="70" name="host" placeholder="localhost" value="<?php echo $host; ?>"></td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">Percorso assoluto</td>
                            <td style="width: 350px; text-align: left;" valign="top"><input type="text" size="70" name="sito" placeholder="/agua" value="/agua"></td>
                        </tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                            <td colspan="2" rowspan="1" style="width: 350px;" align="center" valign="top"><span style="font-weight: bold;">Parametri mysql</span></td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">Nome host server mysql</td>
                            <td style="width: 350px; text-align: left;" valign="top"><input type="text" size="70" name="db_server" placeholder="localhost" value="<?php echo $db_server; ?>"></td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">Nome utente mysql</td>
                            <td style="width: 350px; text-align: left;" valign="top"><input type="text" size="70" name="db_user" placeholder="root" value="<?php echo $db_user; ?>"></td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">Password mysql</td>
                            <td style="width: 350px; text-align: left;" valign="top"><input type="text" size="70" name="db_password" placeholder="password" value="<?php echo $db_password; ?>"></td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">Nome archivio</td>
                            <td style="width: 350px; text-align: left;" valign="top"><input type="text" size="70" name="db_nomedb" placeholder="agua" value="agua"></td>
                        </tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                            <td colspan="2" rowspan="1" style="width: 350px;" align="center" valign="top"><span style="font-weight: bold;">Intestazione Attività</span></td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">Nome Azienda</td>
                            <td style="width: 350px; text-align: left;" valign="top"><input type="text" size="70" name="azienda" placeholder="ALFA SPA" value="<?php echo $azienda; ?>"></td>
                        </tr>
                 
                        <?php
                            
echo "<td colspan=\"2\" align=\"center\"><br><input type=\"submit\" name=\"azione\" value=\"$_submit\"></td></tr>\n";
                                       
echo "</tbody></table></form></div></body></html>\n";
?>