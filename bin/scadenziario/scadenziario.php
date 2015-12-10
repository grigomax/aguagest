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

base_html($_cosa, $_percorso);
java_script($_cosa, $_percorso);
jquery_datapicker($_cosa, $_percorso);

echo "</head>\n";
echo "<body>\n";

testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['scadenziario'] > "1")
{

    echo "<table align=\"left\" width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\">\n";

    if ($_GET['giorno'] != "")
    {
        //cambiamo la data..
        $_data = cambio_data("it", $_GET['giorno']);
        $result = tabella_scadenziario("giorno", $_percorso, $_GET['giorno']);
        //echo $result->rowCount();
    }
    else
    {
        $_parametri['data_scad'] = cambio_data("us", $_POST['data_scad']);
        $_parametri['campi'] = $_POST['campi'];
        $_parametri['descrizione'] = $_POST['descrizione'];

        $result = tabella_scadenziario("elenco_dadata", $_percorso, $_parametri);
    }

    echo "<h3 align=\"center\">Scadenze ed Appuntamenti in agenda $_data</h3><br>";

    if ($result->rowCount() < 1)
    {
        echo "<form action=\"scadenza.php\" id=\"modifica\" method=\"GET\">";
        pulsanti("nuovo", "submit", "nuovo_get", "get", "scadenza.php", "100px", "100px", "Inserisci Scadenza", "giorno", $_GET['giorno'], "nuovo", "modifica");
        echo "</form>\n";
    }
    else
    {



// Tutto procede a meraviglia...
        
        echo "<table width=\"90%\" align=\"center\">";
        echo "<form action=\"result_scad.php\" id=\"sposta\" method=\"POST\">\n";
        echo "<tr>";
        echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">N. Scad.</span></td>";
        echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Scad.</span></td>";
        echo "<td align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
        echo "<td width=\"90\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
        echo "<td width=\"90\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Importo Scadenza</span></td>";
        echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Banca</span></td>";
        echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Cod. Utente</span></td>";
        echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">N. prot. iva</span></td>";
        echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Seleziona</span></td>";

        echo "</tr>";
        $_prima = "ciao";
        foreach ($result AS $dati)
        {
            //echo "mona";
            $_mese_rif = substr($dati['data_scad'], 0, -3);

            if ($_prima != "ciao")
            {
                if ($_mese_rif != $_mese_rif2)
                {
                    echo "<tr><td align =\"right\" colspan=\"9\"><font face=\"arial\" size=\"2\">Importo mese euro $_somma</font></td></tr>\n";
                    $_somma = "";
                    echo "<tr><td align =\"center\" colspan=\"9\"><br><font face=\"arial\">Scadenze al $_mese_rif</font></td></tr>\n";
                }
            }
            echo "<tr>";
            echo "<td width=\"50\" align=\"center\"><span class=\"testo_blu\">$dati[nscad]</span></td>\n";
            echo "<td width=\"50\" align=\"center\"><span class=\"testo_blu\">$dati[scadenza]</span></td>\n";
            echo "<td align=\"left\"><span class=\"testo_blu\"><a href=\"scadenza.php?azione=visualizza&anno=$dati[anno]&nscad=$dati[nscad]\">$dati[descrizione]</a></span></td>";
            echo "<td width=\"90\" align=\"right\"><span class=\"testo_blu\">$dati[status]</span></td>\n";
            echo "<td width=\"90\" align=\"right\"><span class=\"testo_blu\"><b>$dati[impeff]</b></span></td>\n";
            echo "<td width=\"30\" align=\"center\"><span class=\"testo_blu\">$dati[banca]</span></td>\n";
            echo "<td width=\"50\" align=\"center\"><span class=\"testo_blu\">$dati[utente]</span></td>\n";
            echo "<td width=\"50\" align=\"center\"><span class=\"testo_blu\">$dati[nproto]</span></td>\n";
            printf("<td width=\"50\" align=\"center\"><input type=checkbox name=\"numero[]\" value=\"%s%s\"></td>\n", $dati['anno'], $dati['nscad']);
            $_prima = "";

            if($dati['status'] != "saldato")
            {
                $_somma = $_somma + $dati['impeff'];
                $_totale = $_totale + $dati['impeff'];  
            }
            
            echo "</tr>";
            echo "<tr>";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
            echo "<td height=\"1\" align=\"center\" class=\"logo\"></td>\n";
            echo "<td width=\"90\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
            echo "<td width=\"90\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
            echo "<td width=\"30\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
            echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>\n";
            echo "</tr>";

            $_mese_rif2 = $_mese_rif;
        }
        echo "<tr><td align =\"right\" colspan=\"10\"><font face=\"arial\" size=\"2\">Importo mese euro $_somma</font></td></tr>\n";
        $_somma = "";
        echo "<tr><td colspan=\"9\" align=\"right\"><hr><br> Importo scadenze Totale = € $_totale</td></tr>\n";
        echo "<tr><td colspan=\"9\" align=\"right\"><hr><br> Sposta Scadenze selezionate <input type=\"text\" class=\"data\" size=\"11\" maxlength=\"10\" name=\"data\" value=\"$_data\"> \n";
        echo "<br><input type=\"submit\" name=\"azione\" value=\"sposta\"></td></tr>\n";
        echo "</form>\n";
        echo "</td></tr></table>\n";
    }
    echo "</td></tr></body></html>";

    $conn->null;
    $conn = null;
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>