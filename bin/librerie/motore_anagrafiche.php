<?php

/*
 * Agua Gest File motore anagrafiche
 * Libreria di supporto per le anagrafiche, qui verranno messe tutte le funzioni
 * inerenti alla gestione mysql tabelle come le banche, clienti vettori
 * quindi tutto quello che riguarda
 * Selezione, inserimento, modifica, aggiornamento, cancellazione ecc.
 * 
 * aguagest.sourceforge.net 

 */

/*ISTRUZIONI  VELOCI PER IL COLLEGAMENTO AL DATABASE PDO
 * 
 * $query;
 * $result = $conn->query($query);
 * if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
 * Poi qui o la multipla con un forearc
 * foreach ($result as $return)
 * 
 * oppure la singola con 
 * $dati = $result->fetch(PDO::FETCH_ASSOC);
 *  
 */


/**Gestione dell'archivio agenti se femminili per connessioni PDO
 * @cosa singola_select estrae una singola riga in formato per un aselect
 * @cosa elenca_select elenca l'elenco della tabella in formato select
 * @return type
 */
function tabella_agenti($_cosa, $_codage, $_parametri)
{
    global $conn;
    global $_percorso;

    if ($_cosa == "singola")
    {
        $query = sprintf("select * from agenti where codice=\"%s\"", $_codage);
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result as $return)
            ;
    }
    elseif ($_cosa == "singola_select")
    {
        $query = sprintf("select codice, ragsoc from agenti where codice=\"%s\"", $_codage);
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result as $dati1)
            ;
        printf("<option value=\"%s\">%s</option>\n", $_codage, $dati1['ragsoc']);
    }
    elseif ($_cosa == "Inserisci")
    {
        $_ragsoc = addslashes($_parametri['ragsoc']);
        $_ragsoc2 = addslashes($_parametri['ragsoc2']);
        $_indirizzo = addslashes($_parametri['indirizzo']);
        $_citta = addslashes($_parametri['citta']);
        $_note = addslashes($_parametri['note']);

        $_data_reg = date('Y-m-d');
        $query = "INSERT INTO agenti ( codice, data_reg, ragsoc, ragsoc2, indirizzo, cap, citta, prov, codnazione, codfisc, piva, contatto,"
                . " telefono, telefono2, cell, fax, iva, email, email2, email3, sitocli, note ) VALUES "
                . " ('$_codage', '$_data_reg', '$_ragsoc', '$_ragsoc2', '$_indirizzo', '$_parametri[cap]', '$_citta', '$_parametri[prov]', '$_parametri[codnazione]', '$_parametri[codfisc]', '$_parametri[piva]', '$_parametri[contatto]', '$_parametri[telefono]', '$_parametri[telefono2]', '$_parametri[cell]', '$_parametri[fax]', '$_parametri[iva]', '$_parametri[email]', '$_parametri[email2]', '$_parametri[email3]', '$_parametri[sitocli]', '$_note')";


        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
        
    }
    elseif($_cosa == "Aggiorna")
    {
        $_ragsoc = addslashes($_parametri['ragsoc']);
        $_ragsoc2 = addslashes($_parametri['ragsoc2']);
        $_indirizzo = addslashes($_parametri['indirizzo']);
        $_citta = addslashes($_parametri['citta']);
        $_note = addslashes($_parametri['note']);

        $_data_reg = date('Y-m-d');
        $query = "UPDATE agenti set ragsoc='$_ragsoc', ragsoc2='$_ragsoc2', indirizzo='$_indirizzo', cap='$_parametri[cap]', citta='$_citta', prov='$_parametri[prov]', codnazione='$_parametri[codnazione]', codfisc='$_parametri[codfisc]', piva='$_parametri[piva]', contatto='$_parametri[contatto]',"
                . " telefono='$_parametri[telefono]', telefono2='$_parametri[telefono2]', cell='$_parametri[cell]', fax='$_parametri[fax]', iva='$_parametri[iva]', email='$_parametri[email]', email2='$_parametri[email2]', email3='$_parametri[email3]', sitocli='$_parametri[sitocli]', note='$_note' 
                    where codice = '$_codage' limit 1 ";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
           $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            $return = "OK";
        }
        
    }
    elseif ($_cosa == "elenca_select")
    {
        // elenco le modalita di pagamento per una ventuale variazione
        $query = sprintf("select codice, ragsoc from agenti order by ragsoc");
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result as $dati1)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati1['codice'], $dati1['ragsoc']);
        }
    }
    elseif ($_cosa == "elenca_select_2")
    {

        echo "<select name=\"$_parametri\">\n";
        
        $query = sprintf("select codice, ragsoc from agenti where codice=\"%s\"", $_codage);
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        foreach ($result as $dati);
        
        printf("<option value=\"%s\">%s</option>\n", $dati['codice'], $dati['ragsoc']);
        
        echo "<option value=\"\"></option>\n";

        //seconda query
        $query = sprintf("select codice, ragsoc from agenti order by ragsoc");
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result as $dati1)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati1['codice'], $dati1['ragsoc']);
        }

        echo "</select>\n";
        echo "</td></tr>\n";
    }
    elseif($_cosa == "ricerca")
    {
        $_descrizione = $_parametri['descrizione'];
        $_campi = $_parametri['campi'];

        $_descrizione = "%$_descrizione%";


        $query = sprintf("select * from agenti where $_campi like \"%s\" order by ragsoc", $_descrizione);
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $return = $result;
    }
    else
    {
        // elenco le modalita di pagamento per una ventuale variazione
        $query = sprintf("select codice, ragsoc from agenti order by ragsoc");
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

        $return = $result;
    }




    return $return;
}

/**Gestisce la tabella aliquote iva
 * Passando la variabile cosa come selezione delle funzioni,
 * tutte le richiesta femminili sono in connessione PDO
 * @param string $_cosa singolo restitusce la singola riga
 * @param string $_cosa singola restitusce la singola riga PDO
 * @param string $_cosa "singola_aliquota" = passando il codice $_codiva restitusce valore numerico iva PDO
 * @param string $_cosa descsingola restitusce la descrizione della singola riga
 * @param string $_cosa elenco_codice restiruisce l'array della tabella per codice
 * @param string $_cosa elenco restiruisce l'array dellla tabella
 * @param valore $_codiva codice da richiamare
 * 
 * @return arrey con il risultato
 */
function tabella_aliquota($_cosa, $_codiva, $_parametri)
{
    global $conn;
    global $dec;
    global $_percorso;
    

    if ($_cosa == "singolo")
    {
//mi restituisce l'arre singolo
        $query = "select * from aliquota where codice='$_codiva' limit 1";

// Esegue la query...
        $res = mysql_query($query, $conn);
        $dati = mysql_fetch_array($res);
    }
    elseif ($_cosa == "singola")
    {
//mi restituisce l'arre singolo
        $query = "select * from aliquota where codice='$_codiva' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result as $dati)
            ;
    }
    elseif ($_cosa == "desc_singola")
    {
//mi restituisce l'arre singolo
        $query = "select * from aliquota where codice='$_codiva' limit 1";
// Esegue la query...
        $res = mysql_query($query, $conn);
        $array = mysql_fetch_array($res);

        $dati = $array['descrizione'];
    }
    elseif ($_cosa == "singolo_aliquota")
    {
//mi restituisce l'arre singolo
        $query = "select * from aliquota where codice='$_codiva' limit 1";

// Esegue la query...
        $res = mysql_query($query, $conn);
        $array = mysql_fetch_array($res);

        $dati = $array['aliquota'];
    }
    elseif ($_cosa == "singola_aliquota")
    {
//mi restituisce l'arre singolo
        $query = "select * from aliquota where codice='$_codiva' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result as $array)
            ;
        $dati = $array['aliquota'];
    }
    elseif ($_cosa == "elenco_codice")
    {
//mi restituisce l'elenco
        $query = "select * from aliquota order by codice";

// Esegue la query...
        $dati = mysql_query($query, $conn);
    }
    elseif ($_cosa == "elenca_codice")
    {
//mi restituisce l'elenco
        $query = "select * from aliquota order by codice";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        $dati = $result;
    }
    elseif ($_cosa == "elenca_codice_indetraibile")
    {
//mi restituisce l'elenco
        $query = "select * from aliquota WHERE colonnacli = '1'  order by codice";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        $dati = $result;
    }
    elseif ($_cosa == "singola_select")
    {
        $query = sprintf("select descrizione from aliquota where codice=\"%s\"", $_codiva);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result AS $dati)
            
            printf("<option value=\"%s\">%s</option>\n", $_codiva, $dati['descrizione']);
    }
    elseif ($_cosa == "elenca_select_2")
    {

        $query = "select codice, descrizione from aliquota where codice='$_codiva'";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }


        foreach ($result AS $dati);
        echo "<select name=\"$_parametri\">\n";
        printf("<option value=\"%s\">%s - %s</option>\n", $_codiva, $_codiva, $dati['descrizione']);
        
        echo "<option value=\"\"></option>\n";
        
        $query = "select codice, descrizione from aliquota order by codice";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[codice]\">$dati[codice] - $dati[descrizione]</option>\n";
        }
         echo "</select>\n";
        
    }
    elseif ($_cosa == "elenco_select")
    {

//mi restituisce l'arre singolo
        $query = "select * from aliquota order by codice";

// Esegue la query...
        $res = mysql_query($query, $conn);

        echo "<select name=\"$_codiva\">\n";
        echo "<option value=\"\"></option>\n";

        while ($dati = mysql_fetch_array($res))
        {
            echo "<option value=\"$dati[codice]\">$dati[codice] - $dati[descrizione]</option>\n";
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "elenca_select_numeri")
    {
        $query = "select codice from aliquota order by codice";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        echo "<select name=\"$_parametri\">\n";
        
        echo "<option value=\"$_codiva\">$_codiva</option>\n";

        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[codice]\">$dati[codice]</option>\n";
        }

        echo "</select>\n";
    }
    elseif($_cosa == "elenca")
    {
//mi restituisce l'elenco
        $query = "select * from aliquota order by descrizione";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        $dati = $result;
    }
    else
    {
//mi restituisce l'elenco
        $query = "select * from aliquota order by descrizione";

    // Esegue la query...
            $dati = mysql_query($query, $conn);
    }
    
    return $dati;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------

/* * Funzione di controllo della tabella articoli, inserisci, modifica, elimina, cerca, elenca.
 *
 * @global  $conn
 * @param type $_cosa
 * @param type $_codice
 * @param type $_parametri
 * @return type 
 */

