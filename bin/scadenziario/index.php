<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */
if($_primoingresso != "SI")
{

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso . "../setting/vars.php";

session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carico le librerie necessarie
require "../librerie/motore_anagrafiche.php";

}

base_html("", $_percorso);
//java_script($_cosa, $_percorso);
//jquery_datapicker($_cosa, $_percorso);
echo "</head>\n";

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['scadenziario'] > "1")
{
    echo "<table width=\"100%\">\n";
    
    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
            
    $monthNames = Array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre");
    
    //tra le variabili globali definiamo..
    //larghezza tabella;
    $Tablewight = "90%";
    //altezza celle
    $Cellheight = "60px";
    $Cellwidth = "100px";
    

    if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
    if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");

    $cMonth = $_REQUEST["month"];
    $cYear = $_REQUEST["year"];

    $prev_year = $cYear;
    $next_year = $cYear;
    $prev_month = $cMonth-1;
    $next_month = $cMonth+1;

    if ($prev_month == 0 ) {
        $prev_month = 12;
        $prev_year = $cYear - 1;
    }
    if ($next_month == 13 ) {
        $next_month = 1;
        $next_year = $cYear + 1;
    }
    
    
    echo "<table width=\"$Tablewight\" align=\"center\">\n";
        ?>
        <tr align="center">
            <td bgcolor="#999999" style="color:#FFFFFF"><big>Calendario delle scadenze</big>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" align="left">  <a href="<?php echo $_SERVER["PHP_SELF"] . "?month=" . $prev_month . "&year=" . $prev_year; ?>" style="color:#FFFFFF">Precedente</a></td>
                        <td width="50%" align="right"><a href="<?php echo $_SERVER["PHP_SELF"] . "?month=" . $next_month . "&year=" . $next_year; ?>" style="color:#FFFFFF">Prossimo</a>  </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center">
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <tr align="center">
                        <td colspan="7" bgcolor="#999999" style="color:#FFFFFF"><strong><?php echo $monthNames[$cMonth - 1] . ' ' . $cYear; ?></strong></td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#999999" style="color:#FFFFFF"><strong>Lunedì</strong></td>
                        <td align="center" bgcolor="#999999" style="color:#FFFFFF"><strong>Martedì</strong></td>
                        <td align="center" bgcolor="#999999" style="color:#FFFFFF"><strong>Mercoledì</strong></td>
                        <td align="center" bgcolor="#999999" style="color:#FFFFFF"><strong>Giovedì</strong></td>
                        <td align="center" bgcolor="#999999" style="color:#FFFFFF"><strong>Venerdì</strong></td>
                        <td align="center" bgcolor="#999999" style="color:#FFFFFF"><strong>Sabato</strong></td>
                        <td align="center" bgcolor="#999999" style="color:#FFFFFF"><strong>Domenica</strong></td>
                    </tr>
                    <?php
                    
                    $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                    $maxday = date("t",$timestamp);
                    $thismonth = getdate ($timestamp);
                    $startday = $thismonth['wday'];
                    $oggi = date('Ynj');
                    if($startday == "0")
                    {
                        $startday = "6";
                    }
                    else
                    {
                        $startday--;
                    }
                    //echo $startday;
                    //echo $maxday;
                    //echo $maxday+$startday;
                    for ($i=0; $i<($maxday+$startday); $i++)
                    {
                        //echo $i;
                        if(($i % 7) == 0 )
                        {
                            echo "<tr>\n";
                        }
                        
                        if($i < $startday)
                        {
                            echo "<td></td>\n";
                        }
                        else
                        {
                            
                            if(($i % 7) == "6")
                            {
                                echo "<td bgcolor=\"green\" align='center' valign='top' width=\"$Cellwidth\" height=\"$Cellheight\">\n";
                                echo "<a href=\"".$_directory."scadenziario.php?giorno=".($thismonth['year']."-".$thismonth['mon']."-".($i - $startday + 1))."\">\n";
                                echo "<font size=\"4\">". ($i - $startday + 1) . "</font></a>\n";
                               
                            }
                            elseif(($i % 7) == "5")
                            {
                                echo "<td bgcolor=\"ciain\" align='center' valign='top' width=\"$Cellwidth\" height=\"$Cellheight\">\n";
                                echo "<a href=\"".$_directory."scadenziario.php?giorno=".($thismonth['year']."-".$thismonth['mon']."-".($i - $startday + 1))."\">\n";
                                echo "<font size=\"4\">". ($i - $startday + 1) . "</font></a>\n";
                               
                            }
                            elseif(($thismonth['year'].$thismonth['mon'].($i - $startday + 1)) == $oggi)
                            {
                               echo "<td align='center' bgcolor=\"#FFCCFF\" valign='top' width=\"$Cellwidth\" height=\"$Cellheight\">\n";
                               echo "<a href=\"".$_directory."scadenziario.php?giorno=".($thismonth['year']."-".$thismonth['mon']."-".($i - $startday + 1))."\">\n";
                               echo "<font size=\"4\"><b>". ($i - $startday + 1) . "</b></font></a>\n";
                               
                            }
                            else
                            {
                               echo "<td align='center' valign='top' width=\"$Cellwidth\" height=\"$Cellheight\">\n";
                               echo "<a href=\"".$_directory."scadenziario.php?giorno=".($thismonth['year']."-".$thismonth['mon']."-".($i - $startday + 1))."\">\n";
                               echo "<font size=\"4\">". ($i - $startday + 1) . "</font></a>\n";
                               
                               
                            }
                            
                            //uguale a tutte le celle...
                            
                            $result = tabella_scadenziario("data_singola", $_percorso, ($thismonth['year']."-".$thismonth['mon']."-".($i - $startday + 1)));
                               //echo "<ul align=\"left\"><font size=\"2\">\n";
                            

                               // echo "<font size=\"1\">\n";
                               foreach ($result AS $dati)
                               {
                                  echo "<li style=\"font-size: 0.7em;\" align=\"left\">$dati[descrizione]</li>\n"; 
                               }

                               echo "</font>\n";
                               //echo "</ul>\n";
                               
                               echo "</td>\n"; 

                            
                        }
                        
                        if(($i % 7) == 6 )
                        {
                            echo "</tr>\n";
                        }
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
    <?php
    echo "</td></tr></table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>