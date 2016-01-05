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

require $_percorso . "librerie/lib_html.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);



//carichiamo la base delle pagine:
        base_html_stampa("chiudi", $_parametri);



if ($_SESSION['user']['vendite'] > "1")
{

    //recupero i post
    $_parametri['data_start'] = cambio_data("us", $_GET['data_start']);
    $_parametri['data_fine'] = cambio_data("us", $_GET['data_fine']);
    $_parametri['tipo'] = $_GET['tipo'];



    $result = tabella_effetti("selezione_elenco", $_percorso, $_annoeff, $_numeff, $_parametri);

    $_parametri['tabella'] = "Tipo = $_GET[tipo] dal $_GET[data_start]";
    $_parametri['stampa'] = "Scadenziario effetti";
    $_parametri['anno'] = $_GET['data_fine'];
    $_parametri['data'] = date('d-m-Y');
    
    intestazione_html($_cosa, $_percorso, $_parametri);
    
    
    echo "<table align=\"center\" border=\"0\" align=\"center\" class=\"classic_bordo\">";
    
    echo "<tr><td align=\"center\" colspan=\"8\" class=\"tabella\">1 - Rimessa diretta - 2 - Contanti - 3 - Ricevuta bancaria - 4 - Tratta o cambiale - 5 - Contrassegno - 6 - Bonifico Bancario - 7 - Ricevimento Fattura</td></tr>";



// Tutto procede a meraviglia...
    echo "<tr>";
    echo "<td width=\"30\" align=\"center\" class=\"tabella\">Tipo</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"tabella\">Data Fatt.</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"tabella\">Fattura</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"tabella\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"tabella\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"tabella\">Scadenza</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"tabella\">N. effetto</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"tabella\">Status</span></td>";
    echo "</tr>";
    $_prima = "ciao";

    foreach ($result AS $dati)
    {
        $_mese_rif = substr($dati['scadeff'], 0, -3);

        if ($_prima != "ciao")
        {
            if ($_mese_rif != $_mese_rif2)
            {
                echo "<tr><td align =\"right\" colspan=\"9\"><font face=\"arial\" size=\"2\" color=\"black\">Importo mese euro $_somma</font></td></tr>\n";
                $_somma = "";
                echo "<tr><td align =\"center\" colspan=\"9\" class=\"tabella\"><br><font face=\"arial\">Scadenze al $_mese_rif</font></td></tr>\n";
            }
        }
        echo "<tr>";
        printf("<td align=\"center\" class=\"tabella_elenco\">%s</span></td>", $dati['tipoeff']);
        printf("<td align=\"center\" class=\"tabella_elenco\">%s</span></td>", $dati['documento']);
        printf("<td align=\"center\" class=\"tabella_elenco\"><b>%s/$dati[suffixdoc]</b></span></td>", $dati['numdoc']);
        printf("<td align=\"left\" class=\"tabella_elenco\">%s</span></td>", $dati['ragsoc']);
        printf("<td align=\"center\" class=\"tabella_elenco\">%s</span></td>", $dati['impeff']);
        printf("<td align=\"center\" class=\"tabella_elenco\">%s</span></td>", $dati['scadenza']);
        printf("<td align=\"center\" class=\"tabella_elenco\"><b>%s</b></span></td>", $dati['numeff']);
        printf("<td align=\"center\" class=\"tabella_elenco\">%s</span></td>", $dati['status']);
        $_prima = "";

        $_somma = $_somma + $dati['impeff'];
        $_totale = $_totale + $dati['impeff'];


        echo "</tr>";
        echo "<tr>";
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "</tr>";
        $_mese_rif2 = $_mese_rif;
    }
    echo "<tr><td align =\"right\" colspan=\"9\"><font face=\"arial\" size=\"2\" color=\"black\">Importo mese euro $_somma</font></td></tr>\n";
    $_somma = "";

    echo "<tr><td align =\"right\" colspan=\"9\"><hr></td></tr>\n";
    echo "<tr><td align =\"right\" colspan=\"9\"><font face=\"arial\" size=\"2\" color=\"black\"><b>Totale effetti euro $_totale</b></font></td></tr>\n";
    echo "</td></tr></table></body></html>";


//chiudiamo la connessione
    $conn->null;
    $conn = null;
    echo "</table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>