<div id="societe_visualisation" class="section_label_maj">
    <h2>Détail de la société </h2>   
        <div class="section_label_maj" id="raison_sociale">
           <?php echo $societe->raison_sociale; ?>
        </div>
        <div class="section_label_maj" id="raison_sociale_abregee">
            <?php echo $societe->raison_sociale_abregee; ?>
        </div>
        <div class="section_label_maj" id="statut">
            <?php echo $societe->statut; ?>
        </div>
        <div class="section_label_maj" id="type_societe">
           <?php echo $societe->type_societe; ?>
        </div>                
        <div class="section_label_maj" id="type_numero_compte">
           <?php echo $societe->type_numero_compte; ?>
        </div>                 
        <div class="section_label_maj" id="siret">
             <?php echo $societe->siret; ?>
        </div>                
        <div class="section_label_maj" id="code_naf">
            <?php echo $societe->code_naf; ?>
        </div>
        <?php
        foreach ($societe->enseignes as $key => $enseigne) :
            ?>
            <div class="section_label_maj" id="enseigne_<?php echo $key; ?>">
                <?php echo $enseigne; ?>
            </div>
            <?php
        endforeach;
        ?>
        <div class="section_label_maj" id="tva_intracom">
            <?php echo $societe->tva_intracom; ?>
        </div>
        <div class="section_label_maj" id="commentaire">
            <?php echo $societe->commentaire; ?>
        </div>
</div>
