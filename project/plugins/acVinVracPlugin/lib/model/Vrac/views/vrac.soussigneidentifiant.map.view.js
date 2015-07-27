function(doc) {
    if (doc.type != "Vrac") {
        return;
  }
 
    if (doc.mandataire_identifiant) {
    emit([doc.campagne, doc.mandataire_identifiant, doc.date_signature, doc.valide.statut],  [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.numero_archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.createur_identifiant, doc.valide.date_signature_vendeur, doc.valide.date_signature_acheteur, doc.valide.date_signature_courtier, doc.bouteilles_quantite, doc.jus_quantite, doc.raisin_quantite, doc.prix_initial_unitaire, doc.date_signature]);
    }
      emit([doc.campagne, doc.acheteur_identifiant, doc.date_signature, doc.valide.statut], [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.numero_archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.createur_identifiant, doc.valide.date_signature_vendeur, doc.valide.date_signature_acheteur, doc.valide.date_signature_courtier, doc.bouteilles_quantite, doc.jus_quantite, doc.raisin_quantite, doc.prix_initial_unitaire, doc.date_signature]);
    
      emit([doc.campagne, doc.vendeur_identifiant, doc.date_signature, doc.valide.statut], [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, doc.numero_archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.createur_identifiant, doc.valide.date_signature_vendeur, doc.valide.date_signature_acheteur, doc.valide.date_signature_courtier, doc.bouteilles_quantite, doc.jus_quantite, doc.raisin_quantite, doc.prix_initial_unitaire, doc.date_signature]);
}