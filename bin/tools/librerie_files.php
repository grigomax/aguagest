<?php

//librerie separata che utilizzo quando mi serve per interagire con i file di
//sistema


function gestisci_directory($_cosa, $_directory, $_ritorno)
{
    global $_percorso;
    
    
    if($_cosa == "crea")
    {
        if (!is_dir($_percorso.$_directory))
        {
            if(mkdir($_percorso.$_directory, 0755))
            {
                $return = "OK";
            }
            else
            {
                $return = "NO";
            }
        }
        else
        {
            $return = "exist";
        }
        
        
    }
    
    
    if($_ritorno == "verbose")
    {
       if($return == "OK")
        {
            echo "<h3>Creazione cartella $_directory riuscita</h3>\n";
        }
        elseif($return == "exist")
        {
            echo "<h3>Cartella $_directory è già esistente</h3>\n";
        }
        else
        {
            echo "<h3>Impossibile Creare la cartella $_directory</h3>\n";
        }
    }
    
    
    
    
    
    
    return $return;
}


function copia_tutto($src, $dest)
{


    if (!is_dir($dest))
    {
        echo "<br>Creazione Cartella\n";
        mkdir($dest, 0755);
    }
    else
    {
        echo "<br>Cartella Esistente\n";
    }


    foreach (scandir($src) as $file)
    {
        //echo "<br>leggiamo il contenuto $file\n";
       // if (!is_readable($src . '/' . $file))
         //   continue;
        
        //escludiamo il fatto dei punti..
                
        if (($file != '.') AND ($file != '..'))
        {
            //qui se il primo a leggere è una directory entriamo..
           // echo "<br>Rileggiamo il contenuto $file\n";
            
            //echo "<br>".is_dir($src.'/'.$file);
            
            if (is_dir($src.'/'.$file))
            {
                echo "<br>Creazione Cartella $file\n";
                mkdir($dest . '/' . $file, 0755);
                copia_tutto($src . '/' . $file, $dest . '/' . $file);
            }
            else
            {
                copy($src . '/' . $file, $dest . '/' . $file);
                echo "<br>Copia in corso.. $src / $file\n";
            }
   
        }

    }
}

function svuota_cartella($dirpath)
{
    $handle = opendir($dirpath);
    while (($file = readdir($handle)) !== false)
    {
        if (($file != '.') AND ($file != '..'))
        {
            if (is_dir($dirpath.'/'.$file))
            {
                svuota_cartella($dirpath.'/'.$file);
                
                //rmdir($dirpath.'/'.$file);
            }
            else
            {
                if (unlink($dirpath .'/'.$file))
                {
                    echo "Cancellato: " . $file . "<br/>";
                }
                else
                {
                    echo "Impossibile cancellare " . $file . "<br/>";
                }
            }
            
        }
    }
    closedir($handle);
    
    if(rmdir($dirpath))
    {
        echo "<br>Rimozione Cartella $dirpath riuscita..";
    }
    else
    {
        echo "<br>Impossibile rimuovere completamente la cartella $dirpath <br>Alcuni file non possono essere eliminati..\n";
    }
}


?>
