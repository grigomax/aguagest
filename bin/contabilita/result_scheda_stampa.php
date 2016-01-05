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
require "../../setting/par_conta.inc.php";
require "../librerie/motore_anagrafiche.php";
require "../librerie/stampe_pdf.php";
//qui parte l'avventura del sig. buonaventura...


if ($_SESSION['user']['contabilita'] > "1")
{



//recupero tutti POST.

    $_start = $_GET['start'];
    $_end = $_GET['end'];

    $_tipo_cf = $_GET['tipo_cf'];
    $_codconto = $_GET['codconto'];
    $_tipo = $_GET['tipo'];


//componiamo il conto completo
//prima di inserire facciamo un po di conti..
//completiamo il codice conto..
    if ($_tipo_cf == "C")
    {
        $dati = tabella_clienti("singola", $_codconto, $_parametri);

        $_descrizione = $dati['ragsoc'];
        $_tipo_cf = "C";

        //vuol dire che sono clienti
        $_conto = sprintf("%s%s", $MASTRO_CLI, $_codconto);
    }
    elseif ($_tipo_cf == "F")
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


//Fissiamo il numero di righe per pagina...
    $rpp = "44";

    //mi prendo l'anno..

    $_anno = substr($_start, "0", "4");
    //echo $_anno;
    //provo a prendermi il saldo precedente
    $query = "SELECT *, SUM(dare), SUM(avere), date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont, data_cont AS data from prima_nota where conto = '$_conto ' AND data_cont >= '$_anno-01-01' AND data_cont < '$_start' ORDER BY data ASC, nreg ";

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



    $query = "SELECT *, date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont, data_cont AS data from prima_nota where conto = '$_conto ' AND data_cont >= '$_start' AND data_cont <= '$_end' ORDER BY data ASC, nreg ";
//cerco il numero di righe

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
    $righe = $result->rowCount();

    //inserisco il numero di righe per pagina
    $_pagine = $righe / $rpp;
    //arrotondo per eccesso
    $pagina = ceil($_pagine);

    if ($_tipo == "pdf")
    {

        //includiamo le librerie pdf..
        //
	
	//creaimo il file
        crea_file_pdf($_cosa, $_orientamento, "scheda contabili");

        for ($_pg = 1; $_pg <= $pagina; $_pg++)
        {

            crea_pagina_pdf();

            //intestazione
            crea_intestazione_ditta_pdf("schede_contabili", "", $_anno, $_pg, $pagina, $_parametri);


            intesta_tabella($_cosa, $_codconto, $_descrizione, $data);

            $_return = corpo_tabella($_cosa, $res2, $rpp, $_return);

            calce_tabella($_cosa, $_return['dare'], $_return['avere'], $_return['saldo'], "");
        }


        //chiudiamo il files..

        chiudi_files("scheda_contabile", "../..", "I");
    }
    else
    {

        //INIZIO PARTE VISIVA..
        //carichiamo la base delle pagine:
        base_html_stampa("chiudi", $_parametri);

//carichiamo la testata del programma.
#testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

        for ($_pg = 1; $_pg <= $pagina; $_pg++)
        {

//selezioniamo il singolo per sapere cosa è ed apriamo una tabella..

            echo "<table align=\"center\" width=\"95%\" border=\"0\ class=\"table\" style=\"page-break-inside: avoid;\">\n";
            echo "<tr><td align=\"left\" colspan=\"6\"><h3>$azienda</h3></td></tr>\n";
            echo "<tr><td align=\"left\" colspan=\"3\">$cap $citta $prov</td>\n";
            echo "<td align=\"right\" colspan=\"3\">Pag. $_pg / $pagina</td></tr>\n";

            echo "<tr><td align=\"center\" colspan=\"6\"><h3>Estratto conto contabile</h3></td></tr>\n";
            echo "<tr><td align=\"center\" colspan=\"6\"><h3>$_codconto - $_descrizione</h3></td></tr>\n";
            echo "<tr><td align=\"center\" colspan=\"6\"><br>Dalla Data $_start  Alla data $_end</td></tr>\n";


            echo "<tr>";
            echo "<td width=\"30\" align=\"center\" class=\"tabella\">N. Reg</span></td>\n";
            echo "<td width=\"50\" align=\"center\" class=\"tabella\">Data cont.</span></td>\n";
            echo "<td width=\"30\" align=\"center\" class=\"tabella\">N. proto</span></td>\n";
            echo "<td width=\"380\" align=\"CENTER\" class=\"tabella\">Descrizione</span></td>\n";
            echo "<td width=\"60\" align=\"center\" class=\"tabella\">Dare</span></td>\n";
            echo "<td width=\"60\" align=\"center\" class=\"tabella\">Avere</span></td>\n";
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



            for ($_nr = 1; $_nr <= $rpp; $_nr++)
            {
                $dati = $result->fetch(PDO::FETCH_ASSOC);

                if (($dati['dare'] == "") AND ( $dati['avere'] == ""))
                {
                    echo "<tr>";
                    echo "<td width=\"30\" align=\"center\" class=\"tabella_elenco\">&nbsp;</span></td>\n";
                    echo "<td width=\"70\" align=\"center\" class=\"tabella_elenco\">&nbsp;</span></td>\n";
                    echo "<td width=\"30\" align=\"center\" class=\"tabella_elenco\">&nbsp;</span></td>\n";
                    echo "<td width=\"380\" align=\"CENTER\" class=\"tabella_elenco\">&nbsp;</span></td>\n";
                    echo "<td width=\"60\" align=\"center\" class=\"tabella_elenco\">&nbsp;</span></td>\n";
                    echo "<td width=\"60\" align=\"center\" class=\"tabella_elenco\">&nbsp;</span></td>\n";
                    echo "<td width=\"90\" align=\"center\" class=\"tabella_elenco\">&nbsp;</span></td>\n";
                    echo "</tr>";
                }
                else
                {
                    echo "<tr>";
                    printf("<td align=\"center\" class=\"tabella_elenco\"><b>%s</b></span></td>\n", $dati['nreg']);
                    printf("<td align=\"center\" class=\"tabella_elenco\">%s</span></td>\n", $dati['data_cont']);
                    printf("<td align=\"center\" class=\"tabella_elenco\"><b>%s/$dati[suffix_proto]</b></span></td>\n", $dati['nproto']);
                    printf("<td align=\"left\" class=\"tabella_elenco\">%s</span></td>\n", $dati['descrizione']);

                    if ($dati['dare'] == "0.00")
                    {
                        $dati['dare'] = "&nbsp;";
                    }
                    if ($dati['avere'] == "0.00")
                    {
                        $dati['avere'] = "&nbsp;";
                    }

                    printf("<td align=\"right\" class=\"tabella_elenco\">%s</span></td>\n", $dati['dare']);
                    printf("<td align=\"right\"class=\"tabella_elenco\" >%s</span></td>\n", $dati['avere']);

                    $_dare = $_dare + $dati['dare'];
                    $_avere = $_avere + $dati['avere'];
                    $_saldo = $_saldo + $dati['dare'] - $dati['avere'];

                    if ($_saldo > "0.00")
                    {
                        $_scritta_p = "D";
                    }
                    else
                    {
                        $_scritta_p = "A";
                    }

                    echo "<td align=\"right\"class=\"tabella_elenco\">" . number_format($_saldo, '2') . " $_scritta_p</span></td>\n";

                    echo "</tr></form>";
                }
            }
            if ($_saldo > "0.00")
            {
                $_scritta = "Dare";
            }
            else
            {
                $_scritta = "Avere";
            }

            echo "<tr><td colspan=\"7\"><hr></td></tr>\n";
            echo "<tr>\n";
            echo "<td colspan=\"4\" align=\"right\" class=\"tabella_elenco\">&nbsp;</span></td>\n";
            echo "<td align=\"right\" class=\"tabella_elenco\">$_dare</span></td>\n";
            echo "<td align=\"right\" class=\"tabella_elenco\">$_avere</span></td>\n";
            echo "<td align=\"right\" class=\"tabella_elenco\">&nbsp;</span></td></tr>\n";
            echo "<tr><td colspan=\"7\"><hr></td></tr>\n";
            printf("<tr><td colspan=\"7\" align=\"right\" sclass=\"tabella_elenco\">Totale Saldo = %s %s</span></td></tr>", number_format($_saldo, '2'), $_scritta);

            echo "</table>\n";
        }


        echo "</body></html>";
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>