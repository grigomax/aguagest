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
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

   

    echo "<table><tr><td>";

    echo "</td><td>";
    printf("<br><br><form action=\"prezziper1.php\" method=\"POST\">\n");
    echo "<table width=\"400\" border=\"1\" align=right>\n";

    echo "<tr><td colspan=2 align=\"center\"><font size=3><span class=\"testo_blu\"><b>Inserisci o Modifica o Elimina Prezzo</b><br></font></span></td>\n";
    ;

    if ($_POST['azione'] == "Inserisci")
    {
        $dati = tabella_articoli("singola_prezzo", $_POST['codice'], "1");
        
	//$query = sprintf("SELECT articolo, descrizione, listino, rigo FROM articoli INNER JOIN listini ON articoli.articolo=listini.codarticolo where articolo = \"%s\" and rigo='1'", $_POST['articolo']);

	printf("<tr><td align=right>Cliente :</td><td><input type=\"text\" name=\"cliente\" value=\"%s\" size=\"15\" maxleght=\"15\"></td></tr>", $_POST['utente']);
	printf("<tr><td align=right>Codice :</td><td><input type=\"text\" name=\"codice\" value=\"%s\" size=\"40\" maxleght=\"15\"></td></tr>", $dati['articolo']);
	printf("<tr><td align=right>Descrizione :</td><td><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"90\" maxleght=\"80\"></td></tr>", $dati['descrizione']);
	printf("<tr><td align=right>Prezzo</td><td><input type=\"text\" name=\"listino\" value=\"%s\" size=\"11\" maxleght=\"10\"></td></tr>", $dati['listino']);
	echo "<tr><td colspan=2 align=RIGHT><input type=\"submit\" name=\"azione\" value=\"Inserisci\"></td></tr>";
    }
    else
    {
        $dati = tabella_prezzi_cliente("singola", $_POST['utente'], $_POST['codice'], $_parametri);

	printf("<tr><td align=right>Cliente :</td><td><input type=\"text\" name=\"cliente\" value=\"%s\" size=\"15\" maxleght=\"15\"></td></tr>", $dati['cliente']);
	printf("<tr><td align=right>Codice :</td><td><input type=\"text\" name=\"codice\" value=\"%s\" size=\"40\" maxleght=\"15\"></td></tr>", $dati['codarticolo']);
	printf("<tr><td align=right>Descrizione :</td><td><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"90\" maxleght=\"80\"></td></tr>", $dati['descrizione']);
	printf("<tr><td align=right>Prezzo</td><td><input type=\"text\" name=\"listino\" value=\"%s\" size=\"11\" maxleght=\"10\"></td></tr>", $dati['listino']);
	echo "<tr><td>&nbsp;</td><td align=RIGHT><input type=\"submit\" name=\"azione\" value=\"Modifica\"><input type=\"submit\" name=\"azione\" value=\"Elimina\"></td></tr>";
    }

    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</table>";
// ************************************************************************************** -->
    echo "</td></tr></table>\n";

    // elimino la sessione usata
    unset($_SESSION['cliente']);
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>