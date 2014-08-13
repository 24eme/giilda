<div id="principal" class="clearfix">
    <form action="" method="post" id="principal">

        <h2 class="titre_principal">Création de votre compte</h2>


        <p class="titre_section">Merci d'indiquer votre e-mail et un mot de passe: </p>
        <br/>
        <div id="nouvelle_declaration" class="fond" >
            <div class="bloc_form bloc_form_condensed">               

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
            </div>
        </div> 
        <div style="margin: 10px 0; clear: both; float: right;">
            <button class="btn_vert btn_majeur " type="submit">Valider</button> 
        </div>
    </form>
</div>   
<?php slot('colReglementation'); ?>
<?php include_partial('vrac/colReglementation'); ?>
<?php end_slot(); ?>
