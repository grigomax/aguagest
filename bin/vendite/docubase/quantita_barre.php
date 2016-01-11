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
require "../../librerie/motore_doc_pdo.php";
//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

//recupero le sessioni:
//recupero le session

    $_codutente = $_SESSION['utente'];
    $dati = $_SESSION['datiutente'];
    $_tdoc = $_SESSION['tdoc'];
    $_dove = $_SESSION['programma'];
    if ($_calce == "calce2")
    {
        $_suffix = $_SESSION['suffix'];
    }
    $id = session_id();

// prendo la fiunzione della muovimentaizone
    $_calce = $_SESSION['calce'];

// veirfico che tipo di inserimento voglio se manuale o automatico
    $_azione = $_POST['azione'];

//vedo se c'è bisogno di una riga vuota.

    $_cosa = $_GET['cosa'];

    echo "<html><body>";
//VISULIZZO LA PAGINA:

    intesta_html($_tdoc, "", $dati, "");

    $_listinocli = $dati['listino'];
    $_scontocli = $dati['scontocli'];
    $_scontocli2 = $dati['scontocli2'];
    $_scontocli3 = $dati['scontocli3'];
    $_ivacli = $dati['iva'];



//controlliamo che il file rispetti le dimensioni impostate
    if ($_FILES["file"]["size"] < 1024000)
    {
//controlliamo se ci sono stati errori durante l'upload
        if ($_FILES["file"]["error"] > 0)
        {
            echo "Codice Errore: " . $_FILES["file"]["error"] . "";
        }
        else
        {
            //stampo alcune informazioni sul file
            //il nome originale
            echo "Nome File: " . $_FILES["file"]["name"] . "<br>";
            //il mime-type
//	echo "Tipo File: " . $_FILES["file"]["type"] . "<br>";
            //la dimensione in byte
            echo "Dimensione [byte]: " . $_FILES["file"]["size"] . "<br>";
            //il nome del file temporaneo
//	echo "Nome Temporaneo: " . $_FILES["file"]["tmp_name"] . "<br>";
            //controllo se il file esiste già sul server
            //sposto il file caricato dalla cartella temporanea alla destinazione finale
            move_uploaded_file($_FILES["file"]["tmp_name"], "../../../spool/" . $_FILES["file"]["name"]);
//	echo "File caricato in: " . "../../../upload/" . $_FILES["file"]["name"];
        }
    }
    else
    {
        echo "File troppo grande!!";
    }

