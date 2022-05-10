<?php include_partial('sv12/preTemplate'); ?>

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('sv12') ?>" class="active">SV12</a></li>
</ol>

<div class="row">
    <div class="col-xs-12" id="formEtablissementChoice">
        <?php include_component('sv12', 'formEtablissementChoice') ?>
    </div>
</div>

<?php include_partial('sv12/postTemplate'); ?>
