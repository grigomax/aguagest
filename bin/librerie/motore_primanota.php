<?php



/*
  Motore libreria per le schermate della prima nota in generale
 * qui ci sono tutte le librerie...
 * agua-gest aguagest.sourceforge.net
 */

//funzione di ricerca causali..
function causali($_causale, $_azione)
{
    global $conn;
    global $_percorso;
    
	//funzione che mi permette di ricercare tutte le causali contabili..

	global $conn;
	if ($_azione == "singolo")
	{
		// Stringa contenente la query di ricerca...
		$query = "select * from causali_contabili where causale = $_causale order by causale";
	}
	else
	{
		// Stringa contenente la query di ricerca...
		$query = "select * from causali_contabili order by causale";
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

        $dati = $result->fetch(PDO::FETCH_ASSOC);
        
	return $dati;
}

function piano_conti($_codconto, $_azione)
{
    global $_percorso;
    global $conn;
	//funzione che mi permette di restituire il piano dei conti

	
	if ($_azione == "singolo")
	{
		// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti where livello >= '2' and codconto = $_codconto order by codconto";
		// Esegue la query...
		$res = mysql_query($query, $conn);

		$dati = mysql_fetch_array($res);
	}
        elseif ($_azione == "singola")
	{
		// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti where livello >= '2' and codconto = $_codconto order by codconto";
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore $_azione Query = $query - $_errore[2]";
			$_errori['files'] = "motore_anagrafiche.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$dati = "errore";
		}
		else
		{
			foreach ($result AS $dati);
		}
	}
	elseif ($_azione == "descsingola")
	{
		// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti where livello >= '2' and codconto = $_codconto order by codconto";
		// Esegue la query...
		$res = mysql_query($query, $conn);

		$dati = mysql_fetch_array($res);

		$dati = $dati['descrizione'];
	}
	elseif ($_azione == "desc_singola")
	{
		// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti where livello >= '2' and codconto = $_codconto order by codconto";
		// Esegue la query...
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore $_azione Query = $query - $_errore[2]";
			$_errori['files'] = "motore_anagrafiche.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$dati = "errore";
		}
		else
		{
			foreach ($result AS $dati);
		}

		$dati = $dati['descrizione'];
	}
	elseif ($_azione == "mastrini")
	{
		// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti where livello >= '2' order by codconto";
		// Esegue la query...
		$res = mysql_query($query, $conn);

		$dati = $res;
	}
	else
	{
		// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti order by codconto";
		// Esegue la query...
		$res = mysql_query($query, $conn);

		$dati = $res;
	}



	return $dati;
}

/**
 *
 * @global  $conn
 * @param <type> $_codconto
 * @param <type> singolo ritorna l'array della riga
 * * @param <type> descsingola = ritorna la descrizione del piano dei conti
 * @param <type> nat_conto prende un gruppo di conti in base alla natura ma di livello 2 restituisce l'array della query
 * @return <type>
 */
function tabella_piano_conti($_cosa, $_codconto, $_parametri)
{
//funzione che mi permette di restituire il piano dei conti

	global $conn;

	if ($_cosa == "singolo")
	{
// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti where livello >= '2' and codconto = '$_codconto' order by codconto";
// Esegue la query...
		$res = mysql_query($query, $conn);

		$dati = mysql_fetch_array($res);
	}
    elseif ($_cosa == "singola")
    {
// Stringa contenente la query di ricerca...
        $query = "select * from piano_conti where livello >= '2' and codconto = '$_codconto' order by codconto";
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
    }
    elseif ($_cosa == "descsingola")
    {
// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti where livello >= '2' and codconto = '$_codconto' order by codconto";
// Esegue la query...
		$res = mysql_query($query, $conn);

		$datip = mysql_fetch_array($res);

		$dati = $datip['descrizione'];
	}
	elseif ($_cosa == "nat_conto")
	{
// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti where livello >= '2' and natcon = '$_codconto' order by codconto";
// Esegue la query...
		$res = mysql_query($query, $conn);

		$dati = $res;
	}
	elseif ($_cosa == "elenco_select_conto")
	{
		$query1 = "select * from piano_conti WHERE livello = '2' order by descrizione";
		$res_1 = mysql_query($query1, $conn);

		echo "<select name=\"$_parametri[name]\">\n";
		echo "<option value=\"$_parametri[conto]\">$_parametri[descrizione] - $_parametri[conto]</option>";
		echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
		echo "<span class=\"testo_blu\">";
		while ($dati = mysql_fetch_array($res_1))
		{
			printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
		}
		echo "</select>\n";
	}
	elseif ($_cosa == "elenco_select_mastro")
	{
		$query1 = "select * from piano_conti WHERE livello = '1' order by descrizione";
		$res_1 = mysql_query($query1, $conn);

		echo "<select name=\"$_parametri[name]\">\n";
		echo "<option value=\"$_parametri[conto]\">$_parametri[descrizione] - $_parametri[conto]</option>";
		echo "<option value=\"\"></option>";
// Tutto procede a meraviglia...
		echo "<span class=\"testo_blu\">";
		while ($dati = mysql_fetch_array($res_1))
		{
			printf("<option value=\"%s\">%s - %s</option>\n", $dati['codconto'], $dati['descrizione'], $dati['codconto']);
		}
		echo "</select>\n";
	}
	elseif($_cosa == "inserisci")
	{
		
		$query = "INSERT INTO piano_conti (codconto, descrizione, natcon, livello, tipo_cf) values ('$_codconto', '$_parametri[descrizione]', '$_parametri[natconto]', '$_parametri[livello]', '$_parametri[tipo_cf]')";
		
		//eseguiamo..
		
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$dati['result'] = FALSE;
			$dati['errori'] = $_errori['descrizione'];
		}
		else
		{
			$dati['result'] = TRUE;
		}
		
	}
	elseif($_cosa == "aggiorna")
	{
		//funzione aggiornamento..
		
		$query = "UPDATE piano_conti SET descrizione='$_parametri[descrizione]', natcon='$_parametri[natconto]' , livello='$_parametri[livello]', tipo_cf='$_parametri[tipo_cf]' WHERE codconto='$_codconto' LIMIT 1";
		
		//eseguiamo..
		
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$dati['result'] = FALSE;
			$dati['errori'] = $_errori['descrizione'];
		}
		else
		{
			$dati['result'] = TRUE;
		}
		
		
	}
	else
	{
// Stringa contenente la query di ricerca...
		$query = "select * from piano_conti order by codconto";
// Esegue la query...
		$res = mysql_query($query, $conn);

		$dati = $res;
	}



	return $dati;
}

/* * funzione che mi gestisce la tabella delle causali contabili
 *
 * @global $conn $conn
 * @param type $_cosa Inserisci, singolo, modifica, elimina, elenco
 * @param type $_codice
 * @param type $_parametri
 * @return type generalmente un array
 */

function tabella_causali_contabili($_cosa, $_percorso, $_codice, $_parametri)
{
	global $conn;

	if ($_cosa == "singolo")
	{
		$query = "SELECT * FROM causali_contabili WHERE causale=$_codice";
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}

		foreach ($result as $dati)
			;
		//inseriamo eventuali errori nel return..
		$dati['errori'] = $_errore['2'];
	}
	elseif ($_cosa == "ultimo")
	{
		//vedimo l'ultimo numero inserito..
		$query = "select causale from causali_contabili order by causale desc limit 1";
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}

		foreach ($result as $dati)
			;
		//inseriamo eventuali errori nel return..
		$dati['errori'] = $_errore['2'];

		$dati = $dati['causale'] + 1;
	}
	elseif ($_cosa == "cerca")
	{
		$_descrizione = "%$_parametri[descrizione]%";
		$query = sprintf("select * from causali_contabili where %s like \"%s\" order by causale", $_parametri['campi'], $_descrizione);
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}

		$dati['dati'] = $result;
		$dati['errori'] = $_errore['2'];
	}
	elseif ($_cosa == "verifica")
	{

		$query = sprintf("select causale from causali_contabili where causale=\"%s\"", $_codice);
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}

		if ($result->rowCount() > 0)
		{
			$dati['result'] = TRUE;
		}
		else
		{
			$dati['result'] = FALSE;
		}

		$dati['errori'] = $_errori;
	}
	elseif ($_cosa == "Inserisci")
	{

		$query = "INSERT INTO causali_contabili ( causale, descrizione, conto_1, conto_2, conto_3, conto_4,conto_5, conto_6,conto_7, conto_8,conto_9, conto_10 )
            VALUES ('$_codice', '$_parametri[descrizione]', '$_parametri[conto_1]', '$_parametri[conto_2]', '$_parametri[conto_3]', '$_parametri[conto_4]', '$_parametri[conto_5]', '$_parametri[conto_6]', '$_parametri[conto_7]', '$_parametri[conto_8]', '$_parametri[conto_9]', '$_parametri[conto_10]')";
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$dati['result'] = FALSE;
			$dati['errori'] = $_errore['2'];
		}
		else
		{
			$dati['result'] = TRUE;
		}
	}
	elseif ($_cosa == "Aggiorna")
	{

		$query = "UPDATE causali_contabili SET descrizione=\"$_parametri[descrizione]\", conto_1=\"$_parametri[conto_1]\",
    conto_2=\"$_parametri[conto_2]\" , conto_3=\"$_parametri[conto_3]\", conto_4=\"$_parametri[conto_4]\", conto_5=\"$_parametri[conto_5]\",
    conto_6=\"$_parametri[conto_6]\", conto_7=\"$_parametri[conto_7]\", conto_8=\"$_parametri[conto_8]\", conto_9=\"$_parametri[conto_9]\",
    conto_10=\"$_parametri[conto_10]\" WHERE causale = \"$_codice\"";
		
		$conn->exec($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$dati['result'] = FALSE;
			$dati['errori'] = $_errore['2'];
		}
		else
		{
			$dati['result'] = TRUE;
		}
	}
	elseif($_cosa == "Elimina")
	{
	
		$query = sprintf("DELETE FROM causali_contabili WHERE causale=\"%s\" limit 1", $_codice);
		$conn->exec($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$dati['result'] = FALSE;
			$dati['errori'] = $_errore['2'];
		}
		else
		{
			$dati['result'] = TRUE;
		}
		
	}
	else
	{
		$query = "select * from causali_contabili order by causale";
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}

		$dati['dati'] = $result;
		$dati['errori'] = $_errore['2'];
	}


	return $dati;
}

