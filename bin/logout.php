<?php

// recupero le sessioni
session_start();
$_SESSION['keepalive'] ++;
require "../setting/vars.php";
require "librerie/lib_html.php";

base_html("chiudi", $_percorso);
echo "<BODY background=\"images/aguaback.jpg\">\n";
echo "<center><img src=\"images/aguagest.png\" alt=\"logo agua\"><font color=\"yellow\"><br>\n";

unset($_SESSION['user']);

$_SESSION = array();
session_destroy();
 echo "<h1>Gestionale Aziendale</h1>";
echo "<center><h1>Logo out effettuato con successo</h1></center>";
echo "<br><a href=\"$sito/index.php\">Premi qui per tornare alla pagina iniziale</a>";
echo "</body></html>\n";

?>