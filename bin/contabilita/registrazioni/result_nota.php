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
$conn = permessi_sessione("verifica", $_percorso);
require "../../../setting/par_conta.inc.php";
require "../../librerie/motore_primanota.php";
require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{


    $id = session_id();

//prendiamo i post per sapere cosa fare..

    $_azione = $_POST['azione'];


//passiamo il parametro spesometro in quanto è uguale per tutti
//abilitiamo lo spesometro 2011
    if ($SPESOMETRO == "SI")
    {
        $_parametri['spesometro'] = $_POST['spesometro'];
    }

//cambiamo il tipo di causare in modo da impostare st per lo standard
//Creiamo la pagina html..

    echo "<center>\n";
    echo "<h2>Registrazione di prima nota</h2>\n";

//passo la variabile del post note alla funzione di controllo del testo pervia del problema della parole accentate ecc..
    $_note = mysql_real_escape_string($_POST['note']);


    if ($_azione == "Inserisci")
    {
        //ci prendiamo anche tutte le sessioni
        //Recupero le sessioni così da mostrarle..
        $_data_reg = $_SESSION['datareg'];
        $_data_gior = $_SESSION['datagior'];
        $_testo = $_SESSION['testo'];
        $_causale = $_SESSION['causale'];
        $_parametri['note'] = $_note;

        if ($_causale == "FA")
        {
            //verifichiamo che le variabili siano piene..
            if (($_POST['anno'] == "") OR ( $_POST['nreg'] == "") OR ( $_POST['nproto'] == "") OR ( $_POST['anno_proto'] == "") OR ( $_POST['ndoc'] == "") OR ( $_POST['anno_doc'] == "") OR ( $_POST['data_doc'] == "") OR ( $_POST['codpag'] == ""))
            {
                echo "<h2>Errore una delle variabili e vuota</h2>";

                exit;
            }
            //ottimo se piena modifichiamo anche il testo da scrivere;
            $_testo = $_testo . $_POST['ndoc'] . " / " . $_POST['anno_doc'] . " del " . $_POST['data_doc'] . " - " . $_SESSION['utente']['ragsoc'];
        }


        //Passo tutte la varialivi ad un array e poi lo passo alla funzione..

        $_anno = $_SESSION['anno'];
        $_nreg = $_POST['nreg'];
        if (($_causale == "PA") OR ( $_causale == "IN"))
        {
            $_parametri['codpag'] = $_SESSION['registrazione']['tipopag'];
            $_parametri['nproto'] = $_SESSION['registrazione']['nproto'];
            $_parametri['anno_proto'] = $_SESSION['registrazione']['anno_proto'];
            $_parametri['ndoc'] = $_SESSION['registrazione']['ndoc'];
            $_parametri['anno_doc'] = $_SESSION['registrazione']['anno_doc'];
            $_parametri['data_doc'] = $_SESSION['registrazione']['data_doc'];
            $_parametri['segno'] = "P";
        }
        else
        {
            $_parametri['codpag'] = $_POST['codpag'];
            $_parametri['nproto'] = $_POST['nproto'];
            $_parametri['anno_proto'] = $_POST['anno_proto'];
            $_parametri['ndoc'] = $_POST['ndoc'];
            $_parametri['anno_doc'] = $_POST['anno_doc'];
            $_parametri['banca'] = $_POST['banca'];
            $_parametri['segno'] = $_SESSION['parametri']['segno'];

            $_data_doc = cambio_data("us", $_POST['data_doc']);
            //lo passo all'array
            $_parametri['data_doc'] = $_data_doc;
        }


        //cambiamo la data prima dell'inserimento..'

        $_data_reg = cambio_data("us", $_data_reg);
        $_data_gior = cambio_data("us", $_data_gior);


        //passiamo tutto alla funzione di inserimento dati in prima nota..
        $_return = tabella_primanota("Inserisci", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);

        //verifico se ci sono stati erroi nell'inserimento del documento
        if ($_return['errori']['errore'] != "")
        {
            echo "<h2>Errore nell'inserimento</h2>\n";
            echo $_return[errori][descrizione];
        }
        else
        {
            echo "<h2 align=\"center\">Registrazione aggiornata con successo..</h2>";
            echo "<h3 align=\"center\">Nr. Registrazione <b>$_return[nreg]</b></h3>";
            echo "<h3 align=\"center\">Nr. protocollo <b>$_return[nproto]</b></h3>";

            if ($_causale == "FA")
            {

                echo "<center>Elenco scadenze\n";

                $_parametri['campo1'] = "nproto";
                $_parametri['campo2'] = "anno_proto";
                $_parametri['data_campo1'] = $_return['nproto'];
                $_parametri['data_campo2'] = $_parametri['anno_proto'];

                $res = tabella_scadenziario("elenco", $_percorso, $_parametri);

                echo "<table align=\"center\" border=\"1\" width=\"60%\">\n";
                echo "<tr><td colspan=\"3\">Vai direttamente alla scadenza </td></tr>\n";
                echo "<tr>\n";
                echo "<td>N. Scad</td><td>Data Scadenza</td><td>Importo</td></tr>\n";

                while ($dati = mysql_fetch_array($res))
                {
                    echo "<td><a href=\"../../scadenziario/scadenza.php?azione=visualizza&anno=$dati[anno]&nscad=$dati[nscad]\">$dati[nscad]<td>$dati[data_scad]</td><td>$dati[impeff]</td></tr>\n";
                }

                echo "</table>\n";
            }
            
            echo "<h3 align=\"center\"><a href=\"../ricerca_scheda.php\">Vai alle schede contabili</a></h3>";
            echo "<h3 align=\"center\"><a href=\"prima_nota.php?azione=ST\">Inserisci una nuova registrazione</a></h3>";
            echo "<h3 align=\"center\"><a href=\"prima_nota.php?azione=FA\">Inserisci una nuova Fattura Acquisto</a></h3>";

            //elenchiamo le scadenze appena inserite..
            //visto che è tutto ok, svuotiamo il carrello..

            carrello_primanota("svuota", $_anno, $_rigo, $_tipo_cf, $_codconto, $_dare, $_avere, $_segno, $_iva);
        }
    }
    elseif ($_azione == "Salda")
    {
        //ci prendiamo anche tutte le sessioni
        //Recupero le sessioni così da mostrarle..
        $_data_reg = $_SESSION['datareg'];
        $_data_gior = $_SESSION['datagior'];
        $_testo = $_SESSION['testo'];
        $_causale = $_SESSION['causale'];
        $_anno = $_SESSION['anno'];
        $_nreg = $_SESSION['nreg'];
        $_parametri['codpag'] = $_POST['codpag'];
        $_parametri['banca'] = $_POST['banca'];
        $_parametri['segno'] = $_POST['segno'];
        $_parametri['note'] = $_note;

        //cambiamo la data prima dell'inserimento..'
        //passiamo tutto alla funzione di inserimento dati in prima nota..
        $_return = tabella_primanota("salda", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);
        //verifico se ci sono stati erroi nell'inserimento del documento

        if ($_return['errori']['errore'] != "")
        {
            echo "<h2>Errore nell'inserimento</h2>\n";
            echo $_return[errori][descrizione];
        }
        else
        {
            echo "<h2 align=\"center\">Registrazione Saldata con successo..</h2>";
            echo "<h3 align=\"center\">Nr. Registrazione $_return[nreg]</h3>";

            
            echo "<h3 align=\"center\"><a href=\"../ricerca_scheda.php\">Vai alle schede contabili</a></h3>";
            echo "<h3 align=\"center\"><a href=\"prima_nota.php?azione=ST\">Inserisci una nuova registrazione</a></h3>";
            echo "<h3 align=\"center\"><a href=\"prima_nota.php?azione=FA\">Inserisci una nuova Fattura Acquisto</a></h3>";
        }
    }
    else
    {

        //ci prendiamo anche tutte le sessioni
        //Recupero le sessioni così da mostrarle..
        $_data_reg = $_SESSION['datareg'];
        $_data_gior = $_SESSION['datagior'];
        $_testo = $_SESSION['testo'];
        $_causale = $_SESSION['causale'];
        $_anno = $_SESSION['anno'];
        $_nreg = $_SESSION['nreg'];
        $_parametri['codpag'] = $_POST['codpag'];
        $_parametri['banca'] = $_POST['banca'];
        $_parametri['note'] = $_note;

        if ($_POST['segno'] != "")
        {
            $_parametri['segno'] = $_POST['segno'];
        }
        else
        {
            $_parametri['segno'] = $_SESSION['parametri']['segno'];
        }
        //cambiamo la data prima dell'inserimento..'
        //passiamo tutto alla funzione di inserimento dati in prima nota..
        $_return = tabella_primanota("Aggiorna", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_gior, $_parametri, $_percorso);
        //verifico se ci sono stati erroi nell'inserimento del documento

        if ($_return['errori']['errore'] != "")
        {
            echo "<h2>Errore nell'inserimento</h2>\n";
            echo $_return[errori][descrizione];
        }
        else
        {
            echo "<h2 align=\"center\">Registrazione Aggiornata con successo..</h2>";
            echo "<h3 align=\"center\">Nr. Registrazione $_return[nreg]</h3>";

            if ($_causale == "FA")
            {
                echo "<center>Elenco scadenze\n";

                $_parametri['campo1'] = "nproto";
                $_parametri['campo2'] = "anno_proto";
                $_parametri['data_campo1'] = $_SESSION['parametri']['nproto'];
                $_parametri['data_campo2'] = $_SESSION['parametri']['anno_proto'];
                $res = tabella_scadenziario("elenco", $_percorso, $_parametri);

                echo "<table align=\"center\" border=\"1\" width=\"60%\">\n";
                echo "<tr><td colspan=\"3\">Vai direttamente alla scadenza </td></tr>\n";
                echo "<tr>\n";
                echo "<td>N. Scad</td><td>Data Scadenza</td><td>Importo</td></tr>\n";

                while ($dati = mysql_fetch_array($res))
                {
                    echo "<td><a href=\"../../scadenziario/scadenza.php?azione=visualizza&anno=$dati[anno]&nscad=$dati[nscad]\">$dati[nscad]<td>$dati[data_scad]</td><td>$dati[impeff]</td></tr>\n";
                }

                echo "</table>\n";
            }


            echo "<h3 align=\"center\"><a href=\"../ricerca_scheda.php\">Vai alle schede contabili</a></h3>";
            echo "<h3 align=\"center\"><a href=\"prima_nota.php?azione=ST\">Inserisci una nuova registrazione</a></h3>";
            echo "<h3 align=\"center\"><a href=\"prima_nota.php?azione=FA\">Inserisci una nuova Fattura Acquisto</a></h3>";

            //visto che è tutto ok, svuotiamo il carrello..

            carrello_primanota("svuota", $_anno, $_rigo, $_tipo_cf, $_codconto, $_dare, $_avere, $_segno, $_iva);
        }
    }

//visto che è tutto ok eliminiamo le sessioni..
    elimina_sessioni();



    echo "</body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>