function carrello_primanota($_cosa, $_anno, $_rigo, $_tipo_cf, $_codconto, $_dare, $_avere, $_segno, $_iva)
{
	global $conn;
	global $id;
        global $_percorso;
        global $dec;
	$_causale = $_SESSION['causale'];
	require "../../../setting/par_conta.inc.php";

	//funzione che mi rende l'ultimo rigo della registrazione inseita
	if ($_cosa == "Aggiorna")
	{
		// Stringa contenente la query di ricerca...
		$query = sprintf("UPDATE prima_nota_basket SET iva=\"%s\", dare=\"%s\", avere=\"%s\" where sessionid=\"%s\" and rigo=\"%s\" limit 1", $_iva, $_dare, $_avere, $id, $_rigo);
		
                $result = $conn->exec($query);
                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }
                
	}
	elseif ($_cosa == "Inserisci")
	{
		//prima di inserire facciamo un po di conti..
		//completiamo il codice conto..
		if ($_tipo_cf == "C")
		{
                    $dati = tabella_clienti("singola",$_codconto, $_parametri);
                    $_descrizione = addslashes($dati['ragsoc']);
                    //vuol dire che sono clienti
                    $_conto = sprintf("%s%s", $MASTRO_CLI, $_codconto);
		}
		elseif ($_tipo_cf == "F")
		{
                    $dati = tabella_fornitori("singola", $_codconto, $_parametri);
			$_descrizione = addslashes($dati['ragsoc']);
			$_conto = sprintf("%s%s", $MASTRO_FOR, $_codconto);
		}
		else
		{
                        $dati = tabella_piano_conti("singola", $_codconto, $_parametri);
                        
			$_descrizione = addslashes($dati['descrizione']);
			$_conto = $_codconto;
		}

		if ($_causale != "ST")
		{
			$_parametri = $_SESSION['parametri'];
			//se la causale è fa bisogna inserire tutti i dati nel carrello..
			$query = "INSERT prima_nota_basket (sessionid, anno, nreg, data_reg, data_cont, causale, descrizione, ndoc, anno_doc, suffix_doc, data_doc, conto, dare, avere, tipopag, nproto, anno_proto, suffix_proto, liquid_iva, segno, iva) VALUES ('$id', '$_anno', '$_parametri[nreg]', '$_parametri[data_reg]', '$_parametri[data_cont]', '$_causale', '$_descrizione', '$_parametri[ndoc]', '$_parametri[anno_doc]', '$_parametri[suffix_doc]' ,'$_parametri[data_doc]', '$_conto', '$_dare', '$_avere', '$_parametri[tipopag]', '$_parametri[nproto]', '$_parametri[anno_proto]', '$_parametri[suffix_proto]', '$_parametri[liquid_iva]', '$_segno', '$_iva')";
			// Esegue la query...
			$result = $conn->exec($query);
                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
                            echo $_errore['2'];
                            //aggiungiamo la gestione scitta dell'errore..
                            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                            scrittura_errori($_cosa, $_percorso, $_errori);
                        }
		}
		else
		{
			// Stringa contenente la query di ricerca...
			$query = "INSERT prima_nota_basket (sessionid, anno, data_reg, data_cont, descrizione, causale, conto, dare, avere, iva) VALUES ('$id', '$_anno', '$_data_reg', '$_data_cont','$_descrizione', '$_causale', '$_conto', '$_dare', '$_avere', '$_iva')";
			$result = $conn->exec($query);
                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
                            echo $_errore['2'];
                            //aggiungiamo la gestione scitta dell'errore..
                            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                            scrittura_errori($_cosa, $_percorso, $_errori);
                        }
		}
	}
	elseif ($_cosa == "elimina")
	{
		$query = "DELETE FROM prima_nota_basket where sessionid='$id' and rigo='$_rigo'";
		$result = $conn->exec($query);
                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }
	}
	elseif ($_cosa == "leggi")
	{
		//leggiamo la riga richiesta
		$query = "SELECT * FROM prima_nota_basket where sessionid='$id' AND rigo='$_rigo' ORDER BY anno, rigo";
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
                $return = $result->fetch(PDO::FETCH_ASSOC);

	}
	elseif ($_cosa == "svuota")
	{
		$query = "DELETE FROM prima_nota_basket where sessionid='$id'";

		$result = $conn->exec($query);
                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }
	}
	else
	{
		// Stringa contenente la query di ricerca...
		$query = "SELECT * FROM prima_nota_basket where sessionid='$id' ORDER BY anno, rigo";
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

/**
 * Funzione che mi permette di interagire con la tabella prima nota
 * funzione che mi permette di inserire, cacellare chiedere le cose alla tabella prima nota.
 * @global <type> $conn
 * @global <type> $id
 * @param string $_cosa
 * @param string $_cosa ultimo =  Retituisce l'ultimo numero inserito , obbligatorio il parametro anno
 * @param string $_cosa Inserisci_singolo =  questa funzione permettere di inserire una riga singola nella prima nota..
 * @param string $_cosa verifica_FV mi controlla se un documento esiste già obbligatori $_causale, $_anno, $_nreg
 * @param string $_cosa verifica_doc_aperto = questa funzione mi permette di vedere se un documento è ancora aperto.. obbligatorio
  * causale, anno, ndoc, parametri['utente']
 * @param array $_parametri iva
 * @param string $_cosa la funzione mi restituisce vero o falso alla domanda se un determinato conto è muovimentato all'interno nel piano dei conti
 * * @return boolean
 */
function tabella_primanota($_cosa, $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso)
{
	global $conn;
	global $id;
        global $MASTRO_EFFETTI;
        global $_percorso;
        global $dec;


	//* funzione che mi permette di inserire, cacellare chiedere le cose alla tabella prima nota.
	if ($_cosa == "ultimo")
	{
		//funzione che mi restituisce l'ultimo numero inserito..
		$query = sprintf("SELECT nreg FROM prima_nota where anno=\"%s\" ORDER BY nreg DESC LIMIT 1", $_anno);
		$res = mysql_query($query, $conn);
		$dati = mysql_fetch_array($res);
		$_ultimo = $dati['nreg'];
		$_tocca = $_ultimo + 1;
		return $_tocca;
	}
	elseif ($_cosa == "ultimo_numero")
	{
		//funzione che mi restituisce l'ultimo numero inserito..
		$query = "SELECT nreg FROM prima_nota where anno='$_anno' ORDER BY nreg DESC LIMIT 1";
		
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

		$dati = $result->fetch(PDO::FETCH_ASSOC);
		return $dati['nreg']+1;
	}
	elseif ($_cosa == "ultimo_proto")
	{
		//funzione che mi restituisce l'ultimo numero inserito..
		$query = "SELECT nproto FROM prima_nota where causale='FA' and anno_proto='$_anno' AND suffix_proto='$_parametri' ORDER BY nproto DESC LIMIT 1";
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
                $dati = $result->fetch(PDO::FETCH_ASSOC);
                
		$_ultimo = $dati['nproto'];
		$_tocca = $_ultimo + 1;
		return $_tocca;
	}
	elseif($_cosa == "check")
	{
		//la funzione mi restituisce vero o falso alla domanda se un determinato conto è muovimentato all'interno nel piano dei conti
		$query = "SELECT * FROM prima_nota WHERE conto = '$_parametri[conto]'";
		
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}
		
		//verifichiamo se esiste almeno una riga..
		if($result->rowCount() > "0")
		{
			$dati['result'] = TRUE;
			#foreach ($result AS $dati);
		}
		else
		{
			$dati['result'] = FALSE;
		}
		
		$dati['errori'] = $_errori['descrizione'];
		
		return $dati;
		
			
		
	}
	elseif ($_cosa == "verifica_numero")
	{
		//Veirifichiamo se un numero è occupto
		//funzione che mi restituisce l'ultimo numero inserito..
		$query = "SELECT nreg FROM prima_nota where anno='$_anno' and nreg='$_nreg' ORDER BY nreg DESC LIMIT 1";
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
                
		if (($result->rowCount()) > 0)
		{
			$_return = true;
		}
		else
		{
			$_return = false;
		}
		return $_return;
	}
	elseif ($_cosa == "verifica_FV")
	{
		//Veirifichiamo se un numero è occupto
		//funzione che mi controlla se la registrazione esiste già
		$query = "SELECT ndoc FROM prima_nota where causale='$_causale' AND anno_doc='$_anno' AND suffix_doc='$_parametri' AND ndoc='$_nreg' ORDER BY ndoc DESC LIMIT 1";
		
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
                
                
		if(($result->rowCount()) > 0)
		{
			$_return['risposta'] = "esiste";
		}
		else
		{
			$_return['risposta'] = "vuoto";
		}

		$_return['query'] = $query;

		return $_return;
	}
	elseif ($_cosa == "verifica_doc_aperto")
	{
		global $MASTRO_CLI;
		// questa funzione mi permette di vedere se un documento è ancora aperto..

		$_utente = $MASTRO_CLI . $_parametri['utente'];
		$query= "SELECT *,(SUM(avere) - SUM(dare)) AS diff FROM prima_nota where conto = '$_utente' AND ndoc = '$_nreg' AND anno_doc='$_anno' AND suffix_doc='$_parametri[suffix_doc]' GROUP BY ndoc";
		
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
                
                if ($dati['diff'] != "0.00")
		{
			$_return['risposta'] = "vero";
		}
		else
		{
			$_return['risposta'] = "falso";
		}

		$_return['diff'] = $dati['diff'];
		$_return['query'] = $sql;

		return $_return;
	}
	elseif ($_cosa == "verifica_nproto")
	{
		//Veirifichiamo se un numero è occupto
		//funzione che mi restituisce l'ultimo numero inserito..
		$query = "SELECT nproto FROM prima_nota where causale='FA' and anno_proto='$_parametri[anno_proto]' and suffix_proto='$_parametri[suffix_proto]' and nproto='$_parametri[nproto]' ORDER BY nreg DESC LIMIT 1";

                //echo $query;
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
                                
                if ($result->num_rows > 0)
		{
			$_return = true;
		}
		else
		{
			$_return = false;
		}
		return $_return;
	}
	elseif ($_cosa == "leggi_singolo")
	{
		//leggiamo la riga richiesta
		$query = "SELECT *, date_format(data_reg, '%d%-%m-%Y') AS data_reg, date_format(data_cont, '%d-%m-%Y') AS data_cont FROM prima_nota where anno='$_anno' AND nreg='$_nreg' ORDER BY anno, rigo";
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
		
                $return = $result->fetch(PDO::FETCH_ASSOC);
		
                return $return;
	
        }
	elseif ($_cosa == "leggi_PA")
	{
		//leggiamo la riga richiesta
		if ($_causale == "PA")
		{
			$query = "SELECT *,(SUM(avere) - SUM(dare))AS diff,  date_format(data_reg, '%d-%m-%Y') AS data_reg, date_format(data_cont, '%d-%m-%Y') AS data_cont FROM prima_nota where conto='$_parametri[conto]' AND $_parametri[campo1]='$_parametri[campo1_data]' AND $_parametri[campo2] ='$_parametri[anno_proto]' AND $_parametri[campo3] = '$_parametri[suffix_proto]' ORDER BY anno_proto, suffix_proto, rigo";
		}
		else
		{
			$query = "SELECT *,(SUM(dare) - SUM(avere))AS diff,  date_format(data_reg, '%d-%m-%Y') AS data_reg, date_format(data_cont, '%d-%m-%Y') AS data_cont FROM prima_nota where conto='$_parametri[conto]' AND $_parametri[campo1]='$_parametri[campo1_data]' AND $_parametri[campo2] ='$_parametri[anno_doc]' AND $_parametri[campo3] = '$_parametri[suffix_doc]' ORDER BY anno, suffix_doc, rigo";
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
                
                $dati = $result->fetch(PDO::FETCH_ASSOC);
		
                return $dati;
	}
	elseif ($_cosa == "salda")
	{
		//funzione che mi aggiorna solo il segno dell'operazione

		$query = sprintf("UPDATE prima_nota SET segno=\"%s\", sp_metro=\"%s\" where anno=\"%s\" AND nreg=\"%s\"", $_parametri['segno'], $_parametri['spesometro'], $_anno, $_nreg);
		
                $result = $conn->exec($query);
                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore $_cosa = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);

                    $_return['errori']['descrizione'] = "Si &egrave; verificato un errore nella query aggiornamento prima_nota <br>\n\"$query\"\n";
                    $_return['errori']['errore'] = "errore";
		}

		return $_return;
	}
	elseif ($_cosa == "Inserisci")
	{
		//portiamo ii numeri ufficiali in sede
		$_nproto = $_parametri['nproto'];
		//funzione che mi inserisce il documento dal basket alla prima nota..
		//leggiamo se è ancora valido l'ultimo documento..'
		//verifichiamo che il numero scelto non si occupato
		$_result = tabella_primanota("verifica_numero", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

		if ($_result == "true")
		{
			//vuol dire che il numero è occupato e quindi ne aggiungiamo uno
			$_nreg = $_nreg + 1;
		}

		if ($_causale == "FA")
		{
			//verifichiamo che il numero scelto non si occupato
			$_result = tabella_primanota("verifica_nproto", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

			if ($_result == "true")
			{
				//vuol dire che il numero è occupato e quindi ne aggiungiamo uno
				$_nproto = $_nproto + 1;
			}
		}

		//bene ora inseriamo il tutto..
		//leggiamo il basket e di pari passo inseriamo il tutto..
		$res = carrello_primanota("elenco", $_anno, $_rigo, $_tipo_cf, $_codconto, $_dare, $_avere, $_segno, $_iva);

		//ottimo ora con un ciclo di while mettiamo tutto dentro..

		foreach ($res AS $dati)
		{
			// Stringa contenente la query di ricerca...
			$query = sprintf("INSERT prima_nota (anno, nreg, data_reg, data_cont, segno, iva, descrizione, causale, conto, desc_conto, dare, avere,
                ndoc, anno_doc, data_doc, tipopag, nproto, anno_proto, suffix_proto, sp_metro, note, status)
                VALUES
                (\"%s\", \"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\", \"%s\",\"%s\")", $_anno, $_nreg, $_data_reg, $_data_cont, $_parametri['segno'], $dati['iva'], addslashes($_testo), $_causale, $dati['conto'], addslashes($dati['descrizione']), $dati['dare'], $dati['avere'], $_parametri['ndoc'], $_parametri['anno_doc'], $_parametri['data_doc'], $_parametri['codpag'], $_nproto, $_parametri['anno_proto'], $_parametri['suffix_proto'], $_parametri['spesometro'], addslashes($_parametri['note']), "Inserito");

		$result = $conn->exec($query);
                    if ($conn->errorCode() != "00000")
                    {
                        $_errore = $conn->errorInfo();
                        echo $_errore['2'];
                        //aggiungiamo la gestione scitta dell'errore..
                        $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                        scrittura_errori($_cosa, $_percorso, $_errori);
                        $_return['errori']['errore'] = "errore";
                    }
		}

		//ora che abbiamo inserito il tutto nella prima nota.. provvedimo ad inserire eventuale scadenza
		//verifichiamo che non ci siano stati errori..
		if (($_causale == "FA") AND ($_return['errori']['errore'] != "errore") AND ($_parametri['segno'] == "P"))
		{
			//verifichiamo le scadenze date dal tipo di pagamento ed provvediamo ad inserirle nello scadenziario.
			//calcoliamo le date..

			$_data_scad = scadenza($_parametri['data_doc'], $_parametri['codpag']);

			//calcoliamo l'importo..

			$_impeff = importi($_parametri['codpag'], $_parametri['imponibile'], $_parametri['totiva'], $_SESSION['totdoc']);

			//divido l'array;
			$_importo = $_impeff['valore'];

			//ora che abbiamo gli importi e le rate provvediamo ad inserire il tutto nello scadenziario..
			$_rate = $_data_scad['rate'];

			for ($_v = 1; $_v <= $_rate; $_v++)
			{
				$_parametri['data_scad'] = $_data_scad[$_v];
				$_parametri['descrizione'] = $_testo;
				$_parametri['importo'] = $_SESSION['totdoc'];
				$_parametri['utente'] = $_SESSION['utente']['codice'];
				$_parametri['descrizione'] = $_testo;
				$_parametri['impeff'] = $_impeff['valore'][$_v];
				$_parametri['nproto'] = $_nproto;
                                $_parametri['suffix_proto'] = $_SESSION['suffix_proto'];
                                $_parametri['status'] = "in attesa";
                                $_parametri['anno'] = $_anno;

				tabella_scadenziario("inserisci", $_percorso, $_parametri);
			}
		}



		//Passiamo il numero della registrazione
		$_return['nreg'] = $_nreg;
		$_return['nproto'] = $_nproto;

		return $_return;
	}
	elseif ($_cosa == "Aggiorna")
	{
		//funzione che mi aggiorna la registrazione
		//bene ora inseriamo il tutto..
		//leggiamo il basket e di pari passo inseriamo il tutto..
		//Eliminiamo la registrazione precedente...
		$_parametri['cosa'] = $_cosa;
		$_return = tabella_primanota("elimina_reg", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

		if ($_return != "true")
		{
			exit($_return);
		}
		else
		{
			$_return = "";

			$res = carrello_primanota("elenco", $_anno, $_rigo, $_tipo_cf, $_codconto, $_dare, $_avere, $_segno, $_iva);

			//ottimo ora con un ciclo di while mattiamo tutto dentro..

			foreach ($res AS $dati)
			{
				// Stringa contenente la query di ricerca...
				//   $query = sprintf("INSERT prima_nota (anno, nreg, data_reg, data_cont, descrizione, causale, conto, desc_conto, dare, avere, status) VALUES (\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\")", $_anno, $_nreg, $_data_reg, $_data_cont, $_testo, $_causale, $dati['conto'], $dati['descrizione'], $dati['dare'], $dati['avere'], "Inserito");
				// Stringa contenente la query di ricerca...
                            
                                $query = "INSERT prima_nota (anno, nreg, data_reg, data_cont, segno, iva,  descrizione, causale, conto, desc_conto, dare, avere, ndoc, anno_doc, suffix_doc, data_doc, tipopag, nproto, anno_proto, suffix_proto, sp_metro, note, status)
                                VALUES ('$_anno', '$_nreg', '$_data_reg', '$_data_cont', '$dati[segno]', '$dati[iva]', '$_testo', '$_causale', '$dati[conto]', '$dati[descrizione]', '$dati[dare]', '$dati[avere]', '$dati[ndoc]', '$dati[anno_doc]', '$dati[suffix_doc]' ,'$dati[data_doc]', '$_parametri[codpag]', '$dati[nproto]', '$dati[anno_proto]', '$dati[suffix_proto]' ,'$_parametri[spesometro]', '$_parametri[note]', 'Inserito')";

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
			}

			//ora che abbiamo inserito il tutto nella prima nota.. provvedimo ad inserire eventuale scadenza
			//verifichiamo che non ci siano stati errori..
			//if (($_causale == "FA") AND ($_return['errori']['errore'] != "errore"))
			if (($_causale == "FA") AND ($_parametri['segno'] == "P"))
			{
				//eliminiamo le scadenze vecchie prima di inserire le nuove..
				//
                    //prepariamo i dati da passere allla variabile $_paramenti
                                $_parametri['nproto'] = $_SESSION['parametri']['nproto'];
                                $_parametri['anno_proto'] = $_SESSION['parametri']['anno_proto'];
                                $_parametri['suffix_proto'] = $_SESSION['parametri']['suffix_proto'];

				$_ris = tabella_scadenziario("elimina_proto", $_percorso, $_parametri);
                                    
                                if($_ris != "OK")
                                {
                                    echo "Errore Eliminazione scadenza\n";
                                }

				//verifichiamo le scadenze date dal tipo di pagamento ed provvediamo ad inserirle nello scadenziario.
				//calcoliamo le date..

				$_data_scad = scadenza($_SESSION['parametri']['data_doc'], $_parametri['codpag']);

				//calcoliamo l'importo..

				$_impeff = importi($_parametri['codpag'], $_parametri['imponibile'], $_parametri['totiva'], $_SESSION['parametri']['avere']);

				//divido l'array;
				$_importo = $_impeff['valore'];

				//ora che abbiamo gli importi e le rate provvediamo ad inserire il tutto nello scadenziario..
				$_rate = $_data_scad['rate'];

				for ($_v = 1; $_v <= $_rate; $_v++)
				{
					$_parametri['data_scad'] = $_data_scad[$_v];
					$_parametri['descrizione'] = $_testo;
					$_parametri['importo'] = $_SESSION['parametri']['avere'];
					$_parametri['utente'] = $_SESSION['utente']['codice'];
					$_parametri['descrizione'] = $_testo;
					$_parametri['impeff'] = $_impeff['valore'][$_v];
					$_parametri['nproto'] = $_SESSION['parametri']['nproto'];
					$_parametri['anno_proto'] = $_SESSION['parametri']['anno_proto'];
                                        $_parametri['suffix_proto'] = $_SESSION['parametri']['suffix_proto'];
					$_parametri['ndoc'] = $_SESSION['parametri']['ndoc'];
					$_parametri['anno_doc'] = $_SESSION['parametri']['anno_doc'];
					$_parametri['data_doc'] = $_SESSION['parametri']['data_doc'];
                                        $_parametri['status'] = "in attesa";
                                        $_parametri['anno'] = $_anno;

					$ris = tabella_scadenziario("inserisci", $_percorso, $_parametri);
                                        
                                        if($ris != "OK")
                                        {
                                            echo "Errore Inserimento scadenziario";
                                        }
				}
			}



			//Passiamo il numero della registrazione
			$_return['nreg'] = $_nreg;
                        $_return['nproto'] = $_parametri['nproto'];
                        $_return['suffix_proto'] = $_parametri['suffix_proto'];
                        $_return['anno_proto'] = $_parametri['anno_proto'];
			return $_return;
		}
	}
	elseif ($_cosa == "basket")
	{
		//funzione che mi permette di inserire la registrazione nel carrello della prima nota.
		//leggiamo la registrazione
		$res = tabella_primanota("elenco", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);
		//ora passiamo ad inserire tutto nell basket..
		//ora con un ciclo di while passiamo dentro tutto

		foreach ($res AS $dati)
		{
			if ($_return['spesometro'] == "")
			{
				$_return['spesometro'] = $dati['sp_metro'];
			}

			$_return['errori']['testo'] = $dati['descrizione'];
			// Stringa contenente la query di ricerca...

			$query = sprintf("INSERT prima_nota_basket (sessionid, anno, nreg, data_reg, data_cont, descrizione, causale, conto, dare, avere, ndoc, anno_doc, suffix_doc, data_doc, segno, iva, tipopag, nproto, anno_proto, suffix_proto, note)
		VALUES (\"%s\", \"%s\", \"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\", \"%s\", \"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\", \"%s\", \"%s\", \"%s\")", $id, $dati['anno'], $dati['nreg'], $dati['data_reg'], $dati['data_cont'], $dati['desc_conto'], $dati['causale'], $dati['conto'], $dati['dare'], $dati['avere'], $dati['ndoc'], $dati['anno_doc'], $dati['suffix_doc'], $dati['data_doc'], $dati['segno'], $dati['iva'], $dati['tipopag'], $dati['nproto'], $dati['anno_proto'], $dati['suffix_proto'], $dati['note']);

                        $result = $conn->exec($query);
                        if ($conn->errorCode() != "00000")
                        {
                            $_errore = $conn->errorInfo();
                            echo $_errore['2'];
                            //aggiungiamo la gestione scitta dell'errore..
                            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                            scrittura_errori($_cosa, $_percorso, $_errori);
                            $_return['errori']['descrizione'] = "Si &egrave; verificato un errore nella query inserimento query tabella nota_basket<br>\n\"$query\"\n";
                            $_return['errori']['errore'] = "errore";
			}
		}

		return $_return;
	}
	elseif ($_cosa == "elimina_reg")
	{

		//Prima di eliminare la registrzione la leggiammo.
		//
                $dati = tabella_primanota("leggi_singolo", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);
		//VERIFICHIAMO CHE NON SIA UNA FA PERCHE BISOGNA CANCELLARE ANCHE LA SCADENZA..
		//CANCELLIAMO PRIMA LA SCEDENZA POI LA REGISTRAZIONE..
		//leggiamo la registrazione..


		if ($dati['causale'] == "FA")
		{
			//ora eliminiamo la scadenza...
			$_parametri['anno_proto'] = $dati['anno_proto'];
			$_parametri['nproto'] = $dati['nproto'];
                        $_parametri['suffix_proto'] = $dati['suffix_proto'];

			$ris = tabella_scadenziario("elimina_proto", $_percorso, $_parametri);
                        
                        if($ris != "OK")
                        {
                            echo "<h3 align=\"center\"> <font color=\"RED\">Errore Eliminazione Scadenziario</font></h3>\n";
                        }
		}

		if ($_parametri['cosa'] != "Aggiorna")
		{
                   
			if ($dati['causale'] == "FV")
			{

				// se è una fattura vendita sblocco la fattura..  solo a livello contbile ma non a livello vendite
				$query = "UPDATE fv_testacalce SET status='evaso', contabilita = 'NO' where anno='$dati[anno_doc]' AND suffix='$dati[suffix_doc]' AND ndoc='$dati[ndoc]' ";
				// Esegue la query...
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
                                    echo "<h3 align=\"center\"> <font color=\"GREEN\">Ripristino Fattura OK</font></h3>\n";
                                }
			}
                        
                        $desc_conto = substr($dati['descrizione'], 0, 8);
                        
                        if($dati['dare'] != "0.00")
                        {
                            $valore = $dati['dare'];
                        }
                        else
                        {
                            $valore = $dati['avere'];
                        }
                        
                        if (($dati['causale'] == "IN") AND ($desc_conto == "Em. eff."))
			{
				// se è una fattura vendita sblocco la fattura..  solo a livello contbile ma non a livello vendite
				$query = "UPDATE effetti SET status='presentato', contabilita = 'NO' where annodoc='$dati[anno_doc]' AND suffixdoc='$dati[suffix_doc]' AND numdoc='$dati[ndoc]' AND impeff=$valore limit 1";
				
                                //echo $query;
                                
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
                                    echo "<h3 align=\"center\"> <font color=\"GREEN\">Ripristino Effetto OK</font></h3>\n";
                                }
			}
                        
                        
                        if (($dati['causale'] == "IN") AND ($desc_conto != "Em. eff."))
			{

				// se è una fattura vendita sblocco la fattura..  solo a livello contbile ma non a livello vendite
				$query = "UPDATE effetti SET status='in attesa', contabilita = 'NO' where annodoc='$dati[anno_doc]' AND suffixdoc='$dati[suffix_doc]' AND numdoc='$dati[ndoc]' AND impeff=$valore limit 1";
				// Esegue la query...
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
                                    echo "<h3 align=\"center\"> <font color=\"GREEN\">Ripristino Effetto OK</font></h3>\n";
                                }
			}
                        
                        
                        
		}

		// Stringa contenente la query di ricerca...
		$query = "DELETE FROM prima_nota WHERE anno='$_anno' AND nreg='$_nreg'";
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
			$_return = "true";
		}

		return $_return;
	}
	elseif ($_cosa == "Inserisci_singolo")
	{
		//questa funzione permettere di inserire una riga singola nella prima nota..
		// Stringa contenente la query di ricerca...
		if ($_parametri['status'] = "")
		{
			$_parametri['status'] == "Inserito";
		}

		$query = sprintf("INSERT prima_nota (anno, nreg, data_reg, data_cont, descrizione, segno,  causale, conto, desc_conto, iva, dare, avere, ndoc, anno_doc, data_doc, tipopag, nproto, anno_proto, note, status)
                VALUES
                (\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\", \"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\", \"%s\", \"%s\", \"%s\")", $_anno, $_nreg, $_data_reg, $_data_cont, $_testo, $_parametri['segno'], $_causale, $_parametri['conto'], $_parametri['desc_conto'], $_parametri['iva'], $_parametri['dare'], $_parametri['avere'], $_parametri['ndoc'], $_parametri['anno_doc'], $_parametri['data_doc'], $_parametri['codpag'], $_nproto, $_parametri['anno_proto'], $_parametri['note'], "Inserito");

		//echo "<br>$query\n";
		// Esegue la query...
		if (mysql_query($query, $conn) != 1)
		{
			$_return['errori']['descrizione'] = "Si &egrave; verificato un errore nella query inserimento query tabella prima_nota Inserisci:<br>\n\"$query\"\n";
			$_return['errori']['errore'] = "errore";
		}



		//Passiamo il numero della registrazione
		$_return['nreg'] = $_nreg;
		$_return['nproto'] = $_nproto;

		return $_return;
	}
	elseif($_cosa == "inserisci_singola")
	{
		//Versione con librerie PDO
		
		//questa funzione permettere di inserire una riga singola nella prima nota..
		// Stringa contenente la query di ricerca...
		if ($_parametri['status'] = "")
		{
			$_parametri['status'] == "Inserito";
		}

		$query = "INSERT prima_nota (anno, nreg, data_reg, data_cont, descrizione, segno,  causale, conto, desc_conto, iva, dare, avere, ndoc, suffix_doc, anno_doc, data_doc, tipopag, nproto, anno_proto, suffix_proto, note, status)
                VALUES ( '$_anno', '$_nreg', '$_data_reg', '$_data_cont', '$_testo', '$_parametri[segno]', '$_causale', '$_parametri[conto]', '$_parametri[desc_conto]', '$_parametri[iva]', '$_parametri[dare]', '$_parametri[avere]', '$_parametri[ndoc]', '$_parametri[suffix_doc]', '$_parametri[anno_doc]', '$_parametri[data_doc]', '$_parametri[codpag]', '$_parametri[nproto]', '$_parametri[anno_proto]', '$_parametri[suffix_proto]' ,'$_parametri[note]', 'Inserito')";

		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
			$_errori['files'] = "motore_primanota.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			$_return['errori'] = $_errori['descrizione'];
                        $_return['result'] = "NO";
		}
		else
		{
			//inserimento corretto..
			$_return['result'] = "$_nreg positiva";
			
		}
		return $_return;
			
	}
	else
	{
		// Stringa contenente la query di ricerca...
		$query = "SELECT * FROM prima_nota WHERE anno='$_anno' AND nreg='$_nreg' ORDER BY anno, rigo";
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

		return $result;
	}
}

