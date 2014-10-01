<!-- #principal -->
<div id="principal" class="clearfix mdp_oublie">
    <form action="" class="ui-tabs" method="post">
        <h2 class="titre_principal">Mon compte - Mot de passe oublié</h2>

        <p class="titre_section">Afin de récuperer votre mot de passe veuillez renseigner votre identifiant.</p>
        <br/>
        <div id="nouvelle_declaration" class="fond">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>

            <div class="ligne_form">
                <?php echo $form['login']->renderError() ?>
                <?php echo $form['login']->renderLabel() ?>
                <?php echo $form['login']->render() ?>
            </div>
        </div>
        <div style="margin: 10px 0; clear: both; float: right;">
            <button class="btn_vert btn_majeur " type="submit">Valider</button> 
        </div>
    </form>
</div>
