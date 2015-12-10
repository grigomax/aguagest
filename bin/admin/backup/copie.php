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
ini_set("max_execution_time", 0);
ini_set("memory_limit", "256M");

session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

// --no-data=TRUE
if ($_SESSION['user']['setting'] > "3")
{

    
//Backup directory and filename
    //secondo me.. bisogna generare tre situazioni..
    // la prima legge i dati negessati 
    // la seconda scrive quello che si vuole..
    // la terza esegue..
    // farei tre funzioni..
    //la prima legge e prepara la struttura..
    //la seconda legge e prepara i dati
    // la terza chiude il file..
    // quando si prepano i dati gli sriviamo già sull'output così non memorizziamo niente..

    function getTablesList($conn)
    {
        $tables = array();

        $query = "SHOW TABLES";

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

        return $result;

    }

    function scrivi_dati($table, $conn)
    {
        //$sqldump = "";

// adding TABLE data to backup   
        $query = "SELECT * FROM $table";
        
        $conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
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

        $num_fields = $result->columnCount();
        //echo $num_fields."<br>";
        //$num_fields = 10;

        foreach ($result AS $row)
        {
            //$sqldump = 'INSERT INTO ' . $table . ' VALUES(';
            echo 'INSERT INTO ' . $table . ' VALUES(';
            //$sqldump.= 'INSERT INTO bv_bolle VALUES(';

            for ($j = 0; $j < $num_fields; $j++)
            {
                $row[$j] = addslashes($row[$j]);

                if (isset($row[$j]))
                {
                    //$sqldump.= '"' . $row[$j] . '"';
                    echo '"' . $row[$j] . '"';
                }
                else
                {
                    //$sqldump.= '""';
                    echo '""';
                }

                if ($j < ($num_fields - 1))
                {
                    //$sqldump.= ',';
                    echo ',';
                }
            }

            //$sqldump.= ");\n";
            echo ");\n";

            //scriviamo i dati.

            //invia_dati($sqldump, $filename);
//azzeriamo
            //unset($sqldump);
        }
        $result->closeCursor();
        //unset($result);


        //$sqldump = "\n\n\n";
        echo "\n\n\n";

        //invia_dati($sqldump, $filename);

        //azzeriamo
        //unset($sqldump);
    }

    function scrivi_struttura($table, $conn)
    {
        //global $handle;

        //$sqldump = "";
// adding DROP TABLE to backup  
        //$sqldump = 'DROP TABLE IF EXISTS ' . $table . ';';
        echo 'DROP TABLE IF EXISTS ' . $table . ';';

// adding CREATE TABLE to backup   
        $query = "SHOW CREATE TABLE $table";

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

        foreach ($result AS $createtable)
            ;

        //$createtable = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));


        //$sqldump.= "\n\n";
        //$sqldump.= $createtable[1];
        //$sqldump.= ";\n\n";
        echo "\n\n";
        echo $createtable[1];
        echo ";\n\n";

        //qui scriviamo la riga e la svuotiamo..

        //invia_dati($sqldump, $filename);
        //svuotiamo la riga 
        unset($sqldump);
        unset($result);

        //return $sqldump;
    }

    function invia_dati($sqldump, $filename)
    {
        global $handle;

        //echo $sqldump;
        fwrite($handle, $sqldump);
    }

    //due funzioni una apre e chiude il file
    // l'altra scrive i dati..

    function writeFile($cosa, $handle, $filename)
    {
        global $_percorso;

        if ($cosa == "apri")
        {
            //$return = fopen('php://output', 'w');
            $return = fopen($_percorso . "../spool/$filename", 'a');
        }
        else
        {
            fclose($handle);
            $return = "chiuso";
        }

        return $return;
    }

// MAIN SCRIPT 
// 
    if (($_POST['struttura'] == "SI") AND ( $_POST['dati'] == ""))
    {
        $filename = "copie_agua_struttura_" . date('Y-m-d') . ".sql";
    }
    elseif (($_POST['dati'] == "SI") AND ( $_POST['struttura'] == ""))
    {
        $filename = "copie_agua_dati_" . date('Y-m-d') . ".sql";
    }
    else
    {

        $filename = "copie_agua_" . date('Y-m-d') . ".sql";
    }

    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('Content-Type: text/sql; charset=utf-8');
    header("Content-Disposition: attachment; filename=$filename");


    $sqldump = "";


    $result = getTablesList($conn);
    
    
    foreach ($result as $table)
    {
        global $filename;

       // $handle = writeFile("apri", $handle, $filename);
        
        if (($_POST['struttura'] == "SI") AND ( $_POST['dati'] == ""))
        {
            scrivi_struttura($table[0], $conn);
        }
        elseif (($_POST['dati'] == "SI") AND ( $_POST['struttura'] == ""))
        {
            scrivi_dati($table[0], $conn);
        }
        else
        {
            scrivi_struttura($table[0], $conn);
            scrivi_dati($table[0], $conn);
        }
        //writeFile("chiudi", $handle, $filename);
        
    }

    $conn-> null;
    
    
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>