<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso ."../setting/vars.php";
session_start();
$_SESSION['keepalive']++;
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



if ($_SESSION['user']['plugins'] > "1")
{


    echo "<table><tr><td valign=\"top\">";
    require "naviga.php";
    echo "</td>";
    ?>
    <td width="85%" align="center" valign="top">
        <span class="intestazione"><br><b>Gestione Programmi proprietari</b></span><br>
        <img src="../images/agua.jpg" width="363" height="267" border="0"><br>
    </td>
    </tr>
    </table>
    </body>
    </html>
    <?php

}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>