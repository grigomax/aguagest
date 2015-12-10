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



//Cambio le variabili e le faccio vedere
$_listino = $_POST['listino'];
$_tipo = $_POST['tipo'];


//come prima cosa prendiamoci il tipo di listino dal database..

$listino = tabella_stampe_layout("singola", $_percorso, $_POST['tdoc']);




$title = "Listino Prezzi N. $_listino";

//settiamo i colori per lo sfondo pagina ecc..
//$_parametri['FONT_BACK'] = "80%";
//$_parametri['WIDTH'] = "750px";
$_parametri['MARGINI'] = "0";
$_parametri['PADDING'] = "0px";
$_parametri['BACK'] = "#ffffff";

base_html_stampa("chiudi", $_parametri);


if($_tipo == "catmer")
{
    $_catmer = $_POST['catmer'];
    
    //selezioniamo il nome dall'anagrafe..
    
    $_nomelist = tabella_catmer("singola_codice", $_catmer, $_parametri);
    
    $_parametri['tabella'] = $_nomelist['catmer'];
    
    $query = "SELECT substring(articolo, 1,$listino[ST_ARTICOLO_CT]) AS articolo, substring(descrizione,1,$listino[ST_DESCRIZIONE_CT]) AS descrizione, tipart, unita, listino from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where rigo='$_listino' AND catmer='$_catmer' ORDER BY articolo";
}


if($_tipo == "per_codice")
{
    if ($_POST['ccodini'] != 0)
{
    $_codini = $_POST['ccodini'];
    $_codfin = $_POST['ccodfin'];
}
else
{
    $_codini = $_POST['codini'];
    $_codfin = $_POST['codfin'];
}

$_listino = $_POST['listino'];
$_sconto = $_POST['sconto'];


$_parametri['tabella'] = "Listino Prezzi N. $_listino";


$query = sprintf("select articolo, substring(descrizione,1,50) AS descrizione, substring(desrid,1,30) AS desrid, unita, listino from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where rigo=\"%s\" and articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_listino, $_codini, $_codfin);


}


$result = $conn->query($query);

if ($conn->errorCode() != "00000")
{
    $_errore = $conn->errorInfo();
    echo $_errore['2'];
    //aggiungiamo la gestione scitta dell'errore..
    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
    $_errori['files'] = "stampe listini.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
}

//Listini articolo..
// questa selezione mi permette di avere il numero di pagine ed il numero di
//righe in anticipo
//cerco il numero di righe
$righe = $result->rowCount();
//echo $righe;
//inserisco il numero di righe per pagina
$rpp = $listino['ST_RPP'];

$_pagine = $righe / $rpp;
//arrotondo per eccesso
$pagina = ceil($_pagine);


for ($_pg = 1; $_pg <= $pagina; $_pg++)
{

    //proviamo a caricare la funzione intestazione..
    //echo $listino['']
    $_parametri['intesta_immagine'] = $listino['ST_LOGOG'];

    $_parametri['intestazione'] = $listino['ST_TLOGO'];
    

    intestazione_html($_cosa, $_percorso, $_parametri);

    echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" width=\"750px\">\n";
    echo "<tr>\n";

    if ($listino['ST_ARTICOLO'] == "SI")
    {
        echo "<td valign=\"top\" width=\"$listino[ST_ARTICOLO_LC]%\" align=\"center\"><b>Codice</b></td>\n";
    }

    if ($listino['ST_DESCRIZIONE'] == "SI")
    {
        echo "<td valign=\"top\" width=\"$listino[ST_DESCRIZIONE_LC]%\" align=\"center\"><b>Descrizione</b></td>\n";
    }

    if ($listino['ST_UNITA'] == "SI")
    {
        echo "<td valign=\"top\" width=\"$listino[ST_UNITA_LC]%\" align=\"center\"><b>U.m.</b></td>\n";
    }

    if ($listino['ST_LISTINO'] == "SI")
    {
        echo "<td valign=\"top\" width=\"$listino[ST_LISTINO_LC]%\" align=\"center\"><b>Listino</b></td>\n";
    }

    echo "</tr>\n";

// ciclo di estrazione dei dati
    for ($_nr = 1; $_nr <= $rpp; $_nr++)
    {
        $dati3 = $result->fetch(PDO::FETCH_ASSOC);
        echo "<tr>\n";
        $_listino = $dati3['listino'];
// eliminazione della scritta vuoto dalla stampa
        if ($_listino == "0.00" or null)
        {
            $_listino = "a richiesta";
        }

        if ($listino['ST_ARTICOLO'] == "SI")
        {
            echo "<td valign=\"top\" width=\"$listino[ST_ARTICOLO_LC]%\" align=\"$listino[ST_ARTICOLO_ALL]\"><font face=\"$listino[ST_FONTCORPO]\" style=\"font-size: $listino[ST_FONTCORPOSIZE]" . "pt;\">$dati3[articolo]&nbsp;</td>\n";
        }


        if ($listino['ST_DESCRIZIONE'] == "SI")
        {
            echo "<td valign=\"top\" width=\"$listino[ST_DESCRIZIONE_LC]%\" align=\"$listino[ST_DESCRIZIONE_ALL]\"><font face=\"$listino[ST_FONTCORPO]\" style=\"font-size: $listino[ST_FONTCORPOSIZE]" . "pt;\">$dati3[descrizione]</td>\n";
        }

        if ($listino['ST_UNITA'] == "SI")
        {
            echo "<td valign=\"top\" width=\"$listino[ST_UNITA_LC]%\" align=\"$listino[ST_UNITA_ALL]\"><font face=\"$listino[ST_FONTCORPO]\" style=\"font-size: $listino[ST_FONTCORPOSIZE]" . "pt;\">$dati3[unita]</td>\n";
        }

        if ($listino['ST_LISTINO'] == "SI")
        {
            echo "<td valign=\"top\" width=\"$listino[ST_LISTINO_LC]%\" align=\"$listino[ST_LISTINO_ALL]\"><font face=\"$listino[ST_FONTCORPO]\" style=\"font-size: $listino[ST_FONTCORPOSIZE]" . "pt;\">$_listino</td>\n";
        }

        echo "</tr>\n";
    }

    echo "<table border=\"1\" align=\"center\" width=\"750px\">\n";
    echo "<tr>\n";
    echo "<td width=\"20%\" align=\"left\"><i>Pagina </i>$_pg di $pagina</td>\n";
    echo "<td width=\"60%\" align=\"center\"> Data " . date("d / m / Y") . "  -  Validità listino : " . date("Y") . "</td>\n";
    echo "<td width=\"20%\" align=\"right\"><i>Pagina </i>$_pg di $pagina</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "<br>\n";
}
?>
</body>
</html>
