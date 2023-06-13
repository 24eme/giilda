#!/bin/bash

. bin/config.inc

if ! test -e "$DRM_STAT_SOURCE_DIR" ; then
    echo DRM_STAT_SOURCE_DIR missing 1>&2
    exit 1
fi
if ! test -e "$DRM_STAT_DEST_STATS" ; then
    echo DRM_STAT_DEST_STATS missing 1>&2
    exit 2
fi

ls $DRM_STAT_SOURCE_DIR/ | grep DRM | sed 's/^[^_]*_//'  | sed 's/-.*//'  | sort -u | while read id ; do
    ls $DRM_STAT_SOURCE_DIR/*$id*csv | tail -n 1 ;
done | while read file ; do
    date=$(echo $file | sed 's|/|_|g' | awk -F '_' '{print $6}') ;
    cat $file | sed 's/^/'$date';/' | sed 's/;/£/ ; s/;/£/ ; s/;/£/ ;  s/;/£/ ;  s/;/£/ ;  s/;/£/ ;  s/;/£/ ; s/;/£/ ; s/;/£/ ; s/;/£/ ;  s/;/£/ ;  s/;/£/ ;  s/;/£/'
done | awk -F ';' 'BEGIN{OFS=";" ;} {$12 = "-"; print $0}' > $DRM_STAT_DEST_STATS"/.all_drm.csv"

grep ';TAV;' $DRM_STAT_DEST_STATS"/.all_drm.csv" | sort > $DRM_STAT_DEST_STATS"/.raw_tav.csv"
grep ';suspendu;stocks' $DRM_STAT_DEST_STATS"/.all_drm.csv" | sort > $DRM_STAT_DEST_STATS"/.raw_stocks.csv"
grep ';suspendu;entrees;' $DRM_STAT_DEST_STATS"/.all_drm.csv" | sort > $DRM_STAT_DEST_STATS"/.raw_entrees.csv"
grep ';suspendu;sorties;' $DRM_STAT_DEST_STATS"/.all_drm.csv" | sort > $DRM_STAT_DEST_STATS"/.raw_sorties.csv"

echo "date;type;periode;operateur;accises;certification;genre;appellation;mention;lieu;couleur;cepage;Libelle complementaire;libelle complet;type DRM;categorie de mouvement;type de mouvement;volume en hl;pays export;numero du contrat;numero de document;prix de la transaction;accises acheteur;nom acheteur;CODE INAO Douane;Libelle clair;campagne;type de mouvement 2;TAV;pays export 2;fin 2;" > $DRM_STAT_DEST_STATS"/external_drm_stock.csv"
cp $DRM_STAT_DEST_STATS"/external_drm_stock.csv" $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv"
join -a 1 -j 1 -t ';' $DRM_STAT_DEST_STATS"/.raw_stocks.csv" $DRM_STAT_DEST_STATS"/.raw_tav.csv"  | awk -F ';' 'BEGIN{OFS=";" ;} {$18 = "-"; sub(/,/, ".", $12); print $0}' | sed 's/£/;/g' >> $DRM_STAT_DEST_STATS"/external_drm_stock.csv"
join -a 1 -j 1 -t ';' $DRM_STAT_DEST_STATS"/.raw_entrees.csv" $DRM_STAT_DEST_STATS"/.raw_tav.csv" | awk -F ';' 'BEGIN{OFS=";" ;} {$18 = "-"; sub(/,/, ".", $12); print $0}' | sed 's/£/;/g' > $DRM_STAT_DEST_STATS"/.raw_mouvements.csv"
join -a 1 -j 1 -t ';' $DRM_STAT_DEST_STATS"/.raw_sorties.csv" $DRM_STAT_DEST_STATS"/.raw_tav.csv" | awk -F ';' 'BEGIN{OFS=";" ;} {$5 = $5 * -1; $18 = "-"; sub(/,/, ".", $12); print $0}' | sed 's/£/;/g' >> $DRM_STAT_DEST_STATS"/.raw_mouvements.csv"
cat $DRM_STAT_DEST_STATS"/.raw_mouvements.csv" | sort >> $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv"

sed -i 's/;;-;;;;;-/;;-/' $DRM_STAT_DEST_STATS"/external_drm_stock.csv"
sed -i 's/;;-;;;;;-/;;-/' $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv"

#if test "$WITH_HLAP" ; then
head -n 1 $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv" | sed 's/fin 2/volume en hlap/' > $DRM_STAT_DEST_STATS"/external_drm_mouvements_hlap.csv"
tail -n +2 $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv" |
    awk -F ';' 'BEGIN{OFS=";" ;} { $31 = "";  if ($29) $31 = $29 * $18 / 100 ; if ( $14 ~ /Mati[èe]res premi/ ) { $31 = $18 ; $18 = ""; } print $0}' |
    awk -F ';' 'BEGIN{OFS=";"; IGNORECASE = 1;}{$25=$14; gsub(/.*\(/, "", $25); gsub(/\).*/, "", $25); if (!($25 ~ /INF_18/) && !($25 ~ /VDN_VDL/) && ($8 ~ /armagnac/ || $14 ~ /armagnac/) ) $8 = "Armagnac"; if ($8 == "Armagnac" && $6 ~ /vins|vin /) $6 = "VDE"; if ($7 ~ /matières premières/ || $8 ~ /matières premières/ ~ $14 ~ /matières premières/ || $25 ~ /MATIERES_PREMIERES/) { $7 = "Matières premières"; gsub(/Matières premières */, "", $8);} if ($9 ~ /Autres produits intermé/) { $8 = $9 ; $9 = ""; } ; if ( $9 ~ /Blanche/ || $25 == "1B614H" || $14 ~ /Blanche/) { $9 = "Blanche"; $8 = "Armagnac" ; if ($6 ~ /vin /) $25 = "1B614H" } if ($25 == "1B612H" || $9 ~ /Ténarèze/ || $14 ~ /Ténarèze/) { $9 = "Ténarèze" ; if ($6 ~ /vin /) $25 = "1B612H"} ; if ( $14 ~ /bas[- ]*armagnac/ || $25 == "1B613H") { $9 = "Bas"; $8 = "Armagnac" ; if ($6 ~ /vin /) $25 = "1B613H"  } ;  if ( $14 ~ /haut[- ]*armagnac/ || $25 == "1B615H") { $9 = "Haut"; $8 = "Armagnac" ; if ($6 ~ /vin /) $25 = "1B615H"  } ; if ($9 ~ /millesime/) $9 = ""; $26 = $6" "$7" "$8" "$9; if ($6 == "Alcools") { if ($7 == "Matières premières") $26 = $7" "$8" "$9; else $26 = $6 " "$8 " " $9 } else { if ($6 == "VDE") $26 = $6" "$8" "$9; } ; print $0}' |
    awk -F ';' 'BEGIN{OFS=";" ;}{mois=substr($3,5,2); annee=substr($3,0,4); campagne=annee"-"(annee+1); if (mois > "07") campagne=(annee-1)"-"annee; $27=campagne; print $0; }' >> $DRM_STAT_DEST_STATS"/external_drm_mouvements_hlap.csv"