function tabella_articoli($_cosa, $_codice, $_parametri)
{
    global $conn;
    global $_percorso;
//funzione che mi restituisce l'array con tutta la riga del cliente

    if ($_cosa == "singolo")
    {
        $query = "select * from articoli where articolo='$_codice' limit 1";

        $res = mysql_query($query, $conn);

        //verifico se ha trovato qualcosa..
        if (mysql_num_rows($res) < 1)
        {
           $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $dati['errori'] = "Nessun Articolo trovato";
        }
        else
        {
            $dati = mysql_fetch_array($res);
        }
    }
    elseif ($_cosa == "singola")
    {
        $query = "select * from articoli where articolo='$_codice' limit 1";

        $result = domanda_db("query", $query, $_parametri);
        
        if($result != "NO")
        {
            $dati = $result->fetch(PDO::FETCH_ASSOC);
            $dati['risultato'] = "SI";
        }
        else
        {
            $dati['risultato'] = "NO";
        }
        
    }
    elseif ($_cosa == "singola_prezzo")
    {

        $query = "SELECT * FROM articoli INNER JOIN listini ON articoli.articolo=listini.codarticolo WHERE articolo='$_codice' AND rigo='$_parametri' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Articolo trovato";
        }
        else
        {
            foreach ($result AS $dati);
        }
    }
    elseif ($_cosa == "fornitori")
    {
        //ricerca per qulsiasi fornitore

        $query = sprintf("select * from articoli where artfor=\"%s\" or artfor2=\"%s\" or artfor_3=\"%s\" limit 1", $_codice, $_codice, $_codice);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Articolo trovato";
        }
        else
        {
            if ($result->rowCount() > "0")
            {
                foreach ($result AS $dati);
                $dati['risultato'] = "SI";
            }
            else
            {
                $dati['risultato'] = "NO";
            }
        }
    }
    elseif ($_cosa == "ricerca")
    {
        $query = sprintf("select * from articoli INNER JOIN listini ON articoli.articolo = listini.codarticolo where rigo='1' and $_parametri[campi] like \"%s\" order by articolo", $_parametri['descrizione']);

        // Esegue la query...
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $dati = $result;
    }
    elseif ($_cosa == "check")
    {
        $query = "select * from articoli where articolo='$_codice' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $dati = true;
        }
        else
        {

            $dati = false;
        }
    }
    elseif ($_cosa == "blocca")
    {

        $_datareg = date('Y-m-d');
        // Stringa contenente la query di ricerca...
        $query = "insert into articoli(articolo, descrizione ) values ( '$_codice', '$_codice' )";


        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $dati['errori'] = "Errore inserimento articolo.. $_errori[descrizione]";
            $dati['risultato'] = "NO";
        }
        else
        {
                $dati['risultato'] = "SI";
                $dati['articolo'] = $_codice;

        }
        
        
        
    }
    elseif ($_cosa == "Inserisci")
    {
        $_descsito = addslashes($_parametri['descsito']);
        $_memoart = addslashes($_parametri['memoart']);
        $_descrizione = addslashes($_parametri['descrizione']);
        $_ordine_cat = addslashes($_parametri['ordine_cat']);

        $_datareg = date('Y-m-d');
        // Stringa contenente la query di ricerca...
        $query = "insert into articoli(articolo, descrizione, desrid, unita, codbar, fornitore, artfor, preacqnetto, prelisacq,
	    scaa, scab, scac, qta_cartone, qta_multi_ord, qtaminord, pesoart, lead_time, prod_composto, stato_prod, data_var, tipart, catmer, iva, memoart, provvart,
            fornitore2, preacqnetto2, prelisacq_2, scaa_2, scab_2, scac_2, artfor2, qta_cartone_2, qta_multi_ord_2, qtaminord_2, lead_time_2, prod_composto_2, stato_prod_2, data_var_2,
            fornitore_3, artfor_3, prelisacq_3, scaa_3, scab_3, scac_3, preacqnetto_3,
            qta_cartone_3, qta_multi_ord_3, qtaminord_3, lead_time_3, prod_composto_3, stato_prod_3, data_var_3, sitoart, data_reg, esco,
	    esma, scorta, immagine, pagcat, catalogo, pubblica, descsito, artcorr, artcorr_2, artcorr_3, a_settore, a_scaffale, a_ripiano, a_cassetto, art_alternativo, ordine_cat, egpz, immagine2, es_selezione)
	    values( '$_codice', '$_descrizione', '$_parametri[desrid]', '$_parametri[unita]', '$_parametri[codbar]', '$_parametri[fornitore]',
                '$_parametri[artfor]', '$_parametri[preacqnetto]', '$_parametri[prelisacq]', '$_parametri[scaa]', '$_parametri[scab]',
                '$_parametri[scac]', '$_parametri[qta_cartone]', '$_parametri[qta_multi_ord]', '$_parametri[qtaminord]', '$_parametri[pesoart]', '$_parametri[lead_time]', '$_parametri[prod_composto]', '$_parametri[stato_prod]',
                '$_parametri[data_var]', '$_parametri[tipart]', '$_parametri[catmer]', '$_parametri[iva]',
                '$_memoart', '$_parametri[provvart]', '$_parametri[fornitore2]', '$_parametri[preacqnetto2]', '$_parametri[prelisacq_2]', '$_parametri[scaa_2]', '$_parametri[scab_2]', '$_parametri[scac_2]', '$_parametri[artfor2]',
                '$_parametri[qta_cartone_2]', '$_parametri[qta_multi_ord_2]', '$_parametri[qtaminord_2]', '$_parametri[lead_time_2]', '$_parametri[prod_composto_2]', '$_parametri[stato_prod_2]', '$_parametri[data_var_2]',
                '$_parametri[fornitore_3]', '$_parametri[artfor_3]', '$_parametri[prelisacq_3]', '$_parametri[scaa_3]', '$_parametri[scab_3]', '$_parametri[scac_3]', '$_parametri[preacqnetto_3]',
                '$_parametri[qta_cartone_3]', '$_parametri[qta_multi_ord_3]', '$_parametri[qtaminord_3]', '$_parametri[lead_time_3]', '$_parametri[prod_composto_3]', '$_parametri[stato_prod_3]', '$_parametri[data_var_3]',
                '$_parametri[sitoart]', '$_datareg', '$_parametri[esco]', '$_parametri[esma]', '$_parametri[scorta]', '$_parametri[immagine]', '$_parametri[pagcat]', '$_parametri[catalogo]',
                '$_parametri[pubblica]', '$_descsito', '$_parametri[artcorr]', '$_parametri[artcorr_2]', '$_parametri[artcorr_3]', '$_parametri[a_settore]', '$_parametri[a_scaffale]', '$_parametri[a_ripiano]',
                '$_parametri[a_cassetto]', '$_parametri[art_alternativo]', '$_ordine_cat', '$_parametri[egpz]', '$_parametri[immagine2]', '$_parametri[es_selezione]')";


        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $dati['errori'] = "Errore inserimento articolo.. $_errori[descrizione]";
        }
    }
    elseif ($_cosa == "Aggiorna")
    {
        //funzione che mi aggiorna gli articoli
        $_descsito = addslashes($_parametri['descsito']);
        $_memoart = addslashes($_parametri['memoart']);
        $_ordine_cat = addslashes($_parametri['ordine_cat']);
        $_descrizione = addslashes($_parametri['descrizione']);

        $_datareg = date('Y-m-d');

        $query = "update articoli set descrizione='$_descrizione', desrid='$_parametri[desrid]', unita='$_parametri[unita]', codbar='$_parametri[codbar]',
    fornitore='$_parametri[fornitore]', artfor='$_parametri[artfor]', preacqnetto='$_parametri[preacqnetto]', prelisacq='$_parametri[prelisacq]', scaa='$_parametri[scaa]',
    scab='$_parametri[scab]', scac='$_parametri[scac]', qta_cartone='$_parametri[qta_cartone]', qta_multi_ord='$_parametri[qta_multi_ord]', qtaminord='$_parametri[qtaminord]',
    pesoart='$_parametri[pesoart]', lead_time='$_parametri[lead_time]', prod_composto='$_parametri[prod_composto]', stato_prod='$_parametri[stato_prod]', data_var='$_parametri[data_var]', tipart='$_parametri[tipart]', catmer='$_parametri[catmer]',
    iva='$_parametri[iva]', memoart='$_memoart', provvart='$_parametri[provvart]', fornitore2='$_parametri[fornitore2]', artfor2='$_parametri[artfor2]',
    preacqnetto2='$_parametri[preacqnetto2]', prelisacq_2='$_parametri[prelisacq_2]', scaa_2='$_parametri[scaa_2]', scab_2='$_parametri[scab_2]', scac_2='$_parametri[scac_2]',
    sitoart='$_parametri[sitoart]', qta_cartone_2='$_parametri[qta_cartone_2]', qta_multi_ord_2='$_parametri[qta_multi_ord_2]', qtaminord_2='$_parametri[qtaminord_2]',
    lead_time_2='$_parametri[lead_time_2]', prod_composto_2='$_parametri[prod_composto_2]', stato_prod_2='$_parametri[stato_prod_2]', data_var_2='$_parametri[data_var_2]',
    fornitore_3='$_parametri[fornitore_3]', artfor_3='$_parametri[artfor_3]', prelisacq_3='$_parametri[prelisacq_3]',
    scaa_3='$_parametri[scaa_3]', scab_3='$_parametri[scab_3]', scac_3='$_parametri[scac_3]', preacqnetto_3='$_parametri[preacqnetto_3]',
    qta_cartone_3='$_parametri[qta_cartone_3]', qta_multi_ord_3='$_parametri[qta_multi_ord_3]', qtaminord_3='$_parametri[qtaminord_3]',
    lead_time_3='$_parametri[lead_time_3]', prod_composto_3='$_parametri[prod_composto_3]', stato_prod_3='$_parametri[stato_prod_3]', data_var_3='$_parametri[data_var_3]', esco='$_parametri[esco]',
    esma='$_parametri[esma]', scorta='$_parametri[scorta]', immagine='$_parametri[immagine]', pagcat='$_parametri[pagcat]', catalogo='$_parametri[catalogo]', pubblica='$_parametri[pubblica]',
    descsito='$_descsito', artcorr='$_parametri[artcorr]', artcorr_2='$_parametri[artcorr_2]', artcorr_3='$_parametri[artcorr_3]', a_settore='$_parametri[a_settore]', a_scaffale='$_parametri[a_scaffale]',
    a_ripiano='$_parametri[a_ripiano]', a_cassetto='$_parametri[a_cassetto]', art_alternativo='$_parametri[art_alternativo]', ordine_cat='$_ordine_cat', egpz='$_parametri[egpz]', immagine2='$_parametri[immagine2]', es_selezione='$_parametri[es_selezione]' WHERE articolo='$_codice' LIMIT 1";


        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Errore Aggiornamento articolo..";
        }
    }
    elseif ($_cosa == "Elimina")
    {

        $query = sprintf("(select articolo, anno, ndoc, utente from magazzino where articolo=\"%s\" limit 10)
	 UNION (select articolo, anno, ndoc, utente from of_dettaglio where articolo=\"%s\" limit 10) 
	 UNION (select articolo, anno, ndoc, utente from pv_dettaglio where articolo=\"%s\" limit 10) 
	 UNION (select articolo, anno, ndoc, utente from co_dettaglio where articolo=\"%s\" limit 10) 
	 UNION (select articolo, anno, ndoc, utente from bv_dettaglio where articolo=\"%s\" limit 10) 
	 UNION (select articolo, anno, ndoc, utente from fv_dettaglio where articolo=\"%s\" limit 10)", $_codice, $_codice, $_codice, $_codice, $_codice, $_codice);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }


        $query2 = sprintf("select sum(qtacarico) AS qtacarico, sum(qtascarico) AS qtascarico from magazzino where articolo=\"%s\"", $_codice);

        $result2 = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result2 AS $dati2)
            $_qtacarico = $dati2['qtacarico'];
        $_qtascarico = $dati2['qtascarico'];

        $_giacenza = ($_qtacarico - $_qtascarico);

        //echo $res;
        // La query e' stata eseguita con successo...
        // Non so' se ci sono articoli con quel codice... da cambiare..
        if ($result->rowCount() > 0)
        {
            echo "<br> Impossibile eliminare l'articolo perche trovato nei seguenti muovimenti.";
            echo " <table align=center border=1>";
            echo "<tr><td colspan=\"4\">Giacenza articolo = $_giacenza</td></tr>";
            echo "<tr><td>articolo</td><td>anno</td><td>n. doc.</td><td>utente</td></tr>";
            foreach ($result AS $dati)
                ; {

                printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $dati['articolo'], $dati['anno'], $dati['ndoc'], $dati['utente']);
            }
            echo "</table>";


            echo "<center><br><h3> forzare eliminatura ? </h3><br> in caso di forzatura l'articolo verra eliminato <br>dal magazzino sia normale che storico";

            echo "<br><a href=\"risinseart.php?azione=Forza&codice=$_codice\"> ELIMINA SENZA PAURA </a></center>";
            $dati['errori'] = "Trovato articolo Muovimentato";
        }
        else
        {
            $dati = tabella_articoli("Forza", $_codice, "");
        }
    }
    elseif ($_cosa == "Forza")
    {

        $query = sprintf("DELETE FROM articoli WHERE articolo=\"%s\" limit 1", $_codice);

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Errore eliminazione articoli";
        }
        else
        {

            $dati['conferma1'] = "Eliminazione articolo riuscita";

            $query = sprintf("DELETE FROM listini WHERE codarticolo=\"%s\" ", $_codice);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                echo "Si &egrave; verificato un errore nella query listino $_nlv:<br>\n\"$query\"\n";

                $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
                $dati['errori'] = "Errore eliminazione listino $_nlv";
            }
            else
            {
                $dati['conferma2'] = "Eliminazione listini articolo riuscita";

                //eliminiamo anche dai codici a barre..
                $query = sprintf("DELETE FROM barcode WHERE articolo=\"%s\" ", $_codice);

                $result = $conn->exec($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
                    $dati['errori'] = "Errore eliminazione barcode";
                }
                else
                {
                    $dati['conferma3'] = "Eliminazione barcode articolo riuscita";

                    // proseguiamo con le query a cascata..

                    $query = sprintf("DELETE FROM magazzino WHERE articolo=\"%s\" ", $_codice);
                    $result = $conn->exec($query);

                    if ($conn->errorCode() != "00000")
                    {
                        $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
                        $dati['errori'] = "Errore eliminazione magazzino";
                    }
                    else
                    {
                        $dati['conferma4'] = "Eliminazione magazzino articolo riuscita";

                        $query = sprintf("DELETE FROM magastorico WHERE articolo=\"%s\" ", $_codice);
                        $result = $conn->exec($query);

                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
                            $dati['errori'] = "Errore eliminazione magazzino storico";
                        }
                        else
                        {
                            $dati['conferma4'] = "Eliminazione magazzino storico articolo riuscita";
                        }
                    }
                }
            }
        }
    }
    elseif ($_cosa == "elenco")
    {
//mi restituisce l'arre singolo
        $query = "select * from articoli order by articolo";

// Esegue la query...
        $res = mysql_query($query, $conn);

        if (mysql_num_rows($res) > "0")
        {
            $dati = $res;
        }
        else
        {
            $dati['errori'] = "Errore Query elenco articoli";
        }
    }
    elseif ($_cosa == "select_intestazione")
    {
//mi restituisce l'arre singolo
        $query = "DESCRIBE articoli";

// Esegue la query...
        $res = mysql_query($query, $conn);

        echo "<select name=\"$_codice\">\n";
        echo "<option value=\"\"></option>\n";

        while ($dati = mysql_fetch_array($res))
        {
            echo "<option value=\"$dati[Field]\">$dati[Field]</option>\n";
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "elenco_select")
    {

        $query = "SELECT articolo, substring(descrizione,1,60) AS descrizione FROM articoli where es_selezione != 'SI' ORDER BY articolo";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $dati = $result;
    }
    elseif ($_cosa == "elenca_select")
    {

        $query = "SELECT articolo, substring(descrizione,1,60) AS descrizione FROM articoli where es_selezione != 'SI' ORDER BY descrizione";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        echo "<select name=\"$_codice\">\n";
        echo "<option value=\"\"></option>\n";

        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[articolo]\">$dati[descrizione] - $dati[articolo]</option>\n";
        }

        echo "</select>\n";

    }
    elseif ($_cosa == "provvart")
    {
        $query = sprintf("select provvart from articoli where articolo=\"%s\"", $_codice);
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result AS $dati2)
            ;
        $dati = $dati2['provvart'];
    }
    elseif ($_cosa == "esma")
    {
        $query = "select esma from articoli where articolo='$_codice'";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result AS $dati2);
        $dati = $dati2['esma'];
    }
    elseif($_cosa == "aggiorna_fornitore")
    {
        @$_ultacq = $_parametri['totriga'] / $_parametri['quantita'];

        //verifichiamo che fornitore è..

        $query = sprintf("UPDATE articoli SET preacqnetto=\"%s\", prelisacq=\"%s\", scaa=\"%s\", scab=\"%s\", scac=\"%s\", ultacq=\"%s\" where articolo=\"%s\" and fornitore=\"%s\"", $_parametri['nettovendita'], $_parametri['listino'], $_parametri['sca'], $_parametri['scb'], $_parametri['scac'], $_ultacq, $_codice, $_parametri['utente']);

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        // se le righe prese sono uguali a zero vuol dire che è il secondo
        if ($result < 1)
        {
                //vuo dire che non è il fornitore uno vediamo se è il due
                $query = sprintf("UPDATE articoli SET preacqnetto2=\"%s\", prelisacq_2=\"%s\", scaa_2=\"%s\", scab_2=\"%s\", scac_2=\"%s\", ultacq=\"%s\" where articolo=\"%s\" and fornitore2=\"%s\"", $_parametri['nettovendita'], $_parametri['listino'], $_parametri['sca'], $_parametri['scb'], $_parametri['scac'], $_ultacq, $_codice, $_parametri['utente']);

                $result = $conn->exec($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
                }
                // se le righe prese sono uguali a zero vuol dire che è il secondo
                if ($result < 1)
                {
                        //vuol dire che per forza è il tre.. !
                        $query = sprintf("UPDATE articoli SET preacqnetto_3=\"%s\", prelisacq_3=\"%s\", scaa_3=\"%s\", scab_3=\"%s\", scac_3=\"%s\", ultacq=\"%s\" where articolo=\"%s\" and fornitore_3=\"%s\"", $_parametri['nettovendita'], $_parametri['listino'], $_parametri['sca'], $_parametri['scb'], $_parametri['scac'], $_ultacq, $_codice, $_parametri['utente']);

                        $result = $conn->exec($query);

                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
                        }
                }
        }

        
        
    }
    elseif($_cosa == "aggiorna_ultimo_acq")
    {
        @$_ultacq = $_parametri['totriga'] / $_parametri['quantita'];
        
        $query = sprintf("UPDATE articoli SET ultacq=\"%s\" where articolo=\"%s\"", $_ultacq, $_codice);
// Esegue la query...
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_errori['errori'] = "NO";
        }
        
    }
    else
    {
        //mi scrivo un errore per capire da dove arrivo qui..
        
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa ";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        
        
//mi restituisce l'arre singolo
        $query = "DESCRIBE articoli";

// Esegue la query...
        $res = mysql_query($query, $conn);

        if (mysql_num_rows($res) > "0")
        {
            $dati = $res;
        }
        else
        {
            $dati['errori'] = "Errore Query elenco articoli";
        }
    }

    return $dati;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------

