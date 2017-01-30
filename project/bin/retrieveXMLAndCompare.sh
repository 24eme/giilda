#!/bin/bash

curl -s http://10.222.223.1/reception_douanes/429164072/ | while read url ; do
	OUT=$(php5 symfony drm:storeXMLRetour $url $*)
	RET=$?
	DRM=$(echo $OUT | sed 's/ .*//')
	if test $RET -ne 1 ; then
		echo $OUT
	fi
	if test $RET -eq 0 ; then
		php5 symfony drm:compareXMLs $DRM
	fi
done
