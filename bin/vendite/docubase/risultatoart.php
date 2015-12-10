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
require "../../librerie/motore_doc_pdo.php";
require "../../librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{

    //recupero le variabili
    $_tdoc = $_SESSION['tdoc'];
// funzione cancella articolo
    $_cosa = $_POST['azione'];
// prendo la fiunzione della muovimentaizone
    $_calce = $_SESSION['calce'];
//mi prendo la sessione..
    $id = session_id();
    $_utente = $_SESSION['utente'];
    $dati = $_SESSION['datiutente'];

    intesta_html($_tdoc, "", $dati, "");

    if ($_POST['campi'] != "")
    {
        $_campi = $_POST['campi'];
    }
    else
    {
        $_campi = "descrizione";
    }


    $_descrizione = $_POST['descrizione'];
    $_tdoc = $_SESSION['tdoc'];

// Stringa contenente la query di ricerca...
    if ($_descrizione == "")
    {
        echo "<h2> Nessun Carattere immesso nel campo ricerca </h2>";
        echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A>";
        exit;
    }


// selezioniamo solo gli articoli che non sono colleati al magazzino...
// echo $_tdoc;

    if (( $_tdoc == "FATTURA") or ( $_tdoc == "NOTA DEBITO"))
    {
// seleziono solo il metodo spesa e basta
// Stringa contenente la query di ricerca...
        $_descrizione = "%$_descrizione%";

        $query = sprintf("select * from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where esma = 'SI' and rigo='1' and $_campi like \"%s\" order by articolo", $_descrizione);
        $_messaggio = "Nessun articolo trovato quelli non collegati con il magazzino\n";
    }
    else
    {
// Stringa contenente la query di ricerca...
        $_descrizione = "%$_descrizione%";

        $query = sprintf("select * from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where rigo='1' and $_campi like \"%s\" order by articolo", $_descrizione);
        $_messaggio = "Nessun articolo Trovato";
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


// Tutto procede a meraviglia...
    echo "<table align=\"center\" width=\"90%\">";
    echo "<tr>";
    echo "<tr><td colspan=\"7\"><span class=\"testo_blu\"><center><b>Risulati ricerca</b></center></span> </td></tr>\n";
    echo "<td width=\"60\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Foto</span></td>";

    echo "<td width=\"100\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Etichetta</span></td>";
    echo "<td width=\"400\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
    echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Um</span></td>";
    echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Listino</span></td>";
    echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Inserisci</span></td>";
    echo "<td width=\"35\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Sel.</span></td>";
    echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Azione</span></td>";
    echo "</tr>";

    if ($result->rowCount() == 0)
    {
        echo "<tr><td colspan=6 align=center><h2>$_messaggio</h2><br>
		<A HREF=\"#\" onClick=\"history.back()\">Riprova</A></td></tr>";
        return;
    }
    else
    {
        echo "<form action=\"quantita.php\" method=\"POST\">\n";
        foreach ($result AS $dati)
        {
            echo "<tr>";
            printf("<td width=\"60\" align=\"center\"><span class=\"testo_blu\"><img src=\"../../../imm-art/%s\" height=\"50\" width=\"50\"></span></td>", $dati['immagine']);


            if ($_SESSION['programma'] == "ACQUISTO")
            {
                printf("<td width=\"100\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['artfor']);
            }
            else
            {
                printf("<td width=\"100\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['desrid']);
            }
            printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['descrizione']);
            printf("<td width=\"40\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['unita']);
            if ($_SESSION['programma'] == "ACQUISTO")
            {
                printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['prelisacq']);
            }
            else
            {
                printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['listino']);
            }

            printf("<td width=\"70\" height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"submit\" name=\"codice\" value=\"%s\"></td>", $dati['articolo']);
            printf("<td width=\"30\" align=\"center\"><input type=checkbox name=\"articolo[]\" value=\"%s\"></td>\n", $dati['articolo']);
            echo "<td width=\"40\" height=\"1\" align=\"center\" class=\"testo_blu\"><input type=\"submit\" name=\"azione\" value=\"vai\"></td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td width=\"60\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"100\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"40\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"35\" height=\"1\" align=\"center\" class=\"logo\"></td>";
            echo "<td width=\"40\" height=\"1\" align=\"center\" class=\"logo\"></td>";

            echo "</tr>";
        }
        echo "</form>\n";

        echo "</td></tr></table></body></html>";
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>