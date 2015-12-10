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
     echo "<br><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
	<tr>
	<td width=\"85%\" align=\"center\" valign=\"top\">
	<span class=\"intestazione\"><br>Gestione Prezzi personalizzati di vendita<br><br> <b>Scegliere il cliente</b></span><br>
	</td></tr>";

    $_cliente = $_POST['cliente'];
    $_codice = $_POST['codice'];
    $_descrizione = $_POST['descrizione'];
    $_listino = $_POST['listino'];

    if ($_POST['azione'] == "Inserisci")
    {
	$_parametri['descrizione'] = $_POST['descrizione'];
        $_parametri['listino'] = $_POST['listino'];
        
        
        $result = tabella_prezzi_cliente("inserisci", $_cliente, $_codice, $_parametri);

	if($result == "OK")
	{
	    echo "<tr><td>Codice $_codice inserito correttamente</td></td>";
	}
        else
        {
            echo $result['descrizione'];
        }

	return;
    }// parentesi fine funzione inserimento
//inizio aggiornamento
    if ($_POST['azione'] == "Modifica")
    {
        $_parametri['descrizione'] = $_POST['descrizione'];
        $_parametri['listino'] = $_POST['listino'];
        
        $result = tabella_prezzi_cliente("modifica", $_cliente, $_codice, $_parametri);
	
	if($result == "OK")
	{
	    echo "<tr><td>Codice $_codice Modificato correttamente</td></td>";
	}
        else
        {
            echo $result['descrizione'];
        }

	return;
    }// parentesi fine funzione inserimento
//inizio eliminazione
    if ($_POST['azione'] == "Elimina")
    {

	$result = tabella_prezzi_cliente("elimina", $_cliente, $_codice, $_parametri);
        
	if($result == "OK")
	{
	    echo "<tr><td>Codice $_codice Modificato correttamente</td></td>";
	}
        else
        {
            echo $result['descrizione'];
        }

	return;
    }

    if ($_POST['azione'] == "")
    {


	printf("<br><br><form action=\"prezziper2.php\" method=\"POST\">");

	echo "<tr><td align=center><br>";
	
        tabella_clienti("elenca_select", "cliente", $_parametri);
        
	echo "</td></tr>\n";

	echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Avanti\">\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";
	echo "</body></html>";
    }// fine paerte visiva.
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>