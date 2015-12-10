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
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


// elimino le sessioni create
	unset($_SESSION['tdoc']);
	unset($_SESSION['ndoc']);
	unset($_SESSION['anno']);
	$_campi = $_POST['campi'];
	echo "<span class=\"testo_blu\"><center><br><b>Operazione Annullata con successo</b><br> <a href=\"../../index.php\">Ritorna all'indice</a></center></span><br>";

echo "</td></tr></table></body></html>";

?>