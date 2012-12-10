function(doc) {
  	if (doc.type != "Vrac")
    	return;
  
  	emit(["STATUT", doc.mandataire_identifiant, doc.valide.statut, doc.type_transaction],  [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve]);
  	
  	emit(["STATUT", doc.acheteur_identifiant, doc.valide.statut, doc.type_transaction], [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve]);
  	
  	emit(["STATUT", doc.vendeur_identifiant, doc.valide.statut, doc.type_transaction], [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve]);
  	
  	emit(["TYPE", doc.mandataire_identifiant, doc.type_transaction, doc.campagne],  [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve]);
  	
  	emit(["TYPE", doc.acheteur_identifiant, doc.type_transaction, doc.campagne], [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve]);
  	
  	emit(["TYPE", doc.vendeur_identifiant, doc.type_transaction, doc.campagne], [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve]);
}