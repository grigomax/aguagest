<?php
/*
 * Agua_gest programma gestionale by Grigolin Massimo
 * aguagest.sourceforge.net - massimo@mcetechnik.it
 * Programma distribuito secondo licenza GNU GPL
  programma che mi serve per richiamare tutte le librerie html nelle pagine
 * ed anche il javascript
 * 
 * Visto che la libreria html diciamo è la struttura base del programma vorrei passarle anche la funzione di sicurezza..
 * 
 *  */

/*ISTRUZIONI  VELOCI PER IL COLLEGAMENTO AL DATABASE PDO
 * 
 * $query;
$result = $conn->query($query);
if ($conn->errorCode() != "00000")
{
    $_errore = $conn->errorInfo();
    echo $_errore['2'];
    //aggiungiamo la gestione scitta dell'errore..
    $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
    scrittura_errori($_cosa, $_percorso, $_errori);
    $return['descrizione'] = $_errori['descrizione'];
    $return['query'] = $query;
    $return['result'] = "NO";
}
 * Poi qui o la multipla con un forearc
foreach ($result as $return)
 * 
 * oppure la singola con 
$dati = $result->fetch(PDO::FETCH_ASSOC);


//cerca numero righe
 * if ($result->num_rows > 0)
        


 */

//define("gestione_errori", "gestione_errori");

if(isset($DEBUG))
{
    //modalità debug
    if ($DEBUG == "SI")
    {
        set_error_handler("gestione_errori");
    }
    else
    {
        set_error_handler("gestione_errori", E_ALL & ~E_DEPRECATED);
    }
}
else
{
    set_error_handler("gestione_errori", E_ALL & ~E_DEPRECATED);
}



//funzione sperimentale che mi consente di verificare una sessione aperta..
//evitiamo il primo ingresso
if (!isset($_POST['entra']))
{
    //se non è il primo ingresso verifichiamo altrimenti cacciamo fuori
    //verifichiamo la presenza di una sessione..
    if (!isset($_SESSION['user']))
    {

        // se l'utente non è presente lo caccio fuori
        echo "<!DOCTYPE html>\n";
        echo "<html lang=\"it\">\n";
        echo "<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
        #echo "<meta http-equiv=\"Expires\" content=\"-1\" />\n";
        #echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
        echo "<LINK REL=\"shortcut icon\" HREF=\"" . $_percorso . "images/favicon.ico\">\n";
        echo "<title>$title</title>\n";
        echo "<link rel=\"stylesheet\" href=\"" . $_percorso . "css/globale.css\" type=\"text/css\">\n";

        echo "<center><h1><br><b>Agua Gest gestione sicurezza..</b></h1>";
        echo "<center><h1><font color=RED>Utente non riconoscuto</h1></font>";
        echo "<h3><br>Riprova premendo qui ---> <a href=\"" . $sito . "/index.php\">Login</a></h3></center>";
        $_errori['descrizione'] = "Tentativo di accedere alle cartelle interne da $_SERVER[REMOTE_ADDR] proveniente da $_SERVER[HTTP_REFERER] con user agent $_SERVER[HTTP_USER_AGENT]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);

        //Elimino le sessioni
        $_SESSION = array();
        session_destroy();



        //carico la sessione con la connessione al database..
        $conn = permessi_sessione("verifica_PDO", $_percorso);

        //qui decidiamo di registrarlo..
        tabella_banned_ip("inserisci", $_SERVER[REMOTE_ADDR], $volte, $_SERVER[HTTP_USER_AGENT]);

        //facciamo il check
        $check = tabella_banned_ip("check", $_SERVER[REMOTE_ADDR], '5', $user_agent);


        if ($check == "SI")
        {
            header("Location: http://aguagest.sourceforge.net/");
        }
        else
        {
            header("location: " . $sito . "/index.php?msg=Error_0");
        }

        exit();
    }
}

function primo_ingresso($_user, $_password, $_parametri)
{
    global $sito;
    global $_percorso;


    //connettiamoci al database.

    $conn = connessione_mysql('PDO', $query, $_parametri);


    //facciamo il check
    $check = tabella_banned_ip("check", $_SERVER[REMOTE_ADDR], '5', $user_agent);

    if ($check == "SI")
    {
        if($_user == "admin")
        {
            echo "<h2>Controllo Credenziali ADMIN</h2>\n";
        }
        else
        {
            header("Location: http://aguagest.sourceforge.net/");
            exit(); 
        }
        
    }


    //---------------------------------------------------------------
    // query

    $result = $conn->prepare("SELECT * FROM utenti WHERE user=? AND pwd=?");

    $result->bindParam(1, $_user);

    $result->bindParam(2, $_password);

    $result->execute();

    if ($result->rowCount() == 1)
    {

        //vuol dire che l'utente esiste quindi possiamo settargli le sessioni..
        // se la riposta è positiva ok verifico altrimenti out
        //prendo i dati
        foreach ($result AS $dati)
            ;

        //ora prima di dare l'ok verifichiamo che l'utente non sia bloccato..

        if ($dati['blocco'] == "SI")
        {
            $_return = "NO";
            header("location: ../index.php?msg=Error_4");
            exit();
        }
        else
        {
            //tutto ok
            $_SESSION['user'] = $dati;
            $_return = "OK";
        }
    }
    else
    {

        // se l'utente non è presente lo caccio fuori
        echo "<center><h1><br><b>Agua Gest gestione sicurezza..</b></h1>";
        echo "<center><h1><font color=RED>Utente non riconoscuto</h1></font>";
        echo "<h2><br>Nome utente o password ERRATI ! </h2>\n";
        echo "<h3><br>Riprova premendo qui ---> <a href=\"" . $sito . "/index.php\">Login</a></h3></center>";
        $_errori['descrizione'] = "Tentativo Utente non riconosciuto o inesistente da $_SERVER[REMOTE_ADDR] proveniente da $_SERVER[HTTP_REFERER] con user agent $_SERVER[HTTP_USER_AGENT]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);

        //Elimino le sessioni
        $_SESSION = array();
        session_destroy();

        //a questo punto lo scriviamo nel database..
        //qui decidiamo di registrarlo..
        tabella_banned_ip("inserisci", $_SERVER[REMOTE_ADDR], $volte, $_SERVER[HTTP_USER_AGENT]);

        //facciamo il check
        $check = tabella_banned_ip("check", $_SERVER[REMOTE_ADDR], '5', $user_agent);


        if ($check == "SI")
        {
            header("Location: http://aguagest.sourceforge.net/");
        }
        else
        {
            header("location: ../index.php?msg=Error_1");
        }

        exit();
    }


    //qui inseriamo il tipo nel database di riconoscimento..
    return $_return;
}





function verifica_installazione($user, $password)
{
    global $_percorso;
    global $conn;
    global $sito;

    //verifichiamo che ill'inetrno del file di configurazione sia tutto ok

    if (file_exists($_percorso . "../setting/vars.php"))
    {
        include $_percorso . "../setting/vars.php";
    }
    else
    {
        echo "<style=\"font-weight: bold;\"><font color=\"RED\">";
        echo "<br><br><h1>Errore Generale..! = File settaggi non trovato..</h1></font></font></span>";
        echo "<br><br>Il file vars.php contenuto all'interno della cartella agua/setting Non viene rilevato";
        echo "<br><br><br>Impossibile proseguire contattare l'amministratore del programma </font></a>";
        echo "<br>";
        $_return = "blocca";
    }

    if (($host == "") or ( $db_server == "") or ( $db_user == "") or ( $db_nomedb == ""))
    {
        echo "<style=\"font-weight: bold;\"><font color=\"RED\">";
        echo "<br><br>Errore 2 = File variante base vuoto</font></font></span>";
        echo "<br><br>Il file vars.php contenuto all'interno della cartella agua/setting non contiene le variabili come localhost e nome database e password non vuote..";

        if ($user == "admin")
        {
            echo "<br><br>Se Questa è una nuova installazione del programma Inizia la procedura <a href=\"admin/installazione/install.php\"><B>INSTALLAZIONE</B></a></font>";
            echo "<br>\n";
            echo "<br>Se invece si intende recuperare una installazione segui la procedura <a href=\"\">Recovery</a> Futuramente</font>";
        }
        else
        {
            echo "<br><br><br>Impossibile proseguire contattare l'amministratore del programma </font></a>";
        }


        echo "<br>";
        $_return = "blocca";
    }
    else
    {

        // Ora mi connetto al database per prelevare le versioni del programma..
        $conn = connessione_mysql('PDO', $query, $_parametri);

        $query = ("SELECT * FROM version WHERE id='1' ");

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

        foreach ($result AS $dati)
            ;

        //verifico aggiornamento programma
        require "include/version.inc.php";

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

        foreach ($result AS $datianno)
            ;

        if ($datianno['anno'] != date('Y'))
        {
            $_return = "ATTENZIONE:  si sta accedendo al programma con anno diverso dall' anno utenza<br>
	    Si prega di prestare molta attenzione ";
        }

        // se il programma coincide bene altrimenti blocco
        if (($dati['aguagest'] != $AGUAGEST) OR ( $dati['aguabase'] != $AGUABASE))
        {
            echo "<!DOCTYPE html>\n";
            echo "<html lang=\"it\">\n";
            echo "<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
            echo "<LINK REL=\"shortcut icon\" HREF=\"images/favicon.ico\">\n";
            echo "<title>Agua Gest - UPDATE</title>\n";
            echo "<BODY background=\"images/aguaback.jpg\">\n";
            echo "<h1 align=\"center\"><font color=\"yellow\">Agua Gest </font><font color=\"red\"> Attenzione</font></h2>";
            echo "<h2 align=\"center\">La Versione installata &egrave; diversa da quella in uso</h2>";
            echo "<h3 align=\"center\">Versione Agua in uso = $dati[aguagest] versione Agua installata $AGUAGEST</h3>\n";
            echo "<h3 align=\"center\">Versione Archivi in uso = $dati[aguabase] versione Archivi installati $AGUABASE</h3>\n";

            if ($user == "admin")
            {

                //verifichiamo le credenziali dell'amministratore..
                $admin = primo_ingresso($user, $password, $dati);

                if ($admin == "OK")
                {
                    echo "<h1 align=\"center\">Autentificazione verificata</h1>";

                    echo "<h2 align=\"center\">Benvenuto amministratore nel programma di aggiornamento di agua..</h2>";

                    echo "<h3 align=\"center\">Questo programma aggiorner&agrave; agua all'ultima versione..</h3>";

                    echo "<h3 align=\"center\">Premi qui sotto per proseguire nell'aggiornamento</h3>";

                    echo "<h3 align=\"center\"><a href=\"admin/upgrade/aggiorna.php\">Aggiorna.. </a></h3>";

                    echo $_SESSION['user']['user'];

                    $_return = "blocca";
                }
                else
                {
                    echo "Password errata..";
                    $_return = "blocca";
                }
            }
            else
            {
                echo "<h3 align=\"center\"><font color=\"red\">Vi preghiamo di aggiornare il programma prima dell'uso</font></h3>";
                echo "<h3 align=\"center\">Leggete il manuale per vedere la procedura</h3>";
                echo "<h3 align=\"center\"><a href=\"manuale/contenuti/M0121.html\" target\"_blanck\">Apri pagina manuale..</a></h3>";

                echo "<a HREF=\"#\" onClick=\"history.back()\">Torna Indietro</a>\n";

                $_return = "blocca";
            }
        }
    }

    return $_return;
}

/**
 * Funzione di verifica connessione.. controllo che l'utente esiste altrimenti lo caccia fuori..
 * Se tutto ok ritorna la connessione al database..
 * @global type $sito
 * @param type tipologia di connessione verifica oppure verifica_PDO
 * @param type $_percorso
 * @return type $conn che è la connessione a mysql.
 */
function permessi_sessione($_cosa, $_percorso)
{
    global $sito;
    global $_percorso;

    if (($_cosa == "verifica") or ( $_cosa == "verifica_PDO"))
    {
        // recupero le variabili di sessione
        $_user = $_SESSION['user']['user'];
        $_passwd = $_SESSION['user']['passwd'];

        // se non esistono vuote caccio fuori tutti
        if (empty($_user) and ( $_passwd) == true)
        {
            #echo "<body bgcolor=\"#d6e4f9\" text=\"#053487\" link=\"#053487\" vlink=\"#053487\" alink=\"#053487\">";
            echo "<td width=\"85%\" align=\"center\" valign=\"top\">";
            echo "<span class=\"intestazione\"><br><b>Agua Gest sezione sicurezza..</b></span><br>";
            echo "<center> <h1><font color=RED>Utente non riconoscuto</h1></font>";
            echo "<h2>Dati Sessione utente scaduti si prega di Ripresentarsi</h2>";
            echo "<h3>Vai al login ---> <a href=\"$sito/index.php\">Login</a></h3></center>";
            echo "prima";



            //Elimino le sessioni
            unset($_SESSION['user']);
            $_SESSION = array();
            session_destroy();
            exit;
        }
        else
        {
            //se tutto ok connettiamo al database..
            if ($_cosa == "verifica_PDO")
            {
                //ci connettiamo al database in modalità ad oggetti
                $conn = connessione_mysql("PDO", $query, $_parametri);
            }
            else
            {
                $conn = connessione_mysql($_cosa, $query, $_parametri);
            }
            return $conn;
        }
    }
    else
    {
        echo "<td width=\"85%\" align=\"center\" valign=\"top\">";
        echo "<span class=\"intestazione\"><br><b>Agua Gest sezione sicurezza..</b></span><br>";

        if ($_cosa == "scaduta")
        {
            echo "<span class=\"intestazione\"><br><b>La sessione è scaduta.. Devi ripresentarti</b></span><br>";
            echo "<span class=\"intestazione\"><br><b><a href=\"" . $sito . "/index.php\">Torna al login premendo qui..!</a></b></span><br>";
            echo "</td></tr>\n";
        }
        else
        {
            echo "<span class=\"intestazione\"><br><b>Non hai i permessi per visualizzare la pagina oppure la sessione è scaduta</b></span><br>";
            echo "<span class=\"intestazione\"><br><b><a href=\"" . $sito . "/index.php\">Torna al login premendo qui..!</a></b></span><br>";

            echo "</td></tr>\n";
        }

        echo "</table></body></html>";
    }
}

/**
 * La funzione mi permette di connettermi al database in qualsiasi momento..
 * @global type $db_server
 * @global type $db_user
 * @global type $db_password
 * @global type $db_nomedb
 * @return type $conn Variabile per la connessione..
 */
