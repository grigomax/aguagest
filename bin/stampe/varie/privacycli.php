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
session_start(); 
$_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['stampe'] > "1")
{
    ?>
    <body>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    	<tr>
    	    <td width="85%" align="center" valign="top">


    <?php
    printf("<br><br><form action=\"rp-privacycli.php\" target=\"sotto\" method=\"POST\">");

    echo "<tr><td align=center><h1>Stampa Privacy<br></h1><br></td></tr>";

    echo "<tr><td align=center>Selezionare il Cliente da stampare<br><br>";

    tabella_clienti("elenca_select", "codice", "codice");
    
    echo "</td></tr>\n";


    echo "</table>\n";
    echo "<center><br>&nbsp;<input type=\"submit\" name=\"azione\" value=\"Stampa\"> &nbsp;<input type=\"submit\" name=\"azione\" value=\"PDF\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";


    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
