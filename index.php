<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 */

echo "<!DOCTYPE html>\n";
echo "<html lang=\"it\">\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
echo "<LINK REL=\"shortcut icon\" HREF=\"favicon.ico\">\n";
echo "<title>Agua Gest</title>\n";

echo "<style>\n";
echo "input {
    font-size: 0.9em;
    }
\n";

echo "</style>\n";


echo "</head>\n";
echo "<BODY background=\"bin/images/aguaback.jpg\">\n";
echo "<center><img src=\"bin/images/aguagest.png\" alt=\"logo agua\"><font color=\"yellow\"><br>\n";


//verifichiamo in primis che esistano le classi PDO necessarie al funzionamento del programma

if (class_exists("PDO") == "true")
{
    if (isset($_GET['msg']))
    {
        if ($_GET['msg'] == "Error_0")
        {
            $_avviso = "Impossibile accedere alle cartelle interne..";
        }

        if ($_GET['msg'] == "Error_1")
        {
            $_avviso = $_avviso . "Nome utente o password Errati";
        }

        if ($_GET['msg'] == "Error_2")
        {
            $_avviso = $_avviso . "Nome utente campo vuoto";
        }

        if ($_GET['msg'] == "Error_3")
        {
            $_avviso = $_avviso . "Password campo vuoto";
        }

        if ($_GET['msg'] == "Error_4")
        {
            $_avviso = $_avviso . "L'utente risulta Bloccato";
        }
    }


    echo "<h1>Gestionale Aziendale</h1>";
    //verifico aggiornamento programma
    require_once "bin/include/version.inc.php";

    echo "Versione = " . $_PROGRAM_VERSION;
    echo "<h3>Per accedere al programma inserire</h3>";
    echo "<h3><font color=\"red\">$_avviso</font></h3>";
    echo "</font>";
    echo "<FORM action=\"bin/index.php\" method=\"POST\">";
    echo "<table style=\"margin-center: auto; margin-center: auto; width: 30%; text-align: center;\" border=\"0\" cellpadding=\"2\" cellspacing=\"2\" align=\"center\">";
    echo "<tbody>
        <tr>
            <td style=\"vertical-align: top;\"><font color=\"cyan\"><b>Nome utente</b><br></font>
             </td>
             <td style=\"vertical-align: top;\"><INPUT type=\"text\" autofocus name=\"user\" size=\"21\" maxlength=\"20\">
             </td>
        </tr>
        <tr>
            <td style=\"vertical-align: top;\"><font color=\"cyan\"><b>Password</b></font><br>
             </td>
             <td style=\"vertical-align: top;\"><INPUT type=\"password\" name=\"password\" size=\"21\" maxlength=\"20\">
             </td>
        </tr>
        <tr>
            <td style=\"vertical-align: top;\">&nbsp;
             </td>
             <td style=\"vertical-align: top;\"><INPUT type=\"submit\" name=\"entra\" value=\"Entra\">
             </td>
        </tr>
      </tbody>
    </table>
    </FORM>
    </center>\n";

    //vediamo se esiste una versione nuova del programma

    $versione = @file_get_contents("http://www.mcetechnik.it/reposity_agua/version.inc.php", null, null, "4", "4");
    //$versione = @file_get_contents("http://localhost/agua/bin/include/version.inc.php", null, null, "4", "4");

    if ($versione > $AGUAGEST)
    {
        echo "<center><h3><font color=\"RED\">E' stata rilasciata una nuova versione di agua gest</h3>";
        echo "<center>La nuova versione &egrave; la $versione</br>";
        echo "<center>Tutte le nuove caratteristiche sono visibili presso il sito ufficiale</br><br>\n";
        echo "<a href=\"http://www.mcetechnik.it/reposity_agua/version.txt\" target=\"_blank\">Clicca qui per andare direttamente</a>\n";
    }
}
else
{
    echo "<center>\n";
    echo "<h1>Impossibile Accedere al programma</h1>\n";
    echo "<h1>Libreria oggetti PDO non trovata</h1>\n";
    echo "<h2>Contattare l'amministratore</h2>\n";
}

echo "</font></center></body></html>\n";
?>