/**Funzione tabella banche
 * Gestische le banche a livello inserimento ricerca ecc.
 * @global  $conn
 * @$_cosa elenco=elenca in ordine di codice
 * @$_cosa singola estrae array banca su passaggio $_codice
 * @$_cosa singola_descrizione estrae array banca su passaggio $_codice descrizione banca
 * @return type 
 */
function tabella_banche($_cosa, $_codice, $_abi, $_cab, $_parametri)
{
    global $conn;
    global $_percorso;
    global $dec;

    if ($_cosa == "singolo")
    {
//mi restituisce l'arre singolo
        $query = "select * from banche where codice='$_codice' limit 1";

// Esegue la query...
        $res = mysql_query($query, $conn);
        $dati = mysql_fetch_array($res);
    }
    elseif ($_cosa == "singola")
    {
//mi restituisce l'arre singolo
        $query = "select * from banche where codice='$_codice' limit 1";

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati = "errore";
        }
        else
        {
            foreach ($result AS $dati)
                ;
        }
    }
    elseif ($_cosa == "singola_descrizione")
    {
//mi restituisce l'arre singolo
        $query = "select * from banche where banca='$_codice' limit 1";

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati = "errore";
        }
        else
        {
            foreach ($result AS $dati)
                ;
        }
    }
    elseif ($_cosa == "singolo_abi")
    {
//mi restituisce l'arre singolo
        $query = "select * from banche where abi='$_abi' AND cab='$_cab' limit 1";

// Esegue la query...
        $res = mysql_query($query, $conn);
        $dati = mysql_fetch_array($res);
    }
    elseif ($_cosa == "singola_abi")
    {
//mi restituisce l'arre singolo
        $query = "select * from banche where abi='$_abi' AND cab='$_cab' limit 1";
        
        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati = "errore";
        }
        else
        {
            $dati = $result->fetch(PDO::FETCH_ASSOC);
        }
        
    }
    elseif ($_cosa == "verifica")
    {
        //la funzione risponde true or false

        $query = sprintf("select codice, abi, cab from banche where codice=\"%s\" OR (abi=\"%s\" AND cab=\"%s\") limit 1", $_codice, $_abi, $_cab);

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

        foreach ($result AS $dati)
            ;

        $dati['errori']['descrizione'] = $_errori['descrizione'];

        //verifichiamo se esiste almeno una riga..
        if ($result->rowCount() > "0")
        {
            $dati['result'] = TRUE;
            #foreach ($result AS $dati);
        }
        else
        {
            $dati['result'] = FALSE;
        }
    }
    elseif ($_cosa == "ricerca")
    {

        $_descrizione = "%$_parametri[descrizione]%";
        $_campi = $_parametri['campi'];
        $query = sprintf("select * from banche where $_campi like \"%s\" order by banca", $_descrizione);

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

        $dati = $result;
    }
    elseif ($_cosa == "inserisci")
    {

        //inseriamo i dati nel database..

        $_data_reg = date('Y-m-d');
        $query = sprintf("INSERT INTO banche ( codice, banca, indirizzo, telefono, cell, fax, abi, cab, cin, cc, iban, swift, note, es_selezione ) VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\",\"%s\", \"%s\", \"%s\", \"%s\")", $_codice, $_parametri['banca'], $_parametri['indirizzo'], $_parametri['telefono'], $_parametri['cell'], $_parametri['fax'], $_abi, $_cab, $_parametri['cin'], $_parametri['cc'], $_parametri['iban'], $_parametri['swift'], $_parametri['note'], $_parametri['es_selezione']);

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['result'] = FALSE;
            $dati['errori'] = $_errori['descrizione'];
        }
        else
        {
            $dati['result'] = TRUE;
        }
    }
    elseif ($_cosa == "aggiorna")
    {

        $query = "UPDATE banche SET banca='$_parametri[banca]', indirizzo='$_parametri[indirizzo]', telefono='$_parametri[telefono]', cell='$_parametri[cell]', fax='$_parametri[fax]', abi='$_abi', cab='$_cab', cin='$_parametri[cin]', cc='$_parametri[cc]', iban='$_parametri[iban]', swift='$_parametri[swift]', note='$_parametri[note]', es_selezione='$_parametri[es_selezione]' WHERE codice='$_codice'";

        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['result'] = FALSE;
            $dati['errori'] = $_errori['descrizione'];
        }
        else
        {
            $dati['result'] = TRUE;
        }
    }
    elseif ($_cosa == "elimina")
    {

        $query = "DELETE FROM banche WHERE codice='$_codice' limit 1";

        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['result'] = FALSE;
            $dati['errori'] = $_errori['descrizione'];
        }
        else
        {
            $dati['result'] = TRUE;
        }
    }
    elseif ($_cosa == "elenca")
    {

        $query = "select * from banche order by codice";

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

        $dati = $result;
    }
    elseif ($_cosa == "elenca_radio")
    {

        $query = "select * from banche where es_selezione != 'SI' order by codice ";

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

        $dati = $result;
    }
    elseif ($_cosa == "elenca_select_2")
    {

        echo "<select name=\"$_parametri\">\n";
        
        $query = "select * from banche where codice='$_codice' limit 1";
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
        
        foreach ($result as $dati);
        
        printf("<option value=\"%s\">%s</option>\n", $dati['codice'], $dati['banca']);
        
        echo "<option value=\"\"></option>\n";

        //seconda query
        $query = "select * from banche where es_selezione != 'SI' order by codice ";
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

        foreach ($result as $dati1)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati1['codice'], $dati1['banca']);
        }

        echo "</select>\n";
        echo "</td></tr>\n";
    }
    else
    {
//mi restituisce l'arre singolo
        $query = "select * from banche order by codice";

// Esegue la query...
        $dati = mysql_query($query, $conn);
    }
    return $dati;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------

/* * Funzione di controllo tabella codici a barre..
 * Funzioni
 *
 * 
 * @global type $conn
 * @global type $_percorso
 * @param type $_cosa = Tipo di richiesta, check, inserisci, elimina, aggiorna, cerca_barre, cerca_articolo 
 * @param type $_codbar
 * @param type $_articolo
 * @param type $_rigo
 * @return type
 */
function tabella_barcode($_cosa, $_codbar, $_articolo, $_rigo)
{
    global $conn;
    global $_percorso;

    if ($_cosa == "singola")
    {
        // la funzione controlla che il codice a barre non sia già inserito o presente..

        $query = "SELECT * FROM barcode WHERE codbar='$_codbar' limit 1";

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
        
        if($result->rowCount() > 0 )
        {
            foreach ($result as $dati);
            $return = $dati['articolo'];
        }
        else
        {
            $return = "NO";
        }

    }
    elseif ($_cosa == "check")
    {
        // la funzione controlla che il codice a barre non sia già inserito o presente..

        $query = "SELECT * FROM barcode WHERE codbar='$_codbar' limit 1";

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

        //restiuiamo solo il numero righe
        $_check = $result->rowCount();


        if ($_check > "0")
        {
            //articolo esistente
            foreach ($result as $return);

            $return['presenza'] = "SI";
        }
        else
        {
            //via libera..
            $return['presenza'] = "NO";
        }
    }
    elseif ($_cosa == "ultimo")
    {
        //la funzione mi restituische l'ultimo numero del rigo in base all'articolo

        $query = "SELECT * FROM barcode WHERE articolo = '$_articolo' ORDER BY rigo DESC LIMIT 1";
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

        foreach ($result as $dati)
            ;

        $return = $dati['rigo'];
    }
    elseif ($_cosa == "Inserisci")
    {
        //funzione che mi inseriche il codice a barre..

        $query = "INSERT INTO barcode (codbar, articolo, rigo) VALUES ('$_codbar', '$_articolo', '$_rigo')";
        //echo "<br>$query";
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            $_errori['errori'] = "SI";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            $_errori['errori'] = "OK";
        }

        $return = $_errori;
    }
    elseif ($_cosa == "elenco_codice")
    {

        if ($_codbar != "")
        {
            $query = "SELECT * FROM barcode WHERE codbar='$_codbar' ORDER BY articolo, rigo, codbar";

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

            foreach ($result AS $dati)
                ;
            $_articolo = $dati['articolo'];
        }


        $query = "SELECT * FROM barcode WHERE articolo = '$_articolo' ORDER BY articolo, rigo, codbar";
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

        $return = $result;
    }
    elseif ($_cosa == "Aggiorna")
    {

        //funzione che mi aggiorna il codice a barre..

        $query = "UPDATE barcode SET codbar='$_codbar' WHERE articolo ='$_articolo' AND rigo != '1' AND rigo='$_rigo' LIMIT 1";
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $return = "NO";
        }
        else
        {
            $return = "OK";
        }
    }
    elseif ($_cosa == "Elimina")
    {

        $query = "DELETE FROM barcode WHERE codbar='$_codbar' AND articolo ='$_articolo' AND rigo != '1' AND rigo='$_rigo' LIMIT 1";
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
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
        // funzione elenco per articolo

        $query = "SELECT * FROM barcode ORDER BY articolo, rigo, codbar";
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

        $return = $result;
    }





    return $return;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------
/* * Funzione di gestione categorie mercerologiche
 *
 * @global  $conn
 * @global type $_percorso
 * @param type $_cosa sincgolo estrae la singola riga in array
 * @param type $_cosa elenco elenca le categorie per descrizione
 * @param type $_codice
 * @param type $_parametri
 * @return type array
 */

function tabella_catmer($_cosa, $_codice, $_parametri)
{
    global $conn;
    global $_percorso;

    if ($_cosa == "singola_id")
    {

//mi restituisce l'arre singolo
        $query = "SELECT * FROM catmer WHERE id='$_parametri'";

// Esegue la query...
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }


        foreach ($result AS $return);

    }
    elseif ($_cosa == "singola_codice")
    {

//mi restituisce l'arre singolo
        $query = "SELECT * FROM catmer WHERE codice='$_codice'";

// Esegue la query...
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }


        foreach ($result AS $return);

    }
    elseif ($_cosa == "aggiorna_id")
    {

//mi restituisce l'arre singolo
        $query = "UPDATE catmer SET codice='$_codice', catmer = '$_parametri[descrizione]' where id='$_parametri[id]'";

// Esegue la query...
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            $return = "OK";
        }

    }
    elseif ($_cosa == "check_codice")
    {

//mi restituisce l'arre singolo
        $query = "SELECT * FROM catmer where codice='$_codice' OR catmer='$_parametri[descrizione]'";
        
// Esegue la query...
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        if($result->rowCount() > 0)
        {
            $return = "NO";
        }
        else
        {
            $return = "OK";
        }

    }
    elseif($_cosa == "inserisci")
    {
        
        $query = "insert into catmer ( codice, catmer ) VALUES ( '$_codice', '$_parametri[descrizione]')";
        
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
    }
    elseif($_cosa == "elimina_id")
    {
        
        $query = "DELETE FROM catmer where id='$_parametri'";
        
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
        
        
    }
    elseif($_cosa == "Array")
    {
        
        $catmer = tabella_catmer("elenca", $_codice, $_parametri);
        
        //inserisco i dati in un array..
        foreach ($catmer AS $dati2)
        {
            $categoria[$dati2['codice']] = $dati2['catmer'];
        }
        
        if($_codice != "")
        {
            $return = $categoria[$_codice];
        }
        else
        {
            $return = $categoria;
        }
        
        
    }
    elseif ($_cosa == "elenca_select_N")
    {

//mi restituisce l'arre singolo
        $query = "select * from catmer order by catmer";

// Esegue la query...
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

        echo "<select name=\"$_parametri\">\n";
        echo "<option value=\"\"></option>\n";

        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[id]\">$dati[catmer] - $dati[codice]</option>\n";
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "elenca_select")
    {
        //mi restituisce l'arre singolo
        $query = "select * from catmer order by catmer";

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

        echo "<select name=\"$_codice\">\n";
        echo "<option value=\"\"></option>\n";

        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[codice]\">$dati[catmer] - $dati[codice]</option>\n";
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "elenca_select_2")
    {
        echo "<select name=\"$_parametri\">\n";
        
        $query = "select * from catmer where codice='$_codice'";

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
        
        foreach ($result AS $dati);
        
        echo "<option value=\"$dati[codice]\">$dati[catmer] - $dati[codice]</option>\n";
        
        //mi restituisce l'arre singolo
        $query = "select * from catmer order by catmer";

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

        
        echo "<option value=\"\"></option>\n";

        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[codice]\">$dati[catmer] - $dati[codice]</option>\n";
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "elenca")
    {
        $query = sprintf("select * from catmer order by catmer");

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

        $return = $result;
    }
    elseif ($_cosa == "elenca_codice")
    {
        $query = sprintf("select * from catmer order by codice");

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

        $return = $result;
    }
    else
    {
        // Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("select * from catmer order by catmer");

// Esegue la query...
        $return = mysql_query($query, $conn);
    }



    return $return;
}