//schermate..
function schermate_primanota($_finestra, $_parametri)
{
	global $id;
        global $_percorso;
        global $dec;
        global $conn;
        
	require "../../../setting/par_conta.inc.php";
	require "../../../setting/vars.php";
	//in base al tipo di richiesta compongo la schermata..

	if ($_finestra == "Nuova")
	{
		if ($_parametri == "FA")
		{
			//PRENDO LE COSE E LE SBATTO DENTRO I DATI..

			$_utente = $_SESSION['utente'];
			//$_iva = $_SESSION['parametri']['iva'];
			$_iva_ded = $_SESSION['parametri']['iva_ded'];
			$_segno = $_SESSION['parametri']['segno'];
			$_anno = $_SESSION['anno'];

			//cambiamo ilcodice iva con l'aliquota..'
			$aliquota = tabella_aliquota("singola", $_SESSION['parametri']['iva'], $_percorso);

			//vediamo se è indetraibile;
			$_iva = $aliquota['aliquota'];

			//prima di tutto devo verificare quanti caratteri ha l'aliquota..
			$_numiva = strlen($_iva);

			//Facciamo dei conti provvisori..
			$_totdoc = $_SESSION['totdoc'];

			//ora dividiamo le operazioni per uno o più caratteri
			if ($_numiva > 1)
			{
				$_dividendo = "1.$_iva";
			}
			else
			{
				$_dividendo = "1.0$_iva";
			}

			$_imponibile = $_totdoc / $_dividendo;
			$_imposta = $_totdoc - $_imponibile;

			//a questo punto vediamo se esiste la indeducibilità.. e inserisco un'altra riga con lo stesso conto
			// per vedere con tutto costo..
			if ($_iva_ded != "")
			{
				//togliamo all'iva un po soldi da portare a costo..
				//prendo l'iva e la divido per 100
				$_iva_ind = 100 - $_iva_ded;

				$_valore = $_imponibile / 100;
				$_imponibile = $_valore * $_iva_ded;
				$_imp_costo = $_valore * $_iva_ind;


				$_valore = $_imposta / 100;
				$_imposta = $_valore * $_iva_ded;
				$_costo = $_valore * $_iva_ind;

				if ($_segno == "N")
				{
					//inseriamo le righe all'interno del carrello e poi le elenchiamo e per inserire i valori le facciamo modificare..
					//riga avere perché fornitore
					carrello_primanota("Inserisci", $_anno, $_rigo, "F", $_utente['codice'], $_totdoc, $_avere, $_segno, "");
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $_SESSION['utente']['cod_conto'], $_date, $_imponibile, $_segno, $ivasis);
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $CONTO_IVA_ACQUISTI, $_dare, $_imposta, $_segno, $ivasis);
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $_SESSION['utente']['cod_conto'], $_dare, $_imp_costo, $_segno, $aliquota['codice']);
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $_SESSION['utente']['cod_conto'], $_dare, $_costo, $_segno, "");
				}
				else
				{
					//inseriamo le righe all'interno del carrello e poi le elenchiamo e per inserire i valori le facciamo modificare..
					//riga avere perché fornitore
					carrello_primanota("Inserisci", $_anno, $_rigo, "F", $_utente['codice'], $_dare, $_totdoc, $_segno, "");
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $_SESSION['utente']['cod_conto'], $_imponibile, $_avere, $_segno, $ivasis);
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $CONTO_IVA_ACQUISTI, $_imposta, $_avere, $_segno, $ivasis);
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $_SESSION['utente']['cod_conto'], $_imp_costo, $_avere, $_segno, $aliquota['codice']);
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $_SESSION['utente']['cod_conto'], $_costo, $_avere, $_segno, "");
				}
			}
			else
			{
				//inseriamo le righe all'interno del carrello e poi le elenchiamo e per inserire i valori le facciamo modificare..
				if ($_segno == "N")
				{
					carrello_primanota("Inserisci", $_anno, $_rigo, "F", $_utente['codice'], $_totdoc, $_avere, $_segno, "");
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $_SESSION['utente']['cod_conto'], $_dare, $_imponibile, $_segno, $aliquota['codice']);
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $CONTO_IVA_ACQUISTI, $_dare, $_imposta, $_segno, $aliquota['codice']);
				}
				else
				{
					//riga DARE..
					carrello_primanota("Inserisci", $_anno, $_rigo, "F", $_utente['codice'], $_dare, $_totdoc, $_segno, "");
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $_SESSION['utente']['cod_conto'], $_imponibile, $_avere, $_segno, $aliquota['codice']);
					carrello_primanota("Inserisci", $_anno, $_rigo, "", $CONTO_IVA_ACQUISTI, $_imposta, $_avere, $_segno, $aliquota['codice']);
				}
			}

			schermate_primanota("elenco", $_parametri);

			$_return['finestra'] = "elenco";
			return $_return;
		}
		elseif ($_parametri == "PA")
		{
			//leggo i parametri della registrazione poi vedo se è gia stta pagata in parte e propongo il resto..
			$_utente = $_SESSION['utente'];
			//propongo la prima riga con il saldo della registrazione..
			//
            //inseriamo nel carrello la regitrazione..

			$_dare = $_SESSION['registrazione']['diff'];
			$_anno = $_SESSION['anno'];

			carrello_primanota("Inserisci", $_anno, $_rigo, "F", $_utente['codice'], $_dare, $_avere, $_segno, "");

			schermate_primanota("elenco", $_parametri);

			$_return['finestra'] = "elenco";
			return $_return;
		}
		elseif ($_parametri == "IN")
		{
			//leggo i parametri della registrazione poi vedo se è gia stta pagata in parte e propongo il resto..
			$_utente = $_SESSION['utente'];
			//propongo la prima riga con il saldo della registrazione..
			//
            //inseriamo nel carrello la regitrazione..

			$_avere = $_SESSION['registrazione']['diff'];
			$_anno = $_SESSION['registrazione']['anno'];

			carrello_primanota("Inserisci", $_anno, $_rigo, "C", $_utente['codice'], $_dare, $_avere, $_segno, "");

			schermate_primanota("elenco", $_parametri);

			$_return['finestra'] = "elenco";
			return $_return;
		}
		else
		{
			echo "<form action=\"corpo_nota.php\" id=\"nuova\" method=\"POST\">\n";
			echo "<tr><td>Rigo</td><td>Codice PDC</td><td width=\"50%\">Descrizione</td><td align=\"center\">Dare</td><td align=\"center\">Avere</td><td>Azione</td></tr>\n";
			echo "<tr><td colspan=\"7\" align=\"right\">Inserisci una riga..<input type=\"submit\" name=\"azione\" value=\"nuovariga\"></td></tr>\n";
			echo "</form>";
		}
	}
	elseif ($_finestra == "nuovariga")
	{
		$_return = schermate_primanota("elenco", "");
		echo "<tr><td colspan=\"7\"><hr></td><tr>\n";
		echo "<form action=\"corpo_nota.php\" id=\"myform\" method=\"POST\">\n";
		echo "<tr><td>C. Iva</td><td colspan=\"2\">Tipo conto</td><td>Conto</td><td>Val. Dare</td><td>Val. Avere</td><td>Azione</td></tr>\n";

		$_result = tabella_aliquota("elenca_codice", $_codiva, $_percorso);

		echo "<tr><td><select name=\"iva\">\n";
                if($_SESSION['causale'] == "FA")
                {
                    echo "<option value=\"".$_SESSION[parametri][iva]."\">".$_SESSION[parametri][iva]."</option>\n";
                }
                else
                {
                    echo "<option value=\"\"></option>\n";
                }
		

		
                foreach ($_result AS $_cod_iva)
		{
			echo "<option value=\"$_cod_iva[codice]\">$_cod_iva[codice]</option>\n";
		}


		echo "</select></td>\n";
		echo "<td colspan=\"2\" align=\"left\"><select id=\"tipo_conto\" name=\"tipo_cf\">" . Showtipo_conto() . "</select></td>\n";
		echo "<td align=\"left\" ><select id=\"codconto\" name=\"codconto\"><option>Scegli...</option></select>\n";
		echo "<td align=\"right\"><input type=\"text\" name=\"dare\" value=\"0.00\" size=\"11\" maxlenght=\"10\"></td>\n";
		echo "<td align=\"right\"><input type=\"text\" name=\"avere\" value=\"0.00\" size=\"11\" maxlenght=\"10\"></td>\n";
		echo "<td align=\"center\"><input type=\"submit\" name=\"azione\" value=\"Inserisci\"></td>\n";
		echo "</tr>\n";
		echo "</form>\n";

		return $_return;
	}
	elseif ($_finestra == "causale")
	{
		$_anno = $_SESSION['anno'];
		//inseriamo le righe all'interno del carrello e poi le elenchiamo e per inserire i valori le facciamo modificare..
		//con un ciclo di for conto fino a dieci poi vedo se il campo è pieno bene altrimenti salto..
		//Il parametro parametri contiene l'arrey del ciclo mysql..'
		for ($index = 0; $index <= 10; $index++)
		{
			$_conto = "conto_$index";
			if ($_parametri[$_conto] != "")
			{
				carrello_primanota("Inserisci", $_anno, $_rigo, "", $_parametri[$_conto], $_dare, $_avere, $_segno, $_iva);
			}
		}

		schermate_primanota("elenco", $_parametri);
	}
	elseif ($_finestra == "modifica")
	{
		//proviamo a modificare la riga richiesta..
		//selezioniamo la riga richiesta
		$dati = carrello_primanota("leggi", $_anno, $_parametri, $_tipo_cf, $_codconto, $_dare, $_avere, $_segno, $_iva);

		//schermate_primanota("elenco", "");
		#echo "<tr><td colspan=\"7\"><hr></td><tr>\n";
		echo "<form action=\"corpo_nota.php\" method=\"POST\">\n";
		echo "<tr><td class=\"tabella\">Rigo</td><td class=\"tabella\">IVA</td><td class=\"tabella\">Conto PDC</td><td align=\"left\" class=\"tabella\">Descrizione</td><td class=\"tabella\">Val. Dare</td><td class=\"tabella\">Val. Avere</td><td class=\"tabella\">Azione</td></tr>\n";
		echo "<tr><td class=\"tabella_elenco\"><input type=\"radio\" name=\"rigo\" value=\"$dati[rigo]\" checked>$dati[rigo]</td>\n";

		echo "<td class=\"tabella_elenco\">\n";
                
                tabella_aliquota("elenca_select_numeri", $dati[iva], "iva");

                echo "</td>\n";
		echo "<td class=\"tabella_elenco\">$dati[conto]</td>\n";
		echo "<td width=\"40%\" align=\"left\" class=\"tabella_elenco\">$dati[descrizione]\n";
		echo "<td class=\"tabella_elenco\" align=\"right\"><input type=\"text\" name=\"dare\" value=\"$dati[dare]\" size=\"11\" maxlenght=\"10\"></td>\n";
		echo "<td class=\"tabella_elenco\" align=\"right\"><input type=\"text\" name=\"avere\" value=\"$dati[avere]\" size=\"11\" maxlenght=\"10\"></td>\n";
		echo "<td class=\"tabella_elenco\" align=\"center\"><input type=\"submit\" name=\"azione\" value=\"Aggiorna\"></td>\n";
		echo "</tr>\n";
		echo "</form>\n";
	}
	else
	{	 //elenchiamo il carrello..
		echo "<tr><td class=\"tabella\">Rigo</td><td class=\"tabella\">Iva</td><td class=\"tabella\">Codice PDC</td><td class=\"tabella\" width=\"50%\">Descrizione</td><td class=\"tabella\" align=\"center\">Dare</td><td align=\"center\" class=\"tabella\">Avere</td><td align=\"center\" class=\"tabella\">Azione</td></tr>\n";
		$dati_carr = carrello_primanota("elenco", $_anno, $_rigo, $_tipo_cf, $_codconto, $_dare, $_avere, $_segno, $_iva);

                foreach ($dati_carr AS $dati)
		{
			if ($dati['dare'] == "0.00")
			{
				$_dare_vis = "&nbsp;";
			}
			else
			{
				$_dare_vis = $dati['dare'];
			}
			if ($dati['avere'] == "0.00")
			{
				$_avere_vis = "&nbsp;";
			}
			else
			{
				$_avere_vis = $dati['avere'];
			}
			echo "<form action=\"corpo_nota.php\" method=\"POST\"><tr><td class=\"tabella_elenco\"><input type=\"radio\" name=\"rigo\" value=\"$dati[rigo]\" checked >$dati[rigo]</td><td class=\"tabella_elenco\">$dati[iva]</td><td class=\"tabella_elenco\">$dati[conto]</td><td class=\"tabella_elenco\" align=\"left\" width=\"50%\">$dati[descrizione]</td><td class=\"tabella_elenco\" align=\"right\">$_dare_vis</td><td class=\"tabella_elenco\" align=\"right\">$_avere_vis</td><td class=\"tabella_elenco\" align=\"center\" width=\"20%\"><input type=\"submit\" name=\"azione\" value=\"modifica\"><input type=\"submit\" name=\"azione\" value=\"elimina\" onclick=\"if(!confirm('Sicuro di voler eliminare la riga ?')) return false;\" ></form></td></tr>\n";
			$_dare = $_dare + $dati['dare'];
			$_avere = $_avere + $dati['avere'];
		}

		$_return['sbilanciamento']['dare'] = $_dare;
		$_return['sbilanciamento']['avere'] = $_avere;
		return $_return;
	}
}

