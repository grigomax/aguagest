<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
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

// Inizio tabella pagina principale ----------------------------------------------------------

echo "<table width=\"100%\" align=\"left\"><tr><td>";

//inizio pagina alternativa
// prendo le variabili passate
//	e verifico se sono piene



$_user = $_SESSION['user']['user'];
$_azione = $_GET['azione'];



if ($_azione == "pwd")
{
    $_user = $_POST['user'];
    $_vecchia = $_POST['vecchia'];
    $_nuova = $_POST['nuova'];
    $_conferma = $_POST['conferma'];

    $dati = tabella_utenti("singolo", $_id, $_user, $_password, $_blocca, $_parametri);

    if (($_vecchia OR $_nuova OR $_conferma) == "")
    {
        echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Errore n. 1</b><br></font></span></td>\n";
        echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Uno dei campi immesso risulta vuoto</b><br></font></span></td>\n";
        echo "<tr><td colspan=2 align=\"center\"><font size=2>Tornare indietro e verificare<br></font></span></td>\n";
        exit;
    }
    elseif ($_vecchia != $dati['pwd'])
    {
        #   echo $_vecchia;
        #   echo $_nuova;
        echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Errore n. 2</b><br></font></span></td>\n";
        echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Vecchia password non corrispondente</b><br></font></span></td>\n";
        echo "<tr><td colspan=2 align=\"center\"><font size=2>Tornare indietro e verificare<br></font></span></td>\n";
        exit;
    }
    elseif ($_nuova != $_conferma)
    {
        echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Errore n. 3</b><br></font></span></td>\n";
        echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Nuove password non corrispondenti</b><br></font></span></td>\n";
        echo "<tr><td colspan=2 align=\"center\"><font size=2>Tornare indietro e verificare<br></font></span></td>\n";
        exit;
    }
    else
    {

        echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Utente $_user</b><br></font></span></td></tr>\n";

        //inizio aggiornamento
        if ($_POST['azione'] == "aggiorna")
        {
            // prendo le variabili passate
            //cerco la riga se c'� l'aggiorno,

            $_aggiorna = tabella_utenti("modifica", $_id, $_user, $_nuova, $_blocca, $_parametri);

            if ($_aggiorna == "OK")
            {
                echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Aggiornamento eseguito</b><br></font></span></td></tr>\n";
            }
            else
            {
                echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Errore Aggiornamento</b><br></font></span></td></tr>\n";
            }


            return;
        }// parentesi fine funzione inserimento
    }
}
else
{
    echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Utente $_user</b><br></font></span></td></tr>\n";
    
    $_POST['USER_SCREEN_FONT_SIZE'] = $_POST['USER_SCREEN_FONT_SIZE'] / 10;
    $_aggiorna = tabella_utenti("update", $_id, $_user, $_password, $_blocca, $_POST);

    if ($_aggiorna == "OK")
    {
        echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Aggiornamento eseguito</b><br></font></span></td></tr>\n";
        
        $_SESSION['user']['USER_FONT_SIZE'] = $_POST['USER_FONT_SIZE'];
        $_SESSION['user']['USER_SCREEN_COLOR_BACKGROUND'] = $_POST['USER_SCREEN_COLOR_BACKGROUND'];
        $_SESSION['user']['USER_SCREEN_WIDTH'] = $_POST['USER_SCREEN_WIDTH'];
        $_SESSION['user']['USER_SCREEN_FONT_TYPE'] = $_POST['USER_SCREEN_FONT_TYPE'];
        
    }
    else
    {
        echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Errore Aggiornamento</b><br></font></span></td></tr>\n";
    }
    
}
?>