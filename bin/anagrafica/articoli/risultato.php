<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['anagrafiche'] > "1")
{

	$_azione = $_GET['azione'];

//verifico se il calmpo articolo e vuoto cosi da evitar di cercare a caso
	
	if($_POST['articolo'] != "")
	{
		$_campi = "articolo";
		$_descrizione = $_POST['articolo'];
	}
        elseif($_POST['codbar'] != "")
        {
                $_campi = "codbar";
		$_descrizione = $_POST['codbar'];
        }
        else
        {       
		$_campi = $_POST['campi'];
		$_descrizione = $_POST['descrizione'];
	}

	echo "<table width=\"100%\" border=\"0\" align=\"left\">\n";

        echo "<tr><td align=center valign=\"top\">";

	echo "<h3>Risulati Ricerca</h3>\n";
	echo "<h3>Testo Cercato in <font color=\"GREEN\">$_campi</font></h3>\n";

// Stringa contenente la query di ricerca...
	if (($_descrizione == "") AND ($_azione != "elenca"))
	{
		echo "<h3> Nessun Carattere immesso nel campo ricerca </h3>";
		echo "<h3> Elencare Tutti gli articoli ? </h3>\n";
		echo "<br><A HREF=\"risultato.php?azione=elenca\">SI Elenca</A><br>";
		echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A>";
		exit;
	}


	if ($_campi == "codbar")
	{

		$articolo = tabella_barcode("singola", $_descrizione, $_articolo, $_rigo);

		$_parametri['descrizione'] = $articolo;
		$_parametri['campi'] = "articolo";
		
		$result = tabella_articoli("ricerca", $_codice, $_parametri);
	}
	else
	{
		$_parametri['descrizione'] = "%$_descrizione%";
		$_parametri['campi'] = $_campi;

		$result = tabella_articoli("ricerca", $_codice, $_parametri);
	}




	echo "<table width=\"90%\">";
// Esegue la query...

		
	if ($result == "NO")
	{
		echo "<tr><td colspan=6 align=center><h2>Nessun articolo Trovato</h2><br>
		<A HREF=\"#\" onClick=\"history.back()\">Riprova</A></td></tr>";
		return;
	}
	else
	{
		// La query ?stata eseguita con successo...
		// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
		// Tutto procede a meraviglia...

		echo "<tr>";
		if (($_campi == "articolo") OR ($_campi == "descrizione"))
		{
			echo "<td width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Foto</span></td>";
			echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Articolo</span></td>";
			echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Art. Fornitore</span></td>";
		}
		else
		{
			echo "<td width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Foto</span></td>";
			echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Articolo</span></td>";
			echo "<td width=\"140\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">$_campi</span></td>";
		}
		echo "<td width=\"280\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
		echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Un</span></td>";
		echo "<td width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Prezzo</span></td>";
                echo "<td width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Giacenza</span></td>";
		echo "</tr>";

		foreach ($result AS $dati)
		{
			echo "<tr>";
			if (($_campi == "articolo") OR ($_campi == "descrizione"))
			{
				printf("<td align=\"center\"><span class=\"testo_blu\"><a href=\"visualizzacod.php?codice=%s\"><img src=\"../../../setting/imm-art/%s\" height=\"50\" width=\"50\"></a></span></td>", $dati['articolo'], $dati['immagine']);
				printf("<td align=\"center\"><span class=\"testo_blu\"><a href=\"visualizzacod.php?codice=%s\" class=\"testo_blu\">%s</a></span></td>", $dati['articolo'], $dati['articolo']);
				printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['artfor']);
			}
			else
			{
				printf("<td align=\"center\"><span class=\"testo_blu\"><a href=\"visualizzacod.php?codice=%s\"><img src=\"../../../setting/imm-art/%s\" height=\"50\" width=\"50\"></a></span></td>", $dati['articolo'], $dati['immagine']);
				printf("<td align=\"center\"><span class=\"testo_blu\"><a href=\"visualizzacod.php?codice=%s\" class=\"testo_blu\">%s</a></span></td>", $dati['articolo'], $dati['articolo']);
				if($_campi == "codbar")
				{
					printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $_descrizione);
				}
				else
				{
					printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati[$_campi]);
				}
				
			}
			printf("<td align=\"left\"><span class=\"testo_blu\"><a href=\"visualizzacod.php?codice=%s\" class=\"testo_blu\">%s</a></span></td>", $dati['articolo'], $dati['descrizione']);
			printf("<td align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['unita']);
			echo "<td align=\"center\"><span class=\"testo_blu\"><b>$dati[listino]</b></span></td>\n";
                        $_impegno = impegni_articolo("solo_giacenza", $dati['articolo'], date('Y'));
                        
                        if($_impegno['giacenza'] > 0)
                        {
                            echo "<td align=\"center\"><font color=\"#a80994\"><b>$_impegno[giacenza]</b></font></td>\n";
                        }
                        else
                        {
                            echo "<td align=\"center\"><span class=\"testo_blu\"></span></td>\n";
                        }
                        
			echo "</tr>";
			echo "<tr>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
                        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "</tr>";
		}
	}

	echo "</td></tr></table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>