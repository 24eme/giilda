<!-- #principal -->
<section class="container alerte">
  <ol class="breadcrumb">
    <li><a class="active" href="<?php echo url_for('alerte'); ?>">Alertes</a></li>
  </ol>
    <?php include_partial('consultation_alertes', array('form' => $form)); ?>

    <?php include_partial('liste_alertes', array('alertesHistorique' => $alertesHistorique, 'consultationFilter' => $consultationFilter, 'page' => $page, 'nbPage' => $nbPage, 'nbResult' => $nbResult, 'modificationStatutForm' => $modificationStatutForm)); ?>
    <!-- fin #contenu_etape -->
</section>
<!-- fin #principal -->

<?php
if (sfConfig::get('app_alertes_debug', false)):
    slot('colButtons');
    ?>
    <div id="action" class="bloc_col">
        <h2>Action</h2>
        <div class="contenu">
            <?php include_partial('alerte/choose_date', array('dateForm' => $dateForm)); ?>
        </div>
    </div>
    <?php
    end_slot();
endif;
?>
