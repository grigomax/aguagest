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



if ($_SESSION['user']['magazzino'] > "1")
{

// mi prendo i post della pagina precendente..

    $_codicecli = $_POST['codice'];
    $_tut = $_GET['tut'];

// Verifico se il cliente � anche fornitore..

    if($_tut == "fornitore")
    {
        $query = sprintf("select ragsoc, piva from fornitori where codice=\"%s\"", $_codicecli);
    }
    else
    {
        $query = sprintf("select ragsoc, piva from clienti where codice=\"%s\"", $_codicecli);
    }
    

    $result = domanda_db("query", $query, $_parametri);

    $dati = $result->fetch(PDO::FETCH_ASSOC);

    $_ragsoc = $dati['ragsoc'];
    $_pivacli = $dati['piva'];

// ora provo a cercare sull'anagrafica fornitori una corrispondenza della partiva iva
    if($_tut == "fornitore")
    {
        $query = sprintf("select * from clienti where piva LIKE \"%s\"", "%$_pivacli");
        
    }
    else
    {
        $query = sprintf("select * from fornitori where piva LIKE \"%s\"", "%$_pivacli");
    }
    
    
    $result = domanda_db("query", $query, ($_parametri['echo'] = "verbose"));

    if ($result != "NO")
    {
        if ($result->rowCount() >= 0)
        {
            // se esiste mi prendo il codice fornitore	
            $dati = $result->fetch(PDO::FETCH_ASSOC);
            $_codicefor = $dati['codice'];
        }
    }
    else
    {
        $_codicefor = "";
    }


    $query = "SELECT * FROM catmer where imballo = '1'";

    $result = domanda_db("query", $query, $_parametri);

    $dati = $result->fetch(PDO::FETCH_ASSOC);

    $_catmer = $dati['codice'];


// ora cerco il tutto e lo metto su video..

    if($_tut == "fornitore")
    {
        $query = sprintf(" select sum(qtacarico) AS carico, sum(qtascarico) as scarico, sum(qtacarico) - sum(qtascarico) AS differenza, descrizione, magazzino.articolo from magazzino INNER JOIN articoli ON magazzino. articolo=articoli.articolo where catmer='$_catmer' and (tut='f' and utente=\"%s\" OR tut='c' and utente=\"%s\") GROUP BY magazzino.articolo ", $_codicecli, $_codicefor);
    }
    else
    {
       $query = sprintf("select sum(qtacarico) AS carico, sum(qtascarico) as scarico, sum(qtascarico) - sum(qtacarico) AS differenza, descrizione, magazzino.articolo from magazzino INNER JOIN articoli ON magazzino. articolo=articoli.articolo where catmer='$_catmer' and (tut='f' and utente=\"%s\" OR tut='c' and utente=\"%s\") GROUP BY magazzino.articolo ", $_codicefor, $_codicecli); 
    }
    

    $result = domanda_db("query", $query, $_parametri);


    // Tutto procede a meraviglia...
    echo "<center>";
    echo "Elenco Cliente $_ragsoc";
    echo "<table align=center border=1 width=\"80%\">";
    echo "<tr><td>codice</td><td>Descrizione</td><td>Carico</td><td>Scarico</td><td>Differenza</td><td> Scrivi TU !</td></tr>";
    foreach ($result AS $dati)
    {
        printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>. </td></tr>", $dati['articolo'], $dati['descrizione'], $dati['carico'], $dati['scarico'], $dati['differenza']);
    }
    echo "</table>";


    echo "<br>";

    if($_tut == "fornitore")
    {
        $query = sprintf("select sum(qtacarico) AS carico, sum(qtascarico) as scarico, sum(qtacarico) - sum(qtascarico) AS differenza, descrizione, magastorico.articolo from magastorico INNER JOIN articoli ON magastorico. articolo=articoli.articolo where catmer='$_catmer' and (tut='f' and utente=\"%s\" OR tut='c' and utente=\"%s\" ) GROUP BY magastorico.articolo", $_codicecli, $_codicefor);
    }
    else
    {
        $query = sprintf("select sum(qtacarico) AS carico, sum(qtascarico) as scarico, sum(qtascarico) - sum(qtacarico) AS differenza, descrizione, magastorico.articolo from magastorico INNER JOIN articoli ON magastorico. articolo=articoli.articolo where catmer='$_catmer' and (tut='f' and utente=\"%s\" OR tut='c' and utente=\"%s\" ) GROUP BY magastorico.articolo", $_codicefor, $_codicecli);
    }
    

// inizio estrazioni dati e pagina visiva

    $result = domanda_db("cosa", $query, $_parametri);


    // Tutto procede a meraviglia...
    echo "<center>";
    echo "$_ragsoc";
    echo "<br>Anni Precedenti";
    echo "<table align=center border=1 width=\"80%\">";
    echo "<tr><td>codice</td><td>Descrizione</td><td>Carico</td><td>Scarico</td><td>Differenza</td><td> Scrivi TU !</td></tr>";
    foreach ($result AS $dati)
    {
        printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>. </td></tr>", $dati['articolo'], $dati['descrizione'], $dati['carico'], $dati['scarico'], $dati['differenza']);
    }
    echo "</table>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
