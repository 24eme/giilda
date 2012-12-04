<div id="eatblissement_<?php echo $etablissement->identifiant; ?>" class="form_section">
    <h2>Détail de la société </h2>  
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
    </div>
</div>
