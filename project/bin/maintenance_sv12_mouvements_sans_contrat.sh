while read ligne  
do
    #echo "Mise à jour de la SV12 : $ligne"
    #echo "----------------------------------------------------"
    php symfony sv12:regenerate_mouvements $ligne
done < /tmp/sv12bug.list