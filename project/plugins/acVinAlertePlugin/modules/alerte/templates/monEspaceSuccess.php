<div id="contenu" class="alerte">

    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            
            <form action="<?php echo url_for('alerte_modification_statuts'); ?>" method="post" >
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

    <!-- #colonne -->
    <aside id="colonne">
        <div class="bloc_col" id="contrat_aide">
            <h2>Aide</h2>

            <div class="contenu">
                <ul>
                    <li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
                    <li class="assistance"><a href="#">Assistance</a></li>
                    <li class="contact"><a href="#">Contacter le support</a></li>
                </ul>
            </div>
        </div>
    </aside>
    <!-- fin #colonne -->
</div>
