<?php use_helper('Date'); ?>
<?php use_helper('Float'); ?>
<?php include_partial('ds/preTemplate'); ?>
<?php include_partial('ds/breadcrum', array('etablissement' => $etablissement)); ?>

<div class="row">
    <div class="col-xs-12 formEtablissement">
        <?php include_component('ds', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>
</div>

<h1><?php echo str_replace('%millesime%', (explode('-', $date)[0]-1), DSConfiguration::getInstance()->getTitle()) ?></h1>

<div class="col-xs-12">
    <div class="row" style="margin:0;">
      <h4>
          <form class="form-inline pull-right" method="get">
              <?php echo $formPeriodes->renderGlobalErrors() ?>
              <?php echo $formPeriodes->renderHiddenFields() ?>
              Stock à fin :
              <div class="form-group<?php if($formPeriodes['date']->hasError()): ?> has-error<?php endif; ?>" style="width: 160px;">
                  <?php echo $formPeriodes['date']->renderError(); ?>
                  <?php echo $formPeriodes['date']->render(); ?>
              </div>
              <button type="submit" class="btn btn-default">Changer</button>
          </form>
        </h4>
    </div>

    <p>&nbsp;</p>

    <table  class="table table-striped table-filter table-bordered" style="border-top:none;">
    	<thead>
      		<tr>
      			<th class="col-md-10">Etat</th>
      			<th class="text-center col-md-2">&nbsp;</th>
      		</tr>
    	</thead>
    	<tbody>
        <tr>
        <?php if (!$docRepriseProduits): ?>
          <td>La saisie de votre <?php echo str_replace('%millesime%', (explode('-', $date)[0]-1), DSConfiguration::getInstance()->getTitle()) ?> est <strong>impossible</strong> car nous n'avez pas saisie votre DRM de <strong><?php echo (format_date($date, 'MMMM yyyy', 'fr_FR')) ?></strong></td>
          <td>
            <a href="<?php echo url_for('drm_mon_espace', $etablissement) ?>">Espace DRM</a>
          </td>
        <?php elseif(!$ds): ?>
          <td>Vous pouvez saisir votre <?php echo str_replace('%millesime%', (explode('-', $date)[0]-1), DSConfiguration::getInstance()->getTitle()) ?></td>
          <td>
            <a href="<?php echo url_for('ds_creation', ['identifiant' => $etablissement->identifiant, 'date' => $date]) ?>" class="btn btn-primary">Saisir les stocks</a>
          </td>
        <?php elseif($ds->isValidee()): ?>
          <td>Votre <?php echo str_replace('%millesime%', (explode('-', $date)[0]-1), DSConfiguration::getInstance()->getTitle()) ?> est <strong>validée</strong></td>
          <td>
              <a href="<?php echo url_for('ds_visualisation', $ds) ?>" class="btn btn-primary pull-right">Accéder à la déclaration&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></a>
          </td>
        <?php else: ?>
          <td>Votre <?php echo str_replace('%millesime%', (explode('-', $date)[0]-1), DSConfiguration::getInstance()->getTitle()) ?> est <strong>en cours de saisie</strong></td>
          <td>
            <a href="<?php echo url_for('ds_stocks', $ds) ?>" class="btn btn-primary pull-right">Reprendre la saisie&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></a>
          </td>
        <?php endif; ?>
        </tr>
    	</tbody>
    </table>
</div>

<?php include_partial('dae/postTemplate'); ?>
