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
require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_primanota.php";




if ($_SESSION['user']['contabilita'] > "1")
{


//Prendiamoci i GET ed i POST
    $_azione = $_POST['azione'];
    if ($_POST['azione'] == "Stampa")
    {
        //carichiamo la base delle pagine:
        base_html_stampa("chiudi", $_parametri);

//carichiamo la testata del programma.
#testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

        $_anno = $_GET['anno'];
        $_nreg = $_GET['nreg'];


        echo "<center>\n";
    }
    else
    {
        //carichiamo la base delle pagine:
        base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
        testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
        menu_tendina($_cosa, $_percorso);
        $_causale = $_GET['causale'];
        $_anno = $_GET['anno'];
        $_nreg = $_POST['nreg'];


        echo "<center>\n";
    }


    $dati = tabella_primanota("leggi_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//qui di seguito elenchiamo la schermata della calce della prima nota..


    echo "<h2>Visualizzazione Registrazione</h2>\n";

    echo "<table width=\"80%\" border=\"0\">\n";
    if ((($dati['status'] == "chiuso") OR ( $dati['status'] == "Inserito")) AND ( $dati['liquid_iva'] == "SI") AND ( ($dati['segno'] == "P") OR ( $dati['segno'] == "C")))
    {
        echo "<form action=\"calce_nota.php\" method=\"POST\">\n";
    }
    else
    {
        echo "<form action=\"corpo_nota.php\" method=\"POST\">\n";
    }

    echo "<td colspan=\"2\">N. Registrazione <br><b><input type=\"radio\" name=\"nreg\" value=\"$dati[nreg]\" checked>$dati[nreg] / <input type=\"radio\" name=\"anno\" value=\"$dati[anno]\" checked> $dati[anno]</b></td><td colspan=\"2\">Data registrazione <b><br>$dati[data_reg]</b></td><td colspan=\"1\">Data Contabile <br><b>$dati[data_cont]</b></td></tr>\n";

    echo "<tr><td colspan=\"6\"><br>Descrizione Movimento   <b>$dati[descrizione]</b></td></tr>\n";
    echo "<tr><td colspan=\"2\"><br>Causale Movimento   <b>$dati[causale]</b></td>\n";
    echo "<td colspan=\"1\"><br>Status   <b>$dati[status]</b></td>\n";
    echo "<td colspan=\"1\"><br>Liq. IVA   <b>$dati[liquid_iva]</b></td>\n";
    echo "<td colspan=\"2\"><br>Reg. Giornale   <b>$dati[giornale]</b></td></tr>\n";
    echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
    echo "</table>\n";
// passiamo al tipo di schermata..
//In questo caso standard

    schermate_visualizza_reg($_causale, $_anno, $_nreg, $dati);


    echo "<span><tr><td colspan=\"6\" align=\"LEFT\"><b>NOTE SULLA REGISTRAZIONE:</b><br>$dati[note]<br></td></tr></span>\n";


    if ($_azione != "Stampa")
    {
        if (($dati['status'] == "Inserito") AND ( $dati['liquid_iva'] != "SI"))
        {
            echo "<tr><td colspan=\"6\" align=\"right\"><br><input type=\"submit\" value=\"Elimina\" name=\"azione\" onclick=\"if(!confirm('Procedere Alla Eliminazione ? ')) return false;\" >Oppure <input type=\"submit\" value=\"Modifica\" name=\"azione\">
            <br></form><form action=\"calce_nota.php?nreg=$dati[nreg]&anno=$dati[anno]\" method=\"POST\"><input type=\"submit\" value=\"Salda\" name=\"azione\"></td></tr>\n";
            echo "</form>\n";

            echo "<form action=\"visualizza_reg.php?anno=$_anno&nreg=$_nreg\" method=\"POST\" target=\"_blanck\">\n";
            echo "<tr><td colspan=\"6\" align=\"right\"><br><input type=\"submit\" value=\"Stampa\" name=\"azione\"></td></tr>\n";
            echo "</form>\n";
        }
        elseif ((($dati['status'] == "chiuso") OR ( $dati['status'] == "Inserito")) AND ( $dati['liquid_iva'] == "SI") AND ( ($dati['segno'] == "P") OR ( $dati['segno'] == "C")))
        {
            if ($SPESOMETRO == "SI")
            {
                echo "<tr><td colspan=\"6\" align=\"right\"><br>Inserisci Spesometro <br><input type=\"submit\" value=\"Spesometro\" name=\"azione\"><br></td></tr>\n";
            }

            echo "<tr><td colspan=\"6\" align=\"right\"><br>Unica azione Possibilie <br><input type=\"submit\" value=\"Salda\" name=\"azione\"><br></td></tr>\n";
            echo "</form>\n";
            echo "<form action=\"visualizza_reg.php?anno=$_anno&nreg=$_nreg\" method=\"POST\" target=\"_blanck\">\n";
            echo "<tr><td colspan=\"6\" align=\"right\"><br><input type=\"submit\" value=\"Stampa\" name=\"azione\"></td></tr>\n";
            echo "</form>\n";
        }
        else
        {
            echo "</form>\n";

            echo "<form action=\"visualizza_reg.php?anno=$_anno&nreg=$_nreg\" method=\"POST\" target=\"_blanck\">\n";
            echo "<tr><td colspan=\"6\" align=\"right\"><br><input type=\"submit\" value=\"Stampa\" name=\"azione\"></td></tr>\n";
            echo "</form>\n";
            echo "<tr><td colspan=\"6\" align=\"right\">Impossibile modificare la registrazione in quanto gi&agrave; nel Libro Giornale<br>
            Oppure &egrave; stata liquidata</td></tr>\n";
            echo "<form action=\"visualizza_reg.php?anno=$_anno&nreg=$_nreg\" method=\"POST\" target=\"_blanck\">\n";
            echo "<tr><td colspan=\"6\" align=\"right\"><br><input type=\"submit\" value=\"Stampa\" name=\"azione\"></td></tr>\n";
            echo "</form>\n";
        }

        echo "<br>";
        echo "<form action=\"corpo_nota.php\" method=\"POST\">\n";
        echo "<tr><td colspan=\"6\" align=\"center\"><input type=\"submit\" value=\"Annulla\" name=\"azione\" onclick=\"if(!confirm('Sicuro di voler Annullare la operazione ?')) return false;\" ></form>\n";
    }
    echo "</table>\n";

    echo "</body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>