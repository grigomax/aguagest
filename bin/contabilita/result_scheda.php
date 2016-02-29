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

require "../librerie/motore_primanota.php";
require "../librerie/motore_anagrafiche.php";
require "../../setting/par_conta.inc.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{



//recupero tutti POST se il GET non c'è

    if ($_POST['diretto'] != "")
    {
        //$_codconto = $_POST['diretto'];
        // mi prendo le prime due cifre passate..
        $_mastro = substr($_POST['diretto'], 0, 2);
        $_codconto = substr($_POST['diretto'], 2, 10);

        if ($_mastro == $MASTRO_CLI)
        {
            $dati = tabella_clienti("singola", $_codconto, $_parametri);

            $_descrizione = $dati['ragsoc'];
            $_tipo_cf = "C";

            //vuol dire che sono clienti
            $_conto = sprintf("%s%s", $MASTRO_CLI, $_codconto);
        }
        elseif ($_mastro == $MASTRO_FOR)
        {
            //vuol dire che sono clienti
            $dati = tabella_fornitori("singola", $_codconto, $_parametri);

            $_descrizione = $dati['ragsoc'];
            $_conto = sprintf("%s%s", $MASTRO_FOR, $_codconto);
            $_tipo_cf = "F";
        }
        else
        {
            $_codconto = $_POST['diretto'];
            $dati = tabella_piano_conti("singola", $_codconto, $_parametri);

            $_descrizione = $dati['descrizione'];
            $_tipo_cf = $dati['tipo_cf'];
            $_conto = $_codconto;
        }

        $_start = $_POST['start'];
        $_start_sql = cambio_data("us", $_POST['start']);


        $_end = $_POST['end'];
        $_end_sql = cambio_data("us", $_POST['end']);
        $_start_anno = cambio_data("anno_it", $_POST['start']);
    }
    else
    {
        if ($_GET['azione'] != "")
        {
            
            $_tipo_cf = substr($_GET['azione'], "0", "1");
            $_start_anno = substr($_GET['azione'], "1", "4");
            $_codconto = substr($_GET['azione'], "5", "10");
            
            $_start = "01-01-" . $_start_anno;
            $_start_sql = $_start_anno . "-01-01";

            $_end_sql = $_start_anno . "-12-31";
            $_end = "31-12-" . $_start_anno;
    
        }
        else
        {
            $_start = $_POST['start'];
            $_start_sql = cambio_data("us", $_POST['start']);

            $_end = $_POST['end'];
            $_end_sql = cambio_data("us", $_POST['end']);

            $_tipo_cf = $_POST['tipo_cf'];
            $_codconto = $_POST['codconto'];
            $_start_anno = cambio_data("anno_it", $_POST['start']);
        }

//componiamo il conto completo
//prima di inserire facciamo un po di conti..
//completiamo il codice conto..
        if ($_tipo_cf == "C")
        {
            $dati = tabella_clienti("singola", $_codconto, $_parametri);
            $_descrizione = $dati['ragsoc'];

            //vuol dire che sono clienti
            $_conto = sprintf("%s%s", $MASTRO_CLI, $_codconto);
        }
        elseif ($_tipo_cf == "F")
        {
            //vuol dire che sono clienti
            $dati = tabella_fornitori("singola", $_codconto, $_parametri);
            $_descrizione = $dati['ragsoc'];
            $_conto = sprintf("%s%s", $MASTRO_FOR, $_codconto);
        }
        else
        {
            $dati = tabella_piano_conti("singola", $_codconto, $_parametri);
            $_descrizione = $dati['descrizione'];
            $_conto = $_codconto;
        }
    }
    $_start_prima = $_start_sql - 1;
    $_start_dopo = $_start_sql + 1;


    //proviamo ad inserire i pulsanti..
    echo "<form action=\"\" id=\"pulsanti\" method=\"GET\">";
    
    echo "<center>\n";
    pulsanti("home", "submit", "", "get", "../index.php", "40px", "40px", "Indice", "", "", "Cerca", $_id);
    pulsanti("cerca", "submit", "", "get", "ricerca_scheda.php", "40px", "40px", "Cerca", "", "", "Cerca", $_id);
    pulsanti("indietro", "submit", "", "get", "result_scheda.php", "40px", "40px", "Anno precedente", "azione", "$_tipo_cf$_start_prima$_codconto", "Annopre", $_id);
    pulsanti("avanti", "submit", "", "get", "result_scheda.php", "40px", "40px", "Anno successivo", "azione", "$_tipo_cf$_start_dopo$_codconto", "Annodopo", $_id);
    pulsanti("stampa", "submit", "_blank", "get", "result_scheda_stampa.php", "40px", "40px", "Stampa", "azione", "$_start_sql$_end_sql$_tipo_cf$_codconto", "Stampa", $_id);
    pulsanti("aiuto", "submit", "_blank", "get", "../manuale/visualizza_guida.php?file=M0701.html", "40px", "40px", "Aiuto", "file", "M0701.html", "Aiuto", $_id);
    echo "</form>\n";
    
    
    //echo "<button type=\"submit\" formmethod=\"get\" formaction=\"result_scheda_stampa.php\" formtarget=\"_blank\" name=\"start\" value=\"start=$_start_sql&end=$_end_sql&tipo_cf=$_tipo_cf&codconto=$_codconto\">Clicca</button>\n";
    
    echo "<span class=\"tabella\"><center><b>Scheda Contabile</b><br>\n";
    //selezioniamo il singolo per sapere cosa è ed apriamo una tabella..

    echo "<table align=\"center\" width=\"85%\" border=\"0\>\n";
    echo "<tr><td align=\"left\" colspan=\"6\"><br><h3>$_codconto - $_descrizione</h3></td></tr>\n";
    echo "<tr><td align=\"center\" colspan=\"6\"><br>Dalla Data $_start  Alla data $_end </td></tr>\n";



    //provo a prendermi il saldo precedente
    $query = "SELECT *, SUM(dare), SUM(avere), date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont, data_cont AS data from prima_nota where conto = '$_conto ' AND data_cont >= '$_start_anno-01-01' AND data_cont < '$_start_sql' ORDER BY data ASC, nreg ";
    //echo $query;

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    $saldo = $result->fetch(PDO::FETCH_ASSOC);

    $_saldo = $saldo['SUM(dare)'] - $saldo['SUM(avere)'];

    $query = "SELECT *, data_reg, date_format(data_cont, '%d-%m-%Y') data_cont, data_cont AS data from prima_nota where conto = '$_conto ' AND data_cont >= '$_start_sql' AND data_cont <= '$_end_sql' ORDER BY data ASC, data_reg, nreg ";

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    // Tutto procede a meraviglia...

    echo "<tr>";
    echo "<td width=\"30\" align=\"center\" class=\"tabella\">N. Reg</span></td>\n";
    echo "<td width=\"50\" align=\"center\" class=\"tabella\">Data cont.</span></td>\n";
    echo "<td width=\"30\" align=\"center\" class=\"tabella\">N. prot.</span></td>\n";
    echo "<td width=\"380\" align=\"CENTER\" class=\"tabella\">Descrizione</span></td>\n";
    echo "<td width=\"50\" align=\"center\" class=\"tabella\">Dare</span></td>\n";
    echo "<td width=\"50\" align=\"center\" class=\"tabella\">Avere</span></td>\n";
    echo "<td width=\"90\" align=\"center\" class=\"tabella\">Saldo</span></td>\n";
    echo "</tr>";
    if ($_saldo > "0.00")
    {
        $_scritta_p = "D";
    }
    else
    {
        $_scritta_p = "A";
    }
    echo "<tr><td class=\"tabella_elenco\"></td><td class=\"tabella_elenco\"></td><td class=\"tabella_elenco\"></td><td class=\"tabella_elenco\">Saldo a riporto</td><td class=\"tabella_elenco\"></td><td class=\"tabella_elenco\"></td><td class=\"tabella_elenco\" align=\"right\">$_saldo $_scritta_p</td></tr>\n";
    foreach ($result AS $dati)
    {
        echo "<tr>";
        printf("<form action=\"registrazioni/visualizza_reg.php?causale=%s&anno=%s\" method=\"POST\">\n", $dati['causale'], $dati['anno']);
        printf("<td align=\"center\" class=\"tabella_elenco\"><input type=\"submit\" name=\"nreg\" value=\"%s\"></span></td>\n", $dati['nreg']);
        printf("<td align=\"center\" class=\"tabella_elenco\">%s</span></td>\n", $dati['data_cont']);
        echo "<td align=\"center\" class=\"tabella_elenco\">$dati[nproto] / $dati[suffix_proto]</span></td>\n";
        printf("<td align=\"left\" class=\"tabella_elenco\">%s</span></td>\n", $dati['descrizione']);

        if ($dati['dare'] == "0.00")
        {
            $dati['dare'] = "&nbsp;";
        }
        if ($dati['avere'] == "0.00")
        {
            $dati['avere'] = "&nbsp;";
        }

        printf("<td align=\"center\" class=\"tabella_elenco\">%s</span></td>\n", $dati['dare']);
        printf("<td align=\"center\"class=\"tabella_elenco\" >%s</span></td>\n", $dati['avere']);
        $_saldo = $_saldo + $dati['dare'] - $dati['avere'];

        if ($_saldo > "0.00")
        {
            $_scritta_p = "D";
        }
        else
        {
            $_scritta_p = "A";
        }

        printf("<td align=\"right\"class=\"tabella_elenco\">%s $_scritta_p</span></td>\n", number_format($_saldo, '2'));


        echo "</tr></form>";
    }
    echo "<tr><td colspan=\"7\"><hr></td></tr>\n";
    if ($_saldo > "0.00")
    {
        $_scritta = "Dare";
    }
    else
    {
        $_scritta = "Avere";
    }
    printf("<tr><td colspan=\"7\" align=\"right\" sclass=\"tabella_elenco\">Totale Saldo = %s %s</span><br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>", number_format($_saldo, '2'), $_scritta);



    echo "</table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>