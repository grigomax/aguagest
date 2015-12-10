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
$conn = permessi_sessione("verifica", $_percorso);

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
    $_annonuovo = $_anno + 1;
    $_catmer = $_POST['catmer'];

    echo "Eseguito. <br><br>";

    echo "Inizio svuotamento archivio di magazzino attuale.....";
// Ora ho finito di copiare tutto
// procedo allo svuotamento del magazzino attuale


    echo "Inizio riporto giacenze finale in iniziale nell' archivio di magazzino attuale.....";

    // ora per poter iniziare a riportare le rimanenze finali per l'anno nuovo devo prendere tutta l'anagrafica articoli.. quindi

    $query4 = "SELECT articolo, catmer FROM articoli where catmer='$_catmer' ORDER BY articolo";

    // Esegue la query...
    if ($res4 = mysql_query($query4, $conn))
    {//1
        // La query ?stata eseguita con successo...
        // MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
        mysql_num_rows($res4);

        // prendo tutti i dati fin che li trova
        while ($dati4 = mysql_fetch_array($res4))
        {//3
            // elimino in ogni caso l'anno prima di inserirlo
            $query3 = "DELETE from magazzino where tut='giain' and articolo='$dati4[articolo]'";

            // Esegue la query...
            if (mysql_query($query3, $conn) != 1)
            {
                echo "impossibile proseguire errore numero 3";
                exit;
            }


            // ora che ho tutti gli articoli ordinati procedo a prendermeli dal magastorico

            $query5 = sprintf("SELECT (SUM(qtacarico) - SUM(qtascarico)) AS qtafinale, (SUM(valoreacq) / SUM(qtacarico)) * (SUM(qtacarico) - SUM(qtascarico)) AS valorefin FROM `magastorico` where articolo=\"%s\" AND anno=\"%s\"", $dati4['articolo'], $_anno);
            //echo $query5;
            //eseguo la query
            if ($res5 = mysql_query($query5, $conn))
            {//1
                mysql_num_rows($res5);

                if ($res5 >= 1)
                {//2
                    $dati5 = mysql_fetch_array($res5);
                    // ora procedo ad inserirli nel magazzino nuovo
                    $_tut = "giain";
                    $_data = date("Y-m-d");
                    $query6 = sprintf(" INSERT INTO magazzino (anno, datareg, tut, articolo, qtacarico, valoreacq ) values ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\")", $_annonuovo, $_data, $_tut, $dati4['articolo'], $dati5['qtafinale'], $dati5['valorefin']);
                    //echo $query6;
                    if (mysql_query($query6, $conn) != 1)
                    {
                        echo mysql_error();
                    }
                }//chiusuradomanda magazzino
            }
        }
    }
// fine parte lavorativa ora inizia quella visiva
    echo "Eseguito. <br><br>";

    echo "Se Non appaiono messaggi d'errore tutto il travaso e stato eseguito con successo";

}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
