<?php use_helper('Date'); ?>
<?php include_partial('ds/preTemplate'); ?>
<?php include_partial('ds/breadcrum', array('etablissement' => $etablissement)); ?>

<section id="principal">
    <?php include_partial('ds/etapes', array('ds' => $ds)); ?>

    <div class="form-horizontal">

        <p>Dans cette étape, vous devez confirmer les informations ci-dessous :</p>
        <div class="row">
            <div class="col-xs-9">
                <h3 style="margin-top: 5px;">Entreprise</h3>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-3 control-label">Raison sociale</label>
                   <div class="col-sm-8">
                        <p class="form-control-static" style="padding-bottom: 0; min-height: inherit;"><?php echo $ds->declarant->raison_sociale ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-3 control-label">Famille</label>
                   <div class="col-sm-8">
                        <p class="form-control-static" style="padding-bottom: 0; min-height: inherit;"><?php echo ($ds->declarant->famille)? EtablissementFamilles::getFamilleLibelle($ds->declarant->famille) : null; ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-3 control-label">Sous famille</label>
                   <div class="col-sm-8">
                        <p class="form-control-static" style="padding-bottom: 0; min-height: inherit;"><?php echo ($ds->declarant->sous_famille)? EtablissementFamilles::getSousFamilleLibelle($ds->declarant->famille, $ds->declarant->sous_famille) : null; ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-3 control-label">CVI</label>
                   <div class="col-sm-8">
                        <p class="form-control-static" style="padding-bottom: 0; min-height: inherit;"><?php echo $ds->declarant->cvi ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-3 control-label">Adresse</label>
                   <div class="col-sm-8">
                        <p class="form-control-static" style="padding-bottom: 0; min-height: inherit;"><?php echo $ds->declarant->adresse ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-3 control-label">Code postal</label>
                   <div class="col-sm-8">
                        <p class="form-control-static" style="padding-bottom: 0; min-height: inherit;"><?php echo $ds->declarant->code_postal ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-3 control-label">Commune</label>
                   <div class="col-sm-8">
                        <p class="form-control-static" style="padding-bottom: 0; min-height: inherit;"><?php echo $ds->declarant->commune ?></p>
                   </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-9">
              <p style="margin: 10px 0 0 0;">
                <em>En cas d'erreur, merci de bien vouloir contacter votre interprofession afin de corriger les informations erronées.</em>
              </p>
            </div>
       </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-xs-6">
                <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('ds_etablissement', $etablissement) ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Étape précédente</a>
            </div>
            <div class="col-xs-6 text-right">
                <a class="btn btn-primary" href="<?php echo url_for('ds_stocks', $ds) ?>">Étape suivante&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>
        </div>
    </div>


</div>
