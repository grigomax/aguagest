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
base_html_stampa("chiudi", $_parametri);

if ($_SESSION['user']['anagrafiche'] > "1")
{

    //prendiamoci i post..
    $_tipo = $_GET['tipo'];

    if ($_tipo == "tipart")
    {
        $_descrizione = "Tipologia Articoli";
        $result = tabella_tipart("elenca_codice", $_codice, $_parametri);
        $_campo = "tipoart";
    }
    else
    {
        $_descrizione = "Categoria Merceologica";
        $result = tabella_catmer("elenca_codice", $_codice, $_parametri);
        $_campo = "catmer";
    }

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
    echo "<tr>";
    echo "<td colspan=\"2\" align=\"center\" valign=\"top\">";
    $_data = date('d-m-Y');
    echo "<span class=\"testo_blu\"><h2>Stampa $_descrizione </h2> $azienda $_data</span>";


    // Tutto procede a meraviglia...
    echo "<table width=\"90%\">";
    echo "<tr>";

    echo "<td width=\"100\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
    echo "<td width=\"350\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";


    echo "</tr>";

    foreach ($result AS $dati)
    {
        echo "<tr>";
        echo "<td width=\"100\" align=\"left\"><span class=\"testo_blu\">$dati[codice]</span></td>\n";
        echo "<td width=\"350\" align=\"left\"><span class=\"testo_blu\">$dati[$_campo]</span></td>\n";
        echo "</tr>";
        echo "<tr>";
        echo "<td width=\"100\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"350\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "</tr>";
    }

    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>