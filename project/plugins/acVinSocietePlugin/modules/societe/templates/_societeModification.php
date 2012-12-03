<div id="recherche_societe" class="section_label_maj">
    <h2>Sélectionner un type de société </h2>        
        <?php
        echo $societeForm->renderHiddenFields();
        echo $societeForm->renderGlobalErrors();
        ?>
        <div class="section_label_maj" id="raison_sociale">
            <?php echo $societeForm['raison_sociale']->renderError(); ?>
            <?php echo $societeForm['raison_sociale']->renderLabel(); ?>
            <?php echo $societeForm['raison_sociale']->render(); ?>
        </div>
        <div class="section_label_maj" id="raison_sociale_abregee">
            <?php echo $societeForm['raison_sociale_abregee']->renderLabel(); ?>
            <?php echo $societeForm['raison_sociale_abregee']->render(); ?>
            <?php echo $societeForm['raison_sociale_abregee']->renderError(); ?>
        </div>
        <div class="section_label_maj" id="statut">
            <?php echo $societeForm['statut']->renderLabel(); ?>
            <?php echo $societeForm['statut']->render(); ?>
            <?php echo $societeForm['statut']->renderError(); ?>
        </div>
        <div class="section_label_maj" id="type_societe">
            <?php echo $societeForm['type_societe']->renderLabel(); ?>
            <?php echo $societeForm['type_societe']->render(); ?>
            <?php echo $societeForm['type_societe']->renderError(); ?>
        </div>                
        <div class="section_label_maj" id="type_numero_compte">
            <?php echo $societeForm['type_numero_compte']->renderLabel(); ?>
            <?php echo $societeForm['type_numero_compte']->render(); ?>
            <?php echo $societeForm['type_numero_compte']->renderError(); ?>
        </div>                 
        <div class="section_label_maj" id="siret">
            <?php echo $societeForm['siret']->renderLabel(); ?>
            <?php echo $societeForm['siret']->render(); ?>
            <?php echo $societeForm['siret']->renderError(); ?>
        </div>                
        <div class="section_label_maj" id="code_naf">
            <?php echo $societeForm['code_naf']->renderLabel(); ?>
            <?php echo $societeForm['code_naf']->render(); ?>
            <?php echo $societeForm['code_naf']->renderError(); ?>
        </div>
        <?php
        foreach ($societeForm->getObject()->enseignes as $key => $enseigne) :
            ?>
            <div class="section_label_maj" id="code_naf">
                <?php echo $societeForm['enseignes[' . $key . ']']->renderLabel(); ?>
                <?php echo $societeForm['enseignes[' . $key . ']']->render(); ?>
                <?php echo $societeForm['enseignes[' . $key . ']']->renderError(); ?>
            </div>
            <?php
        endforeach;
        ?>
        <div class="section_label_maj" id="code_naf">
            <?php echo $societeForm['tva_intracom']->renderLabel(); ?>
            <?php echo $societeForm['tva_intracom']->render(); ?>
            <?php echo $societeForm['tva_intracom']->renderError(); ?>
        </div>
        <div class="section_label_maj" id="commentaire">
            <?php echo $societeForm['commentaire']->renderLabel(); ?>
            <?php echo $societeForm['commentaire']->render(); ?>
            <?php echo $societeForm['commentaire']->renderError(); ?>
        </div>
</div>
