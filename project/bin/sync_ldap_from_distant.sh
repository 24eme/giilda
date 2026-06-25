#!/bin/bash

cd $(dirname $0)/..

. bin/config.inc

admindn=$(grep [^e]dn: config/app.yml | sed 's/.*dn: *//')
adminpass=$(grep -A 5  ldap config/app.yml | grep pass:  | sed 's/.*pass: *//' )
basedc=$(grep dc: config/app.yml | sed 's/.*dc: *//')

ldapsearch -x -H "ldap://"$COUCHDISTANTHOST -D $admindn -w"$adminpass" -b $basedc  > /tmp/ldap.diff
tail -n "+"$(grep -n "cn: monitor" /tmp/ldap.diff  | awk -F ':' '{print $1 + 1}' | tail -n 1) /tmp/ldap.diff  | ldapadd -x -D $admindn -w"$adminpass"
