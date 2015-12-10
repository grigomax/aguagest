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
    echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";

    
    echo "<tr>\n";
    
    echo "<tr><td width=\"100%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

// ***********************************************************************************************************
    echo "<h1>Ricerca Articoli</h1>\n";
    echo "<form action=\"risultato.php\" method=\"POST\">\n";
    echo "<table width=\"80%\" border=\"0\"\n";

// CAMPO DOVE ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"right\"><span class=\"testo_blu\">Dove:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" align=\"left\">";
    echo "<select name=\"campi\">\n";
    echo "<option value=\"descrizione\">Descrizione</option>\n";
    echo "<option value=\"articolo\">Codice articolo</option>\n";
    echo "<option value=\"memoart\">Descrizione interna</option>\n";
    echo "<option value=\"pagcat\">Pagina Catalogo</option>\n";
    echo "<option value=\"descsito\">Desc. Sito</option>\n";
    echo "<option value=\"artfor\">Articolo fornitore 1</option>\n";
    echo "<option value=\"artfor2\">Articolo fornitore 2</option>\n";
    echo "<option value=\"artfor_3\">Articolo fornitore 3</option>\n";
    echo "<option value=\"catmer\">Categoria merceologica</option>\n";
    echo "<option value=\"tipart\">Tipo articolo</option>\n";
    echo "<option value=\"fornitore\">Fornitore 1</option>\n";
    echo "<option value=\"fornitore2\">Fornitore 2</option>\n";
    echo "<option value=\"fornitore_3\">Fornitore 3</option>\n";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
    printf("<td class=\"colonna\" width=\"200\" align=\"left\"><input type=\"text\" autofocus name=\"descrizione\" value=\"%s\" size=\"60\" maxlength=\"40\"></td></tr>\n", $_descrizione);

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Codice diretto => &nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"left\"><input type=\"text\" name=\"articolo\" size=\"16\" maxlength=\"15\"></td></tr>\n";
    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Codice a Barre => &nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"left\"><input type=\"text\" name=\"codbar\" size=\"33\" maxlength=\"30\"></td></tr>\n";

// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "</table>\n<input type=\"submit\" value=\"Cerca !\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
// ************************************************************************************** -->
    echo "</table>\n";
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>