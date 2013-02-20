<?php use_helper('Float'); ?>
<?php use_helper('Date'); ?>
<!-- #principal -->
<section id="principal">
    <?php include_partial('header', array('revendication' => $revendication, 'actif' => 2)); ?>
    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <h2>Volumes revendiqués</h2>
        <?php if(isset($revendication)): ?>
            <a class="btn_majeur btn_modifier" href="<?php echo url_for('revendication_add_row', $revendication); ?>"><span>Ajouter une ligne</span></a>
        <?php endif; ?>
        <?php include_partial('revendication/editionList', array('revendication' => $revendication, 'revendications' => $revendications, 'retour' => 'odg')); ?>
    </section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('revendication'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('revendication_view_erreurs',array('odg' => $revendication->odg, 'campagne' => $revendication->campagne)); ?>" class="btn_majeur btn_acces"><span>Retour aux erreurs</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
