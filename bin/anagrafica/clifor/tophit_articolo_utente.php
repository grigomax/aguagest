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
ini_set('session.gc_maxlifetime', $SESSIONTIME);
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//prendiamoci i dati

base_html_stampa("chiudi", $_parametri);

if ($_SESSION['user']['anagrafiche'] > "1")
{
    //settiamo le righe per pagina,

    $rpp = "44";

    $_tut = $_GET['tut'];
    $_utente = $_GET['utente'];

    $_parametri['tabella'] = "Top Hit Articolo per utente";
    $_parametri['data'] = date('d-m-Y');
    $_parametri['stampa'] = "codice utente $_utente";
    $_parametri['anno'] = "Tipo $_tut";



    $query = "SELECT magastorico.articolo, descrizione, unita, qtacarico, qtascarico from magastorico INNER JOIN articoli ON magastorico.articolo=articoli.articolo where tut='$_tut' and utente='$_utente' group by magastorico.articolo order by descrizione";

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "tophitarticolo.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    //iniziamo ad impaginare

    $righe = $result->rowCount();

    //inserisco il numero di righe per pagina
    $_pagine = $righe / $rpp;
    //arrotondo per eccesso
    $pagina = ceil($_pagine);

    for ($_pg = 1; $_pg <= $pagina; $_pg++)
    {

        $_parametri[pg] = $_pg;
        $_parametri[pagina] = $pagina;

        intestazione_html($_cosa, $_percorso, $_parametri);
        //copiamoci la domanda..

        echo "<table align=\"left\" width=\"95%\" border=\"0\" class=\"elenco_stampa\">\n";
        echo "<tr class=\"titolo\"><td>Codice</td><td>Descrizione</td><td align=\"center\">UM</td><td>Scarico</td><td>Carico</td></tr>\n";
        for ($_nr = 1; $_nr <= $rpp; $_nr++)
        {
            $dati = $result->fetch(PDO::FETCH_ASSOC);

            echo "<tr><td class=\"tabella_elenco\">&nbsp;$dati[articolo]</td><td class=\"tabella_elenco\">".substr($dati[descrizione], 0, 80) ."</td><td class=\"tabella_elenco\" align=\"center\">$dati[unita]</td><td class=\"tabella_elenco\" align=\"right\">$dati[qtascarico]</td><td align=\"right\" class=\"tabella_elenco\">$dati[qtacarico]</td></tr>\n";
        }

        echo "<tr><td colspan=\"5\"><hr></td></tr>\n";
        echo "</table>\n";
    }
}
else
{
    echo "<h2>Non hai i permessi per poter visualizzare cliente/fornitore</h2>\n";
}
?>