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

require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_primanota.php";
require "../../librerie/motore_anagrafiche.php";
//carichiamo la base delle pagine:
base_html($_cosa, $_percorso);
java_script($_cosa, $_percorso);
jquery_datapicker($_cosa, $_percorso);

echo "</head>";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

    $_periodo = $_POST['anno'] . "-" . $_POST['periodo'];

//mi prendo l'elenco dei mesi..
    $_datanuova = cambio_data("listamesi", $_data);
    $_azione = $_POST['azione'];
    $_datareg = cambio_data("us", $_POST['datareg']);
    $_anno_reg = cambio_data("anno_it", $_POST['datareg']);
    $_acconto = $_POST['acconto'];
    $_credito = $_POST['credito'];

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
    echo "<tr>";

    echo "<td>";

    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";


    if ($_azione == "liquida")
    {
        echo "<h3>Liquidazione iva $_POST[periodo]</h3>\n";

        //Dobbiamo primaleggere l'archivio liquidazioni..'
        //
		$_anno = substr($_POST['periodo'], -7, 4);
        $_periodo = substr($_POST['periodo'], 5, 2);

        $liquid = tabella_liquid_iva_periodica("singola", $_anno, $_periodo, $_parametri);

        //gestione_result($_cosa, $liquid, $_parametri);
        //ottimo ora facciamo le due scritture di contabilità..
        //------------------
        //dobbiamo passare dall dall'iva acquisti al conto erario'
        //Prima di iniziare leggiamo il numero di registrazione che tocca..
        $_nreg = tabella_primanota("ultimo_numero", $id, $_anno_reg, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

        //gestione_result($_cosa, $_nreg, $_parametri);
        //impostiamo le scritture..

        $_mese = $_datanuova[$_periodo];
        if ($_periodo == "13")
        {
            $_testo = "Versamento Acconto iva mese Dicembre $_anno ";
        }
        else
        {
            $_testo = "Versamento iva mese $_mese $_anno ";
        }

        $_parametri['segno'] = "P";
        $_parametri['conto'] = $CONTO_IVA_ERARIO;
        $_parametri['desc_conto'] = piano_conti($CONTO_IVA_ERARIO, "desc_singola");
        $_parametri['dare'] = $liquid['val_liquid'];

        //inseriamo la prima scrittura nella prima nota...
        $_result = tabella_primanota("inserisci_singola", $id, $_anno_reg, $_nreg, "ST", $_testo, $_datareg, $_datareg, $_parametri, $_percorso);


        //parametri contro conto..
        $_parametri['dare'] = "";

        if($_POST['banca'] == "AV")
        {
            $_banca['banca'] = "Avviso Bonario";
        }
        elseif($_POST['banca'] == "MC")
        {
            $_banca['banca'] = "Movimento di chiusura";
        }
        else
        {
            $_banca = tabella_banche("singola", $_POST['banca'], $_abi, $_cab, $_parametri);
        }
        

        //ora riapriamo la bana in avere..
        //m prima cancelliamo il dare
        $_parametri['dare'] = "";
        $_parametri['conto'] = "$MASTRO_BANCHE$_POST[banca]";
        $_parametri['desc_conto'] = $_banca['banca'];
        $_parametri['avere'] = $liquid['val_liquid'];

        //inseriamo la prima scrittura nella prima nota...
        $_result = tabella_primanota("inserisci_singola", $id, $_anno_reg, $_nreg, "ST", $_testo, $_datareg, $_datareg, $_parametri, $_percorso);


        echo "<br>Registrazione di contabilit&agrave; effettuata con il numero $_nreg";

        //ora aggiorniamo la tabella liquidazioni..

        echo "<br>Aggiornamento tabella liquidazioni\n";

        $_parametri = "";
        $_parametri['banca'] = $_POST['banca'];
        $_parametri['n_reg'] = $_nreg;
        $_parametri['data_vers'] = $_datareg;
        $_return = tabella_liquid_iva_periodica("aggiornamento_liquid", $_anno, $_periodo, $_parametri);

        if ($_return == "NO")
        {
            echo "<h2>Errore nell'inserimento</h2>\n";
            echo $_return[errori][descrizione];
            echo "procedura bloccata\n";
            exit;
        }
        else
        {
            echo "Liquidazione effettuata con successo.. !";
        }
    }
    else // CAMBIO DI FUNZIONE
    {

//due domande o ona.. ?
//megio due..
//acquisti
        $query = "select *, (SUM(dare) - SUM(avere)) AS acquisti from prima_nota where liquid_iva != 'SI' AND conto='$CONTO_IVA_ACQUISTI' and data_cont like '$_periodo%'";

        //eseguiamo la query
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

        $dati_acq = $result->fetch(PDO::FETCH_ASSOC);

//vendite
        $query = "select *, (SUM(avere) - SUM(dare)) AS vendite from prima_nota where liquid_iva != 'SI' AND conto='$CONTO_IVA_VENDITE' and data_cont like '$_periodo%'";
        //echo "<br> $query";
        //eseguiamo la query
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
        $dati_ven = $result->fetch(PDO::FETCH_ASSOC);

//selezioniamo i mesi..
        if ($_POST['periodo'] == "01")
        {
            $_per_vc = "12";
            $query = "SELECT * FROM liquid_iva_periodica where periodo = '12' and anno = '$_POST[anno]'-1 limit 1";
        }
        elseif ($_POST['periodo'] == "13")
        {
            $_per_vc = "12";
            $query = "SELECT * FROM liquid_iva_periodica where periodo = '12' and anno = '$_POST[anno]'-1 limit 1";
            //echo $query;
        }
        else
        {
            $_per_vc = $_POST['periodo'] - 1;
            $query = "SELECT * FROM liquid_iva_periodica where periodo = '$_POST[periodo]' -1 and anno = '$_POST[anno]' limit 1";
        }

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

        $dati_liquid = $result->fetch(PDO::FETCH_ASSOC);
//echo $dati_liquid['diff_periodo'];
//echo $dati_liquid['iva_acq'];
//dividiamo le funzioni...
        if ($_azione == "consolida")
        {

            //calcolo se devo versare..
            //vediamo se è l'acconto e poi lo registriamo nel database

            if ($_POST['periodo'] == "13")
            {
                // qui calcoliamo l'acconto iva del mese di dicemtre e lo scriviamo nella tabella.

                $_differenza = number_format((($dati_liquid['diff_periodo'] * 88) / 100), $dec, '.', '');

                $_parametri['val_liquid'] = $_differenza;
                $_differenza = "0.00";
                $_parametri['versato'] = "NO";
                $_parametri['cod_tributo'] = "6013";
            }
            else
            {
                //verifichiamo di non avere da considerare la liquidazione dell'anno scorso..
                if ($_POST['periodo'] == "12")
                {
                    //andiamoci a prendere l'acconto relativo all'anno già versato.

                    $query = "SELECT * FROM liquid_iva_periodica where periodo = '13' and anno = '$_POST[anno]' AND versato = 'SI' limit 1";

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

                    $dati_acc = $result->fetch(PDO::FETCH_ASSOC);
                }


                if ($_credito == "NO")
                {
                    $_differenza = ($dati_ven['vendite'] - $dati_acq['acquisti']);
                }
                elseif ($_acconto == "NO")
                {
                    $_differenza = ($dati_ven['vendite'] - $dati_acq['acquisti'] - abs($dati_liquid['cred_residuo']));
                }
                else
                {
                    $_differenza = ($dati_ven['vendite'] - $dati_acq['acquisti'] - $dati_acc['val_liquid'] - abs($dati_liquid['cred_residuo']));
                }


                //prima di tutto inseriamo i valori nell'archivio liquidazioni.
                //prepariamo i dati..
                $_parametri['iva_acq'] = $dati_acq['acquisti'];
                $_parametri['iva_vend'] = $dati_ven['vendite'];
                $_parametri['diff_periodo'] = $dati_ven['vendite'] - $dati_acq['acquisti'];
                $_parametri['cod_tributo'] = "60$_POST[periodo]";

                if ($_differenza < "0.00")
                {
                    $_parametri['cred_residuo'] = $_differenza;
                    $_differenza = "0.00";
                    $_parametri['val_liquid'] = $_differenza;
                    $_parametri['versato'] = "SI";
                }
                else
                {
                    $_parametri['val_liquid'] = $_differenza;
                    $_differenza = "0.00";
                    $_parametri['cred_residuo'] = $_differenza;
                    $_parametri['versato'] = "NO";
                }
            }//fine escusione 13 periodo

            $_return = tabella_liquid_iva_periodica("inserisci", $_POST['anno'], $_POST['periodo'], $_parametri);

            if ($_return['result'] == "NO")
            {
                echo "<h2>Errore nell'inserimento</h2>\n";
                echo $_return[errori][descrizione];
                echo "procedura bloccata\n";
                exit;
            }
            else
            {
                echo "<h4>Scrittura tabella liquidazione Effettuata</h4>\n";
            }

            //escludiamo le prossime procedure per la funzione acconto.

            if ($_POST['periodo'] != "13")
            {

                //ottimo ora facciamo le due scritture di contabilità..
                //------------------
                //dobbiamo passare dall dall'iva acquisti al conto erario'
                //Prima di iniziare leggiamo il numero di registrazione che tocca..
                $_nreg = tabella_primanota("ultimo_numero", $id, $_POST['anno'], $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

                //impostiamo le scritture..
                $_mese = $_datanuova[$_POST[periodo]];
                $_testo = "Giro conto iva acquisti mese $_mese";

                $_parametri['segno'] = "P";
                $_parametri['conto'] = $CONTO_IVA_ERARIO;
                $_parametri['desc_conto'] = piano_conti($CONTO_IVA_ERARIO, "desc_singola");
                $_parametri['dare'] = $dati_acq['acquisti'];

                //inseriamo la prima scrittura nella prima nota...
                $_result = tabella_primanota("inserisci_singola", $id, $_POST['anno'], $_nreg, "ST", $_testo, $_datareg, $_datareg, $_parametri, $_percorso);

                if ($_return['result'] == "NO")
                {
                    echo "<h2>Errore nell'inserimento</h2>\n";
                    echo $_return[errori][descrizione];
                    echo "procedura bloccata\n";
                    exit;
                }

                //parametri contro conto..
                $_parametri['dare'] = "";
                $_parametri['segno'] = "P";
                $_parametri['conto'] = $CONTO_IVA_ACQUISTI;
                $_parametri['desc_conto'] = piano_conti($CONTO_IVA_ACQUISTI, "desc_singola");
                $_parametri['avere'] = $dati_acq['acquisti'];

                //inseriamo la prima scrittura nella prima nota...
                $_result = tabella_primanota("inserisci_singola", $id, $_POST['anno'], $_nreg, "ST", $_testo, $_datareg, $_datareg, $_parametri, $_percorso);

                if ($_return['result'] == "NO")
                {
                    echo "<h2>Errore nell'inserimento</h2>\n";
                    echo $_return[errori][descrizione];
                    echo "procedura bloccata\n";
                    exit;
                }

                echo "<br>Registrazione di contabilit&agrave; effettuata con il numero $_nreg";

                //dobbiamo passare dall dall'iva Vendite al conto erario'
                //Prima di iniziare leggiamo il numero di registrazione che tocca..
                $_nreg = tabella_primanota("ultimo_numero", $id, $_POST['anno'], $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

                //impostiamo le scritture..
                $_mese = $_datanuova[$_POST[periodo]];
                $_testo = "Giro conto iva vendite mese $_mese";

                $_parametri['segno'] = "P";
                $_parametri['conto'] = $CONTO_IVA_ERARIO;
                $_parametri['desc_conto'] = piano_conti($CONTO_IVA_ERARIO, "desc_singola");
                $_parametri['avere'] = $dati_ven['vendite'];

                //inseriamo la prima scrittura nella prima nota...
                $_result = tabella_primanota("inserisci_singola", $id, $_POST['anno'], $_nreg, "ST", $_testo, $_datareg, $_datareg, $_parametri, $_percorso);

                if ($_return['result'] == "NO")
                {
                    echo "<h2>Errore nell'inserimento</h2>\n";
                    echo $_return[errori][descrizione];
                    echo "procedura bloccata\n";
                    exit;
                }

                //parametri contro conto..
                $_parametri['avere'] = "";
                $_parametri['segno'] = "P";
                $_parametri['conto'] = $CONTO_IVA_VENDITE;
                $_parametri['desc_conto'] = piano_conti($CONTO_IVA_VENDITE, "desc_singola");
                $_parametri['dare'] = $dati_ven['vendite'];

                //inseriamo la prima scrittura nella prima nota...
                $_result = tabella_primanota("inserisci_singola", $id, $_POST['anno'], $_nreg, "ST", $_testo, $_datareg, $_datareg, $_parametri, $_percorso);

                if ($_return['result'] == "NO")
                {
                    echo "<h2>Errore nell'inserimento</h2>\n";
                    echo $_return[errori][descrizione];
                    echo "procedura bloccata\n";
                    exit;
                }

                echo "<br>Registrazione di contabilit&agrave; effettuata con il numero $_nreg";

                //ecco se tutto è andato bene provvediamo a bloccare il le registrazioni..

                echo "<br>Blocco operazioni liquidate acquisti...";

                $query = "SELECT * FROM prima_nota WHERE conto='$CONTO_IVA_ACQUISTI' AND liquid_iva != 'SI' AND data_cont like '$_periodo%'";

                $result = $conn->query($query);
                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                    $return['result'] = "NO";
                }
                // Ora per ogni riga aggiorniamo le liquidazione
                foreach ($result AS $dati)
                {
                    $query = "UPDATE prima_nota set liquid_iva = 'SI' where nreg='$dati[nreg]' AND anno='$dati[anno]'";

                    $conn->exec($query);
                    if ($conn->errorCode() != "00000")
                    {
                        $_errore = $conn->errorInfo();
                        echo $_errore['2'];
                        //aggiungiamo la gestione scitta dell'errore..
                        $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                        scrittura_errori($_cosa, $_percorso, $_errori);
                        $return['result'] = "NO";
                    }
                }

                echo "<br>Blocco operazioni liquidate Vendite...";

                $query = "SELECT * FROM prima_nota WHERE conto='$CONTO_IVA_VENDITE' AND liquid_iva != 'SI' AND data_cont like '$_periodo%'";

                $result = $conn->query($query);
                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                    $return['result'] = "NO";
                }
                // Ora per ogni riga aggiorniamo le liquidazione
                foreach ($result AS $dati)
                {
                    $query = "UPDATE prima_nota set liquid_iva = 'SI' where nreg='$dati[nreg]' AND anno='$dati[anno]'";

                    $conn->exec($query);
                    if ($conn->errorCode() != "00000")
                    {
                        $_errore = $conn->errorInfo();
                        echo $_errore['2'];
                        //aggiungiamo la gestione scitta dell'errore..
                        $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                        scrittura_errori($_cosa, $_percorso, $_errori);
                        $return['result'] = "NO";
                    }
                }
            } // fine esclusione operazioni acconto.
            echo "<h4>Operazioni riuscite perfettamente.. </h4>\n";
        }
        else // fine operazione di consolidamento..
        {

//ORa facciamo i conti e poi li trascriviamo..  poi se l'utente dice che è Ok faccio i giroconti e li riporto nel conto dell'erario..
//l'iva da versare = all'iva vendite + iva versata - iva acquisti - iva deifferenza mese prima..
            echo "<form action=\"liquid_iva_period2.php\" method=\"POST\">\n";
            echo "<h4> Valori iva Riportati..</h4>\n";
            echo "<h4> <input type=\"radio\" name=\"anno\" value=\"$_POST[anno]\" checked>anno = $_POST[anno] mese = <input type=\"radio\" name=\"periodo\" value=\"$_POST[periodo]\" checked>$_POST[periodo]</h4>\n";

            // qui facciamo la differenza con il mese.. se è 13 vuo dire che è l'acconto dell'iva..

            if ($_POST['periodo'] == "13")
            {
                echo "Elaborazioni per l'acconto iva di Dicembre $_POST[anno] <br><br>\n";

                echo "Liquidazione Iva Dicembre anno " . ($_POST['anno'] - "1") . "  = $dati_liquid[diff_periodo]<br><br>\n";

                $_differenza = number_format((($dati_liquid['diff_periodo'] * 88) / 100), $dec, '.', '');

                echo "<b><font color=\"red\">Iva da versare entro il 27 $_datanuova[12] = $_differenza</font></b>\n";

                echo "<h4>Procedere alla creazione della registrazione  ?";
                echo "<h4>Una volta consolitata la liquidazione <br>Verrà creato un muovimento per la consolidazione</h4>\n";
            }
            else
            {

                echo "Iva vendite per il mese di " . $_datanuova[$_POST[periodo]] . "= $dati_ven[vendite]<br>";
                echo "Iva acquisti per il mese di " . $_datanuova[$_POST[periodo]] . " = $dati_acq[acquisti]<br>";
                echo "Iva Credito mesi precedenti $_datanuova[$_per_vc] = $dati_liquid[cred_residuo]<br>";


                $_differenza = ($dati_ven['vendite'] - $dati_acq['acquisti'] - abs($dati_liquid['cred_residuo']));



                $_per_vc = $_per_vc + 2;

                if ($_differenza < "0.00")
                {
                    $_differenza = "0.00";
                }

                echo "<b><font color=\"red\">Iva da versare entro il 16 $_datanuova[$_per_vc] = $_differenza</font></b></br>\n";


                if ($_POST['periodo'] == "12")
                {
                    //andiamoci a prendere l'acconto relativo all'anno già versato.

                    $query = "SELECT * FROM liquid_iva_periodica where periodo = '13' and anno = '$_POST[anno]' AND versato = 'SI' limit 1";

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

                    $dati_acc = $result->fetch(PDO::FETCH_ASSOC);

                    echo "Acconto iva versato =  $dati_acc[val_liquid]<br>\n";
                    $_differenza = $_differenza - $dati_acc['val_liquid'];
                    if ($_differenza < "0.00")
                    {
                        $_differenza = "0.00";
                    }
                    echo "<b>Iva da versare entro il 16 $_datanuova[$_per_vc] = $_differenza</b></br>\n";

                    echo "<br>In Caso di pagamento liquidazione senza considerare l'acconto <input type=\"checkbox\" name=\"acconto\" value=\"NO\"><br>\n";
                }

                echo "<br>In caso di liquidazione senza tener conto del credito <input type=\"checkbox\" name=\"credito\" value=\"NO\"><br>\n";

                echo "<h5>Procedere al passaggio definitivo della liquidazione di " . $_datanuova[$_POST[periodo]] . " ?</h5>";

                echo "<h5>Una volta consolitato la liquidazione tutti i muovimenti inerenti verranno bloccati !</h5>\n";
            }



            $_hoy = date('d-m-Y');
            //qua io metterei un blocco nel caso l'iva sia già stata liquidata..

            if (($dati_ven[vendite] != "0.00") AND ( $dati_acq[acquisti] != "0.00") OR ( $_POST['periodo'] == "13"))
            {
                echo "Inserisci la data per l'operazione <input type=\"text\" class=\"data\" name=\"datareg\" value=\"$_hoy\" size=\"11\" maxlength=\"10\">\n";
                echo "<br><br><input type=\"submit\" name=\"azione\" value=\"consolida\">\n";

                echo "</form>\n";
            }
            else
            {
                echo "<b>Iva periodica già liquidata</b>\n";
            }

            if ($_POST['periodo'] != "13")
            {
                //qui facciamo apparire le stampe a video..

                echo "<h5>Oppure procedi alla stampa..</h5>";
                echo "<a href=\"stampa_iva.php?azione=conto&periodo=$_POST[periodo]&anno=$_POST[anno]\" target=\"_blank\">Stampa riassunto mese su nuova finestra</a>\n";
                echo "<br><br><a href=\"stampa_iva.php?azione=pdf&periodo=$_POST[periodo]&anno=$_POST[anno]\" target=\"_blank\">Stampa file pdf su nuova finestra con la liquidazione</a>\n";
            }
        }
    }
    echo "<br><br></td></tr></table></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>