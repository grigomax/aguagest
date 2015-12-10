<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 */

//facciamo partire la sesssione..
session_start();
$_SESSION['keepalive'] ++;
//carichiamo la base del programma includendo i file minimi
//prima di tutto bisogna verificare l'installazione..
$_percorso = "";
require "librerie/lib_html.php";

//verifichiamo chi entra..
//vediamo se è il primo ingresso
if ($_POST['entra'] == "Entra")
{
    $user = $_POST['user'];
    $password = $_POST['password'];

    if ($user == '')
    {
        $_errori['descrizione'] = "Errore Nuome utente vuoto";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);

        //Elimino le sessioni
        $_SESSION = array();
        session_destroy();

        header("location: ../index.php?msg=Error_2");

        exit();
    }

    if ($password == '')
    {

        $_errori['descrizione'] = "Errore Campo password vuoto";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);

        //Elimino le sessioni
        $_SESSION = array();
        session_destroy();

        header("location: ../index.php?msg=Error_3");

        exit();
    }

    $_verifica = verifica_installazione($user, $password);


//qui verifichiamo di bloccare la situazione oppure continuare
    if ($_verifica == "blocca")
    {
        //carichiamo la base delle pagine:
        base_html("", $_percorso);
        exit;
    }

    //dobbiamo passare la versione del database al sistema..

    primo_ingresso($user, $password, $_parametri);
}
else
{

    //passaiamo la funzione alla sessione ritorna la connessione mysql
    $conn = permessi_sessione("verifica_PDO", $_percorso);
}
// se tutto ok proseguiamo..
require "../setting/vars.php";

require "librerie/motore_anagrafiche.php";

//SETTIAMO LE VARIABILI VUOTE..
$_cosa = "";

//carichiamo la base delle pagine:
base_html("", $_percorso);
java_script($_cosa, $_percorso);
fancybox($_cosa);

