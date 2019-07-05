<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="<?php echo url_for('societe'); ?>">Page d'accueil</a> &gt; <strong><?php echo ($societe->isInCreation()) ? "Création d'une nouvelle société" : $societe->raison_sociale; ?></strong></p>
    <!-- #contacts -->
    <section id="contacts">
        <div id="creation_societe">
            <h2><?php echo ($societe->isInCreation()) ? "Création d'une nouvelle société" : $societe->raison_sociale; ?></h2>
            <form action="<?php echo url_for('societe_modification', array('identifiant' => $societeForm->getObject()->identifiant)); ?>" method="post">
                <div class="form_btn">
                    <?php if($societe->isInCreation()): ?>
                    <a href="<?php echo url_for('societe_annulation', $societe); ?>" class="btn_majeur btn_annuler">Annuler</a>
                    <?php else: ?>
                    <a href="<?php echo url_for('societe_visualisation', $societe); ?>" class="btn_majeur btn_annuler">Annuler</a>
                    <?php endif; ?>
                    <button id="btn_valider" type="submit" class="btn_majeur btn_valider">Valider</button>
                </div>
                <?php if(isset($validation)): ?>
                    <?php include_partial('document_validation/validation', array('validation' => $validation)); ?>
                <?php endif; ?>
                <div id="detail_societe" class="form_section ouvert">
                    <h3>Détail de la société</h3>
                    <?php if($reduct_rights) :
                            include_partial('societeModificationRestricted', array('societeForm' => $societeForm));
                            else :
                            include_partial('societeModification', array('societeForm' => $societeForm));
                        endif;
                    ?>
                </div>
                <div id="detail_societe_sepa" class="form_section ouvert">
                  <h3>Détail de la société</h3>
                  <?php
                    if(!$reduct_rights) :
                      include_partial('societeSepaModification', array('societeForm' => $societeForm));
                    endif;
                  ?>
                </div>
                <div id="coordonnees_societe" class="form_section ouvert">
                    <h3>Coordonnées de la société</h3>
               <?php
                    if($reduct_rights) :
                            include_partial('compte/modificationCoordonneeRestricted', array('compteForm' => $contactSocieteForm, 'compteSociete' => true));
                            else :
                            include_partial('compte/modificationCoordonnee', array('compteForm' => $contactSocieteForm, 'compteSociete' => true));
                        endif;
                    ?>
                </div>
                <div class="form_btn">
                    <?php if($societe->isInCreation()): ?>
                    <a href="<?php echo url_for('societe_annulation', $societe); ?>" class="btn_majeur btn_annuler">Annuler</a>
                    <?php else: ?>
                    <a href="<?php echo url_for('societe_visualisation', $societe); ?>" class="btn_majeur btn_annuler">Annuler</a>
                    <?php endif; ?>
                    <button id="btn_valider" type="submit" class="btn_majeur btn_valider">Valider</button>
                </div>
            </form>
        </div>
    </section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
