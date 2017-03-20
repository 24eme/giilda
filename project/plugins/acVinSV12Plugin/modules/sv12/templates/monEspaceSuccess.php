<ol class="breadcrumb">
    <li><a href="<?php echo url_for('sv12') ?>">SV12</a></li>
    <li class="active"><a href="<?php echo url_for('sv12_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->identifiant ?>)</a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
        <?php include_component('sv12', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>
</div>


<div class="row">
    <div class="col-xs-12">
        <h3>Création d'une SV12</h3>
        <form method="post" action="<?php echo url_for('sv12_etablissement', $etablissement); ?>" class="form-horizontal">
            <?php echo $formCampagne->renderGlobalErrors() ?>
            <?php echo $formCampagne->renderHiddenFields() ?>
            <div class="form-group">
                <?php echo $formCampagne['campagne']->renderLabel("Campagne", array("class" => "col-sm-2 control-label")); ?>
                <div class="col-sm-2">
                  <?php echo $formCampagne['campagne']->render(); ?>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-default" autofocus="autofocus" type="submit">Créer une SV12</button>
                </div>
            </div>
        <form>
    </div>
</div>

<?php include_partial('sv12/list', array('list' => $list)) ?>
