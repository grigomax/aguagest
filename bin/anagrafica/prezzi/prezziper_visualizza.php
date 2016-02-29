<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['plugins'] > "1")
{
    echo "<table align=\"left\" border=\"0\" width=\"100%\">\n";
    echo "<tr><td valign=\"top\" class=\"logo\">";
    echo "<a href=\"prezziper_stampa.php\" class=\"testo_bianco\" target=\"_blank\">Stampa Pagina</a>\n";
    echo "</td></tr>";
    echo "<tr><td valign=\"top\" >\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
	<tr>
	<td width=\"85%\" align=\"center\" valign=\"top\">
	<span class=\"intestazione\">Visualizza prezzi personalizzati <br><br> <b>Scegliere il cliente</b></span><br>
	</td></tr>";

    $result = tabella_prezzi_cliente("elenco_stampa", $_utente, $_articolo, $_parametri);
    echo "<form action=\"prezziper3.php\" method=\"POST\">\n";

    echo "<table>\n";
    echo "<tr><td><b>Ragione sociale</b> </td></td><td width=\"60%\"><b>Descrizione</b></td><td><b>Prezzo</b></td><td width=\"100\"><b>Articolo</b></tr>\n";
    foreach ($result AS $dati)
    {
        if ($_ragsoc != $dati['codice'])
        {
            echo "<tr><td colspan=\"4\"><hr></td></tr>\n";
        }
        echo "<tr><td>$dati[ragsoc]</td><td>$dati[descrizione]</td><td>$dati[listino]</td><td><input type=\"radio\" name=\"utente\" value=\"$dati[codice]\"><input type=\"submit\" name=\"codice\" value=\"$dati[codarticolo]\"></td></tr>\n";
        $_ragsoc = $dati[codice];
    }
    echo "</table>\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>