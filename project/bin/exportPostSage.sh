#!/bin/bash

. bin/config.inc

if ! test "$SAMBA_IP" || ! test "$SAMBA_SHARE" || ! test "$SAMBA_AUTH" || ! test "$SAMBA_SAGESUBDIR" || ! test "$SAMBA_SAGEFILE"; then
    echo "Pas d'info sur les contacts avec le serveur SAGE"
    exit 3
fi

cd $TMP
if smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; get societes.csv" | grep NT_STATUS_OBJECT_NAME_NOT_FOUND ; then
    echo "societes.csv not found" 1>&2
    exit 4
fi
if smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; get factures.csv" | grep NT_STATUS_OBJECT_NAME_NOT_FOUND ; then
    echo "factures.csv not found" 1>&2
    exit 5
fi

cd -

cat $TMP/factures.csv | awk -F ';' '{print $14}' | sort | uniq | while read FACTUREID; do
    php symfony facture:setexported $FACTUREID;
done
