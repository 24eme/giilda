<?php
echo $societeForm->renderHiddenFields();
echo $societeForm->renderGlobalErrors();
$societe = $societeForm->getObject();
?>

<div class="form_contenu">
    <div class="form_ligne">
        <label for="type_societe">Type de la société</label>
        <span class="champ_long"><?php echo $societe->type_societe; ?></span>
    </div>
    <div class="form_ligne">
        <label for="societe_modification_raison_sociale">Nom de la société </label> 
        <input type="text" id="societe_modification_raison_sociale" class="champ_long" value="<?php echo $societe->raison_sociale; ?>" disabled="disabled"> 
    </div>
    <div class="form_ligne">
        <div class="form_colonne">
            <label for="societe_modification_raison_sociale_abregee">Abrégé</label>	
            <input type="text" id="societe_modification_raison_sociale_abregee" value="<?php echo $societe->raison_sociale_abregee; ?>" disabled="disabled">
        </div>
        <div class="form_colonne">
            <label for="societe_modification_statut" class="label_liste">Statut</label>
            <ul class="radio_list">
                <li disabled="disabled">
                    <input type="radio" id="societe_modification_statut_ACTIF" disabled="disabled" 
                           <?php echo ($societe->statut == 'ACTIF') ? 'checked="checked"' : ''; ?> >&nbsp;
                    <label for="societe_modification_statut_ACTIF">Actif</label>
                </li>
                <li disabled="disabled">
                    <input type="radio" id="societe_modification_statut_SUSPENDU" disabled="disabled" 
                           <?php echo ($societe->statut != 'ACTIF') ? 'checked="checked"' : ''; ?> >&nbsp;
                    <label for="societe_modification_statut_SUSPENDU">Suspendu</label>
                </li>
            </ul>	
        </div>
    </div>
    <?php if ($societe->isNegoOrViti()) : ?>
        <div class="form_ligne">
            <label class="label_liste" for="societe_modification_cooperative">Cave coopérative </label>
            <ul class="radio_list">
                <li>
                    <input id="societe_modification_cooperative_0" type="radio"  <?php echo (!$societe->cooperative) ? 'checked="checked"' : ""; ?> disabled="disabled" >
                    <label for="societe_modification_cooperative_0">Non</label>
                </li>
                <li>
                    <input id="societe_modification_cooperative_1" type="radio" <?php echo ($societe->cooperative) ? 'checked="checked"' : ""; ?> disabled="disabled" >
                    <label for="societe_modification_cooperative_1">Oui</label>
                </li>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form_ligne"> 
        <label for="societe_modification_type_numero_compte_fournisseur" class="label_liste">Numéros de compte</label>
        <ul class="checkbox_list" disabled="disabled">
            <li disabled="disabled">
                <input id="societe_modification_type_numero_compte_client_CLIENT" type="checkbox" disabled="disabled" <?php echo ($societe->exist('code_comptable_client') && $societe->code_comptable_client) ? 'checked="checked"' : ''; ?> >
                <label for="societe_modification_type_numero_compte_client_CLIENT" disabled="disabled">Client</label>
            </li>
        </ul>
        <ul class="checkbox_list">
            <li disabled="disabled">
                <input id="societe_modification_type_numero_compte_fournisseur_FOURNISSEUR" type="checkbox" disabled="disabled" <?php echo ($societe->exist('code_comptable_fournisseur') && $societe->code_comptable_fournisseur) ? 'checked="checked"' : ''; ?>  >
                <label for="societe_modification_type_numero_compte_fournisseur_FOURNISSEUR">Fournisseur</label>
            </li>
        </ul>
    </div>  
    <div class="form_ligne">
        <label for="societe_modification_type_fournisseur" class="label_liste">Type fournisseur</label> 
        <ul class="checkbox_list">
            <li disabled="disabled">
                <input type="checkbox" id="societe_modification_type_fournisseur_MDV" value="MDV" disabled="disabled" 
                       <?php echo ($societe->exist('type_fournisseur') && in_array('MDV', $societe->type_fournisseur->toArray(true,false))) ? 'checked="checked"' : ''; ?> >&nbsp;
                <label for="societe_modification_type_fournisseur_MDV">MDV</label>
            </li>
            <li disabled="disabled">
                <input type="checkbox" id="societe_modification_type_fournisseur_PLV" value="PLV" disabled="disabled"
                       <?php echo ($societe->exist('type_fournisseur') && in_array('PLV', $societe->type_fournisseur->toArray(true,false))) ? 'checked="checked"' : ''; ?> >&nbsp;
                <label for="societe_modification_type_fournisseur_PLV">PLV</label>
            </li>
        </ul>    
    </div>

    <div class="form_ligne">
        <div class="form_colonne">            
            <label for="societe_modification_siret">SIRET</label>	
            <input type="text" id="societe_modification_siret" disabled="disabled" value="<?php echo $societe->siret; ?>">	
        </div>
        <div class="form_colonne">

            <label for="societe_modification_code_naf">Code Naf</label>		
            <input type="text" id="societe_modification_code_naf" disabled="disabled" value="<?php echo $societe->code_naf; ?>" >	
        </div>
    </div> 
    <div class="form_ligne">
        <label for="societe_modification_no_tva_intracommunautaire">TVA Intracom.</label>       
        <input type="text" id="societe_modification_no_tva_intracommunautaire" disabled="disabled" value="<?php echo $societe->no_tva_intracommunautaire; ?>" >          
    </div> 

    <?php if ($societe->exist('enseignes') && count($societe->enseignes)) : ?>
        <div id="enseignes_list">
            <?php foreach ($societe->enseignes as $cpt => $enseigne) : ?>    
                <div class="form_ligne">
                    <label for="societe_modification_enseignes_<?php echo $cpt; ?>_label">Enseigne</label>
                    <input id="societe_modification_enseignes_<?php echo $cpt; ?>_label" type="text" value="<?php echo $enseigne->label; ?>" disabled="disabled">
                    <a class="btn_supprimer_ligne_template" data-container="div" >Supprimer</a>
                </div>
            <?php endforeach; ?>

            <div class="form_ligne" >
                <a class="btn_ajouter_ligne_template" data-container="#enseignes_list" data-template="#template_enseigne" >Ajouter une enseigne</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="form_ligne">
        <?php echo $societeForm['commentaire']->renderLabel(); ?>
        <?php echo $societeForm['commentaire']->render(); ?>
        <?php echo $societeForm['commentaire']->renderError(); ?>
    </div>
</div>