function schermate_calcenota($_finestra, $_parametri)
{
    global $conn;
    global $_percorso;
    
	require "../../../setting/par_conta.inc.php";
	require_once "../../librerie/motore_anagrafiche.php";
	//*** funzione che mi compone la finestra della calce del deocumento prima nota ecc.
	if ($_finestra == "FA")
	{
		$_data_reg = $_SESSION['datareg'];
		$_data_cont = $_SESSION['datacont'];
		$_testo = $_SESSION['testo'];
		$_causale = $_SESSION['causale'];
		$_anno = $_SESSION['anno'];
		$_submit = $_SESSION['submit'];
		$_nreg = $_SESSION['nreg'];
		$_utente = $_SESSION['utente'];
                $_suffix_proto = $_SESSION['suffix_proto'];

		//vediamo se stiamo inserendo oppure aggiornando
		if ($_parametri != "Modifica")
		{
			//vuol dire che è standard..
			$_nreg = tabella_primanota("ultimo_numero", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);
			$_nproto = tabella_primanota("ultimo_proto", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_suffix_proto, $_percorso);
			echo "<tr><td colspan=\"3\"><br>Numero Registrazione = <input type=\"number\" name=\"nreg\" value=\"$_nreg\" size=\"7\" maxlenght=\"6\"></td>\n";
			echo "<td colspan=\"3\"><br>Anno  Registrazione = <input type=\"number\" name=\"anno\" value=\"$_anno\" size=\"7\" maxlenght=\"4\"></td></tr>\n";
			echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
			echo "<tr><td colspan=\"3\"><br>Numero protocollo = <input type=\"number\" name=\"nproto\" value=\"$_nproto\" size=\"7\" maxlenght=\"6\"> Suffiso <input type=\"radio\" name=\"suffix_proto\" value=\"$_suffix_proto\" checked>$_suffix_proto</td>\n";
			echo "<td colspan=\"3\"><br>Anno  Protocollo = <input type=\"number\" name=\"anno_proto\" value=\"$_anno\" size=\"7\" maxlenght=\"4\"></td></tr>\n";
			echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
			echo "<tr><td colspan=\"2\"><br>Numero Fattura Fornitore = <input type=\"text\" name=\"ndoc\" size=\"7\" maxlenght=\"6\"></td>\n";
			echo "<td colspan=\"2\"><br>Anno Fattura<input type=\"number\" name=\"anno_doc\" size=\"7\" maxlenght=\"4\"></td>\n";
			echo "<td colspan=\"2\"><br>Data = <input type=\"text\" class=\"data\" name=\"data_doc\" size=\"11\" maxlenght=\"10\"></td></tr>\n";
			echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
			$_codpag = $_utente['codpag'];
		}
		else
		{
			echo "<tr><td colspan=\"3\"><br>Numero Registrazione = <input type=\"radio\" name=\"nreg\" value=\"$_nreg\" checked>$_nreg</td>\n";
			echo "<td colspan=\"3\"><br>Anno  Registrazione = <input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>$_anno</td></tr>\n";
			echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
			echo "<tr><td colspan=\"3\"><br>Numero protocollo = " . $_SESSION[parametri][nproto] . "/" . $_SESSION[parametri][suffix_proto] . "</td>\n";
			echo "<td colspan=\"3\"><br>Anno  Protocollo = " . $_SESSION[parametri][anno_proto] . "</td></tr>\n";
			echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
			echo "<tr><td colspan=\"2\"><br>Numero Fattura Fornitore = " . $_SESSION[parametri][ndoc] . "</td>\n";
			echo "<td colspan=\"2\"><br>Anno Fattura " . $_SESSION[parametri][anno_doc] . "</td>\n";
			echo "<td colspan=\"2\"><br>Data = " . $_SESSION[parametri][data_doc] . "</td></tr>\n";
			echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
			$_codpag = $_SESSION['parametri']['tipopag'];
		}

		//elenchiamo le scadenze...

		echo "<tr><td colspan=\"3\">Verifica Pagamento <br>\n";
                
                tabella_pagamenti("elenca_select_2", $_codpag, "codpag");
        
		echo "</td>";

		echo "<td colspan=\"3\">Verifica Banca <br>\n";
		echo "<select name=\"banca\">\n";
		$dati = tabella_banche("singola_abi", "", $_utente['abi'], $_utente['cab'], $_parametri);
		printf("<option value=\"%s\">%s</option>\n", $dati['codice'], $dati['banca']);
		

                //inserisco una riga vuota..
		echo "<option value=\"\"></option>\n";
		$res = tabella_banche("elenca", "", "", "", "");
		foreach ($res AS $dati)
		{
			printf("<option value=\"%s\">%s</option>\n", $dati['codice'], $dati['banca']);
		}

		echo "</select>\n";

		echo "</td></tr>";
	}
	else
	{
		$_data_reg = $_SESSION['datareg'];
		$_data_cont = $_SESSION['datacont'];
		$_testo = $_SESSION['testo'];
		$_causale = $_SESSION['causale'];
		$_anno = $_SESSION['anno'];
		$_submit = $_SESSION['submit'];
		$_nreg = $_SESSION['nreg'];
		$_segno = $_SESSION['parametri']['segno'];

		//vediamo se stiamo inserendo oppure aggiornando
		if ($_parametri != "Modifica")
		{
			//vuol dire che è standard..
			$_tocca = tabella_primanota("ultimo_numero", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);
		}


		if ($_parametri == "Modifica")
		{
			echo "<tr><td colspan=\"3\"><br>Numero Registrazione = <input type=\"radio\" name=\"nreg\" value=\"$_nreg\" size=\"7\" maxlenght=\"6\" checked>$_nreg</td>\n";
			echo "<td colspan=\"3\"><br>Anno  Registrazione = <input type=\"radio\" name=\"anno\" value=\"$_anno\" size=\"7\" maxlenght=\"4\" checked>$_anno</td></tr>\n";
			echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
		}
		elseif ($_parametri == "Salda")
		{
			echo "<tr><td colspan=\"3\"><br>Numero Registrazione = <input type=\"radio\" name=\"nreg\" value=\"$_nreg\" size=\"7\" maxlenght=\"6\" checked>$_nreg</td>\n";
			echo "<td colspan=\"3\"><br>Anno  Registrazione = <input type=\"radio\" name=\"anno\" value=\"$_anno\" size=\"7\" maxlenght=\"4\" checked>$_anno</td></tr>\n";
			echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
			echo "<td colspan=\"6\"><br>Chiusura forzata registrazione = <select name=\"segno\">\n";
			echo "<option value=\"$_segno\">$_segno</option>\n";
			echo "<option value=\"P\">Postitiva - Fattura</option>\n";
			echo "<option value=\"N\">Negativa - Nota Credito</option>\n";
			echo "<option value=\"C\">Chiusura - Chiude il documento come pagato</option>\n";
			echo "</td></tr>\n";
		}
		else
		{
			echo "<tr><td colspan=\"3\"><br>Numero Registrazione = <input type=\"number\" name=\"nreg\" value=\"$_tocca\" size=\"7\" maxlenght=\"6\"></td>\n";
			echo "<td colspan=\"3\"><br>Anno  Registrazione = <input type=\"number\" name=\"anno\" value=\"$_anno\" size=\"7\" maxlenght=\"4\"></td></tr>\n";
			echo "<tr><td colspan=\"6\"><hr></td></tr>\n";
		}
	}

	return $_note;
}

