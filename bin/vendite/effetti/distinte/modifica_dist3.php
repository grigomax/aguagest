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

//carichiamo le librerie necessarie..
require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{


    $_azione = $_POST['azione'];

// prendiamoci i post precedenti
// Impostiamo le variabili ambiente
    $_anno = $_POST['anno'];
    $_numero = $_POST['check'];
// prendiamo la data di oggi
// $_datadist = date('Y-m-d');
    $_status = "inserito";
    $_ndistinta = $_POST['ndistinta'];
    $_bancadist = $_SESSION['bancadist'];
    $_datadist = $_SESSION['datadist'];





    if ($_azione == "vai")
    {
        // iL PROGRAMMA MODIFICA LA DISTINTA APPENA PRESENTATA
        // ok per ogni articolo mi prendo tutti i dati dell'articolo
// 	Libero gli effetti po li riassegno
        //azzeriamo la variabile parametri
        $_parametri = "";
        $_parametri['ndistinta'] = $_ndistinta;
        $_parametri['datadist'] = $_datadist;

        $result = tabella_effetti("libera_distinta", $_percorso, $_annoeff, $_numeff, $_parametri);

        if ($result != "ok")
        {
            echo $result;
        }
        else
        {

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
            // INIZIO PARTE VISIVA DELLA GENERAZIONE..
// INIZIO PARTE VISIVA DELLA GENERAZIONE..

            if ($result == "ok")
            {
                echo "<h2> Modifica n. $_ndistinta effettuata </h2> ";

                echo "<h3> Se non appaiono errori a video la modifica &egrave; stata eseguita con successo</h3>";

                echo "<h4> Ora si puo prodere alla stampa della distinta oppure alla sua esportazione </h4>";

                //echo "<h4> <a href=\"stampa_dist.php?ndistinta=$_datadist$_ndistinta\" target=\"_blank\"> Stampa subito</a></h4>";
            }
            else
            {

                echo "<h2> Errore Distinta n. $_ndistinta  </h2> ";
                echo "<h3> $_result</h3> ";
            }
        }
    }




    if ($_azione == "Elimina")
    {
// 	Eliminiamo tutti gli effetii facenti parte di una distinta specifica relativa all'anno in corso..

        $_parametri = "";
        $_parametri['ndistinta'] = $_ndistinta;
        $_parametri['datadist'] = $_anno;

        $result = tabella_effetti("elimina_distinta", $_percorso, $_annoeff, $_numeff, $_parametri);

        if ($result != "ok")
        {
            echo $result;
        }
        else
        {

            echo "<h2> Elimina distinta &egrave; stata effettuata </h2> ";

            echo "<h4> Ora non si è più in grado recuperarla..</h4>";
        }
    }

    if ($_azione == "Libera")
    {
// Lieriamo Gli effetti dalla distinta.. praticamente cancelliamo la distinta senza rimettendo gli effetti in modalita attesa



        $_parametri = "";
        $_parametri['ndistinta'] = $_ndistinta;
        $_parametri['datadist'] = $_anno;

        $result = tabella_effetti("libera_distinta", $_percorso, $_annoeff, $_numeff, $_parametri);

        if ($result != "ok")
        {
            echo $result;
        }
        else
        {

            echo "<h2> La Distinta &egrave; stata eliminata ed gli effetti liberati </h2> ";

            echo "<h4> Ora tutti gli effetti sono in status attesa a disposizione </h4>";
        }
    }

//resetto i parametri..
    unset($_SESSION['ndistinta']);
    unset($_SESSION['bancadist']);
    unset($_SESSION['datadist']);
    $_parametri = "";

    $conn->null;
    $conn = null;
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>