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


#Mi prendo il post di arrivo.. !
//verifico se il calmpo articolo e vuoto cosi da evitar di cercare a caso
    if ($_POST['numeff'] == "")
    {
        $_campi = $_POST['campi'];
        $_descrizione = $_POST['descrizione'];
    }
    else
    {
        $_campi = "numeff";
        $_descrizione = $_POST['numeff'];
    }

// Stringa contenente la query di ricerca...
    if ($_descrizione == "")
    {
        echo "<h2> Nessun Carattere immesso nel campo ricerca </h2>";
        echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A>";
        exit;
    }


    $_parametri['descrizione'] = "%$_descrizione%";
    $_parametri['campi'] = $_campi;
    $_parametri['sospesi'] = $_POST['sospesi'];

    $result = tabella_effetti("ricerca_eff", $_percorso, $_annoeff, $_numeff, $_parametri);

// elenca le fatture presenti in archivio non evase FATTURE VENDITA
// elenco documenti fatture vendita
    echo "<form action=\"stampa_result_eff.php\" target=\"_blank\" method=\"POST\">\n";
    echo "<span class=\"testo_blu\"><center><br><b>Scadenziario Effetti.. </b></center><br>";
    echo "Per stampare questa pagina <input type=\"radio\" name=\"sospesi\" value=\"$_parametri[sospesi]\" checked><input type=\"radio\" name=\"campi\" value=\"$_parametri[campi]\" checked><input type=\"radio\" name=\"descrizione\" value=\"$_parametri[descrizione]\" checked><input type=\"submit\" value=\"stampa\">\n";
    echo "</form>\n";
    echo "<BR>1 - Rimessa diretta - 2 - Contanti - 3 - Ricevuta bancaria - 4 - Tratta o cambiale - 5 - Contrassegno - 6 - Bonifico Bancario - 7 - Ricevimento Fattura </span> <br>";


    // Tutto procede a meraviglia...
    echo "<table align=\"center\" width=\"95%\">";
    echo "<tr>";
    echo "<td width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Tipo</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Fatt.</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Pagamento</span></td>";
    echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
    echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Scadenza</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Status</span></td>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
    echo "</tr>";

    foreach ($result AS $dati)
    {
        # $_mese_rif = substr($dati['scadeff'],0,-3);
        #  if($_mese_rif != $_mese_rif2)
        #  {
        #      echo "<tr><td align =\"center\" colspan=\"9\"><br><font face=\"arial\">Scadenze al $_mese_rif</font></td></tr>\n";
        # }
        echo "<tr>";
        printf("<form action=\"visualizzadoc.php?anno=$dati[annoeff]\" method=\"POST\">");
        printf("<td width=\"30\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['tipoeff']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datafatt']);
        echo "<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>$dati[numdoc] / $dati[suffixdoc]</b></span></td>\n";
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['modpag']);
        if ($CONTABILITA == "SI")
        {
            printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">
		<a href=\"../../contabilita/result_scheda.php?tipo_cf=C&codconto=%s&start=%s\">%s</span></td>", $dati['codice'], $dati['annoeff'], $dati['ragsoc']);
        }
        else
        {
            printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
        }
        printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['impeff']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['scadeff']);
        printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['status']);
        printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"submit\" name=\"ndoc\" value=\"%s\"></td>", $dati['numeff']);


        #http://localhost/agua/bin/contabilita/result_scheda.php?tipo_cf=C&codconto=867&start=2012


        echo "</form></tr>";
        echo "<tr>";
        echo "<td width=\"30\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
        echo "</tr>";
        #$_mese_rif2 = $_mese_rif;
    }


    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>