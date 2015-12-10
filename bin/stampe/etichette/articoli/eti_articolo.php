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
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
        base_html_stampa("", $_parametri);

echo "<STYLE>@page { size: 21cm 29.7cm; margin: 0cm; }</STYLE>\n";
echo "</head>";

//programma generico di stampa etichette..
//Prendiamo il nome dal database delle stampe 

$etichetta = tabella_stampe_layout("singola", $_percorso, $_GET['tdoc']);


//decidiamo di passare al get e non al post per poter allacciare eventuali collegamenti di stampa rapida della etichetta
//selezioniamo gli articoli interessati

if ($_GET[codice_end] == "")
{
	$query = "select substring(articolo, 1,$etichetta[ST_ARTICOLO_CT]) AS articolo, substring(descrizione,1,$etichetta[ST_DESCRIZIONE_CT]) AS descrizione, codbar, immagine from articoli where articolo = '$_GET[codice]'";
	$number = $_GET['number'];
}
else
{
	$query = "select substring(articolo, 1,$etichetta[ST_ARTICOLO_CT]) AS articolo, substring(descrizione,1,$etichetta[ST_DESCRIZIONE_CT]) AS descrizione, codbar, immagine from articoli where articolo >= '$_GET[codice]' AND articolo <= '$_GET[codice_end]' ORDER BY articolo";
	$number = "1";
}

$result = $conn->query($query);

if ($conn->errorCode() != "00000")
{
	$_errore = $conn->errorInfo();
	echo $_errore['2'];
	//aggiungiamo la gestione scitta dell'errore..
	$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
	$_errori['files'] = "eti_articolo.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
}


//facciamo un'attimo i conti sulla dimensione



if ($etichetta[ST_RIGA] == "SI")
{
	echo "<table align=\"left\" width=\"$etichetta[ST_RIGA_LC]\" border=\"1\" cellpadding=\"1\" cellSpacing=\"0\">";
}
else
{
	echo "<table align=\"left\" width=\"$etichetta[ST_RIGA_LC]\" border=\"0\" cellpadding=\"1\" cellSpacing=\"0\">";
}


