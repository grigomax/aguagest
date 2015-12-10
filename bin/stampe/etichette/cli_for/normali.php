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

session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require $_percorso . "librerie/motore_anagrafiche.php";

if ($_POST['codconto'] == "")
{
    if ($_POST['tipo_cf'] != "")
    {
        echo Show_destinazione();
        die;
    }
}

//carichiamo la base delle pagine:
base_html("", $_percorso);

java_script($_cosa, $_percorso);

jquery_menu_cascata("base", "normali.php");

echo "</head>\n";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['stampe'] > "1")
{
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
        <tr>
    	<td width=\"85%\" align=\"center\" valign=\"top\">\n";

    echo "<form action=\"eti_cli_for.php\" id=\"myform\" target=\"_blanck\" method=\"POST\">";

    echo "<tr><td align=center><h2><input type=\"radio\" name=\"azione\" value=\"$_GET[azione]\" checked>Stampa Etichette $_GET[azione] </h2>Selezionare il codice da stampare<br>";

    if ($_GET['azione'] == "fornitori")
    {
        tabella_fornitori("elenca_select", "codice", $_parametri);
        echo "<tr><td align=\"center\"><BR><input type=\"radio\" value=\"normali\" name=\"tipo\" checked>NORMALI - <input type=\"radio\" value=\"destinazione\" name=\"tipo\"> Destinazione </td></tr>\n";

        echo "</td></tr>\n";
    }
    else
    {
        //tabella_clienti("elenca_select", "codice", $_parametri);
        echo "<select id=\"tipo_conto\" name=\"tipo_cf\">" . Show_cliente() . "</select>\n";
        echo "<br><select id=\"codconto\" name=\"codconto\"><option>Scegli...</option>\n";
        echo "</td></tr>\n";
    }

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