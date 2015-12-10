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
require $_percorso . "../setting/par_conta.inc.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

if ($_SESSION['user']['contabilita'] > "1")
{

    //funzione che mi legge le operazioni da includere
    function leggi_registrazioni($_cosa, $_causale, $_positivita, $_anno, $_conto)
    {
        global $conn;
        global $_percorso;

        if ($_cosa == "clienti")
        {
            $query = "SELECT *, date_format(data_doc, '%d%m%Y') datadoc, date_format(data_cont, '%d-%m-%Y') data_cont FROM prima_nota INNER JOIN aliquota ON prima_nota.iva=aliquota.codice WHERE sp_metro != 'SI' AND ivacee != 'S' AND modello1012 !='S' AND causale='$_causale' AND data_doc LIKE '$_anno%'  AND segno='$_positivita' AND conto != '$_conto' GROUP BY nreg order by data_doc, CAST( ndoc AS SIGNED ) ASC, rigo";
        }
        else
        {
            $query = "SELECT *, date_format(data_doc, '%d%m%Y') datadoc, date_format(data_cont, '%d%m%Y') data_cont FROM prima_nota INNER JOIN aliquota ON prima_nota.iva=aliquota.codice WHERE sp_metro != 'SI' AND ivacee != 'S' AND modello1012 !='S' AND causale='$_causale' AND data_doc LIKE '$_anno%'  AND segno='$_positivita' AND conto = '$_conto' GROUP BY nreg order by data_doc, CAST( ndoc AS SIGNED ) ASC, rigo";
        }

        #echo $query;

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "stampa_reg.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }


        return $result;
    }

    function leggi_chie($_cosa, $_anno, $_nreg)
    {
        global $conn;
        global $percorso;
        global $MASTRO_CLI;
        global $MASTRO_FOR;


        if ($_cosa == "clienti")
        {
            $_mastro = $MASTRO_CLI;
        }
        else
        {
            $_mastro = $MASTRO_FOR;
        }

        $query = "SELECT * FROM prima_nota WHERE conto LIKE '$_mastro%' AND nreg='$_nreg' AND anno='$_anno'";
        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "stampa_reg.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result as $dati)
            ;

        if ($_cosa == "clienti")
        {
            $utente = tabella_clienti("partitaiva", substr($dati['conto'], 2), $_parametri);
            $dati['piva'] = $utente['piva'];
            $dati['codfisc'] = $utente['codfisc'];
        }
        else
        {
            $utente = tabella_fornitori("partitaiva", substr($dati['conto'], 2), $_parametri);

            $dati['piva'] = $utente['piva'];
            $dati['spesometro'] = $utente['spesometro'];
            $dati['codfisc'] = $utente['codfisc'];
        }


        return $dati;
    }

    //funzione che mi legge l'imponibile dalla registrazione
    function leggi_importi($_cosa, $_somma, $_conto_iva, $_codiva, $_nreg, $_anno)
    {
        global $conn;
        global $percorso;
        global $MASTRO_CLI;
        global $MASTRO_FOR;

        //leggo imposta


        if ($_cosa == "clienti")
        {
            $query = "SELECT *, SUM($_somma) AS imposta FROM prima_nota where conto = '$_conto_iva' AND iva='$_codiva' AND nreg='$_nreg' AND anno='$_anno'";

            $result = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "stampa_reg.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }

            foreach ($result as $dati)
                ;

            $return['imposta'] = $dati['imposta'];
        }
        else
        {
            $query = "SELECT *, SUM($_somma) AS imposta FROM prima_nota where conto = '$_conto_iva' AND iva='$_codiva' AND nreg='$_nreg' AND anno='$_anno'";

            $result = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "stampa_reg.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }

            foreach ($result as $dati)
                ;

            $return['imposta'] = $dati['imposta'];

            $query = "SELECT *, SUM($_somma) AS imposta FROM prima_nota where iva='' AND nreg='$_nreg' AND anno='$_anno'";

            $result = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "stampa_reg.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }

            foreach ($result as $dati)
                ;

            $return['imposta'] = $return['imposta'] + $dati['imposta'];
        }




        //leggiamo anche l'imponibile

        if ($_cosa == "clienti")
        {
            $query = "SELECT *, SUM($_somma) AS imponibile FROM prima_nota where conto != '$_conto_iva' AND iva='$_codiva' AND nreg='$_nreg' AND anno='$_anno'";
        }
        else
        {
            $query = "SELECT *, SUM($_somma) AS imponibile FROM prima_nota where conto != '$_conto_iva' AND iva !='' AND iva NOT LIKE 'E%' AND nreg='$_nreg' AND anno='$_anno'";
        }

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "stampa_reg.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result as $dati)
            ;

        $return['imponibile'] = $dati['imponibile'];

        return $return;
    }

    function campi($_cosa, $_rigo, $_colonna, $_tipo, $_valore)
    {

        //funzione che mi completa il campo da scrivere..

        if ($_tipo == "CB")
        {
            $_campo = str_pad('1', 16, ' ', STR_PAD_LEFT);
        }
        elseif (($_tipo == "PI") OR ( $_tipo == "CF") OR ( $_tipo == "PN") OR ( $_tipo == "AN") OR ( $_tipo == "PR") OR ( $_tipo == "PE"))
        {
            $_campo = str_pad($_valore, 16, ' ', STR_PAD_RIGHT);
        }
        else
        {
            $_campo = str_pad($_valore, 16, ' ', STR_PAD_LEFT);
        }


        return $_cosa . $_rigo . $_colonna . $_campo;
    }

    function scriviriga_D($_cosa, $_parametri)
    {
        global $codfisc;
        global $_moduloD;
        global $_rigoD;
        global $fp;


        $_moduloD ++;

        //conviene preparare tutta la riga intera e poi decidere i dati da immettere al suo interno in modo da mantenere la struttura..
        // creando una funzione che mi immetta i dati a man mano che mi servono..
        //inizio prima parte del record..
        $_commento = sprintf("D%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s", str_pad($codfisc, 16, ' ', STR_PAD_RIGHT), str_pad($_moduloD, 8, '0', STR_PAD_LEFT), str_pad($_filler, 3, ' ', STR_PAD_LEFT), str_pad($_filler, 25, ' ', STR_PAD_LEFT), str_pad($_filler, 20, ' ', STR_PAD_LEFT), str_pad('GRGMSM77E21G224K', 16, ' ', STR_PAD_RIGHT), $_parametri['1'], $_parametri['2'], $_parametri['3'], $_parametri['4'], $_parametri['5'], $_parametri['6'], $_parametri['7'], $_parametri['8'], $_parametri['9'], $_parametri['10']);

        $_commento = sprintf("%sA\r\n", str_pad($_commento, 1897, ' ', STR_PAD_RIGHT));


        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");
        $_rigoD ++;
    }

//dividiamo la sessione la parte stampa e la parte generatrice del file..
    $_parametri['anno'] = $_POST['anno'];
    $_parametri['data'] = date('d-m-Y');
    $_parametri['stampa'] = "Spesometro";
    $_parametri['tabella'] = "Stampa Analitica";

    if ($_POST['azione'] == "stampa")
    {


//imizio parte html..

               base_html_stampa("chiudi", $_parametri);


        $_parametri['tabella'] = "Stampa Analitica fornitori";
        intestazione_html($_cosa, $_percorso, $_parametri);

        echo "<table class=\"elenco_stampa\" border=\"0\">";

        // Tutto procede a meraviglia...
        echo "<thead>\n";
        echo "<tr>";
        echo "<tr><th colspan=\"10\"><hr></th></tr>\n";
        echo "<th width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">cod-iva</span></th>";
        echo "<th width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">P. iva</span></th>";
        echo "<th width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Cod. fisc.</span></th>";
        echo "<th width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Doc.</span></th>";
        echo "<th width=\"35\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></th>";
        echo "<th width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Causale</span></th>";
        echo "<th width=\"100\" align=\"CENTER\" class=\"logo\"><span class=\"testo_bianco\">Desc. Conto</span></th>";
        echo "<th width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></th>";
        echo "<th width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Imposta</span></th>";
        echo "<th width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">N.reg</span></th>";
        echo "</tr>";
        echo "<tr><th colspan=\"10\"><hr></th></tr>\n";
        echo "</thead>\n";
        echo "<tfoot>\n";
        echo "<tr><td colspan=\"10\"><hr></td></tr>\n";
        echo "</tfoot>\n";



        //leggiamo le registrazioni

        $result = leggi_registrazioni("fornitori", 'FA', 'P', $_POST['anno'], $CONTO_IVA_ACQUISTI);

        foreach ($result as $leggo)
        {

            $NUMERO++;

            //leggiamo di chi è ?

            $chie = leggi_chie("fornitori", $leggo['anno'], $leggo['nreg']);

            //selezioniamo l'imponibile

            if ($chie['spesometro'] != 'SI')
            {
                $importi = leggi_importi("fornitori", "dare", $CONTO_IVA_ACQUISTI, $leggo['iva'], $leggo['nreg'], $leggo['anno']);

                echo "<tbody><tr>";
                printf("<td class=\"tabella_elenco\" width=\"30\" align=\"center\">%s</td>", $leggo['iva']);
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"center\">%s</td>", str_replace('IT', '', $chie['piva']));
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"center\">%s</td>", $chie['codfisc']);
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"CENTER\">%s</td>", $NUMERO);
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"CENTER\">%s</td>", $leggo['ndoc']);
                printf("<td class=\"tabella_elenco\" width=\"40\" align=\"center\">%s</td>", $leggo['causale']);
                printf("<td class=\"tabella_elenco\" width=\"200\" align=\"left\">%s</td>", $chie['desc_conto']);

                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"right\">%s</td>", floor($importi['imponibile']));
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"right\">%s</td>", floor($importi['imposta']));
                printf("<td class=\"tabella_elenco\" width=\"70\" align=\"center\">%s</td>", $leggo['nreg']);
                $_imponibile = $_imponibile + floor($importi['imponibile']);
                $_imposta = $_imposta + floor($importi['imposta']);

                echo "</tr>";
            }

            echo "</tbody>\n";
        }


        echo "<tfoot>\n";
        echo "<tr><td colspan=\"6\"><hr></td><td>$_imponibile</td><td>$_imposta</td><td><hr></td></tr>\n";
        echo "</tfoot>\n";

        //stampiamo i clienti..
        
        $_parametri['tabella'] = "Stampa Analitica Clienti";
        intestazione_html($_cosa, $_percorso, $_parametri);

        echo "<table class=\"elenco_stampa\" border=\"0\">";

        // Tutto procede a meraviglia...
        echo "<thead>\n";
        echo "<tr>";
        echo "<tr><th colspan=\"10\"><hr></th></tr>\n";
        echo "<th width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">cod-iva</span></th>";
        echo "<th width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">P. iva</span></th>";
        echo "<th width=\"30\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Cod. fisc.</span></th>";
        echo "<th width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data Doc.</span></th>";
        echo "<th width=\"35\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Fattura</span></th>";
        echo "<th width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Causale</span></th>";
        echo "<th width=\"100\" align=\"CENTER\" class=\"logo\"><span class=\"testo_bianco\">Desc. Conto</span></th>";
        echo "<th width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></th>";
        echo "<th width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Imposta</span></th>";
        echo "<th width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">N.reg</span></th>";
        echo "</tr>";
        echo "<tr><th colspan=\"10\"><hr></th></tr>\n";
        echo "</thead>\n";
        echo "<tfoot>\n";
        echo "<tr><td colspan=\"10\"><hr></td></tr>\n";
        echo "</tfoot>\n";



        //leggiamo le registrazioni

        $result = leggi_registrazioni("clienti", 'FV', 'P', $_POST['anno'], $CONTO_IVA_ACQUISTI);

        foreach ($result as $leggo)
        {

            $NUMERO++;

            //leggiamo di chi è ?

            $chie = leggi_chie("clienti", $leggo['anno'], $leggo['nreg']);

            //selezioniamo l'imponibile

            if ($chie['spesometro'] != 'SI')
            {
                $importi = leggi_importi("clienti", "avere", $CONTO_IVA_ACQUISTI, $leggo['iva'], $leggo['nreg'], $leggo['anno']);

                echo "<tbody><tr>";
                printf("<td class=\"tabella_elenco\" width=\"30\" align=\"center\">%s</td>", $leggo['iva']);
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"center\">%s</td>", str_replace('IT', '', $chie['piva']));
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"center\">%s</td>", $chie['codfisc']);
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"CENTER\">%s</td>", $NUMERO);
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"CENTER\">%s</td>", $leggo['ndoc']);
                printf("<td class=\"tabella_elenco\" width=\"40\" align=\"center\">%s</td>", $leggo['causale']);
                printf("<td class=\"tabella_elenco\" width=\"200\" align=\"left\">%s</td>", $chie['desc_conto']);

                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"right\">%s</td>", floor($importi['imponibile']));
                printf("<td class=\"tabella_elenco\" width=\"50\" align=\"right\">%s</td>", floor($importi['imposta']));
                printf("<td class=\"tabella_elenco\" width=\"70\" align=\"center\">%s</td>", $leggo['nreg']);
                $_imponibile = $_imponibile + floor($importi['imponibile']);
                $_imposta = $_imposta + floor($importi['imposta']);

                echo "</tr>";
            }

            echo "</tbody>\n";
        }


        echo "<tfoot>\n";
        echo "<tr><td colspan=\"6\"><hr></td><td>$_imponibile</td><td>$_imposta</td><td><hr></td></tr>\n";
        echo "</tfoot>\n";
        
        
        
        
        

        echo "</table></body></html>";
        
        
    }
    else
    {
        // qui inizia la sezione  di elaborazione file stampa..
        base_html("chiudi", $_percorso);

        //carichiamo la testata del programma.
        testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
        menu_tendina($_cosa, $_percorso);


        echo "<table align=\"center\" width=\"100%\" border=\"0\">\n";
        echo "<tr>\n";

        echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
        echo "<span class=\"intestazione\"><br><b>Creazione Files<br> Conforme alla comunicazione 15/10/2013<br>Per modello Polivalente</b></span><br>\n";


        //creiamo il files..

        $_nomefile = $piva . "_NSP";


        $nfile = $_percorso . "../spool/$_nomefile.nsp";
// creo il files e nascondo la soluzione
        $fp = fopen($nfile, "w");
//controllo l'esito
        if (!$fp)
            die("Errore.. non sono riuscito a creare il file.. Permessi ?");


        //sezione riga tipo A
        $_escludi = array(".", "-", "/", ":");


        $_commento = sprintf("A%sNSP0001%s%s00000000%s%s%sA\r\n", str_pad($_filler, 14, ' ', STR_PAD_LEFT), str_pad($codfisc, 16, ' ', STR_PAD_RIGHT), str_pad($_filler, 483, ' ', STR_PAD_LEFT), str_pad($_filler, 100, ' ', STR_PAD_LEFT), str_pad($_filler, 1068, ' ', STR_PAD_LEFT), str_pad($_filler, 200, ' ', STR_PAD_LEFT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");


        //sezione riga tipo B
        //impostiamo la partitaiva
        $_IVA_B = str_replace('IT', '', $piva);


        $_commento = sprintf("B%s%s%s%s%s%s100%s%s01000111100001%s%s%s%s%s%s%s%s%s$_POST[anno]%s%s%s%s%s%s%s%s%sA\r\n", str_pad($codfisc, 16, ' ', STR_PAD_RIGHT), str_pad('1', 8, '0', STR_PAD_LEFT), str_pad($_filler, 3, ' ', STR_PAD_LEFT), str_pad($_filler, 25, ' ', STR_PAD_LEFT), str_pad($_filler, 20, ' ', STR_PAD_LEFT), str_pad('GRGMSM77E21G224K', 16, ' ', STR_PAD_RIGHT), str_pad($_filler, 17, '0', STR_PAD_LEFT), str_pad($_filler, 6, '0', STR_PAD_LEFT), str_pad($_IVA_B, 11, ' ', STR_PAD_RIGHT), str_pad($CODATTIVITA, 6, ' ', STR_PAD_LEFT), str_pad(str_replace($_escludi, '', $telefono), 12, ' ', STR_PAD_RIGHT), str_pad(str_replace($_escludi, '', $fax), 12, ' ', STR_PAD_RIGHT), str_pad($email3, 50, ' ', STR_PAD_RIGHT), str_pad($_filler, 45, ' ', STR_PAD_LEFT), str_pad($_filler, 8, '0', STR_PAD_LEFT), str_pad($_filler, 42, ' ', STR_PAD_LEFT), str_pad($azienda, 60, ' ', STR_PAD_RIGHT), str_pad($_filler, 18, ' ', STR_PAD_LEFT), str_pad($_filler, 18, '0', STR_PAD_LEFT), str_pad($_filler, 45, ' ', STR_PAD_LEFT), str_pad($_filler, 8, '0', STR_PAD_LEFT), str_pad($_filler, 118, ' ', STR_PAD_LEFT), str_pad($_filler, 6, '0', STR_PAD_LEFT), str_pad($_filler, 1, ' ', STR_PAD_LEFT), str_pad($_filler, 8, '0', STR_PAD_LEFT), str_pad($_filler, 1296, ' ', STR_PAD_LEFT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");
        $_rigoB ++;


        //sesione riga tipo C
        //
		
		
		
		
		//sezione rihe tipo D
        //
		//
		
		//iniziamo a leggere le fatture vendita

        $result = leggi_registrazioni("clienti", 'FV', 'P', $_POST['anno'], $CONTO_IVA_VENDITE);

        //creiamo un ciclo di for da 1 a 6 per la scrittura delle righe campi..

        foreach ($result as $leggo)
        {

            $index ++;
            //leggiamo di chi è ?

            $chie = leggi_chie("clienti", $leggo['anno'], $leggo['nreg']);

            //selezioniamo l'imponibile

            $importi = leggi_importi("clienti", "avere", $CONTO_IVA_VENDITE, $leggo['iva'], $leggo['nreg'], $leggo['anno']);

            //leggiamo la partita iva se inizia con zzz la annulliamo e mettiamoil codice fiscale.

            $pizza = substr($chie['piva'], '0', '3');

            if ($pizza == "ZZZ")
            {
                $_rigo['campo02'] = campi('FE', '00' . $index, '002', 'CF', $chie['codfisc']);
            }
            else
            {
                $_rigo['campo01'] = campi('FE', '00' . $index, '001', 'PI', str_replace('IT', '', $chie['piva']));
            }
            $_rigo['campo07'] = campi('FE', '00' . $index, '007', 'DT', str_replace('-', '', $leggo['datadoc']));
            $_rigo['campo08'] = campi('FE', '00' . $index, '008', 'DT', str_replace('-', '', $leggo['datadoc']));
            $_rigo['campo09'] = campi('FE', '00' . $index, '009', 'AN', $leggo['ndoc']);
            $_rigo['campo10'] = campi('FE', '00' . $index, '010', 'NP', floor($importi['imponibile']));
            $_rigo['campo11'] = campi('FE', '00' . $index, '011', 'NP', floor($importi['imposta']));

            if (floor($importi['imposta']) == "0")
            {
                $_rigo['campo04'] = campi('FE', '00' . $index, '004', 'CB', '');
            }

            $_TA004001 ++;

            $_parametri[$index] = $_rigo['campo01'] . $_rigo['campo02'] . $_rigo['campo03'] . $_rigo['campo04'] . $_rigo['campo05'] . $_rigo['campo06'] . $_rigo['campo07'] . $_rigo['campo08'] . $_rigo['campo09'] . $_rigo['campo10'] . $_rigo['campo11'] . $_rigo['campo12'];


            $_rigo = null;
            //chiudiamo il cilo di for se arrivato a 6 chiudendo la scrittura e azzerendo l'indice

            if ($index == "6")
            {

                scriviriga_D($_campo, $_parametri);
                $_parametri = null;
                $index = "0";
            }
        }

        scriviriga_D($_campo, $_parametri);

        $_parametri = null;
        $index = null;
        $_rigo = null;
        $_campo = null;


        //qui scriviamo il quadro FR

        $result = leggi_registrazioni("fornitori", 'FA', 'P', $_POST['anno'], $CONTO_IVA_ACQUISTI);

        //creiamo un ciclo di for da 1 a 6 per la scrittura delle righe campi..

        foreach ($result as $leggo)
        {
            //leggiamo di chi è ?

            $chie = leggi_chie("fornitori", $leggo['anno'], $leggo['nreg']);

            //selezioniamo l'imponibile

            if ($chie['spesometro'] != 'SI')
            {
                $index ++;

                $importi = leggi_importi("fornitori", "dare", $CONTO_IVA_ACQUISTI, $leggo['iva'], $leggo['nreg'], $leggo['anno']);


                if (($chie['piva'] == "CARBU") OR ( $chie['piva'] == "RISTO"))
                {
                    $_rigo['campo02'] = campi('FR', '00' . $index, '002', 'CB', '');
                    $_TA005002 ++;
                    //echo $_TA005002;
                }
                else
                {
                    $_rigo['campo01'] = campi('FR', '00' . $index, '001', 'PI', str_replace('IT', '', $chie['piva']));
                    $_TA005001 ++;
                }

                $_rigo['campo03'] = campi('FR', '00' . $index, '003', 'DT', str_replace('-', '', $leggo['datadoc']));
                $_rigo['campo04'] = campi('FR', '00' . $index, '004', 'DT', str_replace('-', '', $leggo['data_cont']));
                $_rigo['campo08'] = campi('FR', '00' . $index, '008', 'NP', floor($importi['imponibile']));
                $_rigo['campo09'] = campi('FR', '00' . $index, '009', 'NP', floor($importi['imposta']));

                //if (floor($importi['imposta']) == "0")
                //{
                //		$_rigo['campo05'] = campi('FR', '00' . $index, '005', 'CB', '');
                //}


                $_parametri[$index] = $_rigo['campo01'] . $_rigo['campo02'] . $_rigo['campo03'] . $_rigo['campo04'] . $_rigo['campo05'] . $_rigo['campo06'] . $_rigo['campo07'] . $_rigo['campo08'] . $_rigo['campo09'] . $_rigo['campo10'] . $_rigo['campo11'] . $_rigo['campo12'];


                $_rigo = null;
                //chiudiamo il cilo di for se arrivato a 6 chiudendo la scrittura e azzerendo l'indice

                if ($index == "6")
                {

                    scriviriga_D($_campo, $_parametri);
                    $_parametri = null;
                    $index = "0";
                }
            }//fine esclusione spesometro
            //chiusure forearch
        }

        scriviriga_D($_campo, $_parametri);

        $_parametri = null;
        $index = null;
        $_rigo = null;
        $_campo = null;





        //qui scriviamo il quadro NE

        $result = leggi_registrazioni("clienti", 'FV', 'N', $_POST['anno'], $CONTO_IVA_VENDITE);

        //creiamo un ciclo di for da 1 a 6 per la scrittura delle righe campi..

        foreach ($result as $leggo)
        {

            $index ++;
            //leggiamo di chi è ?

            $chie = leggi_chie("clienti", $leggo['anno'], $leggo['nreg']);

            //selezioniamo l'imponibile

            $importi = leggi_importi("clienti", "dare", $CONTO_IVA_VENDITE, $leggo['iva'], $leggo['nreg'], $leggo['anno']);


            if ($index > 9)
            {
                $_rigo['campo01'] = campi('NE', '0' . $index, '001', 'PI', str_replace('IT', '', $chie['piva']));
                $_rigo['campo03'] = campi('NE', '0' . $index, '003', 'DT', str_replace('-', '', $leggo['datadoc']));
                $_rigo['campo04'] = campi('NE', '0' . $index, '004', 'DT', str_replace('-', '', $leggo['datadoc']));
                $_rigo['campo05'] = campi('NE', '0' . $index, '005', 'AN', $leggo['ndoc']);
                $_rigo['campo06'] = campi('NE', '0' . $index, '006', 'NP', floor($importi['imponibile']));
                $_rigo['campo07'] = campi('NE', '0' . $index, '007', 'NP', floor($importi['imposta']));
            }
            else
            {
                $_rigo['campo01'] = campi('NE', '00' . $index, '001', 'PI', str_replace('IT', '', $chie['piva']));
                $_rigo['campo03'] = campi('NE', '00' . $index, '003', 'DT', str_replace('-', '', $leggo['datadoc']));
                $_rigo['campo04'] = campi('NE', '00' . $index, '004', 'DT', str_replace('-', '', $leggo['datadoc']));
                $_rigo['campo05'] = campi('NE', '00' . $index, '005', 'AN', $leggo['ndoc']);
                $_rigo['campo06'] = campi('NE', '00' . $index, '006', 'NP', floor($importi['imponibile']));
                $_rigo['campo07'] = campi('NE', '00' . $index, '007', 'NP', floor($importi['imposta']));
            }

            $_TA006001 ++;

            $_parametri[$index] = $_rigo['campo01'] . $_rigo['campo02'] . $_rigo['campo03'] . $_rigo['campo04'] . $_rigo['campo05'] . $_rigo['campo06'] . $_rigo['campo07'] . $_rigo['campo08'] . $_rigo['campo09'] . $_rigo['campo10'] . $_rigo['campo11'] . $_rigo['campo12'];


            $_rigo = null;
            //chiudiamo il cilo di for se arrivato a 6 chiudendo la scrittura e azzerendo l'indice

            if ($index == "10")
            {

                scriviriga_D($_campo, $_parametri);
                $_parametri = null;
                $index = "0";
            }
        }

        scriviriga_D($_campo, $_parametri);
        $_parametri = null;
        $index = null;
        $_rigo = null;
        $_campo = null;




        //qui scriviamo il quadro NR

        $result = leggi_registrazioni("fornitori", 'FA', 'N', $_POST['anno'], $CONTO_IVA_ACQUISTI);

        //creiamo un ciclo di for da 1 a 10 per la scrittura delle righe campi..

        foreach ($result as $leggo)
        {

            $index ++;
            //leggiamo di chi è ?

            $chie = leggi_chie("fornitori", $leggo['anno'], $leggo['nreg']);

            //selezioniamo l'imponibile

            $importi = leggi_importi("fornitori", "avere", $CONTO_IVA_ACQUISTI, $leggo['iva'], $leggo['nreg'], $leggo['anno']);


            if ($index > 9)
            {
                $_rigo['campo01'] = campi('NR', '0' . $index, '001', 'PI', str_replace('IT', '', $chie['piva']));
                $_rigo['campo02'] = campi('NR', '0' . $index, '002', 'DT', str_replace('-', '', $leggo['datadoc']));
                $_rigo['campo03'] = campi('NR', '0' . $index, '003', 'DT', str_replace('-', '', $leggo['data_cont']));
                $_rigo['campo04'] = campi('NR', '0' . $index, '004', 'NP', floor($importi['imponibile']));
                $_rigo['campo05'] = campi('NR', '0' . $index, '005', 'NP', floor($importi['imposta']));
            }
            else
            {
                $_rigo['campo01'] = campi('NR', '00' . $index, '001', 'PI', str_replace('IT', '', $chie['piva']));
                $_rigo['campo02'] = campi('NR', '00' . $index, '002', 'DT', str_replace('-', '', $leggo['datadoc']));
                $_rigo['campo03'] = campi('NR', '00' . $index, '003', 'DT', str_replace('-', '', $leggo['data_cont']));
                $_rigo['campo04'] = campi('NR', '00' . $index, '004', 'NP', floor($importi['imponibile']));
                $_rigo['campo05'] = campi('NR', '00' . $index, '005', 'NP', floor($importi['imposta']));
            }

            $_TA007001 ++;

            $_parametri[$index] = $_rigo['campo01'] . $_rigo['campo02'] . $_rigo['campo03'] . $_rigo['campo04'] . $_rigo['campo05'] . $_rigo['campo06'] . $_rigo['campo07'] . $_rigo['campo08'] . $_rigo['campo09'] . $_rigo['campo10'] . $_rigo['campo11'] . $_rigo['campo12'];


            $_rigo = null;
            //chiudiamo il cilo di for se arrivato a 6 chiudendo la scrittura e azzerendo l'indice

            if ($index == "10")
            {

                scriviriga_D($_campo, $_parametri);
                $_parametri = null;
                $index = "0";
            }
        }

        scriviriga_D($_campo, $_parametri);
        $_parametri = null;
        $index = null;
        $_rigo = null;
        $_campo = null;




        ///// Sesione per righe tipo E

        $_moduloE ++;

        if ($_TA001001 != "")
        {
            $_scrivi_TA001001 = 'TA001001' . str_pad($_TA001001, 16, ' ', STR_PAD_LEFT);
        }

        if ($_TA004001 != "")
        {
            $_scrivi_TA004001 = 'TA004001' . str_pad($_TA004001, 16, ' ', STR_PAD_LEFT);
        }

        if ($_TA004002 != "")
        {
            $_scrivi_TA004002 = 'TA004002' . str_pad($_TA004002, 16, ' ', STR_PAD_LEFT);
        }

        if ($_TA005001 != "")
        {
            $_scrivi_TA005001 = 'TA005001' . str_pad($_TA005001, 16, ' ', STR_PAD_LEFT);
        }

        if ($_TA005002 != "")
        {
            $_scrivi_TA005002 = 'TA005002' . str_pad($_TA005002, 16, ' ', STR_PAD_LEFT);
        }

        if ($_TA006001 != "")
        {
            $_scrivi_TA006001 = 'TA006001' . str_pad($_TA006001, 16, ' ', STR_PAD_LEFT);
        }

        if ($_TA007001 != "")
        {
            $_scrivi_TA007001 = 'TA007001' . str_pad($_TA007001, 16, ' ', STR_PAD_LEFT);
        }


        //inizio prima parte del record..
        $_commento = sprintf("E%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s", str_pad($codfisc, 16, ' ', STR_PAD_RIGHT), str_pad($_moduloE, 8, '0', STR_PAD_LEFT), str_pad($_filler, 3, ' ', STR_PAD_LEFT), str_pad($_filler, 25, ' ', STR_PAD_LEFT), str_pad($_filler, 20, ' ', STR_PAD_LEFT), str_pad('GRGMSM77E21G224K', 16, ' ', STR_PAD_RIGHT), $_scrivi_TA001001, $_scrivi_TA002001, $_scrivi_TA003001, $_scrivi_TA003002, $_scrivi_TA003003, $_scrivi_TA004001, $_scrivi_TA004002, $_scrivi_TA005001, $_scrivi_TA005002, $_scrivi_TA006001, $_scrivi_TA007001, $_scrivi_TA008001, $_scrivi_TA009001, $_scrivi_TA0100001, $_scrivi_TA011001);

        $_commento = sprintf("%sA\r\n", str_pad($_commento, 1897, ' ', STR_PAD_RIGHT));
        //echo $_commento;
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");
        $_rigoE ++;





////sezione righe tipo Z

        $_commento = sprintf("Z%s%s%s%s%s%sA\r\n", str_pad($_filler, 14, ' ', STR_PAD_LEFT), str_pad($_rigoB, 9, '0', STR_PAD_LEFT), str_pad($_rigoC, 9, '0', STR_PAD_LEFT), str_pad($_rigoD, 9, '0', STR_PAD_LEFT), str_pad($_rigoE, 9, '0', STR_PAD_LEFT), str_pad($_filler, 1846, ' ', STR_PAD_LEFT));
        fwrite($fp, $_commento);
        if (!$fp)
            die("Errore.. Riga non inserita ?");


//chiudiamo il file..

        fclose($fp);



        echo "<center>";
        echo "<h2>Se non appaiono errori a video<br> la creazione del file è stata <br>eseguita con successo</h2>";
        //echo "<br>";
        echo "<h4> Totali.. Rigo B $_rigoB - Rigo C $_rigoC - Rigo D $_rigoD - Rigo E $_rigoE</h4>\n";
        echo "<h4> Totali.. Quadro FE $_TA004001 - FE comulativo $_TA004002 - FR $_TA005001 - FR comulativo $_TA005002 - NE $_TA006001 - NR $_TA007001</h4>\n";




        echo "<h3>Preleva il file NSP qui=> <a href=\"$nfile\"> Cliccando di destro Qui!</a></h3>";

        echo "</td></tr>\n";
        echo "</table></body></html>\n";
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
