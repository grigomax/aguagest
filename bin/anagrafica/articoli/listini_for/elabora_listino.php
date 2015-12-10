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

echo "<font size=\"2\"> \n";

//ora c'é il discorso del codice articoli e della generazione dei prezzi..
// mi conviene creare due funzioni una che mi genera il prezzo di vendita
// e una che mi genera il codice articolo se non trovato..
// e una che mi genera il prezzo di acquisto completo sconti ecc.

function prezzo_vendita($_acqnetto)
{
    global $dec;
    global $_POST;


    if ($_POST['percento'] == "SI")
    {
        $_prezzo = (($_acqnetto * $_POST['moltiplica']) / 100) + $_acqnetto;
    }
    else
    {
        //echo $_POST['moltiplica'];
        //echo $_acqnetto;
        $_prezzo = $_acqnetto * $_POST['moltiplica'];
    }


    return number_format(($_prezzo), $dec);
}

function genera_codice($_cosa, $_newcod, $_cod_start, $_multi_cod, $_codfornitore)
{
    //funzioni globali..
    global $conn;
    global $_percorso;
    global $_POST;

    //consideriamo, i post.. e le funzioni..

    /*
     * prima funzione..
     * aggiornamento in base al codice fornitore..
     * seconda funzione..
     * inserimento di codici nuovi a partire da un xxx
     * terza funzione
     * associamo al codice articolo il codice fornitore..
     * 
     */

    //son della serie che bisogna creare una funzione che se ce aggiorna altrimenti inserisce..

    if ($_cosa == "genera_numero")
    {
        if ($_newcod == "SI")
        {
            $return = $_cod_start + $_multi_cod;
        }
        else
        {
            $return = $_newcod;
        }
    }
    elseif ($_cosa == "cod_fornitore")
    {

        $articolo = tabella_articoli("check", $_codfornitore, $_parametri);

        if ($articolo == true)
        {
            $_cod_start ++;
            $articolo = tabella_articoli("check", $_codfornitore, $_parametri);

            if ($articolo == "false")
            {
                //inseriamo
                $return = tabella_articoli("blocca", $_codfornitore, $_parametri);
            }
            else
            {
                echo "<h1> Impossibile proseguire numerazione con codice $_codfornitore Errate </h1>\n";
                exit;
            }
        }
        else
        {
            //inseriamo
            $return = tabella_articoli("blocca", $_codfornitore, $_parametri);
            
        }

    }
    else
    {
        //partiamo dal fatto che si aggiorni l'articolo..

        if ($_POST['newcod'] != "SI")
        {
            //vuodire che è un aggiornamento del listino metel..
            //qui aggiorniamo..
            //iniziamo a selezionare l'articolo per vedere se c'è..
            $articolo = tabella_articoli("fornitori", $_codfornitore, $_parametri);

            if ($articolo == "NO")
            {
                //domanda è inseriamo..
                if ($_POST['codforart'] == "SI")
                {
                    //lo inseriamo..

                    $articolo = genera_codice("cod_fornitore", $_newcod, $_cod_start, $_multi_cod, $_codfornitore);
                }
            }

            $return = $articolo;
        }
        else
        {

            //Verifichiamo se il vogliamo il codfornitore uguale al codice articolo..
            if ($_POST['codforart'] == "SI")
            {
                //echo "qui";
                 $return = genera_codice("cod_fornitore", $_newcod, $_cod_start, $_multi_cod, $_codfornitore);

                 //echo $return;
            }
            else
            {
                //altrimenti lo inseriamo...
                //generiamo il codice..
                $_cod_start = genera_codice("genera_numero", $_POST['newcod'], $_cod_start, $_POST['multi_cod'], $_codfornitore);
                //verifichia che il codice non sia già presente..
                //poi verifichiamo che non sia già presente se no lo inseriamo solo parzialemnte e poi 
                //lo aggiorniamo con la funzione in essere..

                $articolo = tabella_articoli("check", $_cod_start, $_parametri);

                if ($articolo == true)
                {
                    $_cod_start ++;
                    $articolo = tabella_articoli("check", $_cod_start, $_parametri);

                    if ($articolo == "false")
                    {
                        //inseriamo
                        $articolo = tabella_articoli("blocca", $_cod_start, $_parametri);
                    }
                    else
                    {
                        echo "<h1> Impossibile proseguire numerazione con codice $_cod_start Errate </h1>\n";
                        exit;
                    }
                }
                else
                {
                    $articolo = tabella_articoli("blocca", $_cod_start, $_parametri);
                   //se il codice fornitore è uguale al codice articolo..
                    $return['risultato'] = "SI";
                    $return['articolo'] = $_cod_start; 
                    //echo "ciao $_cod_start\n";
                }

                
            }
        }

    }


    return $return;
}

