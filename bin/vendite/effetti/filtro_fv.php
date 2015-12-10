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

if ($_SESSION['user']['vendite'] > "1")
{

?>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<tr>
<td align="left" valign="top">
			<span class="intestazione"><b>Intestatario</b></span><br>
			</td></tr>
			<tr><td><?php

	// Cerco il post inviatomi dalla pagina precente ed lo cerco sul database clienti

	$query = sprintf( "select * from clienti where codice=\"%s\"", $_POST['cliente'] );

	// Esegue la query...
	if( $res = mysql_query( $query, $conn ) )
	{
		// La query ?stata eseguita con successo...
		// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
		if( mysql_num_rows( $res ) )
		{
			// Tutto procede a meraviglia...
			while ($dati = mysql_fetch_array($res))
			{
	$_SESSION[ 'cliente' ] = $_POST['cliente'];
	$_utente = $_POST['cliente'];
	echo "<tr>";
	printf( "<td align=\"left\"><span class=\"testo_blu\">%s<br>", $dati['ragsoc'] );
	printf( "%s<br>", $dati['indirizzo'] );
	printf( "%s %s %s<br>", $dati['cap'],$dati['citta'],$dati['provincia'] );
	echo "</span></td></tr>";
	echo "<tr>";
	echo "<td width=\"100\" height=\"1\" align=\"center\" class=\"logo\"></td>";
	echo "</tr></table>";
	$_SESSION['agente'] = $dati['codagente'];

	$_bloccocli = $dati['bloccocli'];
			}

		}
	}


if ( $_bloccocli == "SI")
{
// se risulta bloccato chiudo tutto

echo "<table align=\"center\" width=\"80%\" border=\"0\">";
echo "<tr><td align=\"center\" class=\"logo\"><span class=\"testo_bianco\"><h2>Il seguente cliente Risulta Bloccato.</h2></span></td></tr>";
echo "<tr><td align=center><font color=RED ><h3> Si prega di aggiornare l'anagrafica cliente - IMPOSSIBILE Emettere qualsiasi Documento di vendita</h3></font></td></tr>";
echo "<tr><td align=center><br><a href=\"../../index.php\">Torna all'indice documenti</a></td></tr></table>";
}
else
{
echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">";
echo "<tr><td width=\"85%\" align=\"center\" valign=\"top\">";
echo "<span class=\"intestazione\"><b>Selezionare la fattura da saldare</b></span></td></tr>";
echo "<font color=\"red\">ATTENZIONE lo status dei documenti deve essere Stampato !</b></font></span></td></tr>";

	printf( "<br><br><form action=\"eseguimp_fv.php\" method=\"POST\">" );

	$_anno = $_POST['anno'];

	echo "<tr><td align=center>Anno Doc <input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>$_anno<br>";
	echo "<select name=\"codini\">\n";
	echo "<option value=\"\">Da Numero</option>";

	// Stringa contenente la query di ricerca...
	$query = sprintf( "select * from fv_testacalce INNER JOIN clienti ON fv_testacalce.utente = clienti.codice where utente=\"%s\" and anno=\"%s\" and status='stampato' order by ndoc", $_utente, $_anno );


		// Esegue la query...
	if( $res = mysql_query( $query, $conn ) )
	{
		// La query ?stata eseguita con successo...
		// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
		if( mysql_num_rows( $res ) )
		{
			// Tutto procede a meraviglia...
			echo "<span class=\"testo_blu\">";
			while ($dati = mysql_fetch_array($res))
			{
			printf( "<option value=\"%s\">%s - %s</option>\n", $dati[ 'ndoc' ], $dati[ 'ndoc' ], $dati['ragsoc']);
		}
		}
	}
	echo "</select>\n";
	echo "</td></tr>\n";

// disabilitazione secondo documento
/*
// inserimento fine numero

	echo "<tr><td align=center><br>";
	echo "<select name=\"codfine\">\n";
	echo "<option value=\"\">A Numero</option>";

	// Stringa contenente la query di ricerca...
	$query = sprintf( "select * from co_testacalce INNER JOIN clienti ON co_testacalce.utente = clienti.codice where utente=\"%s\" and anno=\"%s\" and status='stampato' order by ndoc", $_utente, $_anno );


		// Esegue la query...
	if( $res = mysql_query( $query, $conn ) )
	{
		// La query ?stata eseguita con successo...
		// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
		if( mysql_num_rows( $res ) )
		{
			// Tutto procede a meraviglia...
			echo "<span class=\"testo_blu\">";
			while ($dati = mysql_fetch_array($res))
			{
			printf( "<option value=\"%s\">%s - %s</option>\n", $dati[ 'ndoc' ], $dati[ 'ndoc' ], $dati['ragsoc']);
		}
		}
	}
	echo "</select>\n";
	echo "</td></tr>\n";

// cerco l'ultimo numero di bolla inserito e lo propongo


	// Stringa contenente la query di ricerca...
	// mi piglio l'ultimo n. documento inserito...
	$query = sprintf( "SELECT ndoc FROM fv_testacalce where anno=\"%s\" ORDER BY ndoc DESC LIMIT 1", $_anno );

	// Esegue la query...
	if( $res = mysql_query( $query, $conn ) )
	{
	$dati = mysql_fetch_array($res);
	$_undoc = $dati['ndoc'];
	// una volta trovato ne aggiungo uno.
	$_ndoc = $_undoc + 1;
	}
*/
//	Prendo le variabili abiente come il giorno

	$_annodoc = date("Y");
	$_mesedoc = date("m");
	$_daydoc = date("d");

	echo "<tr><td align=\"center\"><br>Tipo evasione <input type=\"text\" name=\"tdocevaso\" size=\"30\" value=\"emessa ri.ba.\" maxlenght=\"30\"> </td></tr>";

	printf( "<tr><td align=\"center\"><br>Numero Riba<input type=\"text\" name=\"ndoc\" size=\"6\" maxlenght=\"6\">  Anno <input type=\"text\" name=\"annodoc\" size=\"4\" value=\"%s\"></td></tr>", $_annodoc );

// invio di sette post

	echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Prosegui\");>\n";	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";

	echo "</body></html>";

}// fine blocco cliente


}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>