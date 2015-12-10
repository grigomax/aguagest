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
require_once $_percorso . "librerie/motore_anagrafiche.php";

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

    echo "<table width=\"80%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr><td align=\"center\" valign=\"top\" colspan=\"2\">\n";
    echo "<span class=\"intestazione\"><b>Risultato duplica articoli</b><br></span><br></td></tr>\n";

    // prendo le variabili

    $_articolo = $_POST['articolo'];
    $_newcod = trim($_POST['newcod']);

    if ($_newcod != "")
    {
        //verifichiamo se l'articol esiste

        if (tabella_articoli("check", $_newcod, $_parametri) == true)
        {
            echo "<tr><td align center><H3>ATTEZIONE il codice immesso è gia esistente !</h3></td></tr>\n";
        }
        else
        {
            //azzeriamo i parametri..
            $_parametri = "";
            //prendiamo i dati articolo e lo associamo direttamente alla variabile parametri..
            $_parametri = tabella_articoli("singola", $_articolo, $_parametri);


            $res = tabella_articoli("Inserisci", $_newcod, $_parametri);

            // Esegue la query...
            if ($res['errori'] != "")
            {
                echo $res['errori'];
                exit;
            }
            else
            {
                echo "<tr><td align center><b> Articolo Inserito procediamo il listino</b></td></tr>\n";
                // se non ci sono errori inseriamo i prezzi nell listino.
                //funzione duplica..

                $res = tabella_listini("duplica", $_articolo, $_nlv, $_newcod);

                if ($res['errori'] != "")
                {
                    echo $res['errori'];
                }
                else
                {
                    echo "<tr><td align center><b>Listino articolo inserito </b></td></tr>\n";

                    //iseriamo anche il codice a barre

                    $result = tabella_barcode("Inserisci", $_newcod, $_newcod, '1');

                    if ($result['errori'] != "OK")
                    {
                        echo $result['descrizione'];
                    }
                    else
                    {
                        echo "<tr><td align center><b>Barcode articolo inserito </b></td></tr>\n";
                    }
                }

                echo "<tr><td align center><b> Articolo duplicato perfettamente </b></td></tr>\n";
                echo "<tr><td align center><br>Vuoi modificare subito questo articolo</td></tr>\n";
                echo "<tr><td align center><br><a href=\"modificacod.php?azione=Modifica&articolo=$_newcod\">Vai cliccando qui</a></td></tr>\n";
            }
        }
    }
    else
    {
        echo "<tr><td align center><br>ATTENZIONE NESSUN CODICE IMMESSO !!</td></tr>\n";
    }


    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>