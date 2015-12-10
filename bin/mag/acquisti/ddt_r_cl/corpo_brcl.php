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
    require_once "travaso_brcl.inc";

    $id = session_id();



    echo "<table width=\"80%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo "<td align=\"left\" valign=\"top\" colspan=\"5\">\n";
    echo "<span class=\"intestazione\">Gestione ddt reso conto lavoro</span><br> CORPO DOCUMENTO</td></tr>\n";



// funzione cancella articolo
    $_cosa = $_POST['azione'];

    if ($_cosa == cancella)
    {
	$_rigo = $_POST['rigo'];
	$_anno = $_GET['anno'];
	$_ndoc = $_GET['ndoc'];
//Imposto una sessione per poter aggiungere gli articoli edutilizzare le pagine vecchie
//cosi mi imposto la sessione calce in parziale
	$_SESSION['calce'] = "parziale";
	// Stringa contenente la query di ricerca...

	$query = sprintf("DELETE from doc_basket where sessionid=\"%s\" and rigo=\"%s\" and anno=\"%s\" AND ndoc = \"%s\"", $id, $_rigo, $_anno, $_ndoc);

// Esegue la query...
	mysql_query($query, $conn);
    }



// funzione modifica articolo
    if ($_cosa == modifica)
    {

	$_rigo = $_POST['rigo'];
	$_anno = $_GET['anno'];
	$_ndoc = $_GET['ndoc'];

// Stringa contenente la query di ricerca...

	$query = sprintf("select * from doc_basket where sessionid=\"%s\" and rigo=\"%s\" AND anno=\"%s\" AND ndoc= \"%s\" order by rigo", $id, $_rigo, $_anno, $_ndoc);

// Esegue la query...
	$res = mysql_query($query, $conn);

	$dati2 = mysql_fetch_array($res);


// seleziona.....
	echo "<tr>";
	echo "<td width=\"10\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Rigo</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice Arr.</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice Dest.</span></td>";
	echo "<td align=\"left\" class=\"logo\" colspan=\"5\"><span class=\"testo_bianco\">Descrizione</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Um</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.t&agrave;</span></td>";


	printf("<tr><form action=\"corpo_brcl.php?anno=%s&ndoc=%s\" method=\"POST\">", $dati2['anno'], $dati2['ndoc']);
	printf("<td align=center><input type=\"text\" name=\"rigo\" value=\"%s\" size=\"4\"></td>", $dati2['rigo']);
	printf("<td align=center><input type=\"text\" name=\"articolo\" value=\"%s\" size=\"15\" maxlength=\"15\"></td>", $dati2['articolo']);
	printf("<td align=center><input type=\"text\" name=\"codfor\" value=\"%s\" size=\"15\" maxlength=\"15\"></td>", $dati2['artfor']);
	printf("<td align=left colspan=\"5\"><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"50\" maxlength=\"80\"></td>", $dati2['descrizione']);
	printf("<td align=center><input type=\"text\" name=\"unita\" value=\"%s\" size=\"2\" maxlength=\"2\"></td>", $dati2['unita']);
	printf("<td align=center><input type=\"text\" name=\"qta2\" value=\"%s\" size=\"6\" maxlength=\"18\"></td>", $dati2['quantita']);


	echo "<tr><td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.t&agrave; evasa</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.t&agrave; estratta</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.t&agrave; saldo</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.t&agrave; rsaldo</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Listino</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Valore Agg.</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Sc A</span></td>";
#echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Sc B</span></td>";
#echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Sc C</span></td>";
#echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Aggiorna</span></td>";
	echo "<td align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
	echo "</tr>";
	printf("<tr><td align=center><input type=\"text\" name=\"qtaevasa\" value=\"%s\" size=\"6\" maxlength=\"18\"></td>", $dati2['qtaevasa']);
	printf("<td align=center><input type=\"text\" name=\"qtaestratta\" value=\"%s\" size=\"6\" maxlength=\"18\"></td>", $dati2['qtaestratta']);
	printf("<td align=center><input type=\"text\" name=\"qtasaldo\" value=\"%s\" size=\"6\" maxlength=\"18\"></td>", $dati2['qtasaldo']);
	printf("<td align=center><input type=\"text\" name=\"rsaldo\" value=\"%s\" size=\"2\" maxlength=\"18\"></td>", $dati2['rsaldo']);
	printf("<td align=\"center\"><input type=\"text\" name=\"listino\" value=\"%s\" size=\"6\" maxlength=\"18\"></td>", $dati2['listino']);
	printf("<td align=\"center\"><input type=\"text\" name=\"peso\" value=\"%s\" size=\"6\" maxlength=\"18\"></td>", $dati2['peso']);
	printf("<td align=center><input type=\"text\" name=\"sca\" value=\"%s\" size=\"4\" maxlength=\"6\"></td>", $dati2['sca']);
	/* #printf( "<td align=center><input type=\"text\" name=\"scb\" value=\"%s\" size=\"4\" maxlength=\"6\"></td>", $dati2['scab'] );
	  #printf( "<td align=center><input type=\"text\" name=\"scc\" value=\"%s\" size=\"4\" maxlength=\"6\"></td>", $dati2['scac'] );
	  if($dati2['agg'] == "SI")
	  {
	  print("<td align=center><input type=\"checkbox\" name=\"agg\" value=\"SI\"  CHECKED></td>");
	  }
	  else
	  {
	  print( "<td align=center><input type=\"checkbox\" name=\"agg\" value=\"SI\"></td>");
	  }
	 *
	 */
	echo "<td align=center><input type=\"submit\" name=\"azione\" value=\"aggiorna\"></td></form></tr></table>";
    }// chiusura di modifica

    if ($_cosa == aggiorna)
    {
	if (update_basket_vecchio($_POST['rigo'], $_GET['anno'], $_GET['ndoc'], $_POST['articolo'], $_POST['codfor'], $_POST['descrizione'], $_POST['unita'], $_POST['qta2'], $_POST['qtaevasa'], $_POST['qtaestratta'], $_POST['qtasaldo'], $_POST['rsaldo'], $_POST['listino'], $_POST['sca'], $_POST['scb'], $_POST['scc'], $_POST['agg'], $_POST['peso']))
	{
	    // Se ci sono errori la funzione pensa a stamparmi il codice d'errore...
	    exit(0);
	}
    }

    echo "</table><br>";
