  <?php include_partial('fil_ariane', array('vrac' => $vrac, 'compte' => $compte, 'fil' => -1, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
    <?php 
    $params = array('vrac' => $vrac, 'actif' => $actif);
    if(isset($urlsoussigne)): 
       $params['urlsoussigne'] =  $urlsoussigne;
    endif;
?>
<?php include_partial('etapes', $params); ?>
<?php if (!$isTeledeclarationMode && $vrac->isTeledeclare()) : ?>
                <h2 style="text-align: center; color: red">Contrat Télédéclaré</h2>
<?php endif; ?>
