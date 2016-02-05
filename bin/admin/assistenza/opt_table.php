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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{

    echo "<center><br><br><br>Eliminazione dati tabelle temporanee<br><br>";

    $query = "TRUNCATE TABLE doc_basket";

    domanda_db("exec", $query, $_cosa, $_ritorno, "verbose");

    $query = "TRUNCATE TABLE prima_nota_basket";

    domanda_db("exec", $query, $_cosa, $_ritorno, "verbose");
    

    echo "<center><br><br><br> Inizio Ottimizzzione tabelle..<br><br>";

    $query = "OPTIMIZE TABLE agenti,aliquota,articoli,banche,banned_ip,barcode,bvfor_dettaglio,bvfor_testacalce,bv_bolle,bv_dettaglio,catmer,causali_contabili,clienti,co_dettaglio,co_testacalce,doc_basket,effetti,fornitori,fv_dettaglio,fv_testacalce,imballi,liquid_iva_periodica,listini,magastorico,magazzino,oc_dettaglio,oc_testacalce,of_dettaglio,of_testacalce,pagamenti,piano_conti,prezzi_cliente,prima_nota,prima_nota_basket,promozioni,provvigioni,pv_dettaglio,pv_testacalce,scadenziario,stampe_layout,tipart,utente_campivari,utenti,version,vettori,zone";


    $result = $conn->exec($query);

    domanda_db("exec", $query, $_cosa, $_ritorno, "verbose");

}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>