<!-- #principal -->
    <section id="principal" class="alerte">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            
            <form action="<?php echo url_for('alerte_modification_statuts', array('retour' => 'etablissement')); ?>" method="post" >
            <div id="toutes_alertes">
                    <h2>Les alertes de <?php echo $etablissement->nom; ?></h2>

            <?php include_partial('liste_alertes_etablissement', array('alertesEtablissement' => $alertesEtablissement, 'modificationStatutForm' => $modificationStatutForm)); ?>
            </div>
            <div id="modification_alerte">	
                <h2>Modification des alertes sélectionnées</h2>
                <?php
                echo $modificationStatutForm->renderHiddenFields();
                echo $modificationStatutForm->renderGlobalErrors();
                ?>

                <div class="bloc_form">
                    <div class="ligne_form">
                        <?php echo $modificationStatutForm['statut_all_alertes']->renderError(); ?>
                        <?php echo $modificationStatutForm['statut_all_alertes']->renderLabel() ?>
                        <?php echo $modificationStatutForm['statut_all_alertes']->render() ?> 
                    </div>
                    <div class="ligne_form ligne_form_alt">
                        <?php echo $modificationStatutForm['commentaire_all_alertes']->renderError(); ?>
                        <?php echo $modificationStatutForm['commentaire_all_alertes']->renderLabel() ?>
                        <?php echo $modificationStatutForm['commentaire_all_alertes']->render() ?> 
                    </div>
                </div>

                <div class="btn_form">
                    <button type="submit" id="alerte_valid" class="btn_majeur btn_modifier">Modifier</button>
                </div>
            </div>
            </form>

        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('alerte'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
