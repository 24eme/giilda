<?php
$isCompteSociete = isset($isCompteSociete) && $isCompteSociete;
$colClass = ($isCompteSociete) ? 'col-xs-8' : 'col-xs-4';
$isSameAdresseThanSociete = !$isCompteSociete && $compteForm->getObject()->isSameAdresseThanSociete() && !$compteForm->hasErrors();
$isSameContactThanSociete = !$isCompteSociete && $compteForm->getObject()->isSameContactThanSociete() && !$compteForm->hasErrors();
$isSameExtraThanSociete = !$isCompteSociete && $compteForm->getObject() instanceof Etablissement && $compteForm->getObject()->isSameCompteThanSociete();
$isSameDroitsThanSociete = !$isCompteSociete && $compteForm->getObject()->isSameDroitsThanSociete();

?>
<div id="coordonnees_modification">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Adresse <?php if ($isSameAdresseThanSociete) : ?>&nbsp;-&nbsp;<span class="text-muted">Même adresse que la société</span><?php endif; ?></h4>
            <span class="pull-right <?php echo ($isCompteSociete) ? '' : ' clickable pointer '; echo ($isSameAdresseThanSociete) ? ' panel-collapsed ' : ' '; ?>" style="margin-top: -20px; font-size: 15px;">
                <span class="label-edit" ><?php echo ($isSameAdresseThanSociete) ? 'Editer' : 'Edition'; ?></span>&nbsp;
                <?php if (!$isCompteSociete): ?><i class="glyphicon <?php echo ($isSameAdresseThanSociete) ? ' glyphicon-chevron-down ' : 'glyphicon-chevron-up'; ?>"></i><?php endif; ?>
            </span>
        </div>
        <div class="panel-body  <?php echo ($isSameAdresseThanSociete) ? ' collapse ' : ''; ?>">
            <?php
            echo $compteForm->renderHiddenFields();
            echo $compteForm->renderGlobalErrors();
            ?>
            <div class="form-group">
                <?php echo $compteForm['adresse']->renderError(); ?>
                <?php echo $compteForm['adresse']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>

                <div class="col-xs-8"><?php echo $compteForm['adresse']->render(); ?></div>
            </div>
            <div class="form-group">

                <?php echo $compteForm['adresse_complementaire']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>

                <div class="col-xs-8"><?php echo $compteForm['adresse_complementaire']->render(); ?></div>
                <?php echo $compteForm['adresse_complementaire']->renderError(); ?>
            </div>
            <div class="form-group">
                <?php echo $compteForm['code_postal']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>
                <div class="col-xs-3"><?php echo $compteForm['code_postal']->render(); ?></div>
                <?php echo $compteForm['insee']->renderLabel(null, array('class' => 'col-xs-2 control-label')); ?>
                <div class="col-xs-3"><?php echo $compteForm['insee']->render(); ?></div>

                <?php echo $compteForm['code_postal']->renderError(); ?>
                <?php echo $compteForm['insee']->renderError(); ?>
            </div>
            <div class="form-group">

                <?php echo $compteForm['commune']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>

                <div class="col-xs-8"><?php echo $compteForm['commune']->render(); ?></div>
                <?php echo $compteForm['commune']->renderError(); ?>
            </div>
            <div class="form-group">

                <?php echo $compteForm['pays']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>

                <div class="col-xs-8"><?php echo $compteForm['pays']->render(); ?></div>
                <?php echo $compteForm['pays']->renderError(); ?>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><h4 class="panel-title">E-mail / téléphone / fax <?php if ($isSameContactThanSociete) : ?>&nbsp;-&nbsp;<span class="text-muted">Même contact que la société</span><?php endif; ?></h4>
            <span class="pull-right <?php echo ($isCompteSociete) ? '' : ' clickable pointer '; echo ($isSameContactThanSociete) ? ' panel-collapsed ' : ' '; ?>" style="margin-top: -20px; font-size: 15px;">
                <span class="label-edit" ><?php echo ($isSameContactThanSociete) ? 'Editer' : 'Edition'; ?></span>&nbsp;
                <?php if (!$isCompteSociete): ?><i class="glyphicon <?php echo ($isSameContactThanSociete) ? ' glyphicon-chevron-down ' : 'glyphicon-chevron-up'; ?>"></i><?php endif; ?>
            </span>
        </div>
        <div class="panel-body <?php echo ($isSameContactThanSociete) ? ' collapse ' : ''; ?>">
            <div class="form-group <?php if($compteForm['email']->hasError()): ?> has-error<?php endif; ?>">

                <?php echo $compteForm['email']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>

                <div class="col-xs-8"><?php echo $compteForm['email']->render(); ?></div>

                <?php echo $compteForm['email']->renderError(); ?>
            </div>
            <div class="form-group">

                <?php echo $compteForm['telephone_perso']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>

                <div class="col-xs-8"><?php echo $compteForm['telephone_perso']->render(); ?></div>
                <?php echo $compteForm['telephone_perso']->renderError(); ?>
            </div>
            <div class="form-group">

                <?php echo $compteForm['telephone_bureau']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>

                <div class="col-xs-8"><?php echo $compteForm['telephone_bureau']->render(); ?></div>
                <?php echo $compteForm['telephone_bureau']->renderError(); ?>
            </div>
            <div class="form-group">

                <?php echo $compteForm['telephone_mobile']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>

                <div class="col-xs-8"><?php echo $compteForm['telephone_mobile']->render(); ?></div>
                <?php echo $compteForm['telephone_mobile']->renderError(); ?>
            </div>
            <div class="form-group">

                <?php echo $compteForm['fax']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>

                <div class="col-xs-8"><?php echo $compteForm['fax']->render(); ?></div>
                <?php echo $compteForm['fax']->renderError(); ?>
            </div>

                <div class="form-group">

                    <?php echo $compteForm['site_internet']->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>
                    <div class="col-xs-8"><?php echo $compteForm['site_internet']->render(); ?></div>
                    <?php echo $compteForm['site_internet']->renderError(); ?>
                </div>

        </div>
    </div>

    <?php if (SocieteConfiguration::getInstance()->getExtras() && !$compteForm->getObject() instanceof Compte ): ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Champs extras<?php if ($isSameExtraThanSociete) : ?>&nbsp;-&nbsp;<span class="text-muted">Même informations que la société</span><?php endif; ?></h4>
                    <span class="pull-right <?php echo ($isCompteSociete) ? '' : ' clickable pointer '; echo ($isSameExtraThanSociete) ? ' panel-collapsed ' : ' '; ?>" style="margin-top: -20px; font-size: 15px;">
                        <span class="label-edit" ><?php echo ($isSameExtraThanSociete) ? 'Editer' : 'Edition'; ?></span>&nbsp;
                        <?php if (!$isCompteSociete): ?><i class="glyphicon <?php echo ($isSameExtraThanSociete) ? ' glyphicon-chevron-down ' : 'glyphicon-chevron-up'; ?>"></i><?php endif; ?>
                    </span>
            </div>
            <div class="panel-body <?php echo ($isSameExtraThanSociete) ? ' collapse ' : ''; ?>">
            <?php foreach(SocieteConfiguration::getInstance()->getExtras() as $k => $e): ?>
                  <?php if (!isset($e['auto']) || !$e['auto']): ?>
                    <?php if(isset($compteForm['extra_'.$k])): ?>
                      <div class="form-group">
                          <?php echo $compteForm['extra_'.$k]->renderLabel(null, array('class' => 'col-xs-4 control-label')); ?>
                          <div class="col-xs-8"><?php echo $compteForm['extra_'.$k]->render(); ?></div>
                          <?php echo $compteForm['extra_'.$k]->renderError(); ?>
                      </div>
                    <?php endif; ?>
                  <?php else: $compte = $compteForm->getObject()->getMasterCompte();
                      if ($compteForm->getObject()->exist('extras') && $compteForm->getObject()->get('extras')->exist($k)): ?>
                  <div class="form-group">
                      <label class="col-xs-4 control-label"><?php echo $e['nom']; ?></label>
                      <div class="col-xs-8"><?php echo $compteForm->getObject()->get('extras')->get($k); ?></div>
                  </div>
                  <?php endif; endif; ?>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="panel panel-default">
            <div class="panel-heading"><h4 class="panel-title">Droits<?php if ($isSameDroitsThanSociete) : ?>&nbsp;-&nbsp;<span class="text-muted">Même droits que la société</span><?php endif; ?></h4>
                <span class="pull-right <?php echo ($isCompteSociete) ? '' : ' clickable pointer '; echo ($isSameDroitsThanSociete) ? ' panel-collapsed ' : ' '; ?>" style="margin-top: -20px; font-size: 15px;">
                    <span class="label-edit" ><?php echo ($isSameDroitsThanSociete) ? 'Editer' : 'Edition'; ?></span>&nbsp;
                    <?php if (!$isCompteSociete): ?><i class="glyphicon <?php echo ($isSameDroitsThanSociete) ? ' glyphicon-chevron-down ' : 'glyphicon-chevron-up'; ?>"></i><?php endif; ?>
                </span>
            </div>
            <div class="panel-body <?php echo ($isSameDroitsThanSociete) ? ' collapse ' : ''; ?>">
                <div class="form-group">
                    <div class="col-xs-12"><?php echo $compteForm['droits']->renderError(); ?></div>
                    <?php echo $compteForm['droits']->renderLabel(null, array('class' => 'col-xs-3 control-label')); ?>
                    <div class="col-xs-9"><?php echo $compteForm['droits']->render(); ?></div>
                    <div class="col-xs-12"><?php echo $compteForm['alternative_logins']->renderError(); ?></div>
                    <?php echo $compteForm['alternative_logins']->renderLabel(null, array('class' => 'col-xs-3 control-label select2')); ?>
                    <div class="col-xs-9"><?php echo $compteForm['alternative_logins']->render(); ?></div>
                </div>
            </div>
        </div>
    </div>
