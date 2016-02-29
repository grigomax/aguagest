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
session_start();
$_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "2")
{
    //prendiamoci i post.
    
        //prendiamoci i post..
    $_tipo = $_GET['tipo'];
    
    if($_tipo == "tipart")
    {
        $_selezione = "Tipologia Articoli";
    }
    else
    {
        $_selezione = "Categoria Merceologica";
    }
    
    $_id = $_POST['id'];
    
    
// Inizio tabella pagina principale ----------------------------------------------------------

    echo "<table valign=\"top\" width=\"60%\" border=\"0\" align=\"center\"><tr><td valign=\"top\" align=\"left\" width=\"80%\">";
    printf("<form action=\"mod-gruppo.php?tipo=$_tipo\" method=\"POST\">\n");
    echo "<table width=\"80%\" border=\"0\" align=center>\n";

    echo "<tr><td colspan=2 align=\"center\"><h2 align=\"center\">Modifica $_selezione</h2></span></td>\n";


    echo "<tr><td colspan=2 align=center><br>";

    if ($_POST['azione'] == "Inserisci")
    {
	echo "Codice = <input type=\"text\" name=\"codice\" value=\"$dati[codice]\" size=\"20\" maxleght=\"18\">\n";
        echo "<br>Descrizione = <input type=\"text\" name=\"descrizione\" value=\"$_descrizione\" size=\"50\" maxleght=\"70\">\n";
        if($_tipo == "catmer")
        {
            if($dati['imballo'] == "1")
            {
                echo "<br>Questo gruppo sono imballi = <input type=\"checkbox\" name=\"imballo\" value=\"1\" checked>\n";
            }
            else
            {
                echo "<br>Questo gruppo sono imballi = <input type=\"checkbox\" name=\"imballo\" value=\"1\">\n";
            }
            
        }
	echo "<br><input type=\"submit\" name=\"azione\" value=\"Inserisci\"></td></tr>";
    }
    else
    {

	    if($_tipo == "tipart")
            {
                $dati = tabella_tipart("singola_id", $_codice, $_id);
                $_descrizione = $dati['tipoart'];
            }
            else
            {
                $dati = tabella_catmer("singola_id", $_codice, $_id);
                $_descrizione = $dati['catmer'];
            }
    
	echo "<input type=\"radio\" name=\"id\" value=\"$dati[id]\" checked >ID = $dati[id]<br>\n";
        echo "<input type=\"radio\" name=\"oldcod\" value=\"$dati[codice]\" checked ><br>\n";
	echo "Codice = <input type=\"text\" name=\"codice\" value=\"$dati[codice]\" size=\"20\" maxleght=\"18\">\n";
        echo "<br>Descrizione = <input type=\"text\" name=\"descrizione\" value=\"$_descrizione\" size=\"50\" maxleght=\"70\">\n";
        
        if($_tipo == "catmer")
        {
            if($dati['imballo'] == "1")
            {
                echo "<br>Questo gruppo sono imballi = <input type=\"checkbox\" name=\"imballo\" value=\"1\" checked>\n";
            }
            else
            {
                echo "<br>Questo gruppo sono imballi = <input type=\"checkbox\" name=\"imballo\" value=\"1\">\n";
            }
            
        }
        
        
        echo "<BR>Attenzione tutti gli articoli verranno aggiornati con il nuovo nome</td></tr>\n";
	echo "<td align=RIGHT><input type=\"submit\" name=\"azione\" value=\"Modifica\"><input type=\"submit\" name=\"azione\" value=\"Elimina\"></td></tr>";
    }

    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</table>";
// ************************************************************************************** -->
    echo "</td></tr></table>\n";

    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>