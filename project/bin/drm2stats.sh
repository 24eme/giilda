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

echo "date;type;periode;operateur;accises;certification;genre;appellation;mention;lieu;couleur;cepage;Libelle complementaire;libelle complet;type DRM;categorie de mouvement;type de mouvement;volume en hl;pays export;numero du contrat;numero de document;prix de la transaction;accises acheteur;nom acheteur;fin 1;type DRM 2;categorie de mouvement 2;type de mouvement 2;TAV;pays export 2;fin 2;" > $DRM_STAT_DEST_STATS"/external_drm_stock.csv"
cp $DRM_STAT_DEST_STATS"/external_drm_stock.csv" $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv"
join -a 1 -j 1 -t ';' $DRM_STAT_DEST_STATS"/.raw_stocks.csv" $DRM_STAT_DEST_STATS"/.raw_tav.csv"  | awk -F ';' 'BEGIN{OFS=";" ;} {$18 = "-"; sub(/,/, ".", $12); print $0}' | sed 's/£/;/g' >> $DRM_STAT_DEST_STATS"/external_drm_stock.csv"
join -a 1 -j 1 -t ';' $DRM_STAT_DEST_STATS"/.raw_entrees.csv" $DRM_STAT_DEST_STATS"/.raw_tav.csv" | awk -F ';' 'BEGIN{OFS=";" ;} {$18 = "-"; sub(/,/, ".", $12); print $0}' | sed 's/£/;/g' > $DRM_STAT_DEST_STATS"/.raw_mouvements.csv"
join -a 1 -j 1 -t ';' $DRM_STAT_DEST_STATS"/.raw_sorties.csv" $DRM_STAT_DEST_STATS"/.raw_tav.csv" | awk -F ';' 'BEGIN{OFS=";" ;} {$5 = $5 * -1; $18 = "-"; sub(/,/, ".", $12); print $0}' | sed 's/£/;/g' >> $DRM_STAT_DEST_STATS"/.raw_mouvements.csv"
cat $DRM_STAT_DEST_STATS"/.raw_mouvements.csv" | sort >> $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv"

recode UTF8..ISO88591 $DRM_STAT_DEST_STATS"/external_drm_stock.csv"
recode UTF8..ISO88591 $DRM_STAT_DEST_STATS"/external_drm_mouvements.csv"

if test -e "$DRM_STATS_SQLITE" ; then
    python3 bin/csv2sql.py $DRM_STATS_SQLITE $DRM_STAT_DEST_STATS
fi
