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
        echo "<title>INSTALLAZIONE COMPLETATA</title>\n";
        echo "<link rel=\"stylesheet\" href=\"" . $_percorso . "css/globale.css\" type=\"text/css\">\n";
        
        echo "<BODY background=\"" . $_percorso . "images/aguaback.jpg\">\n";
?>

<div style="text-align: center;"><h1>Agua Gest</h1><br>
<div style="text-align: center;"><big style="font-weight: bold;">Fine installazione</big><br>
<br>
<div style="text-align: center;"><big style="font-weight: bold;">Installazione completata ed &egrave; andata abuon fine</big><br>
<br>
<div style="text-align: center;">Vi consigliamo di prendere nota di questi dati..<br>
<br>

Per poter accedere al programma bisogna inserire il nome utente dell' amministratore e la sua password che &egrave; :<br><br>
<b>Nome utente = admin <br>
Password = admin<br></b><br>

Per poter utilizzare correttamente il programma bisogna completare l'anagrafica azienda<br>
quindi Appena entrati In basso a destra icona <i> Configura il programma</i><br>
- Gestione parametri Aziendali <br>
 - Parametri azienda e completare l'anagrafica.. <br>
 Poi si consiglia di creare un utente user per poter usare normalmente il programma. <br><br>
<font color=RED> Si sconsiglia di utilizzare il programma con l'account admin</font><br>
<br>
Vi ricordiamo che tutti i parametri immessi in queste pagine sono sempre <br> modificabili nella sezione amministratore. <br>
Per Poter configurare i documenti di vendita nella sezione parametri aspetto documenti.

<h3><a href="../../../index.php"> Clicca qui ! per entrare nel mondo di agua.. </h3> <br></a>


</div>
</body>
</html>
