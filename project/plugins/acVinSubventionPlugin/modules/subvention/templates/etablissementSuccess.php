<ol class="breadcrumb">
  <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
  <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->siret ?>)</a></li>

</ol>

<section id="principal">
<?php if(!$isTeledeclarationMode): ?>
  <div class="row" id="formEtablissementChoice">
    <div class="col-xs-12">
      <?php include_component('subvention', 'formEtablissementChoice') ?>
    </div>
  </div>
<?php endif; ?>

<h2>Contrat Relance Viti</h2>
<div class="row">
    <div class="col-xs-9">
        <p>Texte explicatif</p>

        <a href="<?php echo url_for('subvention_creation',array('identifiant' => $etablissement->identifiant, 'operation' => SubventionConfiguration::getInstance()->getOperationEnCours())); ?>" class="btn btn-primary">Démarrer la demande</a>
    </div>
    <div class="col-xs-3">
        <?php include_partial('subvention/aide'); ?>
    </div>
</section>
