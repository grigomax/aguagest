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
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
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



if ($_SESSION['user']['contabilita'] > "1")
{

//Prendiamoci i post

    $_campi = $_POST['campi'];
    $_descrizione = $_POST['descrizione'];



    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
    echo "<tr>";
    echo "<td width=\"90%\" align=\"center\" valign=\"top\">";

    echo "<span class=\"testo_blu\"><b>Risulati ricerca</b></span>";


    // Stringa contenente la query di ricerca...

    $_descrizione = "%$_descrizione%";


    $query = sprintf("select * from piano_conti where $_campi like \"%s\" order by codconto", $_descrizione);

    // Esegue la query...
    if ($res = mysql_query($query, $conn))
    {
	// La query ?stata eseguita con successo...
	// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
	if (mysql_num_rows($res))
	{
	    // Tutto procede a meraviglia...
	    echo "<table width=\"700\">";
	    echo "<tr>";
	    echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Conto</span></td>";
	    echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
	    echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Natura</span></td>";
	    echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Tipo Mastro</span></td>";
	    echo "<td width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Livello Conto</span></td>";

	    echo "</tr>";

	    while ($dati = mysql_fetch_array($res))
	    {
		echo "<tr>";
		printf("<td width=\"50\" align=\"left\"><span class=\"testo_blu\"><a href=\"maschera_conto.php?azione=Modifica&conto=%s\">%s</span></td>", $dati['codconto'], $dati['codconto']);
		printf("<td width=\"250\" align=\"left\"><span class=\"testo_blu\"><a href=\"maschera_conto.php?azione=Modifica&conto=%s\">%s</span></td>", $dati['codconto'], $dati['descrizione']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['natcon']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['tipo_cf']);
		printf("<td width=\"60\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['livello']);
		echo "</tr>";
		echo "<tr>";
		echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"250\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";

		echo "</tr>";
	    }
	}
    }
    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>