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


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{
// mi prendo i paametri passati
//
////Prima di tutto eseguo un controllo sulla presenza di certi campi
//e il loro valore, devo verificare che la larghezza del corpo documento non sia superiore
//alla larghezza della pagina. se fosse così blocco il programma e faccio tornare indietro.
#azzeriamo la variabile..
    //verifichiamo che non sia una eticheta

    $_azione = $_POST['azione'];

    if ($_azione == "Inserisci")
    {
        //ed il nome del documento da prendere..
        $_tdoc = $_POST['tdoc'];
        
        $_tipo = substr($_tdoc, "0", "3");
        
        if (($_tipo == "eti") OR ( $_GET[eti] == "SI"))
        {
            //confermiamo che la etichetta è una etichetta

            $_eti = "SI";
        }
        
        if (($_tipo == "lis") OR ( $_GET[lis] == "SI"))
        {
            //confermiamo che la etichetta è una etichetta

            $_lis = "SI";
        }
        
    }
    else
    {
        $_tdoc = $_GET['tdoc'];
    }
   



    echo "<h2>Controllo larghezza corpo documento</h2>\n";
    $_spazio = "0";
    if ($_POST['ST_RIGA'] == "SI")
    {
        if ($_POST['ST_RIGA_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_RIGA_LC'];
        }
        else
        {
            echo "<h3>Attenzione RIGA unita e senza valore</h3>";
            exit;
        }
    }
    if ($_POST['ST_ARTICOLO'] == "SI")
    {
        if ($_POST['ST_ARTICOLO_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_ARTICOLO_LC'];
        }
        else
        {
            echo "<h3>Attenzione ARTICOLO unita e senza valore</h3>";
            exit;
        }
    }
    if ($_POST['ST_ARTFOR'] == "SI")
    {
        if ($_POST['ST_ARTFOR_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_ARTFOR_LC'];
        }
        else
        {
            echo "<h3>Attenzione ARTICOLO FORNITORE e senza valore</h3>";
            exit;
        }
    }
    if ($_POST['ST_DESCRIZIONE'] == "SI")
    {
        if ($_POST['ST_DESCRIZIONE_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_DESCRIZIONE_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna DESCRIZIONE e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_UNITA'] == "SI")
    {
        if ($_POST['ST_UNITA_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_UNITA_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna unita e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_QUANTITA'] == "SI")
    {
        if ($_POST['ST_QUANTITA_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_QUANTITA_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna quntita e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_QTAEVASA'] == "SI")
    {
        if ($_POST['ST_QTAEVASA_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_QTAEVASA_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna QTANTITA EVASA e senza valore</h3>";
            exit;
        }
    }


    if ($_POST['ST_QTAESTRATTA'] == "SI")
    {
        if ($_POST['ST_QTAESTRATTA_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_QTAESTRATTA_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna QUANTITA ESTRATTA e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_QTASALDO'] == "SI")
    {
        if ($_POST['ST_QTASALDO_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_QTASALDO_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna QUANTITA SALDO e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_LISTINO'] == "SI")
    {
        if ($_POST['ST_LISTINO_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_LISTINO_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna LISTINO e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_SCONTI'] == "SI")
    {
        if ($_POST['ST_SCONTI_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_SCONTI_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna SCONTI e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_NETTO'] == "SI")
    {
        if ($_POST['ST_NETTO_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_NETTO_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna NETTO e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_TOTRIGA'] == "SI")
    {
        if ($_POST['ST_TOTRIGA_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_TOTRIGA_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna TOTRIGA e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_CODIVA'] == "SI")
    {
        if ($_POST['ST_CODIVA_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_CODIVA_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna CODICE IVA e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_RSALDO'] == "SI")
    {
        if ($_POST['ST_RSALDO_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_RSALDO_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna RSALDO e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_PESO'] == "SI")
    {
        if ($_POST['ST_PESO_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_PESO_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna PESO e senza valore</h3>";
            exit;
        }
    }

    if ($_POST['ST_CONSEGNA'] == "SI")
    {
        if ($_POST['ST_CONSEGNA_LC'] != "")
        {
            $_spazio = $_spazio + $_POST['ST_CONSEGNA_LC'];
        }
        else
        {
            echo "<h3>Attenzione colonna CONSEGNA e senza valore</h3>";
            exit;
        }
    }


//ora che abbiamo la somma del corpo, verifichiamo che sia uguale a 100% altrimenti blocchiamo e facciamo tornare indietro

    if (($_spazio >= "200") AND ( $_eti != SI))
    {
        echo "<h3>Somma corpo documenti = $_spazio mm </h3>\n";
        echo "<h2> Errore la somma delle percentuali del corpo &egrave; diversa da 100%</h2>\n";
        echo "<h3>Si prega di tornare indetro e Verificare. </h3>\n";

        exit;
    }


//
//



    if ($_tdoc == "ddt")
    {
        $_TDOC = "bv_layout.php";
        $_NDOC = "D.D.T. DPR 476/96";
    }
    elseif ($_tdoc == "conferma")
    {
        $_TDOC = "co_layout.php";
        $_NDOC = "Conferma Ordine";
    }
    elseif ($_tdoc == "ordine")
    {
        $_TDOC = "oc_layout.php";
        $_NDOC = "Ordine Agente";
    }
    elseif ($_tdoc == "fattura")
    {
        $_TDOC = "fv_layout.php";
        $_NDOC = "FATTURA";
    }
    elseif ($_tdoc == "immediata")
    {
        $_TDOC = "fv_acco_layout.php";
        $_NDOC = "$nomedoc";
    }
    elseif ($_tdoc == "preventivo")
    {
        $_TDOC = "pv_layout.php";
        $_NDOC = "Preventivo";
    }
    elseif ($_tdoc == "inventario")
    {
        $_TDOC = "inventario.php";
        $_NDOC = "Inventario Magazzino";
    }
    elseif ($_tdoc == "rimanenze")
    {
        $_TDOC = "rimanenze.php";
        $_NDOC = "Rimanenze Magazzino";
    }
    elseif ($_tdoc == "fornitore")
    {
        $_TDOC = "of_layout.php";
        $_NDOC = "Ordine Fornitore";
    }
    else
    {
        // vuol dire che è una etichetta o un listino
        $_NDOC = $_POST['ST_NDOC'];
    }

    //aggiungo il discorso accenti ecc..	
    $_body = addslashes($_POST['BODY']);
    $_ST_AVVISO_LC = addslashes($_POST['ST_AVVISO_LC']);



    if ($_azione == "Inserisci")
    {
        $query = "INSERT INTO stampe_layout (tdoc, ST_NDOC, ST_LOGOG, ST_LOGOM, ST_LOGOP,
    ST_TLOGO, ST_FONTOLOGO, ST_FONTLOGOSIZE, ST_TIPOTESTATA, ST_SOTTOTESTATA, ST_FONTINTEST, ST_FONTINTESTSIZE, ST_TIPOCALCE,
    ST_FONTESTACALCE, ST_FONTESTASIZE, ST_FONTCORPO, ST_FONTCORPOSIZE, ST_RPP, ST_RIGA, ST_RIGA_LC, ST_ARTICOLO, ST_ARTICOLO_ALL, ST_ARTICOLO_CT, ST_ARTICOLO_LC,
    ST_ARTFOR, ST_ARTFOR_ALL, ST_ARTFOR_CT, ST_ARTFOR_LC, ST_DESCRIZIONE, ST_DESCRIZIONE_ALL, ST_DESCRIZIONE_CT, ST_DESCRIZIONE_LC, ST_UNITA, ST_UNITA_ALL, ST_UNITA_LC,
    ST_QUANTITA, ST_QUANTITA_ALL, ST_QUANTITA_CT, ST_QUANTITA_LC, ST_QTAEVASA, ST_QTAEVASA_ALL, ST_QTAEVASA_CT, ST_QTAEVASA_LC, ST_QTAESTRATTA, ST_QTAESTRATTA_ALL, ST_QTAESTRATTA_CT,
    ST_QTAESTRATTA_LC, ST_QTASALDO, ST_QTASALDO_ALL, ST_QTASALDO_CT, ST_QTASALDO_LC , ST_LISTINO, ST_LISTINO_ALL, ST_LISTINO_CT, ST_LISTINO_LC, ST_AVV_PN, ST_SCONTI, ST_SCONTI_ALL,
    ST_SCONTI_LC, ST_NETTO, ST_NETTO_ALL, ST_NETTO_CT, ST_NETTO_LC, ST_TOTRIGA, ST_TOTRIGA_ALL, ST_TOTRIGA_CT , ST_TOTRIGA_LC, ST_CODIVA, ST_CODIVA_ALL, ST_CODIVA_LC,
    ST_RSALDO, ST_RSALDO_ALL, ST_RSALDO_LC, ST_PESO, ST_PESO_ALL , ST_PESO_LC, ST_CONSEGNA, ST_CONSEGNA_ALL, ST_CONSEGNA_CT, ST_CONSEGNA_LC, ST_AVVISO, ST_AVVISO_ALL ,
    ST_AVVISO_LC, ST_PREZZI , ST_DATA, BODY, ST_INTERLINEA )
						
						VALUES
						( '$_tdoc',  '$_NDOC', '$_POST[ST_LOGOG]', '$_POST[ST_LOGOM]', '$_POST[ST_LOGOP]',
    '$_POST[ST_TLOGO]', '$_POST[ST_FONTLOGO]', '$_POST[ST_FONTLOGOSIZE]', '$_POST[ST_TIPOTESTATA]',
    '$_POST[ST_SOTTOTESTATA]', '$_POST[ST_FONTINTEST]',  '$_POST[ST_FONTINTESTSIZE]',  '$_POST[ST_TIPOCALCE]',
    '$_POST[ST_FONTESTACALCE]',  '$_POST[ST_FONTESTASIZE]',  '$_POST[ST_FONTCORPO]',
    '$_POST[ST_FONTCORPOSIZE]',  '$_POST[ST_RPP]', '$_POST[ST_RIGA]', '$_POST[ST_RIGA_LC]',
	'$_POST[ST_ARTICOLO]',  '$_POST[ST_ARTICOLO_ALL]',  '$_POST[ST_ARTICOLO_CT]', '$_POST[ST_ARTICOLO_LC]',
    '$_POST[ST_ARTFOR]',  '$_POST[ST_ARTFOR_ALL]',  '$_POST[ST_ARTFOR_CT]',  '$_POST[ST_ARTFOR_LC]',
     '$_POST[ST_DESCRIZIONE]',  '$_POST[ST_DESCRIZIONE_ALL]',  '$_POST[ST_DESCRIZIONE_CT]',
    '$_POST[ST_DESCRIZIONE_LC]',  '$_POST[ST_UNITA]',  '$_POST[ST_UNITA_ALL]',  '$_POST[ST_UNITA_LC]',
    '$_POST[ST_QUANTITA]',  '$_POST[ST_QUANTITA_ALL]',  '$_POST[ST_QUANTITA_CT]',  '$_POST[ST_QUANTITA_LC]',
    '$_POST[ST_QTAEVASA]',  '$_POST[ST_QTAEVASA_ALL]', '$_POST[ST_QTAEVASA_CT]',  '$_POST[ST_QTAEVASA_LC]',
     '$_POST[ST_QTAESTRATTA]',  '$_POST[ST_QTAESTRATTA_ALL]', '$_POST[ST_QTAESTRATTA_CT]',
    '$_POST[ST_QTAESTRATTA_LC]',  '$_POST[ST_QTASALDO]', '$_POST[ST_QTASALDO_ALL]', '$_POST[ST_QTASALDO_CT]',
     '$_POST[ST_QTASALDO_LC]',   '$_POST[ST_LISTINO]',  '$_POST[ST_LISTINO_ALL]', '$_POST[ST_LISTINO_CT]',
     '$_POST[ST_LISTINO_LC]',  '$_POST[ST_AVV_PN]',  '$_POST[ST_SCONTI]', '$_POST[ST_SCONTI_ALL]',
    '$_POST[ST_SCONTI_LC]',  '$_POST[ST_NETTO]', '$_POST[ST_NETTO_ALL]', '$_POST[ST_NETTO_CT]',
    '$_POST[ST_NETTO_LC]', '$_POST[ST_TOTRIGA]', '$_POST[ST_TOTRIGA_ALL]', '$_POST[ST_TOTRIGA_CT]',
    '$_POST[ST_TOTRIGA_LC]', '$_POST[ST_CODIVA]',  '$_POST[ST_CODIVA_ALL]', '$_POST[ST_CODIVA_LC]',
     '$_POST[ST_RSALDO]',  '$_POST[ST_RSALDO_ALL]', '$_POST[ST_RSALDO_LC]',  '$_POST[ST_PESO]',
    '$_POST[ST_PESO_ALL]', '$_POST[ST_PESO_LC]', '$_POST[ST_CONSEGNA]', '$_POST[ST_CONSEGNA_ALL]',
    '$_POST[ST_CONSEGNA_CT]', '$_POST[ST_CONSEGNA_LC]', '$_POST[ST_AVVISO]', '$_POST[ST_AVVISO_ALL]',
    '$_ST_AVVISO_LC',  '$_POST[ST_PREZZI]', '$_POST[ST_DATA]', '$_body', '$_POST[ST_INTERLINEA]')";
    }

    if ($_azione == "Aggiorna")
    {
// ora devo prendermi i post..
//inizio parte produttiva
// aggiorniamo i dati nel database..

        $query = "UPDATE stampe_layout SET ST_NDOC = '$_NDOC', ST_LOGOG = '$_POST[ST_LOGOG]', ST_LOGOM = '$_POST[ST_LOGOM]', ST_LOGOP = '$_POST[ST_LOGOP]',
    ST_TLOGO = '$_POST[ST_TLOGO]', ST_FONTOLOGO = '$_POST[ST_FONTLOGO]', ST_FONTLOGOSIZE = '$_POST[ST_FONTLOGOSIZE]', ST_TIPOTESTATA = '$_POST[ST_TIPOTESTATA]',
    ST_SOTTOTESTATA = '$_POST[ST_SOTTOTESTATA]', ST_FONTINTEST = '$_POST[ST_FONTINTEST]', ST_FONTINTESTSIZE = '$_POST[ST_FONTINTESTSIZE]', ST_TIPOCALCE = '$_POST[ST_TIPOCALCE]',
    ST_FONTESTACALCE = '$_POST[ST_FONTESTACALCE]', ST_FONTESTASIZE = '$_POST[ST_FONTESTASIZE]', ST_FONTCORPO = '$_POST[ST_FONTCORPO]',
    ST_FONTCORPOSIZE = '$_POST[ST_FONTCORPOSIZE]', ST_RPP = '$_POST[ST_RPP]', ST_RIGA = '$_POST[ST_RIGA]', ST_RIGA_LC = '$_POST[ST_RIGA_LC]',
    ST_ARTICOLO = '$_POST[ST_ARTICOLO]', ST_ARTICOLO_ALL = '$_POST[ST_ARTICOLO_ALL]', ST_ARTICOLO_CT = '$_POST[ST_ARTICOLO_CT]', ST_ARTICOLO_LC = '$_POST[ST_ARTICOLO_LC]',
    ST_ARTFOR = '$_POST[ST_ARTFOR]', ST_ARTFOR_ALL= '$_POST[ST_ARTFOR_ALL]', ST_ARTFOR_CT = '$_POST[ST_ARTFOR_CT]', ST_ARTFOR_LC = '$_POST[ST_ARTFOR_LC]',
    ST_DESCRIZIONE = '$_POST[ST_DESCRIZIONE]', ST_DESCRIZIONE_ALL = '$_POST[ST_DESCRIZIONE_ALL]', ST_DESCRIZIONE_CT = '$_POST[ST_DESCRIZIONE_CT]',
    ST_DESCRIZIONE_LC = '$_POST[ST_DESCRIZIONE_LC]', ST_UNITA = '$_POST[ST_UNITA]', ST_UNITA_ALL = '$_POST[ST_UNITA_ALL]', ST_UNITA_LC = '$_POST[ST_UNITA_LC]',
    ST_QUANTITA = '$_POST[ST_QUANTITA]', ST_QUANTITA_ALL = '$_POST[ST_QUANTITA_ALL]', ST_QUANTITA_CT = '$_POST[ST_QUANTITA_CT]', ST_QUANTITA_LC = '$_POST[ST_QUANTITA_LC]',
    ST_QTAEVASA = '$_POST[ST_QTAEVASA]', ST_QTAEVASA_ALL = '$_POST[ST_QTAEVASA_ALL]', ST_QTAEVASA_CT = '$_POST[ST_QTAEVASA_CT]', ST_QTAEVASA_LC = '$_POST[ST_QTAEVASA_LC]',
    ST_QTAESTRATTA = '$_POST[ST_QTAESTRATTA]', ST_QTAESTRATTA_ALL = '$_POST[ST_QTAESTRATTA_ALL]', ST_QTAESTRATTA_CT = '$_POST[ST_QTAESTRATTA_CT]',
    ST_QTAESTRATTA_LC = '$_POST[ST_QTAESTRATTA_LC]', ST_QTASALDO = '$_POST[ST_QTASALDO]', ST_QTASALDO_ALL = '$_POST[ST_QTASALDO_ALL]', ST_QTASALDO_CT = '$_POST[ST_QTASALDO_CT]',
    ST_QTASALDO_LC = '$_POST[ST_QTASALDO_LC]', ST_LISTINO = '$_POST[ST_LISTINO]', ST_LISTINO_ALL = '$_POST[ST_LISTINO_ALL]', ST_LISTINO_CT = '$_POST[ST_LISTINO_CT]',
    ST_LISTINO_LC = '$_POST[ST_LISTINO_LC]', ST_AVV_PN = '$_POST[ST_AVV_PN]', ST_SCONTI = '$_POST[ST_SCONTI]', ST_SCONTI_ALL = '$_POST[ST_SCONTI_ALL]',
    ST_SCONTI_LC = '$_POST[ST_SCONTI_LC]', ST_NETTO = '$_POST[ST_NETTO]', ST_NETTO_ALL = '$_POST[ST_NETTO_ALL]', ST_NETTO_CT = '$_POST[ST_NETTO_CT]',
    ST_NETTO_LC = '$_POST[ST_NETTO_LC]', ST_TOTRIGA = '$_POST[ST_TOTRIGA]', ST_TOTRIGA_ALL = '$_POST[ST_TOTRIGA_ALL]', ST_TOTRIGA_CT = '$_POST[ST_TOTRIGA_CT]',
    ST_TOTRIGA_LC = '$_POST[ST_TOTRIGA_LC]', ST_CODIVA = '$_POST[ST_CODIVA]', ST_CODIVA_ALL = '$_POST[ST_CODIVA_ALL]', ST_CODIVA_LC = '$_POST[ST_CODIVA_LC]',
    ST_RSALDO = '$_POST[ST_RSALDO]', ST_RSALDO_ALL = '$_POST[ST_RSALDO_ALL]', ST_RSALDO_LC = '$_POST[ST_RSALDO_LC]', ST_PESO = '$_POST[ST_PESO]',
    ST_PESO_ALL = '$_POST[ST_PESO_ALL]', ST_PESO_LC = '$_POST[ST_PESO_LC]', ST_CONSEGNA = '$_POST[ST_CONSEGNA]', ST_CONSEGNA_ALL = '$_POST[ST_CONSEGNA_ALL]',
    ST_CONSEGNA_CT = '$_POST[ST_CONSEGNA_CT]', ST_CONSEGNA_LC = '$_POST[ST_CONSEGNA_LC]', ST_AVVISO = '$_POST[ST_AVVISO]', ST_AVVISO_ALL = '$_POST[ST_AVVISO_ALL]',
    ST_AVVISO_LC = '$_ST_AVVISO_LC', ST_PREZZI = '$_POST[ST_PREZZI]', ST_DATA = '$_POST[ST_DATA]', BODY = '$_body' , ST_INTERLINEA='$_POST[ST_INTERLINEA]' WHERE tdoc = '$_tdoc' limit 1";

        //echo $sql;
    }

    if ($_azione == "Elimina")
    {
        $query = "DELETE FROM stampe_layout WHERE ndoc='$_todc' LIMIT 1";
    }

    $result = $conn->query($query);

    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "salva_layout.php";
        scrittura_errori($_cosa, $_percorso, $_errori);

        echo "Errore nell'aggiornamento punto inserimento $_tdoc  $db_nomedb";
        echo "<br> ecco la query <br>$query<br>\n";
        echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
        
        echo "</body></html>";
        $fine = 0;
        exit;
    }



    echo "<center>";
    echo "<h2>Se non appaiono errori a video<br> il file &egrave; stato <br>modificato con successo</h2>";
    echo "<br>";
   
    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>