<?php
$typesLiaisons =  EtablissementClient::getTypesLiaisons();
?>
<div id="etablissement_<?php echo $etablissement->identifiant; ?>" class="etablissement form_section ouvert">
    <h3><?php echo $etablissement->nom; ?></h3>
    <div class="form_modifier">
      <a id="btn_modifier" href="<?php echo url_for('etablissement_modification', $etablissement); ?>" class="btn_majeur btn_modifier">Modifier</a>
    </div>
    <div class="form_contenu">  
        <div class="form_ligne">
            <label for="famille">
                Type établissement 
            </label>
            <?php echo $etablissement->famille; ?>
        </div>
        <div class="form_ligne">
            <label for="ordre">
                Ordre affichage 
            </label> 
            <?php echo $ordre; ?>
        </div>
        <div class="form_ligne"> 
            <label for="nom">
                Nom du chai :
            </label>
            <?php echo $etablissement->nom; ?>
        </div>
        <div class="form_ligne">
            <label for="statut">
                Statut </label>
            <?php echo $etablissement->statut; ?>
        </div>
        <div class="form_ligne"> 
            <label for="cvi">
                CVI </label>
            <?php echo $etablissement->cvi; ?>
        </div>                
        <div class="form_ligne">
            <label for="commune">
                Ville :
            </label>
            <?php echo $etablissement->siege->commune; ?>
        </div>
        <?php if (count($etablissement->liaisons_operateurs) > 0) : ?> 
            <div class="form_ligne">
                <legend>
                    Liaisons opérateurs :
                </legend>
            </div>
            <?php foreach ($etablissement->liaisons_operateurs as $liaison_operateur): ?>
                <div class="form_ligne">
                    <div class="form_colonne">
                        <label for="libelle_etablissement"><?php echo $typesLiaisons[$liaison_operateur->type_liaison]; ?></label>     
                        <?php echo $liaison_operateur->libelle_etablissement; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
