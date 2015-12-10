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
$conn = permessi_sessione("verifica", $_percorso);

require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_primanota.php";
require $_percorso . "librerie/motore_anagrafiche.php";
//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);





if ($_SESSION['user']['contabilita'] > "1")
{




    $id = session_id();
//funzione 

    if ($_POST['codconto'] == "")
    {
        if ($_POST['tipo_cf'] != "")
        {
            echo Showcodconto();
            die;
        }
    }

    base_html($_cosa, $_percorso);
    java_script($_cosa, $_percorso);

    jquery_menu_cascata($_cosa, $_percorso);


    $_azione = $_POST['azione'];
    $_cosa = substr($_POST['utente'], '0', '2');
    $_utente = substr($_POST['utente'], '2', '10');

//sistemiamo i posto..
//ripristinaimo solo le sessioni
    if ($_POST['azione'] != "Nuova")
    {
        $_data_reg = $_SESSION['datareg'];
        $_data_gior = $_SESSION['datagior'];
        $_anno = $_SESSION['anno'];
        $_causale = $_SESSION['causale'];

        if ($_POST['testo'] != "")
        {
            $_testo = $_POST['testo'];
            $_SESSION['testo'] = $_testo;
        }
        else
        {
            $_testo = $_SESSION['testo'];
        }
    }

    if ($_azione == "Nuova")
    {
//prendiamo ci i post
        //echo $_POST['causale'];
        //verifichiamo un po di parametri...
        if (($_POST['datareg'] == "") OR ( $_POST['datagior'] == "") OR ( $_POST['causale'] == "0"))
        {
            echo "<h2 align=\"center\">Attenzione uno dei campi obbligatori non &egrave; stato inserito</h2>\n";
            echo "<h3 align=\"center\">Data Registrazione = $_POST[datareg]</h3>\n";
            echo "<h3 align=\"center\">Data Contabile = $_POST[datagior]</h3>\n";
            echo "<h3 align=\"center\">Causale = $_POST[causale]</h3>\n";
            echo "<h3 align=\"center\">Tornare indietro e verificare</h3>\n";
            exit;
        }
        else
        {
            //controlliamo la data registrzione
            $_checkdate = verifica_data($cosa, $_POST['datareg']);


            if ($_checkdate['errore'] == "error")
            {
                echo "<h3 align=\"center\">Errore nella data registrazione</h3>\n";
                echo $_checkdate['descrizione'];
                echo "<h3 align=\"center\">Tornare indietro e verificare</h3>\n";
                $_checkdate = "";
                exit;
            }

            //controlliamo la data contabile
            $_checkdate = verifica_data($cosa, $_POST['datagior']);
            if ($_checkdate['errore'] == "error")
            {
                echo "<h3 align=\"center\">Errore nella data contabile</h3>\n";
                echo $_checkdate['descrizione'];
                echo "<h3 align=\"center\">Tornare indietro e verificare</h3>\n";
                $_checkdate = "";
                exit;
            }


            $_data_reg = $_POST['datareg'];
            $_data_gior = $_POST['datagior'];
            $_causale = $_POST['causale'];
            $_SESSION['datareg'] = $_data_reg;
            $_SESSION['datagior'] = $_data_gior;
            $_SESSION['causale'] = $_causale;

            //recuperiamo l'anno e passiamo ad una sessione
            $_anno = cambio_data("anno_it", $_data_reg);

            $_SESSION['anno'] = $_anno;

            if (($_causale == "ST") AND ( $_POST['utente'] == "EL"))
            {
                $_testo = "Inserisci la descrizione";

                //passiamo la selezione dei campi l tipo di causale che è nell'elenco

                $dati = causali($_POST['ndoc'], "singolo");

                $_testo = $dati['descrizione'];
                $_SESSION['testo'] = $_testo;

                $_finestra = "causale";
                $_parametri = $dati;
            }
            elseif ($_causale == "FA")
            {
                if (($_POST['totdoc'] == "") OR (($_POST['utente'] == "") AND ($_POST['piva'] == "")) OR ( $_POST['segno'] == "") OR ( $_POST['iva'] == ""))
                {
                    echo "<h2>Attenzione uno dei campi obbligatori non &egrave; stato inserito</h2>\n";
                    echo "<h3 align=\"center\">Tornare indietro e verificare</h3>\n";
                    exit;
                }
                else
                {
                    //recuperiamo i post aggiutivi
                    
                    if($_POST['utente'] == "")
                    {
                        $_SESSION['utente'] = tabella_fornitori("singolo_piva", $_POST['piva'], "");
                    }
                    else
                    {
                        $_SESSION['utente'] = tabella_fornitori("singolo", $_POST['utente'], "");
                    }
                    $_totdoc = $_POST['totdoc'];
                    if ($_POST['segno'] == "N")
                    {
                        $_testo = "Ric. Nota Credito n. ";
                    }
                    else
                    {
                        $_testo = "Fattura Acquisto n. ";
                    }
                    $_finestra = "Nuova";
                    $_SESSION['testo'] = $_testo;
                    $_parametri = "FA";

                    //impostiamo le sessioni nuove
                    $_SESSION['totdoc'] = $_totdoc;

                    //Selezioniamo il fornitore e lo passiamo alla variabile
                    

                    //impostiamo la sessione con i parametri dell'operazione
                    $_SESSION['parametri']['segno'] = $_POST['segno'];
                    $_SESSION['parametri']['iva'] = $_POST['iva'];
                    $_SESSION['parametri']['iva_ded'] = $_POST['iva_ded'];
                }
            }
            elseif ($_causale == "PA")
            {
                if (($_POST['utente'] == "") OR ( $_POST['ndoc'] == ""))
                {
                    echo "<h2>Attenzione uno dei campi obbligatori non &egrave; stato inserito</h2>\n";
                    echo "<h3 align=\"center\">Utente = $_POST[utente]</h3>\n";
                    echo "<h3 align=\"center\">N. documento = $_POST[ndoc]</h3>\n";
                    echo "<h3 align=\"center\">Tornare indietro e verificare</h3>\n";
                    exit;
                }
                else
                {
                    //recuperiamo i post aggiutivi
                    $_finestra = "Nuova";
                    $_parametri['campo1'] = "nproto";
                    $_parametri['campo2'] = "anno_proto";
                    //splitiamo il numero del documento per prenderci anche l'anno del protocollo
                    //sappiano che l'anno è dic 4 cifre'

                    $_parametri['anno_proto'] = substr($_POST['ndoc'], 0, 4);
                    $_parametri['campo1_data'] = substr($_POST['ndoc'], 4, 10);
                    $_parametri['conto'] = $_utente;

                    //selezioniamo la registrazione ela passiamo ad una sessione..
                    $_SESSION['registrazione'] = tabella_primanota("leggi_PA", $id, $_anno, $_nreg, "PA", $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    //elimino il mastro e prendo il fornitore
                    $_fornitore = substr($_utente, '2', '10');
                    //Selezioniamo il fornitore e lo passiamo alla variabile
                    $_SESSION['utente'] = tabella_fornitori("singolo", $_fornitore, "");
                    $_parametri = "PA";

                    $_testo = "Pagamento fatt. forn. n. " . $_SESSION['registrazione']['ndoc'] . " - " . $_SESSION['utente']['ragsoc'];
                    $_SESSION['testo'] = $_testo;
                }
            }
            elseif ($_causale == "IN")
            {
                if (($_POST['utente'] == "") OR ( $_POST['ndoc'] == ""))
                {
                    echo "<h2>Attenzione uno dei campi obbligatori non &egrave; stato inserito</h2>\n";
                    echo "<h3 align=\"center\">Utente = $_POST[utente]</h3>\n";
                    echo "<h3 align=\"center\">N. documento = $_POST[ndoc]</h3>\n";
                    echo "<h3 align=\"center\">Tornare indietro e verificare</h3>\n";
                    exit;
                }
                else
                {
                    //recuperiamo i post aggiutivi
                    $_finestra = "Nuova";
                    $_parametri['campo1'] = "ndoc";
                    $_parametri['campo2'] = "anno_doc";
                    $_parametri['anno_doc'] = substr($_POST['ndoc'], 0, 4);
                    $_parametri['campo1_data'] = substr($_POST['ndoc'], 4, 10);
                    $_parametri['conto'] = $MASTRO_CLI . $_utente;

                    //selezioniamo la registrazione ela passiamo ad una sessione..
                    $_SESSION['registrazione'] = tabella_primanota("leggi_PA", $id, $_anno, $_nreg, "IN", $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                    //elimino il mastro e prendo il fornitore
                    //$_fornitore = substr($_utente, '2', '10');
                    //Selezioniamo il fornitore e lo passiamo alla variabile
                    $_SESSION['utente'] = tabella_clienti("singolo", $_utente, "");
                    $_parametri = "IN";

                    $_testo = "Incasso Fatt. n. " . $_SESSION['registrazione']['ndoc'] . " - " . $_SESSION['utente']['ragsoc'];
                    $_SESSION['testo'] = $_testo;
                }
            }
            else
            {
                $_testo = "Inserisci la descrizione";
                $_finestra = "ST";
            }
        }
    }
    elseif ($_azione == "Inserisci")
    {
        //echo $_POST['tipo_cf'];
        if(($_POST['codconto'] == "0") OR ($_POST['tipo_cf'] == ""))
        {
            echo "<h3 align=\"center\">impossibile proseguire in quanto il codice conto non è stato selezionato</h3>\n";
            exit;
        }
        
        if(($_POST['dare'] == "0.00") AND ($_POST['avere'] == "0.00"))
        {
            echo "<h3 align=\"center\">Impossibile proseguire in quanto il valore dare o avere risulta uguale a 0</h3>\n";
            exit;
        }
        
        $_segno = $_SESSION['parametri']['segno'];
        $_iva = $_SESSION['parametri']['iva'];
        carrello_primanota("Inserisci", $_anno, "", $_POST['tipo_cf'], $_POST['codconto'], $_POST['dare'], $_POST['avere'], $_segno, $_POST['iva']);
        $_finestra = "elenco_primanota";
    }
    elseif ($_azione == "nuovariga")
    {
        #java_script("");
        $_tipo_cf = $_POST['tipo_cf'];
        $_finestra = "nuovariga";
        
    }
    elseif ($_azione == "Modifica")
    {
        $_anno = $_POST['anno'];
        $_nreg = $_POST['nreg'];
        $_result = tabella_primanota("basket", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

        if ($_result['errori']['errore'] == "errore")
        {
            echo "<h2>Errore nell'inserimento " . $_result['errori']['descrizione'] . "</h2>\n";
            exit;
        }
        else
        {
            $dati = carrello_primanota("leggi", $_anno, "1", $_tipo_cf, $_codconto, $_dare, $_avere, $_segno, $_iva);

            //impostiamo le sessioni

            $_data_reg = $dati['data_reg'];
            $_data_gior = $dati['data_cont'];
            $_causale = $dati['causale'];
            $_testo = $_result['errori']['testo'];
            $_SESSION['datareg'] = $_data_reg;
            $_SESSION['datagior'] = $_data_gior;
            $_SESSION['causale'] = $_causale;
            $_SESSION['anno'] = $_anno;
            $_SESSION['nreg'] = $_nreg;
            $_SESSION['submit'] = $_azione;
            $_SESSION['testo'] = $_testo;
            $_SESSION['parametri']['segno'] = $dati['segno'];
            $_SESSION['parametri']['iva'] = $dati['iva'];
            $_SESSION['parametri'] = $dati;
            $_SESSION['spesometro'] = $_result['spesometro'];

            if (($_causale == "FA") OR ( $_causale == "PA"))
            {
                //mi recupero il nome utente fornitore..
                //leggo le prime due colonne del codice se corrisponde al matro fornitori le tolgo poi chiedo tutti i dati del fornitore
                $_conto = substr($dati['conto'], '0', '2');

                if ($_conto == $MASTRO_FOR)
                {
                    $_conto = substr($dati['conto'], 2);

                    $_SESSION['utente'] = tabella_fornitori("singolo", $_conto, "");
                }
            }

            if (($_causale == "FV") OR ( $_causale == "IN"))
            {

                //mi recupero il nome utente fornitore..
                //leggo le prime due colonne del codice se corrisponde al matro fornitori le tolgo poi chiedo tutti i dati del fornitore
                $_conto = substr($dati['conto'], '0', '2');

                if ($_conto == $MASTRO_CLI)
                {
                    $_conto = substr($dati['conto'], 2);

                    $_SESSION['utente'] = tabella_clienti("singolo", $_conto, "");
                }
            }
        }

        //    giriamo le date cosi da farle apparire giuste..
        $_data_reg = cambio_data("it", $_data_reg);
        $_data_gior = cambio_data("it", $_data_gior);


        $_finestra = "elenco";
    }
    elseif ($_azione == "Elimina")
    {
        //mi prendo i post
        $_anno = $_POST['anno'];
        $_nreg = $_POST['nreg'];
        //Funzione di Eliminazione registrazione..

        echo "<body>\n";
        //carichiamo il menu a tendina..
        menu_tendina($_cosa, $_percorso);
        echo "<center>\n";
        echo "<h2>Registrazione di prima nota</h2>\n";


        $_result = tabella_primanota("elimina_reg", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

        if ($_result != "true")
        {

            echo "<h2>Errore nella cancellazione della registrazione</h2>\n";
            echo $_return[errori][descrizione];
        }
        else
        {

            echo "<h2 align=\"center\">Eliminazione Registrazione</h2>\n";
            echo "<h3 align=\"center\">Riuscita</h2>\n";
        }

        //elimino tutte le sessioni
        elimina_sessioni();
        exit;
    }
    elseif ($_azione == "modifica")
    {
        //mandiamo la finestra modifica..
        $_finestra = "modifica";
        $_parametri = $_POST['rigo'];
    }
    elseif ($_azione == "elimina")
    {
        carrello_primanota("elimina", $_anno, $_POST['rigo'], $_POST['tipo_cf'], $_POST['codconto'], $_POST['dare'], $_POST['avere'], $_segno, $_iva);
        $_finestra = "elenco_primanota";
    }
    elseif ($_azione == "Aggiorna")
    {
        
        if(($_POST['dare'] == "0.00") AND ($_POST['avere'] == "0.00"))
        {
            echo "<h3 align=\"center\">Impossibile proseguire in quanto il valore dare o avere risulta uguale a 0</h3>\n";
            exit;
        }
        
        $_segno = $_SESSION['parametri']['segno'];

        carrello_primanota("Aggiorna", $_anno, $_POST['rigo'], "", "", $_POST['dare'], $_POST['avere'], $_segno, $_POST['iva']);
        $_finestra = "elenco_primanota";
    }
    elseif ($_azione == "Annulla")
    {
        //annullamento documento..

        annulla_doc($id);

        echo "<body>\n";
                //carichiamo il menu a tendina..
        menu_tendina($_cosa, $_percorso);
        echo "<center>\n";
        echo "<h2>Registrazione di prima nota</h2>\n";
        echo "<h2>Registrazione annullata con successo</h2>\n";


        exit;
    }
    else
    {
        $_finestra = "elenco";
    }

    //aggiorna_testo.php?nome_variabile_sessione=testo&valore_variabile_sessione=valore
    ?>

    <script>

        function session_aggiorna_testo(sessione, valore) {
            $.ajax({
                type: "POST",
                url: 'aggiorna_testo.php?nome_variabile_sessione=' + sessione + '&valore_variabile_sessione=' + valore,
                contentType: "text/html; charset=utf-8",
                dataType: "html",
                success: function (response) {

                    alert('Aggiornamento Descrizione Movimento')


                },
                error: function () {
                    alert('Impossibile Caricale applicazione keepalive');

                }
            });
        }
    </script>




    <?php

    echo "<body>\n";
    echo "<center>\n";
    echo "<h2>Registrazione di prima nota</h2>\n";

    echo "<table width=\"90%\" border=\"0\">\n";
    echo "<tr>\n";
    echo "<td colspan=\"4\">Data registrazione $_data_reg</td><td colspan=\"3\">Data Contabile $_data_gior</td></tr>\n";
    echo "<form action=\"corpo_nota.php\" method=\"POST\"><tr><td colspan=\"7\">Descrizione Movimento  <input type=\"text\" size=\"82\" maxlength=\"100\" name=\"testo\" value=\"$_testo\" onchange=\"session_aggiorna_testo('testo', this.value)\"> <input type=\"submit\" name=\"azione\" value=\"nuovariga\"></td></tr>\n";
    echo "</form>";
    echo "<tr><td colspan=\"7\"><hr></td></tr>\n";

    $_return = schermate_primanota($_finestra, $_parametri);

    $_sbilanciamento = $_return['sbilanciamento']['dare'] - $_return['sbilanciamento']['avere'];


#if ($_return['finestra'] != "")
#{
#    $_azione = "";
#}


    echo "<tr><td colspan=\"7\"><hr></td></tr>\n";

    @$_sbilanciamento = number_format($_sbilanciamento, $decdoc);


    if (($_sbilanciamento == "0.00") AND ( ($_azione == "modifica") OR ( $_azione == "nuovariga") OR ( ($_azione == "Nuova") AND ( $_causale != "FA"))))
    {
        echo "<tr><td colspan=\"7\" align=\"right\"><font color=\"red\"> Sblilanciamento = $_sbilanciamento</font></td></tr>\n";
    }
    elseif (($_sbilanciamento != "0.00") AND ( ($_azione == "modifica") OR ( $_azione == "Aggiorna") OR ( $_azione == "nuovariga") OR ( $_azione == "Inserisci") OR ( $_azione == "elimina")))
    {
        echo "<tr><td colspan=\"7\" align=\"right\"><font color=\"red\"> Sblilanciamento = $_sbilanciamento</font></td></tr>\n";
    }
    else
    {
        echo "<tr><td colspan=\"7\" align=\"right\"><font color=\"red\"> Sblilanciamento = $_sbilanciamento</font></td></tr>\n";
        echo "<tr><td colspan=\"7\" align=\"right\"><a href=\"calce_nota.php\">Continua..</a></td></tr>\n";
    }
    echo "<form action=\"corpo_nota.php\" method=\"POST\">\n";
    echo "<tr><td colspan=\"7\" align=\"center\"><input type=\"submit\" value=\"Annulla\" name=\"azione\" onclick=\"if(!confirm('Sicuro di voler Annullare la operazione ?')) return false;\" ></form>\n";
    echo "</table>\n";

    echo "</body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>