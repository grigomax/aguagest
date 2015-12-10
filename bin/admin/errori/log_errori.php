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
base_html_stampa("chiudi", $_parametri);

$_parametri['intestazione'] = "2";
intestazione_html($_cosa, $_percorso, $_parametri);
//carichiamo la testata del programma.
//testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
//menu_tendina($_cosa, $_percorso);

$_file = $_GET['files'];

if ($_SESSION['user']['setting'] > "3")
{

    echo "<table width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"left\" width=\"80%\" valign=\"top\">\n";
    echo "<br><br><br> Visualizzazione log... $_file<br><br>";

    echo "<form action=\"log_errori.php?files=$_file\" method=\"post\">\n";
    echo "<input type=\"submit\" name='Svuota' value=' Svuota '>\n";
    echo "</form>\n";

    $_nome_file = $_percorso . "../spool/$_file";
    $_contenuto = file_get_contents($_nome_file);
    
    

    if (file_exists($_nome_file))
    {
        if (isset($_POST['Svuota']))
        {
            if (!unlink($_nome_file))
            {
                echo "Impossibile eliminare il file\n";
            }
            else
            {
                echo "Il file $nome_file è stato cancellato";
            }
        }
        else
        {
            //echo "<font size=\"1\">\n";
            //echo stripslashes($_contenuto);
            echo $_contenuto;
            //include $_nome_file;
            //echo "\n";
        }
    }

    echo "</td></tr></table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>