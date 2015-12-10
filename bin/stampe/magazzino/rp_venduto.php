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

require "../../librerie/motore_anagrafiche.php";

//  mi prendo i post della pagina precedente..
if ($_SESSION['user']['stampe'] > "1")
{
    $_anno = $_POST['anno'];
    $_mese = $_POST['mese'];
    $_tipo = $_POST['tipo'];

// verifico l'anno passato per vedere cosa che archivio prendere

    $query = "SELECT anno FROM magazzino WHERE tut = 'giain' ORDER BY anno LIMIT 1";

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

    foreach ($result AS $datianno);

    if ($_anno < $datianno['anno'])
    {
        $_magazzino = "magastorico";
    }
    else
    {
        $_magazzino = "magazzino";
    }

    $_mesec = "$_mese";
    $_datareg = "$_anno-$_mesec-%";


// estraggo i dati dal magazzino
// seleziono il tipo di stampa..
    if ($_tipo == "catmer")
    {
        
        $query = sprintf("SELECT catmer AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY catmer", $_magazzino, $_magazzino, $_datareg);
        
        $catmer = tabella_catmer("elenca", $_codice, $_parametri);
        
        //inserisco i dati in un array..
        foreach ($catmer AS $dati2)
        {
            $categoria[$dati2['codice']] = $dati2['catmer'];
        }
        
    }
    
    if ($_tipo == "tipart")
    {
        $query = sprintf("SELECT tipart AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN articoli ON %s.articolo=articoli.articolo WHERE datareg LIKE \"%s\" GROUP BY tipart", $_magazzino, $_magazzino, $_datareg);
        
        $tipart = tabella_tipart("elenca", $_codice, $_parametri);
        
        //inserisco i dati in un array..
        foreach ($tipart AS $dati2)
        {
            $categoria[$dati2['codice']] = $dati2['tipoart'];
        }
        
    }

    if ($_tipo == "cliente")
    {
        $query = sprintf("SELECT ragsoc AS gruppo, SUM(valorevend) AS venduto FROM %s INNER JOIN clienti ON %s.utente=clienti.codice WHERE tut='c' and datareg LIKE \"%s\" GROUP BY ragsoc", $_magazzino, $_magazzino, $_datareg);
    }

    if ($_tipo == "fornitore")
    {
        $query = sprintf("SELECT ragsoc AS gruppo, SUM(valoreacq) AS venduto FROM %s INNER JOIN fornitori ON %s.utente=fornitori.codice WHERE tut='f' and datareg LIKE \"%s\" GROUP BY ragsoc", $_magazzino, $_magazzino, $_datareg);
    }
    
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

//cerco il numero di righe
    $righe = $result->rowCount();
//inserisco il numero di righe per pagina
    $rpp = 40;
    $rpc = 80;

    $_pagine = $righe / $rpc;
//arrotondo per eccesso
    $pagina = ceil($_pagine);

    $_parametri['data'] = date("d / m / Y");
    $_parametri['stampa'] = "VENDUTO DI MAGAZZINO";
    $_parametri['anno'] = $_anno;
    $_parametri['tabella'] = "Venduto Mese $_mese";

    for ($_pg = 1; $_pg <= $pagina; $_pg++)
    {
        $_parametri['pg'] = $_pg;
        $_parametri['pagina'] = $pagina;
        
        intestazione_html($_cosa, $_percorso, $_parametri);
        
        ?>
 
                <table border="0" cellspacing="0" cellpadding="0" align="center" width="90%">
                    <tr>
                        <td align="left" width="49%">
                            <table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">
                                <tr>
                                    <td bgcolor="#FFFFFF" valign="top" width="150" align="left"><font face="arial" size="2">Nome</td>
                                    <td bgcolor="#FFFFFF" valign="top" width="50" align="right"><font face="arial" size="2">Valore</td>
                                </tr>
                                <?php
// ciclo di estrazione dei dati
                                for ($_nr = 1; $_nr <= $rpp; $_nr++)
                                {
                                    $dati3 = $result->fetch(PDO::FETCH_ASSOC);

                                    echo "<tr><td align=\"left\" width=\"150\"><font face=\"arial\" size=\"2\">\n";
                                    if($categoria != "")
                                    {
                                        echo $categoria[$dati3['gruppo']];
                                    }
                                    else
                                    {
                                        echo $dati3['gruppo'];

                                    }
                                    echo "&nbsp;</td>\n";
                                            
                                    printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">%s</td>", $dati3['venduto']);
                                    printf("</tr>");

                                    $_totale = $dati3['venduto'] + $_totale;
                                }
                                ?>
                            </table>

                        </td>
                        <td align="center" width="2%">
                            &nbsp;
                        </td>

                        <td align="left" width="49%">

                            <table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">
                                <tr>
                                    <td bgcolor="#FFFFFF" valign="top" width="150" align="left"><font face="arial" size="2">Nome</td>
                                    <td bgcolor="#FFFFFF" valign="top" width="50" align="right"><font face="arial" size="2">Valore</td>
                                </tr>
                                <?php
// ciclo di estrazione dei dati
                                for ($_nr = 1; $_nr <= $rpp; $_nr++)
                                {
                                    $dati3 = $result->fetch(PDO::FETCH_ASSOC);

                                    echo "<tr><td align=\"left\" width=\"150\"><font face=\"arial\" size=\"2\">\n";
                                    if($categoria != "")
                                    {
                                        echo $categoria[$dati3['gruppo']];
                                    }
                                    else
                                    {
                                        echo $dati3['gruppo'];

                                    }
                                    echo "&nbsp;</td>\n";
                                            
                                    printf("<td align=\"right\" width=\"50\"><font face=\"arial\" size=\"2\">%s</td>", $dati3['venduto']);
                                    printf("</tr>");

                                    $_totale = $dati3['venduto'] + $_totale;
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                </table>

                <table border="1" align="center" width="90%"><br>
                    <tr>
                        <td width="20%" bgcolor="#FFFFFF" align="left"><font face="arial" size="1"><i>Pagina </i><? echo $_pg;?> di <? echo $pagina; ?></font></td>
                        <td align="center"> Valore Totale Venduto di magazzino == <?php echo $_totale; ?></td>
                        <td width="20%" bgcolor="#FFFFFF" align="right"><font face="arial" size="1"><i>Pagina </i><? echo $_pg;?> di <? echo $pagina; ?></font></td>
                    </tr>
                </table>
                <br><br>
            </body>
        </html>

        <?php
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>