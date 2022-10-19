#!/bin/bash

. bin/config.inc

mkdir -p $DRMEXTERNEIMPORTDIR 2> /dev/null

#En cas de problème avec la clé publique lftp, voir http://tutos.tangui.eu.org/2021/02/23/lftp-host-key-verification-failed/
for u in "${GETDRMEXTERNECMD[@]}"
do
    eval $u
done

touch -d "1 day ago" /tmp/import_drm_externe.$$.file
find $DRMEXTERNEIMPORTDIR -newer /tmp/import_drm_externe.$$.file  -name '202*csv' | while read path
do
    file=$(basename $path)
    echo "Import $DRMEXTERNEIMPORTDIR/$file"
    PERIODE=$(echo -n $file | cut -d "_" -f 2)
    IDENTIFIANT=$(echo -n $file | cut -d "_" -f 3)
    cat $DRMEXTERNEIMPORTDIR/$file | grep -v ";dont_revendique;" | grep -E "^(ANNEXE|CRD|CAVE)" | grep -v ';acquitté;' > $DRMEXTERNEIMPORTDIR/$file.tmp
    mv $DRMEXTERNEIMPORTDIR/$file.tmp $DRMEXTERNEIMPORTDIR/$file
    php symfony drm:edi-import $DRMEXTERNEIMPORTDIR/$file $PERIODE $IDENTIFIANT $SYMFONYTASKOPTIONS --trace;
done
rm /tmp/import_drm_externe.$$.file