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


require "../../librerie/motore_doc_pdo.php";
require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{

    //recupero le variabili
    $_tdoc = $_SESSION['tdoc'];
// funzione cancella articolo
    $_cosa = $_POST['azione'];
// prendo la fiunzione della muovimentaizone
    $_calce = $_SESSION['calce'];
//mi prendo la sessione..
    $id = session_id();
    $_utente = $_SESSION['utente'];
    $dati = $_SESSION['datiutente'];

//questa variabile mi indica che tipo di programma sto utilizzano ora..
    $_programma = $_SESSION['programma'];
#echo $_SESSION['calce'];
//mi verifico se è una evasione parziale che mi prondo in numero documento ed anno
    if (($_programma == "parziale_vendita") OR ( $_programma == "parziale_acquisto"))
    {
        $_anno = $_GET['anno'];
        $_ndoc = $_GET['ndoc'];
        $_tdoc = $_SESSION['datidoc']['start'];
    }

//vediamo se siamo in modifica..
    if ($_calce == "calce2")
    {
        //prendo il resto delle varibili
        $_anno = $_SESSION['anno'];
        $_suffix = $_SESSION['suffix'];
        $_ndoc = $_SESSION['ndoc'];
    }


    intesta_html($_tdoc, "", $dati, "");

// funzione che elimina la riga selezionata dal corpo
    if ($_cosa == "cancella")
    {

        $_POST['programma'] = $_programma;

        $result = tabella_doc_basket("delete_rigo", $id, $_POST['rigo'], $_anno, $_suffix, $_ndoc, $_utente, $_POST['articolo'], $_POST);


        if ($result['errori'] != "OK")
        {
            echo $result['descrtizione'];
        }
        else
        {
            $_messaggio = "Riga eliminata correttamente \n";
        }
    }

// funzione modifica articolo riga corpo
    if ($_cosa == "modifica")
    {

        $_rigo = $_POST['rigo'];

        //creiamo una tabellina ad hoc

        echo "<table border=\"0\" class=\"tabella\" align=\"center\" width=\"90%\">\n";
        //mi verifico se è una evasione parziale che mi prondo in numero documento ed anno
        if (($_programma == "parziale_vendita") OR ( $_programma == "parziale_acquisto"))
        {
            $_anno = $_GET['anno'];
            $_ndoc = $_GET['ndoc'];
            $_suffix = $_GET['suffix'];

            $query = "select * from doc_basket where sessionid='$id' and anno='$_anno' and suffix='$_suffix' and ndoc='$_ndoc' and rigo='$_rigo'";
            $result = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query $_cosa= $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }

            $dati_carr = $result->fetch(PDO::FETCH_ASSOC);
        }
        else
        {
            $dati_carr = tabella_doc_basket("leggi_singola", $id, $_rigo, $_anno, $_suffix, $_ndoc, $_utente, $_articolo, $_parametri);
        }


        //Per far apparire il peso dobbimo ridividerlo
        if (($dati_carr['peso'] != 0.000))
        {
            @$_pesoart = $dati_carr['peso'] / $dati_carr['qtasaldo'];
        }
        else
        {
            $_pesoart = $dati_carr['peso'];
        }


        schermata_quantita($_tdoc, "modifica", "Modifica", $dati_carr['rigo'], $dati_carr['articolo'], $dati_carr['artfor'], $dati_carr['descrizione'], $dati_carr['unita'], $dati_carr['quantita'], $dati_carr['listino'], $dati_carr['sca'], $dati_carr['scb'], $dati_carr['scc'], $dati_carr['nettovendita'], $_pesoart, $dati_carr['iva'], $dati_carr['consegna'], $dati_carr['qtaevasa'], $dati_carr['qtaestratta'], $dati_carr['qtasaldo'], $dati_carr['rsaldo'], $dati_carr['agg'], $_anno, $_suffix, $_ndoc);

        exit;
    }

    if ($_cosa == "aggiorna")
    {
        $_POST['programma'] = $_programma;

        $result = tabella_doc_basket("update", $id, $_POST['rigo'], $_anno, $_suffix, $_ndoc, $_utente, $_POST['articolo'], $_POST);


        if ($result['errori'] != "OK")
        {
            echo $result['descrizione'];
        }
        else
        {
            $_messaggio = "Riga Modificata correttamente";
        }
    }

    if ($_cosa == "inserisci")
    {

        $_POST['programma'] = $_programma;


        $result = tabella_doc_basket("inserisci", $id, $_POST['rigo'], $_anno, $_suffix, $_ndoc, $_utente, $_POST['articolo'], $_POST);


        if ($result['errori'] != "OK")
        {
            echo $result['descrizione'];
        }
        else
        {
            $_messaggio = "Riga inserita correttamente";
        }

        //verifichiamo che non si siano aggregate 
        if ($_POST['aggancia'] != "")
        {
            //leggiamo il codice aggregato..

            $_articolo = tabella_articoli("singola", $_POST['aggancia'], $_parametri);

            $_descrizione = $_articolo['descrizione'];
            //includo la funzione che mi preleva i prezzi di vendita
            // Da qui prelevo i vari plugins personalizzadi dei clienti..
            //aggiungiamo il discorso esma;
            $_esco = $_articolo['esco'];

            $_prezzi = prezzi_cliente($_cosa, $dati['codice'], $_POST['aggancia'], $dati['listino'], $dati);

            if ($_prezzi['descrizione'] != "")
            {
                $_descrizione = $_prezzi['descrizione'];
            }

            $_catmer = $_articolo['catmer'];
            $_codutente = $_utente;
            $_listino = $_prezzi['listino'];
            $_messaggio = $_prezzi['messaggio'];
            $_sca = $_prezzi['sca'];
            $_scb = $_prezzi['scb'];
            $_scc = $_prezzi['scc'];

            #echo "sconto uno $_sca<br>\n";
            #echo "sconto due $_scb<br>\n";
            #echo "sconto tre $_scc<br>\n";

            if (file_exists("../../../plugins/altri_campi_clienti.dir/altricampi.inc"))
            {

                $_cosa_vari = "singola";
                //$_caricata = "0";
                include("../../../plugins/altri_campi_clienti.dir/altricampi.inc");
            }

            //sostituiamo gli arre..!

            $_POST['descrizione'] = $_descrizione;
            $_POST['unita'] = $_articolo['unita'];
            $_POST['listino'] = $_listino;

            if (($_POST['sca'] OR $_POST['scb'] OR $_POST['scc']) == (("0") OR ( "")))
            {
                $_POST['sca'] = $_sca;
                $_POST['scb'] = $_scb;
                $_POST['scc'] = $_scc;
            }



            //e poi lo inseriamo
            $result = tabella_doc_basket("inserisci", $id, $_POST['rigo'], $_anno, $_suffix, $_ndoc, $_utente, $_POST['aggancia'], $_POST);


            if ($result['errori'] != "OK")
            {
                echo $result['descrtizione'];
            }
            else
            {
                $_messaggio = "Riga inserita correttamente";
            }
        }

        //aggancia_2
        //verifichiamo che non si siano aggregate 
        if ($_POST['aggancia_2'] != "")
        {
            //leggiamo il codice aggregato..

            $_articolo = tabella_articoli("singola", $_POST['aggancia_2'], $_parametri);

            $_descrizione = $_articolo['descrizione'];
            //includo la funzione che mi preleva i prezzi di vendita
            // Da qui prelevo i vari plugins personalizzadi dei clienti..
            //aggiungiamo il discorso esma;
            $_esco = $_articolo['esco'];

            $_prezzi = prezzi_cliente($_cosa, $dati['codice'], $_POST['aggancia_2'], $dati['listino'], $dati);

            if ($_prezzi['descrizione'] != "")
            {
                $_descrizione = $_prezzi['descrizione'];
            }

            $_catmer = $_articolo['catmer'];
            $_codutente = $_utente;
            $_listino = $_prezzi['listino'];
            $_messaggio = $_prezzi['messaggio'];
            $_sca = $_prezzi['sca'];
            $_scb = $_prezzi['scb'];
            $_scc = $_prezzi['scc'];


            if (file_exists("../../../plugins/altri_campi_clienti.dir/altricampi.inc"))
            {

                $_cosa_vari = "singola";
                //$_caricata = "0";
                include("../../../plugins/altri_campi_clienti.dir/altricampi.inc");
            }

            //sostituiamo gli arre..!

            $_POST['descrizione'] = $_descrizione;
            $_POST['unita'] = $_articolo['unita'];
            $_POST['listino'] = $_listino;

            if (($_POST['sca'] OR $_POST['scb'] OR $_POST['scc']) == (("0") OR ( "")))
            {
                $_POST['sca'] = $_sca;
                $_POST['scb'] = $_scb;
                $_POST['scc'] = $_scc;
            }

            //e poi lo inseriamo
            $result = tabella_doc_basket("inserisci", $id, $_POST['rigo'], $_anno, $_suffix, $_ndoc, $_utente, $_POST['aggancia_2'], $_POST);


            if ($result['errori'] != "OK")
            {
                echo $result['descrtizione'];
            }
            else
            {
                $_messaggio = "Riga inserita correttamente";
            }
        }

        //verifichiamo che non si siano aggregate 
        if ($_POST['aggancia_3'] != "")
        {
            //leggiamo il codice aggregato..

            $_articolo = tabella_articoli("singola", $_POST['aggancia_3'], $_parametri);

            $_descrizione = $_articolo['descrizione'];
            //includo la funzione che mi preleva i prezzi di vendita
            // Da qui prelevo i vari plugins personalizzadi dei clienti..
            //aggiungiamo il discorso esma;
            $_esco = $_articolo['esco'];

            $_prezzi = prezzi_cliente($_cosa, $dati['codice'], $_POST['aggancia_3'], $dati['listino'], $dati);

            if ($_prezzi['descrizione'] != "")
            {
                $_descrizione = $_prezzi['descrizione'];
            }

            $_catmer = $_articolo['catmer'];
            $_codutente = $_utente;
            $_listino = $_prezzi['listino'];
            $_messaggio = $_prezzi['messaggio'];
            $_sca = $_prezzi['sca'];
            $_scb = $_prezzi['scb'];
            $_scc = $_prezzi['scc'];


            if (file_exists("../../../plugins/altri_campi_clienti.dir/altricampi.inc"))
            {

                $_cosa_vari = "singola";
                //$_caricata = "0";
                include("../../../plugins/altri_campi_clienti.dir/altricampi.inc");
            }

            //sostituiamo gli arre..!

            $_POST['descrizione'] = $_descrizione;
            $_POST['unita'] = $_articolo['unita'];
            $_POST['listino'] = $_listino;
            if (($_POST['sca'] OR $_POST['scb'] OR $_POST['scc']) == (("0") OR ( "")))
            {
                $_POST['sca'] = $_sca;
                $_POST['scb'] = $_scb;
                $_POST['scc'] = $_scc;
            }

            //e poi lo inseriamo
            $result = tabella_doc_basket("inserisci", $id, $_POST['rigo'], $_anno, $_suffix, $_ndoc, $_utente, $_POST['aggancia_3'], $_POST);


            if ($result['errori'] != "OK")
            {
                echo $result['descrtizione'];
            }
            else
            {
                $_messaggio = "Riga inserita correttamente";
            }
        }
    }


    if ($_cosa == "cottimo")
    {
        //qui praticamente se l fattura è una fattura differita do la possibilità di
        //cancellare le quantità ed il valore dal corpo e di inserire una voce in calce
        //alla fattura con la somma del deocumento
        if ($_tdoc == "FATTURA")
        {
            if (cottimo($_programma, $id, $dati))
            {
                // Se ci sono errori la funzione pensa a stamparmi il codice d'errore...
                exit(0);
            }
        }
        else
        {
            echo "Questo documento non &egrave; una fattura differita..";
        }
    }

    echo "</table><br>";

//richiamo la funzione che mi mostra compilato il carrello
    mostra_carrello($_programma, $id, $_tdoc, $_calce, $IVAMULTI, $ivasis);

//INSERIAMO QUI L'ANNULLO DEL DOCUMENTO..

    annulla_doc_vendite($_programma, $_tdoc, $_anno, $_suffix, $_ndoc);



    echo "<br></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>