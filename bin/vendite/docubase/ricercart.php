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
require "../../librerie/motore_doc_pdo.php";
require "../../librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);



if ($_SESSION['user']['vendite'] > "2")
{
    //recupero le variabili
    $_tdoc = $_SESSION['tdoc'];
// funzione cancella articolo
    $_cosa = $_POST['azione'];
// prendo la fiunzione della muovimentaizone
    $_calce = $_SESSION['calce'];
//mi prendo la sessione..
    $id = session_id();
    $_utente = $_SESSION['utente'];
    $dati = $_SESSION['datiutente'];
    
    intesta_html($_tdoc, "", $dati, "");
// ***********************************************************************************************************
// ricerca articolo conferma d'ordine..
    printf("<span class=\"testo_blu\">%s</span>\n", $_testo);
    printf("<form action=\"risultatoart.php\" method=\"POST\">\n");
    echo "<table width=\"400\" align=\"center\" border=\"0\"\n";

    echo "<td align=\"left\" valign=\"top\">
			<span class=\"intestazione\"><br><b>Cerca Articolo</b></span>
			</td></tr><tr><td>&nbsp </td></tr>";

// CAMPO DOVE ---------------------------------------------------------------------------------------
    echo "<tr><td width=\"200\" align=\"center\"><span class=\"testo_blu\">Dove:&nbsp;</span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"center\">";
    echo "<select name=\"campi\">\n";
    echo "<option value=\"descrizione\">Descrizione</option>\n";
    echo "<option value=\"articolo\">Codice articolo</option>\n";
    echo "<option value=\"codbar\">Codice a barre</option>\n";
    echo "<option value=\"artfor\">Articolo fornitore</option>\n";
    echo "<option value=\"desrid\">Descrizione ridotta</option>\n";
    echo "<option value=\"fornitore\">Fornitore</option>\n";
    echo "<option value=\"artfor2\">Articolo fornitore 2</option>\n";
    echo "<option value=\"fornitore2\">Fornitore 2</option>\n";
    echo "<option value=\"catmer\">Categoria merceologica</option>\n";
    echo "<option value=\"tipart\">Tipo articolo</option>\n";
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
    printf("<td class=\"colonna\" width=\"200\" align=\"center\"><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"60\" 
maxlength=\"40\"></td></tr>\n", $_descrizione);

// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Cerca !\">\n";
    echo "</td></tr></form>";

// ************************************************************************************** -->
    echo "</table>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