/**
 * Funzione ricerma elenco modifica tabella clienti
 * @global  $conn
 * @$_cosa singolo estrae l'array della singola riga del fornitore
 * @$_cosa elenco ritorna solo la richiesta della query per poi fare il fetch in ordine di ragsoc
 * @$_cosa singola = estrae l'array del cliente in versione PDO
 * @return <type>
 */
function tabella_clienti($_cosa, $_utente, $_parametri)
{
    global $conn;
    global $_percorso;
//funzione che mi restituisce l'array con tutta la riga del cliente

    if ($_cosa == "singolo")
    {
//mi restituisce l'arre singolo
        $query = "select * from clienti where codice='$_utente' limit 1";

// Esegue la query...
        $res = mysql_query($query, $conn);
        $dati = mysql_fetch_array($res);
    }
    elseif ($_cosa == "singola")
    {
//mi restituisce l'arre singolo
        $query = "select * from clienti where codice='$_utente' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $dati = $result->fetch(PDO::FETCH_ASSOC);
    }
    elseif ($_cosa == "partitaiva")
    {

        $query = "SELECT codfisc, piva from clienti where codice	='$_utente'";


        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result as $dati)
            ;
    }
    elseif ($_cosa == "elenca_select")
    {

        echo "<select name=\"$_utente\">\n";
        echo "<option value=\"\"></option>\n";

        $query = sprintf("select codice, ragsoc from clienti where es_selezione != 'SI' order by ragsoc");
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        echo "<span class=\"tabella_elenco\">";
        foreach ($result as $dati)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati['codice'], $dati['ragsoc'], $dati['codice']);
        }

        echo "</select>\n";
        echo "</td></tr>\n";
    }
    elseif ($_cosa == "elenca_select_singolo")
    {

        echo "<select name=\"$_utente\">\n";
        echo "<option value=\"\"></option>\n";

        $query = "select codice, ragsoc from clienti where codice = '$_parametri'";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        echo "<span class=\"tabella_elenco\">";
        foreach ($result as $dati)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati['codice'], $dati['ragsoc'], $dati['codice']);
        }

        echo "</select>\n";
        echo "</td></tr>\n";
    }
    elseif ($_cosa == "elenca_select_ragsoc")
    {

        echo "<select name=\"$_utente\">\n";
        echo "<option value=\"\"></option>\n";

        $query = sprintf("select codice, ragsoc from clienti where es_selezione != 'SI' order by ragsoc");
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        echo "<span class=\"tabella_elenco\">";
        foreach ($result as $dati)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati['ragsoc'], $dati['ragsoc'], $dati['codice']);
        }

        echo "</select>\n";
        echo "</td></tr>\n";
    }
    else
    {
//mi restituisce l'arre singolo
        $query = "select * from clienti ORDER BY ragsoc";

// Esegue la query...
        $dati = mysql_query($query, $conn);
    }

    return $dati;
}

//-----------------------------------------------------------------------

function tabella_destinazioni($_cosa, $_utente, $_codice, $_parametri)
{
    global $conn;
    global $_percorso;

    if ($_cosa == "singola")
    {

//mi restituisce l'arre singolo
        $query = "SELECT * FROM destinazioni WHERE utente='$_utente' AND codice='$_codice' order by predefinito DESC";

// Esegue la query...
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }


        foreach ($result AS $return);
    }
    
    if($_cosa == "elenca_calce")
    {
        echo "<select name=\"destinazione\">\n";
        
        //verifichiamo se c'è un predefinito
        $query = "select codice, dindirizzo from destinazioni where utente='$_utente' AND predefinito = '1' ";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        if($result->rowCount() > 0)
        {
            foreach ($result AS $dati)
            {
              printf("<option value=\"%s\">%s - %s</option>\n", $dati['codice'], $dati['codice'], $dati['dindirizzo']);  
            }
            
        }
        
        $query = "select codice, dindirizzo from destinazioni where utente='$_utente' order by utente, codice ";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        echo "<option value=\"\"></option>\n";
        echo "<span class=\"tabella_elenco\">";
        foreach ($result as $dati)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati['codice'], $dati['codice'], $dati['dindirizzo']);
        }

        echo "</select>\n";
    }
    
    if($_cosa == "elenca_select")
    {
        echo "<select name=\"$_parametri\">\n";
        
        //verifichiamo se c'è un predefinito
                
        $query = "select codice, dindirizzo, predefinito from destinazioni where utente='$_utente' order by utente, codice ";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        echo "<option value=\"\"></option>\n";
        echo "<span class=\"tabella_elenco\">";
        foreach ($result as $dati)
        {
            printf("<option value=\"%s\">%s - %s - %s</option>\n", $dati['codice'], $dati['codice'], $dati['dindirizzo'], $dati['predefinito']);
        }

        echo "</select>\n";
    }
    
    
    
    return $return;
    
}


function Show_destinazione()
{
	global $conn;
        global $_percorso;
        //echo $_POST['tipo_cf'];

        $query = "SELECT codice, utente, dragsoc, dindirizzo FROM destinazioni WHERE utente = '$_POST[tipo_cf]' ORDER BY codice";
        
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        $_codconto .= '<option value="0">Scegli...</option>';

        foreach ($result AS $dati)
        {
            $_codconto .= '<option value="' . $dati['codice'] . '">' . $dati['codice'] . '-' . $dati['dragsoc'] . '</option>';
        }


    return $_codconto;
}

function Show_cliente()
{
    global $conn;
    global $_percorso;
        $query = "SELECT codice, ragsoc FROM clienti WHERE es_selezione != 'SI' ORDER BY ragsoc";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        $_tipo_conto .= '<option value="0">Scegli il cliente</option>';
        foreach ($result AS $dati)
        {
            $_tipo_conto .= "<option value=\"$dati[codice]\">$dati[ragsoc]</option>\n";
        }
        

	return $_tipo_conto;
}


function Show_suffix()
{
    global $conn;
    
        
        
        $_tipo_conto .= '<option value="0">Scegli il suffisso..</option>';

        for ($index = "A"; $index < "Z"; $index++)
        {
            $_tipo_conto .= "<option value=\"$index\">$index</option>\n";
        }
        
	return $_tipo_conto;
}


function suffisso($_cosa, $_select, $_parametri)
{
    global $conn;
    global $dec;
    global $_percorso;
    global $SUFFIX_DDT;
    
        
    if($_cosa == "select")
    {
        echo "<select name=\"$_select\">\n";
        
        if($SUFFIX_DDT == "")
        {
           echo '<option value="0">Scegli il suffisso..</option>';
        }
        else
        {
            echo "<option value=\"$SUFFIX_DDT\">$SUFFIX_DDT</option>\n";
        }
        

        for ($index = "A"; $index < "Z"; $index++)
        {
            echo "<option value=\"$index\">$index</option>\n";
        }
        
        echo "</select>\n";
        
    }
        
        

}


//-----------------------------------------------------------------------
/**
 * 
 * @global type $conn
 * @param type $_cosa
 * @param type $_percorso
 * @param type $_annoeff
 * @param type $_numeff
 * @param type $_parametri
 * @return string
 */
function tabella_effetti($_cosa, $_percorso, $_annoeff, $_numeff, $_parametri)
{
    global $conn;

    if ($_cosa == "elenco_fatture")
    {
        $query = "select * from fv_testacalce INNER JOIN clienti ON fv_testacalce.utente = clienti.codice where tdoc != 'NOTA CREDITO' AND status = 'stampato' order by anno, ragsoc, ndoc";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $return = $result;
    }
    elseif ($_cosa == "leggi_singolo")
    {
//mi restituisce l'arre singolo
        $query = "SELECT * FROM effetti WHERE annoeff='$_annoeff' AND numeff='$_numeff' limit 1";


        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        //retiruiamo solo il numero..
        foreach ($result AS $return)
            ;
    }
    elseif ($_cosa == "check_singolo")
    {
//mi restituisce l'arre singolo
        $query = "SELECT * FROM effetti WHERE annoeff='$_annoeff' AND numeff='$_numeff' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        //restiuiamo solo il numero righe
        $return = $result->rowCount();
    }
    else if ($_cosa == "singolo")
    {
        //mi restituisce l'arre singolo
        $query = "SELECT * FROM effetti WHERE annoeff='$_annoeff' AND numeff='$_numeff' limit 1";

        // Esegue la query...
        $res = mysql_query($query, $conn);
        $return = mysql_fetch_array($res);
    }
    elseif ($_cosa == "singola")
    {
//mi restituisce l'arre singolo
        
        if($_parametri == "data_us")
        {
            $query = "SELECT * FROM effetti WHERE numeff='$_numeff' and annoeff='$_annoeff'";
        }
        else
        {
            $query = "SELECT *, date_format(dataeff, '%d-%m-%Y') as dataeff, date_format(scadeff, '%d-%m-%Y') AS scadeff, date_format(datapag, '%d-%m-%Y') AS datapag, date_format(datadoc, '%d-%m-%Y') AS datadoc, date_format(datadist, '%d-%m-%Y') AS datadist FROM effetti WHERE numeff='$_numeff' and annoeff='$_annoeff'";
        }
        


        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        //retiruiamo solo il numero..
        foreach ($result AS $return)
            ;
    }
    elseif ($_cosa == "leggi_distinta")
    {
//mi restituisce l'arre singolo
        $query = "SELECT * FROM effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status !='chiuso' and datadist LIKE '$_parametri[anno]%' AND tipoeff = '3' AND presenta = 'SI' AND ndistinta='$_parametri[ndistinta]' ORDER BY numeff, annoeff DESC";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        $return = $result;
    }
    elseif ($_cosa == "ultimo_numero")
    {

        $query = "SELECT numeff FROM effetti where annoeff='$_annoeff' ORDER BY numeff DESC LIMIT 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        //retiruiamo solo il numero..
        foreach ($result AS $dati)
            ;
        $return = $dati['numeff'] + 1;
    }
    elseif ($_cosa == "ultima_distinta")
    {
        $query = "SELECT ndistinta FROM effetti where datadist LIKE '$_parametri[datadist]%' ORDER BY ndistinta DESC LIMIT 1";
        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        //estraiamo i dati..
        foreach ($result as $dati)
            ;

        $return = $dati['ndistinta'] + 1;
    }
    elseif ($_cosa == "inserisci_singolo")
    {

        $query = "INSERT INTO effetti( tipoeff, annoeff, numeff, dataeff, scadeff, impeff, tipodoc, annodoc, suffixdoc, numdoc, datadoc,
            totdoc, codcli, modpag, bancapp, abi, cab, cin, cc, status, datapag, contabilita )
            values( '$_parametri[trata]', '$_annoeff', '$_numeff', '$_parametri[dataeff]', '$_parametri[scadeff]', '$_parametri[impeff]', '$_parametri[tdoc]', '$_parametri[anno]', '$_parametri[suffix]', '$_parametri[ndoc]', '$_parametri[datareg]',
            '$_parametri[totdoc]', '$_parametri[codcli]', '$_parametri[modpag]', '$_parametri[bancapp]', '$_parametri[abi]', '$_parametri[cab]', '$_parametri[cin]', '$_parametri[cc]', '$_parametri[status]', '$_parametri[datapag]', '$_parametri[contabilita]' )";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "errore";
            $restun['descrizione'] = "<br> Errore durante inserimento effetto n.  = " . $_numeff;
        }
        else
        {
            // diamo l'ok per il risultato..

            $return['descrizione'] = "<br> inserimento effetto n.  = " . $_numeff;
        }
    }
    elseif ($_cosa == "aggiorna_distinta")
    {

        $query = "UPDATE effetti SET ndistinta='$_parametri[ndistinta]', datadist='$_parametri[datadist]', bancadist='$_parametri[bancadist]', status='$_parametri[status]', presenta = '$_parametri[presenta]', tipo_pres='$_parametri[tipo_pres]' where annoeff='$_annoeff' AND numeff='$_numeff' LIMIT 1";

        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            $return = "ok";
        }
    }
    elseif ($_cosa == "libera_distinta")
    {

        $query = "UPDATE effetti SET ndistinta='null', datadist='null', bancadist='null', status='inserito', presenta = 'NO' where ndistinta='$_parametri[ndistinta]' AND datadist LIKE '$_parametri[datadist]%'";

        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            $return = "ok";
        }
    }
    elseif ($_cosa == "elenco_distinta")
    {
        // Stringa contenente la query di ricerca... 
        if($_parametri['azione'] == "modifica")
        {
            $query = "select annoeff, ndistinta, datadist, bancadist, banca, status from effetti INNER JOIN banche ON effetti.bancadist = banche.codice where contabilita != '$_parametri[contabilita]' AND datadist LIKE '$_parametri[anno]%' GROUP BY ndistinta order by ndistinta desc ";
        }
        else
        {
            $query = "select annoeff, ndistinta, datadist, bancadist, banca, status from effetti INNER JOIN banche ON effetti.bancadist = banche.codice where datadist LIKE '$_parametri[anno]%' GROUP BY ndistinta order by ndistinta desc ";
        }
        
        // Esegue la query...
        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $return = $result;
    }
    elseif ($_cosa == "elenco_effetti_liberi")
    {

        $query = "select * from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status !='saldato' AND tipoeff = '3' AND presenta = 'NO' ORDER BY impeff asc";

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $return = $result;
    }
    elseif ($_cosa == "elimina_distinta")
    {

        $query = "DELETE FROM effetti WHERE ndistinta='$_parametri[ndistinta]' AND datadist LIKE '$_parametri[datadist]%'";
        ;

        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            $return = "ok";
        }
    }
    elseif($_cosa == "selezione_elenco")
    {
             //Ora vediamo se è numerico
        if (is_numeric($_parametri['tipo']))
        {
            if ($_parametri['data_fine'] != "0000-00-00")
            {
                $query = "select *, date_format(scadeff, '%d-%m-%Y') AS scadenza, date_format(datadoc, '%d-%m-%Y') AS documento from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where tipoeff='$_parametri[tipo]' AND status !='saldato' AND scadeff >= '$_parametri[data_start]' AND scadeff <= '$_parametri[data_fine]' order by scadeff ASC, ragsoc";
            }
            else
            {
                $query = "select *, date_format(scadeff, '%d-%m-%Y') AS scadenza, date_format(datadoc, '%d-%m-%Y') AS documento from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where tipoeff='$_parametri[tipo]' AND status !='saldato' AND scadeff >= '$_parametri[data_start]' order by scadeff ASC, ragsoc";
            }
        }
        elseif ($_parametri['tipo'] == "tutte")
        {
            if ($_parametri['data_fine'] != "0000-00-00")
            {
                $query = "select *, date_format(scadeff, '%d-%m-%Y') AS scadenza, date_format(datadoc, '%d-%m-%Y') AS documento from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status !='saldato' AND scadeff >= '$_parametri[data_start]' AND scadeff <= '$_parametri[data_fine]' order by scadeff ASC, ragsoc";
            }
            else
            {
                $query = "select *, date_format(scadeff, '%d-%m-%Y') AS scadenza, date_format(datadoc, '%d-%m-%Y') AS documento from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status !='saldato' AND scadeff >= '$_parametri[data_start]' order by scadeff ASC, ragsoc";
            }
        }
        else
        {
            if ($_parametri['data_fine'] != "0000-00-00")
            {
                $query = "select *, date_format(scadeff, '%d-%m-%Y') AS scadenza, date_format(datadoc, '%d-%m-%Y') AS documento from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status='$_parametri[tipo]' AND status !='saldato' AND scadeff >= '$_parametri[data_start]' AND scadeff <= '$_parametri[data_fine]' order by scadeff ASC, ragsoc";
            }
            else
            {
                $query = "select *, date_format(scadeff, '%d-%m-%Y') AS scadenza, date_format(datadoc, '%d-%m-%Y') AS documento from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status='$_parametri[tipo]' AND status !='saldato' AND scadeff >= '$_parametri[data_start]' order by scadeff ASC, ragsoc";
            }
        }
        
        $result = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
	}
        
        $return = $result;
        
    }
    elseif($_cosa == "ricerca_eff")
    {
        if($_parametri['sospesi'] == "SI")
        {
            $query = "select *, date_format(datadoc, '%d-%m-%Y') as datafatt, date_format(scadeff, '%d-%m-%Y') as scadeff from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status != 'saldato' AND $_parametri[campi] like '$_parametri[descrizione]' order by datadoc DESC";
        }
        else
        {
            $query = "select *, date_format(datadoc, '%d-%m-%Y') as datafatt, date_format(scadeff, '%d-%m-%Y') as scadeff from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where $_parametri[campi] like '$_parametri[descrizione]' order by datadoc DESC";
        }
        
        
        $result = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
	}
        
        $return = $result;
    }
    elseif($_cosa == "elenco_presentati")
    {
        $query = "select * from effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where status ='presentato' AND presenta='SI' order by annoeff, scadeff ASC, bancadist, ragsoc";
        
        $result = $conn->query($query);
	if ($conn->errorCode() != "00000")
	{
		$_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
	}
        
        $return = $result;
    }
    else
    {

        $query = "select * from fv_testacalce INNER JOIN clienti ON fv_testacalce.utente = clienti.codice where tdoc != 'NOTA CREDITO' AND status = 'stampato' order by anno, ragsoc, ndoc";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
    }

    return $return;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * 
 * @global type $conn
 * @param type $_cosa
 * @param type $_percorso
 * @param type $_anno
 * @param type $_ndoc
 * @param type $_parametri
 * @return string
 */
