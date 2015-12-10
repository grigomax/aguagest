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
require $_percorso . "librerie/motore_doc_pdo.php";
//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//cerco i documenti e li seleziono in base al database..
$_tdoc = $_GET['tdoc'];
$_anno = $_GET['anno'];

//selezioniamo il database documenti..
$_dbdoc = archivio_tdoc($_tdoc);


#$query = sprintf("select * from bv_bolle INNER JOIN clienti ON bv_bolle.utente = clienti.codice where status != 'evaso' and anno=\"%s\" order by ragsoc, ndoc", $_anno);

if ($_tdoc == "fornitori")
{
    $query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN fornitori ON $_dbdoc[testacalce].utente = fornitori.codice where anno='$_anno' AND status != 'evaso' order by ragsoc, anno, ndoc");
}
else
{
    $query = sprintf("select * from $_dbdoc[testacalce] INNER JOIN clienti ON $_dbdoc[testacalce].utente = clienti.codice where anno='$_anno' AND status != 'evaso' AND status != 'saldato' order by ragsoc, anno, ndoc");
}

// Esegue la query...
$result = $conn->query($query);

if ($conn->errorCode() != "00000")
{
    $_errore = $conn->errorInfo();
    echo $_errore['2'];
    //aggiungiamo la gestione scitta dell'errore..
    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
    scrittura_errori($_cosa, $_percorso, $_errori);
}




//inizio parte visiva..

base_html_stampa("chiudi", $_parametri);


echo "<body>\n";
echo "<center>\n";

echo "<table class=\"elenco\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";

echo "<thead>\n";

echo "<tr><th colspan=\"7\"><h3>Elenco Documenti di quest'anno non evasi per cliente</h3> </th></tr>\n";
echo "<tr>\n";

echo "<th width=\"100\" align=\"center\" >Data</th>\n";
echo "<th width=\"40\" align=\"center\">Codice</th>\n";
echo "<th width=\"400\" align=\"left\">Ragione Sociale</th>\n";
echo "<th width=\"50\" align=\"left\">Valore</th>\n";
echo "<th width=\"80\" align=\"center\">Causale</th>\n";
echo "<th width=\"80\" align=\"center\">Status</th>\n";
echo "<th width=\"80\" align=\"center\">Numero</th>\n";
echo "</tr>";
echo "</thead>\n";


echo "<tfoot>\n";
echo "<tr><td colspan=\"7\"><hr></td></tr>\n";
echo "</tfoot>\n";

echo "<tbody>\n";

foreach ($result AS $dati)
{

    $_codcli = $dati['codice'];

    if ($_codcli == $_codcli1)
    {

        echo "<tr>\n";
        printf("<td class=\"elenco\" width=\"100\" align=\"center\">%s</td>\n", $dati['datareg']);
        printf("<td class=\"elenco\" width=\"40\" align=\"center\">%s</td>\n", $dati['codice']);
        printf("<td class=\"elenco\" width=\"400\" align=\"left\">%s</td>\n", $dati['ragsoc']);
        printf("<td class=\"elenco\" width=\"50\" align=\"center\">%s</td>\n", $dati['totimpo']);
        printf("<td class=\"elenco\" width=\"80\" align=\"center\">%s</td>\n", $dati['causale']);
        printf("<td class=\"elenco\" width=\"80\" align=\"center\">%s</td>\n", $dati['status']);
        printf("<td class=\"elenco\" width=\"80\" align=\"center\"><b>%s</b></td>\n", $dati['ndoc']);
        echo "</tr>";
        $_valoretot = $_valoretot + $dati['totdoc'];
    }
    else
    {
        echo "<tr><td class=\"elenco\" colspan=\"3\" align=\"right\"><font size=\"2\">Valore ivato == </font>  </td><td class=\"elenco\"> <font size=\"2\">$_valoretot</font> </td><td class=\"elenco\" colspan=\"3\">&nbsp;</td></tr>";
        echo "<tr><td class=\"elenco\" colspan=\"7\"><hr></td></tr>\n";
        $_valoretot = 0;

        echo "<tr>\n";
        printf("<td class=\"elenco\" width=\"100\" align=\"center\">%s</td>\n", $dati['datareg']);
        printf("<td class=\"elenco\" width=\"40\" align=\"center\">%s</td>\n", $dati['codice']);
        printf("<td class=\"elenco\" width=\"400\" align=\"left\">%s</td>\n", $dati['ragsoc']);
        printf("<td class=\"elenco\" width=\"50\" align=\"center\">%s</td>\n", $dati['totimpo']);
        printf("<td class=\"elenco\" width=\"80\" align=\"center\">%s</td>\n", $dati['causale']);
        printf("<td class=\"elenco\" width=\"80\" align=\"center\">%s</td>\n", $dati['status']);
        printf("<td class=\"elenco\" width=\"80\" align=\"center\"><b>%s</b></td>\n", $dati['ndoc']);
        echo "</tr>\n";
        $_valoretot = $_valoretot + $dati['totdoc'];
    }
    $_codcli1 = $_codcli;

    $_totdoc = $_totdoc + $dati['totdoc'];
}
echo "</tbody>\n";

echo "</table>\n";

echo "<table class=\"elenco\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";

echo "<tr><td colspan=\"7\"><hr/></td></tr>\n";
echo "<tr><td></td><td></td><td></td><td align=\"right\">Valore totale ivato</td><td>$_totdoc</td></tr>\n";

echo "</table>\n";
echo "</body></html>";
?>