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
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

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

	if ($_GET['azione'] == "")
	{
		$_azione = $_POST['azione'];
		$_file = $_POST['file'];
		$_orfile = $_POST['orfile'];
                $_tipo = $_POST['tipo'];
	}
	else
	{
		$_azione = $_GET['azione'];
                $_tipo = $_GET['tipo'];
		$_file = $_GET['file'];
	}

        
        if ($_tipo == "immagini")
        {
            $_link = "imm-art";
            $_immagine = "immagine";
        }
        elseif ($_tipo == "disegni")
        {
             $_link = "imm-art/disegni";
            $_immagine = "immagine2";
        }
        else
        {
             $_link = "imm-art/prestazioni";
            $_immagine = "immagine3";
        }
        
        
        

	echo "<table border=\"0\" width=\"80%\" ><tr><td align=\"center\">";

	if ($_azione == "Elimina")
	{
		//elinazione file..

		unlink("../../../../setting/$_link/$_POST[file]");

		echo "<h3>File Eliminato con successo... !</h3>\n";

		echo "Aggiornamento database...<br>\n";

		$query = "UPDATE articoli SET $_immagine='' where $_immagine='$_file'";

		//eseguiamo
		domanda_db("exec", $query, $_cosa, $_ritorno, "verbose");

		echo "Aggiornamento dabase eseguito<br>";
	}
	else
	{
		if ($_azione == "Rinomina")
		{
			//rinomina file...
			echo "<h3>Rinominazione File.. </h3>\n";

			rename("../../../../setting/$_link/$_orfile", "../../../../setting/$_link/$_file");

			echo "<h3>File Rinominato con Successo</h3>\n";

			echo "<h3>Aggiornamento database...</h3>\n";

			$query = "UPDATE articoli SET $_immagine='$_file' where $_immagine='$_orfile'";

			//eseguiamo
			domanda_db("exec", $query, $_cosa, $_ritorno, "verbose");

			echo "<h3>Aggiornamento dabase eseguito</h3>";
		}


		echo "<form action=\"visualizza_imm.php\" method=\"post\">\n";
		echo "<h3>Cosa desideri fare con questa immagine.. ?</h3>";
                echo "<input name=\"tipo\" type=\"hidden\" value=\"$_tipo\" />\n";
		echo "<input type=\"radio\" name=\"orfile\" value=\"$_file\" checked>$_file <br>";
		echo "<input type=\"text\" name=\"file\" value=\"$_file\" size=\"50\" maxlength=\"50\" ><br>";
		echo "<input type=\"submit\" name=\"azione\" value=\"Elimina\" onclick=\"if(!confirm('Sicuro di voler Eliminare L immagine ?')) return false;\"> Oppure <input type=\"submit\" name=\"azione\" value=\"Rinomina\">\n";
//visualizziamo l'immagine e chiediamo se la si vuole cancellare rinominare oppure se è tutto ok..
		echo "</form>\n";

		echo "<p style='text-align:center;'><img src=\"../../../../setting/$_link/$_file\" width=\"500px\" height=\"500px\">";

		echo "</table>";
// ************************************************************************************** -->
	}



	echo "</td></tr></table>\n";
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>