// Stringa contenente la query di ricerca...

    $query = sprintf("select * from doc_basket where sessionid=\"%s\" order by anno, ndoc, rigo", $id);

// Esegue la query...
    if ($res = mysql_query($query, $conn))
    {
// La query ?stata eseguita con successo...
// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
	if (mysql_num_rows($res))
	{
	    // Tutto procede a meraviglia...
	    printf("<td align=left>%s</td>", $dati['utente']);
	    echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"1\" align=\"center\"><tr>";
	    // Richiamo le variabili dalla sessione
	    // corpo documento...
	    echo "<tr><td colspan=\"6\" align=center><font face=\"arial\" size=\"4\"><a href=\"\">Aggiungi riga</a></font></td>";
	    printf("<form action=\"eseguimp_brcl.php\" method=\"POST\">");
	    printf("<td colspan=\"9\"align=center><input type=\"submit\" name=\"azione\" 	value=\"Estrai\"></form></td></tr>");

	    echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Riga</span></td>";
	    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice arr.</span></td>";
	    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice Dest.</span></td>";
	    echo "<td width=\"400\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
	    echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Um</span></td>";
	    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.t&agrave;</span></td>";
	    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.t&agrave; evasa</span></td>";
	    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.t&agrave; estratta</span></td>";
	    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Q.t&agrave; saldo</span></td>";
	    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Riga saldo</span></td>";
	    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Listino</span></td>";
	    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Valore Agg.</span></td>";
	    echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Sc A</span></td>";
	    #echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Sc B</span></td>";
	    echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Netto</span></td>";
	    echo "<td width=\"100\" align=\"center\"a class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
	    #echo "<td width=\"5\" align=\"center\"a class=\"logo\"><span class=\"testo_bianco\">Agg</span></td>";
	    echo "</tr>";

	    while ($dati = mysql_fetch_array($res))
	    {
		echo "<tr>";
		printf("<form action=\"corpo_brcl.php?anno=%s&ndoc=%s\" method=\"POST\">", $dati['anno'], $dati['ndoc']);
		printf("<td width=\"30\" height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"text\" name=\"rigo\" value=\"%s\" size=\"4\"></td>", $dati['rigo']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['articolo']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['artfor']);
		printf("<td width=\"400\" height=\"1\" align=\"left\" class=\"testo_blu\">%s</td>", $dati['descrizione']);
		printf("<td width=\"30\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['unita']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['quantita']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['qtaevasa']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><b>%s</b></td>", $dati['qtaestratta']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['qtasaldo']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['rsaldo']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['listino']);
		printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['peso']);
		printf("<td width=\"50\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['scaa']);
		#printf( "<td width=\"50\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['scab'] );
		printf("<td width=\"50\" height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati['nettoacq']);

// disabilitp l'ivart
		// Calcolo L'iva
		$_ivariga = $dati['iva'];
		$_castiva[$_ivariga] = ($_castiva[$_ivariga] + $dati['totrigaprovv']);

		//elimino la gestione del magazzino

		printf("<td class=\"testo_blu\"><input type=\"submit\" name=\"azione\" value=\"modifica\">");
		echo "</form></td>";
		printf("<td width=\"3\" height=\"1\" align=\"center\" class=\"testo_blu\"><font color=$_color>%s</font></td>", $dati['agg']);
		echo "</tr>";



		// calcolo castello iva e l'imponibile
		$_imponibile = $_imponibile + $dati['totriga'];
		//	$_totiva = $_totiva + $dati['totrigaprovv'];
		$_valoreriga = $dati['qtaestratta'] * $dati['nettoacq'];
		$_valoreevaso = $_valoreevaso + $_valoreriga;
	    }
	}
    }
    echo "<tr><td colspan=\"15\">&nbsp;</td></tr>";
//$_SESSION[ 'importi' ] = $_imponibile;
//$_SESSION[ 'totiva'] = $_totiva;
    printf("<form action=\"eseguimp_brcl.php\" method=\"POST\">");
    printf("<tr><td colspan=\"15 \" align=right><b>Imponibile materiale estratto = $_valoreevaso / Imponibile ordine rimanente = %s</b><br>
Volete chiudere il documento ?<input type=\"checkbox\" name=\"chiudi\" value=\"SI\"></td></tr>", $_imponibile);
    printf("<tr><td colspan=\"6\" align=center><font face=\"arial\" size=\"4\"><a href=\"\">Aggiungi riga</a></font></td>");

    printf("<td colspan=\"9\"align=center><input type=\"submit\" name=\"azione\" value=\"Estrai\"></form></td></tr>");

    printf("<form action=\"annulladoc.php\" method=\"POST\">");
    printf("<tr><td colspan=\"15\" align=\"center\" class=\"testo_blu\">Per annullare il documento<input type=\"submit\" name=\"azione\" value=\"Annulla\"></form></td>");
    printf("</tr>");
    echo " <tr><td colspan=10>";

    /*
      // Visualizzo tatali iva diverse
      while(list($indice, $valore) = each($_castiva))
      {
      echo "Aliquota Iva : $indice euro $valore<br>";
      }
      echo "Per un totale di Iva = $_totiva </td></tr>";
     */
    echo "</table><br></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>