function prezzo_acquisto($_prelisacq, $_scaa, $_scab, $_scac, $_preacqnetto)
{
    global $dec;
    //funzione che mi crea il prezzo di listino..
    // mi ritorna un array con 

    $_prenetto = number_format(($_prelisacq - (($_prelisacq * $_scaa) / 100)), $dec);

    if ($_scab != "0")
    {
        $_prenetto = number_format(($_prenetto - (($_prenetto * $_scab) / 100)), $dec);
    }

    if ($_scac != "0")
    {
        $_prenetto = number_format(($_prenetto - (($_prenetto * $_scac) / 100)), $dec);
    }



    return $_prenetto;
}

function colonna_fornitore($_cosa, $_nonPOST, $_A)
{
    global $_num_colonna;
    #echo $_POST[campo . $_A];
//bene ora che sappiamo il numero del fornitore possiamo associargli i campi corretti.


    $_num_colonna[$_A] = $_POST[campo . $_A];


    if($_POST['num_forn'] == "3")
    {
        if ($_POST[campo . $_A] == "artfor")
        {
            $_num_colonna[$_A] = "artfor_3";
        }
    }
    
    if($_POST['num_forn'] == "2")
    {
        if ($_POST[campo . $_A] == "artfor")
        {
            $_num_colonna[$_A] = "artfor2";
        }
    }
    
    if ($_POST[campo . $_A] == "artfor")
    {
        $_num_colonna[$_A] = "artfor";
    }
    
    
    if ($_POST[campo . $_A] == "codfor")
    {
        $_num_colonna[$_A] = "fornitore";
    }
    if ($_POST[campo . $_A] == "prelisacq")
    {
        $_num_colonna[$_A] = "prelisacq";
    }
    if ($_POST[campo . $_A] == "scaa")
    {
        $_num_colonna[$_A] = "scaa";
    }
    if ($_POST[campo . $_A] == "scab")
    {
        $_num_colonna[$_A] = "scab";
    }
    if ($_POST[campo . $_A] == "scac")
    {
        $_num_colonna[$_A] = "scac";
    }
    if ($_POST[campo . $_A] == "preacqnetto")
    {
        $_num_colonna[$_A] = "preacqnetto";
    }
    if ($_POST[campo . $_A] == "qtaminord")
    {
        $_num_colonna[$_A] = "qtaminord";
    }
    if ($_POST[campo . $_A] == "data_var")
    {
        $_num_colonna[$_A] = "data_var";
    }
    if ($_POST[campo . $_A] == "lead_time")
    {
        $_num_colonna[$_A] = "lead_time";
    }


    #echo $_num_colonna[$_A] . "|";

    return $_num_colonna;
}

/* * La funzione mi permette di variare il codice unita di tre cifre in quello di due..
 * 
 * @param type $_cosa
 * @param type $_nuova
 * @param type $_vecchia
 */

function unita($_cosa, $_nuova, $_vecchia)
{

    $cambio['PCE'] = "NR";
    $cambio['BLI'] = "NR";
    $cambio['BRD'] = "CF";
    $cambio['KGM'] = "KG";
    $cambio['LE'] = "LT";
    $cambio['LM'] = "MT";
    $cambio['PL'] = "PL";


    return $cambio[$_vecchia];
}

