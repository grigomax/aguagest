<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


require "../librerie/motore_primanota.php";
require "../../setting/par_conta.inc.php";
require "../librerie/motore_anagrafiche.php";
require "../librerie/stampe_pdf.php";
//qui parte l'avventura del sig. buonaventura...


if ($_SESSION['user']['contabilita'] > "1")
{
//recupero tutti POST.
    //separiamo i valori.. :-D

    $_start = substr($_GET['azione'], "0", "10");
    $_end = substr($_GET['azione'], "10", "10");
    $_tipo_cf = substr($_GET['azione'], "20", "1");
    $_codconto = substr($_GET['azione'], "21", "10");

    /*
      echo "<br>$_start\n";
      echo "<br>$_end\n";
      echo "<br>$_tipo_cf\n";
      echo "<br>$_codconto\n";
     */



//componiamo il conto completo
//prima di inserire facciamo un po di conti..
//completiamo il codice conto..
    if ($_tipo_cf == "C")
    {

        $dati = tabella_clienti("singola", $_codconto, $_parametri);

        $_descrizione = $dati['ragsoc'];
        $_tipo_cf = "C";


        //vuol dire che sono clienti
        $_conto = sprintf("%s%s", $MASTRO_CLI, $_codconto);
    }
    elseif ($_tipo_cf == "F")
    {
        //vuol dire che sono clienti
        $dati = tabella_fornitori("singola", $_codconto, $_parametri);

        $_descrizione = $dati['ragsoc'];
        $_conto = sprintf("%s%s", $MASTRO_FOR, $_codconto);
        $_tipo_cf = "F";
    }
    else
    {
        $dati = tabella_piano_conti("singola", $_codconto, $_parametri);

        $_descrizione = $dati['descrizione'];
        $_tipo_cf = $dati['tipo_cf'];
        $_conto = $_codconto;
    }


//Fissiamo il numero di righe per pagina...
    $rpp = "50";

    //mi prendo l'anno..

    $_anno = substr($_start, "0", "4");
    //echo $_anno;
    //provo a prendermi il saldo precedente
    $query = "SELECT *, SUM(dare), SUM(avere), date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont, data_cont AS data from prima_nota where conto = '$_conto ' AND data_cont >= '$_anno-01-01' AND data_cont < '$_start' ORDER BY data ASC, nreg ";

    //echo $query;
    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    $saldo = $result->fetch(PDO::FETCH_ASSOC);

    $_saldo = $saldo['SUM(dare)'] - $saldo['SUM(avere)'];



    $query = "SELECT *, date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont, data_cont AS data from prima_nota where conto = '$_conto ' AND data_cont >= '$_start' AND data_cont <= '$_end' ORDER BY data ASC, nreg ";
//cerco il numero di righe

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    $righe = $result->rowCount();

    //inserisco il numero di righe per pagina
    $_pagine = $righe / $rpp;
    //arrotondo per eccesso
    $pagina = ceil($_pagine);

    $data['day_start'] = $_start;
    $data['day_end'] = $_end;
    
    $_return['saldo'] = $_saldo;

    if ($_saldo > "0.00")
    {
        $_return['scritta_p'] = "D";
    }
    else
    {
        $_return['scritta_p'] = "A";
    }



    //creaimo il file
    crea_file_pdf($_cosa, $_orientamento, "scheda contabili");

    for ($_pg = 1; $_pg <= $pagina; $_pg++)
    {

        crea_pagina_pdf();

        //intestazione
        crea_intestazione_ditta_pdf("schede_contabili", "", $_anno, $_pg, $pagina, $_parametri);


        intesta_tabella($_cosa, $_codconto, $_descrizione, $data);

        $_return = corpo_tabella($_cosa, $result, $rpp, $_return);

        calce_tabella($_cosa, $_return['dare'], $_return['avere'], $_return['saldo'], $_return['width']);
    }


    //chiudiamo il files..

    chiudi_files("scheda_contabile", "../..", "I");
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>