function connessione_mysql($_cosa, $query, $_parametri)
{

    global $db_server;
    global $db_user;
    global $db_password;
    global $db_nomedb;
    global $_percorso;
    global $conn;

    //se non esiste includimi il file
    if (!isset($db_server))
    {
        include $_percorso . "../setting/vars.php";
    }

    if ($_cosa == "PDO")
    {
        if (isset($conn) == false)
        {
            try
            {
                //$conn = new PDO("mysql:host=$db_server;dbname=$db_nomedb", $db_user, $db_password, array(PDO::ATTR_PERSISTENT => TRUE));
                $conn = new PDO("mysql:host=$db_server;dbname=$db_nomedb", $db_user, $db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            } catch (PDOException $e)
            {
                echo 'Errore di connessione: ' . $e->getMessage();
                echo "<span class=\"testo_blu\"><br>Non trovo il database server</span><br>";
                $_errori['descrizione'] = "errore Non trovo il server" . $e->getMessage();
                $_errori['files'] = "lib_html.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }
        }
    }
    elseif ($_cosa == "mysqli")
    {
        
        
    }
    else
    {
        if (!( $conn = @mysql_connect($db_server, $db_user, $db_password) ))
        {
            echo "<span class=\"testo_blu\"><br>Non trovo il database server</span><br>";
            $_errori['descrizione'] = "errore Non trovo il server";
            $_errori['files'] = "lib_html.php";
            scrittura_errori($_cosa, $_percorso, $_errori);

            exit(0);
        }
//Uso il database canis...
        if (!@mysql_select_db($db_nomedb, $conn))
        {
            echo "<span class=\"testo_blu\"><br><b>Non trovo il database</b></span><br>";
            $_errori['descrizione'] = "errore Non trovo database";
            $_errori['files'] = "lib_html.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            exit(0);
        }
    }

    return $conn;
}


/**Funzione che mi connette al database e mi da risposte
 * 
 * @param type $_cosa indica se query o exec
 * @param type $query
 * @param type $_parametri verbose mi ma apparire a video i messaggi
 * @return string
 */
function domanda_db($_cosa, $query, $_ritorno, $_parametri)
{
    global $conn;
    global $_percorso;

    //echo $_parametri;
    //qui passiamo le queri per la domanda.. così da poter avere sempre un risultato corretto 
    //echo "<br>$query\n";

    if ($_ritorno == "solo_fetch")
    {
        $return = $_parametri->fetch(PDO::FETCH_ASSOC);
    }
    else
    {


        if ($_cosa == "exec")
        {
            $result = $conn->exec($query);
        }
        else
        {
            $result = $conn->query($query);
        }

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['descrizione'] = $_errori['descrizione'];
            $return['query'] = $query;
            $return['result'] = "NO";
        }

        if ($_cosa == "exec")
        {
            if ($result == FALSE)
            {
                //echo "ciao";
                $result->null;
                $return = "NO";
            }
        }
        else
        {
            if ($result->rowCount() < 1)
            {
                //echo "ciao";
                $result->null;
                $return = "NO";
                //echo $_parametri;
                //echo $result;
            }
        }

        if ($return == "NO")
        {
            if ($_parametri == "verbose")
            {
                echo "<center><h4><font color=\"green\">Errore Nessuna Corrispondenza trovata</font></h4>\n";
            }

            if ($_parametri == "block")
            {
                echo "<h2 align=\"center\">Errore query</h2>\n";
                echo "<br>$_errori[descrizione]\n";
                echo "<br>$query\n";
                echo "<br>procedura bloccata\n";
                exit;
            }
        }
        else
        {
            if ($_ritorno == "fetch")
            {
                $return = $result->fetch(PDO::FETCH_ASSOC);
            }
            else
            {
                $return = $result;
            }
        }
    }



    return $return;
}

/**
 * Funzione che scrive gli errori si output su un file dentro include..
 * @global type $sito
 * @param type $in_errno
 * @param type $in_errstr
 * @param type $in_errfile
 * @param type $in_errline
 * @param type $in_errcontext 
 */
function gestione_errori($in_errno, $in_errstr, $in_errfile, $in_errline, $in_errcontext)
{
    global $sito;
    global $_percorso;

    //elenco errori..
    //echo $in_errno."<br>";
    //echo $in_errstr."<br>";
    //echo $in_errfile."<br>";
    //echo $in_errline."<br>";

    $errs = array(
        2 => 'AVVERTIMENTO',
        8 => 'Avviso non pericoloso',
        256 => 'E_USER_ERROR',
        512 => 'E_USER_WARNING',
        1024 => 'E_USER_NOTICE',
        2048 => 'Errore settaggio variabili',
        8192 => 'Errore Funzione vecchia',
    );

    $err_type = '';
    foreach ($errs as $val => $errstr)
    {
        if (($in_errno & $val) != 0)
        {
            $err_type .= "$errstr ";
        }
    }

    //qui decidiamo se vederle noi
    #if (($in_errno != "8") AND ($in_errstr != "Division by zero"))
    if (($in_errno != "8") AND ( $in_errno != "2"))
    {
        echo "$in_errno - \n";
        echo "$in_errstr - \n";
        echo "$in_errfile <br>\n";
    }

    //qui decidiamo se farle vedere all'utente
    //escludiamo dal riporto degli errori la funzione ereg le notizie e anche il settagggio variabili
    #if (($in_errno != "8") AND ($in_errno != "2048") AND ($in_errno != "8192") AND ($in_errstr != "Division by zero"))
    if (($in_errno != "8") AND ( $in_errno != "2048") AND ( $in_errno != "8192") AND ( $in_errno != "2"))
    {
        $_immagine = $_percorso . "images/kaboom.png";
        echo <<<EOTABLE
		
  <table align='center' width='75%' border='1' bgcolor='#dddddd'>
  <tr>
    <td valign='center' align='center'>
      <img src="$_immagine" border='0'/>
    </td>
    <td><font size=2>
      <b>Agua Gest Gestione degli errori <br/>Sono dispiaciuto ma &egrave; successo un errore..</b><br/>
      <b>$err_type:</b>($in_errfile, line $in_errline)<br/>
      $in_errstr<br/>
          <b>Errore Registrato correttamente del file log, da sottoporre all'amministratore</b></font>
    </td>
  </tr>
  </table>
		
EOTABLE;
    }

    //qui decidiamo se registrarle

    if (($in_errno != "8") AND ( $in_errno != "2048") AND ( $in_errno != "8192") AND ( $in_errstr != 'Division by zero'))
    {

        $_operazione = "|$err_type:|($in_errfile, line $in_errline)| $in_errstr |";


        error_log(date('d-m-Y/ H:m') . "|utente " . $_SESSION['user']['user'] . " |fallita operazione $_operazione\n", 3, $_percorso . "../spool/agua_php.log");

        // exit on errors, continue otherwise.
        if ($in_errno == E_USER_ERROR)
            exit;
    }
}

/* * Questa funzione mi permette di scrivere tutti gli errori in cosa ad un file sito in spool
 * qui posso tranquillamente mettetere tutto quello che voglio..
 *
 * @global type $conn
 * @param type $_cosa
 * @param type $_percorso
 * @param type $_errori
 * @return type dice se ha fatto oppure no..
 */

function scrittura_errori($_cosa, $_percorso, $_errori)
{
    global $conn;
    global $_percorso;

    date_default_timezone_set('Europe/Rome');
    //tipologia di errori

    $nfile = $_percorso . "../spool/agua_gest.log";
    // creo il files e nascondo la soluzione
    $fp = fopen($nfile, "a");
//controllo l'esito
    if (!$fp)
        die("Errore.. non sono riuscito a creare il file.. Permessi ?");

    //la variabile $_errori è un array contente ò'errore

    $_commento = date('d-m-Y/H:m') . "|utente " . $_SESSION['user']['user'] . " |fallita operazione $_errori[descrizione] | $_errori[files]\n";

    fwrite($fp, $_commento);
    if (!$fp)
        die("Errore.. Riga non inserita ?");

    // chiudiamo il files
    fclose($fp);

    return $_return;
}


/*
 * Funzione pag. base..
 */

function base_html($_cosa, $_percorso)
{
    global $title;
    global $azienda;
    global $sito;

    include $_percorso."../setting/vars_aspetto.php";

    echo "<!DOCTYPE html>\n";
    echo "<html lang=\"it\">\n";
    echo "<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store, must-revalidate\" />\n";
    echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
    echo "<meta http-equiv=\"Expires\" content=\"0\" />\n";

    echo "<LINK REL=\"shortcut icon\" HREF=\"" . $_percorso . "images/favicon.ico\">\n";
    echo "<title>$title</title>\n";
    echo "<link rel=\"stylesheet\" href=\"" . $_percorso . "css/globale.css\" type=\"text/css\">\n";

    //facciamo una prova di carica della sessione di java per 
    //la funzionalità di salva sessione

    java_script($_cosa, $_percorso);

    session_keepalive($_cosa, $_percorso);

    echo "<style>\n";
    
    //essendo i dati a cascata leggiamo i parametri utente se disponibili
    
    $SCREEN_FONT_SIZE = $_SESSION['user']['USER_SCREEN_FONT_SIZE'];
    $SCREEN_COLOR_BACKGROUND = $_SESSION['user']['USER_SCREEN_COLOR_BACKGROUND'];
    $SCREEN_WIDTH = $_SESSION['user']['USER_SCREEN_WIDTH'];
    $SCREEN_FONT_TYPE = $_SESSION['user']['USER_SCREEN_FONT_TYPE'];
    
    echo "BODY { 
        font-size: ".$SCREEN_FONT_SIZE."em;
        width: $SCREEN_WIDTH%;
        font-family: $SCREEN_FONT_TYPE;
        margin: auto; 
        background-color: $SCREEN_COLOR_BACKGROUND;
            
        
        }\n";
    
    //impostiamo la proporzione degli elementi:
    $SCREEN_INPUT_SIZE = "0.9";
    $SCREEN_SELECT_SIZE = "0.9";
    
    echo "select {
          font-size: ".$SCREEN_SELECT_SIZE."em;
                
    }
    
    input {
    font-size: ".$SCREEN_INPUT_SIZE."em;
    }
    
    input[type=\"checkbox\"]{
    width: ".$SCREEN_INPUT_SIZE."em; /*Desired width*/
    height: ".$SCREEN_INPUT_SIZE."em; /*Desired height*/
    }
    
    input[type=\"radio\"]{
    width: ".$SCREEN_INPUT_SIZE."em; /*Desired width*/
    height: ".$SCREEN_INPUT_SIZE."em; /*Desired height*/
    }
    
    \n";
    
    //iniziamo a spostare man mano i vari css..
    echo "span.testo_blu {font-family: $SCREEN_FONT_TYPE; font-size: ".($SCREEN_FONT_SIZE-0.2)."em; color: #053487; }\n";
    
    
    echo "</style>\n";

    if ($_cosa == "chiudi")
    {
        echo "</head>\n";
    }
}

//qui inseriamo la gestione della tabella banned ip
function tabella_banned_ip($_cosa, $ip_provenienza, $volte, $user_agent)
{
    //variabili globali..
    global $conn;
    global $_percorso;

    if ($_cosa == "inserisci")
    {

        //verifichiamo se ce se ce lo aggiorno altrimenti lo inserisco

        $check = tabella_banned_ip("check", $ip_provenienza, '0', $user_agent);

        if ($check == "SI")
        {
            //aggiorno..
            $query = "UPDATE banned_ip SET n_volte = n_volte + 1 WHERE ip_remoto ='$ip_provenienza'";
        }
        else
        {
            //inseriamo il giro nel database..

            $query = "INSERT INTO banned_ip (ip_remoto, n_volte, user_agent) VALUES ('$ip_provenienza', '1', '$user_agent')";
        }

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
    elseif ($_cosa == "cerca")
    {
        $query = "SELECT * FROM banned_ip WHERE ip_remoto = '$ip_provenienza'";

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

        foreach ($result AS $return)
            ;
    }
    elseif ($_cosa == "check")
    {

        $query = "SELECT * FROM banned_ip WHERE ip_remoto = '$ip_provenienza' AND n_volte > '$volte'";


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

        if ($result->rowCount() > 0)
        {
            //vuol dire che la riga è presente
            $return = "SI";
        }
        else
        {
            $return = "NO";
        }
    }
    elseif ($_cosa == "elimina")
    {

        $query = "DELETE FROM banned_ip WHERE ip_remoto='$ip_provenienza' limit 1";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return = "NO";
        }
        else
        {
            $return = "OK";
        }
    }
    else
    {
//elenco..
        $query = "SELECT * FROM banned_ip order by ip_remoto";

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

        $return = $result;
    }


    //ritorniamo esito
    return $return;
}

//gestione utenti..

function tabella_utenti($_cosa, $_id, $_user, $_password, $_blocca, $_parametri)
{
    //glocali
    global $conn;
    global $_percorso;



    if ($_cosa == "singola")
    {
        $query = "select * from utenti where id='$_id' limit 1";


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

        foreach ($result AS $return)
            ;
    }
    elseif ($_cosa == "singolo")
    {
        $query = "select * from utenti where user='$_user' limit 1";

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

        foreach ($result AS $return)
            ;
    }
    elseif ($_cosa == "check")
    {

        $query = "SELECT * FROM utenti WHERE user='$_user' limit 1";

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

        if ($result->rowCount() > 0)
        {
            $return = "SI";
        }
        else
        {
            $return = "NO";
        }
    }
    elseif ($_cosa == "elenca_select")
    {
        echo "<select name=\"id\">\n";
        echo "<option value=\"\"></option>";

        // Stringa contenente la query di ricerca...
        $query = "select * from utenti order by user";

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

        // Tutto procede a meraviglia...
        echo "<span class=\"testo_blu\">";
        foreach ($result AS $dati)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati['id'], $dati['user']);
        }

        echo "</select>\n";
        echo "</td></tr>\n";
    }
    elseif ($_cosa == "elenca_select_2")
    {
        echo "<select name=\"utente_end\">\n";
        echo $_id;
        $singola = tabella_utenti("singola", $_id, $_user, $_password, $_blocca, $_parametri);

        echo "<option value=\"$singola[id]\">$singola[user]</option>";


        // Stringa contenente la query di ricerca...
        $query = "select * from utenti order by user";

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

        foreach ($result AS $dati)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati['id'], $dati['user']);
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "inserisci")
    {

        $_user = addslashes($_parametri['user']);

        $query = "insert into utenti ( user, pwd, datareg, perm, anagrafiche, vendite, magazzino, contabilita, stampe, scadenziario, plugins, setting ) VALUES
	( '$_user', '$_parametri[pwd]', '$_parametri[datareg]', '$_parametri[perm]', '$_parametri[anagrafiche]', '$_parametri[vendite]', '$_parametri[magazzino]', '$_parametri[contabilita]', '$_parametri[stampe]',
	'$_parametri[scadenziario]', '$_parametri[plugins]', '$_parametri[setting]')";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $return = "NO";
        }
        else
        {
            $return = "OK";
        }
    }
    elseif ($_cosa == "modifica")
    {
        $_user = addslashes($_parametri['user']);
        //cerco la riga se c'�l'aggiorno, se non c'�la inserisco
        $query = "UPDATE utenti SET user='$_user', pwd='$_parametri[pwd]', perm='$_parametri[perm]', anagrafiche='$_parametri[anagrafiche]', 
	    vendite='$_parametri[vendite]', magazzino='$_parametri[magazzino]', contabilita='$_parametri[contabilita]', stampe='$_parametri[stampe]', 
	    scadenziario='$_parametri[scadenziario]', plugins='$_parametri[plugins]', setting='$_parametri[setting]', blocco='$_parametri[blocco]' where id='$_id'";
        //esegue la query

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $return = "NO";
        }
        else
        {
            $return = "OK";
        }
    }
    elseif ($_cosa == "aggiorna")
    {

        $query = "UPDATE utenti SET pwd='$_password' WHERE user='$_user'";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $return = "NO";
        }
        else
        {
            $return = "OK";
        }
    }
    elseif ($_cosa == "update")
    {

        $query = "UPDATE utenti SET USER_SCREEN_COLOR_BACKGROUND = '$_parametri[USER_SCREEN_COLOR_BACKGROUND]', USER_SCREEN_WIDTH = '$_parametri[USER_SCREEN_WIDTH]', USER_SCREEN_FONT_TYPE = '$_parametri[USER_SCREEN_FONT_TYPE]', USER_SCREEN_FONT_SIZE = '$_parametri[USER_SCREEN_FONT_SIZE]' WHERE user='$_user'";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $return = "NO";
        }
        else
        {
            $return = "OK";
        }
    }
    elseif ($_cosa == "elimina")
    {

        $query = sprintf("DELETE FROM utenti where id=\"%s\" limit 1", $_id);

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $return = "NO";
        }
        else
        {
            $return = "OK";
        }
    }
    else
    {
        //semrpe elenco
        $query = "SELECT * FROM utenti";

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

        foreach ($result AS $return)
            ;
    }




    return $return;
}

/**
 * Funzione che mi fa apparire la testata del programma..
 * @param type $_cosa
 * @param type $_percorso 
 */
function testata_html($_cosa, $_percorso)
{
    global $sito;
    global $azienda;
    global $SESSIONTIME;
    include $_percorso . "include/version.inc.php";

    //carichiamo il timer..
    //time_session($_cosa, $_percorso, $SESSIONTIME);

    echo "<body topmargin=\"0\">\n";
    //echo "<body onload=\"CreateTimer($SESSIONTIME)\" topmargin=\"0\">\n";

    echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" valing=\"TOP\">\n";
    echo "<tr>\n";
    echo "<td valign=\"middle\" bgcolor=\"#053487\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\">\n";
    echo "<img src=\"" . $_percorso . "images/aguagest.png\" height=\"30\" border=\"0\"> </a></font></td>\n";
    echo "<td bgcolor=\"#053487\"><font color=\"white\" size=\"1\">Versione Agua: $_PROGRAM_VERSION - Archivi : $AGUABASE<br>Azienda $azienda</font></td>\n";
    echo "<td bgcolor=\"#053487\" align=\"center\" valign=\"middle\"><font color=\"white\" size=\"1\">\n";
    echo "CopyRight 2003-2015&copy;<br>Grigolin Massimo</font></td>\n";
    echo "<td bgcolor=\"#053487\" valign=\"middle\" align=\"center\" ><font color=\"white\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Benvenuto <font color=\"yellow\">" . $_SESSION['user']['user'] . "</font></font><font size=\"1\"> <br> N.s. $_SESSION[keepalive]</font></font></font></td>\n";
    echo "</tr></table>\n";
}

function session_timeout($_cosa, $_percorso)
{
    //funzione inserita per ricordo e vedere come fare..
    session_start();
    $_SESSION['keepalive'] ++;
// configura il periodo di time-out in secondi
    $inactive = 600;
// controlla se la variabile $_SESSION["timeout"] 
// è presente
    if (isset($_SESSION["timeout"]))
    {
        // calcolare il "tempo di vita" della sessione 
        $sessionTTL = time() - $_SESSION["timeout"];
        if ($sessionTTL > $inactive)
        {
            session_destroy();
            header("Location: /logout.php");
        }
    }

    $_SESSION["timeout"] = time();
}