foreach ($result AS $dati)
{

	for ($index = 1; $index <= $number; $index++)
	{


		//cerchiamo di fare una etichetta multi uso

		if ($etichetta[ST_QUANTITA] == "SI")
		{
			echo "<tr>";
			echo "<td>\n";

			$etichetta['ST_RIGA_LC'] = $etichetta['ST_RIGA_LC'] - $etichetta['ST_QUANTITA_LC'] +$etichetta['ST_QTASALDO_LC'];
			
			echo "<table align=\"left\" width=\"$etichetta[ST_RIGA_LC]\" border=\"0\" cellpadding=\"0\" cellSpacing=\"0\">";
		}
		
		//riga di intestazione
		if ($etichetta['ST_QTAEVASA'] == "SI")
		{
			echo "<tr>";
			//spessore laterale sx
			echo "<td width=\"$etichetta[ST_QTASALDO_LC]px\" height=\"$etichetta[ST_QTAEVASA_LC]\" ></td>\n";

			//corpo etichetta
			echo "<td width=\"$etichetta[ST_RIGA_LC]px\" height=\"$etichetta[ST_QTAEVASA_LC]\"  align=\"$etichetta[ST_QTAEVASA_ALL]\">\n";
			echo "<font face=\"$etichetta[ST_FONTINTEST]\" style=\"font-size: $etichetta[ST_FONTINTESTSIZE]" . "pt;\" >";
			echo "$azienda";

			echo "</font></td>\n";
			echo "</tr>\n";
		}

		//riga descrizione
		if (($etichetta['ST_DESCRIZIONE'] == "SI") OR ($etichetta['ST_ARTICOLO'] == "SI"))
		{
			echo "<tr>";
			//spessore laterale sx
			echo "<td width=\"$etichetta[ST_QTASALDO_LC]px\" height=\"$etichetta[ST_ARTICOLO_LC]\" ></td>\n";

			//corpo etichetta
			echo "<td width=\"$etichetta[ST_RIGA_LC]px\" height=\"$etichetta[ST_DESCRIZIONE_LC]\"  align=\"$etichetta[ST_DESCRIZIONE_ALL]\">\n";
			echo "<font face=\"$etichetta[ST_FONTCORPO]\" style=\"font-size: $etichetta[ST_FONTCORPOSIZE]" . "pt;\" >";
			if ($etichetta['ST_ARTICOLO'] == "SI")
			{
				echo "<font color=\"red\"><b>$dati[articolo] - </b></font>";
			}

			if ($etichetta['ST_DESCRIZIONE'] == "SI")
			{
				echo $dati[descrizione];
			}

			echo "</font></td>\n";
			echo "</tr>\n";
		}

		//riga descrizione
		if ($etichetta['ST_ARTFOR'] == "SI")
		{
			echo "<tr>";
			//spessore laterale sx
			echo "<td width=\"$etichetta[ST_QTASALDO_LC]px\" height=\"$etichetta[ST_ARTFOR_LC]\" ></td>\n";

			//corpo etichetta
			echo "<td width=\"$etichetta[ST_RIGA_LC]px\" height=\"$etichetta[ST_ARTFOR_LC]\"  align=\"$etichetta[ST_ARTFOR_ALL]\">\n";

			if ($etichetta['ST_ARTFOR_CT'] != "0")
			{
				echo "<img align=\"center\" src=$_percorso/tools/barcode/barcode.php?barcode=$dati[articolo]&height=$etichetta[ST_ARTFOR_LC]&text=1&textdim=$etichetta[ST_ARTFOR_CT]></img>\n";
			}
			else
			{
				echo "<img align=\"center\" src=$_percorso/tools/barcode/barcode.php?barcode=$dati[articolo]&height=$etichetta[ST_ARTFOR_LC]&text=0></img>\n";
			}


			echo "</td>\n";
			echo "</tr>\n";
		}

		// Eventuale cosa da scrivere qui sotto

		if ($etichetta['ST_QTAESTRATTA'] == "SI")
		{
			echo "<tr>";
			//spessore laterale sx
			echo "<td width=\"$etichetta[ST_QTASALDO_LC]px\" height=\"$etichetta[ST_QTAESTRATTA_LC]\" ></td>\n";

			//corpo etichetta
			echo "<td width=\"$etichetta[ST_RIGA_LC]px\" height=\"$etichetta[ST_QTAESTRATTA_LC]\"  align=\"$etichetta[ST_QTAESTRATTA_ALL]\">\n";
			echo "<font face=\"$etichetta[ST_FONTESTACALCE]\" style=\"font-size: $etichetta[ST_FONTESTACALCESIZE]" . "pt;\" >";
			echo $etichetta['ST_AVVISO_LC'];

			echo "</font></td>\n";
			echo "</tr>\n";
		}

		if ($etichetta[ST_QUANTITA] == "SI")
		{
			echo "</table>\n";


			echo "</td><td width=\"$etichetta[ST_QUANTITA_LC]px\" height=\"$etichetta[ST_QUANTITA_LC]\" align=\"$etichetta[ST_QUANTITA_ALL]\">\n";
			echo "<img src=$_percorso../imm-art/$dati[immagine]  width=\"$etichetta[ST_QUANTITA_LC]px\" height=\"$etichetta[ST_QUANTITA_LC]\" ></img>\n";
			echo "</td>\n";
			echo "</tr>\n";
		}

		// inseriamo lo spazio tra una etichetta e quella successiva
		echo "<tr>";
		echo "<td width=\"$etichetta[ST_QTASALDO_LC]px\" height=\"$etichetta[ST_QTASALDO_CT]\" ></td>\n";
		echo "<td width=\"$etichetta[ST_RIGA_LC]px\" height=\"$etichetta[ST_QTASALDO_CT]\" >&nbsp;</td>\n";
		echo "</tr>\n";

		
	}
	
	
}
echo "</table></body></html>\n";
?>