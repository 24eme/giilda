<!-- #principal -->
<section id="principal">
     <ol class="breadcrumb">
        <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
        <li class="active"><a href="">Société <?php echo $societe->raison_sociale; ?></a></li>

    </ol>
    <!-- #contacts -->
    <section id="contacts">
        <div id="creation_societe">
            <div class="col-md-8 col-md-offset-2">
            <h2></h2>
            <form class="form-horizontal" action="<?php echo url_for('societe_modification', array('identifiant' => $societeForm->getObject()->identifiant)); ?>" method="post">
                <?php if(isset($validation)): ?>
                    <?php include_partial('document_validation/validation', array('validation' => $validation)); ?>
                <?php endif; ?>
                <div id="detail_societe" class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Détail de la société</h3></div>
                    <?php if($reduct_rights) :
                            include_partial('societeModificationRestricted', array('societeForm' => $societeForm));
                            else :
                            include_partial('societeModification', array('societeForm' => $societeForm));
                        endif;
                    ?>
                </div>
                <div id="coordonnees_societe" class="form_section ouvert">
                    <h3>Coordonnées de la société</h3>
               <?php
                    if($reduct_rights) :
                            include_partial('compte/modificationCoordonneeRestricted', array('compteForm' => $contactSocieteForm, 'isCompteSociete' => true));
                            else :
                            include_partial('compte/modificationCoordonnee', array('compteForm' => $societeForm, 'isCompteSociete' => true));
                        endif;
                    ?>
                </div>
                <div class="col-xs-6">
                    <a href="<?php echo url_for('societe_visualisation', $societe); ?>" class="btn btn-default">Annuler</a>
                </div><div class="col-xs-6 text-right">
                    <button id="btn_valider" type="submit" class="btn btn-success">Valider</button>
                </div>
               </div>
            </div>
        </form>
   </section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn btn-default"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
