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
ini_set('session.gc_maxlifetime', $SESSIONTIME);
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

    echo "<h1 align=\"center\">Gestione pagine manuale</h1>\n";


    $directory = "contenuti/";
    echo "<table width=\"80%\" align=\"center\" border=\"0\">\n";
    echo "<tr><td colspan=\"7\"><b><a href=\"mod_pag_stat.php?capitolo=nuovo&lingua=it\">Inserisci nuova pagina</a></b></td></tr>\n";
    echo "<tr>\n";

    echo "<td width=\"50\"><b>Capitolo</b></td><td width=\"50\"><b>Pagina</b></td><td><b>Titolo</b></td></tr>\n";

    // Apriamo una directory e leggiamone il contenuto.
    $array_file = glob('contenuti/*.html');
    {
        //$ordinato = sort($array_file);

        foreach ($array_file as $key => $file)
        {
            //togliamo la parte del file che non ci interessa

            $file = substr($file, 10);

            //echo $file . "<br/>";
            //dividiamo il file in base ai capitoli
            echo "<tr>\n";

            $_capitolo = substr($file, "1", "2");
            $_pagina = substr($file, "3", "2");

            if ($_capitolo2 == $_capitolo)
            {
                echo "<td width=\"50\">&nbsp;</td><td width=\"50\">$_pagina</td>\n";
            }
            else
            {
                echo "<td width=\"50\">$_capitolo</td><td width=\"50\">$_pagina</td>\n";
            }

            $_capitolo2 = $_capitolo;

            if (!$p_file = fopen("contenuti/$file", "r"))
            {
                echo "Spiacente, non posso aprire il file $file";
                exit;
            }
            $linea = (fgets($p_file, 255));

            echo "<td>\n";
            if ($_pagina == "00")
            {
                echo "<a href=\"mod_pag_stat.php?azione=modifica&file=$file\"><b>$linea</b></a></td></tr>\n";
            }
            else
            {
                echo "<a href=\"mod_pag_stat.php?azione=modifica&file=$file\">$linea</a></td></tr>\n";
            }


            fclose($p_file);
        }

    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>