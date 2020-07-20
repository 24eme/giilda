#!/bin/bash

. $(dirname $0)/config.inc

cd $TMP

sed -i 's/\\"//g' export_bi_etablissements.csv
csvtool -t ';' col 5,12,13 export_bi_etablissements.csv  > villes.csv
sed -i 's/,/;/g' villes.csv
sed -i 's/ ";/;/g' villes.csv
sed -i 's/;" /;/g' villes.csv
sed -i 's/ "$//g' villes.csv
sed -i 's/"//g' villes.csv
sed -i "s/'/./g" villes.csv
sed -i 's/SAINT /ST /' villes.csv

if ! test -f laposte_hexasmal.csv ; then
wget -O laposte_hexasmal.csv https://www.data.gouv.fr/fr/datasets/r/554590ab-ae62-40ac-8353-ee75162c05ee
fi

cat villes.csv | awk -F ';' '{print "echo -n "$1"\";\"; grep \";\"\""toupper($2)"\"\";\"\""$3"\"\";\" laposte_hexasmal.csv | sed \"s/;.*//\" | tr -d \"\\n\" ; echo ";}' > /tmp/ville1.sh
cat /tmp/ville1.sh | bash | grep ETAB > etablissement2insee.csv

grep -v ';.....$' etablissement2insee.csv | sed 's/;.*//' | while read id ; do grep "^""$id"";" villes.csv ; done | awk -F ';' '{print "echo -n "$1"\";\" ; grep \";"toupper($2)";\" laposte_hexasmal.csv | sed \"s/;.*//\" | sort -u | tr -d \"\\n\"; echo ;"}' | sh > etablissement2insee2.csv

grep -v ';.....$' etablissement2insee2.csv | sed 's/;.*//' | while read id ; do grep "^""$id"";" villes.csv ; done | awk -F ';' '{print "echo -n "$1"\";\" ; grep \";"$3";\" laposte_hexasmal.csv | sed \"s/;.*//\" | sort -u | tr -d \"\\n\"; echo ;"}' | sh > etablissement2insee3.csv

mkdir -p /tmp/cache

grep -v ';.....$' etablissement2insee3.csv | sed 's/;.*//' | while read id ; do grep "^""$id"";" villes.csv ; done | while read line ; do echo $line | sed 's/;.*/;/'  | tr -d '\n' ;  geo=$(echo $line | sed 's/^[^,]*,//') ; md5=$(echo $geo | md5sum | sed 's/ .*//') ; test -f /tmp/cache/$md5 || curl -s -G --data-urlencode "q=""$geo" --data-urlencode "type=municipality" https://api-adresse.data.gouv.fr/search/ | jq '.features[]' | jq -c '[.properties.citycode, .properties.score]'  | sed 's/^.//' | sed 's/.$//'  | awk -F ',' '{if ($2 > 0.6) print  $1}'  | sed 's/"//g' | sort -u | tr -d '\n' > /tmp/cache/$md5 ; cat /tmp/cache/$md5; echo ; done > etablissement2insee4.csv

grep -h ';.....$' etablissement2inse*csv | sort -u | sed 's/;.*//'  > etablissements_trouves.txt
cat villes.csv | sed 's/;.*//' | sort  > etablissements_tous.txt

diff etablissements_t*txt | grep '<'  | sed 's/^..//'  | grep ETAB | while read id ; do grep "^""$id"";" villes.csv ; done | while read line ; do echo $line | sed 's/;.*/;/'  | tr -d '\n' ;  geo=$(echo $line | sed 's/^[^,]*,//') ; md5=$(echo $geo | md5sum | sed 's/ .*//') ; test -f /tmp/cache/$md5 || curl -s -G --data-urlencode "q=""$geo" --data-urlencode "type=municipality" https://api-adresse.data.gouv.fr/search/ | jq '.features[]' | jq -c '[.properties.citycode, .properties.score]'  | sed 's/^.//' | sed 's/.$//'  | awk -F ',' '{if ($2 > 0.6) print  $1}'  | sed 's/"//g' | sort -u | tr -d '\n' > /tmp/cache/$md5 ; cat /tmp/cache/$md5; echo ; done > etablissement2insee5.csv


grep -v ';.....$' etablissement2insee5.csv | sed 's/;.*//' | while read id ; do grep "^""$id"";" villes.csv ; done | while read line ; do echo $line | sed 's/;.*/;/'  | tr -d '\n' ;  geo=$(echo $line | sed 's/^[^,]*,//') ; md5=$(echo $geo | md5sum | sed 's/ .*//') ; test -f /tmp/cache/$md5".2" || curl -s -G --data-urlencode "q=""$geo" https://api-adresse.data.gouv.fr/search/ | jq '.features[]' | jq -c '[.properties.citycode, .properties.score]'  | sed 's/^.//' | sed 's/.$//'  | awk -F ',' '{if ($2 > 0.4) print  $1}'  | sed 's/"//g' | head -n 1 | tr -d '\n' > /tmp/cache/$md5".2" ; cat /tmp/cache/$md5".2"; echo ; done > etablissement2insee6.csv

grep -h ';.....$' etablissement2inse*csv | sort -u  > etablissements_trouves2insee.csv
cat etablissements_trouves2insee.csv | sed 's/;.*//'  > etablissements_trouves.txt
