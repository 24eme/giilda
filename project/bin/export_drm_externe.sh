#!/bin/bash

. bin/config.inc

echo $DRMEXTERNE_EXPORT_IDS | sed 's/|/\n/g' | grep '[A-Z]' | while read id;
do
    eval bash bin/export_drm_teledeclare.sh \"'$DRMEXTERNE_EXPORT_'$id'_FILTER'\" \"'$DRMEXTERNE_EXPORT_'$id'_EXPORTDIR'\" \"'$DRMEXTERNE_EXPORT_'$id'_LIMITIP'\"
done
