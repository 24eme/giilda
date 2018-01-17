function(doc) {
     if (doc.type != "DRM" && doc.type != 'SV12') {

         return;
     }

     if (!doc.valide.date_saisie) {

         return;
     }

     identifiant = doc.identifiant;
     if(doc.mouvements[identifiant]) {
             for (key in doc.mouvements[identifiant]) {
                 var mouv = doc.mouvements[identifiant][key];
                 pays = "";
                 if (mouv.type_hash && mouv.type_hash.match(/^export.*_details$/)) {
                   pays = mouv.detail_libelle;
                 }
                 type_drm = "SUSPENDU";
                 if (mouv.type_drm) {
                   type_drm = mouv.type_drm;
                 }
                 type_drm_libelle = "Suspendu";
                 if (mouv.type_drm_libelle) {
                   type_drm_libelle = mouv.type_drm_libelle;
                 }
                 emit([doc.type, identifiant, doc.campagne, doc.periode, doc._id, mouv.produit_hash, type_drm, mouv.type_hash, mouv.vrac_numero, mouv.detail_identifiant], [doc.declarant.nom, mouv.produit_libelle, type_drm_libelle, mouv.type_libelle, mouv.volume, mouv.vrac_destinataire, mouv.detail_libelle, mouv.date_version, mouv.version, mouv.cvo, mouv.facturable, doc._id+'/mouvements/'+identifiant+'/'+key, pays, mouv.facture]);
             }
     }
 }
