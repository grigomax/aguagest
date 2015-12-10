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
$conn = permessi_sessione("verifica_PDO", $_percorso);
//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['magazzino'] > "1")
{
   
    ?>
    <table width="80%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
    	<td align="center" valign="top" colspan="2">
    	    <span class="intestazione"><b>Risulatato modifica documento</b><br></span><br>
    	</td></tr>
	<?php

	// prendo le variabili

	$_anno = $_POST['anno'];
	$_ndoc = $_POST['ndoc'];
	$_status = $_POST['status'];



	// Stringa contenente la query di ricerca..
	// aggiorno l'intestazione nelle testa calce
	$query = sprintf("update of_testacalce set status=\"%s\" where anno=\"%s\" and ndoc=\"%s\"", $_status, $_anno, $_ndoc);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            echo "<tr><td align center><b> Errore Aggiornamento </b></td></tr>\n";
        }
        else
        {
            echo "<tr><td align center><b> Documento aggiornato perfettamente </b></td></tr>\n";
        }

	


	echo "</body></html>";
    }
    else
    {
	permessi_sessione($_cosa, $_percorso);
    }
    ?>