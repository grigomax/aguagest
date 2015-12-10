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


require $_percorso . "librerie/stampe.inc.php";
require $_percorso . "librerie/motore_anagrafiche.php";
require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_primanota.php";
require "../../librerie/stampe_pdf.php";


//prendiamoci i post
$_anno = $_POST['anno'];
$_azione = $_POST['azione'];
$_anno_liquid = $_POST['anno_liquid'];

//creiamo due funzioni veloci veloci da richiamare qui sotto..

function leggi_liquida($_cosa, $_anno, $_codiceiva)
{
    global $conn;
    global $CONTO_IVA_ACQUISTI;
    global $CONTO_IVA_VENDITE;

    //require_once '';
    if ($_cosa == "acq_imponibile")
    {
        $query = "SELECT * , (SUM( dare ) - SUM( avere ) ) AS valore FROM prima_nota WHERE iva = '$_codiceiva' AND conto != '$CONTO_IVA_ACQUISTI' AND causale='FA' AND data_cont LIKE '$_anno%'";
    }
    elseif ($_cosa == "acq_iva")
    {
        $query = "SELECT * , (SUM( dare ) - SUM( avere ) ) AS valore FROM prima_nota WHERE iva = '$_codiceiva' AND conto = '$CONTO_IVA_ACQUISTI' AND causale='FA' AND data_cont LIKE '$_anno%'";
    }
    elseif ($_cosa == "vend_imponibile")
    {
        $query = "SELECT * , (SUM( avere ) - SUM( dare ) ) AS valore FROM prima_nota WHERE iva = '$_codiceiva' AND conto != '$CONTO_IVA_VENDITE' AND causale='FV' AND data_cont LIKE '$_anno%'";
    }
    else
    {
        $query = "SELECT * , (SUM( avere ) - SUM( dare ) ) AS valore FROM prima_nota WHERE iva = '$_codiceiva' AND conto = '$CONTO_IVA_VENDITE' AND causale='FV' AND data_cont LIKE '$_anno%'";
    }

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore $_azione Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
        $dati = "errore";
    }
    else
    {
        foreach ($result AS $dati)
            ;
    }
    $_return = $dati;




    return $_return['valore'];
}

//inizio parte visiva

//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);


