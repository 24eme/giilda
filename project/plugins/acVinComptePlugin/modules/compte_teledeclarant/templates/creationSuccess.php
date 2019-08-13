<div id="principal" class="clearfix">
    <form action="" method="post" id="principal">

        <h2 class="titre_principal">Création de votre compte</h2>

<?php
$libelle = "Merci d'indiquer votre e-mail, votre mot de passe";
$libelle .= ($form->getTypeCompte() == SocieteClient::SUB_TYPE_COURTIER)? " et votre numéro de carte professionnelle" : "";
$libelle .= (($form->getTypeCompte() == SocieteClient::SUB_TYPE_VITICULTEUR) || ($form->getTypeCompte() == SocieteClient::SUB_TYPE_NEGOCIANT))?
            " et votre numéro de SIRET" : "";
$libelle .= " :";
?>
        <p class="titre_section"><strong>Conseil :</strong> Utiliser un email connu par vos collaborateurs habilités à télé-déclarer sur vos différents établissements.</p>
        <p class="titre_section"><strong>Votre mot de passe</strong> doit contenir au minimum 8 caractères alphanumériques.</p>
        <br/>
        <p class="titre_section"><?php echo $libelle; ?></p>
        <br/>
        <div id="creation_compte_teledeclaration" class="fond" >
            <div class="bloc_form bloc_form_condensed">               

                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>
                <div class="ligne_form ligne_form_alt">
                    <?php echo $form['email']->renderError() ?>
                    <?php echo $form['email']->renderLabel() ?>
                    <?php echo $form['email']->render() ?>
                </div>
                <div class="ligne_form">
                    <?php echo $form['mdp1']->renderError() ?>
                    <?php echo $form['mdp1']->renderLabel() ?>
                    <?php echo $form['mdp1']->render() ?>
                </div>
                <div class="ligne_form ligne_form_alt">
                    <?php echo $form['mdp2']->renderError() ?>
                    <?php echo $form['mdp2']->renderLabel() ?>
                    <?php echo $form['mdp2']->render() ?>
                </div>
                <?php if ($form->getTypeCompte() == SocieteClient::SUB_TYPE_COURTIER): ?>
                    <div class="ligne_form ">
                        <?php echo $form['carte_pro']->renderError() ?>
                        <?php echo $form['carte_pro']->renderLabel() ?>
                        <?php echo $form['carte_pro']->render() ?>
                    </div>
                <?php endif; ?>
                <?php if (($form->getTypeCompte() == SocieteClient::SUB_TYPE_VITICULTEUR) || ($form->getTypeCompte() == SocieteClient::SUB_TYPE_NEGOCIANT)): ?>
                    <div class="ligne_form ">
                        <?php echo $form['siret']->renderError() ?>
                        <?php echo $form['siret']->renderLabel() ?>
                        <?php echo $form['siret']->render() ?>
                    </div>
                    <div class="ligne_form ligne_form_alt">
                        <?php echo $form['num_accises']->renderError() ?>
                        <?php echo $form['num_accises']->renderLabel() ?>
                        <?php echo $form['num_accises']->render() ?>
                    </div>
                <?php endif; ?>             
            </div>
        </div> 
        <div style="margin: 10px 0; clear: both; float: right;">
            <button class="btn_vert btn_majeur " type="submit">Valider</button> 
        </div>
    </form>
</div>   
