<!-- #principal -->
<div class="contenu">
    <form action="" method="post" id="principal" name ="firstConnection">

        <h2 class="titre_principal">Premiere connexion</h2>

        <p class="titre_section">Afin d'accèder à la plateforme de télédéclaration, veuillez remplir les champs suivants :</p>
        <br/>
        <div id="nouvelle_declaration" class="fond clearfix" >
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
                <div class="margin: 10px 0; clear: both;">
                    <button class="btn_vert btn_majeur" type="submit">Valider</button> 
                </div>

    </form>

    <?php slot('colReglementation'); ?>
<div class="bloc_col" id="col_reglementation">
    <h2>Réglementation Générale</h2>

    <div class="contenu">
        <p>
            Vous pouvez télécharger la réglementation générale des transactions au format pdf.
        </p>

        <a href="<?php echo url_for('vrac_reglementation_generale_des_transactions'); ?>" class="lien_notice">Télécharger la réglementation</a>
    </div>
</div>
<?php
end_slot();
?>
    
    
</div>