if ($_SESSION['user']['contabilita'] > "1")
{

    $_parametri['intestazione'] = "3";
  
 
    if ($_POST['azione'] == "Stampa_liquid")
    {

        $_parametri['tabella'] = "Tabella liquidazione Versamenti IVA $_anno";

        intestazione_html($_cosa, $_percorso, $_parametri);
        echo "&nbsp;<br>&nbsp;<br>\n";
        echo "<table align=\"center\" width=\"$PRINT_WIDTH\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">\n";

        echo "<tr><td align=\"center\">Mese</td><td align=\"center\">Iva a Debito</td><td align=\"center\">Liquidato</td><td>banca</td><td align=\"center\">ABI</td><td align=\"center\">CAB</td><td align=\"center\">Data Vers.</td></tr>\n";

        $result = tabella_liquid_iva_periodica("elenca_anno", $_anno, $_periodo, $_parametri);

        foreach ($result AS $row)
        {
            echo "<tr style=\"font-size:10pt;\"><td align=\"center\">$row[periodo]</td><td align=\"right\">$row[diff_periodo]</td>\n";

            if ($row['versato'] == "SI")
            {
                echo "<td align=\"right\">$row[val_liquid]</td>\n";

                $_versato = $_versato + $row['val_liquid'];
            }
            else
            {
                echo "<td align=\"right\">&nbsp;</td>\n";
            }

            $banca = tabella_banche("singola", $row['banca'], $_abi, $_cab, $_parametri);

            echo "<td>$banca[banca]</td><td>$banca[abi]</td><td>$banca[cab]</td><td align=\"right\">$row[versamento]</td></tr>\n";

            $_tot_differenze = $_tot_differenze + $row['diff_periodo'];
        }


        echo "<tr><td><b>TOTALI</b></td><td align=\"right\"><b>$_tot_differenze</b></td><td align=\"right\"><b>$_versato</b></td><td colspan=\"4\">&nbsp</td></tr>\n";

        echo "</table>\n";
    }
    else
    {

        $_parametri['tabella'] = "Liquidazione Annuale IVA $_anno";

        intestazione_html($_cosa, $_percorso, $_parametri);

        echo "<br>&nbsp;\n";

        echo "<table align=\"center\" width=\"$PRINT_WIDTH\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">\n";

        //creaiamo una tabella da 5 colonne per le operazioni di vendita

        echo "<tr><td colspan=\"5\" aling=\"left\"><font size=\"3\"><b>IVA VENDITE</b></font></td></tr>\n";

        echo "<tr><td align=\"center\">Cod. Iva</td><td align=\"center\">Descrizione</td><td align=\"center\">Aliq. %</td><td align=\"center\">Imponibile</td><td align=\"center\"> Imposta</td></tr>\n";

        //leggiamo la tabella iva e mostriamola..

        $result = tabella_aliquota("elenca_codice", $_codiva, $_percorso);

        foreach ($result AS $_row)
        {
            $_imponibile = leggi_liquida("vend_imponibile", $_anno, $_row['codice']);

            if ($_imponibile != "")
            {
                $_imposta = leggi_liquida("vend_imposta", $_anno, $_row['codice']);

                echo "<tr style=\"font-size:10pt;\"><td align=\"center\">$_row[codice]</td><td align=\"left\">$_row[descrizione]</td><td align=\"center\">$_row[aliquota]</td><td align=\"right\">$_imponibile</td><td align=\"right\">$_imposta</td></tr>\n";

                $_tot_imposta_vendite = $_tot_imposta_vendite + $_imposta;
                $_tot_imponibile_vendite = $_tot_imponibile_vendite + $_imponibile;
            }
            //azzeriamo le variabili 
            $_imponibile = "";
            $_imposta = "";
        }


        echo "<tr><td colspan=\"3\" align=\"center\"><b>TOTALI</b></td><td align=\"right\"><b>$_tot_imponibile_vendite</b></td><td align=\"right\"><b>$_tot_imposta_vendite</b></td></tr>\n";

        echo "</table>\n";

        echo "<br>&nbsp;\n";

        echo "<table align=\"center\" width=\"$PRINT_WIDTH\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">\n";


        echo "<tr><td colspan=\"5\" aling=\"left\"><font size=\"3\"><b>IVA ACQUISTI</b></font></td></tr>\n";
        echo "<tr><td align=\"center\">Cod. Iva</td><td align=\"center\">Descrizione</td><td align=\"center\">Aliq. %</td><td align=\"center\">Imponibile</td><td align=\"center\"> Imposta</td></tr>\n";

        $result = tabella_aliquota("elenca_codice", $_codiva, $_percorso);

        foreach ($result AS $_row)
        {
            $_imponibile = leggi_liquida("acq_imponibile", $_anno, $_row['codice']);

            if (($_imponibile != "") AND ( $_row['colonnacli'] != "1"))
            {
                $_imposta = leggi_liquida("acq_iva", $_anno, $_row['codice']);

                echo "<tr style=\"font-size:10pt;\"><td align=\"center\">$_row[codice]</td><td align=\"left\">$_row[descrizione]</td><td align=\"center\">$_row[aliquota]</td><td align=\"right\">$_imponibile</td><td align=\"right\">$_imposta</td></tr>\n";

                $_tot_imposta_acquisti = $_tot_imposta_acquisti + $_imposta;
                $_tot_imponibile_acquisti = $_tot_imponibile_acquisti + $_imponibile;
            }
            //azzeriamo le variabili 
            $_imponibile = "";
            $_imposta = "";
        }

        //partiamo con l'indetraibilità..
        $result = tabella_aliquota("elenca_codice_indetraibile", $_codiva, $_percorso);

        foreach ($result AS $_row)
        {
            $_imponibile = leggi_liquida("acq_imponibile", $_anno, $_row['codice']);

            if ($_imponibile != "")
            {
                if ($_row['colonnacli'] == "1")
                {
                    $_imposta = number_format(($_imponibile * ($_row['aliquota'] / 100)), $dec, '.', '');

                    $_imponibile = $_imponibile + $_imposta;
                }

                echo "<tr style=\"font-size:10pt;\"><td align=\"center\">$_row[codice]</td><td align=\"left\">$_row[descrizione]</td><td align=\"center\">$_row[aliquota]</td><td align=\"right\">$_imponibile</td><td align=\"right\">&nbsp;</td></tr>\n";

                #$_tot_imposta_acquisti = $_tot_imposta_acquisti + $_imposta;
                $_tot_imponibile_acquisti = $_tot_imponibile_acquisti + $_imponibile;
            }

            //azzeriamo le variabili 
            $_imponibile = "";
            $_imposta = "";
        }


        echo "<tr><td colspan=\"3\" align=\"center\"><b>TOTALI</b></td><td align=\"right\"><b>$_tot_imponibile_acquisti</b></td><td align=\"right\"><b>$_tot_imposta_acquisti</b></td></tr>\n";

        echo "</table>\n";
        //calcoliamo gli importi versati..

        $query = "SELECT * , SUM( val_liquid ) AS versati FROM liquid_iva_periodica WHERE versato='SI' AND anno = '$_anno'";
        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_azione Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati = "errore";
        }
        else
        {
            $dati_liq = $result->fetch(PDO::FETCH_ASSOC);
        }

        echo "<table width=\"$PRINT_WIDTH\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">\n";
        echo "<tr><td colspan=\"2\" align=\"center\"><h3>Prospetto finale IVA $_anno</h3></td></tr>\n";
        echo "<tr><td align=\"left\"><b>Totale IVA VENDITE anno $_anno</b></td><td align=\"right\"><b>" . number_format($_tot_imposta_vendite, $dec, ',', '.') . "</b></td></tr>\n";
        echo "<tr><td align=\"left\"><b>Totale IVA ACQUISTI anno $_anno</b></td><td align=\"right\"><b>" . number_format($_tot_imposta_acquisti, $dec, ',', '.') . "</b></td></tr>\n";
        echo "<tr><td align=\"left\"><b>Totale IVA a Devito anno $_anno</b></td><td align=\"right\"><b>" . number_format(($_tot_imposta_vendite - $_tot_imposta_acquisti), $dec, ',', '.') . "	</b></td></tr>\n";
        echo "<tr><td align=\"left\"><b>Versamenti Effettuati anno $_anno</b></td><td align=\"right\"><b>" . number_format($dati_liq['versati'], $dec, ',', '.') . "	</b></td></tr>\n";
        echo "<tr><td align=\"left\"><b>Residui da Versare anno $_anno</b></td><td align=\"right\"><b>" . number_format((($_tot_imposta_vendite - $_tot_imposta_acquisti) - $dati_liq['versati']), $dec, ',', '.') . "</b></td></tr>\n";

        echo "</table>\n";
    }

}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>