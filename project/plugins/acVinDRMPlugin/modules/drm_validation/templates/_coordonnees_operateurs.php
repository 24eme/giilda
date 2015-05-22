<p>Vous êtes sur le point de valider votre DRM.</p>
<p>Veuillez vérifier les informations ci-dessous avant validation : </p>            
<div id="drm_validation_coordonnees">
    <div class="drm_validation_societe">    
        <div class="drm_validation_societe_info">
            <div class="title"><?php echo $drm->societe->raison_sociale; ?></div>
            <div class="panel">
                <ul>
                    <li>
                        <span class="label">SIRET :</span>
                        <span class="infos"><?php echo $drm->societe->siret; ?></span>
                    </li>
                    <li>
                        <span class="label">Adresse :</span>
                        <span class="infos"><?php echo $drm->societe->adresse; ?></span>
                    </li>
                    <li>
                        <span class="label">Code postal :</span>
                        <span class="infos"><?php echo $drm->societe->code_postal; ?></span>
                    </li>
                    <li>
                        <span class="label">Commune :</span>
                        <span class="infos"><?php echo $drm->societe->commune; ?></span>
                    </li>
                    <li>
                        <span class="label">E-mail :</span>
                        <span class="infos"><?php echo $drm->societe->email; ?></span>
                    </li>
                    <li>
                        <span class="label">Téléphone :</span>
                        <span class="infos"><?php echo $drm->societe->telephone; ?></span>
                    </li>
                    <li>
                        <span class="label">Fax :</span>
                        <span class="infos"><?php echo $drm->societe->fax; ?></span>
                    </li>
                </ul>
            </div>
        </div>
        <form action="<?php echo "#" ?>" method="POST" class="drm_validation_societe_form"  style="display: none;">
            <div class="title"><?php echo $drm->societe->raison_sociale; ?></div>
            <div class="panel">
                <ul>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesSocieteForm['siret']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesSocieteForm['siret']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesSocieteForm['adresse']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesSocieteForm['adresse']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesSocieteForm['code_postal']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesSocieteForm['code_postal']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesSocieteForm['commune']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesSocieteForm['commune']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesSocieteForm['email']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesSocieteForm['email']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesSocieteForm['telephone']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesSocieteForm['telephone']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesSocieteForm['fax']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesSocieteForm['fax']->render(); ?></span>
                    </li>
                </ul>
            </div>
        </form> 
    </div>
    <div class="drm_validation_etablissement">
        <form action="<?php echo "#" ?>" method="POST" class="drm_validation_etablissement_form" style="display: none;">
            <div class="title"><?php echo $drm->declarant->nom; ?></div>
            <div class="panel">
                <ul>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesEtablissementForm['cvi']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesEtablissementForm['cvi']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesEtablissementForm['accise']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesEtablissementForm['accise']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesEtablissementForm['adresse']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesEtablissementForm['adresse']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesEtablissementForm['code_postal']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesEtablissementForm['code_postal']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $validationCoordonneesEtablissementForm['commune']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $validationCoordonneesEtablissementForm['commune']->render(); ?></span>
                    </li>                           
                </ul>
            </div>
        </form>
        <div class="drm_validation_etablissement_info">
            <div class="title"><?php echo $drm->declarant->nom; ?></div>
            <div class="panel">
                <ul>
                    <li>
                        <span class="label">CVI :</span>
                        <span class="infos"><?php echo $drm->declarant->cvi; ?></span>
                    </li>
                    <li>
                        <span class="label">ACCISE :</span>
                        <span class="infos"><?php echo $drm->declarant->no_accises; ?></span>
                    </li>
                    <li>
                        <span class="label">Adresse :</span>
                        <span class="infos"><?php echo $drm->declarant->adresse; ?></span>
                    </li>
                    <li>
                        <span class="label">Code postal :</span>
                        <span class="infos"><?php echo $drm->declarant->code_postal; ?></span>
                    </li>
                    <li>
                        <span class="label">Commune :</span>
                        <span class="infos"><?php echo $drm->declarant->commune; ?></span>
                    </li>                           
                </ul>
            </div>
        </div> 
    </div>
</div>

