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

if ($_SESSION['user']['vendite'] > "1")
{


    if($_GET['tipo'] == "tutte")
    {
            //recupero i post
        $_parametri['data_start'] = "0000-00-00";
        $_parametri['data_fine'] = "0000-00-00";
        $_parametri['tipo'] = "tutte"; 
    }
    else
    {
        //recupero i post
     $_parametri['data_start'] = cambio_data("us", $_POST['data_start']);
     $_parametri['data_fine'] = cambio_data("us", $_POST['data_fine']);
     $_parametri['tipo'] = $_POST['tipo']; 
    }
    
    $result = tabella_effetti("selezione_elenco", $_percorso, $_annoeff, $_numeff, $_parametri);




    // elenca le fatture presenti in archivio non evase FATTURE VENDITA
    // elenco documenti fatture vendita
    echo "<span class=\"testo_blu\"><center><br><b>Scadenziario Effetti.. </b></center><br>";
    echo "<center><a href=\"scadenziario_eff_stampa.php?tipo=$_POST[tipo]&data_start=$_POST[data_start]&data_fine=$_POST[data_fine]\" target=no >Stampa Questa Pagina ==><img src=\"../../images/printer.png\" valign=\"center\" width=\"50\"></a><br>\n";
    echo "1 - Rimessa diretta - 2 - Contanti - 3 - Ricevuta bancaria - 4 - Tratta o cambiale - 5 - Contrassegno - 6 - Bonifico Bancario - 7 - Ricevimento Fattura </span> <br>";


    // Tutto procede a meraviglia...
    echo "<table width=\"90%\" align=\"center\">";
    echo "<tr>";
    echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Tipo</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Fatt.</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Pagamento</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Scadenza</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
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
                echo "<tr><td align =\"center\" colspan=\"9\"><br><font face=\"arial\">Scadenze al $_mese_rif</font></td></tr>\n";
            }
        }
        echo "<tr>";
        printf("<form action=\"visualizzadoc.php?anno=$dati[annoeff]\" method=\"POST\">");
        printf("<td width=\"30\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['tipoeff']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['documento']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['numdoc']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['modpag']);
        printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
        printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['impeff']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['scadenza']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['status']);
        printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"submit\" name=\"ndoc\" value=\"%s\"></td>", $dati['numeff']);
        $_prima = "";

        $_somma = $_somma + $dati['impeff'];
        $_totale = $_totale + $dati['impeff'];



        echo "</form></tr>";
        echo "<tr>";
        echo "<td width=\"30\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "</tr>";
        $_mese_rif2 = $_mese_rif;
    }

    echo "<tr><td align =\"right\" colspan=\"9\"><font face=\"arial\" size=\"2\" color=\"black\">Importo mese euro $_somma</font></td></tr>\n";
    $_somma = "";

    echo "<tr><td align =\"right\" colspan=\"9\"><font face=\"arial\" size=\"2\" color=\"black\"><b>Totale effetti euro $_totale</b></font></td></tr>\n";
    echo "</td></tr></table></body></html>";


    //chiudiamo la connessione
    $conn->null;
    $conn = null;
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>