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



if ($_SESSION['user']['setting'] > "3")
{

    echo "<center><br><br><br> Inizio procedura<br><br>";

//recupero le variabili
    $_anno = $_POST['anno'];
    $_azione = $_POST['azione'];
    $_annonuovo = $_anno + 1;

//ora procedo alla verifica che tutti di documenti importanti siano evasi..
// come le bolle e fatture siano evasi..
    if ($CONTABILITA == "SI")
    {
        $query = "(select status, anno, ndoc, utente from bv_bolle where status != 'evaso' and anno='$_anno' order by ndoc) UNION (select status, anno, ndoc, utente from fv_testacalce where status != 'saldato' AND anno='$_anno' order by ndoc)";
    }
    else
    {
        $query = "(select status, anno, ndoc, utente from bv_bolle where status != 'evaso' and anno='$_anno') UNION (select status, anno, ndoc, utente from fv_testacalce where status != 'evaso' AND anno='$_anno')";
    }

    $result = domanda_db("query", $query, $_cosa, $_ritorno, "verbose");


    if ($result != "NO")
    {
        echo " Impossibile proseguire perch&egrave; risultano muovimentati i seguenti muovimenti. e/o non saldati in contabilità";
        echo " <table border=1><tr>";
        echo " <td>status</td><td>anno</td><td>n. doc.</td><td>utente</td></tr>";
        $numero = 0;
        foreach ($result AS $dati)
        {

            if($numero == "0")
            {
                echo "<tr><td colspan=\"4\">Questi sono ddt</td></tr>\n";
            }
            
            if($dati[ndoc] < $numero)
            {
                echo "<tr><td colspan=\"4\">Questi sono fatture</td></tr>\n";
            }
            echo "<tr><td>$dati[status]</td><td>$dati[anno]</td><td>$dati[ndoc]</td><td>$dati[utente]</td></tr>\n";
            $numero = $dati['ndoc'];
        }
        echo "</table>";
    }
    else
    {

        if ($_azione == "Aggiusta")
        {

// il programma gi aggiustamento non fa altro che copiare i muovimenti relativi agli anni sbagliati nel magazzino storico..
// si il muovimento c'è gia lo aggiorno altrimenti lo inserisco..

            echo "lettura muovimenti errati.... <br>\n";
            echo "Progress...30%<br>\n";
            $query = sprintf("SELECT * FROM magazzino WHERE anno <= \"%s\" ORDER BY datareg DESC", $_anno);

            $result = domanda_db("query", $query, $_cosa, $_ritorno, $_parametri);

            foreach ($result AS $dati)
            {

                echo "Rimozione muovimenti vecchi inseriti per aggiornamento.. \n";

// elimino in ogni caso l'anno prima di inserirlo
                $query = "DELETE FROM magastorico WHERE anno='$dati[anno]' AND datareg='$dati[datareg] AND articolo='$dati[articolo]' AND tut='$dati[tut]' LIMIT 1 ";

// Esegue la query...
                domanda_db("exec", $query, $_cosa, $_ritorno, "vebose");

                echo "Eseguito <br>";


                echo "Inizio Copie da Magazzino attuare a Storico movimenti errati.. <br>";
                echo "Progress...<br>\n";

                $query = "INSERT INTO magastorico (`tdoc` ,`anno` , suffix, `ndoc` ,`datareg` ,`tut` ,`rigo` ,`utente` ,`articolo` ,`qtacarico` ,`valoreacq` ,`qtascarico` ,`valorevend` ,`ddtfornitore` ,`fatturacq` ,`protoiva` ,`ts` )
            VALUES ('$dati[tdoc]', '$dati[anno]', '$dati[suffix]', '$dati[ndoc]', '$dati[datareg]', '$dati[tut]', '$dati[rigo]', '$dati[utente]', '$dati[articolo]', '$dati[qtacarico]', '$dati[valoreacq]', '$dati[qtascarico]', '$dati[valorevend]', '$dati[ddtfornitore]', '$dati[fatturacq]', '$dati[protoiva]', CURRENT_TIMESTAMP )";
// Esegue la query...

                domanda_db("exec", $query, $_cosa, $_ritorno, "block");

                echo "Eseguito... <br>";


                echo "Eliminazione dal magazzino attuale... <br>\n";

                // elimino in ogni caso l'anno prima di inserirlo
                $query = "DELETE FROM magazzino WHERE anno='$dati[anno]' AND datareg='$dati[datareg]' AND articolo='$dati[articolo]' AND tut='$dati[tut]' limit 1";

                domanda_db("exec", $query, $_cosa, $_ritorno, "block");

                echo "Eseguito... <br>";
            }

            echo "Progress.... 100% <br>\n";
        }
        else
        {

            // ora devo copiare tutto il magazzino attuale e copiarlo in quello storico
            // mi conviene ordinarlo per data
            // prima di tutto mi conviene eliminare eventuali tracce di record presenti per l'anno corrente prima
            // di mettere le righe nuove
            echo "Rimozione eventuale prova precedente.....";

            // elimino in ogni caso l'anno prima di inserirlo
            $query = sprintf("DELETE FROM magastorico WHERE anno=\"%s\"", $_anno);

            domanda_db("exec", $query, $_cosa, $_ritorno, "verbose");

            echo "Eseguito... <br>";

            echo "Inizio Copie da Magazzino attuare a Storico........";

            // se tutto ok procedo prendo tutto

            $query1 = sprintf("SELECT * FROM magazzino WHERE anno=\"%s\" ORDER BY datareg", $_anno);

            $result1 = domanda_db("query", $query1, $_cosa, $_ritorno, "verbose");

            foreach ($result1 AS $dati1)
            {
                $query2 = "INSERT INTO magastorico (tdoc, anno, suffix, ndoc, datareg, tut, rigo, utente, articolo, qtacarico, valoreacq, qtascarico, valorevend, ddtfornitore, fatturacq, protoiva) values ( '$dati1[tdoc]', '$dati1[anno]', '$dati1[suffix]', '$dati1[ndoc]', '$dati1[datareg]', '$dati1[tut]', '$dati1[rigo]', '$dati1[utente]', '$dati1[articolo]', '$dati1[qtacarico]', '$dati1[valoreacq]', '$dati1[qtascarico]', '$dati1[valorevend]', '$dati1[ddtfornitore]', '$dati1[fatturacq]', '$dati1[protoiva]' )";

                domanda_db("exec", $query2, $_cosa, $_ritorno, "block");
            }//3


            echo "Eseguito. <br><br>";
            echo "Inizio svuotamento archivio di magazzino attuale.....";
            // Ora ho finito di copiare tutto
            // procedo allo svuotamento del magazzino attuale
            // elimino in ogni caso l'anno prima di inserirlo
            $query = "DELETE FROM magazzino WHERE anno='$_anno'";

            domanda_db("exec", $query, $_cosa, $_ritorno, "block");
            echo "Eseguito... <br>";


            echo "Inizio riporto giacenze finale in iniziale nell' archivio di magazzino attuale.....";

            // ora per poter iniziare a riportare le rimanenze finali per l'anno nuovo devo prendere tutta l'anagrafica articoli.. quindi

            $query = "SELECT articolo FROM articoli ORDER BY articolo";

            $result = domanda_db("query", $query, $_cosa, $_ritorno, "verbose");
            
            //vediamo quante righe sono..
            
            $righe = $result->rowCount();
            
            //dividiamole per 4 con con il ceil..
            
            $quarti = ceil($righe/4);

            $riga = 0;
            foreach ($result AS $dati4)
            {//3
                $riga++;
                // ora che ho tutti gli articoli ordinati procedo a prendermeli dal magastorico
                $query5 = sprintf("SELECT (SUM(qtacarico) - SUM(qtascarico)) AS qtafinale, (SUM(valoreacq) / SUM(qtacarico)) * (SUM(qtacarico) - SUM(qtascarico)) AS valorefin FROM `magastorico` where articolo=\"%s\" AND anno=\"%s\"", $dati4['articolo'], $_anno);
                //echo $query5;

                $result5 = domanda_db("query", $query5, $_cosa, $_ritorno, "");
                
                if ($result5->rowCount() >= 1)
                {//2
                    $dati5 = domanda_db("query", $query5, $_cosa, "solo_fetch", $result5);
                    // ora procedo ad inserirli nel magazzino nuovo
                    $_tut = "giain";
                    $_mezzo = "-01.01";
                    $_data = $_annonuovo . $_mezzo;
                    $query6 = "INSERT INTO magazzino (anno, datareg, tut, articolo, qtacarico, valoreacq ) values ( '$_annonuovo', '$_data', '$_tut', '$dati4[articolo]', '$dati5[qtafinale]', '$dati5[valorefin]')";
                    //echo $query6;
                    domanda_db("exec", $query6, $_cosa, $_ritorno, "block");
                }//chiusuradomanda magazzino
                // fine parte lavorativa ora inizia quella visiva
                if($riga == "1")
                {
                    echo "Progress.. = 0%\n";
                }
                
                if($riga == $quarti)
                {
                    echo "----25%\n";
                }
                
                if($riga == $quarti * 2)
                {
                    echo "----50%\n";
                }
                
                if($riga == $quarti * 3)
                {
                    echo "----75%\n";
                }
                
                if($riga == $righe)
                {
                    echo "----100% effettuato\n";
                }
                
            }// fine protezione documenti
            echo "Se Non appaiono messaggi d'errore tutto il travaso &egrave; stato eseguito con successo";
        }
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>