function menu_tendina($_cosa, $_percorso)
{
    global $sito;
    global $CONTABILITA;
    global $nomedoc;

    echo "<link rel=\"stylesheet\" href=\"" . $_percorso . "css/menu_tendina.css\" type=\"text/css\">\n";

    echo "<nav class=\"menu\">\n";
    echo "<ul class=\"menu\">\n";

    echo "<li class=\"menu\"><a href=\"$sito/bin/index.php\">Home</a><span class=\"dropBottom\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"icon home\"><a href=\"$sito/bin/index.php\">Inizio</a></li>\n";
    echo "<li class=\"icon utente\"><a href=\"$sito/bin/user/user.php?azione=std\">Gestione utente</a></li>\n";
    echo "<li class=\"icon privacy\"><a href=\"$sito/bin/user/user.php?azione=pwd\">Gestione Password</a></li>\n";
    echo "<li class=\"icon todo\"><a href=\"$sito/bin/user/todo.php?azione=nuova\">Nuova Cosa da Fare</a></li>\n";
    echo "<li class=\"icon todolist\"><a href=\"$sito/bin/user/lista_todo.php\">Lista Cose da fare</a></li>\n";

    echo "<li class=\"icon help\"><a href=\"#\">Aiuto</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/version.txt\" target=\"_blank\">Novita sulla Versione</a></li>";

    echo "<li class=\"menu\"><a href=\"#\">Manuale Utente</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/manuale/index.php\">Indice</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/manuale/stampa.php\" target=\"_blank\">Stampa Manuale</a></li>\n";

    if ($_SESSION[user][setting] == "4")
    {
        echo "<li class=\"menu\"><a href=\"$sito/bin/manuale/mod_elenco.php\">Modifica Pagina</a></li>\n";
    }

    echo "</ul>\n";
    echo "</li>\n";


    echo "<li class=\"menu\"><a href=\"http://aguagest.sourceforge.net/\" target=\"_blank\">Vai al sito AguaGest</a></li>";
    echo "<li class=\"menu\"><a href=\"https://sourceforge.net/p/aguagest/tickets/new/\" target=\"_blank\">Richiedi Assistenza</a></li>";
    echo "<li class=\"menu\"><a href=\"$sito/bin/include/gpl.txt\" target=\"_blank\">Licenza d'uso GPL Inglese</a></li>";
    echo "<li class=\"menu\"><a href=\"$sito/bin/include/gpl_it.txt\" target=\"_blank\">Licenza d'uso GPL Italiano</a></li>";
    echo "</ul>\n";
    echo "</li>\n";
    //fine sezione aiuto

    if ($_SESSION[user][setting] == "4")
    {
        echo "<li class=\"icon settings\"><a href=\"#\">Admin</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"#\">Parametri Aziendali</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/parametri/parametri.php?azione=modifica\">Parametri Azienda</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/parametri/aspetto.php\">Estetica Programma</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/parametri/para_conta.php\">Parametri contabilità</a></li>\n";

        echo "<li class=\"menu\"><a href=\"#\">Immagini Aziendali</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/parametri/immagini/seleziona_imm.php\">Carica Immagini</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/parametri/immagini/elenco_imm.php\">Gestisci immagini</a></li>\n";
        echo "</ul>\n";
        echo "</li>\n";

        echo "</ul>\n";
        echo "</li>\n";
        //fine parametri

        echo "<li class=\"menu\"><a href=\"#\">Gestione Documenti</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/documenti/mod_layout.php\">Modifica Layout</a></li>\n";

        echo "<li class=\"menu\"><a href=\"#\">Gestione Status</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/documenti/tools_documenti/doc_modstatus.php?tdoc=conferma\">Status Conferma</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/documenti/tools_documenti/doc_modstatus.php?tdoc=ddt\">Status DDT</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/documenti/tools_documenti/doc_modstatus.php?tdoc=FATTURA\">Status Fattura</a></li>\n";

        echo "</ul>\n";
        echo "</li>\n";
        echo "</ul>\n";
        echo "</li>\n";
        //fine documenti


        echo "<li class=\"menu\"><a href=\"#\">Assistenza Archivi</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/assistenza/ricmag.php\">Ricostr. Muovimenti</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/assistenza/rip_giac1.php\">Ripristino Giacenza</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/assistenza/opt_table.php\">Ott. e Pulizia</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/assistenza/repaire_table.php\">Ripara Archivi</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/assistenza/svuota_spool.php\">Svuota Cartella Temp.</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/assistenza/query_manuale.php\">Inserisci Query DB</a></li>\n";

        echo "</ul>\n";
        echo "</li>\n";
        //fine assistenza archivi

        echo "<li class=\"menu\"><a href=\"#\">Errori e Blocchi IP</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/errori/log_errori.php?files=agua_gest.log\" target=\"_blank\">Log AguaGest</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/errori/log_errori.php?files=agua_php.log\" target=\"_blank\">Log PHP</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/errori/visualizza_ip.php\">Elenco Accessi IP</a></li>\n";

        echo "</ul>\n";
        echo "</li>\n";
        //fine erriri

        echo "<li class=\"menu\"><a href=\"#\">Prog. Fine Anno</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/fine_anno/pingmagaz.php\">Chiusura e Riapertura Magazzino</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/fine_anno/ping_contabilita.php\">Chiusura e Riapertura Contabilità</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/fine_anno/elimina_doc.php\">Elimina Preventivi</a></li>\n";

        echo "</ul>\n";
        echo "</li>\n";
        //fine chiusure magazzino

        echo "<li class=\"menu\"><a href=\"#\">Backup</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/backup/back_archivi.php\">Backup Archivi</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/backup/salva_impostazioni.php\">Backup Parametri</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/backup/salva_fatturePA.php\">Backup Fattura PA</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/backup/salva_immagini.php\">Backup Immagini</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/backup/index.php\">Backup Plug-ins</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/upload/upload.php?cosa=parametri\">Carica Parametri</a></li>\n";

        echo "</ul>\n";
        echo "</li>\n";
        //fine chiusure magazzino

        echo "<li class=\"menu\"><a href=\"$sito/bin/admin/mod-user.php\">Gestione Utenti</a></li>\n";
        echo "<li class=\"menu\"><a href=\"http://sourceforge.net/projects/aguagest/files/\" target=\"_blank\">Verifica Aggiornamenti</a></li>\n";


        echo "</ul>\n";
        echo "</li>\n";
        //fine menu admin
    }
    echo "<li class=\"icon exit\"><a href=\"$sito/bin/logout.php\">Uscita</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";

    echo "<li class=\"menu\"><a href=\"#\">Anagrafiche</a><span class=\"dropBottom\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"#\">Articoli</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/ricerca.php\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/modificacod.php?azione=inserisci\">Nuovo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/modifica.php\">Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/duplica_cod.php\">Duplica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/sost_forn.php\">Sostituisci</a></li>\n";

    echo "<li class=\"menu\"><a href=\"#\">Immagini</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/immagini/seleziona_imm.php\">Carica Immagini</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/immagini/elenco_imm.php\">Visualizza Immagini</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/immagini/seleziona_dis.php\">Carica Disegni</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/immagini/elenco_dis.php\">Visualizza Disegni</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";

    echo "<li class=\"menu\"><a href=\"#\">Barcode</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/barcode/maschera_codbar.php?azione=cerca\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/barcode/maschera_codbar.php?azione=nuovo\">Nuovo codice</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";

    echo "<li class=\"menu\"><a href=\"#\">Listini Fornitore</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/articoli/listini_for/seleziona_file.php\">Carica</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";


    echo "</ul>\n";
    echo "</li>\n";

    //fine anagrafica articoli....


    echo "<li class=\"menu\"><a href=\"#\">Clienti</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/clifor/ricerca.php?tut=c\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/clifor/modifica_utente.php?tut=c&azione=nuovo\">Nuovo</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";

    //fine anagrafica clienti
    echo "<li class=\"menu\"><a href=\"#\">Fornitori</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/clifor/ricerca.php?tut=f\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/clifor/modifica_utente.php?tut=f&azione=nuovo\">Nuovo</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine anagrafica Forntori

    echo "<li class=\"menu\"><a href=\"#\">Destinazioni</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/clifor/ricerca_dest.php?tut=c\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/clifor/modifica_dest.php?tut=c&azione=seleziona\">Nuovo</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";
    //fine anagrafiche destinazioni
    
    echo "<li class=\"menu\"><a href=\"#\">Vettori</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/vettori/ricerca.php\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/vettori/modificavett.php?azione=nuovo\">Nuovo</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";
    //fine anagrafica Vettori

    echo "<li class=\"menu\"><a href=\"#\">Agenti</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/agenti/ricerca.php\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/agenti/modificaage.php?azione=nuovo\">Nuovo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/agenti/provage.php\">Provvigioni</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine anagrafica Agenti

    echo "<li class=\"menu\"><a href=\"#\">Pagamenti</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/pagamenti/ricerca.php\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/pagamenti/nuovo_pag.php\">Nuovo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/pagamenti/stampa_pag.php\" target=\"_blank\">Stampa</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine anagrafica Pagamenti

    echo "<li class=\"menu\"><a href=\"#\">Banche</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/banche/ricerca.php\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/banche/modificabanca.php?azione=nuova\">Nuova</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine anagrafica banche

    echo "<li class=\"menu\"><a href=\"#\">Aliquote</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/aliquote/ricerca.php\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/aliquote/modifica_iva.php?azione=nuova\">Nuova</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine anagrafica aliquote

    echo "<li class=\"menu\"><a href=\"#\">Zone</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/zone/mod-zone.php\">Gestione</a></li>\n";
    //echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/aliquote/ricerca.php\">Cerca</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine anagrafica zone

    echo "<li class=\"menu\"><a href=\"#\">Categorie Articoli</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/categorie/mod-gruppo.php?tipo=catmer\">Gruppi Merceologici</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/categorie/stampa_gruppo.php?tipo=catmer\" target=\"_blanck\">Stampa Gruppi</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/categorie/mod-gruppo.php?tipo=tipart\">Tipologie Articolo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/categorie/stampa_gruppo.php?tipo=tipart\" target=\"_blanck\">Stampa Tipologie</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine anagrafica categorie
    echo "<li class=\"menu\"><a href=\"#\">Prezzi</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/prezzi/l5-1.php\">Ricerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/prezzi/prezziper1.php\">Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/prezzi/prezziper_visualizza.php\">Stampa</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/prezzi/gen_prezzi_1.php\">Genera</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/prezzi/automa_cod.php\">Varia autom.</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";

    echo "<li class=\"menu\"><a href=\"$sito/bin/anagrafica/imballi/mod-imballi.php\">Imballi Gestione</a></li>\n";

    //fine anagrafica categorie
    echo "</ul>\n";

    //----------------------fine anagrafiche

    echo "</li>\n";
    echo "<li class=\"menu\"><a href=\"#\">Vendite</a><span class=\"dropBottom\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/nuovodoc.php\">Nuovo Doc.</a></li>\n";
    echo "<li class=\"menu\"><a href=\"#\">D.D.T.</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/nuovodoc.php?tdoc=ddt\">Nuovo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/ricercadoc.php?tdoc=ddt\">Cerca - Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docristasel.php?tdoc=ddt\">Ristampa - invia</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elencodoc.php?tdoc=ddt\">Elenco inseriti</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elenco_doc_xcli.php?tdoc=ddt\">Elenco per cliente</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/importa_doc.php?start=ordine&end=ddt\">Imp. Ordine</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/importa_doc.php?start=preventivo&end=ddt\">Imp. Preventivo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/importa_doc.php?start=conferma&end=ddt\">Imp. Conferma</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/doc_modint.php?tdoc=ddt\">Cambia Intestatario</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/controllo_numeri.php?tdoc=ddt\">Controllo numeri</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine gesione ddt

    echo "<li class=\"menu\"><a href=\"#\">Conferme</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/nuovodoc.php?tdoc=conferma\">Nuova</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/ricercadoc.php?tdoc=conferma\">Cerca - Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docristasel.php?tdoc=conferma\">Ristampa - invia</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elencodoc.php?tdoc=conferma\">Elenco inseriti</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elenco_doc_xcli.php?tdoc=conferma\">Elenco per cliente</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/importa_doc.php?start=ordine&end=conferma\">Imp. Ordine</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/importa_doc.php?start=preventivo&end=conferma\">Imp. Preventivo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/doc_modint.php?tdoc=conferma\">Cambia Intestatario</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/stampa_inevaso.php?tdoc=conferma&anno=2011%22\" target=\"_blank\">Stampa inevaso</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/confermed/prepara_ordini.php\">Prepara Ordini</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/controllo_numeri.php?tdoc=conferma\">Controllo numeri</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine gesione conferme

    echo "<li class=\"menu\"><a href=\"#\">Preventivi</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/nuovodoc.php?tdoc=preventivo\">Nuovo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/ricercadoc.php?tdoc=preventivo\">Cerca - Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docristasel.php?tdoc=preventivo\">Ristampa - invia</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elencodoc.php?tdoc=preventivo\">Elenco inseriti</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elenco_doc_xcli.php?tdoc=preventivo\">Elenco per cliente</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/doc_modint.php?tdoc=preventivo\">Cambia Intestatario</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/controllo_numeri.php?tdoc=preventivo\">Controllo numeri</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine gesione preventivi

    echo "<li class=\"menu\"><a href=\"#\">Ordini</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/nuovodoc.php?tdoc=ordine\">Nuovo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/ricercadoc.php?tdoc=ordine\">Cerca - Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docristasel.php?tdoc=ordine\">Ristampa - invia</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elencodoc.php?tdoc=ordine\">Elenco inseriti</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elenco_doc_xcli.php?tdoc=ordine\">Elenco per cliente</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/doc_modint.php?tdoc=ordine\">Cambia intestatario</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine gesione ordini

    echo "<li class=\"menu\"><a href=\"#\">Fatture</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/nuovodoc.php?tdoc=FATTURA\">Nuova</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/ricercadoc.php?tdoc=FATTURA\">Cerca - Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docristasel.php?tdoc=FATTURA\">Ristampa - invia</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elencodoc.php?tdoc=FATTURA\">Elenco inseriti</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elenco_doc_xcli.php?tdoc=FATTURA\">Elenco per cliente</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/fatturev/generafatt.php\">Genera Fatt.</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/importa_doc.php?start=ddt&end=FATTURA\">Imp. DDT</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/importa_doc.php?start=conferma&end=$nomedoc\">Imp. Conferma</a></li>\n";
    //echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/importa_doc.php?start=ddt_diretto&end=FATTURA\">Imp. DDT. diretto</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/fatturev/fatturato/ricercadoc.php\">Fatturato</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/fatturev/fattura_PA.php\">Esporta P.A.</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/controllo_numeri.php?tdoc=FATTURA\">Controllo numeri</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine gesione fatture

    echo "<li class=\"menu\"><a href=\"#\">Gestione Portafoglio</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/nuovo_eff.php\">Nuovo Effetto</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/ricerca_eff.php\">Cerca - Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/genera_eff.php\">Genera</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/elenco_eff.php\">Elenco Disponibili</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/stampa_scad.php\">Scadenziario</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/importa_fv.php\">Salda Fatture singole</a></li>\n";
    

    echo "</ul>\n";
    echo "</li>\n";
    //fine gesione effetti bancari

    echo "<li class=\"menu\"><a href=\"#\">Distinte bancarie</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/distinte/nuova_dist.php\">Nuova Distinta</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/distinte/modifica_dist.php\">Cerca - Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/distinte/ristampa_dist.php\">Ristampa - invia</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/distinte/esporta_dist.php\">Esporta in C.B.I.</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/effetti/salda_eff.php\">Salda Distinte</a></li>\n";


    echo "</ul>\n";
    echo "</li>\n";
    //fine gesione distimte

    echo "</ul>\n"; // fine vendite
    echo "</li>\n";

    //inizio Magazzino

    echo "<li class=\"menu\"><a href=\"#\">Magazzino</a><span class=\"dropBottom\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"#\">Ordini Fornitore</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/nuovodoc.php?tdoc=fornitore\">Nuovo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/ricercadoc.php?tdoc=fornitore\">Cerca - Modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docristasel.php?tdoc=fornitore\">Ristampa - invia</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elencodoc.php?tdoc=fornitore\">Elenco inseriti</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/elenco_doc_xcli.php?tdoc=fornitore\">Elenco fornitore</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/acquisti/ordiniacq/of_modstatus.php\">Cambia Status</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/stampa_inevaso.php?tdoc=fornitore&anno=2011%22\" target=\"_blank\">Stampa inevaso</a></li>\n";


    echo "</ul>\n";
    echo "</li>\n";
    //fine ordini

    echo "<li class=\"menu\"><a href=\"#\">DDT Acquisto</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/nuovodoc.php?tdoc=ddtacq\">Nuovo</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/acquisti/ddtacq/ricercadoc.php\">Cerca</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/acquisti/ddtacq/elencodoc.php\">Elenco inseriti</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/vendite/docubase/importa_doc.php?start=fornitore&end=ddtacq\">Imp. Ordine Fornitore</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/acquisti/ddtacq/controlloddt_acq.php\">Controllo numeri</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine ddt acquisti

    echo "<li class=\"menu\"><a href=\"#\">Gestione Resi</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/acquisti/ddt_r_cl/importa_brcl.php\">Importa ddt Reso</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/acquisti/importa_bv.php\">Imp. ddt div. da reso</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine ddt acquisti

    echo "<li class=\"menu\"><a href=\"#\">Magazzino</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/gestmag/muovimenti/ricercadoc.php\">Cerca Muovimento</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/gestmag/mod-p2.php\">Giacenze</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/gestmag/rimanenze.php\">Rimanenze Finali</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/gestmag/invemerce.php?tipo=merce\">Inventario</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/gestmag/invemerce.php?tipo=tipo\">Inventario per tip.</a></li>\n";
    

    echo "</ul>\n";
    echo "</li>\n";
    //fine ddt magazzino

    echo "<li class=\"menu\"><a href=\"#\">Imballi</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/imballi/imballi.php?tut=cliente\">Cerca per Cliente</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/mag/imballi/imballi.php?tut=fornitore\">Cerca per Fornitore</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";
    //fine MAGAZZINO

    echo "</ul>\n";
    echo "</li>\n";


    if ($CONTABILITA == "SI")
    {
        echo "<li class=\"menu\"><a href=\"#\">Contabilit&agrave;</a><span class=\"dropBottom\"></span>\n";
        echo "<ul class=\"menu\">\n";

        echo "<li class=\"menu\"><a href=\"#\">Registrazioni</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/ricerca.php\">Cerca Registrazione</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/prima_nota.php?azione=FA\">Fatture Acquisto</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/importa_fatt.php\">Fatture Vendita</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/importa_dist.php\">Distinte Bancarie</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/prima_nota.php?azione=ST\">Prima Nota</a></li>\n";


        echo "<li class=\"menu\"><a href=\"#\">Causali Contabili</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/causali_conta/maschera_causale.php?azione=nuovo\">Nuova</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/causali_conta/ricerca.php\">Cerca causale</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/causali_conta/stampa_causali.php\" target=\"_blank\">Stampa Causali</a></li>\n";

        echo "</ul>\n";
        echo "</li>\n";

        echo "<li class=\"menu\"><a href=\"#\">Controlli</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/controllo_Q.php\">Controllo Quadratura</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/check_fatt.php?azione=acq\">Sospesi per fornitori</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/registrazioni/check_fatt.php?azione=vend\">Sospesi per cliente</a></li>\n";

        echo "</ul>\n";
        echo "</li>\n";



        echo "</ul>\n";
        echo "</li>\n";
        //fine registrazioni

        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/ricerca_scheda.php\">Schede Contabili</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/bilancio/bilancio.php\">Gestione Bilancio</a></li>\n";

        echo "<li class=\"menu\"><a href=\"#\">Operazioni mensili ed annuali</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/libri_contabili/liquid_iva_period.php\">Liquidazione iva Periodica</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/libri_contabili/liquid_iva_period.php?azione=paga\">Pagamento iva Periodica</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/libri_contabili/liquid_iva_annuale.php\">Liquidazione iva Annuale</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/libri_contabili/libro_giornale.php\">Stampa libro giornale</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/libri_contabili/libro_partitari.php\">Stampa Partitari</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/libri_contabili/stampa_int.php\">Stampa Intestazioni</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/libri_contabili/intrastrat.php\">Stampa Intrastrat</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/libri_contabili/export_clifor.php?azione=nuova\">Exp. saldi clienti Fornitori</a></li>\n";

        echo "</ul>\n";
        echo "</li>\n";

        echo "<li class=\"menu\"><a href=\"#\">Piano dei Conti</a><span class=\"dropRight\"></span>\n";
        echo "<ul class=\"menu\">\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/piano_conti/maschera_conto.php?azione=nuovo\">Nuovo Conto</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/piano_conti/ricerca.php\">Ricerca e modifica</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/piano_conti/stampa_pianoc.php\" target=\"_blank\">Stampa Piano dei conti</a></li>\n";
        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/piano_conti/stampa_pianoc.php?azione=clifor\" target=\"_blank\">Stampa piano con clienti e fornitori</a></li>\n";

        echo "</ul>\n";
        echo "</li>\n";

        echo "<li class=\"menu\"><a href=\"$sito/bin/contabilita/spesometro/index.php\">Spesometro</a></li>\n";


        echo "</ul>\n";
        echo "</li>\n";
    }

    echo "<li class=\"menu\"><a href=\"#\">Scadenziario</a><span class=\"dropBottom\"></span>\n";
    echo "<ul class=\"menu\">\n";

    echo "<li class=\"menu\"><a href=\"$sito/bin/scadenziario/index.php\">Calendario</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/scadenziario/scadenza.php?azione=nuova\">Inserisci</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/scadenziario/cerca_scad.php\">Cerca e modifica</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/scadenziario/stampa_scad.php\">Stampa</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/scadenziario/esporta_dati.php\">Esporta</a></li>\n";


    echo "</ul>\n";
    echo "</li>\n";





    echo "<li class=\"menu\"><a href=\"#\">Stampe</a><span class=\"dropBottom\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"#\">Listini</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/listini/list_vendite.php?tipo=per_codice\">Per Codice</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/listini/list_vendite.php?tipo=catmer\">Per Categoria</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/listini/list_fig1.php\">Figurato per Categoria</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";

    echo "<li class=\"menu\"><a href=\"#\">Etichette</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/etichette/articoli/articolo.php\">Articoli</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/etichette/cli_for/normali.php?azione=clienti\">Clienti</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/etichette/cli_for/normali.php?azione=fornitori\">Fornitori</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";

    echo "<li class=\"menu\"><a href=\"#\">Anagrafiche</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/anagrafiche/utente_nome.php?utente=clienti\">Clienti per Selezione</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/anagrafiche/utente_scelta.php?utente=clienti\">Clienti per Categoria</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/anagrafiche/utente_nome.php?utente=fornitori\">Fornitori per Selezione</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/anagrafiche/utente_scelta.php?utente=fornitori\">Fornitori per Categoria</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";

    echo "<li class=\"menu\"><a href=\"#\">Magazzino</a><span class=\"dropRight\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/magazzino/venduto.php\">Venduto per mese</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/magazzino/ricarico.php\">Ricarico vendite</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/magazzino/disponibilita.php\">Disponibilita magazzino</a></li>\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/magazzino/venduto_anno.php\">Venduto per Anno</a></li>\n";
    echo "</ul>\n";
    echo "</li>\n";

    echo "<li class=\"menu\"><a href=\"$sito/bin/stampe/varie/privacycli.php\">Privacy cliente</a></li>\n";

    echo "</ul>\n";
    echo "</li>\n";

    //fine menu stampe..

    echo "<li class=\"menu\"><a href=\"#\">Plug-ins</a><span class=\"dropBottom\"></span>\n";
    echo "<ul class=\"menu\">\n";
    echo "<li class=\"menu\"><a href=\"$sito/bin/plugins/index.php\"\">Vedi plugins installati</a></li>";
    echo "</ul>\n";
    echo "</li>\n";
    //fine sezione aiuto


    echo "</ul>\n";


    echo "</nav>\n";
}

