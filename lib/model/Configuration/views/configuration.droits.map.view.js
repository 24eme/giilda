function(doc)
{
    if (doc.type != "Configuration")
        return ;
    var doc = doc;
    var nb = 0;
   
    var fctObjectCopy = function (objectToCopy){
        var copy = JSON.parse(JSON.stringify(objectToCopy));
        return copy;
    }
   
    var fctArrayCopy = function (arrayToCopy) {
        var newTab = new Array();
        for(var i in arrayToCopy) {
            newTab[i] = fctObjectCopy(arrayToCopy[i]);
        }
        return newTab;
    }
   
    for (certification in doc.declaration.certifications)
    {
        var interprosCertification = new Array();
        for (i in doc.declaration.certifications[certification].interpro)
        {
            interprosCertification[i] = new Array();
            interprosCertification[i]["douane"] = new Array();
            interprosCertification[i]["cvo"] = new Array();
            interprosCertification[i]["douane"][5] = new Array();
            interprosCertification[i]["cvo"][5] = new Array();
            interprosCertification[i]["douane"][4] = new Array();
            interprosCertification[i]["cvo"][4] = new Array();
            interprosCertification[i]["douane"][3] = new Array();
            interprosCertification[i]["cvo"][3] = new Array();
            interprosCertification[i]["douane"][2] = new Array();
            interprosCertification[i]["cvo"][2] = new Array();
            interprosCertification[i]["douane"][1] = new Array();
            interprosCertification[i]["cvo"][1] = new Array();
            interprosCertification[i]["douane"][0] = new Array();
            interprosCertification[i]["cvo"][0] = new Array();
            interprosCertification[i]["douane"][5] = doc.declaration.certifications[certification].interpro[i].droits.douane;
            interprosCertification[i]["cvo"][5] = doc.declaration.certifications[certification].interpro[i].droits.cvo;
        }
       
        for (genre in doc.declaration.certifications[certification].genres)
        {
            for(i in interprosCertification)
            {
                interprosCertification[i]["douane"][4] = new Array();
                interprosCertification[i]["cvo"][4] = new Array();
            }
            if (doc.declaration.certifications[certification].genres[genre].hasOwnProperty("interpro"))
            {
                nb = 0;
                for (item in doc.declaration.certifications[certification].genres[genre].interpro) {
                    nb = nb + 1;
                }
                if (nb > 0) {
                    for (i in doc.declaration.certifications[certification].genres[genre].interpro)
                    {
                        if (doc.declaration.certifications[certification].genres[genre].interpro[i].hasOwnProperty("droits"))
                        {
                            if (doc.declaration.certifications[certification].genres[genre].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane.length > 0)
                            {
                                interprosCertification[i]["douane"][4] = doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane;
                            }
                            if (doc.declaration.certifications[certification].genres[genre].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo.length > 0)
                            {
                                interprosCertification[i]["cvo"][4] = doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo;
                            }
                        }
                    }
                }
            }
            for (appellation in doc.declaration.certifications[certification].genres[genre].appellations)
            {
                for(i in interprosCertification)
                {
                    interprosCertification[i]["douane"][3] = new Array();
                    interprosCertification[i]["cvo"][3] = new Array();
                }
                if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].hasOwnProperty("interpro"))
                {
                    nb = 0;
                    for (item in doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro) {
                        nb = nb + 1;
                    }
                    if (nb > 0) {
                        for (i in doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro)
                        {
                            if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].hasOwnProperty("droits"))
                            {
                                if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane.length > 0)
                                {
                                    interprosCertification[i]["douane"][3] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane;
                                }
                                if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo.length > 0)
                                {
                                    interprosCertification[i]["cvo"][3] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo;
                                }
                            }
                        }
                    }
                }
                for(mention in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions)
                {
                    for(i in interprosCertification)
                    {
                        interprosCertification[i]["douane"][2] = new Array();
                        interprosCertification[i]["cvo"][2] = new Array();
                    }
                    if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].hasOwnProperty("interpro"))
                    {
                        nb = 0;
                        for (item in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro) {
                            nb = nb + 1;
                        }
                        if (nb > 0)
                        {
                            for (i in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro)
                            {
                                if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].hasOwnProperty("droits"))
                                {
                                    if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane.length > 0)
                                    {
                                        interprosCertification[i]["douane"][2] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane;
                                    }
                                    if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo.length > 0)
                                    {
                                        interprosCertification[i]["cvo"][2] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo;
                                    }
                                }
                            }
                        }
                    }
                    for(lieu in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux)
                    {
                        for(i in interprosCertification)
                        {
                            interprosCertification[i]["douane"][1] = new Array();
                            interprosCertification[i]["cvo"][1] = new Array();
                        }
                        if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].hasOwnProperty("interpro"))
                        {
                            nb = 0;
                            for (item in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro) {
                                nb = nb + 1;
                            }
                            if (nb > 0)
                            {
                                for (i in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro)
                                {
                                    if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].hasOwnProperty("droits"))
                                    {
                                        if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane.length > 0)
                                        {
                                            interprosCertification[i]["douane"][1] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane;
                                        }
                                        if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo.length > 0)
                                        {
                                            interprosCertification[i]["cvo"][1] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo;
                                        }
                                    }
                                }
                            }
                        }                       
                        for(couleur in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs)
                        {
                            for(i in interprosCertification)
                            {
                                interprosCertification[i]["douane"][0] = new Array();
                                interprosCertification[i]["cvo"][0] = new Array();
                            }
                            if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].hasOwnProperty("interpro"))
                            {
                                nb = 0;
                                for (item in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro) {
                                    nb = nb + 1;
                                }
                                if (nb > 0)
                                {
                                    for (i in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro)
                                    {
                                        if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].hasOwnProperty("droits"))
                                        {
                                            if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane.length > 0)
                                            {
                                                interprosCertification[i]["douane"][0] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane;
                                            }
                                            if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo.length > 0)
                                            {
                                                interprosCertification[i]["cvo"][0] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo;
                                            }
                                        }
                                    }
                                }
                            }
                            for(cepage in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].cepages)
                            {   
                                var hash = "/declaration/certifications/"+certification+"/genres/"+genre+"/appellations/"+appellation+"/mentions/"+mention+"/lieux/"+lieu+"/couleurs/"+couleur+"/cepages/"+cepage;
                                for(i in interprosCertification)
                                {
                                    emit([hash, "douane", i], fctArrayCopy(interprosCertification[i]["douane"]));
                                    emit([hash, "cvo", i], fctArrayCopy(interprosCertification[i]["cvo"]));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}