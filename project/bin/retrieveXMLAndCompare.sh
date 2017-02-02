#!/bin/bash

. bin/config.inc

curl -s $URL_RETOUR_CFT | while read url ; do
	OUT=$(php5 symfony $SYMFONY_ENV drm:storeXMLRetour $url $*)
	RET=$?
	DRM=$(echo $OUT | sed 's/ .*//')
	if test $RET -ne 1 ; then
		echo $OUT
	fi
	if test $RET -eq 0 ; then
		php5 symfony $SYMFONY_ENV drm:compareXMLs $DRM
	fi
done
