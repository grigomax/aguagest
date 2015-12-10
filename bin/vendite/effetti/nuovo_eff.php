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
session_start(); $_SESSION['keepalive']++;
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

if ($_SESSION['user']['vendite'] > "2")
{
    ?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td width="85%" align="center" valign="top">
                <span class="intestazione"><br>Crea Nuovo effetto<br><br> <b>Scegliere il cliente</b></span><br>
            </td></tr>
	<?php

	echo "<form action=\"maschera_eff.php\" method=\"POST\">\n";

	echo "<tr><td align=center><br>";
        
        tabella_clienti("elenca_select", "utente", $_parametri);
        
        echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Inserisci\">\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";
	echo "</table>";
	echo "</body></html>";
    }
    else
    {
	permessi_sessione($_cosa, $_percorso);
    }
    ?>