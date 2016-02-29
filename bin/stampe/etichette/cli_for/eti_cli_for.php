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

//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);


if ($_SESSION['user']['stampe'] > "1")
{
//recupero le variabili

$_azione = $_POST['azione'];

    if ($_azione == "fornitori")
    {
        
        $_utente = $_POST['codice'];
        $_tipo = $_POST['tipo'];
        $dati = tabella_fornitori("singola", $_utente, $_parametri);
        $_tdoc = "eti_fornitori";

        if ($_tipo == "normali")
        {
            $_ragsoc = $dati['ragsoc'];
            $_ragsoc2 = $dati['ragsoc2'];
            $_indirizzo = $dati['indirizzo'];
            $_cap = $dati['cap'];
            $_citta = $dati['citta'];
            $_prov = $dati['prov'];
            $_nazione = $dati['nazione'];
        }
        else
        {
            $_ragsoc = $dati['dragsoc'];
            $_ragsoc2 = $dati['dragsoc2'];
            $_indirizzo = $dati['dindirizzo'];
            $_cap = $dati['dcap'];
            $_citta = $dati['dcitta'];
            $_prov = $dati['dprov'];
            $_nazione = $dati['dnazione'];
        }
    }
    else
    {
        if ($_POST['codconto'] != "0")
        {
            $dati = tabella_destinazioni("singola", $_POST['tipo_cf'], $_POST['codconto'], $_parametri);
            $_ragsoc = $dati['dragsoc'];
            $_ragsoc2 = $dati['dragsoc2'];
            $_indirizzo = $dati['dindirizzo'];
            $_cap = $dati['dcap'];
            $_citta = $dati['dcitta'];
            $_prov = $dati['dprov'];
            $_nazione = $dati['dnazione'];
        }
        else
        {
            $dati = tabella_clienti("singola", $_POST['tipo_cf'], $_parametri);
            $_ragsoc = $dati['ragsoc'];
            $_ragsoc2 = $dati['ragsoc2'];
            $_indirizzo = $dati['indirizzo'];
            $_cap = $dati['cap'];
            $_citta = $dati['citta'];
            $_prov = $dati['prov'];
            $_nazione = $dati['nazione'];
        }

        $_tdoc = "eti_clienti";
    }



    $etichetta = tabella_stampe_layout("singola", $_percorso, $_tdoc);


    if ($etichetta[ST_RIGA] == "SI")
    {
        echo "<table align=\"left\" width=\"$etichetta[ST_RIGA_LC]\" border=\"1\" cellpadding=\"$etichetta[ST_INTERLINEA]\" cellSpacing=\"0\">";
    }
    else
    {
        echo "<table align=\"left\" width=\"$etichetta[ST_RIGA_LC]\" border=\"0\" cellpadding=\"$etichetta[ST_INTERLINEA]\" cellSpacing=\"0\">";
    }

    //echo "<table align=\"left\" width=\"90%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
// servo lo spazio per l'intestazione
    echo "<tr>\n";
    echo "<td colspan=3>\n";

    $_parametri['intestazione'] = $etichetta['ST_TLOGO'];
    $_parametri['WIDTH'] = $PRINT_WIDTH;
    $_parametri['intesta_immagine'] = $etichetta['ST_LOGOG'];
    intestazione_html($_cosa, $_percorso, $_parametri);

    //echo "<img src=\"" . $_percorso . "../setting/loghiazienda/$etichetta[ST_LOGOG]\" width=\"100%\" border=\"0\">\n";
    //echo "<br>$sitointernet - $email1 - Tel. $telefono Fax $fax - $prov $nazione<br><BR><BR>\n";
    echo "</td>\n";
    echo "</tr>";
    echo "<tr>";
    echo "<td >&nbsp;</td>";
    echo "<td colspan=2><font face=arial><font size=6>";
    echo "Spettabile <br>";
    echo "</td></tr>";
    echo "<td >&nbsp;</td>";
    echo "<td colspan=2><font face=arial><font size=6><b>";
    echo $_ragsoc;
    echo "</b></td></tr>";
    echo "<tr><td >&nbsp;</td>";
    echo "<td colspan=2 align=left><font face=arial><font size=6>&nbsp;";
    echo $_ragsoc2;
    echo "</td></tr>";
    echo "<tr><td >&nbsp;</td>";
    echo "<td colspan=2 align=left><font face=arial><font size=6>";
    echo $_indirizzo;
    echo "</td></tr>";
    echo "<tr><td >&nbsp;</td>";
    echo "<td align=left><font face=arial><font size=6>";
    echo $_cap;
    echo "</td>";
    echo "<td align=right><font face=arial><font size=6><b>";
    echo $_citta;
    echo "</b></td></tr>";
    echo "<tr><td >&nbsp;</td>";
    echo "<td colspan=2 align=right><font face=arial><font size=6>";
    echo $_prov;
    echo "</td></tr>";
    echo "</table>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>