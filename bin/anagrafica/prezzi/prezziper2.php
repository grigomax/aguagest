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
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

// visualizzo i prezi personalizzati de cliente
// imposto la sessione per il cliente

       

    echo "<span class=\"testo_blu\"><center><br><b>Risulati</b></center></span><br>";
    echo "<span class=\"testo_blu\"><center><br><b>Inserisci nuovo articolo premendo <a href=\"prezziper2.php?inserisci=si&cliente=$_POST[cliente]\">QUI !</a></b></center></span><br>";



    echo "<form action=\"prezziper3.php\" method=\"POST\">\n";
    
    
    if ($_GET['inserisci'] == "si")
    {
        echo "<input type=\"radio\" value=\"$_GET[cliente]\" name=\"utente\" checked>codice = $_GET[cliente]\n";
        echo "<center><span class=\"intestazione\"><br>Gestione Prezzi personalizzati di vendita<br><b>Scegliere l'articolo</b></span>
	</td></tr>";
        

        echo "<tr><td align=center><br>";

        tabella_articoli("elenca_select", "codice", $_parametri);
        
        
        echo "</td></tr>\n";

        echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Inserisci\">\n";
    }
    else
    {
        
        echo "<input type=\"radio\" value=\"$_POST[cliente]\" name=\"utente\" checked>codice = $_POST[cliente]\n";
        // Tutto procede a meraviglia...
        echo "<table align=\"center\">";
        echo "<tr>";

        echo "<td width=\"400\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
        echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Prezzo</span></td>";
        echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Modifica</span></td>";
        echo "</tr>";

        $result = tabella_prezzi_cliente("elenca_per_cli", $_POST['cliente'], $_articolo, $_parametri);
        foreach ($result AS $dati)
        {
            echo "<tr>";

            printf("<td width=\"400\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['descrizione']);
            printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['listino']);
            printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"submit\" name=\"codice\" value=\"%s\"></td>", $dati['codarticolo']);
            echo "</tr>";
            echo "<tr>";
            echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "</tr>";
        }
    }// fine query inserimento
    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>