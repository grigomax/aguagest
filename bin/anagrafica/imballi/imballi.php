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


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{
    // prendiamoci il codice articolo da modificare
    $_articolo = $_POST['codice'];

    printf("<br>\n<span class=\"testo_blu\">%s</span>\n", $_testo);
    printf("<br><br><form action=\"mod-imballi.php\" method=\"POST\">\n");
    echo "<table border=\"0\" align=center>\n";

    echo "<tr><td colspan=2 align=\"center\"><font size=3<<span class=\"testo_blu\"><b>Inserisci o Modifica o Elimina imballoo</b><br></font></span></td>\n";
    

    echo "<tr><td colspan=2 align=center><br>";

    if ($_POST['azione'] == "Inserisci")
    {
	echo "<input type=\"text\" name=\"codice\" size=\"40\" maxleght=\"40\"></td></tr>";
	echo "<td align=RIGHT><input type=\"submit\" name=\"azione\" value=\"Inserisci\"></td></tr>";
    }
    else
    {

	$query = sprintf("select * from imballi where id=\"%s\"", $_POST['codice']);

        
	$result = domanda_db("query", $query, $_ritorno, "verbose");

	// Tutto procede a meraviglia...
	foreach ($result AS $dati)
	{
	    printf("id <input type=\"text\" name=\"id\" value=\"%s\" size=\"4\" maxleght=\"3\">", $dati['id']);
	    printf(" Nome :<input type=\"text\" name=\"codice\" value=\"%s\" size=\"40\" maxleght=\"40\"></td></tr>", $dati['imballo']);
	    echo "<td align=RIGHT><input type=\"submit\" name=\"azione\" value=\"Modifica\"><input type=\"submit\" name=\"azione\" value=\"Elimina\"></td></tr>";
	}
    }

    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</table>";
// ************************************************************************************** -->
    echo "</td></tr></table>\n";

    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>