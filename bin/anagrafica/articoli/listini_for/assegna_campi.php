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

//inizio parte visiva..

base_html($_cosa, $_percorso);

java_script($_cosa, $_percorso);

tiny_mce($_cosa, $_percorso);

echo "</head>\n";
echo "<body>\n";

testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

echo "<font size=\"2\"> \n";

//creiamo una funzione con all'interno le colonne articolo interessae dalla nostra selezione
function colonna_articolo($_form)
{
    global $conn;
    //creiamo una select per far apparire le colonne
    //mi restituisce l'arre singolo
    echo "<select name=\"$_form\">\n";
    echo "<option value=\"\"></option>\n";
    echo "<option value=\"articolo\">Codice articolo</option>\n";
    echo "<option value=\"descrizione\">Descrizione</option>\n";
    echo "<option value=\"codbar\">Codice a Barre</option>\n";
    echo "<option value=\"desrid\">Descrizione Ridotta</option>\n";
    echo "<option value=\"unita\">Unità di misura</option>\n";
    echo "<option value=\"iva\">Iva</option>\n";
    echo "<option value=\"catmer\">Categoria Merceologica</option>\n";
    echo "<option value=\"tipart\">Tipologia articolo</option>\n";
    echo "<option value=\"artfor\">Articolo Fornitore</option>\n";
    echo "<option value=\"codfor\">Codice Fornitore</option>\n";
    echo "<option value=\"prelisacq\">Prezzo listino fornitore</option>\n";
    echo "<option value=\"scaa\">Sconto A</option>\n";
    echo "<option value=\"scab\">Sconto B</option>\n";
    echo "<option value=\"scac\">Sconto C</option>\n";
    echo "<option value=\"preacqnetto\">Prezzo acquisto netto</option>\n";
    echo "<option value=\"qtaminord\">Qta minima ordinabile</option>\n";
    echo "<option value=\"pesoart\">Peso articolo</option>\n";
    echo "<option value=\"sitoart\">Sito internet articolo</option>\n";
    echo "<option value=\"immagine\">Immagine articolo</option>\n";
    echo "<option value=\"memoart\">Note Articolo</option>\n";
    echo "<option value=\"provvart\">Provvigioni articolo</option>\n";
    echo "<option value=\"descsito\">Descrizione sito</option>\n";
    echo "<option value=\"artcorr\">Articolo Correlato</option>\n";
    echo "<option value=\"art_alternativo\">Articolo Alternativo</option>\n";
    echo "<option value=\"data_var\">Data validita</option>\n";
    echo "<option value=\"lead_time\">Tempi di consegna</option>\n";


    echo "</select>\n";
}

