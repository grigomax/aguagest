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
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

    $_tipo = $_GET['tipo'];
    echo "<table border=\"0\" width=\"100%\"><tr><td align=\"center\">";

//progrmma che elenca tutte le immagini..
// CAMPO seleziona immagine
    echo "<h3>Elenco $_tipo.. </h3>\n";
    
    if ($_tipo == "immagini")
        {
            $_link = "imm-art";
            $_immagine = "immagine";
        }
        elseif ($_tipo == "disegni")
        {
             $_link = "imm-art/disegni";
            $_immagine = "immagine2";
        }
        else
        {
             $_link = "imm-art/prestazioni";
            $_immagine = "immagine3";
        }

    echo "<table align=\"center\" border=\"1\">\n";
//leggiamo la cartella..
    exec("ls ../../../../setting/$_link/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {
	echo "<tr>\n";
	echo "<td align=\"center\"><a href=\"visualizza_imm.php?tipo=$_tipo&azione=visual&file=$val\"><img src=\"../../../../setting/$_link/$val\" width=\"150px\" height=\"150px\"><br><font size=\"1\">$val</a></td>\n";

	for ($a = 1; $a <= 4; $a++)
	{
	    list($key, $val) = each($resrAr);
	    
	    echo "<td align=\"center\"><a href=\"visualizza_imm.php?tipo=$_tipo&azione=visual&file=$val\"><img src=\"../../../../setting/$_link/$val\" width=\"150px\" height=\"150px\"><br><font size=\"1\">$val</a></td>\n";
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