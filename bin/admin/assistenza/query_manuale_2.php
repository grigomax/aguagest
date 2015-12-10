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
require "../../librerie/motore_anagrafiche.php";


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


    echo "<h2 align=\"center\"> Inserisci query manuale da inviare al database</h2>\n";
    echo "<center>\n";
    echo $_POST['query'];
    
    $query = "$_POST[query]";
    
    if($_POST['azione'] == "exec")
    {
        $result = $conn->exec($query); 
    }
    else
    {
       $result = $conn->query($query); 
    }
    

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }
        else
        {
            echo "<br>Connessione eseguita\n";
            echo "<br> Numero righe  ". $result->rowCount()."\n";
            
            if(stristr($_POST['query'], "SELECT") != FALSE)
            {
                echo "<br>vediamo i risultati";
                
                echo "<table width=\"95%\" border=\"1\" align=\"center\">\n";
                foreach ($result AS $dati)
                {
                    echo "<tr>\n";
                   $for = count($dati);
                   for ($index = 0; $index <= $for; $index++)
                   {
                       echo "<td>\n";
                       echo $dati[$index];
                       echo "</td>\n";
                       
                   }                   
                   
                   echo "</tr>\n";
                }
                
                echo "</table>\n";
                
                
            }
            
            
            
            
        }
    
        
        
        
        
        
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>
