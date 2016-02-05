<ol class="breadcrumb">
    <li><a href="<?php echo url_for('stocks') ?>">Page d'accueil</a></li>
    <li><a href="<?php echo url_for('stocks_etablissement', array('identifiant' => $etablissement->identifiant)) ?>" class="active"><?php echo $etablissement->nom ?></a></li>
</ol>


<div class="row">
    <div class="col-xs-12">
        <?php include_component('stocks', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>

    <div class="col-xs-12">

         <form method="post">
                <?php echo $formCampagne->renderGlobalErrors() ?>
                <?php echo $formCampagne->renderHiddenFields() ?>
                <?php echo $formCampagne; ?> <input class="btn_majeur btn_vert" type="submit" value="changer"/>
            </form>

            <?php include_partial('stocks/recap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
            <?php include_component('stocks', 'mouvements', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?> 
    </div>
</div>
