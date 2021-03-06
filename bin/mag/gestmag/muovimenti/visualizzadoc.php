<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['magazzino'] > "1")
{

//	printf( "<form name=\"modifica\" action=\"modificadoc.php\" method=\"POST\">");
    //recupero le variabili
    $_tdoc = $_GET['tdoc'];
    $_anno = $_GET['anno'];
    $_suffix = $_GET['suffix'];
    $_ndoc = $_POST['ndoc'];
    $_magazzino = $_GET['magazzino'];


    // Stringa contenente la query di ricerca...
    $query = "SELECT * FROM $_magazzino where tdoc='$_tdoc' and suffix='$_suffix' and ndoc='$_ndoc' and anno='$_anno' ORDER BY rigo";

    $dati = domanda_db("query", $query, $_cosa, "fetch", $_parametri);

    if ($_tdoc == "ddtacq")
    {
        $query = sprintf("select * from fornitori where codice=\"%s\"", $dati['utente']);
    }
    else
    {
        $query = sprintf("select * from clienti where codice=\"%s\"", $dati['utente']);
    }

    $dati2 = domanda_db("query", $query, $_cosa, "fetch", $_parametri);
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
            </td>
        </tr>
    </table>
    <br>
    <table border="1" align="center" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td bgcolor="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>Tipo documento</i></font><br><center><b><?php echo $dati['tdoc']; ?> </b></center></td>
    <td bg color="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>Numero fattura fornitore</i></font><br><center><?php echo $dati['fatturacq']; ?></center>
    </td>
    <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Pagina</i><br><?php $_pag = 1;
    echo $_pag;
    ?></font></td>
    <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Documento N.</i></font><br><b><font face="arial" size="3"><?php echo
    $dati['ndoc'];
    ?>/<?php echo $dati['suffix']; ?> - <?php echo $dati['anno']; ?></b></font></td>  </tr>
    <tr>
        <td bg color="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>N. Protocollo iva</i></font><br><b><center><?php echo $dati['protoiva']; ?></b></font></td>  <td bg color="#FFFFFF" align="left"><font face="arial" size="1" valign="top"><i>Numero ddt fornitore </i></font><br><center><?php echo $dati['ddtfornitore']; ?></font></center></td>    <td bgcolor="#FFFFFF"></td>

    <td bgcolor="#FFFFFF" align="center"><font face="arial" size="2" valign="top"><i>Data Documento</i></font><br><font face="arial" size="3"><b><?php echo $dati['datareg']; ?></b></font></td>  </tr>
    </table>

    <br>

    <table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">

        <tr>
            <th bgcolor="#FFFFFF" width="80">Codice</th>
            <th bgcolor="#FFFFFF" width="600" align="left">Descrizione</th>
            <th bgcolor="#FFFFFF" >U.M.</th>
            <th bgcolor="#FFFFFF" >Quantit&agrave;</th>
            <th bgcolor="#FFFFFF" >Netto acquisto</th>
            <th bgcolor="#FFFFFF" >Tot. Riga</th>
        </tr>

        <?php
        $query = sprintf("select * from $_magazzino INNER JOIN articoli ON $_magazzino.articolo=articoli.articolo where tdoc=\"%s\" and anno=\"%s\" and ndoc=\"%s\" order by rigo", $_tdoc, $_anno, $_ndoc);

        $result = domanda_db("query", $query, $_cosa, $_ritorno, $_parametri);

        foreach ($result AS $dati3)
        {
            $_articolo = $dati3['articolo'];
            {
                if ($_articolo == "vuoto")
                    $_articolo = "&nbsp;";
            }

            if ($_tdoc == "ddtacq")
            {
                $query = sprintf("select * from fornitori where codice=\"%s\"", $dati['utente']);
                $_netto = $dati3['valoreacq'] / $dati3['qtacarico'];
                $_qta = $dati3['qtacarico'];
                $_valore = $dati3['valoreacq'];
                $_imponibile = $_imponibile + $dati3['valoreacq'];
            }
            else
            {
                $query = sprintf("select * from clienti where codice=\"%s\"", $dati['utente']);
                $_netto = $dati3['valorevend'] / $dati3['qtascarico'];
                $_qta = $dati3['qtascarico'];
                $_valore = $dati3['valorevend'];
                $_imponibile = $_imponibile + $dati3['valorevend'];
            }

            printf("<tr><td height=\"1\" align=\"center\" class=\"testo_blu\">%s&nbsp;</td>", $_articolo);
            printf("<td height=\"1\" align=\"left\" class=\"testo_blu\">%s</td>", $dati3['descrizione']);
            printf("<td height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $dati3['unita']);
            printf("<td height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $_qta);
            printf("<td height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $_netto);
            printf("<td height=\"1\" align=\"center\" class=\"testo_blu\">%s</td>", $_valore);
            printf("</tr>");
        }
        ?>

        <table border="1" width="100%">

            <br><br>
            <th colspan="2" bgcolor="#FFFFFF" width="50%" align="center"><i>Imponibile  </i><br><center><b><? echo $_imponibile; ?></center></b></th>
            <th colspan="1" bgcolor="#FFFFFF" width="50%" align="center">Imposta  <i><? echo $dati['totiva']; ?></i></th>
            <th colspan="2" bgcolor="#FFFFFF" width="50%" align="center">Tot. Documento  <i><? echo $dati['totdoc']; ?></i></th>
        </table>
    </table>
    </body>
    </html>

    <?php
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>