function base_html_stampa($_cosa, $_parametri)
{
    global $title;
    global $azienda;
    global $_percorso;
    
    require $_percorso . "../setting/vars_aspetto.php";
    
    echo "<!DOCTYPE html>\n";
    echo "<html lang=\"it\">\n";
    echo "<head>\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store, must-revalidate\" />\n";
    echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
    echo "<meta http-equiv=\"Expires\" content=\"0\" />\n";
    echo "<title>$title</title>\n";
    echo "<link rel=\"stylesheet\" href=\"" . $_percorso . "css/globalest.css\" type=\"text/css\">\n";
    //fissiamo i margini del parametro body qui dopo aver caricaro il foglio di stile, in in modo da poter
    //modificare trammite ptrogravva l'ampiezza della pagina ecc.

    //echo $PRINT_WIDTH;
    echo "<style>\n";
    
    if($_parametri['PRINT_FONT_SIZE'] != "")
    {
        $PRINT_FONT_SIZE = $_parametri['PRINT_FONT_SIZE'];
    }
    
    echo "BODY {

            position: relative;
	    font-size: ".$PRINT_FONT_SIZE."pt;
	    width: ".$PRINT_WIDTH."px;
            margin-left: 0px;
            margin: $_parametri[MARGINI] auto; 
            padding: $_parametri[PADDING];
            background-color: $_parametri[BACK];
            font-family: $PRINT_FONT_TYPE;
	    }\n";

    echo "a:link { color: #053487; text-decoration: none; }\n";
    echo "a:visited  { color: #053487; text-decoration: none; }\n";
    echo "a:hover    { color: #053487; text-decoration: none; }\n";

    
    

    if ($_cosa == "chiudi")
    {
        echo "</style></head>\n";
    }
}

function java_script($_cosa, $_percorso)
{
    echo "<link type=\"text/css\" href=\"" . $_percorso . "tools/jquery/themes/cupertino/jquery-ui.css\" rel=\"stylesheet\" />\n";
    echo "<script type=\"text/javascript\" src=\"" . $_percorso . "tools/jquery/jquery-1.9.1.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"" . $_percorso . "tools/jquery/jquery-migrate-1.2.1.min.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"" . $_percorso . "tools/jquery/ui/jquery-ui.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"" . $_percorso . "tools/jquery/autoNumeric.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"" . $_percorso . "tools/jquery/jquery.maskedinput-1.3.min.js\"></script>\n";
    #echo "<script type=\"text/javascript\" src=\"" . $_percorso . "tools/jquery/jquery.ui.datepicker-it.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"" . $_percorso . "tools/tinymce/tinymce.min.js\"></script>\n";
    #echo "<script type=\"text/javascript\" src=\"" . $_percorso . "tools/jquery_vc.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"" . $_percorso . "tools/fancybox/jquery.fancybox.pack.js\"></script>\n";
    //<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
}

/* Funzione menu a cascata
 * 
 */

function jquery_menu_cascata($_cosa, $_percorso)
{

    if ($_cosa == "ST")
    {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {

                var scegli = '<option value="0">Scegli...</option>';
                var attendere = '<option value="0">Attendere...</option>';

                $("select#utente").html(scegli);
                //$("select#utente").attr("disabled", "disabled");
                $("select#ndoc").html(scegli);
                //$("select#ndoc").attr("disabled", "disabled");


                $("select#causale").change(function () {
                    var causale = $("select#causale option:selected").attr('value');
                    $("select#utente").html(attendere);
                    $("select#utente").attr("disabled", "disabled");
                    $("select#ndoc").html(attendere);
                    $("select#ndoc").attr("disabled", "disabled");

                    $.post("prima_nota.php", {causale: causale}, function (data) {
                        $("select#utente").removeAttr("disabled");
                        $("select#utente").html(data);
                    });
                });

                $("select#utente").change(function () {
                    $("select#ndoc").html(attendere);
                    $("select#ndoc").attr("disabled", "disabled");
                    var utente = $("select#utente option:selected").attr('value');

                    $.post("prima_nota.php", {utente: utente}, function (data) {
                        $("select#ndoc").removeAttr("disabled");
                        $("select#ndoc").html(data);
                    });
                });


            });

        </script>

        <?php
    }
    elseif ($_cosa == "scheda")
    {
        ?>

        <script type="text/javascript">
            $(document).ready(function () {

                var scegli = '<option value="0">Scegli...</option>';
                var attendere = '<option value="0">Attendere...</option>';

                $("select#codconto").html(scegli);
                $("select#codconto").attr("disabled", "disabled");


                $("select#tipo_conto").change(function () {
                    var tipo_conto = $("select#tipo_conto option:selected").attr('value');
                    $("select#codconto").html(attendere);
                    $("select#codconto").attr("disabled", "disabled");

                    $.post("ricerca_scheda.php", {tipo_cf: tipo_conto}, function (data) {
                        $("select#codconto").removeAttr("disabled");
                        $("select#codconto").html(data);
                    });
                });
            });

        </script>
        <?php
    }
    elseif($_cosa == "base")
    {
        ?>

        <script type="text/javascript">
            $(document).ready(function () {

                var scegli = '<option value="0">Scegli...</option>';
                var attendere = '<option value="0">Attendere...</option>';

                $("select#codconto").html(scegli);
                $("select#codconto").attr("disabled", "disabled");


                $("select#tipo_conto").change(function () {
                    var tipo_conto = $("select#tipo_conto option:selected").attr('value');
                    $("select#codconto").html(attendere);
                    $("select#codconto").attr("disabled", "disabled");

                    $.post("<?php echo $_percorso; ?>", {tipo_cf: tipo_conto}, function (data) {
                        $("select#codconto").removeAttr("disabled");
                        $("select#codconto").html(data);
                    });
                });
            });

        </script>

        <?php
    }
    else
    {
        ?>

        <script type="text/javascript">
            $(document).ready(function () {

                var scegli = '<option value="0">Scegli...</option>';
                var attendere = '<option value="0">Attendere...</option>';

                $("select#codconto").html(scegli);
                $("select#codconto").attr("disabled", "disabled");


                $("select#tipo_conto").change(function () {
                    var tipo_conto = $("select#tipo_conto option:selected").attr('value');
                    $("select#codconto").html(attendere);
                    $("select#codconto").attr("disabled", "disabled");

                    $.post("corpo_nota.php", {tipo_cf: tipo_conto}, function (data) {
                        $("select#codconto").removeAttr("disabled");
                        $("select#codconto").html(data);
                    });
                });
            });

        </script>

        <?php
    }
}

/**
 * funzione che mi permette di avere il calendatio
 * 
 */
function jquery_datapicker($_cosa, $_percorso)
{
    ?>

    <script type="text/javascript">

        $(document).ready(function () {

            $('.data').datepicker({
                showOn: 'button',
                buttonImage: '<?php echo $_percorso . "images/calendar.png"; ?>',
                buttonImageOnly: true,
                showAnim: 'fadeIn',
                fixedChars: '-',
                dateFormat: 'dd-mm-yy'
            },
            $.datepicker.regional['it']
                    );


            jQuery(function ($) {
                $('.data').mask("99-99-9999");
            });

            //$('.txt_number').autoNumeric({aSep: '.', aDec: ','});

        });
    </script>

    <style>
        .ui-widget { font-family: Segoe UI, Arial, sans-serif; font-size: 12px; }
    </style>

    <?php
}

function jquery_tabs($_cosa, $_percorso)
{
    //modifiche personali alle css di java..
    ?>

    <script type="text/javascript">


        $(function () {

            // Tabs
            $('#tabs').tabs();

        });
    </script>

    <style type="text/css">
        .ui-tabs {
	position: inherit;/* position: relative prevents IE scroll bug (element with position: relative inside container with overflow: auto appear as "fixed") */
	padding: .2em;
        }
        
        .ui-tabs .ui-tabs-nav li {
	position: inherit;
        }
        
        .ui-tabs .ui-tabs-nav li a {
        font-size: 70%;
        }

    </style>



    <?php
}

function fancybox($_cosa)
{
    global $_percorso;

    echo "<link rel=\"stylesheet\" href=\"" . $_percorso . "tools/fancybox/jquery.fancybox.css\" type=\"text/css\" media=\"screen\" />\n";

    //echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $_percorso . "tools/fancybox/jquery.fancybox-1.3.4.css\" media=\"screen\" />\n";
    ?>
    <script>
        $(document).ready(function () {
            $('.fancybox').fancybox({
                padding: 0,
                openEffect: 'elastic'
            });
        });
    </script>


    <?php
}

/**
 * Funzione che mi richiama la sessione per non farla scadere
 * mediante una chiama ajax ad un file che si chiama keepalive.php
 * @param type $_cosa
 * @param type $_percorso 
 */
function session_keepalive($_cosa, $_percorso)
{
    ?>

    <script>
        var i;
        i = 0;

        var x = setInterval(function () {
            chiamata_ajax()
        }, 600000);

        function chiamata_ajax() {
            $.ajax({
                type: "POST",
                url: '<?php echo "$_percorso"; ?>keep_live.php',
                contentType: "text/html; charset=utf-8",
                dataType: "html",
                //      success: function (response) {
                //			if (i==0) {
                //              alert($(response).filter("#risultato").html())
                //		} else {
                //			alert($(response).filter("#risultato2").html())
                //			window.clearInterval(x);
                //		}
                //		i++;
                //},
                error: function () {
                    alert('Impossibile Caricale applicazione keepalive');
                    window.clearInterval(x);
                }
            });
        }
    </script>





    <?
    }

    function time_session($_cosa, $_percorso, $SESSIONTIME)
    {
    ?>
    <script>
        var Tim;

        var TotalSeconds;

        var timer_is_on;

        function CreateTimer(Time) {
            TotalSeconds = Time;
            timer_is_on = true;
            UpdateTimer();

            Tick();
        }



        function Tick() {
            TotalSeconds -= 1;
            if (TotalSeconds >= 0) {
                UpdateTimer()
            }
            clearTimeout(Tim);

            Tim = setTimeout("Tick()", 1000);
        }

        function UpdateTimer() {

            //$('#tempo').text(TotalSeconds);
            document.getElementById('tempo').innerHTML = TotalSeconds;

            if (TotalSeconds == 0) {
                timer_is_on = false;
                clearTimeout(Tim);
            }

        }

    </script>

    <?php
}

/**
 * funzione che mi crea il totale delle distinte
 * @param type $_cosa
 * @param type $_percorso 
 */
