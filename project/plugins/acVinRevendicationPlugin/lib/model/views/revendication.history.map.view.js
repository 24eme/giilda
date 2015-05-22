function(doc) {
    if (doc.type != "Revendication") {
        return;
    }
    var declarants = {};
    for(var d in doc.datas){
 	declarants[d] = doc.datas[d].declarant_nom;
    }
    emit([doc.campagne, doc.odg, doc.date_creation], [declarants]);
}