head -n 1 $DRM_STAT_DEST_STATS"/external_drm_stock.csv" | sed 's/fin 2/volume en hlap/' > $DRM_STAT_DEST_STATS"/external_drm_stock_hlap.csv"
tail -n +2 $DRM_STAT_DEST_STATS"/external_drm_stock.csv" |
    awk -F ';' 'BEGIN{OFS=";" ;} { $31 = ""; if ($29) $31 = $29 * $18 / 100 ; if ( $14 ~ /Mati[èe]res premi/ ) { $31 = $18 ; $18 = ""; } print $0}' |
    awk -F ';' 'BEGIN{OFS=";"; IGNORECASE = 1;}{$25=$14; gsub(/.*\(/, "", $25); gsub(/\).*/, "", $25); if (!($25 ~ /INF_18/) && !($25 ~ /VDN_VDL/) && ($8 ~ /armagnac/ || $14 ~ /armagnac/) ) $8 = "Armagnac"; if ($8 == "Armagnac" && $6 ~ /vins|vin /) $6 = "VDE"; if ($7 ~ /matières premières/ || $8 ~ /matières premières/ ~ $14 ~ /matières premières/ || $25 ~ /MATIERES_PREMIERES/) { $7 = "Matières premières"; gsub(/Matières premières */, "", $8);} if ($9 ~ /Autres produits intermé/) { $8 = $9 ; $9 = ""; } ; if ( $9 ~ /Blanche/ || $25 == "1B614H" || $14 ~ /Blanche/) { $9 = "Blanche"; $8 = "Armagnac" ; if ($6 ~ /vin /) $25 = "1B614H" } if ($25 == "1B612H" || $9 ~ /Ténarèze/ || $14 ~ /Ténarèze/) { $9 = "Ténarèze" ; if ($6 ~ /vin /) $25 = "1B612H"} ; if ( $14 ~ /bas[- ]*armagnac/ || $25 == "1B613H") { $9 = "Bas"; $8 = "Armagnac" ; if ($6 ~ /vin /) $25 = "1B613H"  } ;  if ( $14 ~ /haut[- ]*armagnac/ || $25 == "1B615H") { $9 = "Haut"; $8 = "Armagnac" ; if ($6 ~ /vin /) $25 = "1B615H"  } ; if ($9 ~ /millesime/) $9 = ""; $26 = $6" "$7" "$8" "$9; if ($6 == "Alcools") { if ($7 == "Matières premières") $26 = $7" "$8" "$9; else $26 = $6 " "$8 " " $9 } else { if ($6 == "VDE") $26 = $6" "$8" "$9; } ; print $0}' |
    awk -F ';' 'BEGIN{OFS=";" ;}{mois=substr($3,5,2); annee=substr($3,0,4); campagne=annee"-"(annee+1); if (mois > "07") campagne=(annee-1)"-"annee; $27=campagne; print $0; }' >> $DRM_STAT_DEST_STATS"/external_drm_stock_hlap.csv"
mv $DRM_STAT_DEST_STATS"/external_drm_mouvements_hlap.csv" $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv"
mv $DRM_STAT_DEST_STATS"/external_drm_stock_hlap.csv" $DRM_STAT_DEST_STATS"/external_drm_stock.csv"
#fi

recode UTF8..ISO88591 $DRM_STAT_DEST_STATS"/external_drm_stock.csv"
recode UTF8..ISO88591 $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv"

if test -e "$DRM_STATS_SQLITE" ; then
    python3 bin/csv2sql.py $DRM_STATS_SQLITE".tmp" $DRM_STAT_DEST_STATS
    mv $DRM_STATS_SQLITE".tmp" $DRM_STATS_SQLITE
fi