if ($_SESSION['user'] != "")
{

    if ($_SESSION['user']['perm'] == "SI")
    {
        $_primoingresso = "SI";
        $_SESSION['user']['perm'] = "NO";
        $_directory = "scadenziario/";
        include "scadenziario/index.php";

        exit;
    }



//carichiamo la testata del programma.
    testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
   menu_tendina($_cosa, $_percorso);

//carichiamo l'interfaccia visivo.
   echo "<style>
        body{
        background-repeat: no-repeat;
        background-position: center top;
        }
        
        </style>
    \n";
echo "</head>\n";
   
    echo "<body background=\"images/sfondo.png\">";

    echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
    echo "<tr>\n";
    ?>                    
    <td align="center" valign="top"><h3>Men&ugrave; Principale</h3><?php echo $_verifica; ?>
        <table width="80%" border="0">
            <tr align="center" valign="middle">
                <td width="8%" align="right"><a href="anagrafica/articoli/ricerca.php"><img alt="Anagrafiche" src="images/anagrafica.png"  style="border: 0px solid ; width: 70%;"></a></td>
                <td width="20%"align="left"><a href="anagrafica/articoli/ricerca.php">Cerca Articolo</a></font></td>
                <td width="4%">&nbsp;</td>
                <td width="10%"align="right"><a href="vendite/docubase/nuovodoc.php"><img alt="reparto vendite" src="images/noatun.gif" style="border: 0px solid ; width: 50%;"></a></td>
                <td width="20%"align="left"><a href="vendite/docubase/nuovodoc.php">Crea Documento</a></font></td>
            </tr>
            <tr align="center" valign="middle">
                <td width="8%" align="right"><a href="anagrafica/clifor/ricerca.php?tut=c"><img alt="clienti" src="images/menu/icona_clienti.png"  style="border: 0px solid ; width: 70%;"></a></td>
                <td width="20%"align="left"><a href="anagrafica/clifor/ricerca.php?tut=c">Cerca Cliente</a></font></td>
                <td width="4%">&nbsp;</td>
                <td width="10%"align="right"><a href="anagrafica/clifor/ricerca.php?tut=f"><img alt="fornitori" src="images/menu/icona_agenti.png" style="border: 0px solid ; width: 60%;"></a></td>
                <td width="20%"align="left"><a href="anagrafica/clifor/ricerca.php?tut=f">Cerca Fornitore</a></font></td>
            </tr>

            <tr align="center" valign="middle">
                <td width="8%" align="right"><a href="scadenziario/index.php"><img alt= "Scadenziario" src="images/scadenziario.png" width="70%" border="0"></a></td>
                <td width="20%" align="left">

                    <?php
                    if ($_SESSION['user']['scadenziario'] >= "2")
                    {
                        //verifichiamo se ci sono scadenze..
                        $nscad = tabella_scadenziario("nscad", $_percorso, $_parametri);
                        echo "<a href=\"scadenziario/index.php\"><font color=\"RED\"><b>$nscad </b> &nbsp;Prossime Scadenze </font></a><br>\n";
                        echo "</td>\n";
                    }
                    ?>

                <td></td><td></td><td></td></tr>

    </td></tr></table>

    </td></tr><tr><td height="80">&nbsp;</td></tr>
    <?php
    echo "<tr><td align=\"center\"  valign=\"top\">\n";

    echo "<table width=\"80%\" align=\"center\" bgcolor=\"#99FFFF\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";

    echo "<tr><td colspan=\"4\" align=\"center\" bgcolor=\"yellow\">&#x2193;&#x2193;&#x2193; Cose Da fare &#x2193;&#x2193;&#x2193;</td></tr>\n";

    $result = tabella_todo("elenco", $_anno, $_numero, $_SESSION['user']['id'], $_data_end, $_completato, $_parametri);
    $_inizio = "SI";

    foreach ($result AS $dati)
    {

        if ($dati['utente_end'] == $_utente)
        {
            //qui mettiamo solo la li
            echo "<li><font size=\"3\"><b><a class=\"fancybox\" href=\"#$dati[anno]$dati[numero]\" title=\"Vedi Dettaglio\">$dati[titolo]</a></b></a><br><font size=\"2\"><a href=\"user/todo.php?azione=modifica&anno=$dati[anno]&numero=$dati[numero]\" title=\"Modifica Todo\">$dati[priorita] | Fine $dati[scadenza] | Compl. $dati[completato] %</a></font>\n";
            echo "<div id=\"$dati[anno]$dati[numero]\" style=\"width:450px;display: none;\">\n";
            echo $dati['corpo'];
            echo "</div>\n";

            $_utente = $dati['utente_end'];
            $_inizio = "NO";
        }
        else
        {
            if ($_inizio == "NO")
            {
                echo "</ul></td></tr>\n";
            }

            if ($dati['utente_end'] == $_SESSION['user']['id'])
            {
                $colorea = "#FFCCFF";
            }
            else
            {
                $colorea = "";
            }
            //costruiamo tutto
            echo "<tr>\n";
            echo "<td align=\"left\" bgcolor=\"$colorea\">\n";
            echo "<font color=\"red\">$dati[user]</font><ul>\n";
            echo "<li><font size=\"3\"><b><a class=\"fancybox\" href=\"#$dati[anno]$dati[numero]\" title=\"Vedi Dettaglio\">$dati[titolo]</a></b></a><br><font size=\"2\"><a href=\"user/todo.php?azione=modifica&anno=$dati[anno]&numero=$dati[numero]\" title=\"Modifica Todo\">$dati[priorita] | Fine $dati[scadenza] | Compl. $dati[completato] %</a></font>\n";
            echo "<div id=\"$dati[anno]$dati[numero]\" style=\"width:450px;display: none;\">\n";
            echo $dati['corpo'];
            echo "</div>\n";
            $_utente = $dati['utente_end'];
        }
    }


    echo "</table>\n";

    echo "</td>\n";
    ?>



    </tr>
    </table>

    </center>
    </body>
    </html>
    <?php
}
else
{
    permessi_sessione("scaduta", $_percorso);
}
?>