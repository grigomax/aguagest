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
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);

base_html("chiudi", $_percorso);
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

$_campi = $_POST['campi'];
$_campi2 = $_POST['campi2'];

echo "<span class=\"testo_blu\"><center><br><b>Elenco Fatture trovate</b></center></span><br>";



	$_descrizione = $_POST['descrizione'];
	$_descrizione = "%$_descrizione%";

	$_descrizione2 = $_POST['descrizione2'];
//	$_descrizione2 = "%$_descrizione2%";


	// Stringa contenente la query di ricerca...

/*	if($_campi == "articolo"){
	$query = "select distinct * from bv_bolle INNER JOIN utenti_fornitori ON bv_bolle.utente = utenti_fornitori.codice INNER JOIN bv_dettaglio ON bv_bolle.ndoc=bv_dettaglio.ndoc where '$_campi' like '$_descrizione'";}

	else*/

//	$query = "select * from fv_testacalce where $_campi like '$_descrizione' and $_campi2 like '$_descrizione2' order by ndoc ";

	$query = "select * from fv_testacalce where $_campi like '$_descrizione' order by ndoc ";

//	$query = "select * from fv_testacalce where $_campi >= '$_descrizione' and $_campi2 <= '$_descrizione2'";

//	$query = "select * from fv_testacalce where datareg >= '$_descrizione' and datareg <= '$_descrizione2'";


	// Esegue la query...
	if( $res = mysql_query( $query, $conn ) )
	{
		// La query ?stata eseguita con successo...
		// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
		if( mysql_num_rows( $res ) )
		{
			// Tutto procede a meraviglia...
			echo "<table align=\"center\">";
			echo "<tr>";

echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>";
echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">importo</span></td>";
echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
			echo "</tr>";

			while ($dati = mysql_fetch_array($res))
			{
			$_tdoc = $dati['tdoc'];
			
			if($_tdoc == "NOTA CREDITO")
					{
			echo "<tr>";
printf( "<td width=\"80\" align=\"center\"><font color=red>%s</span></td>",$dati[ 'datareg' ] );
printf( "<td width=\"80\" align=\"center\"><font color=red><b>%s</b></span></td>", $dati[ 'ndoc' ] );
printf( "<td width=\"400\" align=\"left\"><font color=red>%s</span></td>", $dati[ 'utente' ] );
printf( "<td width=\"80\" align=\"center\"><font color=red>%s</span></td>", $dati[ 'totimpo' ] );
printf( "<td width=\"80\" align=\"center\"><font color=red>%s</span></td>", $dati[ 'status' ] );
}
else
{
				
printf( "<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>",$dati[ 'datareg' ] );
printf( "<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati[ 'ndoc' ] );
printf( "<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati[ 'utente' ] );
printf( "<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati[ 'totimpo' ] );
printf( "<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati[ 'status' ] );
	echo "</tr>";
				}
				echo "<tr>";
				echo "<td width=\"200\" height=\"1\" align=\"center\" class=\"logo\"></td>";
				echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
				echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
				echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
				echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
				echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";

				echo "</tr>";
				
				$_nfattura = $_nfattura + 1;
				if($_tdoc == "NOTA CREDITO")
					{
					$_imponibile = $_imponibile - $dati['totimpo'];
					}
					else
					{
					$_imponibile = $_imponibile + $dati['totimpo'];
					}

			}

		}
	}

	echo "Totale imponibile fatturato";	echo $_imponibile;
 	echo "Totale fatture";	echo $_nfattura;


	?>
		</td>
	</tr>
</table>

</body>
</html>
