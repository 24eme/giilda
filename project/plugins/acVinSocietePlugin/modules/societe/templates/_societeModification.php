<div id="detail_societe" class="form_section">
    <h2>Détail de la société</h2>  

    <?php
    echo $societeForm->renderHiddenFields();
    echo $societeForm->renderGlobalErrors();
    ?>
    <div class="form_contenu">
        <div class="form_ligne">
            <?php echo $societeForm['raison_sociale']->renderError(); ?>
            <label for="raison_sociale">
                <?php echo $societeForm['raison_sociale']->renderLabel(); ?>
                <label for="raison_sociale">
                    <?php echo $societeForm['raison_sociale']->render(); ?>
                    </div>
                    <div class="form_ligne">
                        <label for="raison_sociale_abregee">
                            <?php echo $societeForm['raison_sociale_abregee']->renderLabel(); ?>
                            <label>
                                <?php echo $societeForm['raison_sociale_abregee']->render(); ?>
                                <?php echo $societeForm['raison_sociale_abregee']->renderError(); ?>
                                </div>
                                <div class="form_ligne">
                                    <label for="statut">
                                        <?php echo $societeForm['statut']->renderLabel(); ?>
                                    </label>
                                    <?php echo $societeForm['statut']->render(); ?>
                                    <?php echo $societeForm['statut']->renderError(); ?>
                                </div>
                                <div class="form_ligne">
                                    <label for="cooperative">
                                        <?php echo $societeForm['cooperative']->renderLabel(); ?>
                                    </label>
                                    <?php echo $societeForm['cooperative']->render(); ?>
                                    <?php echo $societeForm['cooperative']->renderError(); ?>
                                </div>
                                <!--         <div class="form_ligne">
                                             <label for="type_societe">
                                <?php //echo $societeForm['type_societe']->renderLabel(); ?>
                                             </label>
                                <?php //echo $societeForm['type_societe']->render(); ?>
                                <?php //echo $societeForm['type_societe']->renderError(); ?>
                                        </div>                -->
                                <div class="form_ligne">
                                    <label for="type_numero_compte">
                                        <?php echo $societeForm['type_numero_compte']->renderLabel(); ?>
                                    </label>
                                    <?php echo $societeForm['type_numero_compte']->render(); ?>
                                    <?php echo $societeForm['type_numero_compte']->renderError(); ?>
                                </div>                 
                                <div class="form_ligne">
                                    <label for="siret">
                                        <?php echo $societeForm['siret']->renderLabel(); ?>
                                    </label>
                                    <?php echo $societeForm['siret']->render(); ?>
                                    <?php echo $societeForm['siret']->renderError(); ?>
                                </div>                
                                <div class="form_ligne">
                                    <label for="code_naf">
                                        <?php echo $societeForm['code_naf']->renderLabel(); ?>
                                    </label>
                                    <?php echo $societeForm['code_naf']->render(); ?>
                                    <?php echo $societeForm['code_naf']->renderError(); ?>
                                </div>
                                <?php
                                foreach ($societeForm->getObject()->enseignes as $key => $enseigne) :
                                    ?>
                                    <div class="form_ligne">
                                        <label for="enseignes[<?php echo $key; ?>]">
                                            <?php echo $societeForm['enseignes[' . $key . ']']->renderLabel(); ?>
                                        </label>
                                        <?php echo $societeForm['enseignes[' . $key . ']']->render(); ?>
                                        <?php echo $societeForm['enseignes[' . $key . ']']->renderError(); ?>
                                    </div>
                                    <?php
                                endforeach;
                                ?>
                                <div class="form_ligne">
                                    <label for="tva_intracom">
                                        <?php echo $societeForm['tva_intracom']->renderLabel(); ?>
                                    </label>
                                    <?php echo $societeForm['tva_intracom']->render(); ?>
                                    <?php echo $societeForm['tva_intracom']->renderError(); ?>
                                </div>
                                <div class="form_ligne">
                                    <label for="commentaire">
                                        <?php echo $societeForm['commentaire']->renderLabel(); ?>
                                    </label>
                                    <?php echo $societeForm['commentaire']->render(); ?>
                                    <?php echo $societeForm['commentaire']->renderError(); ?>
                                </div>
                                </div>
                                </div>
