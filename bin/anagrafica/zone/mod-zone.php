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
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "2")
{

// Inizio tabella pagina principale ----------------------------------------------------------

    echo "<table><tr><td>";

    printf("<br><br><form action=\"zone.php\" method=\"POST\">\n");
    echo "<table width=\"400\" border=\"1\" align=right>\n";

    echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Modifica Zona</b><br></font></span></td>\n";
    echo "<tr><td colspan=2 align=\"center\"><font size=3>Se non trovi le modifiche aggiorna la pagina con ALT+F5</font></td>\n";
//inizio pagina alternativa



    if ($_POST['azione'] == "Inserisci")
    {
	// prendo le variabili passate

	$_codice = $_POST['codice'];

	
	//cerco la riga se c'e l'aggiorno, se non c'e la inserisco
	$query = ("SELECT * FROM zone WHERE nome='$_codice'") or die("Errore!");
	//esegue la query
	$res = mysql_query($query, $conn);
//	echo $query;
	if (mysql_num_rows($res) > 0)
	{
	    echo "<td>Zona $_articolo Gi&agrave; presente nell'archivio</td>";
	}
	else
	{
	    $query = ("insert into zone ( nome ) VALUES ( '$_codice')");
	    //esegue la query
	    mysql_query($query, $conn);
	    echo "<td>Zona $_articolo inserito correttamente</td>";
	}

	return;
    }// parentesi fine funzione inserimento
//inizio aggiornamento
    if ($_POST['azione'] == "Modifica")
    {
	// prendo le variabili passate
	//cerco la riga se c' l'aggiorno, se non c' la inserisco
	$query = sprintf("UPDATE zone SET nome = \"%s\" where id=\"%s\"", $_POST['codice'], $_POST['id']);
	//esegue la query
	if ($res = mysql_query($query, $conn) != 1)
	    ;
	{
	    echo "<tr><td>Zona $_articolo Modificato correttamente</td></td>";
	}


	return;
    }// parentesi fine funzione inserimento
//inizio eliminazione
    if ($_POST['azione'] == "Elimina")
    {
	// prendo le variabili passate
	//cerco la riga se c' l'aggiorno, se non c' la inserisco
	$query = sprintf("DELETE FROM zone where id=\"%s\"", $_POST['id']);
	//esegue la query
	if ($res = mysql_query($query, $conn) != 1)
	    ;
	{
	    echo "<tr><td>Zona $_articolo eliminato correttamente</td></td>";
	}


	return;
    }// parentesi fine funzione inserimento


    echo "<tr><td colspan=2 align=\"center\"><span class=\"testo_blu\"><b>Inserisci la ZONA da modificare:</b><br></span></td>\n";

    echo "<tr><td colspan=2 align=center><br>";
    echo "<select name=\"codice\">\n";
    echo "<option value=\"\"></option>";

    // Stringa contenente la query di ricerca...
    $query = sprintf("select * from zone order by nome ");

    // Esegue la query...
    if ($res = mysql_query($query, $conn))
    {
	// La query ?stata eseguita con successo...
	// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
	if (mysql_num_rows($res))
	{
	    // Tutto procede a meraviglia...
	    echo "<span class=\"testo_blu\">";
	    while ($dati = mysql_fetch_array($res))
	    {
		printf("<option value=\"%s\">%s</option>\n", $dati['id'], $dati['nome']);
	    }
	}
    }
    echo "</select>\n";
    echo "</td></tr>\n";

    // PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "<td colspan=2 align=center><input type=\"submit\" name=\"azione\" value=\"Modifica !\"></td></tr><tr><td colspan=2 align=center>Oppure inserisci un codice nuovo<br><input type=\"submit\" name=\"azione\" value=\"Inserisci\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</table>";
// ************************************************************************************** -->
    echo "</td></tr></table>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>