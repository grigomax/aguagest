<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left">
	<tr>
		<td width="15%" align="center" valign="top" class="logo">
			<span class="testo_bianco">
				<hr width="70%">
				<b><a href="../index.php" class="testo_bianco"><== Indietro</a></b>	
				<hr width="70%">
				<?php
				if ($handle = opendir('../../plugins/.'))
				{
					
					while (false !== ($entry = readdir($handle)))
					{
						if ($entry != "." && $entry != ".." && $entry != "index.php" && $entry != "naviga.php" && $entry != "barra.php" && $entry != "tools_documenti.dir" && $entry != "prezzicli.inc")
						{

							echo ("<a href=\"../../plugins/$entry\" class=\"testo_bianco\" >$entry</a><hr width=\"70%\">\n");
						}
					}
					closedir($handle);
				}
				?>
			</span>
		</td>
</table>