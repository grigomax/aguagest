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



if ($_SESSION['user']['stampe'] > "1")
{

    
    function query($_cosa, $query)
    {
        global $conn;
        global $_percorso;
        
        
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
        
        if($_cosa == "righe")
        {
            $return = $result->rowCount();
        }
        elseif($_cosa == "elenco")
        {
            $return = $result;
        }
        else
        {
            //passiamo l'elenco in un arrae..
            foreach ($result AS $dati)
            {
                $return[$dati['gruppo']] = $dati['venduto'];
            }
            
            
        }

        return $return;
    }






//  mi prendo i post della pagina precedente..

    $_anno = $_POST['anno'];
    $_tipo = $_POST['tipo'];

    if ($_mese == 14)
    {
//cambiamo il tutto lo apriamo
    }

// verifico l'anno passato per vedere cosa che archivio prendere

    $query = "SELECT anno FROM magazzino WHERE tut = 'giain' ORDER BY anno LIMIT 1";

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

        foreach ($result AS $datianno);


    if ($_anno < $datianno['anno'])
    {
        $_magazzino = "magastorico";
    }
    else
    {
        $_magazzino = "magazzino";
    }

    $_datareg1 = "$_anno-01-%";
    $_datareg2 = "$_anno-02-%";
    $_datareg3 = "$_anno-03-%";
    $_datareg4 = "$_anno-04-%";
    $_datareg5 = "$_anno-05-%";
    $_datareg6 = "$_anno-06-%";
    $_datareg7 = "$_anno-07-%";
    $_datareg8 = "$_anno-08-%";
    $_datareg9 = "$_anno-09-%";
    $_datareg10 = "$_anno-10-%";
    $_datareg11 = "$_anno-11-%";
    $_datareg12 = "$_anno-12-%";


// estraggo i dati dal magazzino
// seleziono il tipo di stampa..
    if ($_tipo == "catmer")
    {
        $query0 = "SELECT catmer AS descrizione, codice AS categoria FROM catmer ORDER BY codice";
        $query1 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg1);
        $query2 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg2);
        $query3 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg3);
        $query4 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg4);
        $query5 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg5);
        $query6 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg6);
        $query7 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg7);
        $query8 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg8);
        $query9 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg9);
        $query10 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg10);
        $query11 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg11);
        $query12 = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg12);
    }

    if ($_tipo == "tipart")
    {
        $query0 = "SELECT tipoart AS descrizione, codice AS categoria FROM tipart ORDER BY tipoart";
        $query1 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg1);
        $query2 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg2);
        $query3 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg3);
        $query4 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg4);
        $query5 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg5);
        $query6 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg6);
        $query7 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg7);
        $query8 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg8);
        $query9 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg9);
        $query10 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg10);
        $query11 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg11);
        $query12 = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg12);
    }

    if ($_tipo == "cliente")
    {
        $query0 = "SELECT ragsoc AS descrizione, codice AS categoria FROM clienti ORDER BY ragsoc";
        $query1 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg1);
        $query2 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg2);
        $query3 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg3);
        $query4 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg4);
        $query5 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg5);
        $query6 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg6);
        $query7 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg7);
        $query8 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg8);
        $query9 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg9);
        $query10 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg10);
        $query11 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg11);
        $query12 = sprintf("SELECT codice AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg12);
    }

    if ($_tipo == "fornitore")
    {
        $query0 = "SELECT ragsoc AS descrizione, codice AS categoria FROM fornitori ORDER BY ragsoc";
        $query1 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg1);
        $query2 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg2);
        $query3 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg3);
        $query4 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg4);
        $query5 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg5);
        $query6 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg6);
        $query7 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg7);
        $query8 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg8);
        $query9 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg9);
        $query10 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg10);
        $query11 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg11);
        $query12 = sprintf("SELECT codice AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY utente", $_magazzino, $_magazzino, $_datareg12);
    }

    if ($_tipo == "acquisti")
    {
        $query0 = "SELECT catmer AS descrizione, codice AS categoria FROM catmer ORDER BY codice";
        $query1 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg1);
        $query2 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg2);
        $query3 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg3);
        $query4 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg4);
        $query5 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg5);
        $query6 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg6);
        $query7 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg7);
        $query8 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg8);
        $query9 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg9);
        $query10 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg10);
        $query11 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg11);
        $query12 = sprintf("SELECT catmer AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg12);
    }

