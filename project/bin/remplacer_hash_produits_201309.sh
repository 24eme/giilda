#!/bin/bash

. bin/config.inc

curl -s http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_all_docs | cut -d "," -f 1 | grep '^{"id":' | sed 's/{"id":"//' | sed 's/"//' > /tmp/doc_ids

cat /tmp/doc_ids | grep -E "^(DRM|SV12|DS|VRAC|REVENDICATION|CONFIGURATION|FACTURE)" | sed 's/^CONFIGURATION$/999999;CONFIGURATION/' | sed -r 's/^.+-([0-9]{6})$/\1;\0/' | sed -r 's/^.+-([0-9]{6})-M.+$/\1;\0/' | sed -r 's/^.+-[0-9]{4}-([0-9]{4})$/\101;\0/' | sed -r 's/^.+-[0-9]{4}-([0-9]{4})-.+$/\101;\0/' | sed -r 's/^VRAC-([0-9]{6}).+$/\1;\0/' | sed -r 's/^FACTURE-[0-9]{6}-([0-9]{6})[0-9]{4}$/\1;\0/' | sort -r | cut -d ";" -f 2 > /tmp/doc_ids_sorted

cat /tmp/doc_ids_sorted | while read line; do

	#[Modification] Muscadet / ... /  Coteaux de la loire / ... => Muscadet Coteaux de la loire / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/MUS/mentions/(.+)/lieux/CDL/" "/declaration/certifications/AOC/genres/TRANQ/appellations/MUSCDL/mentions/\1/lieux/DEFAUT/"
	
	#[Modification] Muscadet / ... / Côtes de grandlieu / ... => Muscadet Côtes de grandlieu / ..
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/MUS/mentions/(.+)/lieux/CGL/" "/declaration/certifications/AOC/genres/TRANQ/appellations/MUSCGL/mentions/\1/lieux/DEFAUT/"

	#[Modification] Muscadet / ... => Muscadet AC / ..
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/MUS/mentions/(.+)/lieux/([^/]+)/" "/declaration/certifications/AOC/genres/TRANQ/appellations/MUSAC/mentions/\1/lieux/DEFAUT/"
	# Clean les noeuds Muscadet restants
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/MUS/.+" ""

	#[Modficiation] Coteaux du Layon Villages / ... => Coteaux du Layon / Villages / (DEFAULT)
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/CLV/mentions/DEFAUT/" "/declaration/certifications/AOC/genres/TRANQ/appellations/COL/mentions/VIL/"
	# Clean les noeuds CLV restants
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/CLV/.+" ""
	
	#[Modification] Anjou-Villages / ... / Brissac / ... => Anjou-Villages Brissac / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/AJV/mentions/(.+)/lieux/BRI/" "/declaration/certifications/AOC/genres/TRANQ/appellations/AJVBRI/mentions/\1/lieux/DEFAUT/"

	#[Modification] Anjou / ... / Coteaux de la Loire  / ... => Anjou Coteaux de la Loire  / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/ANJ/mentions/(.+)/lieux/CDL/" "/declaration/certifications/AOC/genres/TRANQ/appellations/ANJCDL/mentions/\1/lieux/DEFAUT/"

	#[Modification] Touraine / .. / Noble Joué ... => Touraine Noble Joué / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/TOU/mentions/(.+)/lieux/NJO/" "/declaration/certifications/AOC/genres/TRANQ/appellations/TOUNJO/mentions/\1/lieux/DEFAUT/"

	#[Modification] Saumur / .. / Champigny ... => Saumur Champigny / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/SAU/mentions/(.+)/lieux/CHA/" "/declaration/certifications/AOC/genres/TRANQ/appellations/SAUCHA/mentions/\1/lieux/DEFAUT/"

	#[Modification] Savennières / .. / Roche aux Moines ... => Savennières Roche aux Moines / ...
	bash bin/remplacer_hash_produit_doc.sh $line "/declaration/certifications/AOC/genres/TRANQ/appellations/SAV/mentions/(.+)/lieux/RAM/" "/declaration/certifications/AOC/genres/TRANQ/appellations/SAVRAM/mentions/\1/lieux/DEFAUT/"	

done