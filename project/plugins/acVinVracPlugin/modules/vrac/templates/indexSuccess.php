<?php use_helper('Vrac'); ?>

<?php $etablissements = array('' => '');
$datas = EtablissementClient::getInstance()->findAll()->rows;
foreach ($datas as $data) {
    $labels = array($data->key[4], $data->key[3], $data->key[1]);
    $etablissements[$data->id] = implode(', ', array_filter($labels));
}
?>

<ol class="breadcrumb">
  <li><a href="#" class="active">Page d'accueil</a></li>
</ol>

<form action="<?php echo url_for('vrac'); ?>" method="post" class="form-inline" id="contrat_creation">
    <?php echo $creationForm->renderHiddenFields() ?>
    <?php echo $creationForm->renderGlobalErrors() ?>
    <?php echo $creationForm['annee']->renderError(); ?>
	<div class="form-group<?php if($creationForm['annee']->hasError()): ?> has-error<?php endif; ?>">
    	<?php echo $creationForm['annee']->render(array('placeholder' => 'AAAA')); ?>
  	</div>
    <?php echo $creationForm['bordereau']->renderError(); ?>
	<div class="form-group<?php if($creationForm['bordereau']->hasError()): ?> has-error<?php endif; ?>">
    	<?php echo $creationForm['bordereau']->render(array('placeholder' => 'N° bordereau')); ?>
  	</div>
  	<button type="submit" class="btn btn-default">Créer le contrat</button>
</form>

<div class="row">
    <div class="col-xs-12">
        <?php include_partial('list', array('vracs' => $vracs)); ?>
    </div>
</div>

<section id="principal">
    <?php
    //include_partial('fil_ariane', array('fil' => 0));
    ?>
    <div id="contenu_etape">
        <?php include_component('vrac', 'formEtablissementChoice') ?>
        <div class="section_label_maj"> 
            <label>10 derniers contrats saisis : </label>   
            <?php include_partial('table_contrats', array('vracs' => $vracs)); ?>
        </div>
    </div>
</section>

<?php
slot('colButtons');
include_partial('actions');
end_slot();

slot('colApplications');
?>
<div id="export_soussignes" class="bloc_col">
    <h2>Export des soussignés</h2>
        <?php
        include_partial('exportEtiquettes',array('etiquettesForm' => $etiquettesForm));
        ?>
</div>

<div id="recherche_legende" class="bloc_col">
    <h2>Légende</h2>
    <?php
    include_partial('rechercheLegende');
    ?>
</div>


<?php
end_slot();
?>