if ($_SESSION['user']['anagrafiche'] > "3")
{
    echo "<table border=\"0\" width=\"100%\" align=\"left\" valign=\"TOP\">\n";
    echo "<tr><td valign=\"top\">\n";

    echo "<h3 align=\"center\">Associazione campi e formule</h3>\n";
    echo "<center>\n";

    //controlliamo che il file rispetti le dimensioni impostate
    if ($_FILES["file"]["size"] < 2048000)
    {
        //controlliamo se ci sono stati errori durante l'upload
        if ($_FILES["file"]["error"] > 0)
        {
            echo "Codice Errore: " . $_FILES["file"]["error"] . "";
            echo "Si prega di virificare e correggere";

            //scriviamo l'errore
            $_errori['descrizione'] = $_FILES["file"]["error"];
            $_errori['files'] = "assegna_campi.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            exit;
        }
        else
        {
            //stampo alcune informazioni sul file
            //il nome originale
            echo "Nome File: " . $_FILES["file"]["name"];
            //il mime-type
            //	echo "Tipo File: " . $_FILES["file"]["type"] . "<br>";
            //la dimensione in byte
            echo "  Dimensione [byte]: " . $_FILES["file"]["size"] . "<br>";
            //il nome del file temporaneo
            //	echo "Nome Temporaneo: " . $_FILES["file"]["tmp_name"] . "<br>";
            //controllo se il file esiste già sul server
            //sposto il file caricato dalla cartella temporanea alla destinazione finale

            if (($_FILES["file"]["type"] != "text/plain") AND ( $_FILES["file"]["type"] != "text/comma-separated-values") AND ( $_FILES["file"]["type"] != "application/vnd.oasis.opendocument.spreadsheet"))
                {
                echo "<h3> " . $_FILES["file"]["type"] . "</h3>";
                echo "<h3>Il file caricato non è conforme alle richieste tipo file errato</h3>";
                echo "<h3>Errore Registrato</h3>";

                //scriviamo l'errore
                $_errori['descrizione'] = "File non conforme  " . $_FILES["file"]["name"] . "  " . $_FILES["file"]["type"];
                $_errori['files'] = "assegna_campi.php";
                scrittura_errori($_cosa, $_percorso, $_errori);

                exit;
            }
            else
            {


                move_uploaded_file($_FILES["file"]["tmp_name"], $_percorso . "../spool/" . $_FILES["file"]["name"]);
                //	echo "File caricato in: " . "../../../upload/" . $_FILES["file"]["name"];
                //verifichiamo se il file è del tipo che vogliamo..
            }
        }
    }
    else
    {
        echo "<h3>File troppo grande!!</h3>";
        echo "<h3>Errore Registrato</h3>";

        //scriviamo l'errore
        $_errori['descrizione'] = "Il file caricato è troppo grande" . $_FILES["file"]["name"];
        $_errori['files'] = "assegna_campi.php";
        scrittura_errori($_cosa, $_percorso, $_errori);

        exit;
    }

    echo "<form action=\"elabora_listino.php\" method=\"POST\">\n";
    echo "</center>\n";

//ora che è tutto ok procediamo con l'elaborazione..
    //leggiamo la prima riga e la esponiamo per poter far scegliere il tipo di sistemazione dei campi e la seguente elaborazione;
    if (file_exists($_percorso . "../spool/" . $_FILES["file"]["name"]))
    {
        $separatore = $_POST['separatore'];

        if ($separatore == "METEL")
        {
            $_tipolistino = substr($_FILES["file"]["name"], 3, 3);
            //echo $_tipolistino;

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

                $news = fopen($_percorso . "../spool/" . $_FILES["file"]["name"] . "", "r"); //apre il file
                $buffer = fgets($news, 4096);

                echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"1\">\n";
                echo "<tr>\n";
                echo "<td>\n";
                echo "<h4>Tipo Tracciato= " . substr($buffer, 0, 20) . "</h4>\n";
                echo "Versione = " . substr($buffer, 20, 3) . "\n";

                //assegnamo i campi..
                echo "</td></tr>\n";
                $buffer = fgets($news, 4096);
                echo "<tr>\n";
                echo "<td><h4>Esempio prima Riga</h4>\n";
                echo "Marchio= " . substr($buffer, 0, 3) . "<br>\n";
                echo "Marca= " . substr($buffer, 3, 3) . "<br>\n";
                echo "Codice Famiglia= " . substr($buffer, 6, 18) . "<br>\n";
                echo "Descrizione =" . substr($buffer, 24, 70) . "<br>\n";


                echo "</td></tr></table>\n";
            }
            elseif ($_tipolistino == "BAR")
            {
                echo "barcode";
            }
            else
            {
                $news = fopen($_percorso . "../spool/" . $_FILES["file"]["name"] . "", "r"); //apre il file
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
                $buffer = fgets($news, 4096);
                echo "<tr>\n";
                echo "<td><h4>Esempio prima Riga</h4>\n";
                echo "Marchio= " . substr($buffer, 0, 3) . "<br>\n";
                echo "Cod. articolo fornitore= " . substr($buffer, 3, 16) . "<br>\n";
                echo "Codice Ean= " . substr($buffer, 19, 13) . "<br>\n";
                echo "Descrizione =" . substr($buffer, 32, 43) . "<br>\n";
                echo "qta cartone =" . substr($buffer, 75, 5) . "<br>\n";
                echo "qta multi =" . substr($buffer, 80, 5) . "<br>\n";
                echo "qta minord =" . substr($buffer, 85, 5) . "<br>\n";
                echo "qta maxord =" . substr($buffer, 90, 6) . "<br>\n";
                echo "tempo= " . substr($buffer, 96, 1) . "<br>\n";
                echo "prezzo uno= " . substr($buffer, 97, 11) . "<br>\n";
                $decimale = substr($buffer, 106, 2);
                echo $decimale;
                $valore = substr($buffer, 97, 9);
                echo $valore;
                $_prezzo = "$valore.$decimale";
                echo "<br>prezzo formattato=" . number_format($_prezzo, 2, '.', '');
                echo "<br>prezzo due =" . substr($buffer, 108, 11) . "<br>\n";
                echo "moltiplicatore =" . substr($buffer, 119, 6) . "<br>\n";
                echo "valuta=" . substr($buffer, 125, 3) . "<br>\n";
                echo "unita =" . substr($buffer, 128, 3) . "<br>\n";
                echo "prod. comp.= " . substr($buffer, 131, 1) . "<br>\n";
                echo "stato prod =" . substr($buffer, 132, 1) . "<br>\n";
                echo "data ultima =" . substr($buffer, 133, 8) . "<br>\n";
                echo "fam. sconto= " . substr($buffer, 141, 18) . "<br>\n";
                echo "statistica =" . substr($buffer, 159, 18) . "<br>\n";
                echo "Electrocod =" . substr($buffer, 177, 20) . "<br>\n";
                echo "Codice barcode= " . substr($buffer, 197, 35) . "<br>\n";
                echo "Q. codice =" . substr($buffer, 232, 1) . "<br>\n";

                echo "</td></tr></table>\n";

                $utente = tabella_fornitori("singola_parametri", $iva_utente, "piva LIKE");
            }
        }
        elseif($separatore == "ODS")
        {
            include $_percorso."tools/ods/ods.php"; //include the class and wrappers
            
            $object=parseOds($_percorso . "../spool/" . $_FILES["file"]["name"]); //load the ods file

            $foglio = "0";
            echo "numero righe ". $object->readSheet("numero",$foglio). " foglio nr. $foglio<br>\n";

            $righe = $object->readSheet("numero",$foglio);

            echo "Elenco prime tre righe<BR></center>";
            echo "<h4>Assegnare i campi letti ai rispettivi database articoli <font color=\"red\">Attenzione il primo campo è la ricerca ed assegnazione</font></h4>\n";
            
            for ($b = 0; $b < 3; $b++)
            {
              $value = $object->readRow($foglio,$b,"");
              //echo "numero colonne ".count($value) ."<br>\n";
              $colonne = count($value);

              for ($a = 0; $a < $colonne; $a++)
              {
                echo "|".$value[$a]['value'] ."\n";
                
              }
              
              echo "</br>\n";
              
            }
            
            $_nr = 0;
            echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"1\">\n";
            echo "<tr>\n";
            $value = $object->readRow($foglio, 4, "");
            //echo "numero colonne ".count($value) ."<br>\n";
            $colonne = count($value);

            for ($a = 0; $a < $colonne; $a++)
            {
                echo "<td>Campo NR $_nr <br>\n";

                echo $value[$a]['value'] . "<br />";
                colonna_articolo("campo$_nr");
                echo "</td>\n";
                $_nr++;
            }


            echo "</tr></table>\n";
            
            
            

        }
        else
        {
            echo "Elenco prime tre righe<BR></center>";
            $news = fopen($_percorso . "../spool/" . $_FILES["file"]["name"] . "", "r"); //apre il file
            $buffer = fgets($news, 4096);
            echo "Riga n. 1  valore = $buffer <BR>"; //riga letta
            $buffer = fgets($news, 4096);
            echo "Riga nr. 2 $_a valore = $buffer <BR>"; //riga letta
            $buffer = fgets($news, 4096);
            echo "Riga nr. 3 $_a valore = $buffer <BR>"; //riga letta
            echo "<h4>Assegnare i campi letti ai rispettivi database articoli <font color=\"red\">Attenzione il primo campo è la ricerca ed assegnazione</font></h4>\n";
            //la mia indea era quella di elencare in base ai campi selzionati l'elenco della tabella..
            $separatore = $_POST['separatore'];
            $suddivisa = explode($separatore, $buffer);

            //della seconda riga elenchiamo i campi di assegnazione con un campo dentro una tabella..
            $_nr = 0;
            echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"1\">\n";
            echo "<tr>\n";
            foreach ($suddivisa as $campo)
            {

                echo "<td>Campo NR $_nr <br>\n";

                echo $campo . "<br />";
                colonna_articolo("campo$_nr");
                echo "</td>\n";
                $_nr++;
            }

            echo "</tr></table>\n";
        }
    }
    //eliminiamo un campo per avere gli effettivi..
    //
	$_nr --;
    //separiamo i campi..
    //
	echo "<br><hr/>\n";
    //ecco qui elenchiamo le opzioni di importazioni tipo le categorie merciologiche, tiport

    echo "<table border=\"0\" width=\"100%\">\n";
    


    if ($_tipolistino == "FST")
    {
        echo "<tr><td>Aggiorna ed inserische le tipologie articolo.. </td></tr>\n";
    }
    else
    {
        echo "<tr>\n";
        echo "<td colspan=\"4\"> Seleziona il numero ed il fornitore<br/> \n";
        //selezioniamo il fornitore
        //associamo il numero del fornitore..
        tabella_fornitori("elenca_select_2", $utente['codice'], "codfor");

        echo "</td></tr>\n";
        echo "<td colspan=\"3\">Seleziona la scontistica riservata sul listino  <input type=\"text\" size=\"4\" name=\"scaa\" value=\"0\"> + <input type=\"text\" size=\"4\" name=\"scab\" value=\"0\"> + <input type=\"text\" size=\"4\" name=\"scac\" value=\"0\"></td>\n";
        echo "</tr>\n";

        echo "<tr>\n";
        echo "<td colspan=\"3\">Aggiornare Descrizione ? <input type=\"checkbox\" name=\"descrizione\" value=\"SI\">\n"
        . "Aggiornare Unita di misura ? <input type=\"checkbox\" name=\"unita\" value=\"SI\"> - Aggiornare la tipologia articolo ? <input type=\"checkbox\" name=\"tipologia\" value=\"SI\"> </td>\n";
        echo "</tr>\n";

        echo "<tr><td colspan=\"4\"><hr/></td></tr>\n";

        echo "<tr>\n";

        //-------------------------------------
        echo "<td><h3>Genera listini<input type=\"checkbox\" name=\"vendita\" value=\"SI\"></h3></tr>\n";
        echo "<tr>\n";
        echo "<td colspan=\"2\">Inserisci un nuovo listino <input type=\"radio\" name=\"listino\" value=\"new\" checked> Oppura Aggiorna Attuale <input type=\"radio\" name=\"listino\" value=\"update\"></td></tr>\n";
        echo "<tr>\n";
        echo "<td colspan=\"2\">Inserisci il fattore di moltiplicazione<input type=\"text\" size=\"4\" name=\"moltiplica\" value=\"0\">\n";
        echo "Se percentuale =><input type=\"checkbox\" name=\"percento\" value=\"SI\">%</td>\n";
        echo "<td>Inserisci il numero del listino<br><input type=\"text\" size=\"4\" name=\"nlv\" value=\"1\"></td>\n";

        echo "</tr>\n";

        //--------------------------------------

        echo "<tr><td colspan=\"4\"><hr/></td></tr>\n";

        echo "<td><h3>Crea Nuovi codici <input type=\"checkbox\" name=\"newcod\" value=\"SI\"></h3></td>\n";
        echo "<td>Inserisci codice base di partenza<br><input type=\"text\" size=\"18\"  maxlenfght=\"15\" name=\"cod_start\" value=\"\"><br>Cod fornitore = cod articolo<input type=\"checkbox\" name=\"codforart\" value=\"SI\"></td>\n";
        echo "<td>Inserisci il fattore di moltiplicazione del codice<br><input type=\"text\" size=\"4\" name=\"multi_cod\" value=\"\"></td></tr>\n";

        echo "<tr><td>\n";
        echo "Inserisci il numero del fornitore\n";
        echo "<select name=\"num_forn\">\n";
        echo "<option value=\"1\"> - 1 - </option>\n";
        echo "<option value=\"2\"> - 2 - </option>\n";
        echo "<option value=\"3\"> - 3 - </option>\n";
        echo "</select>\n";
        echo "</td>\n";
        echo "<td>Unità Mis.<input type=\"size\" size=\"3\" name=\"unita_man\"></td>\n";

        echo "</tr>\n";

        echo "<tr>\n";
        echo "<td>\n";
        echo "Scegli la categoria merceologica <br>\n";
        tabella_catmer("elenca_select", "catmer", $_parametri);
        echo "</td>\n";
        echo "<td>\n";
        echo "Scegli la tipologia articoli <br>\n";
        tabella_tipart("elenca_select", "tipart", $_parametri);
        echo "</td>\n";

        echo "<td>Associa una foto <br/>\n";
        echo "<select name=\"immagine\">";
        echo "<option value\"\"></option>\n";
        exec("ls " . $_percorso . "../imm-art/ ", $resrAr);
        while (list($key, $val) = each($resrAr))
        {
            echo "<option value=\"$val\">$val\n";
        }
        echo "</select></td>";
        echo "</tr>\n";

        echo "<tr>\n";
        echo "<td colspan=\"2\">Esente muov. magazzino  ?  <input type=\"size\" size=\"3\" name=\"esma\" value=\"NO\">\n";
        echo "Pubblica ?  <input type=\"size\" size=\"3\" name=\"pubblica\" value=\"SI\"></td>\n";

        echo "<td colspan=\"2\"> Seleziona l'iva di appartenenza  = \n";

        tabella_aliquota("elenca_select_2", "", "iva");

        echo "</td></tr>\n";


        echo "<tr>\n";



        echo "<tr><td align=\"right\" colspan=\"4\" width=\"500\">Descrizione estesa :&nbsp;<br/>";
        echo "<textarea id=\"elm2\" name=\"descsito\" cols=\"30\" rows=\"25\">$dati[descsito]</textarea></td></tr>\n";
    }

    echo "<tr><td colspan=\"4\"><hr/></td></tr>\n";

    echo "<tr>\n";
    echo "<td colspan=\"4\">Nome file <input type=\"radio\" name=\"nomefile\" value=\"" . $_FILES["file"]["name"] . "\" checked>" . $_FILES["file"]["name"] . " con separatore <input type=\"radio\" name=\"separatore\" value=\"$separatore\" checked>$separatore<input type=\"radio\" name=\"numero_campi\" value=\"$_nr\" checked>$_nr</td>\n";
    echo "</tr>\n";


    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td colspan=\"4\"><br><input type=\"submit\" name=\"azione\" value=\"Prosegui\"></td>\n";
    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";

    //fine operazione files..
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
