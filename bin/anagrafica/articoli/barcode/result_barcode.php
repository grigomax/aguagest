<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "3")
{
    echo "<table width=\"100%\" align=\"center\">";


//prendiamoci i post..

    $_azione = $_POST['azione'];



    echo "<tr><td width=\"85%\" align=\"center\" valign=\"top\">\n";

    if ($_azione == "Inserisci")
    {
        echo "<h2>Inserimento codice a barre..</h2>\n";
        echo "<h3>Controllo presenza..</h3>\n";

        $result = tabella_barcode("check", $_POST['codbar'], $_articolo, $_rigo);

        if ($result['presenza'] == "NO")
        {
            //via libera il codice è vuoto procediamo con l'inserimento
            //inseriamo l'articolo..

            $numero = tabella_barcode("ultimo", $_codbar, $_POST['articolo'], $_rigo);


            $numero = $numero + 1;

            $result = tabella_barcode("Inserisci", $_POST['codbar'], $_POST['articolo'], $numero);

            if ($result['errori'] == "SI")
            {
                echo "<h3>Errore Inserimento codice $result[descrizione]</h3>\n";
            }
            else
            {
                echo "<h3>Codice $_POST[articolo] inserito correttamente.. al rigo $numero</h3>\n";
            }
        }
        else
        {
            //articolo già presente..
            echo "<h3>Codice a Barre già associato ad un articolo</h3>\n";

            echo "<h3>Cod. barre = $result[codbar] è già associato all'articolo $result[articolo]</h3>\n";

            echo "<h3>Tornare indietro e verificare..</h3>\n";

            exit;
        }
    }

    if ($_azione == "Modifica")
    {

        echo "<h2>Aggiorna codice</h2>\n";

        echo "<form action=\"result_barcode.php\" method=\"POST\">\n";

        echo "<h4> <input type=\"radio\" name=\"rigo\" value=\"$_POST[rigo]\" checked>Rigo $_POST[rigo] - <input type=\"radio\" name=\"articolo\" value=\"$_POST[articolo]\" checked>Articolo $_POST[articolo] </h4>\n";
        echo "<br>\n";
        echo "<h4>Aggiorna il codice a barre relativo all'articolo </h4>\n";
        echo "<input type=\"text\" name=\"codbar\" size=\"33\" maxlength=\"30\" value=\"$_POST[codbar]\">\n";

        echo "<br><br>\n";

        echo "<input type=\"submit\" name=\"azione\" value=\"Aggiorna\">\n";
    }

    if ($_azione == "Aggiorna")
    {

        echo "<h2>Aggiorna codice $_POST[articolo]</h2>\n";

        $result = tabella_barcode("Aggiorna", $_POST['codbar'], $_POST['articolo'], $_POST['rigo']);



        if ($result == "NO")
        {
            echo "<h3>Errore Inserimento codice</h3>\n";
        }
        else
        {
            echo "<h3>Codice $_POST[articolo] Aggiornato correttamente rigo $_POST[rigo]</h3>\n";
        }
    }

    if ($_azione == "Elimina")
    {
        echo "<h2>Elimina codice $_POST[articolo]</h2>\n";

        $result = tabella_barcode("Elimina", $_POST['codbar'], $_POST['articolo'], $_POST['rigo']);



        if ($result == "NO")
        {
            echo "<h3>Errore Eliminazione codice $_POST[articolo] rigo $_POST[rigo]</h3>\n";
        }
        else
        {
            echo "<h3>Codice $_POST[articolo] Aggiornato correttamente rigo $_POST[rigo]</h3>\n";
        }
    }


    echo "</td>	</tr></table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>