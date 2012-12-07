                        <?php use_helper('Float'); ?>
<?php use_helper('Date'); ?>
<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <?php include_partial('header', array('revendication' => $revendication, 'actif' => 3)); ?>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_partial('revendication/editionList', array('revendications' => $revendications, 'retour' => 'odg')); ?>
        </section>
    </section>
</div>