function tabella_fatture($_cosa, $_percorso, $_anno, $_suffix, $_ndoc, $_parametri)
{
    global $conn;


    if ($_cosa == "leggi_singola_testata")
    {

        $query = "SELECT * from fv_testacalce WHERE anno='$_anno' AND suffix='$_suffix' AND ndoc='$_ndoc' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        //passo il risultato della linea al sistema
        foreach ($result AS $return)
            ;
    }
    elseif ($_cosa == "aggiorna_status")
    {
        $query = "UPDATE fv_testacalce SET status='$_parametri[status]', tdocevaso='$_parametri[tdocevaso]', evasonum='$_parametri[evasonum]', evasoanno='$_parametri[evasoanno]' where anno='$_anno' AND suffix='$_suffix' and ndoc='$_ndoc' and utente='$_parametri[utente]'";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            $return = "Ok";
        }
    }
    else
    {
        
    }


    return $return;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Funzione ricerma elenco modifica tabella fornitori
 * @global  $conn
 * @param <type> $_cosa singolo estrae l'array della singola riga del fornitore
 * @param <type> $_cosa singola_parametri estrae l'array della singola riga del fornitore dove $_parametri
 * @param <type> $_cosa elenco ritorna solo la richiesta della query per poi fare il fetch in ordine di ragsoc
 * @param <type> $_utente
 * @return array $dati con all'interno le cosa richieste.
 */
function tabella_fornitori($_cosa, $_utente, $_parametri)
{
    global $conn;
    global $_percorso;


    if ($_cosa == "singola")//restituisce array con la riga del fornitore..
    {

        $query = "select * from fornitori where codice='$_utente' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Fornitore trovato";
        }
        else
        {

            foreach ($result AS $dati)
                ;

            $return = $dati;
        }
    }
    elseif ($_cosa == "singola_parametri")//restituisce array con la riga del fornitore..
    {

        $query = "select * from fornitori where $_parametri'%$_utente%' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Fornitore trovato";
        }
        else
        {

            foreach ($result AS $dati)
                ;

            $return = $dati;
        }
    }
    elseif ($_cosa == "singolo")//restituisce array con la riga del fornitore..
    {

        $query = "select * from fornitori where codice='$_utente' limit 1";

        $res = mysql_query($query, $conn);

        if (mysql_num_rows($res) < 1)
        {
            //errore connessione..
            //provo a scrivere l'errore nel log..
            $_errori['descrizione'] = "errore query $query";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $dati['errori'] = "Nessun Frornitore trovato";
        }
        else
        {
            $return = mysql_fetch_array($res);
        }
    }
    elseif ($_cosa == "singola_piva")//restituisce array con la riga del fornitore..
    {

        $query = "select * from fornitori where piva='$_utente' limit 1";

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        if ($result->num_rows < 1)
        {
            $dati['errori'] = "Nessun Frornitore trovato";
        }
        else
        {
            $return = $result->fetch(PDO::FETCH_ASSOC);
        }
    }
    elseif ($_cosa == "elenco_select")
    {

//mi restituisce l'arre singolo
        $query = "select codice, ragsoc from fornitori where es_selezione != 'SI' ORDER BY ragsoc";

// Esegue la query...
        $res = mysql_query($query, $conn);

        echo "<select name=\"$_utente\">\n";
        echo "<option value=\"\"></option>\n";

        while ($dati = mysql_fetch_array($res))
        {
            echo "<option value=\"$dati[codice]\">$dati[ragsoc]</option>\n";
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "elenca_select")
    {

        echo "<select name=\"$_utente\">\n";
        echo "<option value=\"\"></option>\n";

        $query = sprintf("select codice, ragsoc from fornitori where es_selezione != 'SI' order by ragsoc");
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

        echo "<span class=\"tabella_elenco\">";
        foreach ($result as $dati)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati['codice'], $dati['ragsoc'], $dati['codice']);
        }

        echo "</select>\n";
        echo "</td></tr>\n";
    }
    elseif ($_cosa == "elenca_select_2")
    {

        echo "<select name=\"$_parametri\">\n";
        
        $query = "select * from fornitori where codice='$_utente' limit 1";
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
        
        foreach ($result as $dati);
        
        printf("<option value=\"%s\">%s - %s</option>\n", $dati['codice'], $dati['ragsoc'], $dati['codice']);
        
        echo "<option value=\"\"></option>\n";

        //seconda query
        $query = sprintf("select codice, ragsoc from fornitori where es_selezione != 'SI' order by ragsoc");
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

        foreach ($result as $dati1)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati1['codice'], $dati1['ragsoc'], $dati1['codice']);
        }

        echo "</select>\n";
        
    }
    elseif ($_cosa == "partitaiva")
    {

        $query = "SELECT piva, codfisc, spesometro from fornitori where codice ='$_utente'";
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

        foreach ($result as $return)
            ;
    }
    elseif ($_cosa == "elenca")
    {
        //restituisce l'elenco dei fornitori in ordine di ragione sociale
        $query = "select * from fornitori ORDER BY ragsoc";

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

        $return = $result;
    }
    elseif ($_cosa == "elenca_select_ragsoc")
    {

        echo "<select name=\"$_utente\">\n";
        echo "<option value=\"\"></option>\n";

        $query = sprintf("select codice, ragsoc from fornitori where es_selezione != 'SI' order by ragsoc");
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

        echo "<span class=\"tabella_elenco\">";
        foreach ($result as $dati)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati['ragsoc'], $dati['ragsoc'], $dati['codice']);
        }

        echo "</select>\n";
        echo "</td></tr>\n";
    }
    else
    {
        //restituisce l'elenco dei fornitori in ordine di ragione sociale
        $query = "select * from fornitori ORDER BY ragsoc";

        $return = mysql_query($query, $conn);
    }

    return $return;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------
//funzione pagamenti.. richiedo l'elenco oppure il singolo in lista o in select
function tabella_imballi($_cosa, $_imballo, $_parametri)
{
    global $conn;
    global $_percorso;

    if ($_cosa == "elenca_select")
    {
        // elenco le modalita di pagamento per una ventuale variazione
        $query = sprintf("select * from imballi order by imballo");

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

        foreach ($result as $dati)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati['imballo'], $dati['imballo']);
        }
    }
}

//-------------------------
/**
 *
 * @global  $conn
 * @param <type> $_cosa Inserisci, inserisce un record
 * @param <type> $_cosa singolo restituisce l'array finito con la riga
 * @param <type> $_anno obbligatorio
 * @param <type> $_periodo obbligatorio
 * @param <type> $_parametri Array contente tutti i parametri da inserire
 */
function tabella_liquid_iva_periodica($_cosa, $_anno, $_periodo, $_parametri)
{
    global $conn;
    global $_percorso;

    if ($_cosa == "inserisci")
    {
        $query = "INSERT INTO liquid_iva_periodica (anno, periodo, iva_acq, iva_vend, diff_periodo, cred_residuo, val_liquid, versato, banca, data_vers, n_reg, cod_tributo)
            VALUES ('$_anno', '$_periodo', '$_parametri[iva_acq]', '$_parametri[iva_vend]', '$_parametri[diff_periodo]', '$_parametri[cred_residuo]', '$_parametri[val_liquid]', '$_parametri[versato]',
            '$_parametri[banca]', '$_parametri[data_vers]', '$_parametri[n_reg]', '$_parametri[cod_tributo]')";

        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['descrizione'] = $_errori['descrizione'];
            $return['query'] = $query;
            $return['result'] = "NO";
        }
        
        
        
    }
    elseif ($_cosa == "elenco_aperte")
    {

        $query = "SELECT * FROM liquid_iva_periodica WHERE versato != 'SI' ORDER BY anno, periodo";

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

        $return = $result;
    }
    elseif ($_cosa == "singola")
    {
        $query = "SELECT * FROM liquid_iva_periodica where anno='$_anno' AND periodo = '$_periodo'";

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['result'] = "NO";
        }

        $return = $result->fetch(PDO::FETCH_ASSOC);
        
        
    }
    elseif ($_cosa == "aggiornamento_liquid")
    {
        $query = "UPDATE liquid_iva_periodica set versato='SI', banca='$_parametri[banca]', data_vers='$_parametri[data_vers]', n_reg='$_parametri[n_reg]' WHERE anno='$_anno' AND periodo='$_periodo' limit 1";

        $result = $conn->exec($query);
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

    }
    elseif ($_cosa == "elenco_anno")
    {
        //elenco
        $query = "SELECT *, DATE_FORMAT(data_vers, '%d-%m-%Y') AS versamento FROM liquid_iva_periodica where anno='$_anno' ORDER BY anno, periodo";
        $return = mysql_query($query, $conn);
    }
    elseif ($_cosa == "elenca_anno")
    {
        //elenco
        $query = "SELECT *, DATE_FORMAT(data_vers, '%d-%m-%Y') AS versamento FROM liquid_iva_periodica where anno='$_anno' ORDER BY anno, periodo";
        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati = "errore";
        }
        
        $return = $result;
        
    }
    elseif($_cosa == "anni_presenti")
    {
        $query = "SELECT * FROM liquid_iva_periodica GROUP BY anno ORDER BY anno, periodo";

        $return = mysql_query($query, $conn);
    }
    elseif ($_cosa == "elenco_inner_banca")
    {
        $query = "SELECT * FROM liquid_iva_periodica INNER JOIN banche ON liquid_iva_periodica.banca=banche.codice ORDER BY anno, periodo";

        $return = mysql_query($query, $conn);
    }
    else
    {
        $query = "SELECT * FROM liquid_iva_periodica where ORDER BY anno, periodo";
        $return = mysql_query($query, $conn);
    }


    return $return;
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------

