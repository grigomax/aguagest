#!/bin/bash
#provo a prendermi una variabile
echo "Programma che setta i permessi ai file mysql specialmente dopo un trasferimento"
echo "Bisogna passare ad amministratore.."

echo "fermo il server mysql"

service mysqld stop


#prendiamo l'iserimento di dove è la cartella mysql
echo "inserisci il percorso della cartella contenente gli archivi "
echo "Partendo dalla radice.. / inclusa"
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
    echo "procedo a cambiare tutti i permessi...."
    echo "Cambio nome..."
    chown mysql -R $directory
    echo ".. Fatto. !"
    echo "Procedo a cambiare il gruppo"
    chgrp mysql -R $directory
    echo ".. Fatto.. !"
    echo "Procedo a cambiare i permessi"
    chmod 755 -R $directory
    echo "... Fatto. !"
    echo "se Tutto ok riavvio il server mysql"
    service mysqld restart
    echo "ed ora varifichiamo se è status ok"
    service mysqld status
    echo "Speriamo sia tutto ok"
  else
    echo "la directory non esiste"
  fi

else
 echo "la tua risposta è No"
 exit
fi
exit
