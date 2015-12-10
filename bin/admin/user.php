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

// includiamo il files funzioni generali
    $_id = $_POST['id'];

    base_html("", $_percorso);
    java_script($_cosa, $_percorso);
    jquery_datapicker($_cosa, $_percorso);
    echo "</head>";

    echo "<body>";

    printf("<form action=\"mod-user.php\" method=\"POST\">\n");

    echo "<table width=\"70%\" border=\"0\" align=\"center\">\n";



    if ($_POST['azione'] == "Inserisci")
    {
        echo "<tr><td align=right>Nome :</td><td><input type=\"text\" name=\"user\" size=\"40\" maxleght=\"40\" required></td></tr>";
        echo "<tr><td align=right>Password :</td><td><input type=\"text\" name=\"pwd\" size=\"40\" maxleght=\"40\" required></td></tr>";
        echo "<tr><td align=right>RE - Password :</td><td><input type=\"text\" name=\"repwd\" size=\"40\" maxleght=\"40\" required></td></tr>";
        printf("<tr><td align=right>Data reg. : </td><td><input type=\"text\" name=\"datareg\" value=\"%s\" class=\"data\" size=\"11\" maxleght=\"10\"></td></tr>", date('d-m-Y'));
        echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
        echo "<tr><td align=\"center\" colspan=\"2\"> Permessi Da assegnare all'utente </td></tr>\n";
        echo "<tr><td>Anagrafiche</td><td>vietato<input type=\"radio\" name=\"anagrafiche\" value=\"1\"> - 
	solo lettura <input type=\"radio\" name=\"anagrafiche\" value=\"2\"> - 
	Modifica <input type=\"radio\" name=\"anagrafiche\" value=\"3\" checked> - 
	Tutto <input type=\"radio\" name=\"anagrafiche\" value=\"4\"></tr>\n";

        echo "<tr><td>Rep. Vendite</td><td>vietato<input type=\"radio\" name=\"vendite\" value=\"1\"> - 
	solo lettura <input type=\"radio\" name=\"vendite\" value=\"2\"> - 
	Modifica <input type=\"radio\" name=\"vendite\" value=\"3\" checked> - 
	Tutto <input type=\"radio\" name=\"vendite\" value=\"4\"></tr>\n";

        echo "<tr><td>Magazzino</td><td>vietato<input type=\"radio\" name=\"magazzino\" value=\"1\"> - 
	solo lettura <input type=\"radio\" name=\"magazzino\" value=\"2\"> - 
	Modifica <input type=\"radio\" name=\"magazzino\" value=\"3\" checked> - 
	Tutto <input type=\"radio\" name=\"magazzino\" value=\"4\"></tr>\n";

        echo "<tr><td>Rep. Stampe</td><td>vietato<input type=\"radio\" name=\"stampe\" value=\"1\"> - 
	solo lettura <input type=\"radio\" name=\"stampe\" value=\"2\"> - 
	Modifica <input type=\"radio\" name=\"stampe\" value=\"3\" checked> - 
	Tutto <input type=\"radio\" name=\"stampe\" value=\"4\"></tr>\n";


        echo "<tr><td>Contabilità</td><td>vietato<input type=\"radio\" name=\"contabilita\" value=\"1\"> - 
	solo lettura <input type=\"radio\" name=\"contabilita\" value=\"2\"> - 
	Modifica <input type=\"radio\" name=\"contabilita\" value=\"3\" checked> - 
	Tutto <input type=\"radio\" name=\"contabilita\" value=\"4\"></tr>\n";

        echo "<tr><td>Scadenziario</td><td>vietato<input type=\"radio\" name=\"scadenziario\" value=\"1\"> - 
	solo lettura <input type=\"radio\" name=\"scadenziario\" value=\"2\"> - 
	Modifica <input type=\"radio\" name=\"scadenziario\" value=\"3\" checked> - 
	Tutto <input type=\"radio\" name=\"scadenziario\" value=\"4\"></tr>\n";

        echo "<tr><td>Plug - ins</td><td>vietato<input type=\"radio\" name=\"plugins\" value=\"1\"> - 
	solo lettura <input type=\"radio\" name=\"plugins\" value=\"2\"> - 
	Modifica <input type=\"radio\" name=\"plugins\" value=\"3\" checked> - 
	Tutto <input type=\"radio\" name=\"plugins\" value=\"4\"></tr>\n";

        echo "<tr><td>Rep. Amministratore</td><td>vietato<input type=\"radio\" name=\"setting\" value=\"1\"> - 
	solo lettura <input type=\"radio\" name=\"seting\" value=\"2\"> - 
	Modifica <input type=\"radio\" name=\"setting\" value=\"3\" checked> - 
	Tutto <input type=\"radio\" name=\"setting\" value=\"4\"></tr>\n";




        echo "<tr><td colspan=2 align=RIGHT><input type=\"submit\" name=\"azione\" value=\"Inserisci\"></td></tr>";
    }
    else
    {
        echo "<tr><td colspan=2 align=\"center\"><font size=3><span class=\"testo_blu\"><b>Modifica o Elimina Utente </b><br></font></span></td>\n";

        $dati = tabella_utenti("singola", $_id, $_user, $_password, $_blocca, $_parametri);


        printf("<tr><td align=right>id</td><td><input type=\"radio\" name=\"id\" value=\"%s\" size=\"4\" maxleght=\"3\" checked>%s</td></tr>", $dati['id'], $dati['id']);
        printf("<tr><td align=right> Nome : </td><td><input type=\"text\" name=\"user\" value=\"%s\" size=\"40\" maxleght=\"40\"></td></tr>", $dati['user']);
        printf("<tr><td align=right>Password :</td><td><input type=\"text\" name=\"pwd\" value=\"%s\" size=\"40\" maxleght=\"40\"></td></tr>", $dati['pwd']);
        printf("<tr><td align=right>RE - Password :</td><td><input type=\"text\" name=\"repwd\" value=\"%s\" size=\"40\" maxleght=\"40\"></td></tr>", $dati['pwd']);
        printf("<tr><td align=right>Data reg. : </td><td>%s</td></tr>", cambio_data("it", $dati['datareg']));
        printf("<tr><td align=right>Blocca Utente : </td><td><input type=\"text\" name=\"blocco\" value=\"%s\" size=\"3\" maxleght=\"2\"></td></tr>", $dati['blocco']);
        printf("<tr><td align=right>Visualizzazione Scadenziario : </td><td><input type=\"text\" name=\"perm\" value=\"%s\" size=\"3\" maxleght=\"2\"></td></tr>", $dati['perm']);
        echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
        echo "<tr><td align=\"center\" colspan=\"2\"> Permessi Da assegnare all'utente </td></tr>\n";
        echo "<tr><td>Anagrafiche</td><td>vietato<input type=\"radio\" name=\"anagrafiche\" value=\"1\" \n";
        if ($dati[anagrafiche] == 1)
        {
            echo "checked";
        }
        echo "> - 
	solo lettura <input type=\"radio\" name=\"anagrafiche\" value=\"2\" \n";
        if ($dati[anagrafiche] == 2)
        {
            echo "checked";
        }
        echo "> - 
	Modifica <input type=\"radio\" name=\"anagrafiche\" value=\"3\" \n";
        if ($dati[anagrafiche] == 3)
        {
            echo "checked";
        }
        echo "> - 
	Tutto <input type=\"radio\" name=\"anagrafiche\" value=\"4\" \n";
        if ($dati[anagrafiche] == 4)
        {
            echo "checked";
        }
        echo "></tr>\n";

        echo "<tr><td>Rep. Vendite</td><td>vietato<input type=\"radio\" name=\"vendite\" value=\"1\"";
        if ($dati[vendite] == 1)
        {
            echo "checked";
        }
        echo "> - 
	solo lettura <input type=\"radio\" name=\"vendite\" value=\"2\"\n";
        if ($dati[vendite] == 2)
        {
            echo "checked";
        }
        echo "> - 
	Modifica <input type=\"radio\" name=\"vendite\" value=\"3\" \n";
        if ($dati[vendite] == 3)
        {
            echo "checked";
        }
        echo "> - 
	Tutto <input type=\"radio\" name=\"vendite\" value=\"4\" \n";
        if ($dati[vendite] == 4)
        {
            echo "checked";
        }
        echo "></tr>\n";

        echo "<tr><td>Magazzino</td><td>vietato<input type=\"radio\" name=\"magazzino\" value=\"1\" \n";
        if ($dati[magazzino] == 1)
        {
            echo "checked";
        }
        echo "> - 
	solo lettura <input type=\"radio\" name=\"magazzino\" value=\"2\" \n";
        if ($dati[magazzino] == 2)
        {
            echo "checked";
        }
        echo "> - 
	Modifica <input type=\"radio\" name=\"magazzino\" value=\"3\"  \n";
        if ($dati[magazzino] == 3)
        {
            echo "checked";
        }
        echo "> - 
	Tutto <input type=\"radio\" name=\"magazzino\" value=\"4\" \n";
        if ($dati[magazzino] == 4)
        {
            echo "checked";
        }
        echo "></tr>\n";

        echo "<tr><td>Rep. Stampe</td><td>vietato<input type=\"radio\" name=\"stampe\" value=\"1\" \n";
        if ($dati[stampe] == 1)
        {
            echo "checked";
        }
        echo "> - 
	solo lettura <input type=\"radio\" name=\"stampe\" value=\"2\" \n";
        if ($dati[stampe] == 2)
        {
            echo "checked";
        }
        echo "> -  
	Modifica <input type=\"radio\" name=\"stampe\" value=\"3\"  \n";
        if ($dati[stampe] == 3)
        {
            echo "checked";
        }
        echo "> -  
	Tutto <input type=\"radio\" name=\"stampe\" value=\"4\" \n";
        if ($dati[stampe] == 4)
        {
            echo "checked";
        }
        echo "></tr>\n";


        echo "<tr><td>Contabilità</td><td>vietato<input type=\"radio\" name=\"contabilita\" value=\"1\" \n";
        if ($dati[contabilita] == 1)
        {
            echo "checked";
        }
        echo "> -  
	solo lettura <input type=\"radio\" name=\"contabilita\" value=\"2\"\n";
        if ($dati[contabilita] == 2)
        {
            echo "checked";
        }
        echo "> - 
	Modifica <input type=\"radio\" name=\"contabilita\" value=\"3\" \n";
        if ($dati[contabilita] == 3)
        {
            echo "checked";
        }
        echo "> -
	Tutto <input type=\"radio\" name=\"contabilita\" value=\"4\"\n";
        if ($dati[contabilita] == 4)
        {
            echo "checked";
        }
        echo "></tr>\n";

        echo "<tr><td>Scadenziario</td><td>vietato<input type=\"radio\" name=\"scadenziario\" value=\"1\"\n";
        if ($dati[scadenziario] == 1)
        {
            echo "checked";
        }
        echo "> - 
	solo lettura <input type=\"radio\" name=\"scadenziario\" value=\"2\"\n";
        if ($dati[scadenziario] == 2)
        {
            echo "checked";
        }
        echo "> -  
	Modifica <input type=\"radio\" name=\"scadenziario\" value=\"3\" \n";
        if ($dati[scadenziario] == 3)
        {
            echo "checked";
        }
        echo "> - 
	Tutto <input type=\"radio\" name=\"scadenziario\" value=\"4\"\n";
        if ($dati[scadenziario] == 4)
        {
            echo "checked";
        }
        echo "></tr>\n";

        echo "<tr><td>Plug - ins</td><td>vietato<input type=\"radio\" name=\"plugins\" value=\"1\"\n";
        if ($dati[plugins] == 1)
        {
            echo "checked";
        }
        echo "> - 
	solo lettura <input type=\"radio\" name=\"plugins\" value=\"2\"\n";
        if ($dati[plugins] == 2)
        {
            echo "checked";
        }
        echo "> - 
	Modifica <input type=\"radio\" name=\"plugins\" value=\"3\" \n";
        if ($dati[plugins] == 3)
        {
            echo "checked";
        }
        echo "> -
	Tutto <input type=\"radio\" name=\"plugins\" value=\"4\"\n";
        if ($dati[plugins] == 4)
        {
            echo "checked";
        }
        echo "></tr>\n";

        echo "<tr><td>Rep. Amministratore</td><td>vietato<input type=\"radio\" name=\"setting\" value=\"1\"\n";
        if ($dati[setting] == 1)
        {
            echo "checked";
        }
        echo "> - 
	solo lettura <input type=\"radio\" name=\"seting\" value=\"2\"\n";
        if ($dati[setting] == 2)
        {
            echo "checked";
        }
        echo "> -
	Modifica <input type=\"radio\" name=\"setting\" value=\"3\"\n";
        if ($dati[setting] == 3)
        {
            echo "checked";
        }
        echo "> -
	Tutto <input type=\"radio\" name=\"setting\" value=\"4\"\n";
        if ($dati[setting] == 4)
        {
            echo "checked";
        }
        echo "></tr>\n";

        echo "<tr><td>&nbsp;</td><td align=RIGHT><input type=\"submit\" name=\"azione\" value=\"Modifica\"><input type=\"submit\" name=\"azione\" value=\"Elimina\"></td></tr>";
    }

    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</table>";

}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>