/* * Funzione che mi gestisce la tabella dei prezzi..
 *
 * @global  $conn
 * @global type $_parcorso
 * @param type $_cosa
 * @param type $_codice
 * @param type $_nlv
 * @param type $_parametri
 * @return type 
 */

function tabella_listini($_cosa, $_codice, $_nlv, $_parametri)
{
    global $conn;
    global $nlv; // vediamo quanti listini ci sono
    global $_percorso;

    //funzione che mi restituisce l'array con tutta la riga della tabella

    if ($_cosa == "singolo")
    {
        $query = "select * from listini where rigo=\"$_nlv\" and codarticolo=\"$_codice\" LIMIT 1";

        $res = mysql_query($query, $conn);

        //verifico se ha trovato qualcosa..
        if (mysql_num_rows($res) < 1)
        {
            //errore connessione..
            //provo a scrivere l'errore nel log..
            $_errori['descrizione'] = "errore query $query";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);

            $dati['errori'] = "Nessun Articolo trovato";
        }
        else
        {
            $dati = mysql_fetch_array($res);
        }
    }
    elseif ($_cosa == "singola")
    {

        $query = "select * from listini where rigo=\"$_nlv\" and codarticolo=\"$_codice\" LIMIT 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Listino trovato";
        }
        else
        {
            if ($result->rowCount() > 0)
            {
                foreach ($result AS $dati)
                    ;
                $dati['presenza'] = "SI";
            }
            else
            {
                $dati['presenza'] = "NO";
            }
        }
    }
    elseif ($_cosa == "Inserisci")
    {
        for ($_nlv = 1; $_nlv <= $nlv; $_nlv++)
        {
            $query = sprintf("insert into listini( codarticolo, listino, rigo) values( \"%s\", \"%s\", \"%s\")", $_codice, $_parametri["listino$_nlv"], $_nlv);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                echo "Si &egrave; verificato un errore nella query listino $_nlv:<br>\n\"$query\"\n";
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore.anagrafiche.php";
                scrittura_errori($_cosa, $_percorso, $_errori);

                $dati['errori'] = "Errore Inserimento prezzi listino $_nlv";
            }
        }
    }
    elseif ($_cosa == "Aggiorna")
    {
        //funzione che mi aggiorna i listini vendita.
        // inserisco i prezzi articoli nel listino
        // inserisco i prezzi articoli nel listino
        for ($_nlv = 1; $_nlv <= $nlv; $_nlv++)
        {

            // cerco l'artciolo se c'e lo aggiorno altrimenti lo inserisco
            $query = sprintf("SELECT * FROM listini WHERE codarticolo=\"%s\" AND rigo=\"%s\"", $_codice, $_nlv);

            $result = $conn->query($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore.anagrafiche.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $dati['errori'] = "Nessun Articolo trovato";
            }

            // La query e' stata eseguita con successo...
            // Non so' se ci sono articoli con quel codice...
            if ($result->rowCount() > 0)
            {
                $query = sprintf("UPDATE listini SET listino=\"%s\" WHERE codarticolo=\"%s\" AND rigo=\"%s\"", $_parametri["listino$_nlv"], $_codice, $_nlv);

                $result = $conn->exec($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    echo "Si &egrave; verificato un errore nella query listino $_nlv:<br>\n\"$query\"\n";

                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "motore.anagrafiche.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                    $dati['errori'] = "Errore Agguirnamento prezzi listino $_nlv";
                }
            }
            else
            {
                $query = sprintf("insert into listini( codarticolo, listino, rigo) values( \"%s\", \"%s\", \"%s\")", $_codice, $_parametri["listino$_nlv"], $_nlv);

                $result = $conn->exec($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    echo "Si &egrave; verificato un errore nella query listino $_nlv:<br>\n\"$query\"\n";

                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "motore.anagrafiche.php";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                    $dati['errori'] = "Errore Inserimento prezzi listino $_nlv";
                }
            }
        }// chiudo for
    }
    elseif ($_cosa == "agg_singolo")
    {

        // cerco l'artciolo se c'e lo aggiorno altrimenti lo inserisco
        $query = sprintf("SELECT * FROM listini WHERE codarticolo=\"%s\" AND rigo=\"%s\"", $_codice, $_nlv);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Articolo trovato";
        }

        // La query e' stata eseguita con successo...
        // Non so' se ci sono articoli con quel codice...
        if ($result->rowCount() > 0)
        {
            $query = sprintf("UPDATE listini SET listino=\"%s\" WHERE codarticolo=\"%s\" AND rigo=\"%s\"", $_parametri['listino'], $_codice, $_nlv);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                echo "Si &egrave; verificato un errore nella query listino $_nlv:<br>\n\"$query\"\n";

                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore.anagrafiche.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $dati['errori'] = "Errore aggiornamento  prezzi listino $_nlv";
            }
        }
        else
        {
            $query = sprintf("insert into listini( codarticolo, listino, rigo) values( \"%s\", \"%s\", \"%s\")", $_codice, $_parametri['listino'], $_nlv);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                echo "Si &egrave; verificato un errore nella query listino $_nlv:<br>\n\"$query\"\n";

                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "motore.anagrafiche.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                $dati['errori'] = "Errore Inserimento prezzi listino $_nlv";
            }
        }
    }
    elseif ($_cosa == "duplica")
    {

        //funzione che mi dupplica in automatico i prezzi nel listino..
        //pensavo di leggerli e poi ad uno uno aggiornarli e se non ci sono inserirli..
        //ciclo di for..
        for ($_nlv = 1; $_nlv <= $nlv; $_nlv++)
        {
            //leggiamo singolo
            $leggo = tabella_listini("singola", $_codice, $_nlv, "");

            //aggiungiamo il codice destinazione e variamo la lettura
            //inseriamo il singolo
            $dati = tabella_listini("agg_singolo", $_parametri, $_nlv, $leggo);
        }
    }
    else
    {
//mi restituisce l'arre singolo
        $query = "select * from listini ORDER BY rigo, codarticolo";

// Esegue la query...
        $dati = mysql_query($query, $conn);
    }

    return $dati;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------

/* * Funzione che mi ricerca cosa nel magazzino come ultimi muovimenti articolo
 *
 * @global $conn $conn
 * @global type $_percorso
 * @param type $_cosa
 * @param type $_codice
 * @param type $_parametri
 * @return array contente la richiesta
 */

function tabella_magazzino($_cosa, $_tdoc, $_anno, $_suffix, $_ndoc, $_datareg, $_tut, $_rigo, $_utente, $_codice, $_parametri)
{
    global $conn;
    global $_percorso;
    global $dec;


    if ($_cosa == "prendi_anno")
    {
        $query = "SELECT anno FROM magazzino WHERE tut = 'giain' ORDER BY anno LIMIT 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "Nessun Listino trovato";
        }
        else
        {
            foreach ($result AS $_dati)
                ;
            $return = $_dati['anno'];
        }
    }
    
    if ($_cosa == "elenco_anni")
    {

        $query = "select anno from magazzino group by anno order by anno DESC";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query ultima_vendita = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "Nessun Muovimento trovato";
        }
        
        echo "<select name=\"$_parametri\">\n";
        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[anno]\">$dati[anno]</option>";
        }
        
        $query = "select anno from magastorico group by anno order by anno DESC";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query ultima_vendita = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "Nessun Muovimento trovato";
        }
        
        foreach ($result AS $dati2)
        {
            echo "<option value=\"$dati2[anno]\">$dati2[anno]</option>";
        }
        
        echo "<option value=\"%\">Tutti gli anni vecchi</option>";
        echo "</select>\n";

 
    }

    if ($_cosa == "muov_acquisto")
    {
        $query = "SELECT * FROM magazzino INNER JOIN fornitori ON magazzino.utente=fornitori.codice WHERE articolo='$_codice' AND anno >='$_anno' AND tdoc='ddtacq' order by datareg DESC LIMIT $_parametri";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "Nessun Muovimento trovato";
        }
        else
        {
            $return = $result;
        }
    }

    if ($_cosa == "muov_acquisto_vecchio")
    {
        $query = "SELECT * FROM magastorico INNER JOIN fornitori ON magastorico.utente=fornitori.codice WHERE articolo='$_codice' AND anno >='$_anno' AND tdoc='ddtacq' order by datareg DESC LIMIT $_parametri";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "Nessun Muovimento trovato";
        }
        else
        {
            $return = $result;
        }
    }

    if ($_cosa == "muov_vendita")
    {
        $query = "SELECT * FROM magazzino INNER JOIN clienti ON magazzino.utente=clienti.codice WHERE articolo='$_codice' AND anno >='$_anno' AND tut='c' ORDER BY datareg DESC LIMIT $_parametri";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "Nessun Muovimento trovato";
        }
        else
        {
            $return = $result;
        }
    }

    if ($_cosa == "muov_vendita_vecchio")
    {
        $query = "SELECT * FROM magastorico INNER JOIN clienti ON magastorico.utente=clienti.codice WHERE articolo='$_codice' AND anno >='$_anno' AND tut='c' ORDER BY datareg DESC LIMIT $_parametri";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "Nessun Muovimento trovato";
        }
        else
        {
            $return = $result;
        }
    }

    if ($_cosa == "ultima_vendita")
    {

        $query = "select * from magazzino where tut='c' AND utente='$_parametri' and articolo='$_codice' order by anno, datareg ASC limit 1";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query ultima_vendita = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "Nessun Muovimento trovato";
        }

        foreach ($result AS $maga)
            ;

        @$return = $maga['valorevend'] / $maga['qtascarico'];
    }


    if ($_cosa == "elimina_documento")
    {
        $query = sprintf(" delete from magazzino where tdoc=\"%s\" and anno=\"%s\" AND suffix=\"%s\" and ndoc=\"%s\"", $_tdoc, $_anno, $_suffix, $_ndoc);

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
//aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "Nessun Muovimento trovato";
        }
        else
        {
            $return['errori'] = "OK";
        }
    }

    if ($_cosa == "inserisci_singola")
    {

        $query = "insert into magazzino( tdoc, anno, suffix, ndoc, datareg, tut, rigo, utente, articolo, qtacarico, valoreacq, qtascarico, valorevend, ddtfornitore, fatturacq, protoiva, status )
				 values( '$_tdoc', '$_anno','$_suffix' ,'$_ndoc', '$_datareg', '$_tut', '$_rigo', '$_utente', '$_codice', '$_parametri[qtacarico]', '$_parametri[valoreacq]','$_parametri[qtascarico]',
						'$_parametri[valorevend]','$_parametri[ddtfornitore]', '$_parametri[fatturacq]', '$_parametri[protoiva]','$_parametri[status]')";

        #echo $query;
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
//aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return['errori'] = "NO";
        }
        else
        {
            $return['errori'] = "OK";
        }
    }
    
    if($_cosa == "calcola_giacenze")
    {
        //passiamo le giacenze iniziali in un array
        if($_tut == "tipart")
        {
            $query = "SELECT anno, tipart, SUM(qtacarico) AS carico, SUM(valoreacq) AS partenza FROM $_parametri INNER JOIN articoli ON $_parametri.articolo=articoli.articolo WHERE tut = 'giain' AND anno='$_anno' GROUP BY tipart ORDER BY tipart";
        }
        else
        {
            $query = "SELECT anno, catmer, SUM(qtacarico) AS carico, SUM(valoreacq) AS partenza FROM $_parametri INNER JOIN articoli ON $_parametri.articolo=articoli.articolo WHERE tut = 'giain' AND anno='$_anno' GROUP BY catmer ORDER BY catmer";
        }
        

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            if ($_tut == "tipart")
            {
                foreach ($result AS $dati)
                {
                    $return['valore'][$dati['tipart']] = $dati['partenza'];
                    $return['quantita'][$dati['tipart']] = $dati['carico'];
                }
            }
            else
            {
                foreach ($result AS $dati)
                {
                    $return['valore'][$dati['catmer']] = $dati['partenza'];
                    $return['quantita'][$dati['catmer']] = $dati['carico'];
                }
            }
        }
    }

    return $return;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------

function tabella_pagamenti($_cosa, $_codpag, $_parametri)
{
    global $conn;
    global $_percorso;

    if ($_cosa == "singolo")
    {
//mi restituisce l'arre singolo
        $query = "select * from pagamenti where codice='$_codpag' limit 1";

// Esegue la query...
        $res = mysql_query($query, $conn);
        $dati = mysql_fetch_array($res);
    }
    elseif ($_cosa == "singola")
    {
//mi restituisce l'arre singolo
        $query = "select * from pagamenti where codice='$_codpag' limit 1";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Muovimento trovato";
        }

        foreach ($result AS $dati);
        
    }
    elseif ($_cosa == "desc_singola_select")
    {
        $query = sprintf("select descrizione from pagamenti where codice=\"%s\"", $_codpag);
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Muovimento trovato";
        }

        foreach ($result AS $dati1)
            ;

        printf("<option value=\"%s\">%s</option>\n", $_codpag, $dati1['descrizione']);
    }
    elseif ($_cosa == "elenca_select")
    {
        // elenco le modalita di pagamento per una ventuale variazione
        $query = sprintf("select codice, descrizione from pagamenti order by descrizione");

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Muovimento trovato";
        }

        foreach ($result AS $dati1)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati1['codice'], $dati1['descrizione']);
        }
    }
    elseif ($_cosa == "desc_singolo")
    {
        $query = sprintf("select descrizione from pagamenti where codice=\"%s\"", $_codpag);
        $res = mysql_query($query, $conn);
        $dati1 = mysql_fetch_array($res);
        echo "$dati1[descrizione]\n";
    }
    elseif ($_cosa == "elenca_select_2")
    {

        echo "<select name=\"$_parametri\">\n";
        
        $query = sprintf("select codice, descrizione from pagamenti where codice=\"%s\"", $_codpag);
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
        
        foreach ($result as $dati);
        
        printf("<option value=\"%s\">%s</option>\n", $dati['codice'], $dati['descrizione']);
        
        echo "<option value=\"\"></option>\n";

        //seconda query
        $query = sprintf("select codice, descrizione from pagamenti order by descrizione");
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

        foreach ($result as $dati1)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati1['codice'], $dati1['descrizione']);
        }

        echo "</select>\n";
        //echo "</td></tr>\n";
    }
    elseif($_cosa == "elenca_risultato")
    {
        
        $_descrizione = "%$_parametri[descrizione]%";
        
        $query = "select * from pagamenti where $_parametri[campi] like '$_descrizione' order by codice";
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
        
        $dati = $result;
        
        
    }
    else
    {
//mi restituisce l'arre singolo
        $query = "select * from pagamenti order by descrizione";

// Esegue la query...
        $dati = mysql_query($query, $conn);
    }
    return $dati;
}

