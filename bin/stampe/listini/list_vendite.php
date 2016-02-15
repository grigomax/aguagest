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
require "../../librerie/motore_anagrafiche.php";

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


    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\" valign=\"top\">\n";
    echo "<br><b>Scegliere il Listino Da stampare</b><br></span><br><td></tr>\n";

    echo "<form action=\"stampe_listini.php\" target=\"sotto\" method=\"POST\">\n";


    echo "<tr><td align=center><br>";

    if ($_GET['tipo'] == "per_codice")
    {
        echo "<input type=\"radio\" name=\"tipo\" value=\"per_codice\" checked>Per codice articolo<br>\n";
    }
    else
    {
        echo "<input type=\"radio\" name=\"tipo\" value=\"catmer\" checked>Per Categoria Merceologica<br>\n";
    }

    echo "<br><select name=\"listino\">\n";
    for ($_nlv = 1; $_nlv <= $nlv; $_nlv++)
    {
        printf("<option value=\"%s\">Listino prezzi %s </option>", $_nlv, $_nlv);
    }
    echo "</select>\n";
    echo "</td></tr>\n";


    echo "<tr><td align=center><br>";


    if ($_GET['tipo'] == "per_codice")
    {
        echo "<tr><td align=center>dal codice <input type=\"text\" name=\"ccodini\" size=\"10\"> al codice <input type=\"text\" name=\"ccodfin\" size=\"10\"></td></tr>";
        echo "<tr><td align=center><br>";


        tabella_articoli("elenca_select", "codini", $_parametri);

        echo "</td></tr>\n";

        echo "<tr><td align=center><br>";

        tabella_articoli("elenca_select", "codfin", $_parametri);
    }
    else
    {
        tabella_catmer("elenca_select_N", $_codice, "catmer");
    }




    echo "</td></tr>\n";


    echo "<tr><td align=center><br>";
    //selezioniamo il tipo di etichette richiamando le etichette

    $etichetta = tabella_stampe_layout("elenco_listini", $_percorso, $_tdoc);

    echo "<tr><td align=\"center\" height=\"60\">Selezionare il tipo di listino da stampare<br>";
    echo "<select name=\"tdoc\">\n";
    if ($etichetta == "NO")
    {
        echo "<option value=\"\">Nessun Listino presente</option>";
        echo "</select>\n";


        echo "<h3>Impossibile proseguire nessun listino</h3>\n";
        echo "<h3>presente nel database layout</h3>\n";
        echo "</td></tr>\n";
        exit;
    }
    else
    {
        foreach ($etichetta AS $dati_eti)
        {
            echo "<option value=\"$dati_eti[tdoc]\">$dati_eti[ST_NDOC]</option>";
        }
    }


    echo "</select>\n";
    echo "</td></tr>\n";

    echo "</table>\n";
    echo "<center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Stampa\">\n";
    echo "</form>\n";
    echo "</td>\n";
    echo "</td>\n</tr>\n";


    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>