// ora iniziamo ad elaborarlo..

    if (file_exists("../../../spool/" . $NOME_FILECODBAR))
    {
        echo "FILE TROVATO<BR>";
        $news = fopen("../../../spool/$NOME_FILECODBAR", "r"); //apre il file
        $_a = 0;
        while (!feof($news))
        {
            $buffer = fgets($news, 4096);
            $_a = $_a + 1;
            //    echo "Riga n. $_a valore = $buffer <BR>"; //riga letta
            $_arre[$_a] = $buffer;
        }




        //echo "riga A = $_a <br>";
        //nuova riga
        //escludiamo le righe finali da prendere
        $_a = $_a - $RIGHE_FILECODBAR;
        //echo "nuova riga A = $_a <br>";
        //for ($mul = 1; $mul <= 10; ++$mul) {
        for ($_b = 1; $_b <= $_a; $_b++)
        {
            //la stringa da dividere
            $stringa = $_arre[$_b];
            //il separatore
            $separatore = "|";
            //explode
            $suddivisa = explode($separatore, $stringa);
            //vediamo il contenuto di $suddivisa
            //echo "Codice " . $suddivisa[0] . " ";//casa
            //echo "quantita " . $suddivisa[1] . "<br>";//albergo

            $_qta = $suddivisa['1'];
            $_consegna = $suddivisa['2'];

            // cerco l'articolo nel database
            //prima in quello dei codici a barre..
            $_articolo = tabella_barcode("singola", $suddivisa['0'], $_articolo, $_rigo);

            $dati2 = tabella_articoli("singola", $_articolo, $_parametri);


            // mik prendo i dati dell'articolo
            $_esco = $dati2['esco'];
            $_articolo = $dati2['articolo'];
            $_descrizione = $dati2['descrizione'];
            $_unita = $dati2['unita'];
            $_ivart = $dati2['iva'];
            $_catmer = $dati2['catmer'];
            $_img = $dati2['immagine'];
            $_pesoart = $dati2['pesoart'];
            $_fornitore = $dati2['fornitore'];
            $_fornitore2 = $dati2['fornitore2'];
            $_fornitore_3 = $dati2['fornitore_3'];

            if ($_codutente == $_fornitore)
            {
                $_artfor = $dati2['artfor'];
                $_listino = $dati2['prelisacq'];
                $_nettoa = $dati2['preacqnetto'];
                $_sca = $dati2['scaa'];
                $_scb = $dati2['scab'];
                $_scc = $dati2['scac'];
                $_netto = $dati2['preacqnetto'];
                $_qtaminord = $dati2['qtaminord'];
            }

            if ($_codutente == $_fornitore2)
            {
                $_artfor = $dati2['artfor2'];
                $_listino = $dati2['prelisacq_2'];
                $_nettoa = $dati2['preacqnetto2'];
                $_sca = $dati2['scaa_2'];
                $_scb = $dati2['scab_2'];
                $_scc = $dati2['scac_2'];
                $_netto = $dati2['preacqnetto2'];
                $_qtaminord = $dati2['qtaminord'];
            }

            if ($_codutente == $_fornitore_3)
            {
                $_artfor = $dati2['artfor_3'];
                $_listino = $dati2['prelisacq_3'];
                $_nettoa = $dati2['preacqnetto_3'];
                $_sca = $dati2['scaa_3'];
                $_scb = $dati2['scab_3'];
                $_scc = $dati2['scac_3'];
                $_netto = $dati2['preacqnetto_3'];
                $_qtaminord = $dati2['qtaminord'];
            }


            if (($_dove == "ACQUISTO") OR ( $_dove == "DDT_ACQ"))
            {
                if ($_qta <= "1")
                {
                    $_qta = $_qtaminord;
                }
            }
            else
            {

                if ($_ivacli != "")
                {
                    // CERCO L'ALIQUOTA IVA CORRISPONDENTE AL CODICE CLIENTE
                    $dativa = tabella_aliquota("singola", $_ivacli, $_percorso);

                    // imposto la nuova aliquota sugli articoli
                    $_ivart = $dativa['aliquota'];
                    $_ivadesc = $dativa['descrizione'];
                }
                else
                {
                    // CERO L'ALIQUOTA IVA CORRISPONDENTE ALL'articolo
                    $dativa = tabella_aliquota("singola", $_ivart, $_percorso);
                    // imposto la nuova aliquota sugli articoli
                    $_ivart = $dativa['aliquota'];
                }// fine gestione aliquote iva
                //includo la funzione che mi preleva i prezzi di vendita
                // Da qui prelevo i vari plugins personalizzadi dei clienti..
                //includo la funzione che mi preleva i prezzi di vendita
                // Da qui prelevo i vari plugins personalizzadi dei clienti..
                //aggiungiamo il discorso esma;
                $dati['esma'] = $_esco;
                $_prezzi = prezzi_cliente($_cosa, $dati['codice'], $_articolo, $dati['listino'], $dati);

                if ($_prezzi['descrizione'] != "")
                {
                    $_descrizione = $_prezzi['descrizione'];
                }


                $_listino = $_prezzi['listino'];
                $_messaggio = $_prezzi['messaggio'];
                $_sca = $_prezzi['sca'];
                $_scb = $_prezzi['scb'];
                $_scc = $_prezzi['scc'];

                if (file_exists("../../../plugins/altri_campi_clienti.dir/altricampi.inc"))
                {
                    $_cosa_vari = "singola";
                    include("../../../plugins/altri_campi_clienti.dir/altricampi.inc");
                }
            }

            $_peso = $_pesoart * $_qta;




            //funzione che mi inserisce il codice all'interno del carrello

            $_parametri = "";
            $_parametri['programma'] = $_SESSION['programma'];
            $_parametri['artfor'] = $_artfor;
            $_parametri['descrizione'] = $_descrizione;
            $_parametri['unita'] = $_unita;
            $_parametri['qta'] = $_qta;
            $_parametri['listino'] = $_listino;
            $_parametri['sca'] = $_sca;
            $_parametri['scb'] = $_scb;
            $_parametri['scc'] = $_scc;
            $_parametri['iva'] = $_ivart;
            $_parametri['consegna'] = $_consegna;

            tabella_doc_basket("inserisci", $id, $_rigo, $_anno, $_suffix, $_ndoc, $_codutente, $_articolo, $_parametri);

            $_parametri = "";
        }// fine for

        fclose($news); #chiude il file
//elenco il carrello:
        mostra_carrello($_SESSION['programma'], $id, $_tdoc, $_calce, $IVAMULTI, $ivasis);
    }
    else
    {
        echo "non trovo il file";
    }

    echo "</table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
