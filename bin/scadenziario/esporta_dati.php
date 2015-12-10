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


//carico le librerie necessarie
require "../librerie/motore_anagrafiche.php";

//base_html("", $_percorso);
//java_script($_cosa, $_percorso);
//jquery_datapicker($_cosa, $_percorso);
//echo "</head>\n";
//carichiamo la testata del programma.
//testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
//menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['scadenziario'] > "1")
{

    $_parametri['tipo'] = "Tutte";
    $_parametri['data_fine'] = "0000-00-00";
    $_parametri['data_scad'] = date('Y-m-d');
    
    $result = tabella_scadenziario("elenco_pertipo", $_percorso, $_parametri);
    
    $_parametri = "";
    
    
    crea_file_calendar("inoltra", "agenda_agua.ics", $_uid, $_parametri);
    crea_file_calendar("inizio", $_nomefile, "", $_parametri); 
    
    foreach ($result AS $dati)
    {
        
        
        $_parametri['titolo'] = $dati['descrizione'];
        $_parametri['descrizione'] = $dati['descrizione'] . "di importo" . $dati['importo'];
        $_parametri['data_start'] = $dati['data_scad'];
        $_parametri['data_end'] = $dati['data_scad'];

        crea_file_calendar("corpo", $_nomefile, $dati['anno'].$dati['nscad'], $_parametri);
        crea_file_calendar("chiudi_corpo", $_nomefile, $_uid, $_parametri);

        $_parametri = "";

    }
    
    crea_file_calendar("fine", $_nomefile, $_uid, $_parametri);

    
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>