<!-- #principal -->
<div id="principal" class="clearfix">
    <form action="" method="post" class="ui-tabs" name ="firstConnection">

        <h2 class="titre_principal">Premiere connexion</h2>

        <p class="titre_section">Afin d'accèder à la plateforme de télédéclaration, veuillez remplir les champs suivants :</p>
        <br/>
        <div id="nouvelle_declaration" class="fond" >
            <div class="bloc_form bloc_form_condensed">

                <!-- #nouvelle_declaration -->

                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>

                <div class="ligne_form ligne_form_label ligne_form_alt ">
                    <?php echo $form['login']->renderError() ?>
                    <?php echo $form['login']->renderLabel() ?>
                    <?php echo $form['login']->render() ?>
                </div>
                <div class="ligne_form ligne_form_label">
                    <?php echo $form['mdp']->renderError() ?>
                    <?php echo $form['mdp']->renderLabel() ?>
                    <?php echo $form['mdp']->render() ?>
                </div>

            </div>
        </div>
        <div style="margin: 10px 0; clear: both; float: right;">
            <button class="btn_vert btn_majeur " type="submit">Valider</button> 
        </div>

    </form>
</div>
    
    


