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


if ($_SESSION['user']['anagrafiche'] > "2")
{

// Inizio tabella pagina principale ----------------------------------------------------------


    printf("<br><br><form action=\"imballi.php\" method=\"POST\">\n");
    echo "<table border=\"0\" align=\"center\">\n";

    echo "<tr><td colspan=2 align=\"center\"><h2 align=\"center\">Modifica Imballo</h2></span></td>\n";

    echo "<tr><td colspan=2 align=\"center\"><font size=3>Se non trovi le modifiche aggiorna la pagina con ALT+F5</font></td>\n";


//inizio pagina alternativa

    if ($_POST['azione'] == "Inserisci")
    {
        // prendo le variabili passate

        $_codice = $_POST['codice'];

        //cerco la riga se c'e l'aggiorno, se non c'e la inserisco
        $query = ("SELECT * FROM imballi WHERE imballo='$_codice'");
        //esegue la query
        $result = domanda_db("query", $query, $_ritorno, $_parametri);
//	echo $query;

        if ($result->rowCount() > 0)
        {
            echo "<td>Imballo $_articolo Gi&agrave; presente nell'archivio</td>";
        }
        else
        {
            $query = ("insert into imballi ( imballo ) VALUES ( '$_codice')");
            //esegue la query
            $result = domanda_db("exec", $query, $_ritorno, $_parametri);
            echo "<td>Imballo $_articolo inserito correttamente</td>";
        }

        return;
    }// parentesi fine funzione inserimento
//inizio aggiornamento
    if ($_POST['azione'] == "Modifica")
    {
        // prendo le variabili passate
        //cerco la riga se c' l'aggiorno, se non c' la inserisco
        $query = sprintf("UPDATE imballi SET imballo = \"%s\" where id=\"%s\"", $_POST['codice'], $_POST['id']);
        
        
        $result = domanda_db("exec", $query, $_ritorno, "verbose");
        
        echo $result;
        if($result != "NO")
        {
           echo "<tr><td>Imballo $_articolo Modificato correttamente</td></td>";
        }
        else
        {
            echo "<tr><td>Nessuna modifica effettuata</td></td>";
        }
        

        
        
        return;
    }// parentesi fine funzione inserimento
//inizio eliminazione
    if ($_POST['azione'] == "Elimina")
    {
        // prendo le variabili passate
        //cerco la riga se c' l'aggiorno, se non c' la inserisco
        $query = "DELETE FROM imballi where id=".$conn->quote($_POST['id']);
        
        $result = domanda_db("exec", $query, $_ritorno, "verbose");
                
        if($result != "NO")
        {
           echo "<tr><td>Imballo $_articolo Eliminato correttamente</td></td>"; 
        }
        else
        {
            echo "<tr><td>Nessuna riga eliminata</td></td>"; 
        }
        


        return;
    }// parentesi fine funzione inserimento


    echo "<tr><td align=\"center\"><span class=\"testo_blu\"><b>Inserisci l'imballo da modificare:</b><br></span></td>\n";

    echo "<tr><td colspan=2 align=center><br>";
    echo "<select name=\"codice\">\n";
    echo "<option value=\"\"></option>";

    // Stringa contenente la query di ricerca...
    $query = sprintf("select * from imballi order by imballo ");

    $result = domanda_db("query", $query, $_ritorno, $_parametri);

    // Tutto procede a meraviglia...
    echo "<span class=\"testo_blu\">";
    foreach ($result AS $dati)
    {
        printf("<option value=\"%s\">%s</option>\n", $dati['id'], $dati['imballo']);
    }

    echo "</select>\n";
    echo "</td></tr>\n";

    // PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "<td colspan=2 align=center><input type=\"submit\" name=\"azione\" value=\"Modifica !\"></td></tr><tr><td colspan=2 align=center>Oppure inserisci un codice nuovo<br><input type=\"submit\" name=\"azione\" value=\"Inserisci\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</table>";
// ************************************************************************************** -->
    echo "</td></tr></table>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>