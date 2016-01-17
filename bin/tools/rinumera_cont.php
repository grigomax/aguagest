<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso . "../setting/vars.php";
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../librerie/motore_primanota.php";

//carichiamo la base delle pagine:
base_html("", $_percorso);

java_script($_cosa, $_percorso);

jquery_menu_cascata("scheda", $_percorso);

jquery_datapicker($_cosa, $_percorso);
echo "</head>\n";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

    echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\" border=\"0\">\n";
	echo "<tr>\n";
        echo "<td>\n";
        
        
        //inizio rinumerazione..
        
        
        $query = "SELECT * FROM prima_nota where anno='2016' GROUP BY nreg order by nreg, rigo";
        
        $result = domanda_db("query", $query, $_ritorno, "verbose");
                
        $_nreg_new = "0";
        
        foreach ($result AS $dati)
        {
            //qui iniziamo a riscrivere i dati nuovi
            $_nreg_new++;
            
            $query = "UPDATE prima_nota SET nreg='$_nreg_new' where nreg='$dati[nreg]' AND anno='2016'";
            
            $risultato = domanda_db("exec", $query, $_ritorno, "verbose");
            
            if($risultato != "NO")
            {
                echo "<br>Riga modificata da $dati[nreg] a $_nreg_new\n";
            }
            else
            {
                echo "<br>Riga non modificata per registrazione n. $dati[nreg]";
            }
            
            
            
            
        }
    
    
    
    	echo "</td>\n</tr>\n";

	echo "</table>\n";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>