function schermate_visualizza_reg($_causale, $_anno, $_nreg, $_parametri)
{
	if ($_causale == "ST")
	{
		//apriamo una tabella nuova..
		echo "<table align=\"center\" width=\"80%\" cellspacing=\"0\" border=\"0\">\n";
		//elenchiamo il carrello..
		echo "<tr><td align=\"center\" class=\"tabella\">Rigo</td>
            <td class=\"tabella\">Codice PDC</td>
            <td align=\"left\" width=\"50%\" class=\"tabella\">Descrizione</td>
            <td align=\"center\" class=\"tabella\">Dare</td>
            <td align=\"center\" class=\"tabella\">Avere</td></tr>\n";
		$dati_carr = tabella_primanota("elenco", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

		foreach ($dati_carr AS $dati)
		{
			if ($dati['dare'] == "0.00")
			{
				$_dare_vis = "&nbsp;";
			}
			else
			{
				$_dare_vis = $dati['dare'];
			}
			if ($dati['avere'] == "0.00")
			{
				$_avere_vis = "&nbsp;";
			}
			else
			{
				$_avere_vis = $dati['avere'];
			}
			echo "<tr><td align=\"center\" class=\"tabella_elenco\">$dati[rigo]</td><td class=\"tabella_elenco\">$dati[conto]</td><td align=\"left\" width=\"50%\" class=\"tabella_elenco\">$dati[desc_conto]</td><td align=\"right\" class=\"tabella_elenco\">$_dare_vis</td><td align=\"right\" class=\"tabella_elenco\">$_avere_vis</td></tr>\n";
		}
		echo "<tr><td colspan=\"5\"><hr></td></tr>\n";
	}
	else
	{
		//apriamo una tabella nuova..
		echo "<table align=\"center\" width=\"80%\" cellspacing=\"0\" border=\"0\">\n";
		//visualizziamo i parametri della registrazione
		echo "<tr><td align=\"center\" class=\"tabella\">Segno</td>
            <td class=\"tabella\">Protocollo</td>
            <td class=\"tabella\">Anno</td>
            <td width=\"50%\" class=\"tabella\">Documento</td>
            <td colspan=\"2\" align=\"center\" class=\"tabella\">Cod. Pagamento</td>
            </tr>\n";
		echo "<tr><td align=\"center\" class=\"tabella_elenco\">$_parametri[segno]</td>
            <td class=\"tabella_elenco\" align=\"center\">$_parametri[nproto] / $_parametri[suffix_proto]</td>
            <td class=\"tabella_elenco\" align=\"center\">$_parametri[anno_proto]</td>
            <td align=\"left\" width=\"50%\" class=\"tabella_elenco\">$_parametri[ndoc]-$_parametri[suffix_doc] / $_parametri[anno_doc] del $_parametri[data_doc]</td>
            <td align=\"center\" class=\"tabella_elenco\">$_parametri[tipopag]</td>
            <td align=\"center\" class=\"tabella_elenco\"></td></tr>\n";
		//elenchiamo il carrello..
		echo "<tr><td align=\"center\" class=\"tabella\">Rigo</td>
            <td class=\"tabella\">Codice PDC</td>
            <td class=\"tabella\">Iva</td>
            <td width=\"50%\" class=\"tabella\">Descrizione</td>
            <td align=\"center\" class=\"tabella\">Dare</td>
            <td align=\"center\" class=\"tabella\">Avere</td></tr>\n";
		$dati_carr = tabella_primanota("elenco", $id, $_anno, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

		foreach ($dati_carr AS $dati)
		{
			if ($dati['dare'] == "0.00")
			{
				$_dare_vis = "&nbsp;";
			}
			else
			{
				$_dare_vis = $dati['dare'];
			}
			if ($dati['avere'] == "0.00")
			{
				$_avere_vis = "&nbsp;";
			}
			else
			{
				$_avere_vis = $dati['avere'];
			}
			echo "<tr><td align=\"center\" class=\"tabella_elenco\">$dati[rigo]</td><td class=\"tabella_elenco\">$dati[conto]</td><td align=\"center\" class=\"tabella_elenco\">$dati[iva]</td><td width=\"50%\" align=\"left\" class=\"tabella_elenco\">$dati[desc_conto]</td><td align=\"right\" class=\"tabella_elenco\">$_dare_vis</td><td align=\"right\" class=\"tabella_elenco\">$_avere_vis</td></tr>\n";
		}
		echo "<tr><td valign=\"top\" colspan=\"6\"><hr></td></tr>\n";
	}
	echo "</table>\n";
}

function Showtipo_conto()
{
	$_tipo_conto .= '<option value="0">Scegli tipo conto</option>';
	$_tipo_conto .= '<option value="N">Numero conto</option>';
	$_tipo_conto .= '<option value="A">Piano dei conti</option>';
	$_tipo_conto .= '<option value="B">Banche</option>';
	$_tipo_conto .= '<option value="C">Clienti</option>';
	$_tipo_conto .= '<option value="F">Fornitori</option>';

	return $_tipo_conto;
}

function Showcausale()
{
	global $conn;

	$_causale .= '<option value="0">scegli tipo Registrazione</option>';
	$_causale .= '<option value="ST">ST - Registrazione Standard</option>';
	$_causale .= '<option value="IN">IN - Incasso Fattura Vendita</option>';
	$_causale .= '<option value="PA">PA - Pagamento fatture acquisto</option>';

	return $_causale;
}

/* * *
 * Funzione che mi annulla il documento in corso..
 */

function annulla_doc($id)
{
	//la funzione mi annulla il documento in coso..
	global $conn;
        global $_percorso;
        
	//annulllimo il carrello

	$query = "DELETE FROM prima_nota_basket where sessionid='$id'";

	$result = $conn->exec($query);
        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

	elimina_sessioni();
}

function elimina_sessioni()
{

	//ok ottimo..
	//Ora elimino tutte le sessioni in corso..
	unset($_SESSION['testo']);
	unset($_SESSION['datareg']);
	unset($_SESSION['datacont']);
	unset($_SESSION['causale']);
	unset($_SESSION['anno']);
	unset($_SESSION['submit']);
	unset($_SESSION['nreg']);
	unset($_SESSION['utente']);
	unset($_SESSION['totdoc']);
	unset($_SESSION['parametri']);
	unset($_SESSION['registrazione']);
        unset($_SESSION['suffix_proto']);
}

function Showcodconto()
{
	global $conn;
        global $_percorso;

	if ($_POST['tipo_cf'] == "C")
	{
		$query = "SELECT codice, substring(ragsoc,1,40) AS ragsoc FROM clienti WHERE es_selezione != 'SI' ORDER BY ragsoc";
                
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

		foreach ($result AS $row)
		{
			$_codconto .= printf("<option value=\"%s\">%s</option>", $row['codice'], $row['ragsoc']);
			//$_codconto .= '<option value="' . $row['codice'] . '">' . $row['ragsoc'] . '</option>';
		}
	}
	elseif ($_POST['tipo_cf'] == "F")
	{
		$query = "SELECT codice, substring(ragsoc,1,40) AS ragsoc FROM fornitori WHERE es_selezione != 'SI'  ORDER BY ragsoc";
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

		foreach ($result AS $row)
		{
			$_codconto .= '<option value="' . $row['codice'] . '">' . $row['ragsoc'] . '</option>';
		}
	}
	elseif ($_POST['tipo_cf'] == "A")
		{
		$query = "SELECT codconto, substring(descrizione,1,40) AS descrizione FROM piano_conti WHERE livello >= '2' AND tipo_cf='$_POST[tipo_cf]' order by descrizione";
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

                    foreach ($result AS $row)
                    {
                            $_codconto .= '<option value="' . $row['codconto'] . '">' . $row['descrizione'] . '-' . $row['codconto'] . '</option>';
                    }
                }
		elseif ($_POST['tipo_cf'] == "B")
		{
		$query = "SELECT codconto, substring(descrizione,1,40) AS descrizione FROM piano_conti WHERE livello >= '2' AND tipo_cf='$_POST[tipo_cf]' order by descrizione";
                
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

		foreach ($result AS $row)
		{
			$_codconto .= '<option value="' . $row['codconto'] . '">' . $row['descrizione'] . '-' . $row['codconto'] . '</option>';
		}
	}
	else
	{
		$query = "SELECT codconto, substring(descrizione,1,40) AS descrizione FROM piano_conti WHERE livello >= '2' order by codconto";
		
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

		foreach ($result AS $row)
		{
			$_codconto .= '<option value="' . $row['codconto'] . '">' . $row['codconto'] . '-' . $row['descrizione'] . '</option>';
		}
	}
	
	return $_codconto;
}

function Showutente()
{
	global $conn;
	global $MASTRO_FOR;

	if ($_POST['causale'] == "IN")
	{
		$query = "SELECT codice, ragsoc FROM clienti ORDER BY ragsoc";
                $result = $conn->query($query);
                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }
		
		$_utente .= '<option value="0">Scegli il cliente</option>';
                
		foreach ($result AS $row)
		{
			$_utente .= printf("<option value=\"%s%s\">%s</option>", $_POST['causale'], $row['codice'], $row['ragsoc']);
		}
	}
	if ($_POST['causale'] == "PA")
	{
		$query = "SELECT codice, ragsoc FROM fornitori ORDER BY ragsoc";
                $result = $conn->query($query);
		if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }
		$_utente .= '<option value="0">Scegli il cliente</option>';

		foreach ($result AS $row)
		{
			$_utente .= printf("<option value=\"%s%s%s\">%s</option>", $_POST['causale'], $MASTRO_FOR, $row['codice'], $row['ragsoc']);
		}
	}
	else
	{
		$_utente .= '<option value="ST">A - Standard</option>';

		$_utente .= '<option value="EL">Oppure Scegli la causale</option>';
	}


	return $_utente;
}

