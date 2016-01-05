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
base_html_stampa("chiudi", $_parametri);

//carichiamo la testata del programma.
#testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{
    

//recuperiamo i post..
    $_data_start_sql = cambio_data("us", $_POST['data_start']);
    $_data_end_sql = cambio_data("us", $_POST['data_end']);
    $_parametri['tabella'] = "Stampa Intrastrat dal $_POST[data_start] AL $_POST[data_end]";

    intestazione_html($_cosa, $_percorso, $_parametri);



#PER PRIMA MI DEVO prendere i codici iva inerenti;

    $query = "SELECT * from aliquota where ivacee ='S' order by codice";

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    echo "<table class=\"classic\" align=\"left\" width=\"100%\">";
    if ($result->rowCount() > 0)
    {

        echo "<tr>";
        echo "<td width=\"30\" align=\"center\" class=\"tabella\">Cod. Iva</td>";
        echo "<td width=\"120\" align=\"center\" class=\"tabella\">Data Fatt.</span></td>";
        echo "<td width=\"80\" align=\"center\" class=\"tabella\">Fattura</span></td>";
        echo "<td width=\"60\" align=\"center\" class=\"tabella\">N.Proto</span></td>";
        echo "<td width=\"60\" align=\"center\" class=\"tabella\">Causale</span></td>";
        echo "<td width=\"450\" align=\"left\" class=\"tabella\">Ragione Sociale</span></td>";
        echo "<td width=\"100\" align=\"left\" class=\"tabella\">P. Iva</span></td>";
        echo "<td width=\"80\" align=\"center\" class=\"tabella\">Dare</span></td>";
        echo "<td width=\"80\" align=\"center\" class=\"tabella\">Avere</span></td>";

        echo "</tr>";

        foreach ($result AS $dati)
        {
            //per ogni risultato verifichiamo delle fatture..

            $query = "SELECT * FROM prima_nota where iva='$dati[codice]' AND data_cont >= '$_data_start_sql' AND data_cont <= '$_data_end_sql'";

            $result2 = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }

            foreach ($result2 AS $dati_2)
            {
                
                if($vista[$dati_2['nreg']] != "SI")
                {
                    $query3 = "SELECT * FROM prima_nota WHERE anno='$dati_2[anno]' AND nreg='$dati_2[nreg]' ORDER BY rigo";
                    $result3 = $conn->query($query3);

                    if ($conn->errorCode() != "00000")
                    {
                        $_errore = $conn->errorInfo();
                        echo $_errore['2'];
                        //aggiungiamo la gestione scitta dell'errore..
                        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                        scrittura_errori($_cosa, $_percorso, $_errori);
                    }
                
                    foreach ($result3 AS $dati3)
                    {
                    
                        echo "<tr>";
                        printf("<td align=\"center\">%s</span></td>", $dati3['iva']);
                        printf("<td align=\"center\">%s</span></td>", $dati3['data_doc']);
                        echo "<td align=\"center\"><b>$dati3[ndoc]/$dati3[suffix_doc]</b></span></td>\n";
                        echo "<td align=\"center\"><b>$dati3[nproto]/$dati3[suffix_proto]</b></span></td>\n";
                        printf("<td align=\"center\"><b>%s</b></span></td>", $dati3['causale']);
                        printf("<td align=\"left\">%s</span></td>", $dati3['desc_conto']);
                        printf("<td align=\"left\">%s</span></td>", $dati3['piva']);
                        printf("<td align=\"right\">%s</span></td>", $dati3['dare']);
                        printf("<td align=\"right\">%s</span></td>", $dati3['avere']);

                        echo "</tr>";
                        echo "<tr>";
                        echo "<td height=\"1\" align=\"center\"></td>";
                        echo "<td height=\"1\" align=\"center\"></td>";
                        echo "<td height=\"1\" align=\"center\"></td>";
                        echo "<td height=\"1\" align=\"center\"></td>";
                        echo "<td height=\"1\" align=\"center\"></td>";
                        echo "<td height=\"1\" align=\"center\"></td>";
                        echo "<td height=\"1\" align=\"center\"></td>";
                        echo "<td height=\"1\" align=\"center\"></td>";
                        echo "<td height=\"1\" align=\"center\"></td>";
                        echo "</tr>";
                        $vista[$dati_2['nreg']] = "SI";
                        
                    }
                    echo "<tr><td colspan=\"10\"><hr></td></tr>\n";
                }
                
                
                
                #$_mese_rif2 = $_mese_rif;
            }
            
        }
    }
    else
    {
        echo "<h2>Non ci sono aliquote con selezionato ivacee </h2>\n";
    }
    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>