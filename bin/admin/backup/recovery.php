<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
		<LINK REL="shortcut icon" HREF="favicon.ico">
		<title>Agua Gest - Recovery Programm</title>
	</head>
	<BODY background="bin/images/aguaback.jpg">
	<center>
		<img src="bin/images/aguagest.png">
		<font color="yellow">
			<h2>Recovery Program<br>Tutti gli archivi del  programma esistente verranno cancellati</h2>
			<h4>Programma di recupero installazione completa</h4>
		</font>
		<center>
			<?php
			if ($_GET['cosa'] == "elimina_tutto")
			{
				echo "<h1>Eliminazione tutti i parametri di agua... </h1>\n";
				if (file_exists("setting/vars.php"))
				{
					include("setting/vars.php");

					#Connettiamoci al database...

					if (!( $conn = @mysql_connect($db_server, $db_user, $db_password) ))
					{
						echo "<span class=\"testo_blu\"><br>Non trovo il database server</span><br>";
						exit(0);
					}
					//Uso il database canis...
					if (!@mysql_select_db($db_nomedb, $conn))
					{
						echo "<span class=\"testo_blu\"><br><b>Non trovo il database</b></span><br>";
						exit(0);
					}


					//Eliminazione database completa database..

					$query = "DROP DATABASE agua";

					mysql_query($query, $conn) or mysql_error();

					echo "<br><h2>Eliminazione database completata..</h2>\n";
					echo "<br><h3>Eliminazione files.. configurazione...<h3>\n";

					echo "<br>vars.php....\n";
					unlink("setting/vars.php");
					echo "<br>..[ <font color=\"green\"> OK </font> ]....\n";

					echo "<br>vars_aspetto.php....\n";
					unlink("setting/vars_aspetto.php");
					echo "<br>..[ <font color=\"green\"> OK </font> ]....\n";

					echo "<br>par_conta.inc.php....\n";
					unlink("setting/par_conta.inc.php");
					echo "<br>..[ <font color=\"green\"> OK </font> ]....\n";

					echo "<br>maschera_art.php....\n";
					unlink("setting/maschera_art.php");
					echo "<br>..[ <font color=\"green\"> OK </font> ]....\n";

					echo "<br>maschera_art.php....\n";
					unlink("setting/maschera_art.php");
					echo "<br>..[ <font color=\"green\"> OK </font> ]....\n";

					echo "<br>maschera_art.php....\n";
					unlink("setting/maschera_art.php");
					echo "<br>..[ <font color=\"green\"> OK </font> ]....\n";


					echo "<br>Proseguiamo a ripristinare tutto..";

					echo "<br>\n";
					echo "<br>Inizio programma di recupero Ripristinando solo i files dei parametri.. <a href=\"upload/upload.php?cosa=recovery\">Solo Parametri </a>\n";
				}
			}
			else
			{
				if (file_exists("setting/vars.php"))
				{


					echo "File trovato = <font color=\"green\"> [ OK ] </font>";
					include("setting/vars.php");

					if (($host != "") or ($db_server != "") or ($db_user != "") or ($db_nomedb != ""))
					{
						echo "<style=\"font-weight: bold;\"><font color=\"RED\">";
						echo "<br><br>Errore 2 = File variante base non vuoto</font></font></span>";
						echo "<br><br>Il file vars.php contenuto all'interno della cartella agua/setting contiene le variabili come localhost e nome database e password non vuote..";
						echo "<br><font color=\"red\">Tutte le variabili verranno prese in considerazione per la cancellazione del programma esistente..</font>";
						echo "<br>";
						echo "<br><font color=\"red\"> PerPoter recuperare l'installazione occorrono i files delle impostazioni <br>e l'ultima copia degli archivi</font>\n";

						echo "<br>\n";
						echo "<br>Inizio programma di recupero eliminando tutti i dati.. <a href=\"recovery.php?cosa=elimina_tutto\">Elimina.. </a>\n";
						echo "<br>\n";
						echo "<br>Inizio programma di recupero Ripristinando solo i files dei parametri.. <a href=\"upload/upload.php?cosa=parametri\">Solo Parametri </a>\n";



						exit;
					}
					else
					{
						echo "<br>File vuoto = <font color=\"green\"> [ OK ] </font>";

						$file = fopen("setting/vars.php", "r+");
						if ($file)
						{
							echo "<br>Permessi di scrittura = <font color=\"green\"> [ OK ] </font>";
							echo "<br>Il file contenete i parametri &egrave; vuoto.. \n";
							echo "<br>ATTENZIONE IN CASO DI UNA NUOVA INSTALLAZIONE UNSARE IL PROGRAMMA INSTALL\n";
							echo "<br><font color=\"red\"> PerPoter recuperare l'installazione occorrono i files delle impostazioni <br>e l'ultima copia degli archivi</font>\n";
							echo "<br>\n";
							echo "<br>Inizio programma di recupero Ripristinando solo i files dei parametri.. <a href=\"upload/upload.php?cosa=parametri\">Solo Parametri </a>\n";
						}
						else
						{
							echo "<span style=\"font-weight: bold;\">Errore 3 = File variante base non scrivibile.</span>
			<br>Per
			poter Recuperare il programma i files vars.php vars-aspetto.php devono
			avere i permessi lettura e scrittura. si prega si provvedere e poi
			riprovare a recuperare, altrimenti contattare l'amministratore..
			<br><br>
			</div></div>";
							exit;
						}
						fclose($file);
					}
				}
				else
				{
					echo "<br><br style=\"font-weight: bold;\"><font color=\"RED\"><div style=\"text-align: center;\">\n";
					echo "<span style=\"font-weight: bold;\">Errore 1 = File variante base non trovato</span></font>";
					echo "<br><br>Vi preghiamo di verificare se durante la copiatura dei files all interno della cartella del server<br>\n";
					echo ", esiste un files all'interno della cartella agua/settings che si chiama vars.php";
					echo "<br><br>In caso non esistesse provare a ricopiarlo all'interno del server, altrimenti\n";
					echo "<br>contattare il sito ufficiale aguagest.sourceforge.net o provare a riinstallare il programma";
					exit;
				}
			}
			?>

		</center>
	</body>
</html>