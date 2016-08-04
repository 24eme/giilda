<?php
$smallBlock = (!isset($smallBlock)) ? false : $smallBlock;
?>
<div class="list-group-item<?php echo ($compte->isSuspendu()) ? ' disabled': '' ?>">
    <div class="row">
        <?php include_partial('compte/adresseVisualisation', array('compte' => $compte, 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => $smallBlock)); ?>
    </div>
</div>
<div class="list-group-item<?php echo ($compte->isSuspendu()) ? ' disabled': '' ?>">
    <div class="row">
        <?php include_partial('compte/contactVisualisation', array('compte' => $compte, 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => $smallBlock)); ?>
    </div>
</div>
<div class="list-group-item<?php echo ($compte->isSuspendu()) ? ' disabled': '' ?>">
    <?php include_partial('compte/tagsVisualisation', array('compte' => $compte, 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => $smallBlock)); ?>

</div>
