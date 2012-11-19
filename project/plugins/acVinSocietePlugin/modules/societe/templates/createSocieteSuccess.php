<div id="contenu">

    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong> > Visualisation d'un générations d'impression</p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Création d'une société</h2>
            <form action="<?php echo url_for('societe_creation'); ?>" method="post">
                <?php
                echo $form->renderHiddenFields();
                echo $form->renderGlobalErrors();
                ?>  
                <div class="section_label_maj">
                    <label>
                        <?php echo $form['identifiant']->renderLabel() ?>
                    </label>
                    <div class="bloc_form">
                        <?php echo $form['identifiant']->renderError() ?>       
                        <?php echo $form['identifiant']->render() ?>
                    </div>
                </div>
                <div class="section_label_maj">
                    <label>
                        <?php echo $form['siret']->renderLabel() ?>
                    </label>
                    <div class="bloc_form">
                        <?php echo $form['siret']->renderError() ?>       
                        <?php echo $form['siret']->render() ?>
                    </div>
                </div>
                <div class="section_label_maj">
                    <label>
                        <?php echo $form['raison_sociale']->renderLabel() ?>
                    </label>
                    <div class="bloc_form">
                        <?php echo $form['raison_sociale']->renderError() ?>       
                        <?php echo $form['raison_sociale']->render() ?>
                    </div>
                </div>
                <div class="section_label_maj">
                    <label>

                        <?php echo $form['telephone']->renderLabel() ?>      
                    </label>
                    <div class="bloc_form">

                        <?php echo $form['telephone']->renderError() ?>       
                        <?php echo $form['telephone']->render() ?>
                    </div>
                </div>
                <div>
                    <button type="submit" id="societe_valid" class="btn_majeur btn_valider">Valider</button>
                    <a href="<?php echo '#'; ?>" id="alerte_valid" class="btn_majeur btn_valider">Ajouter un Chai</a>
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
