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



if ($_SESSION['user']['magazzino'] > "1")
{

    // Cerco il post inviatomi dalla pagina precente ed lo cerco sul database clienti

    if ($_POST['fornitore'] != null)
    {

	$_SESSION['fornitore'] = $_POST['fornitore'];
	$_fornitore = $_POST['fornitore'];
    }
    else
    {
	$_fornitore = $_SESSION['fornitore'];
    }

    $query = sprintf("select * from fornitori where codice=\"%s\"", $_fornitore);
    // Esegue la query...
    if ($res = mysql_query($query, $conn))
    {
	// La query ?stata eseguita con successo...
	// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
	// Tutto procede a meraviglia...
	$dati = mysql_fetch_array($res);
    }




    // verifico se il cliente ha conferme d'ordine inevase
    // avviso su monitor se ci sono e quali
    $query = sprintf("select * from of_testacalce where utente=\"%s\" and status != 'evaso' ORDER BY ndoc", $_fornitore);
    // Esegue la query...
    $resco = mysql_query($query, $conn);
    $righe = mysql_num_rows($resco);

    if ($righe > 0)
    {

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"1\" align=\"center\">";
	echo "<tr><td colspan=\"4\" align=\"left\" valign=\"top\">";
	echo "<span class=\"intestazione\">Gestione ddtacq</span><br></td></tr>";
	echo "<tr>";

	echo "<tr><td colspan=\"4\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Il seguente fornitore ha Questi documenti inevasi.</span></td></tr>";
	echo "<tr><td>anno</td><td>ndoc</td><td>valore</td><td>status</td></tr>";

	while ($datico = mysql_fetch_array($resco))
	{
	    printf(" <tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $datico['anno'], $datico['ndoc'], $datico['totdoc'], $datico['status']);
	}

	echo "<tr><td colspan=\"4\" align=\"center\"><font color=RED >Continuare ? <a href=\"annulladoc.php\"> NO </a>  - <a href=\"seleziona.php\"> SI</a></font></td></tr>";
	//echo "</table>";

	printf("<form action=\"annulladoc.php\" method=\"POST\">");
	printf("<tr><td colspan=\"10 \" align=\"center\" class=\"testo_blu\"><br>Per annullare l'operazione  <input type=\"submit\" name=\"azione\" value=\"Annulla\"></form></td>");
	echo "</tr>";
	echo "</table>";
// 	exit;
    }
    else
    {
	include "seleziona.php";
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>