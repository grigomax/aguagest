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


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

    $_campi = $_POST['campi'];
    $_descrizione = $_POST['descrizione'];
    $_tut = $_GET['tut'];

    if ($_tut == "c")
    {
        $_tipo = "clienti";
    }
    else
    {

        $_tipo = "fornitori";
    }

// Inizio tabella pagina principale ----------------------------------------------------------
    echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";
    echo "<td align=\"center\" valign=\"top\" >\n";

    echo "<span class=\"testo_blu\"><b>Risulati ricerca $_tipo</b></span>";

// Stringa contenente la query di ricerca...
    if ($_descrizione == "")
    {
        echo "<h2> Nessun Carattere immesso nel campo ricerca </h2>";
        echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A>";
        exit;
    }



    $_descrizione = "%$_descrizione%";

    if ($_tut == "c")
    {
        $query = sprintf("select * from destinazioni where $_campi like \"%s\" order by dragsoc", $_descrizione);
    }
    else
    {
        $query = sprintf("select * from fornitori where $_campi like \"%s\" order by ragsoc", $_descrizione);
    }

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    echo "<table width=\"90%\">";
    echo "<tr>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">utente</span></td>";
    echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
    echo "<td width=\"280\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione sociale</span></td>";
    echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Indirizzo</span></td>";
    echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Citta</span></td>";
    echo "<td width=\"40\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Prov.</span></td>";
    echo "<td width=\"100\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Telefono</span></td>";
    echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Pred.</span></td>";
    echo "</tr>";

    if ($result->rowCount() < 1)
    {
        echo "<tr><td colspan=6 align=center><h2>Nessun $_tipo Trovato</h2><br>
		<A HREF=\"#\" onClick=\"history.back()\">Riprova</A></td></tr>";
        return;
    }
    else
    {



        foreach ($result AS $dati)
        {
            echo "<tr>";
            printf("<td align=\"center\"><span class=\"testo_blu\"><a href=\"modifica_dest.php?tut=$_tut&utente=%s&codice=$dati[codice]\">%s</span></td>", $dati['utente'], $dati['utente']);
            printf("<td align=\"center\"><span class=\"testo_blu\"><a href=\"modifica_dest.php?tut=$_tut&utente=%s&codice=$dati[codice]\">%s</span></td>", $dati['utente'], $dati['codice']);
            printf("<td align=\"left\"><span class=\"testo_blu\"><a href=\"modifica_dest.php?tut=$_tut&utente=%s&codice=$dati[codice]\">%s</span></td>", $dati['utente'], $dati['dragsoc']);
            printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['dindirizzo']);
            printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['dcitta']);
            printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['dprov']);
            printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['telefonodest']);
            printf("<td align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['predefinito']);


            echo "</tr>";
            echo "<tr>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "</tr>";
        }
    }

    echo "</td></tr></table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>