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
    echo "<table width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td width=\"80%\" valign=\"top\">\n";
    ?>


    <div align="center">
        <font size="+2"><strong>Programma di salvataggio dati AGUA GEST <br>
            Backup completo del database<br>
        </strong></font>

    </div>
    <form name="salvataggio" method="post" action="copie.php">
        <table width="70%" border="0" align="center">
            <tr>
                <td>Salvataggio struttura</td>
                <td><input name="struttura" type="checkbox" value="SI" checked>
                </td>
            </tr>
            <tr>
                <td>Salvataggio Dati</td>
                <td><input name="dati" type="checkbox" value="SI" checked>
                </td>
            </tr>
            <tr>
                <td>Nome del Server</td>
                <td><input name="db_server" type="text" value="<? echo $db_server; ?>"></td>
            </tr>
            <tr>
                <td>Nome del database</td>
                <td><input type="text" name="db_nomedb" value="<? echo $db_nomedb; ?>"></td>
            </tr>
            <tr>
                <td>Nome utente Mysql</td>
                <td> <input type="text" name="db_user" value="<? echo $db_user; ?>"> </tr>
            <tr>
                <td>Password MySQL</td>
                <td><input type="password" name="db_password" value="<? echo $db_password; ?>"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="bouton" value="Salva"></td>
            </tr>
        </table>
    </form>

    <p align="center"><strong><font size="4" COLOR="RED">ISTRUZIONI:</font></strong></p>

    <p align="center"><strong><font size="3">Una volta premuto il tasto il salvataggio in diretta partir&agrave; immediatamente <BR>
            e si aprir&agrave; una finestra dove potrete salvare il file in locale.<br>
            <font color="RED">CONTROLLARE SEMPRE LE DIMENSIONI DEL FILE</font></strong></p>



    <?php
    echo "</td></tr></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>