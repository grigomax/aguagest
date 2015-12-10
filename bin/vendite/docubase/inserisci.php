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
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{

//recuperiamo le variabili ambiante
    $id = session_id();
    $_tdoc = $_SESSION['tdoc'];
    $_azione = $_POST['scrivi'];
    $_ndoc = $_POST['ndoc'];
    $_anno = $_POST['annodoc'];
    $_suffix = $_SESSION['suffix'];
    //selezioniamo il cliente
    $dati = $_SESSION['datiutente'];
    $_parametri = $_POST;

    $_datareg = $_POST['datareg'];

    //prendiamoci gli archivi
    $_archivio = archivio_tdoc($_tdoc);
    

    
    //facciamo una piccola verifica se i campi sono pieni o vuoti e se sono corretti..
    
    if(($_ndoc != "") AND ($_anno != "") AND ($_datareg != ""))
    {
        //controlliamo se il numero documento è un intero come anche l'anno
        
        if($_ndoc < "1")
        {
            echo "<br>";
            echo "<center><h3><font color=\"red\">ATTENZIONE.. !</h3>";
            echo "<center><h3><font color=\"red\">Numero documento non valido....</h3>";
            echo "<center><h3>Tornare indietro con il browser e controllare..</h3>";
            exit;
        }
        
        
    }
    else
    {
        
        echo "<br>";
        echo "<center><h3><font color=\"red\">ATTENZIONE.. !</h3>";
        echo "<center><h3><font color=\"red\">Si Dati Documento non validi....</h3>";
        echo "<center><h3>Tornare indietro con il browser e controllare..</h3>";
        exit;
    }
    
    /* Verifichiamo che esistino le sessioni prima di inserire un documento..
     * Così si evita che vada dentro un documento sbagliato o che si inserisca un documento vuoto..
     * 
     */

    if (($_SESSION['utente'] != "") OR ( $_azione == "Spedizione"))
    {

        intesta_html($_tdoc, "normale", $dati, "");


        if ($_azione == "Inserisci")
        {

                //Preparo la data americana per poterla inserire nell'archivio
            $_datareg = cambio_data("us", $_POST['datareg']);
            //verifichiamo la presenza già di un documento con lo stesso numero..
            //$_ndoc = disponibilita_ndoc($_cosa, $_tdoc, $_ndoc, $_anno);

            $_disponibilita = seleziona_documento("disponibilita_numero", $_tdoc, $_anno, $_suffix, $_ndoc, $_archivio, $_parametri);

            if ($_disponibilita['errori'] != "OK")
            {
                echo "errore bloccaggio numero<br>Il numero selezionato risulta già occupato..";
                exit;
            }
            else
            {
                //dentro qui ci inseriamo il documento..
                //il numero del documento che tocca è:
                $_ndoc = $_disponibilita['ndoc'];

                if($_POST['destinazione'] != "0")
                {
                    //selezioniamo l'archivio e sovrascriviamo il post passatp.
                    
                    $_destino = tabella_destinazioni("singola", $dati['codice'], $_POST['destinazione'], $_parametri);
                    $_POST['dragsoc'] = $_destino['dragsoc'];
                    $_POST['dragsoc2'] = $_destino['dragsoc2'];
                    $_POST['dindirizzo'] = $_destino['dindirizzo'];
                    $_POST['dcitta'] = $_destino['dcitta'];
                    $_POST['dcap'] = $_destino['dcap'];
                    $_POST['dprov'] = $_destino['dprov'];
                    $_POST['dnazione'] = $_destino['dnazione'];
                    $_POST['telefonodest'] = $_destino['telefonodest'];
                    
                }

                $_documento = scrivi_doc("inserisci", $id, $_tdoc, $dati, $_ndoc, $_anno, $_suffix, $_datareg, $_POST);


                $_result = tabella_doc_basket("azzera_sessione", $id, $_rigo, $_anno, $_ndoc, $_utente, $_articolo, $_parametri);


                if ($_result['errori'] != "OK")
                {
                    echo $_result['descrizione'];
                }
            }
        }


        if ($_azione == "Aggiorna")
        {//1
            
            //verifichiamo la destinazione
            if($_POST['destinazione'] != "0")
                {
                    //selezioniamo l'archivio e sovrascriviamo il post passatp.
                    
                    $_destino = tabella_destinazioni("singola", $dati['codice'], $_POST['destinazione'], $_parametri);
                    $_POST['dragsoc'] = $_destino['dragsoc'];
                    $_POST['dragsoc2'] = $_destino['dragsoc2'];
                    $_POST['dindirizzo'] = $_destino['dindirizzo'];
                    $_POST['dcitta'] = $_destino['dcitta'];
                    $_POST['dcap'] = $_destino['dcap'];
                    $_POST['dprov'] = $_destino['dprov'];
                    $_POST['dnazione'] = $_destino['dnazione'];
                    $_POST['telefonodest'] = $_destino['telefonodest'];
                    
                }
            
            
            
            
            $_documento = scrivi_doc("aggiorna", $id, $_tdoc, $dati, $_ndoc, $_anno, $_suffix, $_datareg, $_POST);


            $_result = tabella_doc_basket("azzera_sessione", $id, $_rigo, $_anno, $_ndoc, $_utente, $_articolo, $_parametri);


            if ($_result['errori'] != "OK")
            {
                echo $_result['descrizione'];
            }

            if ($_documento['errori']['errore'] != "")
            {
                echo "<h2>Errore nell'inserimento</h2>\n";
                echo $_documento['errori']['descrizione'];
            }
        }



        if ($_azione == "Spedizione")
        {
            $_documento = gestisci_testata("scrivi_spedizione", $_utente, $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_archivi, $_parametri);

            //$_documento = scrivi_spedizione("aggiorna", $id, $_tdoc, $_codutente, $dati, $_ndoc, $_anno, $_POST['datareg'], $_dragsoc, $_dragsoc2, $_dindirizzo, $_POST['dcap'], $_dcitta, $_POST['dprov'], $_POST['aspetto'], $_POST['colli'], $_POST['peso'], $_POST['vettore'], $_POST['porto'], $_memoart, strtoupper($_POST['id_collo']));


            if ($_documento['errori'] != "OK")
            {
                echo "<h2>Errore nell'inserimento</h2>\n";
                echo $_documento['errori']['descrizione'];
            }
        }



#Inizio parte visiva del documento...-------------------------------------------
        if ($_tdoc != "ddtacq")
        {
            #Richiamo le funzioni si di stampa;
            genera_maschera_stampe("../stampa_doc.php", "inserisci", $_documento);
            #genero la selezione dei prezzi
            print_prezzi($_tdoc);
            #faccio apparire i pulsanti per selezionare le lingue..
            seleziona_lingue();
            echo "<br><td align=\"center\"><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Stampa\"> &nbsp;<input type=\"submit\" name=\"azione\" value=\"Inoltra\">";
            echo "</td></tr></form></table>";
        }
        else
        {
            genera_maschera_stampe("../stampa_doc.php", "inserisci", $_documento);
        }


        echo "<br>";
        echo "<center><h3> Vuoi rimodificare subito questo documento ?</h3><br>";
        printf("<a href=\"visualizzadoc.php?tdoc=%s&ndoc=%s&anno=%s\">Clikka qui e vai subito!</a>", $_documento['tdoc'], $_documento['ndoc'], $_documento['anno']);
        echo "</center>";




        // elimino le sessioni usate
        chiudi_sessioni();
    }
    else
    {
        echo "<br>";
        echo "<center><h3><font color=\"red\">ATTENZIONE.. !</h3>";
        echo "<center><h3><font color=\"red\">Si st&agrave; cercando di modificare un documento gi&agrave; modificato od inserito..</h3>";
        echo "<center><h3>Non si pu&ograve; tornare indietro con il browser..</h3>";
        #echo "<center><h3>Non si pu&ograve; tornare indietro con il browser..</h3>";
        exit;
    }



    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>