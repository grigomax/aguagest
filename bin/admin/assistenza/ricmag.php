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



if ($_SESSION['user']['setting'] > "3")
{
    echo "<td width=\"85%\" align=\"center\" valign=\"top\">
	<span class=\"intestazione\"><b>Gestione Database</b></span><br></td></tr>";

    echo "<tr><td align=center><span class=\"testo_blu\"><h2>Ricostruzione muovimenti di magazzino</h2></td></tr>";


    if ($_GET['anno'] == "")
    {
        $_anno = date('Y');
        echo "<tr><td align=\"center\"> Seleziona l'anno che vuoi ripristinare..</td></tr>\n";
        echo "<br><br><form action=\"ricmag.php\" method=\"GET\">\n";

        echo "<tr><td align=center><br>";
        echo "<select name=\"anno\">\n";
        printf("<option value=\"%s\">Anno = %s</option>\n", $_anno, $_anno);
        printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 1, $_anno - 1);
        printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 2, $_anno - 2);
        printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 3, $_anno - 3);
        printf("<option value=\"%s\">Anno = %s</option>\n", $_anno - 4, $_anno - 4);
        echo "</select>\n";
        echo "</td></tr>\n";
        
        echo "<tr><td><input type=\"submit\" value=\"Ricalcola\"> </td></tr>\n";
        echo "</form>\n";
        exit;
    }


    $_anno = $_GET['anno'];


    echo "<tr><td><h3>Elaborazione anno= $_anno</h3></td></tr>\n";


    // inizio la muovimentazione del magazzino per la bolla
    //prendiamo la data più vecchia del magazzino..
    $query = "SELECT * FROM magazzino where anno = '$_anno'";
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


    if ($result->rowCount() > 0)
    {
        //vuol dire che l'anno è presente nel database magazzino altrimenti passiamo allo storico
        $magazzino = "magazzino";
        echo "<tr><td><h3>Elaborazione magazzino in corso</h3></td></tr>\n";
    }
    else
    {
        $magazzino = "magastorico";
        echo "<tr><td><h3>Elaborazione magazzino storico</h3></td></tr>\n";
    }



    $_tdoc = "ddt";
    $_data_us = sprintf("%s-%s-%s", $_annodoc, $_mesedoc, $_daydoc);

    // Prima cancello il magazzino poi lo reinserisco tutti i d.d.t.

    $query = sprintf("delete from $magazzino where tdoc=\"%s\" and anno=\"%s\" ", $_tdoc, $_anno);

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
    else
    {
        echo "<br>Eliminazione ddt - dati dal magazzino effettuata\n";
    }

    // dopo averlo cancellato lo reinserisco
    $query = "SELECT bv_dettaglio.anno, bv_dettaglio.ndoc, bv_dettaglio.utente, bv_dettaglio.articolo, bv_dettaglio.quantita, bv_dettaglio.totriga, bv_bolle.datareg FROM bv_dettaglio INNER JOIN bv_bolle ON bv_dettaglio.anno=bv_bolle.anno AND bv_dettaglio.ndoc = bv_bolle.ndoc WHERE bv_dettaglio.anno='$_anno' AND bv_dettaglio.articolo != 'vuoto' ORDER by bv_dettaglio.anno, bv_dettaglio.ndoc, bv_dettaglio.rigo";
    $result = $conn->query($query);

    //echo $query;
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    foreach ($result AS $dati2)
    {//4
        $_esma = tabella_articoli("esma", $dati2['articolo'], $_parametri);
        //se esma = esenzione magazzino � uguale a si escudo la muovimentazione
        if($_esma == "NO")
        { // parentesi d'escusione
            //qui scarico imposto l'utente a cliente per l'inserimento nel database
            $_utfor = "c";
            $query = sprintf("insert into $magazzino( tdoc, anno, ndoc, datareg, tut, utente, articolo, qtascarico, valorevend ) values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\" )", $_tdoc, $dati2['anno'], $dati2['ndoc'], $dati2['datareg'], $_utfor, $dati2['utente'], $dati2['articolo'], $dati2['quantita'], $dati2['totriga']);

            //echo "<br>$query";
            $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }
        }// fine esclusione magazzino
    }
    echo "<br>Inserimento ddt eseguita \n";
    
    
