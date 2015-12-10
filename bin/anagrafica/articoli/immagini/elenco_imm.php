<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
require $_percorso ."../setting/vars.php";
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


if ($_SESSION['user']['anagrafiche'] > "1")
{

    echo "<table border=\"0\" width=\"100%\"><tr><td align=\"center\">";

//progrmma che elenca tutte le immagini..
// CAMPO seleziona immagine
    echo "<h3>Elenco immagini.. </h3>\n";

    echo "<table align=\"center\" border=\"1\">\n";
//leggiamo la cartella..
    exec("ls ../../../../imm-art/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {
	echo "<tr>\n";
	echo "<td align=\"center\"><a href=\"visualizza_imm.php?azione=visual&file=$val\"><img src=\"../../../../imm-art/$val\" width=\"50px\" height=\"50px\"><br><font size=\"1\">$val</a></td>\n";

	for ($a = 1; $a <= 3; $a++)
	{
	    list($key, $val) = each($resrAr);
	    
	    echo "<td align=\"center\"><a href=\"visualizza_imm.php?azione=visual&file=$val\"><img src=\"../../../../imm-art/$val\" width=\"50px\" height=\"50px\"><br><font size=\"1\">$val</a></td>\n";
	}
	echo "</tr>";
    }
    echo "</table>";



    echo "</td></tr></table>\n";
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>