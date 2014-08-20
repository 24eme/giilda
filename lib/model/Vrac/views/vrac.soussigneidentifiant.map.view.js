function(doc) {
  	if (doc.type != "Vrac")
    	return;

	 campagne = doc.campagne;

    archive = doc.numero_contrat;
    if (doc.numero_archive) {
	archive = doc.numero_archive;
    }

    if (doc.mandataire_identifiant)
	emit(["STATUT", doc.mandataire_identifiant, campagne, doc.valide.statut, doc.type_transaction],  [campagne, doc.valide.statut, doc._id, doc.numero_contrat, archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve]);
  	
    emit(["STATUT", doc.acheteur_identifiant, campagne, doc.valide.statut, doc.type_transaction], [campagne, doc.valide.statut, doc._id, doc.numero_contrat, archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve]);
  	
    emit(["STATUT", doc.vendeur_identifiant, campagne, doc.valide.statut, doc.type_transaction], [campagne, doc.valide.statut, doc._id, doc.numero_contrat, archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve]);
  	
    if (doc.mandataire_identifiant)
	emit(["TYPE", doc.mandataire_identifiant, campagne, doc.type_transaction],  [campagne, doc.valide.statut, doc._id, doc.numero_contrat, archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve]);
  	
    emit(["TYPE", doc.acheteur_identifiant, campagne, doc.type_transaction], [campagne, doc.valide.statut, doc._id, doc.numero_contrat, archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve]);
  	
    emit(["TYPE", doc.vendeur_identifiant, campagne, doc.type_transaction], [campagne, doc.valide.statut, doc._id, doc.numero_contrat, archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve]);
    
    if (doc.mandataire_identifiant)
	emit(["SOCIETE", campagne, doc.mandataire_identifiant, doc.valide.statut],  [campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.numero_archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.createur_identifiant, doc.valide.date_signature_vendeur, doc.valide.date_signature_acheteur, doc.valide.date_signature_courtier, doc.bouteilles_quantite, doc.jus_quantite, doc.raisin_quantite, doc.prix_initial_unitaire]);
  	
    emit(["SOCIETE", campagne, doc.acheteur_identifiant, doc.valide.statut], [campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.numero_archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.createur_identifiant, doc.valide.date_signature_vendeur, doc.valide.date_signature_acheteur, doc.valide.date_signature_courtier, doc.bouteilles_quantite, doc.jus_quantite, doc.raisin_quantite, doc.prix_initial_unitaire]);
  	
    emit(["SOCIETE", campagne, doc.vendeur_identifiant, doc.valide.statut], [campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.numero_archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.createur_identifiant, doc.valide.date_signature_vendeur, doc.valide.date_signature_acheteur, doc.valide.date_signature_courtier, doc.bouteilles_quantite, doc.jus_quantite, doc.raisin_quantite, doc.prix_initial_unitaire]);
  	


}