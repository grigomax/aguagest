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



if ($_SESSION['user']['magazzino'] > "2")
{


    if ($_POST['aggp2'] == "Annulla")
    {

        include "../../index.php";

        exit;
    }


    if ($_POST['aggp2'] == "Aggiorna")
    {


        // prendo le variabili passate

        $_articolo = $_GET['codice'];
        $_giacin = $_POST['giacin'];
        $_valin = $_POST['valin'];

        //inizio a modificare i dati del magazzino
        //setto il tipo utente come magazzino
        $_tut = "giain";
        $_anno = $_GET['anno'];
        $_datareg = $_anno . '-01-01';

        //cerco la riga se c'�l'aggiorno, se non c'�la inserisco
        $query = ("SELECT * FROM magazzino WHERE anno='$_anno' and tut='$_tut' and articolo='$_articolo'") or die("Errore!");
        //esegue la query
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
//	echo $query;
        
        if ($result->rowCount() > 0)
        {
            $query = ("update magazzino set qtacarico='$_giacin', valoreacq='$_valin' where anno='$_anno' and tut='$_tut' and articolo='$_articolo'");
            //esegue la query
            
            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }
            else
            {
                $_result = "Articolo $_articolo aggiornato correttamente";
            }
            
            
        }
        else
        {
            $query = ("insert into magazzino( anno, datareg, tut, articolo, qtacarico, valoreacq) values ( '$_anno', '$_datareg', '$_tut', '$_articolo', '$_giacin', '$_valin')");
            //esegue la query

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }
            else
            {
                $_result = "Articolo $_articolo inserito correttamente";
            }

        }
    }// parentesi fine funzione inserimento
//Inizio pagina visiva
// Inizio tabella pagina principale ----------------------------------------------------------

    echo "<table width=\"80%\" border=\"0\"><tr><td valign=\"TOP\" align=\"left\"width=\"100%\">";
    
    echo "<h3 align=\"center\">$_result</h3>\n";
    
    printf("<form action=\"p2.php\" method=\"POST\">\n");
    echo "<table width=\"100%\" border=\"0\">\n";
    $_anno = date('Y');
    echo "<tr><td colspan=2 align=\"center\"><font size=\"3\"> <span class=\"testo_blu\"><b>Modifica giacenza articoli</b><br></font></span></td>\n";
    echo "<tr><td colspan=2 align=\"center\"><span class=\"testo_blu\"><b>Anno di riferimento <input type=\"number\" name=\"anno\" value=\"$_anno\" size=\"10\"></b><br></span></td></tr>\n";
    echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\"><b>Inserisci il codice preciso da modificare:</b></span></td>\n";
    echo "<td class=\"colonna\" width=\"200\" align=\"align=\"left\"><input type=\"text\" autofocus name=\"codice\" size=\"40\" maxlength=\"40\"></td></tr>\n";


// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "<tr><td>&nbsp;</td><td align=left><input type=\"submit\" value=\"Modifica !\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";

   
    echo "</table>";
// ************************************************************************************** -->
    echo "</td></tr></table>\n";
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>