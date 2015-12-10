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


if ($_SESSION['user']['anagrafiche'] > "3")
{
    echo "<table border=\"0\" width=\"80%\" align=\"left\">\n";
    echo "<tr>\n";

    echo "<td>\n";
    echo "<h3 align=\"center\">Sfogliare e cercare e poi caricare il listino fornitore</h3>\n";
    echo "<center>\n";
#<!--apriamo il form e specifichiamo il tipo di dati e il metodo di invio-->
    echo "<form action=\"assegna_campi.php\" enctype=\"multipart/form-data\" method=\"post\">\n";
    #<!--settiamo la dimensione massima dei file in byte, nel nostro caso 1MB=1024000byte-->
    echo "<input name=\"MAX_FILE_SIZE\" type=\"hidden\" value=\"2048000\" />\n";
    echo "File da caricare:\n";
    #<!--campo per la scelta del file-->
    echo "<input id=\"file\" name=\"file\" type=\"file\" />\n";
    #<!--bottone di invio-->
    echo "<select name=\"separatore\">\n";
    echo "<option value=\"\">Seleziona separatore  </option>\n";
    echo "<option value=\"METEL\">Listino METEL</option>\n";
    echo "<option value=\"|\"> |  </option>\n";
    echo "<option value=\":\"> : </option>\n";
    echo "<option value=\";\"> ; </option>\n";
    echo '<option value=\'","\'> ","</option>\n';
    echo '<option value=\',\'> ,</option>\n';
    echo '<option value=\'"\'> "</option>\n';
    echo "</select>\n";
    echo "<input name=\"submit\" type=\"submit\" value=\"Carica\" />\n";
    echo "</form>\n";
    echo "</center>";

    echo "<hr width=\"100%\"></hr>\n";
    echo "<b>ATTENZIONE: Per poter usufruire del programma il file deve avere le seguenti caratteristiche;</b><br/>\n";
    echo "1 - Essere un file di testo in formato TXT oppure in formato CSV<br/>\n";
    echo "2 - Non ci devono essere righe di commento o altre cose non valide al di fuori della prima riga di intestazione colonne</br>\n";
    echo "3 - La prima colonna deve essere il codice articolo fornitore esatto <br/>\n";
    echo "4 - La colonna prezzi deve essere con il punto e non con la virgola <br/>\n";
    echo "5 - I campi devono essere separati con i seguenti puntatori \" | \" oppure \":\" <br/>\n";
    echo "6 - Per il resto si possono seguire le indicazioni alla pagina successiva..<br/>Vi consiglio di preparare già il file tramite un programma esterno</br>\n";
    echo "come <a href=\"http://www.libreoffice.org\" target=\"_blanck\">Libreoffice calc</a> Scaricabile gratuitamente<br/>\n";
    echo "<font color=\"RED\"> Si consigliano vivamente le copie le modifiche saranno permanenti<br/></font>\n";
    echo "In genere i listini Metel sono distribuiti sui seguenti file<br>( xxx=prefisso del fornitore, ad esempio BRM per Bremas):\n";
    echo "<br>xxxLSP.TXT - listino al pubblico <br>xxxLSG.TXT listino rivenditori<br>xxxFST.TXT elenco delle categorie\n";




    echo "</td></tr></table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>