#!/bin/bash

convert-utf8 () {
    encodage=$(file --mime-encoding $1 | grep -oE ": .+$" | sed 's/: //');
    iconv -f iso-8859-1 -t utf-8 $1 | sed 's/  *;/;/g'
}

ajout-champ-commun () {
    cat $3 | awk -F";" '{ printf("'"$1"';",'"$2"'); for (i=1;i<NF;i++) { printf("%s;",$i) } printf("\n") }' | sed -r 's/;$//'
}

suppression-champ-commun () {
    cat $1 | awk -F";" '{ for (i=2;i<NF;i++) { printf("%s;",$i) } printf("\n") }'
}