//Listini articolo..
// questa selezione mi permette di avere il numero di pagine ed il numero di
//righe in anticipo
// echo $query;
    
    $righe = query("righe", $query0);
    
    $res0 = query("elenco", $query0);
    $gen = query("", $query1);
    $feb = query("", $query2);
    $mar = query("", $query3);
    $apr = query("", $query4);
    $mag = query("", $query5);
    $giu = query("", $query6);
    $lug = query("", $query7);
    $ago = query("", $query8);
    $set = query("", $query9);
    $ott = query("", $query10);
    $nov = query("", $query11);
    $dic = query("", $query12);


//inserisco il numero di righe per pagina
    $rpp = 32;

    $_pagine = $righe / $rpp;
//arrotondo per eccesso
    $pagina = ceil($_pagine);


    for ($_pg = 1; $_pg <= $pagina; $_pg++)
    {
        ?>
        <html>
            <head>
                <title>Venduto di Magazzino</title>
            </head>
            <body>
                <table border="1" align="center" width="90%">
                    <tr>
                        <td width="50%" bgcolor="#FFFFFF" valign="top" align="left">
                            <? echo $azienda; ?> <br>
                            <? echo $sitointernet; ?> <br>
                            Telefono : <? echo $telefono; ?> Fax : <? echo $fax; ?> <br>
                            e-mail : <? echo $email1; ?>
                        </td>
                        <td width="50%" bgcolor="#FFFFFF" valign="top" align="right">
                            Data <? echo date("d / m / Y"); ?>
                            <br>
                            <b>Anno di riferimento = <?php
                                echo $_POST['anno'];
                                echo $_POST['mese'];
                                ?></b>
                            <br><b>Tipo = <?php echo $_POST['tipo']; ?></b>
                        </td>
                    </tr>

                </table>
                <br>
                <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
                    <tr>
                        <td align="left" width="49%">
                            <table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">
                                <tr>
                                    <td bgcolor="#FFFFFF" valign="top" width="100" align="left"><font face="arial" size="1"><b>Categoria</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Gennaio</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Febbraio</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Marzo</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Aprile</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Maggio</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Giugno</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Luglio</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Agosto</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Settembre</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Ottobre</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Novembre</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="40" align="right"><font face="arial" size="1"><b>Dicembre</b></font></td>
                                    <td bgcolor="#FFFFFF" valign="top" width="50" align="right"><font face="arial" size="1"><b>Totale</b></font></td>


                                </tr>
                                <?php
                                //precarico i dati prima di esporli
                                
// ciclo di estrazione dei dati
                                for ($_nr = 1; $_nr <= $rpp; $_nr++)
                                {

                                    $dati0 = $res0->fetch(PDO::FETCH_ASSOC);

                                    printf("<tr><td align=\"left\" ><font face=\"arial\" size=\"1\"><b>%s&nbsp;</b></td>", $dati0['descrizione']);

                                    if ($gen[$dati0['categoria']] != "")
                                    {
                                        //    printf( "<td align=\"left\" width=\"150\"><font face=\"arial\" size=\"1\">%s&nbsp;</td>", $dati1['gruppo'] );
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $gen[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $gen[$dati0['categoria']];
                                        $_gennaio = $_gennaio + $gen[$dati0['categoria']];
                                        //$dati1 = mysql_fetch_array($res1);
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($feb[$dati0['categoria']] != "")
                                    {
                                        //    printf( "<td align=\"left\" width=\"150\"><font face=\"arial\" size=\"1\">%s&nbsp;</td>", $dati2['gruppo'] );
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $feb[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $feb[$dati0['categoria']];
                                        $_febbraio = $_febbraio + $feb[$dati0['categoria']];
                                        
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($mar[$dati0['categoria']] != "")
                                    {

                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $mar[$dati0['categoria']]);
                                        $_marzo = $_marzo + $mar[$dati0['categoria']];
                                        $_totalecat = $_totalecat + $mar[$dati0['categoria']];
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($apr[$dati0['categoria']] != "")
                                    {
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $apr[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $apr[$dati0['categoria']];
                                        $_aprile = $_aprile + $apr[$dati0['categoria']];
                                        
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($mag[$dati0['categoria']] != "")
                                    {
                                        //    printf( "<td align=\"left\" width=\"150\"><font face=\"arial\" size=\"1\">%s&nbsp;</td>", $dati3['gruppo'] );
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $mag[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $mag[$dati0['categoria']];
                                        $_maggio = $_maggio + $mag[$dati0['categoria']];
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($giu[$dati0['categoria']] != "")
                                    {
                                        //    printf( "<td align=\"left\" width=\"150\"><font face=\"arial\" size=\"1\">%s&nbsp;</td>", $dati3['gruppo'] );
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $giu[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $giu[$dati0['categoria']];
                                        $_giugno = $_giugno + $giu[$dati0['categoria']];
                                        
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($lug[$dati0['categoria']] != "")
                                    {
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $lug[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $lug[$dati0['categoria']];
                                        $_luglio = $_luglio + $lug[$dati0['categoria']];
                                        
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($ago[$dati0['categoria']] != "")
                                    {
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $ago[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $ago[$dati0['categoria']];
                                        $_agosto = $_agosto + $ago[$dati0['categoria']];
                                        
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($set[$dati0['categoria']] != "")
                                    {
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $set[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $set[$dati0['categoria']];
                                        $_settembre = $_settembre + $set[$dati0['categoria']];
                                        
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($ott[$dati0['categoria']] != "")
                                    {
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $ott[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $ott[$dati0['categoria']];
                                        $_ottobre = $_ottobre + $ott[$dati0['categoria']];
                                        
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($nov[$dati0['categoria']] != "")
                                    {
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $nov[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $nov[$dati0['categoria']];
                                        $_novembre = $_novembre + $nov[$dati0['categoria']];
                                        
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }

                                    if ($dic[$dati0['categoria']] != "")
                                    {
                                        printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"1\">%s</td>", $dic[$dati0['categoria']]);
                                        $_totalecat = $_totalecat + $dic[$dati0['categoria']];
                                        $_dicembre = $_dicembre + $dic[$dati0['categoria']];
                                        
                                    }
                                    else
                                    {
                                        echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">&nbsp;</td>";
                                    }


                                    //ULTIMA COLONNA
                                    echo "<td align=\"right\" ><font face=\"arial\" size=\"1\">$_totalecat</td>";
                                    //prima di andare a capo sommiamo il tutto
                                    $_totale = $_totale + $_totalecat;

                                    $_totalecat = "0";
                                    printf("</tr>");
                                }

                                echo "<tr>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"100\" align=\"left\"><font face=\"arial\" size=\"1\"><b>Totali</b></font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_gennaio</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_febbraio</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_marzo</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_aprile</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_maggio</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_giugno</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_luglio</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_agosto</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_settembre</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_ottobre</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_novembre</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"40\" align=\"right\"><font face=\"arial\" size=\"1\">$_dicembre</font></td>";
                                echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"50\" align=\"right\"><font face=\"arial\" size=\"1\"><b>$_totale</b></font></td>";
                                ?>
                    </tr>
                </table>

            </td>

        </table>
        </td>
        </tr>
        </table>

        <?php
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
</body>
</html>
