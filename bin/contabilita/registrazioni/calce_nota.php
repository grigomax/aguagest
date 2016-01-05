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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_primanota.php";
require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("", $_percorso);
java_script($_cosa, $_percorso);
jquery_datapicker($_cosa, $_percorso);

echo "</head>\n";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "2")
{


	$id = session_id();

	if (($_POST['azione'] == "Salda") OR ($_POST['azione'] == "Spesometro"))
	{

		//forziamo la saldatura del documento..
		if ($_GET['anno'] != "")
		{
			$_nreg = $_GET['nreg'];
			$_anno = $_GET['anno'];
		}
		else
		{
			$_nreg = $_POST['nreg'];
			$_anno = $_POST['anno'];
		}
		//leggiamo la registrazione;
		$dati = tabella_primanota("leggi_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

		//impostiamo i nuovi parametri per unirci con il programma vecchio..
		$_SESSION['datareg'] = $dati['data_reg'];
		$_SESSION['datacont'] = $dati['data_cont'];
		$_SESSION['testo'] = $dati['descrizione'];
		$_SESSION['causale'] = $dati['causale'];
		$_SESSION['anno'] = $_anno;
		$_SESSION['submit'] = "Aggiorna";
		$_SESSION['nreg'] = $_nreg;
		$_SESSION['utente'] = $dati['utente'];
		$_finestra = "FV";
		$_parametri = "Salda";
		$_SESSION['parametri']['segno'] = $dati['segno'];
		$_submit = "Salda";
		$_data_reg = $_SESSION['datareg'];
		$_data_cont = $_SESSION['datacont'];
		$_testo = $_SESSION['testo'];
		$_spesometro = $dati['sp_metro'];
		$_note = $dati['note'];
	}
	else
	{
		
//Recupero le sessioni così da mostrarle..
		$_data_reg = $_SESSION['datareg'];
		$_data_cont = $_SESSION['datacont'];
		$_testo = $_SESSION['testo'];
		$_causale = $_SESSION['causale'];
		$_anno = $_SESSION['anno'];

		$_submit = $_SESSION['submit'];
	}


	if ($_submit == "Modifica")
	{
		$_submit = "Aggiorna";
//    giriamo le date cosi da farle apparire giuste..
		$_data_reg = cambio_data("it", $_data_reg);
		$_data_cont = cambio_data("it", $_data_cont);
		$_finestra = $_causale;
		$_parametri = "Modifica";
	}
	elseif ($_submit == "Salda")
	{
		$_submit = "Salda";
	}
	else
	{
		$_submit = "Inserisci";
	}


	if ($_causale == "FA")
	{
		$_finestra = "FA";
	}

//qui di seguito elenchiamo la schermata della calce della prima nota..



	echo "<center>\n";
	echo "<h2>Registrazione di prima nota</h2>\n";

	echo "<table width=\"90%\" border=\"0\">\n";

	echo "<td colspan=\"3\">Data registrazione $_data_reg</td><td colspan=\"3\">Data Contabile $_data_cont</td></tr>\n";
	echo "<form action=\"result_nota.php\" method=\"POST\"><tr><td colspan=\"6\"><br>Descrizione Movimento   <b>$_testo</b></td></tr>\n";
	echo "<tr><td colspan=\"6\"><hr></td></tr>\n";

	schermate_calcenota($_finestra, $_parametri);


	if ($SPESOMETRO == "SI")
	{
		echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
		if ($_spesometro == "SI")
		{
			echo "<tr><td colspan=\"6\" align=\"right\"> Registrazione Esclusa dallo Spesometro <input type=\"checkbox\" name=\"spesometro\" value=\"SI\" checked></td></tr>\n";
		}
		elseif ($_SESSION['spesometro'] == "SI")
		{
			echo "<tr><td colspan=\"6\" align=\"right\"> Registrazione Esclusa dallo Spesometro<input type=\"checkbox\" name=\"spesometro\" value=\"SI\" checked></td></tr>\n";
		}
		else
		{
			echo "<tr><td colspan=\"6\" align=\"right\"> Registrazione Esclusa dallo Spesometro<input type=\"checkbox\" name=\"spesometro\" value=\"SI\"></td></tr>\n";
		}

		echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
	}

	$_note = $_SESSION['parametri']['note'];

	echo "<tr><td colspan=\"6\" align=\"center\" valign=\"TOP\">Note Documento :&nbsp;";
	echo "<textarea cols=\"60\" rows=\"6\" name=\"note\" value=\"$_note\">$_note</textarea></td></tr>\n";


	echo "<tr><td colspan=\"6\" align=\"right\"><input type=\"submit\" value=\"$_submit\" name=\"azione\" onclick=\"if(!confirm('Procedere Alla Registrazione ? ')) return false;\" >\n";

	echo "</form>\n";
	echo "<form action=\"corpo_nota.php\" method=\"POST\">\n";
	echo "<tr><td colspan=\"6\" align=\"center\"><input type=\"submit\" value=\"Annulla\" name=\"azione\" onclick=\"if(!confirm('Sicuro di voler Annullare la operazione ?')) return false;\" ></form>\n";
	echo "</table>\n";
	echo "</body></html>\n";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>