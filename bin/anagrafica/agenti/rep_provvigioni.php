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
ini_set('session.gc_maxlifetime', $SESSIONTIME);
session_start();
$_SESSION['keepalive'] ++;

require $_percorso . "librerie/lib_html.php";
require "../../librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);


if ($_SESSION['user']['anagrafiche'] > "1")
{

    $_annorif = $_GET['annorif'];
    $_codiceage = $_GET['codiceage'];

        //intestazione.
        
        
        
        echo "<h1>Risultato provvigioni agente Anno in corso</h1>";

        // Stringa contenente la query di ricerca...

        $query = sprintf("select * from provvigioni INNER JOIN clienti ON provvigioni.utente=clienti.codice where codage=\"%s\" and datareg LIKE \"%s\" order by ndoc", $_codiceage, $_annorif);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        // Tutto procede a meraviglia...
        echo "<table align=center width=\"700\">";
        echo "<tr>";
        echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">N. Doc.</span></td>";
        echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Tipo Documento</span></td>";
        echo "<td width=\"150\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Data reg.</span></td>";
        echo "<td width=\"300\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Utente</span></td>";
        echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Valore Doc.</span></td>";
        echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Val. Riscosso</span></td>";
        echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Provv. netta</span></td>";
        echo "</tr>";

        foreach ($result AS $dati)
        {
            echo "<tr>";
            printf("<td width=\"70\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['ndoc']);
            printf("<td width=\"200\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['tdoc']);
            printf("<td width=\"150\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
            printf("<td width=\"300\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
            printf("<td width=\"150\" align=\"right\"><span class=\"testo_blu\">%s</span></td>", $dati['totdoc']);
            printf("<td width=\"150\" align=\"right\"><span class=\"testo_blu\">%s</span></td>", $dati['riscosso']);
            printf("<td width=\"150\" align=\"right\"><span class=\"testo_blu\">%s</span></td>", $dati['provvigioni']);
            echo "</tr>";
            $_totdoc = $_totdoc + $dati['totdoc'];
            $_totprovv = $_totprovv + $dati['provvigioni'];
            echo "<tr>";
            echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"200\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"300\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "</tr>";
        }


        echo "<tr>";
        echo "<td width=\"70\" align=\"center\" ></td>";
        echo "<td width=\"200\" align=\"center\" ></td>";
        echo "<td width=\"150\" align=\"center\" ></td>";
        echo "<td width=\"300\" align=\"center\" ></td>";
        echo "<td width=\"150\" align=\"right\" >$_totdoc</td>";
        echo "<td width=\"150\" align=\"center\" ></td>";
        echo "<td width=\"150\" align=\"right\" >$_totprovv</td>";
        echo "</tr>";

        echo "</td></tr></table></body></html>";
    }
    else
    {
        permessi_sessione($_cosa, $_percorso);
    }
    ?>