function genera_query($_cosa, $articolo, $_parametri)
{

    //cose globali..
    global $conn;
    global $dec;
    global $_percorso;
    global $_POST;
    



    echo "<br>Elaborazione codice $articolo[articolo]..\n";
    //ora che abbia l'arrey vediamo a cosa corrisponde..
    
//----------------- nuova
    if ($_parametri['artfor'] == $articolo['artfor_3'])
    {
        $parte1 = "prelisacq_3 = '$_parametri[prelisacq]', scaa_3='$_parametri[scaa]',scab_3='$_parametri[scab]', scac_3='$_parametri[scac]', preacqnetto_3='$_parametri[nettoacq]',"
                . "qta_cartone_3 = '$_parametri[qta_cartone]', qta_multi_ord_3 = '$_parametri[qta_multi_ord]', qtaminord_3 = '$_parametri[qtaminord]', lead_time_3='$_parametri[lead_time]', prod_composto_3='$_parametri[prod_composto]',"
                . "stato_prod_3 = '$_parametri[stato_prod]', data_var_3='$_parametri[data_var]', art_alternativo='$_parametri[art_alternativo]' ";
    }
    elseif ($_parametri['artfor'] == $articolo['artfor2'])
    {

        $parte1 = "prelisacq_2 = '$_parametri[prelisacq]', scaa_2='$_parametri[scaa]',scab_2='$_parametri[scab]', scac_2='$_parametri[scac]', preacqnetto2='$_parametri[nettoacq]',"
                . "qta_cartone_2 = '$_parametri[qta_cartone]', qta_multi_ord_2 = '$_parametri[qta_multi_ord]', qtaminord_2 = '$_parametri[qtaminord]', lead_time_2='$_parametri[lead_time]', prod_composto_2='$_parametri[prod_composto]',"
                . "stato_prod_2 = '$_parametri[stato_prod]', data_var_2='$_parametri[data_var]', art_alternativo='$_parametri[art_alternativo]' ";
    }
    else
    {
        $parte1 = "prelisacq = '$_parametri[prelisacq]', scaa='$_parametri[scaa]',scab='$_parametri[scab]', scac='$_parametri[scac]', preacqnetto='$_parametri[nettoacq]',"
                . "qta_cartone = '$_parametri[qta_cartone]', qta_multi_ord = '$_parametri[qta_multi_ord]', qtaminord = '$_parametri[qtaminord]', lead_time='$_parametri[lead_time]', prod_composto='$_parametri[prod_composto]',"
                . "stato_prod = '$_parametri[stato_prod]', data_var='$_parametri[data_var]', art_alternativo='$_parametri[art_alternativo]' ";
    }



    //aggiungiamo eventuali altri campi..

    if ($_POST['descrizione'] == "SI")
    {
        $_descrizione = addslashes($_parametri['descrizione']);
        $parte1.= ",descrizione='$_descrizione'";
    }

    if ($_POST['unita'] == "SI")
    {
        echo $_parametri['unita'];
        $_unita = unita($_cosa, $_unita, $_parametri['unita']);

        $parte1.= ",unita='$_unita'";
    }
    else
    {
        if ($_POST['newcod'] == "SI")
        {
            $parte1.= ",unita='$_POST[unita_man]'";
        }
    }

    if ($_POST['tipologia'] == "SI")
    {
        //echo "ciao";
        //echo $_POST['tipologia'];
        //echo $_tipart;
        $parte1.= ",tipart='$_parametri[tipart]'";
    }
    else
    {
        if ($_POST['newcod'] == "SI")
        {
            $parte1.= ",tipart='$_POST[tipart]'";
        }
    }

    if ($_POST['newcod'] == "SI")
    {

        $_descsito = addslashes($_POST['descsito']);

        $parte1.=", data_reg='$_parametri[datareg]', fornitore='$_POST[codfor]', artfor='$_parametri[artfor]', catmer='$_POST[catmer]', esma='$_POST[esma]', pubblica='$_POST[pubblica]', iva='$_POST[iva]', immagine='$_POST[immagine]', descsito='$_descsito' ";
    }

    //qui se noi non volessimo aggiornare ma inserire.. ?

    $query = "UPDATE articoli SET " . $parte1 . "where articolo = '$articolo[articolo]' limit 1";

    //echo "<br>$query\n";
    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "elabora listino.php";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    //echo $query . "<br>\n";
    //aggiornaimoil codice a barre se non ce lo inseriamo..

    if ($_POST['newcod'] == "SI")
    {
        //echo 'ciao';
        tabella_barcode("Inserisci", $articolo['articolo'], $articolo['articolo'], "0");
    }

    if ($_parametri['codbar'] != "")
    {
        $verifica = tabella_barcode("check", $_parametri['codbar'], $_articolo, $_rigo);

        if ($verifica['presenza'] == "NO")
        {
            tabella_barcode("Inserisci", $_parametri['codbar'], $articolo['articolo'], "1");
            echo "Inserito barcod..\n";
        }
        else
        {
            tabella_barcode("Aggiorna", $_parametri['codbar'], $articolo['articolo'], $verifica['rigo']);
            echo "Aggiornato barcod..\n";
        }
    }

    if ($_POST['vendita'] == "SI")
    {
        $_parametri['listino'] = prezzo_vendita($_parametri['nettoacq']);

        //questa funzione verifica se esiste l'articolo.. se cè lo aggiorno altrimenti lo inserisco..
        $_errori = tabella_listini("agg_singolo", $articolo['articolo'], $_POST['nlv'], $_parametri);

        if ($_errori['errori'] != "")
        {
            // Inizio tabella pagina principale ----------------------------------------------------------
            echo "<table width=\"95%\" cellspacing=\"0\" border=\"1\" align=\"left\" cellpadding=\"4\">\n";
            // includo la barra di navigazione

            echo "<span class=\"testo_blu\"><h3>Trovato un Errore.. = $_errori[errori]</h3></span>";
            echo "<center><h2><br> Errore inserimento prezzi articolo si prega di Verificare</h2>\n";
            echo "<center><h2><br> Errore errore Registrato</h2>\n";
            exit;
        }
        else
        {
            echo "Aggiornato listino..\n";
        }
    }
}

