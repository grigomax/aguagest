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

require "../../librerie/motore_anagrafiche.php";
require "../../librerie/motore_doc_pdo.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

    printf("<form action=\"modificadoc.php\" method=\"POST\">");

//recupero le variabili
    $_tdoc = $_GET['tdoc'];
    $_anno = $_GET['anno'];
    $_suffix = $_GET['suffix'];
    if ($_GET['ndoc'] != "")
    {
        $_ndoc = $_GET['ndoc'];
    }
    else
    {
        $_anno = substr($_POST['annondoc'], "0", "4");
        $_suffix = substr($_POST['annondoc'], "4", "1");
        $_ndoc = substr($_POST['annondoc'], "5", "11");
    }
//echo $_suffix;
//echo $_ndoc;
// inizio la differenziazione la diferenziazione dei documenti
//convertiamo il nome documento
    $_archivio = archivio_tdoc($_tdoc);
//restitusco un arrey con il nome archivioo ed il nome
#$_archivioo['testacalce'] = $_testacalce;
#$_archivioo['dettaglio'] = $_dettaglio;
// Stringa contenente la query di ricerca... solo dei fornitori

    $dati_doc = seleziona_documento("seleziona_singolo", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

    if ($dati_doc['errori'] != "OK")
    {
        echo "<h2> Errore</h2>\n";
    }




    $_codutente = $dati_doc['utente'];
// prendo lo status del documento per eliminare i pulsanti sotto
    $_status = $dati_doc['status'];
    $_evasonum = $dati_doc['evasonum'];
    $_evasoanno = $dati_doc['evasoanno'];
    $_tdocevaso = $dati_doc['tdocevaso'];


//    inserisco all'interno del array una variabile'
    $dati_doc['tdoc'] = $_tdoc;
//selezioniamo l'utente'

    if (($_tdoc == "fornitore") or ( $_tdoc == "ddtacq"))
    {
        $dati_ute = tabella_fornitori("singola", $_codutente, $_parametri);
    }
    else
    {
        $dati_ute = tabella_clienti("singola", $_codutente, $_parametri);
    }


    schermata_visualizza("intestazione", $dati_ute, $dati_doc, "", "", "", "", "");

    echo "<br>\n";

    $_imponibile = schermata_visualizza("corpo", $dati_ute, $dati_doc, $_archivio, $_anno, $_suffix, $_ndoc, "");

    echo "<br>\n";

    schermata_visualizza("calce", $dati_ute, $dati_doc, $_archivio, $_anno, $_suffix, $_ndoc, $_imponibile);


    //inseriesco una doppia sicurezza suldocumento..
    if ($dati_doc['contabilita'] == "SI")
    {
        echo "<center><br>Nessuna Opzione possibile in quanto il seguente</center>\n";
        printf("<center>evaso con <a href=\"../effetti/visualizzadoc.php?anno=$dati_doc[evasoanno]&ndoc=$dati_doc[evasonum]&suffix=$dati_doc[evasosuffix]\"<b>$_tdocevaso</b> numero: %s del: %s </a>", $dati_doc['evasonum'], $dati_doc['evasoanno']);
        echo "<center><b>Ed oltretutto &egrave; stato registrato in contabilit&agrave;</b><center>\n";
    }
    else
    {
        if ($_status == "evaso")
        {
            echo "<center>Nessuna Opzione possibile in quanto il seguente</center>\n";
            if($_tdocevaso == "effetto")
            {
                printf("<center>evaso con <a href=\"../effetti/visualizzadoc.php?anno=$dati_doc[evasoanno]&ndoc=$dati_doc[evasonum]&suffix=$dati_doc[evasosuffix]\"<b>$_tdocevaso</b> numero: %s del: %s </a>", $dati_doc['evasonum'], $dati_doc['evasoanno']);
            }
            else
            {
                echo "<center>evaso con <a href=\"visualizzadoc.php?tdoc=$_tdocevaso&anno=$dati_doc[evasoanno]&ndoc=$dati_doc[evasonum]&suffix=$dati_doc[evasosuffix]\"<b>$_tdocevaso</b> numero: $dati_doc[evasonum]/$dati_doc[evasosuffix] del $dati_doc[evasoanno]</a>\n";
            }
            
        }
        elseif (($_status == "parziale") AND ( ($_tdoc == "conferma") OR ( $_tdoc == "fornitore")))
        {
            echo "<center>Attenzione a modificare questo documento in quanto risulta evaso parzialmente</center>";
            echo "<center> con <a href=\"visualizzadoc.php?tdoc=$_tdocevaso&anno=$dati_doc[evasoanno]&ndoc=$dati_doc[evasonum]&suffix=$dati_doc[evasosuffix]\"<b>$_tdocevaso</b> numero: $dati_doc[evasonum]/$dati_doc[evasosuffix] del $dati_doc[evasoanno]</a>\n";
            echo "<center><span class=\"azioni\"><br><b>Azioni possibili</b></center>\n";
            echo "<center><input type=\"submit\" name=\"azione\" value=\"Modifica\"></center>\n";
        }
        elseif ($_status == "saldato")
        {
            echo "<center><br>Nessuna Opzione possibile in quanto il seguente</center>\n";
            echo "<center>evaso con <a href=\"visualizzadoc.php?tdoc=$_tdocevaso&anno=$dati_doc[evasoanno]&ndoc=$dati_doc[evasonum]&suffix=$dati_doc[evasosuffix]\"<b>$_tdocevaso</b> numero: $dati_doc[evasonum]/$dati_doc[evasosuffx] del $dati_doc[evasoanno]</a>\n";
            echo "<center><b>Ed oltretutto &egrave; stato registrato in contabilit&agrave;</b><center>\n";
        }
        else
        {
            echo "<center><span class=\"azioni\"><br><b>Azioni possibili</b></center>\n";
            if ($_SESSION['user']['vendite'] == "4")
            {
                printf("<center><input type=\"submit\" name=\"azione\" value=\"Modifica\"> - <input type=\"submit\" name=\"azione\" value=\"Elimina\"></center>");
            }
            elseif ($_SESSION['user']['vendite'] == "3")
            {
                echo "<center><input type=\"submit\" name=\"azione\" value=\"Modifica\"></center>\n";
            }
            else
            {
                echo "<center>Non hai i permessi per poter modificare questo documento</center>\n";
            }
        }
    }
    echo "</form>\n";

    if ($_tdoc != "ddtacq")
    {
        if (($_tdoc == "ddt") OR ( $_tdoc == "$nomedoc"))
        {
            //diamo la possibilità dicambiare i dati della spedizione
            echo "<form action=\"calce.php?azione=spedizione&tdoc=$_tdoc&anno=$_anno&suffix=$_suffix&ndoc=$_ndoc\" method=\"POST\">\n";
            echo "<center><br>Modifica Dati spedizione ed inserisci <input type=\"submit\" value=\"ID_COLLO\"></center>\n";
            echo "</form>\n";
        }

        //annulla_doc_vendite($_dove);
//generiamo la maschera per stampare:
        echo "<center><br><span class=\"azioni\"><b>Per ristampare Questo documento..</b></center>\n";
        genera_maschera_stampe("../stampa_doc.php", "visualizza", $dati_doc);

//inseriamo la lingua ed i prezzi.
        print_prezzi($_tdoc);

        seleziona_lingue();

//chiudiamo con i pulsanti;
        genera_maschera_stampe($file_stampa, "pulsanti", "");
    }
}
else
{
    echo "<h2>Non hai i permessi per poter visualizzare il documento</h2>\n";
}

echo "</body></html>\n";
?>