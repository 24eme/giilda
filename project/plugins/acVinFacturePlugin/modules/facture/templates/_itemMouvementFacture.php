
<div class="form-group line mvt_ligne" >   
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-3">
                <?php echo $mvtForm['identifiant']->renderError(); ?>
                <?php echo $mvtForm['identifiant']->render(array('class' => 'form-control select2autocomplete input-md', 'placeholder' => 'Rechercher')); ?>
            </div>
            <div class="col-xs-4">
                <?php echo $mvtForm['identifiant_analytique']->renderError(); ?> 
                <?php echo $mvtForm['identifiant_analytique']->render(array('class' => 'form-control input-md text-right select2 identifiant_analytique')); ?> 
                <?php if(isset($item) && $item && $item->facture): ?>
                <input class="form-control input-md text-right" disabled="disabled" type="text" value="<?php echo $item->identifiant_analytique_libelle_compta; ?>" />
                <?php endif; ?>
            </div>
            <div class="col-xs-2">
                <?php echo $mvtForm['libelle']->renderError(); ?> 
                <?php echo $mvtForm['libelle']->render(array('class' => 'form-control input-md text-right')); ?>  
            </div>
            <div class="col-xs-1">
                <?php echo $mvtForm['prix_unitaire']->renderError(); ?> 
                <?php echo $mvtForm['prix_unitaire']->render(array('class' => 'form-control input-md text-right')); ?>  
            </div>
            <div class="col-xs-1">
                <?php echo $mvtForm['quantite']->renderError(); ?> 
                <?php echo $mvtForm['quantite']->render(array('class' => 'form-control input-md text-right')); ?>  
            </div>
            <div class="col-xs-1 row mouvements_facture_delete_row">
                <a href="#" class="btn btn-default btn_supprimer_ligne_template" data-container="#mouvementfacture_list"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
        </div>
    </div>
</div>