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
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{


    if ($_POST['anno'] == "")
    {
        // Stringa contenente la query di ricerca...
        $query = sprintf("select anno from magazzino GROUP BY anno order by anno ASC");
// Esegue la query...
        $dati = domanda_db("query", $query, "fetch", "verbose");
        
        $_anno_arc = $dati['anno'];
        $_anno = date('Y');
    }
    else
    {
        $_anno_arc = $_POST['anno'];
    }


    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo "<td colspan=\"8\" align=\"center\" >\n";
    echo "<span class=\"intestazione\"><b>Programma per la chiusura e la riapertura dell'esercizio di magazzino</b>\n";
    echo "<br>Si chiude sempre l'anno precedente gli esercizi si aprono in automatico</span><br></td></tr>\n";




    echo "<tr><td align=center colspan=8><font color=\"red\">\n";
    echo "<br> Attenzione:  il programma effettua una verifica sull'archivio delle bolle e delle fatture,<br>
    non verifica in alcun modo l'archivio dei carichi del magazzino.\n";
    echo "<br>L'operazione pu&ugrave; essere effettuata una volta sola, <b>ed &egrave; irreversibile.</b><br>\n";
    echo "<font size=\"3\"><b> Quindi SI CONSIGLIA DI FARE LE COPIE DEGLI ARCHIVI PRIMA DI PROSEGUIRE... GRAZIE \"Agua staff\"</b><br>\n";
    echo "L'operazione deve essere eseguita nell'anno nuovo, il programma evidenzia in automatico l'anno da chiudere</font></font></td></tr>\n";

    if ($_anno != $_anno_arc)
    {


//echo "<tr><td colspan=\"8\" align=\"center\"><form action=\"pingmagaz.php\" method=\"POST\">\n";
//echo "Cambia anno => <input type=\"number\" size=\"6\" maxlength=\"4\" name=\"anno\" value=\"$_anno_arc\"><input type=\"submit\" name=\"cambia\">\n";
//
//echo "</form></td></tr>\n";


        echo "<tr><td colspan=\"8\" align=\"center\"><br><h4>Anno chiusura esercizio = $_anno_arc</h4></td></tr>\n";

//Verifico se gli archivi dell'anno precedente sono pposto..
        if ($_anno_arc == ($_anno - 1))
        {
// Stringa contenente la query di ricerca... solo dei fornitori
            $query = sprintf("SELECT * FROM magazzino WHERE anno < \"%s\" ORDER BY datareg DESC", $_anno_arc);
        }
        else
        {
            $query = sprintf("SELECT * FROM magazzino WHERE anno <= \"%s\" ORDER BY datareg DESC", $_anno_arc);
        }
// Esegue la query...
        
        $result = domanda_db("query", $query, $_ritorno, $_parametri);

        if ($result->rowCount() > 0)
        {
            echo "<tr><td colspan=\"8\" align=\"center\"><h3>Impossibile proseguire in quanto ci sono delle discordanze tra le date presenti negli archivi </h3></td></tr>\n";
            //echo "<tr><td colspan=\"8\" align=\"center\"><h4>Cambiare anno di riferimento per sistemare gli archivi magazzino Prima di procedere</h4><br></td></tr>\n";
// inizio muovimenti uscita
            echo "<tr><td colspan=\"8\" align=\"center\"><span class=\"testo_blu\">Ultimi Muovimenti Vendita &nbsp;</span></td></tr>";

            echo "<tr><td>Anno</td><td align=\"left\">Data Reg.</td><td>Tut</td><td>T. Doc.</td><td> Numero Doc.</td><td>articolo</td><td>Scarico</td><td>Carico</td></tr> ";

            // Tutto procede a meraviglia...
            echo "<span class=\"testo_blu\">";
            foreach ($result AS $dati)
            {
                $_annov = $dati['anno'];
                echo "<tr><td>$dati[anno]</td><td align=\"left\">$dati[datareg]</td><td>$dati[tut]</td><td>$dati[tdoc]</td><td>$dati[ndoc]</td><td>$dati[articolo]</td><td>$dati[qtacarico]</td><td>$dati[qtascarico]</td></tr>\n";
            }

            echo "</tr>"; // chiusura tabelle interna
            // inizio parte di esecuzione..
            echo "<form action=\"pongmagaz.php\" method=\"POST\">";
            echo "<tr><td colspan=\"8\" align=center><br><input type=\"radio\" name=\"anno\" value=\"$_annov\" checked>Sistemazione anni precedenti prima di chiudere inventario<br>Sistemazione anno $_annov</td></tr>\n";

            echo "<tr><td colspan=\"8\" align=\"center\"><br><input type=\"submit\" name=\"azione\" value=\"Aggiusta\"></form></td></tr>\n";
        }
        else
        {
            // inizio parte di esecuzione..
            echo "<form action=\"pongmagaz.php\" method=\"POST\">";
            echo "<tr><td colspan=\"8\" align=center><br><input type=\"radio\" name=\"anno\" value=\"$_anno_arc\" checked>Chiusura Inventario anno $_anno_arc</td></tr>\n";

            echo "<tr><td colspan=\"8\" align=\"center\"><br><input type=\"submit\" name=\"azione\" value=\"Inizia !\"></form></td></tr>\n";
        }
    }
    echo "</table>\n";
    



    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>