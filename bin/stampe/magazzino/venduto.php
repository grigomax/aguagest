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



if ($_SESSION['user']['stampe'] > "1")
{
    ?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
    	<td width="85%" align="center" valign="top">
    	    <span class="intestazione"><h1>Stampa Venduto di magazzino</h1><br><b>Scegliere il periodo di riferimento</b><br></span><br>
    	</td></tr>
	<?php

	printf("<form action=\"rp_venduto.php\" target=\"sotto\" method=\"POST\">");

	echo "<tr><td align=center><br>";

        tabella_magazzino("elenco_anni", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_tut, $_rigo, $_utente, $_codice, "anno");
        
	echo "</td></tr>\n";

        echo "<tr><td align=center><br>";
        select_mese($_cosa, "mese");

	echo "</td></tr>\n";

	echo "<tr><td align=center><br>";
	echo "<select name=\"tipo\">\n";
	echo "<option value=\"catmer\">Riassuntivo per Gruppo merceologico</option>";
	echo "<option value=\"tipart\">Riassuntivo per Tipologia articolo</option>";
	echo "<option value=\"cliente\">Riassuntivo per Cliente</option>";
	echo "<option value=\"fornitore\">Riassuntivo per Fornitore</option>";
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Stampa\");>\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";


	echo "</body></html>";
    }
    else
    {
	permessi_sessione($_cosa, $_percorso);
    }
    ?>