//------------------------------------------------------------------------------

function tabella_prezzi_cliente($_cosa, $_utente, $_articolo, $_parametri)
{
    global $conn;
    global $_percorso;
    global $dec;


    if ($_cosa == "singola")
    {
        $query = sprintf("select * from prezzi_cliente where codarticolo=\"%s\" and cliente=\"%s\" LIMIT 1 ", $_articolo, $_utente);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Muovimento trovato";
        }

        if ($result->rowCount() > "0")
        {
            foreach ($result AS $return);
            $return['presenza'] = "SI";
        }
        else
        {
            $return['presenza'] = "NO";
        }
    }
    elseif($_cosa == "elenco_stampa")
    {
        
        
        $query = "SELECT codarticolo, descrizione, prezzi_cliente.listino, ragsoc, codice FROM prezzi_cliente INNER JOIN clienti ON prezzi_cliente.cliente = clienti.codice ORDER BY ragsoc";
        
        
        
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        $return = $result;
        
    }
    elseif($_cosa == "elenca_per_cli")
    {
        $query = "SELECT * FROM prezzi_cliente WHERE cliente = '$_utente' ORDER BY codarticolo ";
        
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        $return = $result;
        
    }
    elseif($_cosa == "inserisci")
    {
        //cerco la riga se c'� l'aggiorno, se non c'� la inserisco
	$query = "SELECT * FROM prezzi_cliente WHERE codarticolo='$_articolo' AND cliente='$_utente'";
        
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        if($result->rowCount() > 0)
	{
	    echo "<td>Codice $_articolo Gi&agrave; presente nell'archivio</td>";
	}
	else
	{
            $_descrizione = addslashes($_parametri['descrizione']);
            $_listino = $_parametri['listino'];
            
            $query = "INSERT INTO prezzi_cliente ( codarticolo, descrizione, listino, cliente ) VALUES ( '$_articolo', '$_descrizione', '$_listino', '$_utente')";
	    //esegue la query
	    $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
                $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                scrittura_errori($_cosa, $_percorso, $_errori);
                 $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
            
            
        }
    }
    elseif($_cosa == "modifica")
    {
        $_descrizione = addslashes($_parametri['descrizione']);
        
	$query = "UPDATE prezzi_cliente SET descrizione='$_descrizione', listino='$_parametri[listino]' WHERE codarticolo='$_articolo' AND cliente='$_utente'";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore.anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Muovimento trovato";
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
        
        
    }
    elseif($_cosa == "elimina")
    {
        // prendo le variabili passate
	//cerco la riga se c'� l'aggiorno, se non c'� la inserisco
	$query = sprintf("DELETE FROM prezzi_cliente WHERE codarticolo=\"%s\" AND cliente=\"%s\"", $_articolo, $_cliente);
	//esegue la query
        
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Muovimento trovato";
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
        
        
    }
    else
    {
        //mettiamo sempre un elenco.
        $query = "SELECT * FROM prezzi_cliente";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['errori'] = "Nessun Muovimento trovato";
        }

        $return = $result;
    }





    return $return;
}

//---------------------------------------------------------------------------------------
/** funzione che mi gestisce le scadenze in acquisto
 * 
 * @global type $conn
 * @param type $_cosa
 * @param type $_percorso
 * @param type $_parametri
 * @return type
 */
function tabella_scadenziario($_cosa, $_percorso, $_parametri)
{

    global $conn;

    if ($_cosa == "Inserisci")
    {
//funzione che mi inserisci i dati nello scadenziario..

        $query = "INSERT INTO scadenziario (anno, `data_scad`, `descrizione`, `importo`, `utente`, `anno_doc`, `ndoc`, `data_doc`, `anno_proto`,
            `nproto`, `codpag`, `banca`, `impeff`, status, data_pag, note) VALUES
            ('$_parametri[anno]', '$_parametri[data_scad]', '$_parametri[descrizione]', '$_parametri[importo]', '$_parametri[utente]', '$_parametri[anno_doc]',
            '$_parametri[ndoc]', '$_parametri[data_doc]', '$_parametri[anno_proto]', '$_parametri[nproto]', '$_parametri[codpag]', '$_parametri[banca]', '$_parametri[impeff]', '$_parametri[status]', '$_parametri[data_pag]', '$_parametri[note]')";

// Esegue la query...
        if (mysql_query($query, $conn) != 1)
        {
            $_return['errori']['descrizione'] = "Si &egrave; verificato un errore nella query inserimento query tabella prima_nota Inserisci:<br>\n\"$query\"\n";
            $_return['errori']['errore'] = "errore";
        }
    }
    if ($_cosa == "inserisci")
    {
//funzione che mi inserisci i dati nello scadenziario..

        $query = "INSERT INTO scadenziario (anno, `data_scad`, `descrizione`, `importo`, `utente`, `anno_doc`, `ndoc`, `data_doc`, `anno_proto`, suffix_proto, 
            `nproto`, `codpag`, `banca`, `impeff`, status, data_pag, note) VALUES
            ('$_parametri[anno]', '$_parametri[data_scad]', '$_parametri[descrizione]', '$_parametri[importo]', '$_parametri[utente]', '$_parametri[anno_doc]',
            '$_parametri[ndoc]', '$_parametri[data_doc]', '$_parametri[anno_proto]', '$_parametri[suffix_proto]', '$_parametri[nproto]', '$_parametri[codpag]', '$_parametri[banca]', '$_parametri[impeff]', '$_parametri[status]', '$_parametri[data_pag]', '$_parametri[note]')";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_return = $_errori;
            $_return = "NO";
        }
        else
        {
            $_return = "OK";
        }
    }
    elseif ($_cosa == "Aggiorna")
    {
//funzione che mi inserisci i dati nello scadenziario..

        $query = "UPDATE scadenziario set data_scad='$_parametri[data_scad]', descrizione='$_parametri[descrizione]', importo='$_parametri[importo]', utente='$_parametri[utente]',
                anno_doc='$_parametri[anno_doc]', ndoc='$_parametri[ndoc]', data_doc='$_parametri[data_doc]', anno_proto='$_parametri[anno_proto]', suffix_proto='$_parametri[suffix_proto]', nproto='$_parametri[nproto]',
                codpag='$_parametri[codpag]', banca='$_parametri[banca]', impeff='$_parametri[impeff]', status='$_parametri[status]', data_pag='$_parametri[data_pag]', note='$_parametri[note]' where anno='$_parametri[anno]' AND nscad='$_parametri[nscad]' limit 1";

// Esegue la query...
        if (mysql_query($query, $conn) != 1)
        {
            $_return['errori']['descrizione'] = "Si &egrave; verificato un errore query tabella prima_nota Aggiorna:<br>\n\"$query\"\n";
            $_return['errori']['errore'] = "errore";
        }
    }
    elseif ($_cosa == "aggiorna")
    {
//funzione che mi inserisci i dati nello scadenziario..

        $query = "UPDATE scadenziario set data_scad='$_parametri[data_scad]', descrizione='$_parametri[descrizione]', importo='$_parametri[importo]', utente='$_parametri[utente]',
                anno_doc='$_parametri[anno_doc]', ndoc='$_parametri[ndoc]', data_doc='$_parametri[data_doc]', anno_proto='$_parametri[anno_proto]', suffix_proto='$_parametri[suffix_proto]', nproto='$_parametri[nproto]',
                codpag='$_parametri[codpag]', banca='$_parametri[banca]', impeff='$_parametri[impeff]', status='$_parametri[status]', data_pag='$_parametri[data_pag]', note='$_parametri[note]' where anno='$_parametri[anno]' AND nscad='$_parametri[nscad]' limit 1";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_return = $_errori;
        }
        else
        {
            $_return = "OK";
        }
    }
    elseif ($_cosa == "elimina_proto")
    {

        $query = "DELETE FROM scadenziario WHERE nproto='$_parametri[nproto]' AND suffix_proto='$_parametri[suffix_proto]' AND anno_proto='$_parametri[anno_proto]'";
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_return = "NO";
        }
        else
        {
            $_return = "OK";
        }
        
        
    }
    elseif ($_cosa == "elimina")
    {
//questa funzione mi elimina le scadenze selezionate..
//qui mettiamo l'elenco
        $query = "DELETE FROM scadenziario WHERE $_parametri[campo1] = '$_parametri[data_campo1]' AND $_parametri[campo2] = '$_parametri[data_campo2]'";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_return = $_errori;
        }
        else
        {
            $_return = "OK";
        }
    }
    elseif ($_cosa == "singolo")
    {
//qui mettiamo l'elenco
        $query = "SELECT * FROM scadenziario WHERE nscad = '$_parametri[nscad]' limit 1";

//eseguiamo la query
        $res = mysql_query($query, $conn) or mysql_error();
// Esegue la query...
        $_return = mysql_fetch_array($res);

//passiamo il tutto a gestirlo con l'array.
    }
    elseif ($_cosa == "singola")
    {
//qui mettiamo l'elenco
        $query = "SELECT * FROM scadenziario WHERE anno='$_parametri[anno]' AND nscad = '$_parametri[nscad]' limit 1";

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
        
        foreach ($result AS $dati);
        $_return = $dati;
        
    }
    elseif ($_cosa == "elenco_dadata")
    {

        $query = "select *, date_format(data_scad, '%d-%m-%Y') AS scadenza from scadenziario where data_scad >= '$_parametri[data_scad]' AND $_parametri[campi] LIKE '%$_parametri[descrizione]%' order by data_scad ASC";

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

        $_return = $result;
    }
    elseif ($_cosa == "elenco_pertipo")
    {
        if ($_parametri['tipo'] == "Libere")
        {
            if ($_parametri['data_fine'] == "0000-00-00")
            {
                $query = "select *, date_format(data_scad, '%d-%m-%Y') AS scadenza from scadenziario where banca='' AND data_scad >= '$_parametri[data_scad]' order by data_scad ASC";
            }
            else
            {
                $query = "select *, date_format(data_scad, '%d-%m-%Y') AS scadenza from scadenziario where banca='' AND data_scad >= '$_parametri[data_scad]' AND data_scad <='$_parametri[data_fine]' order by data_scad ASC";
            }
        }
        elseif ($_parametri['tipo'] == "Tutte")
        {
            
            if ($_parametri['data_fine'] == "0000-00-00")
            {
                $query = "select *, date_format(data_scad, '%d-%m-%Y') AS scadenza from scadenziario where data_scad >= '$_parametri[data_scad]' order by data_scad ASC";
                
            }
            else
            {
                $query = "select *, date_format(data_scad, '%d-%m-%Y') AS scadenza from scadenziario where data_scad >= '$_parametri[data_scad]' AND data_scad <='$_parametri[data_fine]' order by data_scad ASC";
            }
        }
        else
        {
            if ($_parametri['data_fine'] == "0000-00-00")
            {
                $query = "select *, date_format(data_scad, '%d-%m-%Y') AS scadenza from scadenziario where banca='$_parametri[tipo]' AND data_scad >= '$_parametri[data_scad]' order by data_scad ASC";
            }
            else
            {
                $query = "select *, date_format(data_scad, '%d-%m-%Y') AS scadenza from scadenziario where banca='$_parametri[tipo]' AND data_scad >= '$_parametri[data_scad]' AND data_scad <='$_parametri[data_fine]' order by data_scad ASC";
            }
        }

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
 
        $_return = $result;
    }
    elseif($_cosa == "nscad")
    {
        $hoy = date('Y-m-d');
        $hasta = date("Y-m-d", mktime(0, 0, 0, date(m), date(d)+7, date(Y)));
        
        $query = "SELECT * FROM scadenziario where data_scad >= '$hoy' AND data_scad <='$hasta'";
#        echo $query;
 
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
        
        
        
        $_return = $result->rowCount();
        
    }
    elseif($_cosa == "data_singola")
    {
        //echo $_parametri;
        
        $query = "SELECT substring(descrizione,1,'20') AS descrizione FROM scadenziario where status != 'saldato' AND data_scad ='$_parametri' limit 5";
#        echo $query;
 
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
        
        
        
        $_return = $result;
        
    }
    elseif($_cosa == "giorno")
    {
        //echo $_parametri;
        
        $query = "SELECT *, date_format(data_scad, '%d-%m-%Y') AS scadenza FROM scadenziario where data_scad = '$_parametri' ORDER BY descrizione, nscad";
//        echo $query;
 
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
        
        $_return = $result;
        
    }
    elseif ($_cosa == "sposta")
    {
//questa funzione mi elimina le scadenze selezionate..
//qui mettiamo l'elenco
        $query = "UPDATE scadenziario SET data_scad='$_parametri[data_end]' WHERE anno='$_parametri[anno]' AND nscad='$_parametri[nscad]' limit 1";

        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_return = $_errori;
        }
        else
        {
            $_return = "OK";
        }
    }
    elseif($_cosa == "elenca_proto")
    {
        $query = "SELECT * FROM scadenziario WHERE nproto='$_parametri[nproto]' AND suffix_proto='$_parametri[suffix_proto]' AND anno_proto='$_parametri[anno_proto]' ORDER BY data_scad";
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore $_cosa Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $_return = $_errori;
        }
        
        $_return = $result;
        
    }
    else
    {
//qui mettiamo l'elenco
        $query = "SELECT * FROM scadenziario WHERE $_parametri[campo1] = '$_parametri[data_campo1]' AND $_parametri[campo2] = '$_parametri[data_campo2]' ORDER BY data_scad";

//eseguiamo la query
        $_return = mysql_query($query, $conn) or mysql_error();

//passiamo il tutto a gestirlo con l'array.
    }

    return $_return;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------

