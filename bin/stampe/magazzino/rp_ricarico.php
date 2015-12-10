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


//  mi prendo i post della pagina precedente..

$_anno = $_POST['anno'];
$_mese = $_POST['mese'];
$_tipo = $_POST['tipo'];


// preparo la data ovvero datareg
// anno odierno
$_annoinc = date('Y');

if ($_annoinc == $_anno)
{
    $_magazzino = "magazzino";
}
else
{
    $_magazzino = "magastorico";
}
// echo $_anno;
// echo $_magazzino;
$_mesec = "$_mese";
$_datareg = "$_anno-$_mesec-%";


// estraggo i dati dal magazzino
// seleziono il tipo di stampa..
if ($_tipo == "catmer")
{
    $query = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto, SUM(qtascarico) AS scarico, SUM(qtacarico) AS carico, SUM(valoreacq) AS acquistato FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE tut != 'giain' and anno='$_anno' GROUP BY catmer", $_magazzino, $_magazzino);
}
if ($_tipo == "tipart")
{
    $query = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto, SUM(qtascarico) AS scarico, SUM(qtacarico) AS carico, SUM(valoreacq) AS acquistato FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE tut != 'giain' and anno='$_anno' GROUP BY tipart", $_magazzino, $_magazzino);
}

//echo $query;
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

//        foreach ($result AS $dati)
//cerco il numero di righe
$righe = $result->rowCount();
$rpp = 40;
$rpc = 96;

$_pagine = $righe / $rpc;
//arrotondo per eccesso
$pagina = ceil($_pagine);

if ($_tipo == "tipart")
{
    //estraggo i dati dalla tabella catmer e li  metto su un array
    $tipart = tabella_tipart("elenca", $_codice, $_parametri);

//inserisco i dati in un array..
    foreach ($tipart AS $dati2)
    {
        $categoria[$dati2['codice']] = $dati2['tipoart'];
    }
}
else
{
    //estraggo i dati dalla tabella catmer e li  metto su un array
    $catmer = tabella_catmer("elenca", $_codice, $_parametri);

//inserisco i dati in un array..
    foreach ($catmer AS $dati2)
    {
        $categoria[$dati2['codice']] = $dati2['catmer'];
    }
}


//qui dovrei sommare le giacenze iniziali.. ed inserirle su un array

$_giacenze = tabella_magazzino("calcola_giacenze", $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_tipo, $_rigo, $_utente, $_codice, $_magazzino);



for ($_pg = 1; $_pg <= $pagina; $_pg++)
{

    $_parametri[data] = date('d-m-Y');
    $_parametri[stampa] = "Ricarichi";
    $_parametri[anno] = $_anno;
    $_parametri['pg'] = $_pg;
    $_parametri[pagina] = $pagina;
    $_parametri[tabella] = "Tabella Ricarichi per $_tipo";

    intestazione_html($_cosa, $_percorso, $_parametri);

    echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" width=\"90%\">\n";
    echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"300\" align=\"left\"><font face=\"arial\" size=\"2\"><b>Nome</b></td>\n";
    echo "<td bgcolor=\"#FFFFFF\" colspan=\"2\" valign=\"top\" width=\"100\" align=\"center\"><font face=\"arial\" size=\"2\"><b>Giacenza iniziale</b></td>\n";
    echo "<td bgcolor=\"#FFFFFF\" colspan=\"2\" valign=\"top\" width=\"100\" align=\"center\"><font face=\"arial\" size=\"2\"><b>Valore acquisto</b></td>\n";
    echo "<td bgcolor=\"#FFFFFF\" colspan=\"2\" valign=\"top\" width=\"100\" align=\"center\"><font face=\"arial\" size=\"2\"><b>Valore Venduto</b></td>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"50\" align=\"right\"><font face=\"arial\" size=\"2\"><b>Differenza</b></td>\n";
    echo "</tr>\n";

    echo "<tr><td align=\"left\" width=\"200\"><font face=\"arial\" size=\"2\">\n";
    echo "<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">Unita</td>\n";
    echo "<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">Valore</td>\n";
    echo "<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">Unita</td>\n";
    echo "<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">Valore</td>\n";
    echo "<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">Unita</td>\n";
    echo "<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">Valore</td>\n";
    echo "<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">Perc. %</td>\n";
    echo "</tr>\n";

// ciclo di estrazione dei dati
    for ($_nr = 1; $_nr <= $rpp; $_nr++)
    {
        $dati3 = $result->fetch(PDO::FETCH_ASSOC); // calcolo ricarico:

        $_acq = $dati3['acquistato'];
        //$_acq = $dati3['acquistato'];
        //echo $dati3['gruppo'];
        $_vend = $dati3['venduto'];

        @$_differenza = $_vend - $_acq;

        echo "<tr><td align=\"left\" width=\"200\"><font face=\"arial\" size=\"2\">\n";
        if ($categoria != "")
        {
            echo $categoria[$dati3['gruppo']];
        }
        else
        {
            echo $dati3['gruppo'];
        }
        echo "&nbsp;</td>\n";
        //facciamo i conti della riga..
        //pms giacenza 
        @$_pms_gia = $_giacenze['valore'][$dati3['gruppo']] / $_giacenze['quantita'][$dati3['gruppo']];
        //acquisti
        @$_pms_acq = $dati3['acquistato'] / $dati3['carico'];
        //vendite
        @$_pms_vend = $dati3['venduto'] / $dati3['scarico'];

        @$_pms = ($_pms_acq + $_pms_gia) / 2;

        @$_ricarico = number_format(((($_pms_vend / $_pms) * 100) - 100), 2, '.', '');
        if($_ricarico == "-100")
        {
            $_ricarico = "";
        }
        printf("<td align=\"right\"><font face=\"arial\" size=\"2\">%s</td>", $_giacenze['quantita'][$dati3['gruppo']]);
        printf("<td align=\"right\"><font face=\"arial\" size=\"2\">%s</td>", $_giacenze['valore'][$dati3['gruppo']]);
        printf("<td align=\"right\"><font face=\"arial\" size=\"2\">%s</td>", $dati3['carico']);
        printf("<td align=\"right\"><font face=\"arial\" size=\"2\">%s</td>", $dati3['acquistato']);
        printf("<td align=\"right\"><font face=\"arial\" size=\"2\">%s</td>", $dati3['scarico']);
        printf("<td align=\"right\"><font face=\"arial\" size=\"2\">%s</td>", $dati3['venduto']);
        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">%s</td>", $_ricarico);
        printf("</tr>");

        $_acquisto = $_acquisto + $_acq;
        $_vendita = $_vendita + $_vend;
        $_netto = $_netto + $_differenza;
        $_giacenza = $_giacenza + $_giacenze['valore'][$dati3['gruppo']];
        $_totricarico = $_totricarico + $_ricarico;
        $diviso++;
    }

    echo "</table>\n";
}

echo "<table border=\"1\" align=\"center\" width=\"90%\">\n";
echo "<tr>\n";

@$_guadagno = number_format(($_totricarico / $diviso), 2, '.', '');
//@$_ricarico = number_format(((($_vendita - $_acquisto) * 100) / $_acquisto), 2, '.', '');

echo "<td align=\"center\">Valori Netti in euro <br>Giacenza iniziale == $_giacenza <br> Acquisto == $_acquisto <br>Venduto == $_vendita  <br>  Netto in tasca == $_netto  <br>  Ricarico == $_guadagno%</td>\n";
echo "</tr></table></body></html>\n";
