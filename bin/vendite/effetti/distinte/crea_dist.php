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
//carico la sessione con la connessione al database..
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carico le librerie necessarie all'utilizzo
require "../../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{


// Funzione che genera gli effetti dalle fatture in elenco e tramite un check box bengono selezionati.
// controllo sicurezza
// Prendiamo i vari post dalla pagina precedente...
// eseguiamo la connessione con il database
// prendiamoci i post precedenti
// Impostiamo le variabili ambiente
    //prendiamoci l'annp per la distinta
    $_anno = cambio_data("anno_us", $_SESSION['date']);

    $_numero = $_POST['check'];
    $_bancadist = $_SESSION['banca']['codice'];
// prendiamo la data di oggi
    $_datadist = $_SESSION['date'];




// programma che crea la distinta da portare alla banca di preselezionata
// 	Creiamo la parte base dell'inserimento
// selezioniamo l'ultima distinta creata

    $_parametri = "";
    $_parametri['datadist'] = $_anno;
    $_ndistinta = tabella_effetti("ultima_distinta", $_percorso, $_annoeff, $_numeff, $_parametri);

    //setto i parametri standard per tutti gli effetti da inserire nella distinta
    $_parametri = "";
    $_parametri['tipo_pres'] = $_POST['tipo_pres'];
    $_parametri['ndistinta'] = $_ndistinta;
    $_parametri['datadist'] = $_datadist;
    $_parametri['bancadist'] = $_bancadist;
    $_parametri['status'] = "presentato";
    $_parametri['presenta'] = "SI";

    foreach ($_numero as $_value)
    {

        $_numeff = substr($_value, 4, 4);
        $_annoeff = substr($_value, 0, 4);

        $result = tabella_effetti("aggiorna_distinta", $_percorso, $_annoeff, $_numeff, $_parametri);
    } //chiudo foreach
    //resetto i parametri..
    unset($_SESSION['banca']);
    unset($_SESSION['date']);
    $_parametri = "";



// INIZIO PARTE VISIVA DELLA GENERAZIONE..

    if ($result == "ok")
    {
        echo "<h2> Generazione Distinta n. $_ndistinta effettuata </h2> ";

        echo "<h3> Se non appaiono errori a video la generazione &egrave; stata eseguita con successo</h3>";

        echo "<h4> Ora si puo prodere alla stampa della distinta oppure alla sua esportazione </h4>";

        echo "<h4> <a href=\"stampa_dist.php?ndistinta=$_datadist$_ndistinta\" target=\"_blank\"> Stampa subito</a></h4>";
    }
    else
    {

        echo "<h2> Errore Distinta n. $_ndistinta  </h2> ";
        echo "<h3> $_result</h3> ";
    }

    //chiudo la connessione
    $conn->null;
    $conn = null;
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>