function tabella_stampe_layout($_cosa, $_percorso, $_tdoc)
{
    global $conn;


    if ($_cosa == "singola")
    {

        $query = "SELECT * FROM stampe_layout WHERE tdoc = '$_tdoc' limit 1";
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

        foreach ($result AS $return)
            ;
    }
    elseif ($_cosa == "elenco_etichette")
    {
        //classico elenco

        $query = "SELECT * from stampe_layout WHERE tdoc LIKE 'eti%' ORDER BY tdoc";

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

        $return = $result;
    }//fine else
    elseif ($_cosa == "elenco_listini")
    {
        //classico elenco

        $query = "SELECT * from stampe_layout WHERE tdoc LIKE 'lis%' ORDER BY tdoc";

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

        $return = $result;
    }//fine else
    else
    {
        //classico elenco

        $query = "SELECT * from stampe_layout ORDER BY tdoc";

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

        $return = $result;
    }//fine else



    return $return;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------
/* * Funzione di gestione tipologia articolo
 *
 * @global  $conn
 * @global type $_percorso
 * @param type $_cosa sincgolo estrae la singola riga in array
 * @param type $_cosa elenco elenca le tipologia per descrizione
 * @param type $_codice
 * @param type $_parametri
 * @return type array
 */

function tabella_tipart($_cosa, $_codice, $_parametri)
{
    global $conn;
    global $_percorso;

    if ($_cosa == "singola_id")
    {

//mi restituisce l'arre singolo
        $query = "SELECT * FROM tipart WHERE id='$_parametri'";

// Esegue la query...
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }


        foreach ($result AS $return);

    }
    elseif ($_cosa == "singola_codice")
    {

//mi restituisce l'arre singolo
        $query = "SELECT * FROM tipart WHERE codice='$_codice'";

// Esegue la query...
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }


        foreach ($result AS $return);

    }
    elseif ($_cosa == "aggiorna_id")
    {

//mi restituisce l'arre singolo
        $query = "UPDATE tipart SET codice='$_codice', tipoart = '$_parametri[descrizione]' where id='$_parametri[id]'";

// Esegue la query...
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            $return = "OK";
        }

    }
    elseif ($_cosa == "aggiorna")
    {

//mi restituisce l'arre singolo
        $query = "UPDATE tipart SET tipoart = '$_parametri[descrizione]' where codice='$_codice'";

// Esegue la query...
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            $return = "OK";
        }

    }
    elseif ($_cosa == "check_codice")
    {

//mi restituisce l'arre singolo
        $query = "SELECT * FROM tipart where codice='$_codice' OR tipoart='$_parametri[descrizione]'";
        
// Esegue la query...
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        
        if($result->rowCount() > 0)
        {
            $return = "NO";
        }
        else
        {
            $return = "OK";
        }

    }
    elseif($_cosa == "Array")
    {
        
        $tipart = tabella_tipart("elenca", $_codice, $_parametri);
        
        //inserisco i dati in un array..
        foreach ($catmer AS $dati2)
        {
            $categoria[$dati2['codice']] = $dati2['tipart'];
        }
        
        if($_codice != "")
        {
            $return = $categoria[$_codice];
        }
        else
        {
            $return = $categoria;
        }
        
        
    }
    elseif($_cosa == "inserisci")
    {
        
        $query = "insert into tipart ( codice, tipoart ) VALUES ( '$_codice', '$_parametri[descrizione]')";
        
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
    }
    elseif($_cosa == "elimina_id")
    {
        
        $query = "DELETE FROM tipart where id='$_parametri'";
        
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
        
        
    }
    elseif ($_cosa == "elenca_select_N")
    {

//mi restituisce l'arre singolo
        $query = "select * from tipart order by tipoart";

// Esegue la query...
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

        echo "<select name=\"$_parametri\">\n";
        echo "<option value=\"\"></option>\n";

        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[id]\">$dati[tipoart] - $dati[codice]</option>\n";
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "elenco_select")
    {

//mi restituisce l'arre singolo
        $query = "select * from tipart order by tipoart";

// Esegue la query...
        $res = mysql_query($query, $conn);

        echo "<select name=\"$_codice\">\n";
        echo "<option value=\"\"></option>\n";

        while ($dati = mysql_fetch_array($res))
        {
            echo "<option value=\"$dati[codice]\">$dati[tipoart] - $dati[codice]</option>\n";
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "elenca_select_2")
    {
        echo "<select name=\"$_parametri\">\n";
        
        $query = "select * from tipart where codice='$_codice'";

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
        foreach ($result AS $dati);
        echo "<option value=\"$dati[codice]\">$dati[tipoart] - $dati[codice]</option>\n";
        
        //mi restituisce l'arre singolo
        $query = "select * from tipart order by tipoart";

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

        
        echo "<option value=\"\"></option>\n";

        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[codice]\">$dati[tipoart] - $dati[codice]</option>\n";
        }

        echo "</select>\n";
    }
    elseif ($_cosa == "elenca")
    {


        // Stringa contenente la query di ricerca... solo dei fornitori
        $query = "select * from tipart order by tipoart";

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

        $return = $result;
    }
    elseif ($_cosa == "elenca_select")
    {
        //mi restituisce l'arre singolo
        $query = "select * from tipart order by tipoart";

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query elenca_select= $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        echo "<select name=\"$_codice\">\n";
        echo "<option value=\"\"></option>\n";

        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[codice]\">$dati[tipoart] - $dati[codice]</option>\n";
        }

        echo "</select>\n";
    }
        elseif ($_cosa == "elenca_codice")
    {
        $query = sprintf("select * from tipart order by codice");

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

        $return = $result;
    }
    else
    {


        // Stringa contenente la query di ricerca... solo dei fornitori
        $query = "select * from tipart order by tipoart";

// Esegue la query...
        $dati = mysql_query($query, $conn);
    }



    return $return;
}

function tabella_todo($_cosa, $_anno, $_numero, $_utente_end, $_data_end, $_completato, $_parametri)
{
    global $conn;
    global $_percorso;
    global $_dec;
    
    
    if($_cosa == "inserisci")
    {
        
        $query = "INSERT INTO todo (anno, utente_start, utente_end, data_start, data_end, completato, titolo, corpo, priorita) VALUES "
                . "('$_parametri[anno]', '$_parametri[utente_start]', '$_parametri[utente_end]', '$_parametri[data_start]', '$_parametri[data_end]',"
                . "'$_parametri[completato]', '$_parametri[titolo]', '$_parametri[corpo]', '$_parametri[priorita]')";
        
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
        
    }
    elseif($_cosa == "singola")
    {
        $query = "SELECT * FROM todo where anno='$_anno' AND numero='$_numero' limit 1";
        
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result AS $return);
    }
    elseif($_cosa == "aggiorna")
    {
        $query = "UPDATE todo set utente_end='$_parametri[utente_end]', data_start='$_parametri[data_start]', data_end='$_parametri[data_end]',
            completato='$_parametri[completato]', titolo='$_parametri[titolo]', corpo='$_parametri[corpo]', priorita='$_parametri[priorita]'"
                . " WHERE anno='$_anno' AND numero='$_numero' limit 1";
        //echo $query;
                 
        
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
        
        
        
    }
    elseif($_cosa == "elimina")
    {
        $query = "DELETE FROM todo WHERE anno='$_anno' AND numero='$_numero' limit 1";
        //echo $query;
                 
        
        $result = $conn->exec($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $return = $_errori;
        }
        else
        {
            $return = "OK";
        }
          
    }
    elseif($_cosa == "elenco_completo")
    {
        if($_utente_end == "2")
        {
            $query = "SELECT *, date_format(data_end, '%d-%m-%Y') AS scadenza FROM todo ORDER BY data_end, completato ASC";
        }
        else
        {
            $query = "SELECT *, date_format(data_end, '%d-%m-%Y') AS scadenza FROM todo where (utente_start = '$_parametri' OR utente_end = '1' OR utente_end='$_utente_end') ORDER BY data_end, completato ASC";
        }
        
        
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $return = $result;
    }
    else
    {
        if($_utente_end == "2")
        {
            $query = "SELECT *, date_format(data_end, '%d-%m-%Y') AS scadenza FROM todo INNER JOIN utenti ON todo.utente_end=utenti.id where completato != '100' ORDER BY user ASC, priorita, data_end ASC";
        }
        else
        {
            $query = "SELECT *, date_format(data_end, '%d-%m-%Y') AS scadenza FROM todo INNER JOIN utenti ON todo.utente_end=utenti.id where completato != '100' AND (utente_end = '1' OR utente_end='$_utente_end') ORDER BY user ASC, priorita, data_end ASC";
        }
        
        
        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        $return = $result;
    }
    
    
    return $return;
    
}




//------------------------------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * funzione di colloqio con la tabella vettori
 * 
 * @global type $conn parametri di connessione
 * @param type $_cosa dice cosa fare come singolo elenco inserisci modifica elimina
 * @param type $_codice per la ricerca esatta
 * @param type $_parametri array contenente tutti i valori.
 * @return type 
 */
function tabella_vettori($_cosa, $_percorso, $_codice, $_parametri)
{
    global $conn;

    if ($_cosa == "singolo")
    {
//mi restituisce l'arre singolo
        $query = "select * from vettori where codice='$_codice' limit 1";

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

        foreach ($result AS $dati)
            ;
    }
    elseif ($_cosa == "id_collo")
    {
//mi restituisce l'arre singolo
        $query = "select traking from vettori where vettore='$_codice' limit 1";

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore id_collo Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        foreach ($result AS $traking);
        $dati = $traking['traking'];
    }
    elseif ($_cosa == "check")
    {
        //la funzioni mi verifica se il codice è già occupato
        $query = "select codice from vettori where codice='$_codice' limit 1";
        //esegue la query

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

        if ($result->rowCount() > 0)
        {

            echo "<tr><td><b>Il vettore inserito &egrave; gi&agrave; esistente nell'archivio.</td></tr>\n

	    <tr><td>Controllare i seguenti campi nell'archivio per verificare la presenza</td></tr>\n

	    <tr><td>Codice immesso già presente</td></tr>\n

	    <tr><td>Fai indietro con il browser per non perdere i dati inseriti.<br> Poi cambia codice cliente</td></tr>\n";
            $dati['result'] = "1";
            exit;
        }
        else
        {
            echo "<br>risultato controllo ok.. <br>";
            $dati['result'] = "0";
        }
    }
    elseif ($_cosa == "inserisci")
    {
        //inseriamo i campi..

        $query = "INSERT INTO vettori ( codice, vettore, indirizzo, telefono, cell, fax, email, web, traking, note ) VALUES ( '$_codice', '$_parametri[vettore]', '$_parametri[indirizzo]', '$_parametri[telefono]', '$_parametri[cell]', '$_parametri[fax]', '$_parametri[email]', '$_parametri[web]', '$_parametri[traking]', '$_parametri[note]')";

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['risultato'] = "1";
            $dati['errori'] = "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
        }
        else
        {
            $dati['risultato'] = "0";
            $dati['errori'] = "Vettore inserito correttamente";
        }
    }
    elseif ($_cosa == "modifica")
    {

        $query = sprintf("UPDATE vettori SET vettore=\"%s\", indirizzo=\"%s\", telefono=\"%s\", cell=\"%s\", fax=\"%s\", email=\"%s\", web=\"%s\", traking=\"%s\",  note=\"%s\" WHERE codice=\"%s\"", $_parametri['vettore'], $_parametri['indirizzo'], $_parametri['telefono'], $_parametri['cell'], $_parametri['fax'], $_parametri['email'], $_parametri['web'], $_parametri['traking'], $_parametri['note'], $_codice);
        // Esegue la query...

        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['risultato'] = "1";
            $dati['errori'] = "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
        }
        else
        {
            $dati['risultato'] = "0";
            $dati['errori'] = "Vettore modificato correttamente";
        }
    }
    elseif ($_cosa == "elimina")
    {

        $query = sprintf("DELETE FROM vettori WHERE codice=\"%s\" limit 1", $_codice);

        $result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['risultato'] = "1";
            $dati['errori'] = "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
        }
        else
        {
            $dati['risultato'] = "0";
            $dati['errori'] = "Vettore eliminato correttamente";
        }
    }
    elseif ($_cosa == "elenco_campo")
    {

        $query = "select * from vettori where $_parametri[campi] like '$_parametri[descrizione]' order by vettore";

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['risultato'] = "1";
            $dati['errori'] = "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
        }

        $dati = $result;
    }
    elseif ($_cosa == "elenca_select")
    {
        // Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("select * from vettori order by vettore");

        $result = $conn->query($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "motore_anagrafiche.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
            $dati['risultato'] = "1";
            $dati['errori'] = "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
        }

        foreach ($result AS $dati)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati['vettore'], $dati['vettore']);
        }
    }
    else
    {
//mi restituisce l'arre singolo
        $query = "select * from vettori order by vettore";

// Esegue la query...
        $dati = mysql_query($query, $conn);
    }
    return $dati;
}

function tabella_zone($_cosa, $_codice, $_parametri)
{
    global $conn;
    global $_percorso;
    
    if ($_cosa == "elenca_select_2")
    {

        echo "<select name=\"$_parametri\">\n";
        
        $query = sprintf("select * from zone where nome=\"%s\"", $_codice);
        
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
        
        foreach ($result as $dati);
        
        printf("<option value=\"%s\">%s</option>\n", $dati['nome'], $dati['nome']);
        
        echo "<option value=\"\"></option>\n";

        //seconda query
        $query = sprintf("select * from zone order by nome");
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

        foreach ($result as $dati1)
        {
            printf("<option value=\"%s\">%s</option>\n", $dati1['nome'], $dati1['nome']);
        }

        echo "</select>\n";
        echo "</td></tr>\n";
    }
    else
    {
        $query = "SELECT * FROM zone ORDER BY nome ASC";
        
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
        
        
        $_return = $result;
        
    }
    
    
    
    
    
    
    
    return $_return;
}




?>