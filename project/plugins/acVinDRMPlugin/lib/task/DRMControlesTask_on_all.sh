#!/bin/bash
#Params:
#$1 is server
#$2 is application
#$3 is env
if [ "$#" -ne 3 ]
  then
    echo "Erro No argument: please put application name (ivbd, ivso ...)";
    exit 1
fi

curl http://$1:5984/giilda_$2_$3/_design/drm/_view/all?reduce=false > /tmp/drm_id
grep -Po '(DRM-[0-9]+-[0-9]{6}-M[0-9]{2}|DRM-[0-9]+-[0-9]{6})' /tmp/drm_id | sort -u > /tmp/drm_ids
file="/tmp/drm_ids"
i=0
while IFS= read -r line
do
	echo "**$i ** $line"
	i=$((i+1))
 	php ./symfony drm:controles --application=$2 "$line"
done < "$file" 
exit 0