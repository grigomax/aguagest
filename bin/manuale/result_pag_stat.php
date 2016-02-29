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

if ($_SESSION['user']['stampe'] > "3")
{


    echo "<table align=\"center\" width=\"100%\" border=\"0\">\n";
    echo "<tr><td>\n";

    $_azione = $_POST['azione'];

    if ($_POST['capitolo'] == "")
    {
        echo "<h2 align=\"center\">Attenzione Il campo nome pagina risulta vuoto </h2>\n";
        echo "<h2 align=\"center\">Impossibile proseguire</h2>\n";
        exit;
    }

    if ($_POST['title'] == "")
    {
        echo "<h2 align=\"center\">Attenzione Il campo titolo risulta vuoto </h2>\n";
        echo "<h2 align=\"center\">Impossibile proseguire</h2>\n";
        exit;
    }

    $file = $_POST['file'];

    $_nomefile = "M" . $_POST['capitolo'] . $_POST['posizione'] . ".html";

    if ($_azione == "Elimina")
    {
        unlink("contenuti/$_nomefile");

        echo "<h2 align=\"center\">\n";
        echo "Pagina eliminata correttamente..<br><br>\n";
        
        echo "<a href=\"mod_elenco.php\">Clicca qui per ritornare all'elenco modifica.. </a>\n";
    }
    else
    {

        $_body = addslashes($_POST['content']);


        //$_title = addslashes($_POST['title']);

        $_title = str_pad(addslashes($_POST['title']), 100, ' ', STR_PAD_RIGHT);


        //qui vorrei creare un file contentente tutto..



        if (($_azione == "Modifica") AND ( $file != $_nomefile))
        {
            unlink("contenuti/$file");
        }

        $fp = fopen("contenuti/$_nomefile", "w+");
        if (!$fp)
            die("Errore nella aprire il file con il file");

        //scriviamo i dati..

        fwrite($fp, $_title . "\n");

        fwrite($fp, $_body);

        fclose($fp);


        echo "<h2 align=\"center\">\n";
        echo "Pagina aggiornata o inserita correttamente..<br><br>\n";
        
        echo "<a href=\"mod_elenco.php\">Clicca qui per ritornare all'elenco modifica.. </a>\n";
    }
    echo "</td></tr></table>\n";
}
else
{
    permessi_sessione("scaduta", $_percorso);
}
?>
