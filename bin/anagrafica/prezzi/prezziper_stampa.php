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


require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);


if ($_SESSION['user']['plugins'] > "1")
{
    echo "<table align=\"left\" border=\"0\" width=\"100%\">\n";
    echo "<tr><td valign=\"top\" >\n";
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
	<tr>
	<td width=\"85%\" align=\"center\" valign=\"top\">
	<span class=\"intestazione\">Gestione Altre funzioni programma<br><br> <b>Scegliere il cliente</b></span><br>
	</td></tr>";
    
        $result = tabella_prezzi_cliente("elenco_stampa", $_utente, $_articolo, $_parametri);
        
        echo "<table>\n";
        echo "<tr><td>Ragione sociale </td><td>Articolo</td><td>Descrizione</td><td>Prezzo</td></tr>\n";
        foreach ($result AS $dati)
        {
            if($_ragsoc != $dati['codice'])
            {
                echo "<tr><td colspan=\"4\"><hr></td></tr>\n";
            }
            echo "<tr><td>$dati[ragsoc]</td><td>$dati[codarticolo]</td><td>$dati[descrizione]</td><td>$dati[listino]</td></tr>\n";
            $_ragsoc = $dati[codice];
    
        }
    echo "</table>\n";
        echo "</form>\n</td>\n";
        echo "</td>\n</tr>\n";
        echo "</body></html>";
   
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>