function Showndoc()
{
	global $conn;
	require "../../../setting/par_conta.inc.php";

	$_cosa = substr($_POST['utente'], '0', '2');
	$_utente = substr($_POST['utente'], '2', '10');

	if ($_cosa == "EL")
	{
		$query = "SELECT * FROM causali_contabili ORDER BY descrizione";
		$result = $conn->query($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }

		$_ndoc .= '<option value="0">.Scegli la causale</option>';

		foreach ($result AS $row)
		{
			$_ndoc .= printf("<option value=\"%s\">%s</option>", $row['causale'], $row['descrizione']);
		}
	}
	elseif ($_cosa == "IN")
	{
		$_utente = $MASTRO_CLI . $_utente;
		$query = "SELECT *, (SUM(avere) - SUM(dare)) AS diff , date_format(data_doc, '%d-%m-%Y') as dataita FROM prima_nota where conto = '$_utente' GROUP BY ndoc, suffix_doc ORDER BY ndoc";
		$result = $conn->query($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }

		$_ndoc .= '<option value="0">Scegli il documento</option>';

		foreach ($result AS $row)
		{
			if ($row['diff'] != "0.00")
			{
				$_ndoc = printf("<option value=\"%s%s%s\">Fattura vendita nr. %s/%s del  %s Importo = %s </option>", $row['anno_doc'], $row['suffix_doc'], $row['ndoc'], $row['ndoc'],$row['suffix_doc'], $row['dataita'], $row['diff']);
			}
		}
	}
	elseif ($_cosa == "PA")
	{
		#$sql = "SELECT * , (SUM( dare ) - SUM( avere ) ) AS diff FROM prima_nota WHERE conto = '$_utente' GROUP BY nproto ORDER BY nproto";
		$query = "SELECT * , (SUM( dare ) - SUM( avere ) ) AS diff, date_format(data_doc, '%d-%m-%Y') as dataita FROM prima_nota WHERE (causale='FA' OR causale='PA') AND conto = '$_utente' GROUP BY anno_proto, suffix_proto, nproto ORDER BY anno_proto, suffix_proto, nproto";
		$result = $conn->query($query);

                if ($conn->errorCode() != "00000")
                {
                    $_errore = $conn->errorInfo();
                    echo $_errore['2'];
                    //aggiungiamo la gestione scitta dell'errore..
                    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                    $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
                    scrittura_errori($_cosa, $_percorso, $_errori);
                }

		$_ndoc .= '<option value="0">Scegli il documento</option>';

		foreach ($result AS $row)
		{
			if ($row['diff'] != "0.00")
			{
				$_ndoc = printf("<option value=\"%s%s%s\">Fattura acquisto nr. %s del  %s protocollo nr. %s/%s anno %s Valore %s</option>", $row['anno_proto'], $row['suffix_proto'], $row['nproto'], $row['ndoc'], $row['dataita'], $row['nproto'], $row['suffix_proto'], $row['anno_proto'], $row['diff']);
			}
		}
	}
	else
	{
		$_ndoc .= '<option value="ST">Standard</option>';
	}


	return $_ndoc;
}

?>