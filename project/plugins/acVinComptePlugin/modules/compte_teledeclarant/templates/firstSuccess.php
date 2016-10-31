<!-- #principal -->
<div id="principal" class="clearfix">
  <form action="" method="post" class="ui-tabs" name ="firstConnection">
  <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
        <h2 class="titre_principal">Premiere connexion</h2>
</div>
<div class="panel-body">
        <p class="titre_section">Afin d'accèder à la plateforme de télédéclaration, veuillez remplir les champs suivants :</p>
        <br/>
        <div id="nouvelle_declaration" class="row" >
            <div class="bloc_form bloc_form_condensed">

                <!-- #nouvelle_declaration -->
                <div class="col-xs-12">
                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>
</div>
                <div class="col-xs-12">
                    <?php echo $form['login']->renderError() ?>
                  </div>
                    <div class="col-xs-12">
                      <div class="row">
                        <div class="col-xs-offset-2 col-xs-4">
                    <?php echo $form['login']->renderLabel() ?>
                      </div>
                      <div class="col-xs-4">
                    <?php echo $form['login']->render() ?>
                      </div>
                    </div>
                  </div>
                <div class="col-xs-12">
                    <?php echo $form['mdp']->renderError() ?>
                  </div>
                  <div class="col-xs-12">
                    <div class="row">
                      <div class="col-xs-offset-2 col-xs-4">
                    <?php echo $form['mdp']->renderLabel() ?>
                  </div>
                  <div class="col-xs-4">
                    <?php echo $form['mdp']->render() ?>
                  </div>
                  </div>
                  </div>
            </div>
        </div>
        <div class="row" >
        <div class="col-xs-12 text-right">
            <button class="btn btn-success" type="submit">Valider</button>
        </div>
            </div>
          </div>
</div>
</div>
</div>
    </form>
</div>
