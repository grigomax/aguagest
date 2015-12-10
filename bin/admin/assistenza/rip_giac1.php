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
$conn = permessi_sessione("verifica", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{
    ?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td width="85%" align="center" valign="top">
                <span class="intestazione"><b>Programma per la chiusura e la riapertura dei conti di magazzino</b></span><br></td></tr>
        <tr><td align=center><font color="red"><br> Attenzione il programma effettua il ripristino della giacenza iniziale rifacendo i conti con l'anno precedente.., non verifica in alcun modo l'archivio dei carichi del magazzino. L'operazione può essere effettuata quante volte si vuole ogni volta ri riscrive tutto, <b>ed e irreversibile.</b><br><font size="3"><b> Quindi SI CONSIGLIA DI FARE LE COPIE DEGLI ARCHIVI PRIMA DI PROSEGUIRE... GRAZIE "Agua staff"</b></font></font></td></tr>



        <?php

        echo "<br><br><form action=\"rip_giac2.php\" method=\"POST\">";
        echo "<tr><td align=center>seleziona anno da cui ricuperare<br>";
        echo "<select name=\"anno\">\n";
        // Stringa contenente la query di ricerca...
        $query = sprintf("select anno from magastorico order by anno desc");
        // Esegue la query...
        $res = mysql_query($query, $conn);
        // Tutto procede a meraviglia...
        $dati = mysql_fetch_array($res);
        printf("<option value=\"%s\">%s</option>\n", $dati['anno'], $dati['anno']);
        echo "</select>\n";
        echo "</td></tr>\n";

        echo "<tr><td align=center>Seleziona la categoria da ripristinare<br>";
        echo "<select name=\"catmer\">\n";
        $query = sprintf("select * from catmer order by catmer");
        // Esegue la query...
        $res = mysql_query($query, $conn);
        // Tutto procede a meraviglia...
        while ($dati = mysql_fetch_array($res))
        {
            printf("<option value=\"%s\">%s</option>\n", $dati['catmer'], $dati['catmer']);
        }
        echo "</select>\n";
        echo "</td></tr>\n";

        echo "</table><center><br><input type=\"submit\" value=\"Ripristina\");>\n";
        echo "</form>\n</td>\n";
        echo "</td>\n</tr>\n";


        echo "</body></html>";
    }
    else
    {
        permessi_sessione($_cosa, $_percorso);
    }
    ?>
