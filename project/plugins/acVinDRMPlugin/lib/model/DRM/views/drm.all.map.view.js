function(doc) {
    if (doc.type != "DRM") {

        return;
    }
    var transmission = "NON";
    var horodatage = null;
    var coherente = null;
    var diff = null;
    var exclusion_stats = false;
    if (doc.transmission_douane) {
    	if (doc.transmission_douane.success) {
    		transmission = "SUCCESS";
    	  horodatage = doc.transmission_douane.horodatage;
    	}else{
    		transmission = "ERREUR";
    	}
    	coherente = doc.transmission_douane.coherente;
    	diff = doc.transmission_douane.diff;
    }
    if (doc.declarant.exclusion_stats) {
      exclusion_stats = (doc.declarant.exclusion_stats);
    }
    emit([doc.identifiant, doc.campagne, doc.periode, doc.version, doc.type_creation, doc.valide.date_saisie, doc.douane.envoi, doc.douane.accuse, doc.numero_archive, doc.teledeclare, transmission, horodatage, coherente, diff, exclusion_stats], 1);
}
