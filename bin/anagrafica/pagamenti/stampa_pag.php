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


//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

    echo "<span class=\"testo_blu\"><b>Elenco codici di pagamento</b></span>";


    $query = "select * from pagamenti order by descrizione";

// Esegue la query...
    $res = mysql_query($query, $conn);

// Tutto procede a meraviglia...
    echo "<table width=\"700\">";
    echo "<tr>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
    echo "<td width=\"280\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">gg prima scad</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">gg tra scad</span></td>";
    echo "</tr>";

    while ($dati = mysql_fetch_array($res))
    {
	echo "<tr>";
	echo "<td width=\"70\" align=\"center\"><span class=\"testo_blu\">$dati[codice]</span></td>";
	echo "<td width=\"280\" align=\"left\"><span class=\"testo_blu\">$dati[descrizione]</span></td>";
	echo "<td width=\"50\" align=\"left\"><span class=\"testo_blu\">$dati[ggprimascad]</span></td>";
	echo "<td width=\"50\" align=\"left\"><span class=\"testo_blu\">$dati[ggtrascad]</span></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
	echo "<td width=\"280\" height=\"1\" align=\"center\" class=\"logo\"></td>";
	echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
	echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
	echo "</tr>";
    }


    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>