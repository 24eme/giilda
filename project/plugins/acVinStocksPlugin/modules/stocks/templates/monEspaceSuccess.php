<ol class="breadcrumb">
    <li><a href="<?php echo url_for('stocks') ?>">Stocks</a></li>
    <li><a href="<?php echo url_for('stocks_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->identifiant ?>)</a></li>
    <li><a href="<?php echo url_for('stocks_etablissement', array('identifiant' => $etablissement->identifiant)) ?>" class="active"><?php echo $campagne ?></a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
        <?php include_component('stocks', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>

    <div class="col-xs-12">
        <div class="row">
            <form method="post">
                <div class="col-xs-3">
                    <?php echo $formCampagne->renderGlobalErrors() ?>
                    <?php echo $formCampagne->renderHiddenFields() ?>
                    <?php echo $formCampagne; ?> 
                </div>
                <div class="col-xs-3">
                    <br/>
                    <input class="btn btn-default" type="submit" value="changer" style="margin-top: 5px;"/>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?php include_partial('stocks/recap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
                <?php // include_component('stocks', 'mouvements', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?> 
            </div>
        </div>
    </div>
</div>
