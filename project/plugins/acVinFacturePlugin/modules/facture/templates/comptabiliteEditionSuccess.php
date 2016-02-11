<?php use_helper('Float'); ?>
<?php use_javascript('facture.js'); ?>

<div class="col-xs-12">
    <h2>Edition des codes analytiques</h2>


    <form id="form_comptabilite_edition" action="" method="post" class="form-horizontal">

        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>       



        <?php if ($form->hasErrors()): ?>
            <div class="alert alert-danger" role="alert">
                Veuillez compléter ou corriger les erreurs
            </div>
        <?php endif; ?>

        <div class="row row-margin">
            <div class="col-xs-12" style="border-bottom: 1px dotted #d2d2d2;" >
                <div class="row">
                    <div class="col-xs-2 text-center lead text-muted">Numéro Compte Produit</div>
                    <div class="col-xs-2 text-center lead text-muted">Code identifiant analytique</div>
                    <div class="col-xs-4 text-center lead text-muted">Libellé compta</div>
                    <div class="col-xs-4 text-center lead text-muted">Libellé sur factures</div>
                </div>

            </div>
        </div>
        <?php foreach ($form->getObject()->getOrAdd('identifiants_analytiques') as $iakey => $identifiant_analytique) : ?>
           
                <div class="row row-margin"  style="border-bottom: 1px dotted #d2d2d2; padding: 5px;">
                    <div class="col-xs-2">
                        <div class="row">
                            <div class="col-xs-12"><?php echo $form['identifiant_analytique_numero_compte_' . $iakey]->renderError(); ?>  </div>
                            <div class="col-xs-12"><?php echo $form['identifiant_analytique_numero_compte_' . $iakey]->render(array('class' => 'form-control input-sm text-right')); ?>  </div>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="row">
                            <div class="col-xs-12"><?php echo $form['identifiant_analytique_' . $iakey]->renderError(); ?>  </div>
                            <div class="col-xs-12"><?php echo $form['identifiant_analytique_' . $iakey]->render(array('class' => 'form-control input-sm text-right')); ?>  </div>
                        </div>


                    </div>
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-12"><?php echo $form['identifiant_analytique_libelle_compta_' . $iakey]->renderError(); ?>  </div>
                            <div class="col-xs-12"><?php echo $form['identifiant_analytique_libelle_compta_' . $iakey]->render(array('class' => 'form-control input-sm text-right')); ?>  </div>
                        </div>

                    </div>
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-12"><?php echo $form['identifiant_analytique_libelle_' . $iakey]->renderError(); ?>  </div>
                            <div class="col-xs-12"><?php echo $form['identifiant_analytique_libelle_' . $iakey]->render(array('class' => 'form-control input-sm text-right')); ?>  </div>
                        </div>

                    </div>
                </div>
        <?php endforeach; ?>
        <br/>


        <div class="row row-margin">
            <div class="col-xs-6 text-left">
                <a class="btn btn-danger btn-lg btn-upper" href="<?php echo url_for('facture_mouvements') ?>">Annuler</a>
            </div>
            <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-success btn-lg btn-upper">Valider</button>
            </div>
        </div>

    </form>
</div>
