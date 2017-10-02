#!/bin/bash

. $(dirname $0)/config.inc

cat fic | while read url ; do
	OUT=$(php5 symfony drm:storeXMLRetour $url $*)
	RET=$?
	DRM=$(echo $OUT | sed 's/ .*//')
	if test $RET -ne 1 ; then
		echo $OUT
	fi
	if test $RET -eq 0 ; then
		php5 symfony $SYMFONY_ENV drm:compareXMLs $DRM
	fi
done
