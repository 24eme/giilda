<div id="eatblissement_<?php echo $etablissement->identifiant;?>" class="section_label_maj">
        <div class="section_label_maj" id="type_etablissement">
           Type établissement <?php echo $etablissement->famille; ?>
        </div>
        <div class="section_label_maj" id="type_etablissement">
           Ordre affichage <?php echo $ordre; ?>
        </div>
        <div class="section_label_maj" id="nom_chai">
           Nom du chai <?php echo $etablissement->nom; ?>
        </div>
        <div class="section_label_maj" id="statut">
           Statut <?php echo $etablissement->statut; ?>
        </div>
        <div class="section_label_maj" id="cvi">
          CVI <?php echo $etablissement->cvi; ?>
        </div>                
        <div class="section_label_maj" id="ville">
           Ville <?php echo $etablissement->siege->commune; ?>
        </div> 
</div>
