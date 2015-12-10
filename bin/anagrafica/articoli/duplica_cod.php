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
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{


    // Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";
    echo "<tr>\n";
    echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";
    echo "<br>Scegliere L'articolo da duplicare<br>\n";

    $_anno = date("Y");

    printf("<form action=\"ris_duplicacod.php\" method=\"POST\">");

    echo "<b>Selezionare l'articolo</b><br>";
    echo "<br><select name=\"articolo\">\n";
    echo "<option value=\"\"></option>";

    $result = tabella_articoli("elenco_select", $_codice, $_parametri);

    if ($res['errori'] != "")
    {
        echo $res['errori'];
    }
    else
    {
        // Tutto procede a meraviglia...
        echo "<span class=\"testo_blu\">";
        foreach ($result AS $dati)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati['articolo'], $dati['articolo'], $dati['descrizione']);
        }
    }

    echo "</select>\n";


    echo "<br><br><b> Immettere qui sotto il nuovo codice</b><br>";
    echo "<input type=\"text\" name=\"newcod\" size=\"20\" maxlength=\"15\">";


    echo "<center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Duplica\">\n";
    echo "</form>\n";
    echo "</td>\n</tr>\n";


    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>