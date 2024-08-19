<?php use_helper('Compte'); ?>

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
    <li><a href="<?php echo url_for('compte_groupes'); ?>">Liste des groupes</a></li>
</ol>

<h1>La liste des groupes</h1>
<div class="row">
  <div class='col-xs-12'>
    <div class="row">
  <?php

foreach ($facets as $type => $ftype) {
  if (!count($ftype['buckets'])) {
    continue;
  }
  $cpt = 0;
  foreach ($ftype['buckets'] as $f) {
    if(!$cpt){
      echo "<div class='col-sm-3 col-xs-12'>";
    }
    echo '<a class="list-group-item list-group-item-xs" href="'.url_for('compte_groupe', array('groupeName' => str_replace('.','!',sfOutputEscaper::unescape($f['key'])))).'">'.str_replace('_', ' ', $f['key']).'<span class="badge" style="position: absolute; right: 10px;">'.$f['doc_count'].'</span></a>';
    //echo "<a href=''>".$f['key']."</a>";
    if($cpt > 10){
      echo "</div>";
    }
    if($cpt++ > 10){ $cpt = 0; }
  }
}
?>
    </div>
  </div>
  <div class="col-xs-12">
  <br/>
  <h2>Ajouter un nouveau groupe</h2>
    <form method="post" class="form-horizontal" action="<?php echo url_for('compte_groupes'); ?>">
      <?php echo $form->renderHiddenFields() ?>
      <?php echo $form->renderGlobalErrors() ?>
      <div class="col-xs-7">
        <div class="form-group <?php if($form['nom_groupe']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $form['nom_groupe']->renderError(); ?>
            <?php echo $form['nom_groupe']->render(); ?>
        </div>
      </div>
      <div class="col-xs-2">
      <button class="btn btn-default btn-md" type="submit" id="btn_rechercher">Nouveau groupe</button>
      </div>
    </form>
  </div>
</div>