// inizio gesione fattute immediate
    // inizio la muovimentazione del magazzino per la bolla
    $_tdoc = $nomedoc;

    // Prima cancello il magazzino poi lo reinserisco tutti i d.d.t.

    $query = sprintf("delete from $magazzino where tdoc=\"%s\" and anno=\"%s\" ", $_tdoc, $_anno);

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
    else
    {
        echo "<br>Eliminazione $nomedoc eseguita\n";
    }
    

    $query = "SELECT fv_dettaglio.anno, fv_dettaglio.ndoc, fv_dettaglio.utente, fv_dettaglio.articolo, fv_dettaglio.quantita, fv_dettaglio.totriga, fv_testacalce.datareg FROM fv_dettaglio INNER JOIN fv_testacalce ON fv_dettaglio.anno=fv_testacalce.anno AND fv_dettaglio.ndoc = fv_testacalce.ndoc WHERE fv_dettaglio.tdoc='$_tdoc' AND fv_dettaglio.anno='$_anno' AND fv_dettaglio.articolo != 'vuoto' ORDER by fv_dettaglio.anno, fv_dettaglio.ndoc, fv_dettaglio.rigo";
    //$query = "SELECT * FROM fv_dettaglio INNER JOIN fv_testacalce ON fv_dettaglio.ndoc = fv_testacalce.ndoc WHERE fv_dettaglio.tdoc='$_tdoc' AND fv_dettaglio.anno='$_anno' AND fv_dettaglio.articolo != 'vuoto' ORDER by fv_dettaglio.anno, fv_dettaglio.ndoc, fv_dettaglio.rigo";
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

    foreach ($result AS $dati2)
    {//4
        $_esma = tabella_articoli("esma", $dati2['articolo'], $_parametri);
        //se esma = esenzione magazzino � uguale a si escudo la muovimentazione
        if ($_esma == "NO")
        { // parentesi d'escusione
            //qui scarico imposto l'utente a cliente per l'inserimento nel database
            $_utfor = "c";
            $query = sprintf("insert into $magazzino( tdoc, anno, ndoc, datareg, tut, utente, articolo, qtascarico, valorevend ) values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\" )", $_tdoc, $dati2['anno'], $dati2['ndoc'], $dati2['datareg'], $_utfor, $dati2['utente'], $dati2['articolo'], $dati2['quantita'], $dati2['totriga']);

            //echo "<br>$query";
            $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }
        }// fine esclusione magazzino
    }// 4

    echo "<br>Inserimento $nomedoc eseguita\n";
    
    // inizio la muovimentazione del magazzino per le note credito
    $_tdoc = "NOTA CREDITO";

    // Prima cancello il magazzino poi lo reinserisco tutti i d.d.t.

    $query = sprintf("delete from $magazzino where tdoc=\"%s\" and anno=\"%s\" ", $_tdoc, $_anno);

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
    else
    {
        echo "<br>Eliminazione $_tdoc eseguita\n";
    }

    // dopo averlo cancellato lo reinserisco
    $query = "SELECT fv_dettaglio.anno, fv_dettaglio.ndoc, fv_dettaglio.utente, fv_dettaglio.articolo, fv_dettaglio.quantita, fv_dettaglio.totriga, fv_testacalce.datareg FROM fv_dettaglio INNER JOIN fv_testacalce ON fv_dettaglio.anno=fv_testacalce.anno AND fv_dettaglio.ndoc = fv_testacalce.ndoc WHERE fv_dettaglio.tdoc='$_tdoc' AND fv_dettaglio.anno='$_anno' AND fv_dettaglio.articolo != 'vuoto' ORDER by fv_dettaglio.anno, fv_dettaglio.ndoc, fv_dettaglio.rigo";
    //echo $query;   
    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        //echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    foreach ($result AS $dati2)
    {//4
        $_esma = tabella_articoli("esma", $dati2['articolo'], $_parametri);
        //se esma = esenzione magazzino � uguale a si escudo la muovimentazione
        if ($_esma == "NO")
        { // parentesi d'escusione
            //qui scarico imposto l'utente a cliente per l'inserimento nel database
            $_utfor = "c";
            $query = sprintf("insert into $magazzino( tdoc, anno, ndoc, datareg, tut, utente, articolo, qtascarico, valorevend ) values( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\" )", $_tdoc, $dati2['anno'], $dati2['ndoc'], $dati2['datareg'], $_utfor, $dati2['utente'], $dati2['articolo'], -$dati2['quantita'], -$dati2['totriga']);

            $conn->query($query);

            //echo "<br>$query";
            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }
        }// fine esclusione magazzino
    }// 4

    echo "<br>Inserimento $_tdoc eseguita \n";

    echo "<br> Se non appaiono errori a video la ricostruzione dei muovimenti è andata a buon fine<br>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>