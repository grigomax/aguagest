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
require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{


// ora devo prendermi i post..
// poi devo creare il files contenete la esportazione
//inizio parte produttiva

    $_ndistinta = substr($_GET['ndistinta'], 10, 4);
    $_datadist = substr($_GET['ndistinta'], 0, 10);
    $_azione = $_GET['azione'];

#prendiamoci tutti i dati dalla distintaa..
// Stringa contenente la query di ricerca... solo dei fornitori
    $query = "SELECT *, date_format(datadist,'%d%m%y') AS datadist, scadeff AS ordine, date_format(scadeff,'%d%m%y') AS scadeff, date_format(datadoc,'%d-%m-%Y') AS datadoc FROM effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where ndistinta=\"$_ndistinta\" and datadist=\"$_datadist\" ORDER BY ordine";

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

    foreach ($result AS $datie)
        ;

    $_status = $datie['status'];
    $_dataus = $datie['datareg'];

    $datib = tabella_banche("singola", $datie['bancadist'], $_abi, $_cab, $_parametri);




    $_annodistinta = substr($datie['datadist'], 4, 2);

#nome file = numer distinta-anno.cbi
    $_nomefile = sprintf("%s-%s", $datie['ndistinta'], $_annodistinta);

// il nome del files
//$nfile="/agua/plugins/espfatt.txt";
    $nfile = "../../../../spool/$_nomefile.cbi";
// creo il files e nascondo la soluzione
    $fp = fopen($nfile, "w");
//controllo l'esito
    if (!$fp)
        die("Errore.. non sono riuscito a creare il file.. Permessi ?");

//settiamo la lunghezza per il campo nomefile.
    $_nomefile = str_pad($_nomefile, 7, '0', STR_PAD_LEFT);
#settiamo la variabile euro = al 114 carattere
    $euro = str_pad('E', 88, ' ', STR_PAD_LEFT);

#Apriamo il file con il record di testa..
    $spazio = str_pad($_filler, 6, ' ', STR_PAD_LEFT);
    $_commento = " IB$SIA$datib[abi]$datie[datadist]$_nomefile$euro$spazio\n";
    $_record = $_record + 1;

    fwrite($fp, $_commento);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

#Partiamo con inserire le riba..
//settiamo i parametri base di non sconfinamento dai tracciati
    $azienda = substr($azienda, '0', '24');
    $azienda_br = substr($azienda, '0', '20');
    $azienda2 = substr($azienda2, '0', '24');
    $citta = substr($citta, '0', '15');


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

    foreach ($result AS $datir)
    {
        $_rigo = $_rigo + "1";

        $_impeff = number_format($datir[impeff], '2', '', '');
        #$_commento = "$datir[abi]($datir[cab]            $SIA"."4$datir[codcli]\n";
        $_commento = sprintf(" 14%s%s%s30000%s-%s%s%s%s%s%s%s4%s%s\n", str_pad($_rigo, 7, '0', STR_PAD_LEFT), str_pad($_filler, 12, ' ', STR_PAD_LEFT), $datir[scadeff], str_pad($_impeff, 13, '0', STR_PAD_LEFT), $datib['abi'], $datib['cab'], str_pad($datib[cc], 12, '0', STR_PAD_LEFT), $datir['abi'], $datir['cab'], str_pad($_filler, 12, ' ', STR_PAD_LEFT), $SIA, str_pad($datir[codcli], 16, '0', STR_PAD_LEFT), str_pad('E', 7, ' ', STR_PAD_LEFT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");

        //inizio scrittura riga 20
        $_commento = sprintf(" 20%s%s%s%s%s %s %s%s\n", str_pad($_rigo, 7, '0', STR_PAD_LEFT), str_pad($azienda, 24, ' ', STR_PAD_RIGHT), str_pad($azienda2, 24, ' ', STR_PAD_RIGHT), str_pad($indirizzo, 24, ' ', STR_PAD_RIGHT), $cap, str_pad($citta, 15, ' ', STR_PAD_RIGHT), $prov, str_pad($_filler, 14, ' ', STR_PAD_LEFT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");

        $_ragsoc = substr($datir['ragsoc'], '0', '30');
        $_ragsoc2 = substr($datir['ragsoc2'], '0', '30');
        $_iva = substr($datir['piva'], '-11', '11');

        //inizio inserimento riga 30
        $_commento = sprintf(" 30%s%s%s%s%s\n", str_pad($_rigo, 7, '0', STR_PAD_LEFT), str_pad($_ragsoc, 30, ' ', STR_PAD_RIGHT), str_pad($_ragsoc2, 30, ' ', STR_PAD_RIGHT), str_pad($_iva, 16, ' ', STR_PAD_RIGHT), str_pad($_filler, 34, ' ', STR_PAD_LEFT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");

        //inizio inserimento riga 40
        $_indirizzo = substr($datir['indirizzo'], '0', '30');
        $_citta = substr($datir['citta'], '0', '22');
        $_bancapp = substr($datir['bancapp'], '0', '49');

        $_commento = sprintf(" 40%s%s%s%s %s%s\n", str_pad($_rigo, 7, '0', STR_PAD_LEFT), str_pad($_indirizzo, 30, ' ', STR_PAD_RIGHT), str_pad($datir['cap'], 5, ' ', STR_PAD_RIGHT), str_pad($_citta, 22, ' ', STR_PAD_RIGHT), $datir['prov'], str_pad($_bancapp, 50, ' ', STR_PAD_RIGHT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");

        //inizio inserimento riga 50

        $_commento = sprintf(" 50%s%s Nr. %s del %s%s%s%s\n", str_pad($_rigo, 7, '0', STR_PAD_LEFT), str_pad($datir['tipodoc'], 39, ' ', STR_PAD_RIGHT), str_pad($datir['numdoc'], 6, ' ', STR_PAD_RIGHT), str_pad($datir['datadoc'], 25, ' ', STR_PAD_RIGHT), str_pad($_filler, 10, ' ', STR_PAD_LEFT), str_pad($piva, 16, ' ', STR_PAD_RIGHT), str_pad($_filler, 4, ' ', STR_PAD_LEFT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");

        //inizio inserimento riga 51

        $_numrib = sprintf("%s%s", $datir['numeff'], $datir['annoeff']);

        $_commento = sprintf(" 51%s%s%s%s\n", str_pad($_rigo, 7, '0', STR_PAD_LEFT), str_pad($_numrib, 10, '0', STR_PAD_LEFT), str_pad($azienda_br, 20, ' ', STR_PAD_RIGHT), str_pad($_filler, 80, ' ', STR_PAD_LEFT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");

        //inizio inserimento riga 70

        $_numrib = sprintf("%s-%s", $datir['numeff'], $datir['annoeff']);

        $_commento = sprintf(" 70%s%s\n", str_pad($_rigo, 7, '0', STR_PAD_LEFT), str_pad($_filler, 110, ' ', STR_PAD_LEFT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");

        $_totdist = $_totdist + $_impeff;
        $_record = $_record + "7";
    }

//codice di chiusura.
//riga ef di chiusura..
    $_record = $_record + "1";
    $_commento = sprintf(" EF$SIA%s%s%s%s%s%s%s%s%s%s%s\n", $datib[abi], $datie[datadist], str_pad($_nomefile, 20, ' ', STR_PAD_RIGHT), str_pad($_filler, 6, ' ', STR_PAD_RIGHT), str_pad($_rigo, 7, '0', STR_PAD_LEFT), str_pad($_totdist, 15, '0', STR_PAD_LEFT), str_pad($_filler, 15, '0', STR_PAD_LEFT), str_pad($_record, 7, '0', STR_PAD_LEFT), str_pad($_filler, 24, ' ', STR_PAD_LEFT), str_pad('E', 1, ' ', STR_PAD_LEFT), str_pad($_filler, 6, ' ', STR_PAD_LEFT));
    fwrite($fp, $_commento);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

//chiudiamo il file..

    fclose($fp);


    echo "<center>";
    echo "<h2>Se non appaiono errori a video<br> la esportazione dei dati &egrave; stata <br>eseguita con successo</h2>";
    echo "<br>";
    echo "<h3>Preleva il file CBI qui=> <a href=\"$nfile\"> Cliccando Qui!</a></h3>";
    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>