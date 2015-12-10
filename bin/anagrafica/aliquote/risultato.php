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
ini_set('session.gc_maxlifetime', $SESSIONTIME);
session_start();
$_SESSION['keepalive'] ++;
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

//Prendiamoci i post

    $_campi = $_POST['campi'];
    $_descrizione = $_POST['descrizione'];



    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
    echo "<tr>";
    echo "<td align=\"left\" valign=\"top\">";

    echo "<span class=\"testo_blu\"><b>Risulati ricerca</b></span>";



    // Stringa contenente la query di ricerca...

    $_descrizione = "%$_descrizione%";

    $query = sprintf("select * from aliquota where $_campi like \"%s\" order by codice", $_descrizione);

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "risultato.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    // Tutto procede a meraviglia...
    echo "<table width=\"700\">";
    echo "<tr>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
    echo "<td width=\"280\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Esenzione</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Percentuale</span></td>";

    echo "</tr>";

    foreach ($result AS $dati)
    {
        echo "<tr>";
        printf("<td width=\"70\" align=\"center\"><span class=\"testo_blu\"><a href=\"modifica_iva.php?azione=modifica&codice=%s\">%s</span></td>", $dati['codice'], $dati['codice']);
        printf("<td width=\"280\" align=\"left\"><span class=\"testo_blu\"><a href=\"modifica_iva.php?azione=modifica&codice=%s\">%s</span></td>", $dati['codice'], $dati['descrizione']);
        printf("<td width=\"50\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['eseniva']);
        printf("<td width=\"50\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['aliquota']);
        echo "</tr>";
        echo "<tr>";
        echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"280\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";

        echo "</tr>";
    }

    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>