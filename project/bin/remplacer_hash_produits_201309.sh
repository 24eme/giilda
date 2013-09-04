#!/bin/bash

. bin/config.inc

#curl -s http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_all_docs | cut -d "," -f 1 | grep '^{"id":' | sed 's/{"id":"//' | sed 's/"//' > /tmp/doc_ids

cat /tmp/doc_ids | while read line; do

	#[Modification] Muscadet / ... /  Coteaux de la loire / ... => Muscadet Coteaux de la loire / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/MUS/mentions/(.+)/lieux/CDL/" "/declaration/certifications/AOC/genres/TRANQ/appellations/MUSCDL/mentions/\1/lieux/DEFAUT/"
	
	#[Modification] Muscadet / ... / Côtes de grandlieu / ... => Muscadet Côtes de grandlieu / ..
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/MUS/mentions/(.+)/lieux/CGL/" "/declaration/certifications/AOC/genres/TRANQ/appellations/MUSCGL/mentions/\1/lieux/DEFAUT/"

	#[Modficiation] Coteaux du Layon Villages / ... => Coteaux du Layon / Villages / (DEFAULT)
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/CLV/mentions/DEFAUT/" "/declaration/certifications/AOC/genres/TRANQ/appellations/COL/mentions/VIL/"

	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/CLV/.+" ""
	
	#[Modification] Anjou-Villages / ... / Brissac / ... => Anjou-Villages Brissac / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/AJV/mentions/(.+)/lieux/BRI/" "/declaration/certifications/AOC/genres/TRANQ/appellations/AJVBRI/mentions/\1/lieux/DEFAUT/"
	

	#[Modification] Anjou / ... / Coteaux de la Loire  / ... => Anjou-Villages Coteaux de la Loire  / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/ANJ/mentions/(.+)/lieux/CDL/" "/declaration/certifications/AOC/genres/TRANQ/appellations/AJVCDL/mentions/\1/lieux/DEFAUT/"

	#[Modification] Touraine / .. / Noble Joué ... => Touraine Noble Joué / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/TOU/mentions/(.+)/lieux/NJO/" "/declaration/certifications/AOC/genres/TRANQ/appellations/TOUNJO/mentions/\1/lieux/DEFAUT/"	

done