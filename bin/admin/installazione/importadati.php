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
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);
        echo "<BODY background=\"" . $_percorso . "images/aguaback.jpg\">\n";
echo "<center>\n";
echo "<h2>Installazione Agua Gest Passo 4 di 6</h2>\n";
echo "<h3>Importazione struttura database</h3>\n";

function importa_file_sql($sqlfile)
{
    global $conn;

    // estraggo il contenuto del file
    $queries = file_get_contents($sqlfile);
    // Rimuovo eventuali commenti
    $queries = preg_replace(array('/\/\*.*(\n)*.*(\*\/)?/', '/\s*--.*\n/', '/\s*#.*\n/'), "\n", $queries);
    // recupero le singole istruzioni
    $statements = explode(";\n", $queries);
    $statements = preg_replace("/\s/", ' ', $statements);
    // ciclo le istruzioni
    foreach ($statements as $query)
    {
        $query = trim($query);
        
        if ($query)
        {
            // eseguo la singola istruzione
            $conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
                
                
            }
        }
    }
    
    return $_errori;
}

//mysql -uNomeUtenteAmministratore -pRelativaPassword miodatabase < C:\miodump.sql.
//nome file 
$_file = $_percorso . "include/agua_struttura.sql";

//$_risposta = shell_exec("mysql --show-warnings --default-character-set=utf8 --user=$db_user --password=$db_password $db_nomedb  < $_file");

$_errori = importa_file_sql($_file);

if ($_errori != "")
{

	echo "Errore Importazione struttura Errore registrato\n";
	$_errori['descrizione'] = $_risposta;
	$_errori['files'] = "importadati.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<h3><font color=\"RED\">Installazione ha causato errori, vi preghiamo di contattare l'amministratore a<br>massimo@mcetechnik.it</font></h3>\n";

	echo "<pre>$_risposta </pre>\n";
}
else
{
	echo "<h3>importazione struttura eseguita con successo</h3>\n";
	echo "<h2>Installazione Agua Gest Passo 5 di 6</h2>\n";
	echo "<h3>Importazione struttura database</h3>\n";

	//mysql -uNomeUtenteAmministratore -pRelativaPassword miodatabase < C:\miodump.sql.
//nome file 

	$_file = $_percorso . "include/agua_base.sql";

	#$_risposta = shell_exec("mysql --verbose --show-warnings --default-character-set=utf8 --user=$db_user --password=$db_password $db_nomedb  < $_file");
	#$_risposta = shell_exec("mysql --show-warnings --default-character-set=utf8 --user=$db_user --password=$db_password $db_nomedb  < $_file");

        
        $_errori = importa_file_sql($_file);
        
	if ($_risposta != "")
	{

		echo "Errore Importazione dati base programma Errore registrato\n";
		$_errori['descrizione'] = $_risposta;
		$_errori['files'] = "importadati.php";
		scrittura_errori($_cosa, $_percorso, $_errori);
		echo "<h3><font color=\"RED\">Installazione ha causato errori, vi preghiamo di contattare l'amministratore a<br>massimo@mcetechnik.it</font></h3>\n";
		echo "<pre>$_risposta </pre>\n";
	}
	else
	{
		echo "<h3>importazione dati eseguita con successo</h3>\n";
		echo "<h3><br>Vai alla personalizzazione programma e poi hai finito!</h3>\n";

		echo "<h3><a href=\"end.php\"> Vai alla fine</a></h3>\n";
	}
}
?>
