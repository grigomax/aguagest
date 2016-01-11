<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
require $_percorso . "../setting/vars.php";
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

// aggiungiamo tutte le librerie necessarie
require $_percorso . "librerie/motore_primanota.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

	echo "<table width=\"100%\" align=\"center\" border=\"0\" valign=\"TOP\">";
	echo "<tr>";

	$_azione = $_GET['azione'];
	$_codice = $_GET['codice'];

	if ($_azione == "Modifica")
	{
		$dati = tabella_causali_contabili("singolo", $_percorso, $_codice, $_parametri);
		
		//verifichiamo errori..
		if($dati['errori'] != "")
		{
			echo "<h2>Errore Codice non trovato</h2>\n";
			exit;
		}
		
		$_submit = "Aggiorna";
	}
	else
	{
		$_submit = "Inserisci";
	}

	echo "<td align=\"center\" width=\"100%\">";

// Inizio tabella pagina principale ----------------------------------------------------------
	echo "<table width=\"100%\" cellspacing=\"0\" align=\"center\" border=\"0\" cellpadding=\"4\">\n";
	echo "<tr>";
	echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";


	echo "<span class=\"testo_blu\"><br><b>Gestione Causali Contabili</b></span><br>";

	echo "<form action=\"risinse_causali.php\" method=\"POST\">";
	echo "<table width=\"80%\" border=\"0\" align=\"center\">";

	if ($_azione == "Modifica")
	{
//mastro
		echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
		echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"codice\" value=\"$_codice\" checked>$_codice</td><tr>\n";
	}
	else
	{
		
		//vedimo l'ultimo numero inserito..
		$ultimo = tabella_causali_contabili("ultimo", $_percorso, $_codice, $_parametri);

		//aggiungiamo gli zeri prima..
		$ultimo = str_pad($ultimo, 3, '0', STR_PAD_LEFT);

//mastro
		echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
		echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" size=\"4\" maxlength=\"3\" name=\"codice\" value=\"$ultimo\" ><br><font size=\"2\">Codice numerico causale massimo tre cifre</td><tr>\n";
	}

// CAMPO descrizione---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Descrizione:&nbsp;</b></span></td>";
	echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"descrizione\" size=\"51\" maxlength=\"50\" value=\"$dati[descrizione]\"></td></tr>\n";

	if(gettype($conn) == "object")
	{
		$conn = null;
				
		//connettiamoci al database
		$conn = connessione_mysql("mysql", $query, $_parametri);
	}
	for ($index = 1; $index <= "10"; $index++)
	{
		$_conto = "conto_$index";

// CAMPO dare ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\">Codice Conto $index</span></td>";
		
		echo "<td align=\"left\" valign=\"top\">\n";
		
		$_parametri['name'] = "conto_$index";
		$_parametri['conto'] = $dati[$_conto];
		$_parametri['descrizione'] = tabella_piano_conti("descsingola", $dati[$_conto], "descsingola");
		
		tabella_piano_conti("elenco_select_conto", $_codconto, $_parametri);
		
		
		echo "</tr><tr>\n";
	}


// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
	echo "</table>\n<br><input type=\"submit\" name=\"azione\" value=\"$_submit\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Elimina\" onclick=\"if(!confirm('Sicuro di voler eliminare la causale?')) return false;\" >\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";
// ************************************************************************************** -->
	echo "</table>\n";
// Fine tabella pagina princ
// Fine tabella pagina principale -----------------------------------------------------------
	$conn->null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>