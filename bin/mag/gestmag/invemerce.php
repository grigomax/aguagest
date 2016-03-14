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
require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("", $_percorso);
jquery_datapicker($_cosa, $_percorso);

echo "</head>";
echo "<body>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['magazzino'] > "1")
{

    if ($_GET['tipo'] == "merce")
    {
        $_tipologia = "Inventario per gruppo Merceologico";
    }
    else
    {
        $_tipologia = "Inventario per tipologia articolo";
    }


    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo "<td width=\"85%\" align=\"center\" valign=\"top\"><span class=\"intestazione\"><b>$_tipologia</b><br>\n";

    echo "Scegliere la sezione da stampare</span><br></td></tr>\n";

    echo "<br><br><form action=\"inventario_merc.php?tipo=$_GET[tipo]\" method=\"POST\" target=\"_blank\">\n";
    $_anno = date("Y");
    $_annov = $_anno - 1;
    $_hoy = date('d-m-Y');

    echo "<tr><td align=center><br>";
    echo "<select name=\"anno\">\n";
    printf("<option value=\"%s\">Anno = %s</option>\n", $_anno, $_anno);
    printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 1, $_anno - 1);
    printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 2, $_anno - 2);
    printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 3, $_anno - 3);
    printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 4, $_anno - 4);
    echo "</select>\n";
    echo "</td></tr>\n";

    //echo "<tr><td align=\"center\"><br>Prendi in cosiderazione i muovimenti fino alla data.. <input type=\"text\" name=\"datareg\" class=\"data\" value=\"$_hoy\" size=\"11\" maxlength=\"10\"></td></tr>\n";

    echo "<tr><td align=center><br>";

    if ($_GET['tipo'] == "merce")
    {
        tabella_catmer("elenca_select", "catmer", $_parametri);
    }
    else
    {
        tabella_tipart("elenca_select", "catmer", $_parametri);
    }


    echo "<tr><td align=center><br>";
    echo "<select name=\"cgiac\">\n";
    echo "<option value=\"MUOVI\">Visualizza articoli muovimentati diversi da zero</option>";
    echo "<option value=\"DIVZERO\">Visualizza articoli con giacenze diverse da zero</option>";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td align=center><br>";
    echo "<select name=\"cvalore\">\n";
    echo "<option value=\"M\">Calcolo valore sulla media dei prezzi di acquisto</option>";
    echo "<option value=\"U\">Calcolo valore sull'ultimo prezzo di acquisto</option>";
    echo "<option value=\"S\">Calcolo valore sul prezzo di acquisto indicato in anagrafica</option>";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td align=center><br>Stampare la data di stampa.. ? <br>\n";
    echo "NO <input type=\"radio\" value=\"SI\" name=\"data\" checked> - Data del Giorno <input type=\"radio\" value=\"SI\" name=\"data\"> - Ultimo giorno dell'anno <input type=\"radio\" value=\"mese\" name=\"data\">\n";
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