<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * http://aguagest.sourceforge.net/
 * massimo@mcetechnik.it
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);
require "../../librerie/motore_primanota.php";
require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_anagrafiche.php";


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{


    echo "<h2> Importazione Importazione distinte in contabilit&agrave; </h2> ";
    echo "<h4>Se l'effetto esiste gi&agrave; verr&agrave; segnalato ed rimarra in sopeso..</h4> ";


//questo programma importa le varie fatture / note credito in contabilità quindi
// andranno registrate in contabilità con la stessa data di emissione

    $_numero = $_POST['numero'];

// echo $_ndoc;
// ok per ogni articolo mi prendo tutti i dati dell'articolo
// echo $_codice;
    foreach ($_numero as $_annondoc)
    {
        $_anno = substr($_annondoc, "0", "4");
        $_ndistinta = substr($_annondoc, "4", "10");


//leggiamo le fatture e ma mano che le leggiamo le passiamo in contabilità..
        //Poi leggiamo anche tutte le varie aggiute come trasporto ecc..
        //leggo il database delle fatture..
        $query = "SELECT * FROM effetti INNER JOIN clienti ON effetti.codcli=clienti.codice where presenta= 'SI' AND datadist like '$_anno-%-%' AND ndistinta='$_ndistinta' order by numeff";

        //ora esequiamo la query..
        $res = mysql_query($query, $conn) or mysql_error();

        //ci prendiamo i dati..

        while ($dati = mysql_fetch_array($res))
        {


            //prima di tutto verifichiamo che la registrazione non esista già, altrimenti avvisiamo e saltiamo..
            // se il documento è esistente verifichiamo che sia ancora aperto.. ovvero sbilanciato..
            //
        // verifichiamo praticamente che per poter scrivere nella conabilità il documento sia aperto.. oppure lo apriamo..
            $_return = tabella_primanota("verifica_FV", $id, $dati['annodoc'], $dati['numdoc'], "IN", $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

            if ($_return['risposta'] == "esiste")
            {
                $_parametri['utente'] = $dati['codcli'];
                $_return2 = tabella_primanota("verifica_doc_aperto", $id, $dati['annodoc'], $dati['numdoc'], $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                if ($_return2['risposta'] == "vero")
                {
                    echo "<br>Verifica documento disparit&agrave; documento presente in prima nota<br>\n";
                    echo "La fattura nr $dati[numdoc]...... OK<br>\n";
                }
            }

            //echo $_return2['risposta'];

            if (($_return2['risposta'] != "") AND ($_return2['risposta'] == "falso"))
            {
                echo "<h3><font color=\"RED\">La fattura nr $dati[numdoc] presente in contabilit&agrave; risulta chiusa..</font></h3>\n";
                echo "<h3>Errore non bloccante, Effetto nr. $dati[numeff] e anno $_anno </h3>\n";
                echo "<h3>Risulta Gi&agrave; presente in prima nota </h3>\n";
                echo $_return['query'];
                echo "<br>\n";
                echo $_return2['query'];
                echo "<br>\n";
                echo "Per una differeza di euro = $_return2[diff]<br>\n";
            }
            else
            {

                //ora che abbiamo i dati effettuiamo le scritture in contabilità..
                //ci prendiamo il numero..

                $_nreg = tabella_primanota("ultimo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);
                //ottimo ora inseriamo l'intero importo della registrazione in dare al cliente a meno che non sia una nota credito mche va al contrario
                //prepariamo i dati standard..
                $_data_gior = $dati['datadist'];
                $_data_reg = $dati['datadist'];
                $_causale = "IN";
                $_parametri['ndoc'] = $dati['numdoc'];
                $_parametri['anno_doc'] = $dati['annodoc'];
                $_parametri['data_doc'] = $dati['datadoc'];
                $_parametri['codpag'] = $dati['modpag'];

                $_parametri['segno'] = "P";
                //giriamo la data..
                $_data_testo = cambio_data("it", $dati['scadeff']);
                $_testo = "Em. eff. n. " . $dati['numeff'] . " Fatt. $dati[numdoc] Scad. " . $_data_testo . " - " . $dati[ragsoc];
                //inseriamo i dati..
                //inseriamo tutto l'importo della fattura in dare..
                //Prepariamo i dati..
                $_parametri['conto'] = $MASTRO_CLI . $dati[codcli];
                $_parametri['desc_conto'] = $dati['ragsoc'];

                $_parametri['avere'] = $dati['impeff'];


                $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                if ($_result['errori']['errore'] == "errore")
                {
                    echo "<h2>errore nr 1 durante l'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                    exit;
                }

                //se buon fine azzeriamo le variabili
                $_parametri['conto'] = "";
                $_parametri['desc_conto'] = "";
                $_parametri['dare'] = "";
                $_parametri['avere'] = "";

                //ora inseriamo i conti per l'avere il netto merce con la contropartita segnalata sul conto del cliente

                $_parametri['conto'] = $CONTO_EFFETTI_SBF . $dati['bancadist'];
                //richiediamo la descrizione di questo conto...
                $_desc_conto = piano_conti($CONTO_EFFETTI_SBF . $dati['bancadist'], "singolo", $_percorso);
                $_parametri['desc_conto'] = $_desc_conto['descrizione'];
                //controllo se è una nota credito
                $_parametri['dare'] = $dati['impeff'];


                //inseriamo i dati
                $_result = tabella_primanota("Inserisci_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

                if ($_result['errori']['errore'] == "errore")
                {
                    echo "<h2>Errore nr. 2 durante l'inserimento della registrazione nr. $_nreg e documento nr. $_ndoc</h2>\n";
                    exit;
                }

                //ora registriamo l'iva..
                //se buon fine azzeriamo le variabili
                $_parametri['conto'] = "";
                $_parametri['desc_conto'] = "";
                $_parametri['dare'] = "";
                $_parametri['avere'] = "";
                $_parametri['conto'] = "";



                //visto che è tutto ok..
                //setto la fatura vendita a portata in contabilità..

                $query = "UPDATE effetti set contabilita = 'SI' WHERE annoeff='$dati[annoeff]' AND numeff='$dati[numeff]' LIMIT 1";

                mysql_query($query, $conn) or mysql_error();

                //Ora azzero le variabili usate..
                $_parametri = "";
                $_data_gior = "";
                $_data_reg = "";
                $_causale = "";
                $_testo = "";
                $_data_testo = "";

                echo "<br>Immesso numero in contabilita $_nreg per documento nr $dati[numdoc] e per effetto nr. $dati[numeff] ed distinta nr $_ndistinta\n";
                echo "<br>------------------------------------\n";
            }
            //cancelliamo l'eventuale errore per poter ripartire con il prossimo ciclo..
            $_return2 = "";
        }
    } // fine della selezione documenti. foreach
// INIZIO PARTE VISIVA DELLA GENERAZIONE..



    echo "<h3> Se non appaiono errori a video la importazione &egrave; stata eseguita con successo</h3>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>