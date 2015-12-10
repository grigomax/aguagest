<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso . "../setting/vars.php";
ini_set('session.gc_maxlifetime', $SESSIONTIME);

session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carico le librerie necessarie
require "../librerie/motore_anagrafiche.php";

base_html("", $_percorso);
java_script($_cosa, $_percorso);

jquery_datapicker($_cosa, $_percorso);
echo "</head>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['scadenziario'] > "1")
{

    echo "<table align=\"left\" width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\">\n";

    echo "<h3 align=\"center\">Scadenze ed Appuntamenti in agenda</h3>";
    echo "<h3 align=\"center\">Cerca Scadenza</h3>";

    echo "<form action=\"scadenziario.php\" method=\"POST\">\n";

    echo "<h4>Seleziona la data di partenza</h4>\n";

    $_hoy = date('d-m-Y');

    echo "<input type=\"text\" size=\"12\" name=\"data_scad\" class=\"data\" value=\"$_hoy\"><br>\n";

    echo "<br>Seleziona dove :<select name=\"campi\">\n";
    echo "<option value=\"nscad\">Numero Scadenza</option>\n";
    echo "<option value=\"descrizione\">Descrizione</option>\n";
    echo "<option value=\"importo\">Importo</option>\n";
    echo "<option value=\"utente\">Codice Fornitore</option>\n";
    echo "<option value=\"ndoc\">Numero Documento</option>\n";
    echo "<option value=\"nproto\">Numero Protocollo</option>\n";
    echo "<option value=\"banca\">Codice Banca</option>\n";
    echo "<option value=\"impeff\">Importo Effetto</option>\n";
    echo "<option value=\"status\">status</option>\n";
    echo "</select>\n";

    echo "<br><br>\n";
    echo "<input type=\"text\" size=\"50\" name=\"descrizione\" >\n";

    echo "<br><input type=\"submit\" value=\"Stampa\"></form>\n";

    echo "</td></tr></table>\n";

    echo "</td></tr></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>