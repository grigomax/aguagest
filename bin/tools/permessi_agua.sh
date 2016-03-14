#!/bin/bash
#provo a prendermi una variabile
echo "Programma che setta i permessi ai file di agua specialmente dopo un trasferimento"
echo "Bisogna passare ad amministratore.."

echo "fermo il server httpd"

service httpd stop


#prendiamo l'iserimento di dove è la cartella mysql
echo "inserisci il percorso della cartella contenente i file di agua "
echo "Partendo dalla radice.. / inclusa"
echo "Per esempio.. : /var/www/html/"

read directory

echo "la directory da modificare è $directory"

echo "confermi ?"
echo "scrivi si oppure no"
read risposta
if [ "$risposta" == "si" ] ; then
echo "Verifica directory..."
  # controllo se la directory esiste
  if [ -d $directory ]; then
    echo "Fatto. !"
    echo "La directory esiste"
    echo "Spegnamo il server.. "
    service httpd stop
    echo "procedo a cambiare tutti i permessi...."
    echo "Cambio nome..."
    chown apache -R $directory
    echo ".. Fatto. !"
    echo "Procedo a cambiare il gruppo"
    chgrp apache -R $directory
    echo ".. Fatto.. !"
    echo "Procedo a cambiare i permessi"
    chmod 755 -R $directory
    echo "... Fatto. !"
    echo "se Tutto ok riavvio il server httpd"
    service httpd restart
    echo "ed ora varifichiamo se è status ok"
    service httpd status
    echo "Speriamo sia tutto ok"
  else
    echo "la directory non esiste"
  fi

else
 echo "la tua risposta è No"
 exit
fi
exit