function jquery_seleziona_tot($_cosa, $_percorso)
{
    echo "<script src=\"$_percorso/tools/jquery_vc.js\" type=\"text/javascript\" ></script>\n";
    ?>


    <script type="text/javascript">


        $().ready(function () {

            $("input[@name*='check']").click(function () {
                trova_totale();
            });

            trova_totale();

        });

        function trova_totale() {
            var totale = 0;

            $("input[@name*='check']:checked").each(function () {
                totale += parseFloat($(this).attr("valore"));
            });

            $("input[@name=totale]").val(totale);

        }


    </script>
    <?php
}

/* funzione che mi abilita il compositore di messaggi
 * 
 */

function tiny_mce($_cosa, $_percorso)
{
    echo "<script type=\"text/javascript\">\n";

    echo "tinymce.init({\n";
    echo "selector: \"textarea\",\n";
    echo "theme: \"modern\",\n";
    echo "language : 'it',\n";
    echo "browser_spellcheck : true,\n";
    echo "plugins: [\n";
    echo "\"advlist autolink lists link image charmap print preview hr anchor pagebreak\",\n";
    echo "\"searchreplace wordcount visualblocks visualchars code fullscreen\",\n";
    echo "\"insertdatetime media nonbreaking save table contextmenu directionality\",\n";
    echo "\"emoticons template paste legacyoutput \"\n";
    echo "],\n";
    #echo "tools: inserttable,";
    echo "menubar: false,";
    echo "toolbar_items_size: 'small',";


    echo "toolbar1: \"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image\",\n";
    echo "toolbar2: \"print preview media | fontselect fontsizeselect forecolor backcolor emoticons visualchars | table | code\"\n";

    echo "});\n";

    echo "</script>\n";

#<!-- /TinyMCE -->
}

/* * Funzione che mi stampa l'intestazione della pagine sulle stampe..
 *
 *   $_parametri[data] = date('d-m-Y');
 *   $_parametri[stampa] = "differenze";
 *   $_parametri[anno] = $_anno;
 *   $_parametri['pg'] = $_pg;
 *   $_parametri[pagina] = $pagina;
 *   $_parametri[tabella] = "Differenza";
 */
function intestazione_html($_cosa, $_percorso, $_parametri)
{
    require $_percorso . "../setting/vars.php";
    
    if($_parametri['WIDTH'] != "")
    {
        $PRINT_WIDTH = $_parametri['WIDTH'];
    }
    else
    {
        $PRINT_WIDTH = "95%";
    }
    
    if($_parametri['intestazione'] == "0")
    {
        echo "<table border=\"0\" height=\"135px\" align=\"left\" width=\"$PRINT_WIDTH\">\n";
        echo "<tr>\n";
	echo "<td width=\"100%\" align=\"center\">\n";
        echo "&nbsp;\n";
        echo "</td>\n";
        echo "</tr></table>\n";
        
    }
    elseif($_parametri['intestazione'] == "1")
    {
        echo "<table border=\"0\" height=\"135px\" align=\"left\" width=\"$PRINT_WIDTH\">\n";
        echo "<tr>\n";
	echo "<td align=\"center\">\n";
        echo "<img src=\"" . $_percorso . "../setting/loghiazienda/$_parametri[intesta_immagine]\" width=\"$PRINT_WIDTH\">\n";
        echo "</td>\n";
        echo "</tr></table>\n";
    }
    elseif($_parametri['intestazione'] == "2")
    {
        echo "<table border=\"0\" height=\"135px\" align=\"left\" width=\"$PRINT_WIDTH\">\n";
        echo "<tr>\n";
        echo "<td colspan=\"2\" valign=\"top\" align=\"left\"><b>$azienda</b><br>$indirizzo<br>$cap $citta ($prov)<br>P.I. $piva<br>
          Tel. $telefono - Tel/Fax $fax <br>\n";
        
        if($_parametri['email'] == "3")
        {
            echo "e-mail : $email3\n";
        }
        elseif($_parametri['email'] == "2")
        {
            echo "e-mail : $email2\n";
        }
        else
        {
            echo "e-mail : $email1\n";
        }
        
        echo "</td></tr> </table>\n";
    }
    elseif($_parametri['intestazione'] == "3")
    {
        echo "<table border=\"0\" height=\"135px\" align=\"left\" width=\"$PRINT_WIDTH\">\n";
        echo "<tr><td align=\"left\"><h3>$azienda</h3></td></tr>\n";
        echo "<tr><td align=\"left\">$indirizzo</td></tr>\n";
        echo "<tr><td align=\"left\">$cap $citta $prov</td></tr>\n";
        echo "<tr><td align=\"left\">P.I. $piva / C.F. $codfisc</td></tr>\n";
        echo "</table>\n";
    }
    else
    {
        echo "<table border=\"0\" height=\"135px\" align=\"left\" width=\"$PRINT_WIDTH\">\n";
        echo "<tr>\n";
        echo "<td width=\"50%\" valign=\"top\" align=\"left\">\n";
        echo "$azienda<br>$sitointernet <br>Telefono : $telefono Fax : $fax <br>\n";

        if($_parametri['email'] == "3")
        {
            echo "e-mail : $email3\n";
        }
        elseif($_parametri['email'] == "2")
        {
            echo "e-mail : $email2\n";
        }
        else
        {
            echo "e-mail : $email1\n";
        }
        echo  "</td>\n";
        echo "<td width=\"50%\" valign=\"top\" align=\"right\">\n";
        echo "Data $_parametri[data]<br>\n";
        echo "Stampa $_parametri[stampa] : $_parametri[anno]<br>\n";
        echo "<b>Pagina $_parametri[pg] di $_parametri[pagina]</b>\n";
        echo "</td>\n";
        echo "</tr></table>\n";
    }
    
    echo "<table border=\"0\" align=\"left\" width=\"$PRINT_WIDTH\">
	        <tr>
	            <td colspan=\"2\" valign=\"top\" align=\"center\">
	                <font face=\"arial\" size=\"5\">$_parametri[tabella]</font>
	            </td>
	        </tr>
	    </table>\n";
    
    
}

function maschera_invio_posta($_cosa, $_percorso, $_nomefile, $_emailmittente, $_emaildestino, $_oggetto, $_parametri)
{
    #include_once $_percorso."../setting/vars.php";
    include_once $_percorso . "librerie/lib_html.php";
    global $conn;
    global $azienda;

    //carichiamo le 'html...
    //
    base_html("", $_percorso);
    //carichiamo javascript
    java_script($_cosa, $_percorso);

    //carichiamo il tyni

    tiny_mce($_cosa, $_percorso);
// questa funzione mi permette di non generare una e-mail vuota

    echo "</head><body>\n";

    echo "<h1> Eventuale messaggio da includere nella mail</h1>\n";

    if ($_emaildestino == "")
    {
        echo "<h1>Attenzione e-mail destinatario assente..</h1>\n";
    }

    echo "<form action=\"" . $_percorso . "librerie/invia_posta_allegato.php?azione=$_cosa\" enctype=\"multipart/form-data\" method=\"POST\">";

    echo "<table align=\"center\" width=\"80%\">";

    echo "<tr><td>DA e.mail : </td><td><input type=\"text\" name=\"From\" value=\"$_emailmittente\" size=\"50\"></td></tr>\n";
    echo "<tr><td>DA nome : </td><td><input type=\"text\" name=\"FromName\" value=\"$azienda\" size=\"50\"></td></tr>\n";
    echo "<tr><td>A : </td><td><input type=\"text\" name=\"AddAddress\" value=\"$_emaildestino\" size=\"50\"></td></tr>\n";
    echo "<tr><td>A CC: </td><td><input type=\"text\" name=\"AddAddressCC\" value=\"$_emaildestinoCC\" size=\"50\"></td></tr>\n";
    echo "<tr><td>A BCC: </td><td><input type=\"text\" name=\"AddAddressBCC\" value=\"$_emaildestinoBCC\" size=\"50\"></td></tr>\n";



    echo "<tr><td>Rispondere a : </td><td><input type=\"text\" name=\"AddReplyTo\" value=\"$_emailmittente\" size=\"50\"></td></tr>\n";
    echo "<tr><td>Oggetto : </td><td><input type=\"text\" name=\"Subject\" value=\"Invio $_oggetto $_nomefile $azienda\" size=\"50\"></td></tr>\n";
    echo "<tr><td>File allegato</td><td><input type=\"radio\" name=\"AddAttachment\" value=\"$_nomefile\" checked>$_nomefile \n";
    echo "<tr><td>Conferma di lettura e-mail</td><td><input type=\"checkbox\" name=\"ricevuta\" value=\"SI\"></td></tr>\n";

    if ($_cosa == "documento")
    {
        echo "<tr><td>Tipo doc</td><td><input type=\"radio\" name=\"tdoc\" value=\"$_parametri[tdoc]\" checked>$_parametri[tdoc] \n";
        echo "<input type=\"radio\" name=\"anno\" value=\"$_parametri[anno]\" checked>Anno $_parametri[anno] \n";
        echo "<input type=\"radio\" name=\"ndoc\" value=\"$_parametri[ndoc]\" checked>Numero $_parametri[ndoc]\n";
        echo "<input type=\"radio\" name=\"suffix\" value=\"$_parametri[suffix]\" checked>suff.$_parametri[suffix]</td>\n";
        echo "</tr>\n";
    }

    //sezione allegato..

    echo "<tr>\n";
    echo "<td>File eventuale file da allegare: </td>\n";
    echo "<td><input name=\"file\" type=\"file\"></td></tr>\n";
  
    echo "<tr>\n";
    echo "<td>File eventuale file da allegare: </td>\n";
    echo "<td><input name=\"file2\" type=\"file\"></td></tr>\n";
    
    

    #<!--campo per la scelta del file-->
    if ($_parametri[BODY] == "")
    {
        $_parametri[BODY] = "Per inviare la e-mail è obbligatorio compilare il campo testo";
    }
    echo "<tr><td>Corpo : </td><td><textarea id=\"elm1\" cols=\"80\" rows=\"20\" name=\"Body\" value=\"$_parametri[BODY]\">$_parametri[BODY]</textarea></td></tr>\n";

    echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"send\" value=\"spedisci\"><br>&nbsp;<br>&nbsp; </td></tr>\n";

    echo "</form></table>";
}

function select_mese($_cosa, $_select)
{
    //funzione che mi permette di selezionare una colonnina con i mesi..


    echo "<select name=\"$_select\">\n";
    echo "<option value=\"01\">Gennaio</option>";
    echo "<option value=\"02\">Febbraio</option>";
    echo "<option value=\"03\">Marzo</option>";
    echo "<option value=\"04\">Aprile</option>";
    echo "<option value=\"05\">Maggio</option>";
    echo "<option value=\"06\">Giugno</option>";
    echo "<option value=\"07\">Luglio</option>";
    echo "<option value=\"08\">Agosto</option>";
    echo "<option value=\"09\">Settembre</option>";
    echo "<option value=\"10\">Ottobre</option>";
    echo "<option value=\"11\">Novembre</option>";
    echo "<option value=\"12\">Dicembre</option>";
    echo "<option value=\"%\">Tutto l'anno</option>";
    echo "</select>\n";
}

