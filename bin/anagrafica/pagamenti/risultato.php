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
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{
    ?>

    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="left">
        <tr>
            <td width="90%" align="center" valign="top">

                <?php

                echo "<span class=\"testo_blu\"><b>Risulati ricerca</b></span>";


                $_parametri['campi'] = $_POST['campi'];
                $_parametri['descrizione'] = $_POST['descrizione'];

                $result = tabella_pagamenti("elenca_risultato", $_codpag, $_parametri);

                // Tutto procede a meraviglia...
                echo "<table width=\"700\">";
                echo "<tr>";
                echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
                echo "<td width=\"280\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
                echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">gg prima scad</span></td>";
                echo "</tr>";

                foreach ($result AS $dati)
                {
                    echo "<tr>";
                    printf("<td width=\"70\" align=\"center\"><span class=\"testo_blu\"><a href=\"modificapag.php?codice=%s\">%s</span></td>", $dati['codice'], $dati['codice']);
                    printf("<td width=\"280\" align=\"left\"><span class=\"testo_blu\"><a href=\"modificapag.php?codice=%s\">%s</span></td>", $dati['codice'], $dati['descrizione']);
                    printf("<td width=\"200\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ggprimascad']);
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
                    echo "<td width=\"280\" height=\"1\" align=\"center\" class=\"logo\"></td>";
                    echo "<td width=\"200\" height=\"1\" align=\"center\" class=\"logo\"></td>";
                    echo "</tr>";
                }

                echo "</td></tr></table></body></html>";
            }
            else
            {
                permessi_sessione($_cosa, $_percorso);
            }
            ?>