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


//genero il cambio documento
    $paga['1'] = "1 - Rimessa diretta";
    $paga['2'] = "2 - Contanti";
    $paga['3'] = "3 - Ricevuta bancaria";
    $paga['4'] = "4 - Tratta o cambiale";
    $paga['5'] = "5 - Contrassegno";
    $paga['6'] = "6 - Bonifico Bancario";
    $paga['7'] = "7 - Ricevimento Fattura";


// Visualizza documenti... EFFETTI
    echo "<html><body>";

    echo "<form action=\"maschera_eff.php\" method=\"GET\">\n";

//recupero le variabili

    $_anno = $_GET['anno'];
    if ($_GET['ndoc'] != "")
    {
        $_ndoc = $_GET['ndoc'];
    }
    else
    {
        $_ndoc = $_POST['ndoc'];
    }

    //selezioniamo l'effetto..
    $dati = tabella_effetti("singola", $_percorso, $_anno, $_ndoc, $_parametri);

    // prendo lo status del documento per eliminare i pulsanti sotto
    $_status = $dati['status'];

    //prendo il tipo di documento
    $_tdoc = $dati['tdoc'];
    //selezioniamo il cliente dall'anagrafica
    $dati2 = tabella_clienti("singola", $dati['codcli'], $_parametri);

    //selezioniamo il pagamento
    $datip = tabella_pagamenti("singola", $dati['modpag'], $_parametri);

    //selezioniamo la banca di appartenenza
    $datib = tabella_banche("singola", $dati['bancadist'], $_abi, $_cab, $_parametri);
    ?>
    <table border="1" align="center" width="100%">

        <tr>
            <td width="50%" bgcolor="#FFFFFF" valign="top" align="left">
                <i>Spett.le</i>&nbsp;<?php echo $dati['utente']; ?><br>
                <?php echo $dati2['ragsoc']; ?><br>
                <?php echo $dati2['indirizzo']; ?><br>
                <?php echo $dati2['cap']; ?>&nbsp; <?php echo $dati2['citta']; ?>&nbsp;(<?php echo $dati2['prov']; ?>)<br>
                <?php # $naz_cod = ord_field("dcodnazione"); naz_read(); naz_show("ddenominazione");   ?><br>
                P.I.&nbsp;<?php echo $dati2['piva']; ?>
            </td>
            <td width="50%" bgcolor="#ffFFFF" valign="top" align="left">
                <i><b><?php echo $dati['tipodoc']; ?></i></b><br>
                Num. <?php echo $dati['numdoc']; ?> / <?php echo $dati['annodoc']; ?> del <?php echo $dati['datadoc']; ?><br>
                <b>Banca </b><br>
                <?php echo $dati['bancapp']; ?><br>
                ABI <?php echo $dati['abi']; ?>  CAB <?php echo $dati['cab']; ?>  CIN <?php echo $dati['cin']; ?>  C/C <?php echo $dati['cc']; ?><br>
                <b>Pagamento </b><br> <?php echo $datip['descrizione'] ?><br>
                <b>Totale documento </b><br> <?php echo $dati['totdoc'] ?><br>
            </td>
        </tr>
    </table>
    <br>

    <table border="1" align="center" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td bgcolor="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>Tipo di effetto </i></font><br><?php echo $paga[$dati['tipoeff']]; ?>
            </td>
            <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Data Documento</i></font><br><font face="arial" size="3"><b><?php echo $dati['datareg']; ?></b></font></td>
            <?php
            echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"arial\" size=\"2\" valign=\"top\"><i>Documento N.</i></font><br><b><font face=\"arial\" size=\"3\">\n";
            echo "<input type=\"radio\" name=\"numeff\" value=\"$dati[numeff]\" checked >$dati[numeff]/<input type=\"radio\" name=\"annoeff\" value=\"$dati[annoeff]\" checked>$dati[annoeff]</b></font></td>\n";
            ?>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Scadenza</i><br><b><font face="arial" size="3"><?php echo $dati['scadeff']; ?></font></b></td>

            <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Importo effetto / spese</i><br><b><font face="arial" size="3"><?php echo $dati['impeff']; ?> / <?php echo $dati['spese']; ?></font></b></td>

            <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Status</i><br><b><font face="arial" size="3"><?php echo $dati['status']; ?></font></b></td>

        </tr>
        <tr>
            <td bgcolor="#FFFFFF" align="center" width="30%"><font face="arial" size="2" valign="top"><i>Distita n. </i><br><b><font face="arial" size="3"><?php echo $dati['ndistinta']; ?></font></b></td>

            <td bgcolor="#FFFFFF" align="center" width="35%"><font face="arial" size="2" valign="top"><i>Data Distinta</i><br><b><font face="arial" size="3"><?php echo $dati['datadist']; ?></font></b></td>

            <td bgcolor="#FFFFFF" align="center" width="35%"><font face="arial" size="2" valign="top"><i>Banca Presentazione</i><br><b><font face="arial" size="2"><?php echo $datib['banca']; ?></font></b></td>

        </tr>
    </table>

    <br>
    <center>
        <h3> Azioni Possibili </h3>
        <?php
        if (($_status == "inserito") or ( $_status == "in attesa") or ( $_status == "richiamato"))
        {
            $_SESSION['numeff'] = $dati['numeff'];
            $_SESSION['annoeff'] = $dati['annoeff'];
            $_SESSION['cliente'] = $dati['utente'];

            printf("<center><br><input type=\"submit\" name=\"azione\" value=\"Elimina\" onclick=\"if(!confirm('Confermi ELIMINAZIONE Effetto ?')) return false;\" >   <input type=\"submit\" name=\"azione\" value=\"Modifica\"></center>");
            echo "<br>";
        }
        if ($_status == "presentato")
        {
            $_SESSION['numeff'] = $dati['numeff'];
            $_SESSION['annoeff'] = $dati['annoeff'];
            $_SESSION['cliente'] = $dati['utente'];

            printf("<center><br><input type=\"submit\" name=\"azione\" value=\"Salda\"></center>");
            echo "<br>";
        }
        elseif (($_status == "insoluto") OR ( $_status == "riemesso") OR ( $_status == "in attesa") OR ( $_status == "parziale"))
        {
            echo "<table border=\"0\" align=\"center\" width=\"50%\">";
            echo "<center><br><input type=\"submit\" name=\"azione\" value=\"Modifica\"></center>\n";
            echo "<tr><td align=\"center\"><a href=\"stampa_avviso.php?ndoc=$_ndoc&anno=$_anno\" target=no ><img src=\"../../images/printer.png\"></br>Stampa Avviso </a></td>";
            echo "<td align=\"center\"><a href=\"stampa_avviso.php?ndoc=$_ndoc&anno=$_anno&azione=PDF\" target=no >\n";
            echo "<a href=\"stampa_avviso.php?ndoc=$_ndoc&anno=$_anno&azione=PDF\" target=no ><img src=\"../../images/email.png\" width=\"60\"></br>Invia avviso e-mail</a></td></tr></table>";
        }
        else
        {
            printf("<center> Documento saldato il <b>%s</b> Già contabilizzato.. ? $dati[contabilita], nreg $dati[conta_nreg] anno $dati[conta_anno]</center>", $dati['datapag']);
            if ($dati[contabilita] != "SI")
            {
                echo "<center><br><input type=\"submit\" name=\"azione\" value=\"Modifica\"></center>\n";
            }
        }
        ?>
        <form>
            <br>
            <p align="center">Torna all'indice documenti <input type="button" value="Annulla" onclick="location = 'annulladoc.php'"> </p>
        </form>
    </center>
    </body>
    </html>
    <?php
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>