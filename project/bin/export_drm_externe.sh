#!/bin/bash

. bin/config.inc

echo $DRMEXTERNE_IDS | sed 's/|/\n/g' | grep '[A-Z]' | while read id;
do
    eval bash bin/export_drm_teledeclare.sh \"'$DRMEXTERNE_'$id'_FILTER'\" \"'$DRMEXTERNE_'$id'_EXPORTDIR'\" \"'$DRMEXTERNE_'$id'_LIMITIP'\"
done
