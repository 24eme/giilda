<?php
$email_teledecl = null;
if($compte->getSociete()->isTransaction()){
   $email_teledecl = $compte->getSociete()->getEtablissementPrincipal()->getEmailTeledeclaration();
}else{
    $email_teledecl = $compte->getSociete()->getEmailTeledeclaration();
}
 
?>
<div id="principal" class="clearfix">
    <h2 class="titre_principal">Mon compte</h2>

    <p>Pour changer des informations supplémentaire, veuillez passer par Interloire.</p>
    <br/>
    
        <div id="modification_compte" class="fond" >
            <div class="presentation clearfix"<?php if ($form->hasErrors()) echo ' style="display:none;"'; ?> >
                <p class="titre_section">Vos identifiants de connexion : </p>
                <br/>
                <?php if ($sf_user->hasFlash('maj')) : ?>
                    <p class="flash_message text-info"><?php echo $sf_user->getFlash('maj'); ?></p>
                <?php endif; ?>
                <div class="bloc_form bloc_form_condensed" >        
                    <div class="ligne_form ligne_form_alt">
                        <label>Email :</label> <?php echo $email_teledecl; ?>
                    </div>
                    <div class="ligne_form">
                        <label>Mot de passe :</label> ****** 
                    </div>
                    <div class="ligne_form ligne_form_alt">
                        <label>&nbsp;</label>
                    </div>
                </div>

                <div class="ligne_btn">
                    <a href="<?php echo url_for('homepage'); ?>" class=" btn_majeur " alt="Retour" style="cursor: pointer; float: left;">Retour</a>
                    <a href="#" class=" btn_majeur btn_modifier modifier" style="cursor: pointer; float: right;">Modifier les informations</a>
                </div>

            </div>
            <div class="modification clearfix"<?php if (!$form->hasErrors()) echo ' style="display:none;"'; ?>>
                <p class="intro">Modification de vos identifiants de connexion :</p>
                <p class="titre_section"><strong>Votre mot de passe</strong> doit contenir au minimum 8 caractères alphanumériques.</p>
                <br/>
                <form method="post" action="">
                    <?php echo $form->renderHiddenFields(); ?>
                    <?php echo $form->renderGlobalErrors(); ?>

                    <div class="bloc_form bloc_form_condensed" >   
                        <div class="ligne_form ligne_form_alt">
                            <?php echo $form['email']->renderError() ?>
                            <?php echo $form['email']->renderLabel() ?>
                            <?php echo $form['email']->render() ?>
                        </div>
                        <div class="ligne_form" >
                            <?php echo $form['mdp1']->renderError() ?>
                            <?php echo $form['mdp1']->renderLabel() ?>
                            <?php echo $form['mdp1']->render() ?>
                        </div>
                        <div class="ligne_form ligne_form_alt">
                            <?php echo $form['mdp2']->renderError() ?>
                            <?php echo $form['mdp2']->renderLabel() ?>
                            <?php echo $form['mdp2']->render() ?>
                        </div>
                    </div>

                    <div class="ligne_btn">
                        <a class="btn_rouge btn_majeur annuler" style="float: left;" href="#" >Annuler</a>
                        <button type="submit" class=" btn_majeur btn_valider modifier" style="cursor: pointer; float: right;" >Valider</button>
                    </div>

                </form>
            </div>


        </div>

</div>

<script type="text/javascript">
    $("#modification_compte a.modifier, #modification_compte a.annuler").click(function() {
        $("#modification_compte div.presentation").toggle();
        $("#modification_compte div.modification").toggle();
    });
</script>

