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


//carico le librerie necessarie
require "../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);




if ($_SESSION['user']['scadenziario'] > "1")
{

    //recupero i post
    $_parametri['data_scad'] = cambio_data("us", $_POST['data_scad']);
    $_parametri['data_fine'] = cambio_data("us", $_POST['data_fine']);
    $_parametri['tipo'] = $_POST['tipo'];


    echo "<h3 align=\"center\">Scadenze ed Appuntamenti in agenda per $_parametri[tipo]</h3><br>";

    $result = tabella_scadenziario("elenco_pertipo", $_percorso, $_parametri);

// Tutto procede a meraviglia...
    echo "<table class=\"elenco_stampa\">";
    echo "<tr class=\"titolo\">";
    echo "<td width=\"30\" align=\"center\">Numero</span></td>";
    echo "<td width=\"40\" align=\"center\">Data Scad.</span></td>";
    echo "<td width=\"300\" align=\"left\">Descrizione</span></td>";
    echo "<td width=\"50\" align=\"center\">Importo Totale</span></td>";
    echo "<td width=\"50\" align=\"center\">Importo Scadenza</span></td>";
    echo "<td width=\"30\" align=\"center\">Banca</span></td>";
    echo "<td width=\"30\" align=\"center\">Cod. Utente</span></td>";
    echo "<td width=\"30\" align=\"center\">N. prot. iva</span></td>";
    echo "<td width=\"30\" align=\"center\">Status</span></td>";

    echo "</tr>";
    $_prima = "ciao";
    foreach ($result AS $dati)
    {
        if ($dati['status'] != 'saldato')
        {
            $_mese_rif = substr($dati['data_scad'], 0, -3);

            if ($_prima != "ciao")
            {
                if ($_mese_rif != $_mese_rif2)
                {
                    echo "<tr><td align =\"right\" colspan=\"11\"><font face=\"arial\" size=\"2\">Importo mese euro $_somma</font></td></tr>\n";
                    $_somma = "";
                    echo "<tr><td align =\"center\" colspan=\"11\"><br><font face=\"arial\"><b>Scadenze al $_mese_rif</b></font></td></tr>\n";
                }
            }
            echo "<tr>";
            echo "<td width=\"30\" align=\"center\">$dati[nscad]</td>\n";
            echo "<td width=\"40\" align=\"center\">$dati[scadenza]</td>\n";
            echo "<td width=\"300\" align=\"left\">$dati[descrizione]</a></td>";
            echo "<td width=\"50\" align=\"right\">$dati[importo]</td>\n";
            echo "<td width=\"50\" align=\"right\"><b>$dati[impeff]</b></td>\n";
            echo "<td width=\"30\" align=\"center\">$dati[banca]</td>\n";
            echo "<td width=\"30\" align=\"center\">$dati[utente]</td>\n";
            echo "<td width=\"3	0\" align=\"center\">$dati[nproto]</td>\n";
            echo "<td width=\"3	0\" align=\"center\">$dati[status]</td>\n";
            $_prima = "";

            $_somma = $_somma + $dati['impeff'];
            $complessivo = $complessivo + $dati['impeff'];
            echo "</form></tr>";
            echo "<tr>";
            echo "<td colspan=\"9\"><hr>\n";
            echo "</tr>";

            $_mese_rif2 = $_mese_rif;
        }
    }
    echo "<tr><td align =\"right\" colspan=\"11\"><font face=\"arial\" size=\"2\">Importo mese euro $_somma</font></td></tr>\n";
    
    $_somma = "";

    echo "</td></tr></table>\n";
    echo "<font face=\"arial\" size=\"3\"><b>Importo Complessivo euro $complessivo</b></font>\n";
    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>