if ($_SESSION['user']['anagrafiche'] > "3")
{
    echo "<table border=\"0\" width=\"100%\" align=\"left\" valign=\"TOP\">\n";
    echo "<tr><td valign=\"top\">\n";
    echo "<h3 align=\"center\">Elaborazione file</h3>\n";
    echo "<center>\n";

    //settiamo il codice di partenza..
    $_cod_start = $_POST['cod_start'];
    $_parametri['datareg'] = date('Y-m-d');

    if (file_exists($_percorso . "../spool/" . $_POST['nomefile']))
    {

        $news = fopen($_percorso . "../spool/" . $_POST['nomefile'] . "", "r"); //apre il file


        $separatore = $_POST['separatore'];

        if ($separatore == "METEL")
        {

            $_tipolistino = substr($_POST['nomefile'], 3, 3);

            if ($_tipolistino == "LSG")
            {
                echo "listino per rivenditore";
            }
            elseif ($_tipolistino == "FSC")
            {
                echo "Famiglie di sconto";
            }
            elseif ($_tipolistino === "FST")
            {
                echo "famiglie statistiche";

//                    $news = fopen($_percorso . "../spool/" . $_FILES["file"]["name"] . "", "r"); //apre il file
                $buffer = fgets($news, 4096);

                echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"1\">\n";
                echo "<tr>\n";
                echo "<td>\n";
                echo "<h4>Tipo Tracciato= " . substr($buffer, 0, 20) . "</h4>\n";
                echo "Versione = " . substr($buffer, 20, 3) . "\n";

                echo "<h3>Inizio inserimento/aggiornamento</h3>\n";

                while (!feof($news))
                {
                    //echo "ciao";
                    //leggiamo la riga..
                    $buffer = fgets($news, 4096);

                    //assegnamo i campi..
                    //echo "</td></tr>\n";
                    //echo "<tr>\n";
                    //echo "<td><h4>Esempio prima Riga</h4>\n";
                    //echo "Marchio= " . substr($buffer, 0, 3) . "<br>\n";
                    //echo "Marca= " . substr($buffer, 3, 3) . "<br>\n";
                    $_codice = trim(substr($buffer, 6, 18));
                    $_parametri['descrizione'] = trim(substr($buffer, 24, 70));


                    $verifica = tabella_tipart("check_codice", $_codice, $_parametri);


                    if ($verifica == "OK")
                    {
                        tabella_tipart("inserisci", $_codice, $_parametri);

                        echo "<br>Inserita tipologia $_codice\n";
                    }
                    else
                    {
                        tabella_tipart("aggiorna", $_codice, $_parametri);
                        echo "<br>Aggiornamta tipologia $_codice";
                    }
                }
            }
            elseif ($_tipolistino == "BAR")
            {
                echo "barcode";
            }
            else
            {

                //$news = fopen($_percorso . "../spool/" . $_POST['nomefile'] . "", "r"); //apre il file
                $buffer = fgets($news, 4096);

                echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"1\">\n";
                echo "<tr>\n";
                echo "<td>\n";
                echo "<h4>Tipo Listino= " . substr($buffer, 0, 20) . "</h4>\n";
                echo "Sigla Azienda = " . substr($buffer, 20, 3) . "\n";
                $iva_utente = substr($buffer, 23, 11);
                echo "<b>Partita iva azienda = $iva_utente</b>\n";
                echo "Numero listino =" . substr($buffer, 34, 6) . "<br>\n";
                echo "Decorrenza =" . substr($buffer, 40, 8) . "\n";
                echo "Ultima Variazione =" . substr($buffer, 48, 8) . "<br>\n";
                echo "<h4>Descrizione listino =" . substr($buffer, 56, 30) . "</h4>\n";
                echo "Versione tracciato =" . substr($buffer, 125, 3) . "\n";
                echo "data grossista = " . substr($buffer, 128, 8) . "\n";
                echo "Isopartita= " . substr($buffer, 136, 16) . "\n";
                //assegnamo i campi..
                echo "</td></tr>\n";
                echo "</td></tr></table>\n";

                $utente = tabella_fornitori("singola_parametri", $iva_utente, "piva LIKE");
            }

            while (!feof($news))
            {
                //echo "ciao";
                //leggiamo la riga..
                $buffer = fgets($news, 4096);
                //siccome è un aggiornamento del listino acquisto..
                //direi di cercare il codice del prodotto e poi aggiornare l'anagrafica acquisto 
                //incluso del codice a barre ecc.. 
                $_parametri['datareg'] = date('Y-m-d');
                $_parametri['artfor'] = trim(substr($buffer, 3, 16));

                $_parametri['codbar'] = trim(substr($buffer, 19, 13));
                $_parametri['descrizione'] = trim(substr($buffer, 32, 43));
                $_parametri['qta_cartone'] = substr($buffer, 75, 5);
                $_parametri['qta_multi_ord'] = substr($buffer, 80, 5);
                $_parametri['qtaminord'] = substr($buffer, 85, 5);
                $_parametri['qtamaxord'] = substr($buffer, 90, 6);
                $_parametri['lead_time'] = substr($buffer, 96, 1);

                $_parametri['unita'] = substr($buffer, 128, 3);
                $_parametri['prod_composto'] = substr($buffer, 131, 1);
                $_parametri['stato_prod'] = substr($buffer, 132, 1);
                $_parametri['data_var'] = substr($buffer, 133, 8);

                #echo "fam. sconto= " . substr($buffer, 141, 18) . "<br>\n";
                $_parametri['tipart'] = trim(substr($buffer, 159, 18));
                #echo "Electrocod =" . substr($buffer, 177, 20) . "<br>\n";
                #echo "Codice barcode= " . substr($buffer, 197, 35) . "<br>\n";
                #echo "Q. codice =" . substr($buffer, 232, 1) . "<br>\n";
                //prepariamo le variabili
                $decimale = substr($buffer, 106, 2);
                #echo $decimale;
                $valore = substr($buffer, 97, 9);
                #echo $valore;
                $_parametri['prelisacq'] = "$valore.$decimale";

                $_parametri['nettoacq'] = prezzo_acquisto($_parametri['prelisacq'], $_POST['scaa'], $_POST['scab'], $_POST['scac'], $_preacqnetto);

                // qui finisce il discorso varibili e dovremmo anche chiudere.. :-D
                //passare il tutto alla funzione che mi gestisce i dati..
                //lavorerei con la variabile parametri e passarei il tutto alla funzione 

                $articolo = genera_codice("genera", $_newcod, $_cod_start, $_multi_cod, $_parametri['artfor']);

                $_parametri['scaa'] = $_POST['scaa'];
                $_parametri['scab'] = $_POST['scab'];
                $_parametri['scac'] = $_POST['scac'];
      
                //la prossima funzione è la genera query..
                //ora se articolo == SI
                if ($articolo['risultato'] == "SI")
                {
                    genera_query($_cosa, $articolo, $_parametri);

                    $_cod_start = $articolo['articolo'];
                    
                }
                else
                {
                    echo "<br>Errore inserimento articolo.. ";
                    echo "non si capisce";
                }
                $_parametri = "";
            }
        }// fine separazione metell..
        else
        {
            //inizio a trattare il file CSV o txt..
            //* Sappiamo che il numero dei campi passati è dato dal $_POST['numero_campiì];
            #echo $_POST['numero_campi'];
            $_nr = $_POST['numero_campi'];
            /* per praticità passerei il numero nome dai campi su un array con un ciclo di exploy..
             * 
             */

            //ora abbiamo l'array colonna con le colonne presenti con un valore assegnato di ok..
            for ($_A = 0; $_A <= $_nr; $_A++)
            {

                //prima di rinominare le colonne devo verificare che non ci siano da cambiare i campi in base al fornitore..
                $_colonna [$_POST[campo . $_A]] = "OK";
                //verifichiamo se c'è anche il codice fornitore ma più inportate è il numero del fornitore
                // in quanto è una colonna obbligatoria..
                //passo alla funzione che mi controlla le colonne..
                $_num_colonna = colonna_fornitore($_cosa, $_POST, $_A);

                #echo $_num_colonna[$_A] . "|";
            }


            if (($_POST['num_forn'] != "num_for") AND ( $_colonna['descrizione'] == "OK"))
            {

                //verifichiamo prezzi e sconti

                if ($_colonna['scaa'] == "OK")
                {
                    echo "la colonna scaa fornitore  esiste<br/>";

                    $_parametri['scaa'] = $_colonna['scaa'];
                }
                else
                {
                    echo "la colonna scaa non esiste in uno il POST valore sconto = $_POST[scaa] <br/>\n";
                    $_parametri['scaa'] = $_POST['scaa'];
                }

                if ($_colonna['scab'] == "OK")
                {
                    echo "la colonna scab  esiste<br/>";

                    $_parametri['scab'] = $_colonna['scab'];
                }
                else
                {
                    echo "la colonna scab non esiste in uno il POST valore sconto = $_POST[scab]<br/>\n";
                    $_parametri['scab'] = $_POST['scab'];
                }

                if ($_colonna['scac'] == "OK")
                {
                    echo "la colonna scac fornitore  esiste<br/>";

                    $_parametri['scac'] = $_colonna['scac'];
                }
                else
                {
                    echo "la colonna scac non esiste in uno il POST valore sconto = $_POST[scac]<br/>\n";
                    $_parametri['scac'] = $_POST['scac'];
                }

                if ($_colonna['prelisacq'] == "OK")
                {
                    echo "la colonna listino fornitore Trovata !<br/>";

                    $_parametri[$_prelisacq] = $_colonna['prelisacq'];
                }

                //codice fornitore
                if ($_colonna['codfor'] == "OK")
                {
                    echo "la colonna codice fornitore  esiste<br/>";

                    $_parametri['fornitore'] = $_colonna['codfor'];
                }
                elseif ($_POST['codfor'] != "")
                {
                    echo "la colonna codfor non esiste in uso codice = $_POST[codfor]<br/>\n";
                    $_parametri['fornitore'] = $_POST['codfor'];
                }
                else
                {
                    echo "Errore  campo obbligatorio codice fornitore non trovato<br/>\n";
                    //scriviamo l'errore
                    $_errori['descrizione'] = "codice fornitore non trovato" . $_FILES["file"]["name"];
                    $_errori['files'] = "elebora listino.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);

                    exit;
                }
            }
            else
            {
                echo "Errore  campo obbligatorio numero fornitore non trovato<br/>\n";
                //scriviamo l'errore
                $_errori['descrizione'] = "Numero fornitore non trovato" . $_FILES["file"]["name"];
                $_errori['files'] = "elebora listino.php";
                scrittura_errori($_cosa, $_percorso, $_errori);

                exit;
            }


            //vediamo se la colonna unità esiste altrimenti la assegnao
            //colonna unità
            if ($_colonna['unita'] == "OK")
            {
                echo "la colonna unità  esiste<br/>";
                $_parametri['inita'] = $_colonna['unita'];
            }
            elseif ($_POST['unita_man'] != "")
            {
                echo "la colonna unità non esiste in uso il POST $_POST[unita_man]<br/>\n";
                $_parametri['unita'] = $_POST['unita_man'];
            }
            else
            {
                if ($_POST['newcod'] == "SI")
                {
                    echo "Errore  procedimento interrotto non trovata la colonna Unità<br/>\n";
                    exit;
                }
            }

            //colonna catmercerologiche
            if ($_colonna['catmer'] == "OK")
            {
                echo "la colonna categorie  esiste<br/>";
                $_parametri['catmer'] = $_colonna['catmer'];
            }
            elseif ($_POST['catmer'] != "")
            {
                echo "la colonna catmer non esiste in uso il POST $_POST[catmer]<br/>\n";
                $_parametri['catmer'] = $_POST['catmer'];
            }
            else
            {
                if ($_POST['newcod'] == "SI")
                {
                    echo "Errore  procedimento interrotto non trovata la colonna catmer<br/>\n";
                    exit;
                }
            }

            //colonna tipologia artidolo
            if ($_colonna['tipart'] == "OK")
            {
                echo "la colonna tipologia  esiste<br/>";
                $_parametri['tipart'] = $_colonna['tipart'];
            }
            elseif ($_POST['tipart'] != "")
            {
                echo "la colonna tipart non esiste in uso il POST $_POST[tipart]<br/>\n";
                $_parametri['tipart'] = $_POST['tipart'];
            }
            else
            {
                if ($_POST['newcod'] == "SI")
                {
                    echo "Errore  procedimento interrotto non trovata la colonna tipart<br/>\n";

                    exit;
                }
            }

            //colonna tipologia artidolo
            if ($_colonna['iva'] == "OK")
            {
                echo "la colonna iva  esiste<br/>";
                $_parametri['iva'] = $_colonna['iva'];
            }
            elseif ($_POST['iva'] != "")
            {
                echo "la colonna iva non esiste in uso il POST $_POST[iva]<br/>\n";
                $_parametri['iva'] = $_POST['iva'];
            }
            else
            {
                if ($_POST['newcod'] == "SI")
                {
                    echo "Errore  procedimento interrotto non trovata la colonna tipart<br/>\n";

                    exit;
                }
            }


            //colonna tipologia artidolo
            if ($_colonna['descsito'] == "OK")
            {
                echo "la colonna desczito  esiste<br/>";
                $_parametri['descsito'] = $_colonna['descsito'];
            }
            elseif ($_POST['descsito'] != "")
            {
                echo "la colonna Descrizione del sito non esiste in uso il POST $_POST[descsito]<br/>\n";
                $_parametri['descsito'] = $_POST['descsito'];
            }
            else
            {
                if ($_POST['newcod'] == "SI")
                {
                    echo "Errore  procedimento interrotto non trovata la colonna descsito<br/>\n";
                    exit;
                }
            }

            //colonna tipologia artidolo
            if ($_colonna['articolo'] == "OK")
            {
                echo "la colonna articolo  esiste<br/>";
                $_cod_start = $_colonna['articolo'];
            }
            elseif (($_POST['newcod'] != "") AND ( $_POST['cod_start'] != ""))
            {
                echo "la colonna codice non esiste in uso il POST $_POST[cod_start]<br/>\n";
                $_cod_start = $_POST['cod_start'];
            }
            elseif (($_POST['newcod'] == "SI") AND ( $_POST['codforart'] == "SI"))
            {
                echo "la colonna codice articolo non esiste in uso codice fornitore $_colonna[codfor]<br/>\n";
                $_cod_start = $_colonna['codfor'];
            }
            else
            {
                if ($_POST['newcod'] == "SI")
                {
                    echo "Errore  procedimento interrotto non trovata la colonna codice<br/>\n";
                    exit;
                }
            }



            //saltiamo la prima riga che è l'indice..
            $buffer = fgets($news, 4096);
            //passiamo alla seconda
            //-------------------------INIZIO PROCEDIMENTO DI INSERIMENTO---------------------------------------------------
            //carichiamo le riche del file su un array per evitare un loop..
            while (!feof($news))
            {
                //leggiamo la riga..
                $buffer = fgets($news, 4096);

                //verifichiamo che non ci siano righe vuote altrimenti usciamo
                if ($buffer != "")
                {

                    //dividiamo la riga
                    $suddivisa = explode($separatore, $buffer);

                    //ora abbiamo l'array colonna con le colonne presenti con un valore assegnato di ok..
                    for ($_A = 0; $_A <= $_nr; $_A++)
                    {

                        if ($_num_colonna[$_A] == "prelisacq")
                        {
                            $suddivisa[$_A] = str_replace(",", ".", $suddivisa[$_A]);

                            $_nettoacq = prezzo_acquisto($suddivisa[$_A], $_parametri['scaa'], $_parametri['scab'], $_parametri['scac'], $_preacqnetto);

                            $_parametri['nettoacq'] = $_nettoacq;
                            
                            #echo $_parametri['preacqnetto'];
                        }//fine funzione if
                        // qui passiamo tutti i parametri delle colonne presenti all'array per poterli recuperare come se fosse
                        //una estrazione dei una riga di un database..
                        $_parametri[$_num_colonna[$_A]] = trim($suddivisa[$_A]);
                    }//fine funzione for..
                    //generiamo il listino di vendita..




                    //$_parametri['listino'] = prezzo_vendita($_nettoacq);


                    //proviamo a preparare la query di i nserimento
                    #echo "articolo = $_cod_start\n";
                    #echo "catmer = $_parametri[catmer]\n";
                    #echo "artfor = $_parametri[artfor]\n";
                    #echo "descrizione = $_parametri[descrizione]\n";
                    #echo "prelisacq = $_parametri[prelisacq]\n";
                    #echo "prenetto = $_parametri[preacqnetto]\n";
                    #echo "listino = $_listino \n";
                    #echo "foto = $_POST[immagine]\n";
                    #echo "<br/>\n";
                    //ora prepariamo l'inserimento della query..
                    //inseriamo prima l'articolo e poi il listino..
                    //finiamo di preparare l'array parametri
                    $_parametri['immagine'] = $_POST['immagine'];
                    $_parametri['pubblica'] = $_POST['pubblica'];
                    $_parametri['esma'] = $_POST['esma'];

                    // bene ora che ho tutti i parametri direi di generare il codice articolo e poi inserirlo o aggiornarlo..
                    //ora passiamo all'aggiornamento..

                    $articolo = genera_codice("genera", $_newcod, $_cod_start, $_multi_cod, $_parametri['artfor']);
                    
                    
                    //la prossima funzione è la genera query..
                //ora se articolo == SI
                if ($articolo['risultato'] == "SI")
                {
                    genera_query($_cosa, $articolo, $_parametri);

                    $_cod_start = $articolo['articolo'];
                    
                }
                else
                {
                    echo "<br>Errore inserimento articolo.. ";
                    echo "non si capisce";
                }
                

                }
                else
                {
                    //finito di leggere il file..
                    echo "<h3>finito di leggere il file.. </h3>\n";
                }
            }//fine funzione while
        }
    }//fine esistenza file
    //prendiamo tutto il malloppo funzioni lo lo mettiamo qui..
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>