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
require $_percorso . "librerie/motore_anagrafiche.php";
require $_percorso . "librerie/stampe_pdf.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

$_codice = $_POST['codice'];
$_azione = $_POST['azione'];

if ($_SESSION['user']['stampe'] > "1")
{
    $dati = tabella_clienti("singola", $_codice, $_parametri);

    //variabili messaggio..
    
    $_parametri['messaggio_1'] = "Come vi &egrave; noto il D.Lgs. 196/2003 contiene le disposizioni per la tutela delle persone ed altri soggetti al trattamento dei dati personali improntato secondo i principi di correttezza, liceità e trasparenza e di tutela della riservatezza dei diritti delle persone.\n";
    
    
    if($_azione == "PDF")
    {
                //qui iniziamo a costruire la pagina direttamente in pdf..
        //creaiamo il file
        crea_file_pdf($_cosa, $_orientamento, "privacy_$dati[codice]");

        crea_pagina_pdf();
        $_parametri['email'] = "1";
        
        if($PRIVACY_LOGO != "")
        {
            if($PRIVACY_LOGO == "0")
            {
                $_parametri['intestazione'] = "0";
                $_cosa = "nologo";
            }
            elseif($PRIVACY_LOGO == "2")
            {
                $_parametri['intestazione'] = "2";
                $_cosa = "";
            }
            else
            {
                $_cosa = "conlogo";
                $_parametri['intestazione'] = "1";
                $_parametri[intesta_immagine] = $PRIVACY_LOGO;
            }
        }
        
        crea_intestazione_ditta_pdf($_cosa, $_title, $_anno, $_pg, $pagina, $_parametri);
        //qui mi conviene comporla a mano..

        intesta_pagina("titolo", "Informativa ex art. 13 D.Lgs, 30 giugno 2003, n. 196." , $_parametri);
        
        intesta_pagina("indirizzo_cli", $_titolo, $dati);
        
        corpo_pagina("privacy", $dati, $_parametri);
        
        $_pdf = chiudi_files("privacy_$dati[codice]", "../../..", "F");
        
        //prepariamo la maschera per scrivere
        maschera_invio_posta("singolo", $_percorso, $_pdf, $email1, $dati2['email2'], "Invio privacy", $_parametri);
        
    }
    else
    {
        base_html_stampa("chiudi", $_parametri);


    if ($PRIVACY_LOGO != "")
    {
        if ($PRIVACY_LOGO == "0")
        {
            $_parametri['intestazione'] = "0";
        }
        elseif ($PRIVACY_LOGO == "2")
        {
            $_parametri['intestazione'] = "2";
        }
        else
        {
            $_parametri['intestazione'] = "1";
            $_parametri[intesta_immagine] = $PRIVACY_LOGO;
        }
    }

    $_parametri['tabella'] = "Informativa ex art. 13 D.Lgs, 30 giugno 2003, n. 196.";
    $_parametri['email'] = "1";
    intestazione_html($_cosa, $_percorso, $_parametri);
    
    
    ?>

    <table width="90%">
        <tr>

            <td style="width: 50%;"></td>

            <td style="width: 50%;"><span style="font-style: italic; font-weight: bold;"><br>Spettabile</span></td>

        </tr>

        <tr>

            <td style="width: 50%;"></td>

            <td style="width: 50%;"><?php echo $dati['ragsoc']; ?></td>

        </tr>

        <tr>

            <td style="width: 50%;"></td>

            <td style="width: 50%;"><?php echo $dati['indirizzo']; ?></td>

        </tr>

        <tr>

            <td style="width: 50%;"></td>

            <td style="width: 50%;"><?php echo $dati['cap']; ?>  - <?php echo $dati['citta']; ?> (<?php echo $dati['prov']; ?> )</td>

        </tr>

        <tr>

            <td style="width: 50%;"></td>

            <td style="width: 50%;">Fax <?php echo $dati['fax']; ?></td>

        </tr>

    </table>

    <br>

    <table align="center" width="90%" border="0">

        <tr>

            <td>
                <font face="Arial, sans-serif"><b>Informativa
                    ex art. 13 D.Lgs, 30 giugno 2003, n. 196.</b></font>

                <font face="Arial, sans-serif"> Come
                vi &egrave; noto il D.Lgs. 196/2003 contiene le disposizioni per la
                tutela delle persone ed altri soggetti al trattamento dei dati
                personali improntato secondo i principi di correttezza,
                liceit&agrave;
                e trasparenza e di tutela della riservatezza dei diritti delle
                persone.</font>

                <font face="Arial, sans-serif">Pertanto,
                agli effetti del decreto, Le comunichiamo che</font>

                <ul>

                    <li>
                        <font face="Arial, sans-serif">i dati da voi
                        forniti alla nostra societ&agrave; verranno trattati per la
                        gestione amministrativa, e per la gestione degli incarichi da voi
                        affidatoci.</font>

                    </li>

                    <li>
                        <font face="Arial, sans-serif">Il trattamento
                        sar&agrave; eseguito attraverso l'utilizzo di strumenti
                        prevalentemente informatici e solo in parte manuali.</font>

                    </li>

                    <li>
                        <font face="Arial, sans-serif">Il conferimento
                        di tali dati &egrave; obbligatorio e l'eventuale rifiuto potrebbe
                        comportare la mancanza di esecuzione dell'incarico professionale
                        affidatoci.</font>

                    </li>

                    <li>
                        <font face="Arial, sans-serif">I dati saranno
                        trasmessi agli uffici ed organi di competenza, da noi incaricati per
                        assolvere tutti gli incarichi da voi affidatoci, sempre nel rispetto
                        degli obblighi di legge.</font>

                    </li>

                    <li>
    <?php echo "<font face=\"Arial, sans-serif\">Il responsabile
del trattamento &egrave; la seguente <b> $azienda</b>, con sede in $indirizzo $cap - $citta ($prov).</font>"; ?>

                    </li>

                </ul>

                <font face="Arial, sans-serif">&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 
                &nbsp;&nbsp;&nbsp;<?php echo $azienda; ?></font>

            </td>

        </tr>

    </table>

    <br><br>

    <table align="center" width="90%" border="0" cellpadding="0" cellspacing="0">

        <tbody>

            <tr>

                <td>
                    <p style="margin-bottom: 0cm;" align="justify"><font face="Arial, sans-serif"><font size="2">
                        Il sottoscritto__________________________________________________ <br>
                        Titolare/legale	rappresentante della Ditta, </font></font></p>

                    <p style="margin-bottom: 0cm;" align="justify"><font face="Arial, sans-serif"><font size="2">Acquisite
                        le informazioni fornite dal responsabile del trattamento presta il
                        suo consenso:</font></font></p>

                    <ul>

                        <ul>

                            <li>
                                <p style="margin-bottom: 0cm;" align="justify"><font face="Arial, sans-serif"><font size="2">
                                    Al trattamento dei dati personali ai fini indicati nella presente informativa;</font></font></p>

                            </li>

                            <li>
                                <p style="margin-bottom: 0cm;" align="justify"><font face="Arial, sans-serif"><font size="2">
                                    Alla comunicazione dei dati personali per le finalit&agrave; ed soggetti indicati;</font></font></p>

                            </li>

                            <li>
                                <p style="margin-bottom: 0cm;" align="justify"><font face="Arial, sans-serif"><font size="2">
                                    Alla diffusione dei dati personali per le finalit&agrave; e negli ambiti indicati.</font></font></p>

                            </li>

                        </ul>

                    </ul>
                    <br>
                    <p style="margin-bottom: 0cm;" align="justify"><font face="Arial, sans-serif"><font size="2">L&igrave;________________________________&nbsp;</font></font></p>

                    <p style="margin-bottom: 0cm;" align="justify"><font face="Arial, sans-serif"><font size="2">
                        &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Timbro
                        e Firma</font></font></p>

                    <p style="margin-bottom: 0cm;" align="justify"><font face="Arial, sans-serif"><font style="font-size: 8pt;" size="1">Si
                        prega di restituirla firmata anche per fax allo <?php echo $fax; ?></font></font></p>

                </td>

            </tr>

        </tbody>
    </table>
    <?php
    }
    
    
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>