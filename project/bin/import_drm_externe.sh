#!/bin/bash

. bin/config.inc

# Pour le CIVB, les DRM sont versée dans un SFTP tous les matin
# vers 7h50 en fonction du produit qui est déclaré chez eux.
# Ils n'ont pas d'autres intervention a réaliser
# (il existe chez eux un flag IVBD mais c'est pour les DRM qui provienne de GIILDA)

mkdir -p $DRMEXTERNEIMPORTDIR 2> /dev/null

#En cas de problème avec la clé publique lftp, voir http://tutos.tangui.eu.org/2021/02/23/lftp-host-key-verification-failed/
for u in "${GETDRMEXTERNECMD[@]}"
do
    eval $u > /dev/null || echo "Erreur dans la récupération FTP des DRM"
done

touch -d "7 day ago" /tmp/import_drm_externe.$$.file
find $DRMEXTERNEIMPORTDIR -newer /tmp/import_drm_externe.$$.file  -name '202*csv' | while read path
do
    file=$(basename $path)
    PERIODE=$(echo -n $file | cut -d "_" -f 2)
    IDENTIFIANT=$(echo -n $file | cut -d "_" -f 3)
    cat $DRMEXTERNEIMPORTDIR/$file | grep -v ";dont_revendique;" | grep -E "^(ANNEXE|CRD|CAVE)" | grep -v ';acquitté;' > $DRMEXTERNEIMPORTDIR/$file".cleaned"
    php symfony drm:edi-import $DRMEXTERNEIMPORTDIR/$file".cleaned" $PERIODE $IDENTIFIANT $SYMFONYTASKOPTIONS --trace | grep -v 'DEBUG:';
done
rm /tmp/import_drm_externe.$$.file
touch $DRMEXTERNEIMPORTDIR/last_update