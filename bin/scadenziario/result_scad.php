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
require "../librerie/motore_anagrafiche.php";

//carichiamo il discorso contabilità
if ($CONTABILITA == "SI")
{
    //carichiamo le librerie
    require "../../setting/par_conta.inc.php";
    require "../librerie/motore_primanota.php";
}


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

testata_html($_cosa, $_percorso);
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['scadenziario'] > "1")
{

    echo "<table align=\"left\" width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\">\n";

    echo "<h3 align=\"center\">Risultato Scadenze..</h3>";

    
    
    if($_POST['azione'] == "sposta")
    {
        // Impostiamo l'anno di utilizzo
        $_data_end = cambio_data("data_us", $_POST['data']);
        //$_data = $_POST['data'];

        $_numero = $_POST['numero'];
        
        //controllo che i campi siano pieni..
        if (($_POST['data'] == "") OR ( $_POST['numero'] == ""))
        {
            echo "<h3> Attenzione non &egrave; stato selezionato un campo obbligatorio </h3>\n";
            exit;
        }

        foreach ($_numero as $_annondoc)
        {
            //dobbiamo dividere i vari campi in quanto abbiamo anche l'anno..
            $_anno = substr($_annondoc, '0', '4');
            $_nscad = substr($_annondoc, '4', '6');
            
            //iniziamo a spostare le scadenze..
            $_parametri['nscad'] = $_nscad;
            $_parametri['anno'] = $_anno;
            $_parametri['data_end'] = $_data_end;
            $result = tabella_scadenziario("sposta", $_percorso, $_parametri);
            $_parametri="";
            if($result != "OK")
            {
                echo "<h5>Errore Scadenza n. $_nscad / $_anno</h5>\n";
            }
            else
            {
                echo "<h5>Scadenza $_nscad / $_anno Spostata Correttamente al $_POST[data]</h5>\n";
            }
            
        }
        
        echo "<h3 align=\"center\">Scadenze Spostate</h3>";
        exit;
    }
    
    
    
    if ($_POST['azione'] != "Annulla")
    {
//verifichiamo che i post obbligatori non siano vuoti..
        if (($_POST['data_scad'] == "") OR ( $_POST['descrizione'] == "") OR ( $_POST['importo'] == ""))
        {
            echo "<h3 align=\"center\">Attenzione uno dei campi obbligatori risulta vuoto</h3>";
            echo "<h5> Si prega di tornare indietro e verificare</h5>\n";
            exit;
        }

        if ($CONTABILITA == "SI")
        {
            if (($_POST['utente'] == "") AND ( ( $_POST['nproto'] == "") OR ( $_POST['nproto'] == "0")))
            {
                echo "<h3 align=\"center\">Attenzione per inserire una delega è obbligatorio il numero del conto</h3>";
                echo "<h5> Si prega di tornare indietro e verificare</h5>\n";
                exit;
            }
        }
    }
//iniziamo l'inserimento dei dati nell'archivio..
//tutti i parametri vanno inseriti nell'array parametri e passati alla funzione
    $_parametri['anno'] = $_POST['anno'];
    $_parametri['nscad'] = $_POST['nscad'];
    $_parametri['data_scad'] = cambio_data("us", $_POST['data_scad']);
    $_parametri['descrizione'] = addslashes($_POST['descrizione']);
    $_parametri['importo'] = $_POST['importo'];
    $_parametri['utente'] = $_POST['utente'];
    $_parametri['anno_doc'] = $_POST['anno_doc'];
    $_parametri['ndoc'] = $_POST['ndoc'];
    $_parametri['data_doc'] = cambio_data("us", $_POST['data_doc']);
    $_parametri['anno_proto'] = $_POST['anno_proto'];
    $_parametri['suffix_proto'] = $_POST['suffix_proto'];
    $_parametri['nproto'] = $_POST['nproto'];
    $_parametri['codpag'] = $_POST['codpag'];
    $_parametri['banca'] = $_POST['banca'];
    $_parametri['impeff'] = $_POST['impeff'];
    $_parametri['status'] = $_POST['status'];
    $_parametri['data_pag'] = cambio_data("us", $_POST['data_pag']);
    $_parametri['note'] = addslashes($_POST['note']);
    $_annopag = cambio_data("anno_it", $_POST['data_pag']);

    //richiamiamo l'anagrafica fornitori nel caso post



    if ($_POST['azione'] == "Inserisci")
    {
        $_risultato = tabella_scadenziario("inserisci", $_percorso, $_parametri);
        $_scritta = "<h5>Inserimento dati</h5>\n";
    }
    elseif ($_POST['azione'] == "Aggiorna")
    {

        if (($CONTABILITA == "SI") AND ( $_POST['primanota'] == "SI"))
        {

            //leggiamo l'effeto desiderato per vedere se è stato modificato
            $_scadenza = tabella_scadenziario("singola", $_percorso, $_parametri);


            //ci preleviamo l'anno
            $_anno = $_POST['anno'];

            //Prima di iniziare leggiamo il numero di registrazione che tocca..
            $_nreg = tabella_primanota("ultimo_numero", $id, $_annopag, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

            if (($_parametri['status'] == "saldato") AND ( $_scadenza['status'] != "saldato"))
            {
                //registriamo anche incontabilità quallo che è successo..
                // se fa l0insoluto in base alla banca presentato la riapriamo ..

                $_testo = "Pagamento $_parametri[descrizione]";

                //verifichiamo subito se ci sono spese...

                if (($_spese_insoluto != "0.00") AND ( $_spese_insoluto != ""))
                {
                    $_dare_for = $_parametri['impeff'] + $_spese_insoluto;
                }
                else
                {
                    $_dare_for = $_parametri['impeff'];
                }

                $_parametri['segno'] = "P";
                //$_parametri['ndoc'] = $dati_eff['numdoc'];
                //$_parametri['anno_doc'] = $dati_eff['annodoc'];
                //$_parametri['data_doc'] = $dati_eff['datadoc'];
                //$_parametri['codpag'] = $dati_eff['modpag'];

                $_parametri['dare'] = $_dare_for;

//echo "due";
                //riapriamo il cliente in dare..
                if ($_parametri['utente'] == "")
                {
                    $_parametri['conto'] = $_parametri['nproto'];
                    $_parametri['desc_conto'] = piano_conti($_parametri['nproto'], "desc_singola");
                    $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "ST", $_testo, date('Y-m-d'), $_parametri['data_pag'], $_parametri, $_percorso);
                }
                else
                {
                    //echo "ciao";
                    $dati = tabella_fornitori("singola", $_parametri['utente'], $_parametri);
                    $_parametri['conto'] = "$MASTRO_FOR$_parametri[utente]";
                    $_parametri['desc_conto'] = $dati['ragsoc'];
                    $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "PA", $_testo, date('Y-m-d'), $_parametri['data_pag'], $_parametri, $_percorso);
                }
                if ($_result['result'] == "NO")
                {
                    echo $_result['descrizione'];
                }
                else
                {
                    echo $_result;
                }

                if ($_parametri['banca'] == $CONTO_CASSA)
                {
                    $_parametri['conto'] = $CONTO_CASSA;
                    $_parametri['desc_conto'] = piano_conti($CONTO_CASSA, "desc_singola");
                }
                elseif ($_parametri['banca'] == $CONTO_ASSEGNI)
                {
                    $_parametri['conto'] = $CONTO_ASSEGNI;
                    $_parametri['desc_conto'] = piano_conti($CONTO_ASSEGNI, "desc_singola");
                }
                elseif ($_parametri['banca'] == $CONTO_COMPENSAZIONI)
                {
                    $_parametri['conto'] = $CONTO_COMPENSAZIONI;
                    $_parametri['desc_conto'] = piano_conti($CONTO_COMPENSAZIONI, "desc_singola");
                }
                else
                {
                    $_banca = tabella_banche("singola", $_parametri['banca'], $_abi, $_cab, "");
                    $_parametri['conto'] = "$MASTRO_BANCHE$_parametri[banca]";
                    $_parametri['desc_conto'] = $_banca['banca'];
                }

                //ora riapriamo la bana in avere..
                //m prima cancelliamo il dare
                $_parametri['dare'] = "";
                $_parametri['avere'] = $_dare_for;

                if ($_parametri['utente'] == "")
                {
                    $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "ST", $_testo, date('Y-m-d'), $_parametri['data_pag'], $_parametri, $_percorso);
                }
                else
                {
                    $_result = tabella_primanota("inserisci_singola", $id, $_annopag, $_nreg, "PA", $_testo, date('Y-m-d'), $_parametri['data_pag'], $_parametri, $_percorso);
                }

                if ($_result['result'] == "NO")
                {
                    echo $_result['descrizione'];
                }
                else
                {
                    echo $_result;
                }
            }

            //azzero i parametri..
            //$_parametri = "";
        }


        //fine modifica..   
        
        //vediamo se c'è da caricare un files..
        
        if($_FILES['file'] != "")
        {
            //echo "cuai maeda";
            //c'è il file..
            carica_file($_cosa, $_FILES, "pdf", "../../setting/fatture_acq/", "FA_".$_parametri[anno_proto].$_parametri[suffix_proto].$_parametri[nproto], ".pdf");
            
            
        }



        $_risultato = tabella_scadenziario("aggiorna", $_percorso, $_parametri);
        
        $_scritta = "<h5>Aggiornamento dati</h5>\n";
    }
    elseif ($_POST['azione'] == "Elimina")
    {
        $_parametri['campo1'] = "nscad";
        $_parametri['data_campo1'] = $_POST['nscad'];
        $_parametri['campo2'] = "anno";
        $_parametri['data_campo2'] = $_POST['anno'];
        $_risultato = tabella_scadenziario("elimina", $_percorso, $_parametri);
        $_scritta = "<h5>Eliminazioni Scadenza</h5>\n";
    }
    else
    {
        $_scritta = "<h5>Annullameto operazione come richiesto</h5>\n";
    }

//leggiamo gli errori..
// Esegue la query...
    if ($_risultato != "OK")
    {
        echo $_scritta;
        echo $_risultato['errori']['descrizione'];
    }
    else
    {
        echo $_scritta;
        echo "<h5><font color=\"green\">Ok. Operazione andata a buon Fine</font></h5>\n";

        echo "<h3>$_messaggio</h3>\n";
        if (($_POST['primanota'] == "SI") AND ( $_azione != "Annulla"))
        {
            echo "<h3>Operazione inserita in contabilit&agrave; con il numero $_nreg</h3>";
        }

        echo "</center>\n";
    }


    echo "</td></tr></table></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>