function pulsanti($_cosa, $_type, $_form, $_formmethod, $_formaction, $_height, $_width, $_testo, $_name, $_value, $_alt, $_id)
{
    global $_percorso;
    if ($_width == "")
    {
        $_width = "28px";
    }

    if ($_height == "")
    {
        $_height = "28px";
    }

    $FONT_PULSANTI = "Sans";
    $FONTPULSANTISIZE = "8";



    if ($_cosa == "annulla")
    {
        echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_annulla.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "index")
    {
        echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_home.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "conferma")
    {
        if ($_form == "conferma_get")
        {
            echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"> <img src=\"" . $_percorso . "images/pulsanti/pulsante_conferma.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button> \n";
        }
        else
        {
            echo "<button type=\"$_type\" form=\"$_form\" name=\"$_name\" value=\"$_value\" alt=\"$_alt\"> <img src=\"" . $_percorso . "images/pulsanti/pulsante_conferma.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button> \n";
        }
    }

    if ($_cosa == "elimina")
    {
        if ($_form == "elimina_get")
        {
            echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"> <img src=\"" . $_percorso . "images/pulsanti/pulsante_elimina.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button> \n";
        }
        else
        {
            echo "<button type=\"$_type\" form=\"$_form\" name=\"$_name\" value=\"$_value\" alt=\"$_alt\"> <img src=\"" . $_percorso . "images/pulsanti/pulsante_elimina.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button> \n";
        }
    }

    if ($_cosa == "lista")
    {
        if ($_form == "lista_get")
        {
            echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"> <img src=\"" . $_percorso . "images/pulsanti/pulsante_lista.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button> \n";
        }
        else
        {
            echo "<button type=\"$_type\" form=\"$_form\" name=\"$_name\" value=\"$_value\" alt=\"$_alt\"> <img src=\"" . $_percorso . "images/pulsanti/pulsante_lista.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button> \n";
        }
    }


    if ($_cosa == "nuovo")
    {
        if ($_form == "nuovo_get")
        {
            echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\" name=\"$_name\" value=\"$_value\" alt=\"$_alt\" ><img src=\"" . $_percorso . "images/pulsanti/pulsante_nuovo.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
        }
        else
        {
            echo "<button type=\"$_type\" form=\"$_form\" name=\"$_name\" value=\"$_value\" alt=\"$_alt\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_nuovo.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
        }
    }

    if ($_cosa == "modifica")
    {
        if ($_form == "modifica_get")
        {
            echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"> <img src=\"" . $_percorso . "images/pulsanti/pulsante_modifica.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button> \n";
        }
        else
        {
            echo "<button type=\"$_type\" form=\"$_form\" name=\"$_name\" value=\"$_value\" alt=\"$_alt\"> <img src=\"" . $_percorso . "images/pulsanti/pulsante_modifica.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button> \n";
        }
    }

    if ($_cosa == "duplica")
    {
        echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_duplica.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "cerca")
    {
        echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\" value=\"$_value\" name=\"$_name\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_cerca.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "sostituisci")
    {
        echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_sostituisci.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "immagini")
    {
        echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_immagini.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "barcode")
    {
        echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_barcode.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "prezzi_fornitore")
    {
        echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_prezzi_fornitore.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "ritorna")
    {
        echo "<button type=\"$_type\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_ritorna.png\" height=\"$_height\" width=\"$_width\"><br><font color=\"red\"><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "stampa")
    {
        echo "<button type=\"$_type\" formtarget=\"_blank\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_stampa.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }

    if ($_cosa == "salva")
    {
        echo "<button type=\"$_type\" form=\"$_form\" name=\"$_name\" value=\"$_value\" alt=\"$_alt\"> <img src=\"" . $_percorso . "images/pulsanti/pulsante_salva.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button> \n";
    }

    if ($_cosa == "help")
    {
        echo "<button type=\"$_type\" formtarget=\"_blank\" formmethod=\"$_formmethod\" formaction=\"$_formaction\"><img src=\"" . $_percorso . "images/pulsanti/pulsante_aiuto.png\" height=\"$_height\" width=\"$_width\"><br><font face=\"$FONT_PULSANTI\" style=\"font-size: $FONTPULSANTISIZE" . "pt;\">$_testo</font></button>\n";
    }
}

function crea_file_calendar($_cosa, $_nomefile, $_uid, $_parametri)
{
    global $conn;
    global $_percorso;
    global $dec;


    if ($_cosa == "inizio")
    {
        echo "BEGIN:VCALENDAR\n";
        echo "VERSION:2.0\n";
        echo "PRODID:-//Agua Gest//Grigolin Massimo//EN\,\n";
        echo "CALSCALE:GREGORIAN\n";
    }
    elseif ($_cosa == "fine")
    {
        echo "END:VCALENDAR\n";
    }
    elseif ($_cosa == "corpo")
    {

        echo "BEGIN:VEVENT\n";
        echo "UID:$_uid\n";
        echo "SUMMARY:$_parametri[titolo]\n";
        //echo "DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z\n";
        echo "LOCATION:$_parametri[luogo]\n";
        echo "DESCRIPTION:$_parametri[descrizione]\n";
        //echo "URL;VALUE=URI:$_parametri[url]\n";
        echo "DTSTART;VALUE=DATE:$_parametri[data_start]\n";
        echo "DTEND;VALUE=DATE:$_parametri[data_end]\n";
        echo "LAST-MODIFIED:" . gmdate('Ymd') . 'T' . gmdate('His') . "Z\n";
    }
    elseif ($_cosa == "allarme")
    {
        //Here is to set the reminder for the event.
        echo "BEGIN:VALARM\n";
        echo "TRIGGER:-PT1440M\n";
        echo "ACTION:DISPLAY\n";
        echo "DESCRIPTION:Reminder\n";
        echo "END:VALARM\n";
    }
    elseif ($_cosa == "chiudi_corpo")
    {
        echo "END:VEVENT\n";
    }
    else
    {
        //invia.. heaer..
        header('Content-type: text/calendar; charset=utf-8');
        header("Content-Disposition: inline; filename=$_nomefile");
    }




    return $return;
}

/// inizio importazione librerie comode..

/**
 *  funzione che mi gestisce il cambio della data
 * se seleziono it passo la us e mi restituisce la it
 * se anno_it = italiana ricevo solo anno
 *
 * @param $_tipo = it / us / anno_it / anno_us
 * @param $_tipo = listamesi retituisce un arre con la lista dei mesi ed il loro nome..
 * @param $_data =
 * @return la data cambiata
 */
function cambio_data($_tipo, $_data)
{
    $_quanti = strlen($_data);

    if ($_tipo == "it")
    {

        if ($_quanti == "10")
        {
            // invio $_dataus ricevo $_datait
            preg_match_all("/([0-9]{4}).([0-9]{2}).([0-9]{2})/", $_data, $pezzi, PREG_PATTERN_ORDER);

            $_datanuova = $pezzi['3']['0'] . "-" . $pezzi['2']['0'] . "-" . $pezzi['1']['0'];
        }
        elseif ($_quanti == "9")
        {
            // invio $_dataus ricevo $_datait
            preg_match_all("/([0-9]{4}).([0-9]{1}).([0-9]{2})/", $_data, $pezzi, PREG_PATTERN_ORDER);

            $_datanuova = $pezzi['3']['0'] . "-0" . $pezzi['2']['0'] . "-" . $pezzi['1']['0'];
        }
        else
        {
// invio $_dataus ricevo $_datait
            preg_match_all("/([0-9]{4}).([0-9]{2}).([0-9]{2})/", $_data, $pezzi, PREG_PATTERN_ORDER);

            $_datanuova = $pezzi['3']['0'] . "-" . $pezzi['2']['0'] . "-" . $pezzi['1']['0'];
        }
    }
    elseif ($_tipo == "anno_it")
    {
// invio $_data italiana ricevo solo anno

        preg_match_all("/([0-9]{2}).([0-9]{2}).([0-9]{4})/", $_data, $pezzi, PREG_PATTERN_ORDER);

        $_datanuova = $pezzi['3']['0'];
    }
    elseif ($_tipo == "anno_us")
    {
//invio data us e ricevo solo l'anno
        preg_match_all("/([0-9]{4}).([0-9]{2}).([0-9]{2})/", $_data, $pezzi, PREG_PATTERN_ORDER);

        $_datanuova = $pezzi['1']['0'];
    }
    elseif ($_tipo == "listamesi")
    {
//creiamo un array con la lista dei mesi..

        $_datanuova['01'] = "Gennaio";
        $_datanuova['02'] = "Febbraio";
        $_datanuova['03'] = "Marzo";
        $_datanuova['04'] = "Aprile";
        $_datanuova['05'] = "Maggio";
        $_datanuova['06'] = "Giugno";
        $_datanuova['07'] = "Luglio";
        $_datanuova['08'] = "Agosto";
        $_datanuova['09'] = "Settembre";
        $_datanuova['10'] = "Ottobre";
        $_datanuova['11'] = "Novembre";
        $_datanuova['12'] = "Dicembre";
        $_datanuova['13'] = "Gennaio";
        $_datanuova['14'] = "Febbraio";
        $_datanuova['1'] = "Gennaio";
        $_datanuova['2'] = "Febbraio";
        $_datanuova['3'] = "Marzo";
        $_datanuova['4'] = "Aprile";
        $_datanuova['5'] = "Maggio";
        $_datanuova['6'] = "Giugno";
        $_datanuova['7'] = "Luglio";
        $_datanuova['8'] = "Agosto";
        $_datanuova['9'] = "Settembre";
    }
    else
    {
        if ($_quanti == "10")
        {
// invio $_data ita  ricevo $_data us
            preg_match_all("/([0-9]{2}).([0-9]{2}).([0-9]{4})/", $_data, $pezzi, PREG_PATTERN_ORDER);

            $_datanuova = $pezzi['3']['0'] . "-" . $pezzi['2']['0'] . "-" . $pezzi['1']['0'];
        }
        else
        {
// invio $_data ita  ricevo $_data us
            preg_match_all("/([0-9]{2}).([0-9]{2}).([0-9]{4})/", $_data, $pezzi, PREG_PATTERN_ORDER);

            $_datanuova = $pezzi['3']['0'] . "-" . $pezzi['2']['0'] . "-" . $pezzi['1']['0'];
        }
    }

    return $_datanuova;
}

/**
 * Funzione che verifica la validità della data immessa dall'utente in formato italiano....
 * @param stringa $cosa Parametro attualmente non utilizzato
 * @param data $data verifica la data americana se è corretta
 * @return array con due parametri, "errore" indica che c'è un errore ed "descrizione" il tipo di errore da far apparire all'utente
 */
function verifica_data($cosa, $data)
{
//qui bisogna verificare che i campi immessi della data non siano sbagliati..
//
//verifichiamo quanti caratteri immessi.. (109
    $_quanti = strlen($data);

    if ($_quanti != "10")
    {
        $_return['descrizione'] = "<h3 align=\"center\">Attenzione la data inserita non contiene 10 caratteri $_quanti</h3>\n";
        $_return['errore'] = "error";
    }
    else
    {
//verifichiamo che la data immessa non sia maggiore della data odierna..

        $_verifica = cambio_data("us", $data);

        if ($_verifica != "0000-00-00")
        {

//ora separiamo la data e verifichiamo che i campi immessi corrispoandano a giorni mesi anni,,

            preg_match_all("/([0-9]{4}).([0-9]{2}).([0-9]{2})/", $_verifica, $pezzi, PREG_PATTERN_ORDER);

            $_rispo = checkdate($pezzi['2']['0'], $pezzi['3']['0'], $pezzi['1']['0']);

            if ($_rispo != true)
            {
                $_return['descrizione'] = "<h3 align=\"center\">Attenzione la data inserita non Valida</h3>\n";
                $_return['errore'] = "error";
            }
            else
            {
//vuol dire chre la data è valida..
                if ($cosa != "scadenza")
                {
                    if ($_verifica > date('Y-m-d'))
                    {
                        $_return['descrizione'] = "<h3 align=\"center\">Attenzione la data inserita &egrave; Maggiore a quella odierna</h3>\n";
                        $_return['errore'] = "error";
                    }
                }
            }
        }
        else
        {
            $_return['descrizione'] = "<h3 align=\"center\">Attenzione la data inserita &egrave; Uguale a 0</h3>\n";
            $_return['errore'] = "error";
        }
    }

    return $_return;
}

/**
 * Funzione che mi permette di verificare numeri mancanti, oppure di cercare il numero che tocca, od il primo vuoto..
 * @global  $conn
 * @param <type> $_cosa
 * @param <type> $_tabella
 * @return <type>
 */
function cerca_verifica_numero($_cosa, $_tabella, $_parametri)
{
    global $conn;

    if ($_cosa == "cerca_libero")
    {
//questa funzione mi cerca il primo numero libero della tabella richiesta..

        if ($_tabella == "clienti")
        {
            $query = "SELECT codice FROM clienti WHERE codice > '0' ORDER BY codice";
        }
        elseif ($_tabella == "agenti")
        {
            $query = "SELECT codice FROM agenti WHERE codice > '0' ORDER BY codice";
        }
        else
        {
            $query = "SELECT codice FROM fornitori WHERE codice > '0' ORDER BY codice";
        }
        $res = mysql_query($query, $conn);

        $_valore = "1";

        while ($dati = mysql_fetch_array($res))
        {
//qui vediamo se esiste.. 
            if ($_valore != $dati['codice'])
            {
                // ecco la prima risposta è quella che vale..

                $_valore = $dati['codice'] - 1;

                break;
            }
            else
            {
                // ne aggiungiamo uno..
                $_valore = $dati['codice'] + 1;
            }
        }
    }

    if ($_cosa == "cerca_libera")
    {
//questa funzione mi cerca il primo numero libero della tabella richiesta..

        if ($_tabella == "clienti")
        {
            $query = "SELECT codice FROM clienti WHERE codice > '0' ORDER BY codice";
        }
        elseif ($_tabella == "agenti")
        {
            $query = "SELECT codice FROM agenti WHERE codice > '0' ORDER BY codice";
        }
        elseif ($_tabella == "destinazioni")
        {
            $query = "SELECT utente, codice FROM destinazioni WHERE utente='$_parametri' AND codice > '0' ORDER BY utente, codice";
        }
        else
        {
            $query = "SELECT codice FROM fornitori WHERE codice > '0' ORDER BY codice";
        }
        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "lib_mtml.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $_valore = "1";

        foreach ($result AS $dati)
        {
//qui vediamo se esiste.. 
            if ($_valore != $dati['codice'])
            {
                // ecco la prima risposta è quella che vale..

                $_valore = $dati['codice'] - 1;

                break;
            }
            else
            {
                // ne aggiungiamo uno..
                $_valore = $dati['codice'] + 1;
            }
        }
    }


    if ($_cosa == "cerca_ultimo")
    {
        //questa funzione mi cerca il primo numero libero della tabella richiesta..

        if ($_tabella == "clienti")
        {
            $query = "SELECT codice FROM clienti ORDER BY codice DESC limit 1";
        }
        else
        {
            $query = "SELECT codice FROM fornitori ORDER BY codice DESC limit 1";
        }
        $res = mysql_query($query, $conn);

        $dati = mysql_fetch_array($res);
        // ne aggiungiamo uno..
        $_valore = $dati['codice'] + 1;
    }


    return $_valore;
}

/** controllo del codice fiscale * */
function codiceFiscale($cf)
{

    //per abilitare un controllo pulito della partita iva..
    //bisogna anche vedere che non sia estero..
    //verifichiamo se le prime due lettere del campo..

    if (is_numeric($cf) == TRUE)
    {
        //controllo partita iva..

        $return = controllaPIVA($cf);
    }
    else
    {

        $cf = strtoupper($cf);

        if (strlen($cf) != 16)
        {
            $return['result'] = "NO";
            $return['errore'] = "Il codice fiscale non ha 16 cifre";
        }
        elseif (!preg_match("/[A-Z0-9]+$/", $cf))
        {
            $return['result'] = "NO";
            $return['errore'] = "Formato non Valido";
        }
        else
        {
            $s = 0;

            for ($i = 1; $i <= 13; $i+=2)
            {
                $c = $cf[$i];
                if ('0' <= $c and $c <= '9')
                    $s+=ord($c) - ord('0');
                else
                    $s+=ord($c) - ord('A');
            }

            for ($i = 0; $i <= 14; $i+=2)
            {
                $c = $cf[$i];
                switch ($c)
                {
                    case '0': $s += 1;
                        break;
                    case '1': $s += 0;
                        break;
                    case '2': $s += 5;
                        break;
                    case '3': $s += 7;
                        break;
                    case '4': $s += 9;
                        break;
                    case '5': $s += 13;
                        break;
                    case '6': $s += 15;
                        break;
                    case '7': $s += 17;
                        break;
                    case '8': $s += 19;
                        break;
                    case '9': $s += 21;
                        break;
                    case 'A': $s += 1;
                        break;
                    case 'B': $s += 0;
                        break;
                    case 'C': $s += 5;
                        break;
                    case 'D': $s += 7;
                        break;
                    case 'E': $s += 9;
                        break;
                    case 'F': $s += 13;
                        break;
                    case 'G': $s += 15;
                        break;
                    case 'H': $s += 17;
                        break;
                    case 'I': $s += 19;
                        break;
                    case 'J': $s += 21;
                        break;
                    case 'K': $s += 2;
                        break;
                    case 'L': $s += 4;
                        break;
                    case 'M': $s += 18;
                        break;
                    case 'N': $s += 20;
                        break;
                    case 'O': $s += 11;
                        break;
                    case 'P': $s += 3;
                        break;
                    case 'Q': $s += 6;
                        break;
                    case 'R': $s += 8;
                        break;
                    case 'S': $s += 12;
                        break;
                    case 'T': $s += 14;
                        break;
                    case 'U': $s += 16;
                        break;
                    case 'V': $s += 10;
                        break;
                    case 'W': $s += 22;
                        break;
                    case 'X': $s += 25;
                        break;
                    case 'Y': $s += 24;
                        break;
                    case 'Z': $s += 23;
                        break;
                }
            }

            if (chr($s % 26 + ord('A')) != $cf[15])
            {
                $return['result'] = "NO";
                $return['errore'] = "Codice Fiscale Errato";
            }
        }
    }

    return $return;
}

/** controllo della partita iva * */
function controllaPIVA($variabile)
{
    global $conn;

    //per abilitare un controllo pulito della partita iva..
    //bisogna anche vedere che non sia estero..
    //verifichiamo se le prime due lettere del campo..

    if (is_numeric($variabile) == TRUE)
    {
        #echo "SI NUMERICO";
        $_nazione = "IT";
    }
    else
    {
        #echo "testo";
        $_nazione = substr($variabile, '0', '2');
        $variabile = substr($variabile, '2', '15');
    }



    //dividiamo la funzione
    if ($_nazione == "IT")
    {
        //controlliamo la partita iva
        #echo $_documento;
        #echo "<br>$variabile";

        if ($variabile == '')
        {
            $return['result'] = "NO";
            $return['errore'] = "Campo vuoto";
        }
        //la p.iva deve essere lunga 11 caratteri
        elseif (strlen($variabile) != 11)
        {
            $return['result'] = "NO";
            $return['errore'] = "la p.iva deve essere lunga 11 caratteri";
        }
        //la p.iva deve avere solo cifre
        elseif (!preg_match("/[0-9]+$/", $variabile))
        {
            $return['result'] = "NO";
            $return['errore'] = "la p.iva deve contenere solo cifre";
        }
        else
        {
            $primo = 0;
            for ($i = 0; $i <= 9; $i+=2)
                $primo+= ord($variabile[$i]) - ord('0');
            for ($i = 1; $i <= 9; $i+=2)
            {
                $secondo = 2 * ( ord($variabile[$i]) - ord('0') );
                if ($secondo > 9)
                    $secondo = $secondo - 9;
                $primo+=$secondo;
            }
            if ((10 - $primo % 10) % 10 != ord($variabile[10]) - ord('0'))
            {
                $return['result'] = "NO";
                $return['errore'] = "Partita iva non valida";
            }
        }
    }
    elseif ($_nazione == "ZZ")
    {
        //Vuoldire che la partita iva è provvisioria oppure extra europeo..
        //se la zz ha già il suo numero bene.. altrimenti chiediamo al database che numero tocca e lo aggiungiamo..

        if ($variabile != "")
        {
            //vuol dire che è già apposto quindi saltiamo..

            $return['result'] = "OK";
        }
        else
        {
            //cerchiamo il nuovo numero e lo associamo al cliente..

            $query = "SELECT piva from clienti where piva like 'ZZ%' ORDER BY piva DESC LIMIT 1";

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

            foreach ($result AS $dati)
                ;

            $_numero = substr($dati['piva'], '3', '4');
            //echo $_numero;
            //echo $_numero;
            $_numero++;
            //echo $_numero;

            $_numero = str_pad($_numero, 4, 0, STR_PAD_LEFT);
            //echo $_numero;
            $return['nuova'] = "ZZZ" . $_numero;
            $return['result'] = "ext";
        }
    }
    else
    {
        //La partita iva è estera
        echo "partita iva comunitaria stato $_nazione";

        $_stato['result'] = "CE";

        $_stato['nazione'] = $_nazione;

        //qui creiamo un array con le partite ive..

        $_stato['AT']['nome'] = "Austria";
        $_stato['AT']['numero'] = "9";
        $_stato['AT']['tipologia'] = "U99999999 1 blocco di 9 cifre Il primo carattere è sempre 'U'.";

        $_stato['BE']['nome'] = "Belgio";
        $_stato['BE']['numero'] = "10";
        $_stato['BE']['tipologia'] = "1 blocco di 10 cifre.
 Il primo carattere è sempre zero. Tutti i numeri delle partite Iva del Belgio hanno cambiato la loro lunghezza da 9 a 10 cifre a partire dal 01/01/2008.";

        $_stato['BG']['nome'] = "Bungheria";
        $_stato['BG']['numero'] = "9";
        $_stato['BG']['tipologia'] = "1 blocco di 9 cifre per le societa e da 10 per le persone fisiche.";

        $_stato['CY']['nome'] = "Cipro";
        $_stato['CY']['numero'] = "9";
        $_stato['CY']['tipologia'] = "1 blocco di 9 cifre.";

        $_stato['DE']['nome'] = "Germania";
        $_stato['DE']['numero'] = "9";
        $_stato['DE']['tipologia'] = "1 blocco di 9 cifre.";

        $_stato['DK']['nome'] = "Danimarca";
        $_stato['DK']['numero'] = "8";
        $_stato['DK']['tipologia'] = "4 blocchi da 2 cifre.";

        $_stato['EE']['nome'] = "Estonia";
        $_stato['EE']['numero'] = "9";
        $_stato['EE']['tipologia'] = "1 blocco di 9 cifre.";

        $_stato['FI']['nome'] = "Finlandia";
        $_stato['FI']['numero'] = "8";
        $_stato['FI']['tipologia'] = "1 blocco di 8 cifre.";

        $_stato['FR']['nome'] = "Francia";
        $_stato['FR']['numero'] = "11";
        $_stato['FR']['tipologia'] = "1 blocco di 11 cifre le prime due lettere.";

        $_stato['EL']['nome'] = "Grecia";
        $_stato['EL']['numero'] = "9";
        $_stato['EL']['tipologia'] = "1 blocco di 9 cifre.";

        $_stato['GB']['nome'] = "Gran Bretagna";
        $_stato['GB']['numero'] = "12";
        $_stato['GB']['tipologia'] = "999999999	1 blocco di 3 cifre, 1 di 4 cifre ed 1 di 2 cifre.
999999999999	1 blocco di 12 cifre.
 Le ultime 3 identificano la filiale di una società.
GD999
HA999	1 blocco di 5 caratteri.
 GD = Government Departments.
 HA = Health Authorities.";

        $_stato['IE']['nome'] = "Irlanda";
        $_stato['IE']['numero'] = "8";
        $_stato['IE']['tipologia'] = "1 blocco di 8 cifre.";

        $_stato['HR']['nome'] = "Croazia";
        $_stato['HR']['numero'] = "11";
        $_stato['HR']['tipologia'] = "1 blocco di 11 cifre.";

        $_stato['LT']['nome'] = "Lituania";
        $_stato['LT']['numero'] = "9";
        $_stato['LT']['tipologia'] = "1 blocco di 9 cifre o 12.";

        $_stato['LU']['nome'] = "Lussemburgo";
        $_stato['LU']['numero'] = "8";
        $_stato['LU']['tipologia'] = "1 blocco di 8 cifre.";

        $_stato['LV']['nome'] = "Lettonia";
        $_stato['LV']['numero'] = "11";
        $_stato['LV']['tipologia'] = "1 blocco di 11 cifre.";

        $_stato['MT']['nome'] = "MAlta";
        $_stato['MT']['numero'] = "8";
        $_stato['MT']['tipologia'] = "1 blocco di 8 cifre.";

        $_stato['NL']['nome'] = "Olanda";
        $_stato['NL']['numero'] = "12";
        $_stato['NL']['tipologia'] = "1 blocco di 12 cifre la decima è sempre una B.";

        $_stato['PL']['nome'] = "Polonia";
        $_stato['PL']['numero'] = "10";
        $_stato['PL']['tipologia'] = "1 blocco di 10 cifre.";

        $_stato['PT']['nome'] = "Portogallo";
        $_stato['PT']['numero'] = "9";
        $_stato['PT']['tipologia'] = "1 blocco di 9 cifre.";

        $_stato['RO']['nome'] = "Romania";
        $_stato['RO']['numero'] = "2";
        $_stato['RO']['tipologia'] = "Da 2 a 10 cifre";

        $_stato['SI']['nome'] = "Slovenia";
        $_stato['SI']['numero'] = "8";
        $_stato['SI']['tipologia'] = "1 blocco di 8 cifre.";

        $_stato['CZ']['nome'] = "Ceca Repubblica";
        $_stato['CZ']['numero'] = "9";
        $_stato['CZ']['tipologia'] = "1 blocco di 8 o 9 o 10 cifre.";

        $_stato['SK']['nome'] = "Slocakkia";
        $_stato['SK']['numero'] = "10";
        $_stato['SK']['tipologia'] = "1 blocco di 10 cifre.";

        $_stato['ES']['nome'] = "Spagna";
        $_stato['ES']['numero'] = "9";
        $_stato['ES']['tipologia'] = "X9999999X	1 blocco di 9 caratteri.
 Il primo e l'ultimo carattere possono essere alfabetici o numerici ma non possono essere entrambi numerici.";

        $_stato['SE']['nome'] = "Svezia";
        $_stato['SE']['numero'] = "12";
        $_stato['SE']['tipologia'] = "1 blocco di 12 cifre.";

        $_stato['HU']['nome'] = "Ungeria";
        $_stato['HU']['numero'] = "8";
        $_stato['HU']['tipologia'] = "1 blocco di 8 cifre.";




        //VERIFICHIAMO SE ALMENTO CORRISPONDE LA LUNGHEZZA..
        //echo $variabile;

        if (strlen($variabile) != $_stato[$_nazione]['numero'])
        {
            $_stato['errore'] = "Attenzione Iva comunitaria errata";
            $_stato['numero'] = strlen($variabile);
        }


        $return = $_stato;
    }


    return $return;
}

/**
 * Gestione centralizzata dell'iva separata..
 * @param <type> $_cosa
 * @param <type> $_parametri
 * @return <type>
 */
/* * Funzione che mi calcola l'impegno dell'articolo con la relativa giacenza
 * 
 * @global $conn
 * @param type $_cosa
 * @param type $_articolo codicec articolo
 * @param type $_anno da verificare non necessaria attualmenti
 * @return array $_impegni con all'interno giacenza, impegnato, ordinati
 */
function impegni_articolo($_cosa, $_articolo, $_anno)
{
    global $conn;
    global $_percorso;


    #echo gettype($conn);
    #echo is_object($conn);
    // inizio calcolo giacenza

    $query = "select sum(qtacarico) AS qtacarico, sum(qtascarico) AS qtascarico from magazzino where articolo='$_articolo'";

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "stampa_pianoc.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    $dati = $result->fetch(PDO::FETCH_ASSOC);
    $_qtacarico = $dati['qtacarico'];
    $_qtascarico = $dati['qtascarico'];

    //primo array con la giacenza
    $_impegni['giacenza'] = ($_qtacarico - $_qtascarico);

    //seconda query..
    $query = sprintf("select sum(qtasaldo) AS quantita from co_dettaglio INNER JOIN co_testacalce ON co_dettaglio.ndoc=co_testacalce.ndoc AND co_dettaglio.anno=co_testacalce.anno where articolo=\"%s\" AND rsaldo != 'SI' AND status != 'evaso' ", $_articolo);
#echo $query;
    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "stampa_pianoc.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    $dati = "";
    $dati = $result->fetch(PDO::FETCH_ASSOC);
    //secondo arrray con l'impegnato
    $_impegni['impegnato'] = $dati['quantita'];


    $query = sprintf("select sum(qtasaldo) AS quantita from of_dettaglio INNER JOIN of_testacalce ON of_dettaglio.ndoc=of_testacalce.ndoc AND of_dettaglio.anno=of_testacalce.anno where articolo=\"%s\" AND rsaldo != 'SI' and status != 'evaso' ", $_articolo);

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
        $_errori['files'] = "lib_html.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
    $dati = "";
    $dati = $result->fetch(PDO::FETCH_ASSOC);
    //terzo arrray con l'impegnato
    $_impegni['ordinato'] = $dati['quantita'];

    //ritorno un array completo con le tre variabili

    return $_impegni;
}

/**
 * funzione che mi calcola l'iva in automatico..
 * ritorna gli arrei con l'iva
 * l'arrey chiamato numero da come risposta il numero assegnato per l'iva sistema.
 * @cosa fattura = separa tutta l'iva per l'inserimento della fattura
 * @return ritorna un arrey con l'iva...

 */
function gestione_iva($_cosa, $_castiva, $_totiva, $_imponibile, $_spese, $dati, $_datareg)
{
    global $conn;
    global $IVAMULTI;
    global $ivasis;
    global $dec;
    global $DATAIVA;
    global $_percorso;
    require_once "motore_anagrafiche.php";

    /* CASTELLETTO DELL'IVA..
     * 	POSSO VEDERE se all'inerno del castelletto c'è l'aliquota di sistema..
     * di cui inserire l'imponibile delle spese da far la somma.
     * altrimenti devo verificare la presenza dell'aliquota differente dal sistema
     * che è associata la cliente, ed inserire le spese.. 
     * A questo punto io devo verificare che il cliente non sia esente come esportatore
     * abituale, perche in questo caso devo togliere all'iva associata del cliente
     * 	l'importo delle spese ed ricalcolarle con l'iva del sistema, e quindi anche 
     * esporre sia l'imponibile che l'aliquota di sistema ed anche l'imposta
     * dopo di che si può fare le somme.
     * 
     * Mettiamo che ci sia una fornitura con iva al dieci.. di un solo articolo
     * con spedizione a mezzo corriere.. 
     * qui bisognerebbe inserire all'interno del castelletto l'aliquota di sistema
     * 	con il valore delle spese per poi calcolare l'imposta corretta.
     * 
     * quindi..
     * primo passo..
     * Verifica se all'interno del castelletto c'è l'iva sistema..
     * SE 
     * SI aggiungiamo all'imposnibile le spese nell'iva sistema e passiamo a fare i conti.
     * SE NO
     * Verifichiamo c'è una aliquota associata al cliente..
     * SE NO 
     * il cliente non ha aliquota, vuol dire che nel documento che si stà
     * generando ci sono articoli non apparteneti all'iva di sistema, quindi bisogna
     * inserire a mano nell'array del castelletto la riga con aliquota = iva sistema
     * imponibile, ( perche l'imposta la colcoliamo dopo.
     * chiuso
     * SE SI
     * Vuo dire che il cliente ha una forma di esenzione, che poterbbe essere
     * una esenzione totale o parziale, quindi esso poterbbe essere un cliente estero di cui la 
     * forma di esenzione è totale oppure un semplice esportatore abituale di cui
     * gli articoli o prestazioni sono esenti iva, ma l'importo delle spese come il trasporto 
     * va calcolata l'iva di sistma ed addebitata.
     * - Prendiamo l'iva associata al cliente
     * Domanda l'iva è esennte con causale = 2
     * SI SI
     * Bisogna calcola l'iva delle spese separatamente all'esenzione del cliente
     * quindi inseriamo inseriamo a mano una riga con codice aliquota l'iva sistema (21)
     * ed l'imponibile delle spese, per poi passarla alla funzione di calcolo dell'iva.
     * chiuso
     * SE NO
     * Vuol dire che il cliente è completamente esente dall'iva come un cliente estero
     * e quindi l'imponibile delle spese va inserito in somma all'imponibile degli
     * Articoli venduti.. Quindi va aggiunto all'array con lo stesso codice di appartenenza.
     * e mandato a calcolare alla funzione.
     * chiuso
     * chiuso
     * chiuso
     * 
     * 
     */


    //global $_impostaspese;
    //verifichiamo iva di sistema..

    if ($_datareg < $DATAIVA)
    {
        $ivasis = $ivasis - 1;
    }


    if ($_cosa == "iva_corpo")
    {
        //programma che mi serve a visualizzare il totale dell'iva sul corpo del programma
        // guardo la gestione dell'iva
        if (($IVAMULTI == "NO") AND ( $_totiva != "0.00"))
        {
            $_totiva = number_format((($_imponibile * $ivasis) / 100), $dec, '.', '');
        }
        else
        {
            // Visualizzo tatali iva diverse
            while (@list($indice, $valore) = each($_castiva))
            {
                $_aliquota = tabella_aliquota("singola_aliquota", $indice, $_percorso);

                $_ivasep = number_format((($valore * $_aliquota) / 100), $dec, '.', '');

                echo "<font size=\"2\"> Aliq.: <b> $indice </b> - Imponibile: $valore Iva: $_ivasep<br>";
                $_totiva = $_totiva + $_ivasep;
            }
        }
        $_return = $_totiva;
    }
    elseif ($_cosa == "iva_documenti")
    {
        {
            $_totiva = "";

            // inizio calcolo iva cliente per il corpo dei documenti.
            // verifichiamo se nel castelletto c'è iva sistema..

            if ($_castiva[$ivasis] != "")
            {
                //visto che qui è diversa da vuoto sommiamo direttamente le proprie spese..
                $_castiva[$ivasis] = $_castiva[$ivasis] + $_spese;
                #echo "ivasis";
            }
            else
            {

                //cerchiamo se il cliente ha una aliquota associata nell'arrey dati.
                //vedendo se è diverso da vuoto..

                if ($dati['iva'] != "")
                {
                    #echo $dati['iva'];
                    // vuo dire che il cliente ha una forma di esenzione..
                    // vediamo qual'è
                    //prendiamo l'arrey dell'iva
                    $dati_iva = tabella_aliquota("singola", $dati['iva'], $_percorso);

                    //verifichiamo se è esente livello 2

                    if ($dati_iva['eseniva'] == "3")
                    {
                        //vuol dire che il cliente e un esportatore abituale e quindi 
                        //bisogna inserire  a mano..
                        $_castiva[$ivasis] = $_spese;

                        #echo "ciao_2";
                    }
                    else
                    {
                        // vuol dire che il cliente è completamente esente dall'iva..

                        $_castiva[$dati['iva']] = $_castiva[$dati['iva']] + $_spese;

                        #echo "ciao_3";
                    }
                }
                else
                {
                    // no il cliente non ha nessuna iva associata quindi inseriamo 
                    //una riga a mano dentro l'array..
                    #echo "ciao";
                    $_castiva[$ivasis] = $_spese;
                }
            }

            // ora possiamo passare a processare l'importo..
            // Visualizzo tatali iva diverse
            while (@list($indice, $valore) = each($_castiva))
            {
                if ($indice != "")
                {
                    //inanzi tutto creiamo un indice..		
                    $nr = $nr + 1;

                    //prendiamoci l'aliquota di riferimento..
                    $_aliquota = tabella_aliquota("singola_aliquota", $indice, $_percorso);

                    //inseriamo l'aliquota nel array..
                    $_imponibili[$nr]['aliquota'] = $indice;
                    $_imponibili[$nr]['imponibile'] = $valore;


                    if ($indice == $ivasis)
                    {
                        //mi serve per richiamare l'iva globale all'uscita della funzione.
                        $_imponibili['numero'] = $nr;
                    }

                    //calcoliamo l'va
                    $_ivasep = number_format((($valore * $_aliquota) / 100), $dec, '.', '');
                    //inseriamo ..		
                    $_imponibili[$nr]['imposta'] = $_ivasep;

                    #echo $_ivasep;
                    //somma di riporto
                    $_totiva = $_totiva + $_ivasep;
                    $_totimpo = $_totimpo + $valore;

                    if ($indice == $ivasis)
                    {
                        //se l'iva è uguale all'iva del sistema inseriesco le spese..

                        $_imponibili[$nr]['imposta'] = $_imponibili[$nr]['imposta'] + $_impostaspese;
                    }
                }
            }

            $_imponibili['totimpo'] = $_totimpo;
            $_imponibili['totiva'] = $_totiva;

            $_return = $_imponibili;
        }
    }
    else// iva fatture
    {
        $_totiva = "";

        // inizio calcolo iva cliente per il corpo dei documenti.
        // verifichiamo se nel castelletto c'è iva sistema..

        if ($_castiva[$ivasis] != "")
        {
            //visto che qui è diversa da vuoto sommiamo direttamente le proprie spese..
            $_castiva[$ivasis] = $_castiva[$ivasis] + $_spese;
            #echo "ivasis";
        }
        else
        {

            //cerchiamo se il cliente ha una aliquota associata nell'arrey dati.
            //vedendo se è diverso da vuoto..

            if ($dati['iva'] != "")
            {
                #echo $dati['iva'];
                // vuo dire che il cliente ha una forma di esenzione..
                // vediamo qual'è
                //prendiamo l'arrey dell'iva
                $dati_iva = tabella_aliquota("singola", $dati['iva'], $_percorso);

                //verifichiamo se è esente livello 2

                if ($dati_iva['eseniva'] == "3")
                {
                    //vuol dire che il cliente e un esportatore abituale e quindi 
                    //bisogna inserire  a mano..
                    $_castiva[$ivasis] = $_spese;

                    #echo "ciao_2";
                }
                else
                {
                    // vuol dire che il cliente è completamente esente dall'iva..

                    $_castiva[$dati['iva']] = $_castiva[$dati['iva']] + $_spese;

                    #echo "ciao_3";
                }
            }
            else
            {
                // no il cliente non ha nessuna iva associata quindi inseriamo 
                //una riga a mano dentro l'array..
                #echo "ciao";
                $_castiva[$ivasis] = $_spese;
            }
        }

        // ora possiamo passare a processare l'importo..
        // Visualizzo tatali iva diverse
        while (@list($indice, $valore) = each($_castiva))
        {
            if ($indice != "")
            {
                //inanzi tutto creiamo un indice..		
                $nr = $nr + 1;

                //prendiamoci l'aliquota di riferimento..
                $_aliquota = tabella_aliquota("singola_aliquota", $indice, $_percorso);

                //inseriamo l'aliquota nel array..
                $_imponibili[$nr]['aliquota'] = $indice;
                $_imponibili[$nr]['imponibile'] = $valore;


                if ($indice == $ivasis)
                {
                    //mi serve per richiamare l'iva globale all'uscita della funzione.
                    $_imponibili['numero'] = $nr;
                }

                //calcoliamo l'va
                $_ivasep = number_format((($valore * $_aliquota) / 100), $dec, '.', '');
                //inseriamo ..		
                $_imponibili[$nr]['imposta'] = $_ivasep;

                #echo $_ivasep;
                //somma di riporto
                $_totiva = $_totiva + $_ivasep;
                $_totimpo = $_totimpo + $valore;

                if ($indice == $ivasis)
                {
                    //se l'iva è uguale all'iva del sistema inseriesco le spese..

                    $_imponibili[$nr]['imposta'] = $_imponibili[$nr]['imposta'] + $_impostaspese;
                }
            }
        }

        $_imponibili['totimpo'] = $_totimpo;
        $_imponibili['totiva'] = $_totiva;

        $_return = $_imponibili;
    }

    return $_return;
}

//-------------------------------------------------------------------
//Funzione che mi serve per prendere il prezzo di vendita personalizzato per ogni cliente
// Verifica che il cliente associato abbia un listino prezzi proprio..
// oppure abbia un prezzo personalizzato per determinato articolo
// esempio un tizio ha associato listino 1 con sconto 50 pero su un articolo abbia un prezzo netto
//VERIFICO LA PRESENZA DI UN PREZZO NETTO RELATIVO AL CLIENTE E L'ARTICOLO incluso la descrizione
#echo $_codutente;
function prezzi_cliente($_cosa, $_utente, $_articolo, $_nlv, $_parametri)
{

    global $conn;
    global $_percorso;
    global $dec;

    $result = tabella_prezzi_cliente("singola", $_utente, $_articolo, $_parametri);

    if ($result['presenza'] != "NO")
    {
        $_descrizione = $result['descrizione'];
        $_listino = $result['listino'];

        // AVVISO CHE UTILIZZO IL PREZZO NETTO TROVATO
        $_messaggio = "<font color=yellow>ATTENZIONE PREZZO NETTO PERSONALIZZATO DEL CLIENTE</font>";


        $_descrizione = $result['descrizione'];
        $_listino = $result['listino'];
        $_sca = "";
        $_scb = "";
        $_scc = "";
    }
    else
    {
// ecco se non trovo niente
// ASSOCIO IL LISTINO CLIENTE AL PREZZO
// SE TROVO IL LISTINO ASSOCIATO AL CLIENTE AVVISO!

        $result = tabella_listini("singola", $_articolo, $_nlv, $_parametri);

        //vediamo se ha trovato il listino
        if ($result['presenza'] != "NO")
        {

            $_listino = $result['listino'];
            // Opzione ripristino sconti automatica
            $_sca = $_parametri['scontocli'];
            $_scb = $_parametri['scontocli2'];
            $_scc = $_parametri['scontocli3'];

            if ($_sca == "0.00")
            {
                $_sca = "";
            }
            if ($_scb == "0.00")
            {
                $_scb = "";
            }
            if ($_scc == "0.00")
            {
                $_scc = "";
            }
        }
        else
        {
            $result = tabella_listini("singola", $_articolo, "1", $_parametri);

            //vediamo se ha trovato il listino

            if ($result['presenza'] != "NO")
            {
                $_messaggio = "<h3>ATTENZIONE! PREZZO LISTINO ASSOCIATO MANCANTE o PREZZO NON TROVATO !!! <BR> IN USO IL LISTINO 1!</h3>";

                $_listino = $result['listino'];
                // Opzione ripristino sconti automatica
                $_sca = $_parametri['scontocli'];
                $_scb = $_parametri['scontocli2'];
                $_scc = $_parametri['scontocli3'];

                if ($_sca == "0.00")
                {
                    $_sca = "";
                }
                if ($_scb == "0.00")
                {
                    $_scb = "";
                }
                if ($_scc == "0.00")
                {
                    $_scc = "";
                }
            }
        }
    }

    //in caso che l'articolo abbia l'esenzione allo sconto
    //'
    //'
    if ($_esco == "SI")
    {
        $_sca = "";
        $_scb = "";
        $_scc = "";
        $_messaggio = "<font color=blue>ATTENZIONE Articolo esente sconti</font>";
    }

    $return['descrizione'] = $_descrizione;
    $return['listino'] = $_listino;
    $return['sca'] = $_sca;
    $return['scb'] = $_scb;
    $return['scc'] = $_scc;
    $return['messaggio'] = $_messaggio;


    return $return;
}

//-------------------------------------------------------------------
/**
 * funzione scadenza..
 * QUESTA funzione calcola la data di scadenza di un documento passato
 * @global $conn $conn
 * @param type data documento in fomato americano
 * @param type variabile con il codice di pagamento
 * @return arrey contenente il numero della rata e la data in fomato americano
 */
function scadenza($_datadoc, $_pagamento)
{
    global $conn;
    global $dec;
    global $_percorso;

    #costruiamo un calendario gregoriano..

    $MESE = array();
    $MESE['1'] = "31";
    $MESE['2'] = "28";
    $MESE['3'] = "31";
    $MESE['4'] = "30";
    $MESE['5'] = "31";
    $MESE['6'] = "30";
    $MESE['7'] = "31";
    $MESE['8'] = "31";
    $MESE['9'] = "30";
    $MESE['10'] = "31";
    $MESE['11'] = "30";
    $MESE['12'] = "31";
    $MESE['13'] = "31";
    $MESE['14'] = "28";
    $MESE['15'] = "31";


//prendiamoci il pagamento
    $query = "SELECT * FROM pagamenti WHERE codice='$_pagamento' LIMIT 1";

    //dividiamo il discorso per le nuove connessioni al database..

    if (gettype($conn) == "object")
    {

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        //passo il risultato della linea al sistema
        foreach ($result AS $datip)
            ;
    }
    else
    {
        $resp = mysql_query($query, $conn);

// echo mysql_errno() . ": " . mysql_error() . "\n";

        $datip = mysql_fetch_array($resp);
    }

    $_rate = $datip['nscad'];
    $_ggpri = $datip['ggprimascad'];
    $_ggtra = $datip['ggtrascad'];
    $_fm = $datip['dffm'];
    $_scadfissa = $datip['scadfissa'];
    $_unomese = $datip['unomese'];
    $_duemese = $datip['duemese'];
    $_tipopag = $datip['tipopag'];
    $_rataiva = $datip['rataiva'];

    #echo "<br>Esponiamo il tipo di pagamento.. $datip[descrizione]";
    // 	Controlli di sicurezza....

    if ($_rate == "")
    {
        $_rate = 1;
        $_ggpri = 0;
        $_ggtra = 0;
    }



// inizio il ciclo delle scadenze..
    //dobbiamo pensare come se tutti i documenti fossero come data il primo del mese..

    for ($_a = 1; $_a <= $_rate; $_a++)
    {

// inzio a lavorare.. 
        //apriamo il documento.. e verifichiamo che non siamo già su un mese bisestile..

        $today = getdate(strtotime("$_datadoc"));

        //Conviene sempre considerare il giorno di lavorazione 01

        $_daydoc = $today['mday'];
        $_day = "01";

        $_gmese = $MESE[$today['mon']];

        $_mese = $today['mon'];

        $_add2 = ceil($_ggpri / 30);


        #echo "<br> Mesi da considerare $_add2";
        //Conviene sempre considerare il giorno di lavorazione 01
        //$_mese = $_mese + 1;
        #echo "<br>Mese di partenza $_mese";
        //inseriamo il primo mese.. ovvero quello in cui siamo..
//	if ((date("L", mktime(0, 0, 0, 1, 1, $today['year'])) == "1") AND ($MESE[$_mese] == "02"))
//	{
//	    $_add = $_add + "29";
//	}
//	else
//	{
//	    $_add = $_add + $MESE[$_mese];
//	}
        $_annoscad = $today['year'];

        for ($_v = 1; $_v <= $_add2; $_v++)
        {

            //$_add = $_add + $MESE[$_mese];
            //$_annoscad = date("Y", mktime(0, 0, 0, $today['mon'], $_day + $_add, $today['year']));
            //se noi gli chiediamo di verificare l'anno prima di aggiungere il mese..??
            #echo "<br>$_annoscad";
            $_bisestile = date("L", mktime(0, 0, 0, 1, 1, $_annoscad));

            #echo "<br>$_bisestile mese $_mese";

            if (($_bisestile == "1") AND ( $_mese == "2"))
            {
                #echo "<br>ciao";
                $_add = $_add + "29";
            }
            else
            {
                $_add = $_add + $MESE[$_mese];
            }


            if (($_mese == 12))
            {
                $_mese = 1;
            }
            else
            {
                $_mese = $_mese + 1;
            }

            #echo "<br> Mese eleborato $_mese";
// qui la lavoro
            $_scad = date("Y-m-d", mktime(0, 0, 0, $today['mon'], $_day + $_add, $today['year']));
            $_annoscad = date("Y", mktime(0, 0, 0, $today['mon'], $_day + $_add, $today['year']));


            #echo "<br> Data apparente ciclo $_scad con $_add";
        }


        //fine secondo for..
// qui la lavoro

        $_scad = date("Y-m-d", mktime(0, 0, 0, $today['mon'], $_day + $_add, $today['year']));

        #echo "<br> Data apparente  --- $_scad";
// mi riprendo la data creata..
        $_new = getdate(strtotime($_scad));

//VERIFICHIAMO ANNO BISESTILE
        #echo "<br> anno scadenza $_new[year]";

        $bisestile = date("L", mktime(0, 0, 0, 1, 1, $_new['year']));

        #echo "<br> Verifica anno bisestile $bisestile";

        if ($bisestile == "1")
        {
            $MESE['2'] = "29";
        }
        else
        {
            $MESE['2'] = "28";
        }


        if ($_fm == "FM")
        {
            $_nday = $MESE[$_new['mon']];

            #echo "<br> fine mese.. ? $_fm";
        }
        else
        {
            $_nday = $_daydoc;
        }



        if ($_new['mon'] == "$_unomese")
        {
            $_festa1 = 10;
        }
        else
        {
            $_festa1 = "";
        }



        if ($_new['mon'] == $_duemese)
        {
            $_festa2 = "10";
        }
        else
        {
            $_festa2 = "";
        }


        if ($_scadfissa != 0)
        {
            $_festa1 = "";
            $_festa2 = "";
        }


        #echo "<br>data nday = $_nday";



        $_jorno = $_nday + $_scadfissa + $_festa1 + $_festa2;

        #echo "<br>data ufficiale = $_jorno";

        $_siensa = date("Y-m-d", mktime(0, 0, 0, $_new['mon'], $_jorno, $_new['year']));

        $_datadoc = $_scad;
        $_ggpri = $_ggtra;
        $_add = "";

        $data[$_a] = $_siensa;

        #echo "<br>data scadenza = $_siensa";
    }

    $data['rate'] = $_rate;

    return $data;
}

#fine funzione scadenza
// inizio gestione importi
// iniziamo a vedere le varietà di tipologie del modulo pagamenti.
// le varibili da prendere
// 	totimpo = totale dell'imponibile
// 	totiva = e il tototale dell'iva
// 	totdoc = totale del docuemento
//	tipo pagamento

function importi($_pagamento, $_totimpo, $_totiva, $_totdoc)
{
    global $conn;
    global $_percorso;
    global $dec;


#restituisco la variabile importi che è an arre con gli importi
#verifichiamo il pagamento
    $query = ("SELECT * FROM pagamenti WHERE codice='$_pagamento'");

    if (gettype($conn) == "object")
    {

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        //passo il risultato della linea al sistema
        foreach ($result AS $datip)
            ;
    }
    else
    {
        $resp = mysql_query($query, $conn);

        $datip = mysql_fetch_array($resp);
    }




    $_rate = $datip['nscad'];
    $_ggpri = $datip['ggprimascad'];
    $_ggtra = $datip['ggtrascad'];
    $_fm = $datip['dffm'];
    $_scadfissa = $datip['scadfissa'];
    $_unomese = $datip['unomese'];
    $_duemese = $datip['duemese'];
    $_tipopag = $datip['tipopag'];
    $_rataiva = $datip['rataiva'];
    $_trata = $datip['tipopag'];





// variabili di importo rata..
//
// 		1 - Iva divisa sulle varie rate
// 		2 - Iva applicata totalmente sulla prima rata
// 		3 - Iva applicata totalmente sull' ultima rata
// 		4 - La prima rata è solo l'iva
// per ogni occasione dobbiamo fare la prova del nove..
// ora verifichiamo la tipologia di rata e dividiamo i compiti

    if ($_rataiva == "1")
    {
// inizio con calcolo standard dividiamo l'importo per le rate
#mettiamo una sicurezza nel programma..
        if ($_rate == "0")
        {
            $_rate = "1";
        }

        @$_rata = number_format(($_totdoc / $_rate), $dec, '.', '');

// 	associamo ad un array l'importo delle rate singole
// per ogni rata associamo l'importo

        for ($_a = 1; $_a <= $_rate; $_a++)
        {
            $importo[$_a] = $_rata;
        }

// 	Prova del nove
// divise le rate bisogna vedere le la somma coincide
// 	Facciamo la somma delle rate con la momtiplicazione
        $_totrate = $_rata * $_rate;

// 	Facciamo la differenza tra la moltiplicazione e il totale del documento
        $_differenza = $_totdoc - $_totrate;

// 	La differenza va tolta prendiamo il totale delle rate e togliamo una rata di cui facciamo i conti
        $_purata = $_totrate - $_rata;

// si crea una ultima rata diversa facendo conto della differenza
        $_ulrata = $_rata + $_differenza;

// 	il totale sara ultima rata piu il resto
        $_totale = $_ulrata + $_purata;

        $_differenza2 = ($_ulrata + $_purata) - $_totdoc;

        $importo[$_rate] = $_ulrata;
    }
    elseif ($_rataiva == "2")
    {
// 2 - Iva applicata totalmente sulla prima rata
// le varibili da prendere
// 	totimpo = totale dell'imponibile
// 	totiva = e il tototale dell'iva
// 	totdoc = totale del docuemento

        $_rata = number_format(($_totimpo / $_rate), $dec, '.', '');

// 	associamo ad un array l'importo delle rate singole
// per ogni rata associamo l'importo

        for ($_a = 1; $_a <= $_rate; $_a++)
        {
            $importo[$_a] = $_rata;
        }

// 		Ora bisogna aggiungere l'iva alla prima rata e verificare che la somma coincida.

        $_primarata = $_rata + $_totiva;

// 	Prova del nove
// divise le rate bisogna vedere le la somma coincide
// 	Facciamo la somma delle rate con la momtiplicazione
        $_totrate = (($_rata * $_rate) + $_totiva);

// 	Facciamo la differenza tra la moltiplicazione e il totale del documento
        $_differenza = $_totdoc - $_totrate;

// 	La differenza va tolta prendiamo il totale delle rate e togliamo una rata di cui facciamo i conti
        $_purata = $_totrate - $_rata;

// si crea una ultima rata diversa facendo conto della differenza
        $_ulrata = $_rata + $_differenza;

// 	il totale sara ultima rata piu il resto
        $_totale = $_ulrata + $_purata;

//settiamo la prima e l'ultima rata
        $importo['1'] = $_primarata;

        $importo[$_rate] = $_ulrata;
    }
    elseif ($_rataiva == "3")
    {
// 		3 - Iva applicata totalmente sull' ultima rata
// le varibili da prendere
// 	totimpo = totale dell'imponibile
// 	totiva = e il tototale dell'iva
// 	totdoc = totale del docuemento

        $_rata = number_format(($_totimpo / $_rate), $dec, '.', '');

// 	associamo ad un array l'importo delle rate singole
// per ogni rata associamo l'importo

        for ($_a = 1; $_a <= $_rate; $_a++)
        {
            $importo[$_a] = $_rata;
        }

// 	Prova del nove
// divise le rate bisogna vedere le la somma coincide
// 	Facciamo la somma delle rate con la momtiplicazione
        $_totrate = (($_rata * $_rate) + $_totiva);

// 	Facciamo la differenza tra la moltiplicazione e il totale del documento
        $_differenza = $_totdoc - $_totrate;

// 	La differenza va tolta prendiamo il totale delle rate e togliamo una rata di cui facciamo i conti
        $_purata = $_totrate - $_rata;

// si crea una ultima rata diversa facendo conto della differenza
        $_ulrata = $_rata + $_differenza;

// 		Ora aggiungiamo l'iva all'ultima rataiva

        $_ultima_rata = $_ulrata + $_totiva;

        $importo[$_rate] = $_ultima_rata;
    }
    else
    {
// 		// 4 - La prima rata è solo l'iva
// le varibili da prendere
// 	totimpo = totale dell'imponibile
// 	totiva = e il tototale dell'iva
// 	totdoc = totale del docuemento

        @$_rata = number_format(($_totimpo / $_rate), $dec, '.', '');

// 	associamo ad un array l'importo delle rate singole
// per ogni rata associamo l'importo
// 		Settiamo la prima rata l'iva;

        $importo['1'] = $_totiva;

// 		il numero delle rate sara il numero totale meno una che e la prima
        $_rate = $_rate - 1;

        for ($_a = 1; $_a <= $_rate; $_a++)
        {
            $importo[$_a] = $_rata;
        }

// 	Prova del nove
// divise le rate bisogna vedere le la somma coincide
// 	Facciamo la somma delle rate con la momtiplicazione
        $_totrate = (($_rata * $_rate) + $_totiva);

// 	Facciamo la differenza tra la moltiplicazione e il totale del documento
        $_differenza = $_totdoc - $_totrate;

// 	La differenza va tolta prendiamo il totale delle rate e togliamo una rata di cui facciamo i conti
        $_purata = $_totrate - $_rata;

// si crea una ultima rata diversa facendo conto della differenza
        $_ulrata = $_rata + $_differenza;

// 		sistemiamo l ultima rata
        $importo[$_rate] = $_ulrata;
    }

    return array("valore" => $importo, "numero" => $_rate, "trata" => $_trata);
}
?>