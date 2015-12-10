<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
base_html_stampa("chiudi", $_parametri);



if ($_SESSION['user']['stampe'] > "1")
{

    echo "<center><img align=\"center\" src=\"../images/aguagest.png\"></img></center>\n";

    echo "<h1 align=\"center\">Manuale Utente</h1>\n";

    echo "<table align=\"center\" border=\"0\">\n";

    echo "<tr><td>Capitolo</td><td>Sezione</td><td>Titolo</td></tr>\n";

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
                echo "<font color=\"blue\"><b>" . stripslashes($linea) . "</b></font></td></tr>\n";
            }
            else
            {
                echo "" . stripslashes($linea) . "</td></tr>\n";
            }


            fclose($p_file);
        }
    }

    echo "</table>\n";

    echo "<hr>\n";

    //rileggiamo tutto mettendo dei separè..


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

            echo "<p align=\"center\" style=\"background-color: Blue;\"><span style=\"color: #FFFFFF; font-size: 20px;\">\n";

            echo "CAPITOLO $_capitolo Sezione $_pagina\n";

            echo "</span></p>\n";

            if (!$p_file = fopen("contenuti/$file", "r"))
            {
                echo "Spiacente, non posso aprire il file $file";
                exit;
            }
            $linea = (fgets($p_file, 255));

            echo "<font color=\"blue\"><h2>" . stripslashes($linea) . "</h2></font>";

            

            //elenchiamo il contenuto
            $_contenuto = file_get_contents("contenuti/$file");

            echo stripslashes($_contenuto);

            //echo "<hr>\n";
        }
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>