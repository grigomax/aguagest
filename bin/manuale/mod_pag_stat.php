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
base_html("", $_percorso);

java_script($_cosa, $_percorso);

tiny_mce($_cosa, $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_GET['capitolo'] == "nuovo")
{
    $dati = null;
    
    $_azione = "Inserisci";
    $_body = "Inserisci il testo che vuoi pubblicare";
    $_titolo = "Titolo che apparirà nel menu";
}
else
{
    $_azione = "Modifica";

    $file = $_GET['file'];

    $_capitolo = substr($file, "1", "2");
    $_posizione = substr($file, "3", "2");
    if (!$p_file = fopen("contenuti/$file", "r"))
    {
        echo "Spiacente, non posso aprire il file $file";
        exit;
    }
    $_titolo = (fgets($p_file, 255));
}



if ($_SESSION['user']['stampe'] > "3")
{

    echo "<table align=\"center\" width=\"100%\" border=\"0\">\n";
    echo "<tr><td>\n";

    echo "<h1>$_azione Pagina </h1>\n";

    echo "<form method=\"POST\" action=\"result_pag_stat.php\">\n";

    echo "<table border=\"0\" align=\"center\" width=\"80%\">\n";
    echo "<tr><td align=\"left\">\n";

    echo "Capitolo Pagina <input type=\"radio\" value=\"$file\" name=\"file\" checked> <input type=\"text\" name=\"capitolo\" size=\"3\" maxlength=\"2\" value=\"$_capitolo\" autofocus ><br>\n";
    echo "Posizione Pagina <input type=\"text\" name=\"posizione\" size=\"3\" maxlength=\"2\" value=\"$_posizione\"> <br>\n";



    echo "</td></tr>\n";
    echo "<tr><td align=\"left\">\n";
    echo "<b>TITOLO PAGINA  -> </b><input type=\"text\" name=\"title\" size=\"80\" value=\"$_titolo\"><br>&nbsp;</td></tr>\n";
    
    echo "<tr><td>\n";
    echo "<textarea name=\"content\" style=\"width:300px; height:500px;\">\n";
    
    
    if ($_azione == "modifica")
    {
        $_body;
    }
    else
    {
        $_contenuto = stripslashes(file_get_contents("contenuti/$file", "", null,"100"));
        //$_contenuto = file_get_contents("contenuti/$file","",null, "80");
        echo $_contenuto;
    }


    echo "</textarea>\n";
    echo "</td></tr>\n";
    echo "<tr><td align=\"center\">\n";
    echo "<input type=\"submit\" name=\"azione\" value=\"$_azione\">\n";
    if (($_azione == "Modifica") AND ( ($dati[capitolo] != "privacy") AND ( $dati[capitolo] != "condizioni")))
    {
        echo "<input type=\"submit\" name=\"azione\" value=\"Elimina\">\n";
    }
    echo "</td></tr>\n";
    echo "</form>\n";
    echo "</table>\n";

    echo "</td></tr></table>\n";
}
else
{
    permessi_sessione("scaduta", $_percorso);
}
?>