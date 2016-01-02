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

if ($_SESSION['user']['vendite'] > "1")
{

?>
<br>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<tr>
<td width="85%" align="center" valign="top">
	<span class="intestazione"><b>Scegliere il cliente per saldare<br><font color=RED>La Fattura</span><br>
			</td></tr>
			<?php

// selezione fattura

	$_anno = date('Y');
	printf( "<br><br><form action=\"filtro_fv.php\" method=\"POST\">" );
	printf ("<tr><td align=center>Selezionare Anno <input type=\"number\" size=\"5\" name=\"anno\" value=\"%s\"</td></tr>",$_anno);
	echo "<tr><td align=center><br>";
        
        tabella_clienti("elenca_select", "cliente", $_parametri);
        

	echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Avanti\">\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";
	echo "</body></html>";
	
	}
else
{
    permessi_sessione($_cosa, $_percorso);
}

?>