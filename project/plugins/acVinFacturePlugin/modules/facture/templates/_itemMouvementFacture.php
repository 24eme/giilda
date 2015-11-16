
<div class="form-group line mvt_ligne">                            
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-3">
                <?php echo $mvt['identifiant']->render(array('class' => 'form-control select2autocomplete input-md', 'placeholder' => 'Rechercher')); ?>
            </div>
            <div class="col-xs-2">
                <?php echo $mvt['identifiant_analytique']->renderError(); ?> 
                <?php echo $mvt['identifiant_analytique']->render(array('class' => 'form-control input-lg text-right')); ?> 
            </div>
            <div class="col-xs-3">
                <?php echo $mvt['libelle']->renderError(); ?> 
                <?php echo $mvt['libelle']->render(array('class' => 'form-control input-lg text-right')); ?>  
            </div>
            <div class="col-xs-2">
                <?php echo $mvt['quantite']->renderError(); ?> 
                <?php echo $mvt['quantite']->render(array('class' => 'form-control input-lg text-right')); ?>  
            </div>
            <div class="col-xs-2">
                <?php echo $mvt['prix_unitaire']->renderError(); ?> 
                <?php echo $mvt['prix_unitaire']->render(array('class' => 'form-control input-lg text-right')); ?>  
            </div>
            <div class="row mouvements_facture_delete_row">
                <a href="#" class="btn btn-default btn_supprimer_ligne_template" data-container="#mouvementfacture_list"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
        </div>
    </div>
</div>