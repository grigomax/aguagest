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

    if ($CONTABILITA == "SI")
    {
        require "../../../setting/par_conta.inc.php";
        require "../../librerie/motore_primanota.php";
    }

//controllo che i campi siano pieni..

    if (($_POST['data'] == "") OR ( $_POST['check'] == ""))
    {
        echo "<h3> Attenzione non &egrave; stato selezionato un campo obbligatorio </h3>\n";
        exit;
    }


// Impostiamo l'anno di utilizzo
//$_annoeff = cambio_data("anno_it", $_POST['data']);
    $_datapag = cambio_data("us", $_POST['data']);

    $_anno = date('Y'); //prendiamo l'anno in corso

    $_numero = $_POST['check'];

// echo $_ndoc;
// ok per ogni articolo mi prendo tutti i dati dell'articolo
// echo $_codice;
    foreach ($_numero as $_composto)
    {
        //recupero le prime 4 cifre del numero passato cosi ho l'anno
        $_annoeff = substr($_composto, 0, 4);
        $_numeff = substr($_composto, 4, 6);

        if ($CONTABILITA == "SI")
        {
            
            //leggiamo l'effetto da lavorare..
            
            $dati_eff = tabella_effetti("singola", $_percorso, $_annoeff, $_numeff, $_parametri);
            
            
            // direi prenotiamo un numero di contabilità

            $_nreg = tabella_primanota("ultimo_numero", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

            //eseguiamo la registrazione tra la banca sbf ed il conto
            //il dare va alla banca c/c ed in avere il conto sbf
            //come causale ST, come titolo registrazione Accredito SBF + nome banca
            //prepariamo i dati da inserire..



            $_banca = tabella_banche("singola", $dati_eff['bancadist'], $_abi, $_cab, "");

            $_testo = "Accredito Effetti SBF $_banca[banca]";

            $_parametri['avere'] = "";
            $_parametri['segno'] = "P";
            $_parametri['ndoc'] = $dati_eff['numdoc'];
            $_parametri['anno_doc'] = $dati_eff['annodoc'];
            $_parametri['data_doc'] = $dati_eff['datadoc'];
            $_parametri['codpag'] = $dati_eff['modpag'];
            $_parametri['conto'] = "$MASTRO_BANCHE$dati_eff[bancadist]";
            $_parametri['desc_conto'] = $_banca['banca'];
            $_parametri['dare'] = $dati_eff['impeff'];

            //riapriamo il cliente in dare..
            $_result = tabella_primanota("inserisci_singola", $id, $_anno, $_nreg, "ST", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);

            $_piano = tabella_piano_conti("singola", $CONTO_EFFETTI_SBF . $dati_eff['bancadist'], $_parametri);

            //ora riapriamo la bana in avere..
            //m prima cancelliamo il dare
            $_parametri['dare'] = "";
            $_parametri['conto'] = $CONTO_EFFETTI_SBF . $dati_eff['bancadist'];
            $_parametri['desc_conto'] = $_piano['descrizione'];
            $_parametri['avere'] = $dati_eff['impeff'];

            $_result = tabella_primanota("inserisci_singola", $id, $_anno, $_nreg, "ST", $_testo, date('Y-m-d'), $_datapag, $_parametri, $_percorso);
        }


        // ora aggiorniamo l'effetto saldandolo ed inserendoci il numero di registrazione della contabilità
        $query = sprintf("UPDATE effetti SET status='saldato', datapag=\"%s\", conta_anno='$_anno', conta_nreg='$_nreg' WHERE numeff=\"%s\" and annoeff=\"%s\"", $_datapag, $_numeff, $_annoeff);

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);


            echo "<br>Errore... Effetto n. $_numeff Non Saldato\n";
        }
        else
        {
            echo "<br>Ok.. Effetto n. $_numeff Saldato con successo \n";
            
            if($CONTABILITA == "SI")
            {
                echo "registrazione nr $_nreg\n";
            }
        }
        
    } //chiudo foreach
// INIZIO PARTE VISIVA DELLA GENERAZIONE..

    echo "<h4> Se non appaiono errori a video la operazione &egrave; stata eseguita con successo</h4>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>