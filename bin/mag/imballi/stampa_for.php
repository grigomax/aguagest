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



echo "<br><link rel=\"stylesheet\" href=\"../../css/globalest.css\" type=\"text/css\">";


// mi prendo i post della pagina precendente..

$_codicefor = $_POST['codice'];

// Verifico se il cliente � anche fornitore..

	$query = sprintf( "select ragsoc, piva from fornitori where codice=\"%s\"", $_codicefor);

	$res = mysql_query( $query, $conn );
	$dati = mysql_fetch_array($res);
	$_ragsoc = $dati['ragsoc'];
	$_pivafor = $dati['piva'];

// ora provo a cercare sull'anagrafica fornitori una corrispondenza della partiva iva

	$query = sprintf( "select * from clienti where piva=\"%s\"", $_pivafor);
	$res = mysql_query( $query, $conn );
	
	if($_res >= 0)
	{ 
	// se esiste mi prendo il codice fornitore	
	$dati = mysql_fetch_array($res);
	$_codicecli = $dati['codice'];
	}
	else
	{
	$_codicecli = "";
	}

// ora cerco il tutto e lo metto su video..

	$query = sprintf(" select sum(qtacarico) AS carico, sum(qtascarico) as scarico, sum(qtacarico) - sum(qtascarico) AS differenza, descrizione, magazzino.articolo from magazzino INNER JOIN articoli ON magazzino. articolo=articoli.articolo where catmer='IMBALLI' and (tut='f' and utente=\"%s\" OR tut='c' and utente=\"%s\") GROUP BY magazzino.articolo ", $_codicefor, $_codicecli );

// inizio estrazioni dati e pagina visiva


	if( $res = mysql_query( $query, $conn ) )
	{
		if( mysql_num_rows( $res ) )
		{
			// Tutto procede a meraviglia...
			echo "<center>";
			echo "Elenco Fornitore $_ragsoc";
			echo "<table align=center border=1 width=\"80%\">";
			echo "<tr><td>codice</td><td>Descrizione</td><td>Carico</td><td>Scarico</td><td>Differenza</td><td> Scrivi TU !</td></tr>";
			while ($dati = mysql_fetch_array($res))
			{
			printf ("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>. </td></tr>", $dati['articolo'], $dati['descrizione'], $dati['carico'], $dati['scarico'], $dati['differenza'] );
			}
		echo "</table>";
		}
	}

echo "<br>";

	$query = sprintf("select sum(qtacarico) AS carico, sum(qtascarico) as scarico, sum(qtacarico) - sum(qtascarico) AS differenza, descrizione, magastorico.articolo from magastorico INNER JOIN articoli ON magastorico. articolo=articoli.articolo where catmer='IMBALLI' and (tut='f' and utente=\"%s\" OR tut='c' and utente=\"%s\" ) GROUP BY magastorico.articolo", $_codicefor, $_codicecli);

// inizio estrazioni dati e pagina visiva


	if( $res = mysql_query( $query, $conn ) )
	{
		if( mysql_num_rows( $res ) )
		{
			// Tutto procede a meraviglia...
			echo "<center>";
			echo "$_ragsoc";
			echo "<br>Anni Precedenti";
			echo "<table align=center border=1 width=\"80%\">";
			echo "<tr><td>codice</td><td>Descrizione</td><td>Carico</td><td>Scarico</td><td>Differenza</td><td> Scrivi TU !</td></tr>";
			while ($dati = mysql_fetch_array($res))
			{
			printf ("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>. </td></tr>", $dati['articolo'], $dati['descrizione'], $dati['carico'], $dati['scarico'], $dati['differenza'] );
			}
		echo "</table>";
		}
	}


?>
